<?php

namespace App\Filament\Widgets;

use App\Helpers\ColorHelper;
use App\Models\Order;
use App\Models\OrderStatus;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'تحلیل سفارشات';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    public ?string $filter = 'monthly';

    protected function getData(): array
    {
        $statuses = OrderStatus::all();
        [$startDate, $endDate, $interval] = $this->getFilterParams();
        $datasets = [];

        foreach ($statuses as $status) {
            $trend = Trend::query(Order::where('status_id', $status->id))
                ->between($startDate->toCarbon(), $endDate->toCarbon())
                ->{$interval}()
                ->count();

            $datasets[] = [
                'label' => $status->label,
                'data' => $trend->map(fn ($item) => $item->aggregate),
                'backgroundColor' => ColorHelper::filamentColorToHex($status->color),
                'borderColor' => ColorHelper::filamentColorToHex($status->color),
                'borderWidth' => 1,
                'tension' => 0.5

            ];
        }
        return [
            'datasets' => $datasets,
            'labels' => $trend->map(fn ($item) => $this->formatLabel($item->date)),
        ];
    }

    private function getFilterParams(): array
    {
        return match ($this->filter) {
            'daily' => [verta(now())->subMonth(), verta(now()), 'perDay'],
            'monthly' => [verta(now())->subMonths(11), verta(now()), 'perMonth'],
            'yearly' => [verta(now())->subYears(2), verta(now()), 'perYear'],
            default => [verta(now())->subMonths(11), verta(now()), 'perMonth'],
        };
    }

    private function formatLabel(string $date): string
    {
        return match ($this->filter) {
            'daily' => verta($date)->format('F d'),
            'monthly' => verta($date)->format('F Y'),
            'yearly' => verta($date)->setDate($date, 4, 1)->format('Y'),
            default => verta($date)->format('F Y'),
        };
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'روزانه',
            'monthly' => 'ماهانه',
            'yearly' => 'سالانه',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
