<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource\Pages;

use Filament\Resources\Pages\Page;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource;
use MehrdadDindar\FilamentPorsline\Models\Survey;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

class ViewSurveyResponses extends Page
{
    protected static string $resource = SurveyResource::class;

    protected static string $view = 'filament-porsline::pages.view-survey-responses';

    public Survey $record;

    public array $headers = [];
    public array $rows = [];
    public int $respondersCount = 0;

    public function mount(Survey $record): void
    {
        $this->record = $record;
        $service = new PorslineService();
        $data = $service->getSurveyResponses($record->porsline_id, [
            'page' => request()->get('page', 1),
            'page_size' => 10,
        ]);

        $this->headers = $data['headers'] ?? [];
        $this->rows = $data['body'] ?? [];
        $this->respondersCount = $data['responders_count'] ?? 0;
    }

    public function getTableData(): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $pageSize = 10;

        $collection = collect($this->rows);

        return new Paginator(
            $collection->forPage($page, $pageSize)->values(),
            $this->respondersCount,
            $pageSize,
            $page,
            ['path' => request()->url()]
        );
    }

}
