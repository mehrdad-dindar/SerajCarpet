<?php

namespace App\Filament\Widgets;

use App\Models\CallLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CallReportWidget extends ChartWidget
{
    protected static ?string $heading = 'تعداد تماس‌ها (30 روز اخیر)';
    public $dateRange = 30;
    public ?string $filter = 'all';
    protected function getFilters(): ?array
    {
        return [
            'all' => 'همه تماس‌ها',
            'incoming' => 'ورودی',
            'outgoing' => 'خروجی',
        ];
    }

    protected function getData(): array
    {
        $query = CallLog::selectRaw('DATE(timestamp) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->where('timestamp', '>=', Carbon::now()->subDays($this->dateRange));

        if ($this->filter !== 'all') {
            $query->where('call_type', $this->filter);
        }

        $calls = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'تعداد تماس‌ها',
                    'data' => $calls->pluck('count')->toArray(),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.2)',
                ],
            ],
            'labels' => $calls->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
