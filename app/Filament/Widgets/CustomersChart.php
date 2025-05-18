<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'رشد مشتریان';

    public function getFilters(): ?array
    {
        return [
            'daily' => 'روزانه',
            'weekly' => 'هفتگی',
            'monthly' => 'ماهانه',
            'yearly' => 'سالانه',
        ];
    }
    protected function getData(): array
    {
        $filter = $this->filter ?? 'daily';

        [$groupFormat, $labelFormat] = match ($filter) {
            'weekly' => ['%x-%v', 'هفته %V %Y'],
            'monthly' => ['%Y-%m', '%B %Y'],
            'yearly' => ['%Y', '%Y'],
            default => ['%Y-%m-%d', '%d %B'],
        };

        $dates = Customer::query()
            ->selectRaw("DATE_FORMAT(created_at, '$groupFormat') as period")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('period')
            ->toArray();

        $data = Customer::query()
            ->selectRaw("DATE_FORMAT(created_at, '$groupFormat') as period, COUNT(*) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period');

        $labels = collect($dates)->map(function ($date) use ($labelFormat, $filter) {
            if ($filter === 'weekly') {
                [$year, $week] = explode('-', $date);
                $carbon = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
                return verta($carbon)->format($labelFormat);
            }

            return verta($date)->format($labelFormat);
        });

        return [
            'datasets' => [
                [
                    'label' => 'تعداد مشتریان جدید',
                    'data' => collect($dates)->map(fn ($d) => $data[$d] ?? 0),
                    'borderColor' => 'rgb(59,130,246)',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                    'fill' => false,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
