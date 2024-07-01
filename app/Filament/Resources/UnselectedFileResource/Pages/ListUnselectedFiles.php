<?php

namespace App\Filament\Resources\UnselectedFileResource\Pages;

use App\Filament\Resources\UnselectedFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnselectedFiles extends ListRecords
{
    protected static string $resource = UnselectedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
