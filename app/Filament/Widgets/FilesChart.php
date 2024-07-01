<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DailyReport;
use App\Models\CompanyDocument;
use App\Models\UnselectedFile;
use Illuminate\Support\Facades\DB;

class FilesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected int | string | array $columnSpan = '1';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Fetch top DailyReport counts grouped by department
        $dailyReports = DailyReport::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Fetch top CompanyDocument counts grouped by department
        $companyDocuments = CompanyDocument::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Fetch top UnselectedFile counts grouped by department
        $unselectedFiles = UnselectedFile::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare labels and data for the area chart
        $labels = [
            'Daily Reports' => $dailyReports->map(function ($item) {
                return date('M Y', strtotime($item->year . '-' . $item->month . '-01'));
            })->toArray(),
            'Company Documents' => $companyDocuments->map(function ($item) {
                return date('M Y', strtotime($item->year . '-' . $item->month . '-01'));
            })->toArray(),
            'Unselected Files' => $unselectedFiles->map(function ($item) {
                return date('M Y', strtotime($item->year . '-' . $item->month . '-01'));
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
                    'label' => 'Daily Reports',
                    'data' => $data['Daily Reports'],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
                [
                    'label' => 'Company Documents',
                    'data' => $data['Company Documents'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
                [
                    'label' => 'Unselected Files',
                    'data' => $data['Unselected Files'],
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                ],
            ],
            'labels' => array_unique(array_merge($labels['Daily Reports'], $labels['Company Documents'], $labels['Unselected Files'])),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Use 'line' for area chart
    }
}
