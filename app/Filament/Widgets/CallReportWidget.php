<?php

namespace App\Filament\Widgets;

use App\Models\CallLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CallReportWidget extends ChartWidget
{
    protected static ?string $heading = 'تحلیل تماس‌ها (۳۰ روز اخیر)';
    protected static ?int $sort = 4;
    public $dateRange = 30;
    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        return [
            'all'      => 'همه تماس‌ها',
            'inbound'  => 'ورودی',
            'outbound' => 'خروجی',
            'missed'   => 'بی‌پاسخ (Missed)',
        ];
    }

    protected function getData(): array
    {
        $query = CallLog::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->where('created_at', '>=', Carbon::now()->subDays($this->dateRange));

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $calls = $query->pluck('count', 'date')->toArray();

        // ایجاد بازه روزانه ۳۰ روز اخیر با تاریخ شمسی
        $labels = [];
        $data = [];

        for ($i = $this->dateRange; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = verta($date)->format('d F');
            $data[] = $calls[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'تعداد تماس‌ها',
                    'data'            => $data,
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
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
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
