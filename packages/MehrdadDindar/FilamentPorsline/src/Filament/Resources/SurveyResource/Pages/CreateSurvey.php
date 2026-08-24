<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    //    protected function mutateFormDataBeforeCreate(array $data): array
    //    {
    //        $porslineService = app(PorslineService::class);
    //
    //        $surveyData = [
    //            'name' => $data['name'],
    //            'language' => $data['language'],
    //            'folder_id' => $data['folder_id'] ?? null,
    //        ];
    //
    //        $porslineSurvey = $porslineService->createSurvey($surveyData);
    //
    //        if ($porslineSurvey) {
    //            $data['porsline_id'] = $porslineSurvey['id'];
    //            $data['preview_code'] = $porslineSurvey['preview_code'];
    //            $data['report_code'] = $porslineSurvey['report_code'];
    //            $data['url_slug'] = $porslineSurvey['url_slug'] ?? null;
    //            $data['is_active'] = $porslineSurvey['active'] ?? true;
    //            $data['is_stopped'] = $porslineSurvey['is_stopped'] ?? false;
    //            $data['views'] = $porslineSurvey['views'] ?? 0;
    //            $data['submitted_responses'] = $porslineSurvey['submitted_responses'] ?? 0;
    //            $data['question_count'] = $porslineSurvey['question_count'] ?? 0;
    //            $data['is_template'] = $porslineSurvey['is_template'] ?? false;
    //            $data['settings'] = $porslineSurvey['settings'] ?? [];
    //            $data['created_date'] = $porslineSurvey['created_date'] ?? now();
    //        }
    //
    //        return $data;
    //    }
}
