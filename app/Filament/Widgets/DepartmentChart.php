<?php

namespace App\Filament\Widgets;

use App\Models\DailyReport;
use App\Models\UnselectedFile;
use App\Models\CompanyDocument;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Top File Type';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = '1';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        // Fetch data for DailyReports
        $dailyReports = DailyReport::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->limit(5)
            ->orderByDesc('count')
            ->get();

        // Fetch data for CompanyDocuments
        $companyDocuments = CompanyDocument::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->limit(5)
            ->orderByDesc('count')
            ->get();

        // Fetch data for UnselectedFiles (assuming you have a model for this)
        // Replace UnselectedFile with your actual model name and adjust the query accordingly
        $unselectedFiles = UnselectedFile::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->limit(5)
            ->orderByDesc('count')
            ->get();

        // Prepare labels and data for the chart
        $labels = $dailyReports->pluck('department_id')->toArray(); // Replace with appropriate labels
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
            'labels' => $labels,
        ];
    }
}
