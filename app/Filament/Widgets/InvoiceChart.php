<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Hekmatinasser\Verta\Verta;

class InvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'گزارش صورتحساب‌ها';
    protected static ?int $sort = 3;


    protected function getData(): array
    {
        $filter = $this->filter ?? 'daily';

        $statuses = ['paid', 'pending', 'canceled'];

        [$groupFormat, $labelFormat] = match ($filter) {
            'weekly' => ['%x-%v', 'هفته %V %Y'],
            'monthly' => ['%Y-%m', '%B %Y'],
            'yearly' => ['%Y', '%Y'],
            default => ['%Y-%m-%d', '%d %B'],
        };

        $dates = Invoice::query()
            ->selectRaw("DATE_FORMAT(created_at, '$groupFormat') as period")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('period')
            ->toArray();

        $datasets = [];

        foreach ($statuses as $status) {
            $data = Invoice::query()
                ->selectRaw("DATE_FORMAT(created_at, '$groupFormat') as period, COUNT(*) as total")
                ->where('status', $status)
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');

            $datasets[] = [
                'label' => __('invoice.status.' . $status),
                'data' => collect($dates)->map(fn ($d) => $data[$d] ?? 0),
                'borderColor' => $this->getStatusColor($status),
                'backgroundColor' => $this->getStatusColor($status, 0.2),
                'fill' => false,
            ];
        }

        $labels = collect($dates)->map(function ($date) use ($labelFormat, $filter) {
            if ($filter === 'weekly') {
                [$year, $week] = explode('-', $date);
                $carbonDate = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
                return verta($carbonDate)->format($labelFormat);
            }

            return (new Verta($date))->format($labelFormat);
        });

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    private function getStatusColor(string $status, float $opacity = 1): string
    {
        return match ($status) {
            'paid' => "rgba(34,197,94,$opacity)",     // سبز
            'pending' => "rgba(251,191,36,$opacity)", // زرد
            'canceled' => "rgba(239,68,68,$opacity)", // قرمز
            default => "rgba(107,114,128,$opacity)",  // خاکستری
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'روزانه',
            'weekly' => 'هفتگی',
            'monthly' => 'ماهانه',
            'yearly' => 'سالانه',
        ];
    }
}
