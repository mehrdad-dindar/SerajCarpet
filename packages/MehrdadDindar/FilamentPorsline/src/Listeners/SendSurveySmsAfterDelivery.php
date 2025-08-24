<?php

namespace MehrdadDindar\FilamentPorsline\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderLogCreated;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use MehrdadDindar\FilamentPorsline\Jobs\SendSurveySmsJob;
use MehrdadDindar\FilamentPorsline\Models\Survey;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;

class SendSurveySmsAfterDelivery implements ShouldQueue
{
    public function __construct(
        protected PorslineService $porslineService
    ) {
    }

    public function handle(OrderLogCreated $event): void
    {
        $order = $event->activity->subject;
        
        if (!$order instanceof Order) {
            return;
        }

        // Check if order status changed to delivered
        if ($order->status->name !== OrderStatus::DELIVERED_AND_PAID) {
            return;
        }

        // Check if SMS is enabled
        if (!config('filament-porsline.sms.enabled', true)) {
            return;
        }

        // Get or create survey
        $survey = $this->getOrCreateSurvey();
        if (!$survey) {
            return;
        }

        // Schedule SMS for delay days
        $delayDays = config('filament-porsline.sms.delay_days', 2);
        $delayTime = Carbon::now()->addDays($delayDays);

        SendSurveySmsJob::dispatch(
            $order->customer->phone,
            $survey,
            $order->customer->name ?? ''
        )->delay($delayTime);
    }

    protected function getOrCreateSurvey(): ?Survey
    {
        // Try to find existing active survey
        $survey = Survey::active()->notStopped()->first();
        
        if ($survey) {
            return $survey;
        }

        // Create new survey if auto_create_survey is enabled
        if (!config('filament-porsline.survey.auto_create_survey', true)) {
            return null;
        }

        $template = config('filament-porsline.survey.survey_template');
        
        $surveyData = [
            'name' => $template['name'],
            'language' => config('filament-porsline.survey.default_language', 2),
            'folder_id' => config('filament-porsline.survey.default_folder_id'),
        ];

        $porslineSurvey = $this->porslineService->createSurvey($surveyData);
        
        if (!$porslineSurvey) {
            return null;
        }

        return Survey::create([
            'porsline_id' => $porslineSurvey['id'],
            'name' => $porslineSurvey['name'],
            'description' => $template['description'] ?? '',
            'language' => $porslineSurvey['language'],
            'folder_id' => $porslineSurvey['folder_id'] ?? null,
            'preview_code' => $porslineSurvey['preview_code'],
            'report_code' => $porslineSurvey['report_code'],
            'url_slug' => $porslineSurvey['url_slug'] ?? null,
            'is_active' => $porslineSurvey['active'] ?? true,
            'is_stopped' => $porslineSurvey['is_stopped'] ?? false,
            'views' => $porslineSurvey['views'] ?? 0,
            'submitted_responses' => $porslineSurvey['submitted_responses'] ?? 0,
            'question_count' => $porslineSurvey['question_count'] ?? 0,
            'is_template' => $porslineSurvey['is_template'] ?? false,
            'settings' => $porslineSurvey['settings'] ?? [],
            'created_date' => $porslineSurvey['created_date'] ?? now(),
        ]);
    }
} 