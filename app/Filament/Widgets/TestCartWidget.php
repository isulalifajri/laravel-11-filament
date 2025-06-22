<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\TrendValue;

class TestCartWidget extends ChartWidget
{
    protected static ?string $heading = 'Test Chart';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {

         $data = Trend::model(User::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

            // dd($data);
        return [

            'datasets' => [
                [
                    'label' => 'Blog Posts Created',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),


            // 'datasets' => [
            //     [
            //         'label' => 'Blog Posts Created',
            //         'data' => [600,365,450],
            //         'backgroundColor' => [
            //             'rgb(255,99,132)',
            //             'rgb(54,162,235)',
            //             'rgb(255,205,86)',
            //         ],
            //     ],
            // ],
            // 'labels' => ['A','B','C'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
        // return 'doughnut';
    }
}
