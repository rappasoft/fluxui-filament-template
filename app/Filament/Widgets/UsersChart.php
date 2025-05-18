<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'New Users This Year';

    protected static ?string $maxHeight = '120px';

    protected int|string|array $columnSpan = 2;

    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
            'last-year' => 'Last year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $start = now()->startOfYear();
        $end = now()->endOfYear();
        $per = 'Month';

        if ($activeFilter === 'today') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $per = 'Hour';
        } elseif ($activeFilter === 'week') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
            $per = 'Day';
        } elseif ($activeFilter === 'month') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $per = 'Week';
        } elseif ($activeFilter === 'last-year') {
            $start = now()->subYear()->startOfYear();
            $end = now()->subYear()->endOfYear();
        }

        $data = Trend::model(User::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->dateAlias('period')
            ->dateColumn('created_at')
            ->{'per'.$per}()
            ->count();

        return [
            'datasets' => [
                [
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min' => 0,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
