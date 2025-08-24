<?php

namespace MehrdadDindar\FilamentPorsline\Console\Commands;

use Illuminate\Console\Command;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;

class TestPorslineConnection extends Command
{
    protected $signature = 'porsline:test-connection';

    protected $description = 'Test connection to Porsline API';

    public function handle(PorslineService $porslineService): int
    {
        $this->info('Testing Porsline API connection...');

        try {
            $surveys = $porslineService->getSurveys();
            
            if (empty($surveys)) {
                $this->warn('No surveys found. This might be normal if you haven\'t created any surveys yet.');
            } else {
                $this->info('Connection successful! Found ' . count($surveys) . ' surveys.');
                
                $this->table(
                    ['ID', 'Name', 'Language', 'Responses'],
                    collect($surveys)->take(5)->map(function ($survey) {
                        return [
                            $survey['id'] ?? 'N/A',
                            $survey['name'] ?? 'N/A',
                            $survey['language'] ?? 'N/A',
                            $survey['submitted_responses'] ?? 'N/A',
                        ];
                    })
                );
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Connection failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
