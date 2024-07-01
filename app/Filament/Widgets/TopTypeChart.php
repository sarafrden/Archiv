<?php

namespace App\Filament\Widgets;

use App\Models\DailyReport;
use App\Models\UnselectedFile;
use App\Models\CompanyDocument;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Top Department Files';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        // Fetch top DailyReport counts grouped by department
        $dailyReports = DailyReport::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->orderByDesc('count')
            ->with('department') // Assuming there's a 'department' relationship
            ->get();

        // Fetch top CompanyDocument counts grouped by department
        $companyDocuments = CompanyDocument::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->orderByDesc('count')
            ->with('department') // Assuming there's a 'department' relationship
            ->get();

        // Fetch top UnselectedFile counts grouped by department
        $unselectedFiles = UnselectedFile::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->orderByDesc('count')
            ->with('department') // Assuming there's a 'department' relationship
            ->get();

        // Prepare labels and data for the chart
        $labels = [
            'Daily Reports' => $dailyReports->map(function ($item) {
                return $item->department->name;
            })->toArray(),
            'Company Documents' => $companyDocuments->map(function ($item) {
                return $item->department->name;
            })->toArray(),
            'Unselected Files' => $unselectedFiles->map(function ($item) {
                return $item->department->name;
            })->toArray(),
        ];

        $data = [
            'Daily Reports' => $dailyReports->pluck('count')->toArray(),
            'Company Documents' => $companyDocuments->pluck('count')->toArray(),
            'Unselected Files' => $unselectedFiles->pluck('count')->toArray(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Files Count',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                    ],
                ],
            ],
            'labels' => array_merge($labels['Daily Reports']),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
