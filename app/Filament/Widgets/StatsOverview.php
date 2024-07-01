<?php

namespace App\Filament\Widgets;

use App\Models\DailyReport;
use App\Models\Department;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make( 'Total Departments : ' ,  number_format(Department::count()))
                ->descriptionIcon('heroicon-o-rectangle-group')
                ->color('primary'),
            Stat::make('Total Users : ' ,  number_format(User::count()))
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('primary'),
            Stat::make('Total Documents : ' ,  number_format(DailyReport::count()))
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('primary'),
        ];
    }
}
