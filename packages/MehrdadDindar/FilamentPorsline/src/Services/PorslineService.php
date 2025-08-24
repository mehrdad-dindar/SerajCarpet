<?php

namespace MehrdadDindar\FilamentPorsline\Services;

use Filament\Notifications\Notification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use MehrdadDindar\FilamentPorsline\Models\Survey;

class PorslineService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('filament-porsline.api.api_key');
        $this->baseUrl = config('filament-porsline.api.base_url');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => config('filament-porsline.api.timeout', 30),
            'headers' => [
                'Authorization' => "API-Key {$this->apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get list of surveys
     */
    public function getSurveys(): array
    {
        try {
            $mySurveys = [];
            $folders = $this->client->get('/api/folders/');
            $foldersResponse = json_decode($folders->getBody()->getContents(), true);
            foreach ($foldersResponse as $folder) {
                $surveys = $this->client->get('/api/surveys/?folder_id=' . $folder['id']);
                $surveysResponse = json_decode($surveys->getBody()->getContents(), true);
                foreach ($surveysResponse as $survey) {
                    $mySurveys[] = [
                        'porsline_id' => $survey['id'],
                        'name' => $survey['name'],
                        'folder_id' => $folder['id'],
                        'is_active' => $survey['active'],
                        'preview_code' => $survey['preview_code'],
                        'submitted_responses' => $survey['submitted_responses'],
                        'created_date' => $survey['created_date'],
                    ];
                }
            }
            return $mySurveys;
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get survey by ID
     */
    public function getSurvey(int $surveyId): ?array
    {
        try {
            $response = $this->client->get("/api/v2/surveys/{$surveyId}/");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get survey by ID
     */
    public function deleteSurvey(int $surveyId)
    {
        try {
            $response = $this->client->delete("/api/v2/surveys/{$surveyId}/");
            if ($response->getStatusCode() == 204) {
                return true;
            }
            return false;
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new survey
     */
    public function createSurvey(array $data): ?array
    {
        try {
            $response = $this->client->post('/v2/surveys/', [
                'json' => $data,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update survey settings
     */
    public function updateSurveySettings(int $surveyId, array $settings): bool
    {
        try {
            $this->client->patch("/surveys/{$surveyId}/settings/", [
                'json' => $settings,
            ]);
            return true;
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get survey responses
     */
    public function getSurveyResponses(int $surveyId, array $params = []): array
    {
        try {
            $query = http_build_query($params);
            $response = $this->client->get("/v2/surveys/{$surveyId}/responses/results-table/?{$query}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Export survey responses
     */
    public function exportSurveyResponses(int $surveyId, string $format = 'xlsx'): ?string
    {
        try {
            $response = $this->client->get("/v2/surveys/{$surveyId}/responses/export/", [
                'query' => ['export_format' => $format],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['export'] ?? null;
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create notification
     */
    public function createNotification(int $surveyId, array $notificationData): ?array
    {
        try {
            $response = $this->client->post("/v2/surveys/{$surveyId}/notifications/", [
                'json' => $notificationData,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get folders
     */
    public function getFolders(): array
    {
        try {
            $response = $this->client->get('/folders/');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Create folder
     */
    public function createFolder(string $name): ?array
    {
        try {
            $response = $this->client->post('/folders/', [
                'json' => ['name' => $name],
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());
            return null;
        }
    }

    public function syncSurvey()
    {
        try {
            $surveys = $this->getSurveys();

            if (!empty($surveys)) {
                $newIds = collect($surveys)->pluck('porsline_id')->toArray();

                Survey::whereNotIn('porsline_id', $newIds)->delete();

                Survey::upsert(
                    $surveys,
                    ['porsline_id'],
                    ['name', 'folder_id', 'is_active', 'preview_code', 'submitted_responses', 'created_date']
                );

                Notification::make()
                    ->body('نظرسنجی‌ها با موفقیت بروز شدند!')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->body('نظرسنجی‌ای برای بروزرسانی یافت نشد!')
                    ->danger()
                    ->send();
            }
        } catch (GuzzleException $e) {
            Log::error('Porsline API Error: ' . $e->getMessage());

            Notification::make()
                ->body('خطا در دریافت نظرسنجی‌ها!')
                ->danger()
                ->send();

            return null;
        }
    }
}
