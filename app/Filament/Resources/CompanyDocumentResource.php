<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyDocumentResource\Pages;
use App\Filament\Resources\CompanyDocumentResource\RelationManagers;
use App\Models\CompanyDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Department;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Concerns\Translatable;

class CompanyDocumentResource extends Resource
{

    use Translatable;
    protected static ?string $model = CompanyDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $form->schema([
            FileUpload::make('file_path')
                ->label('Upload File')
                ->directory('CompanyDocument'),
            Forms\Components\DatePicker::make('date')->required(),
            Forms\Components\TextInput::make('type')->required(),
            Forms\Components\TextInput::make('number')->required(),
            Forms\Components\Select::make('department_id')
                ->relationship('Department', 'name')
                ->getOptionLabelFromRecordUsing(fn (Department $record) => $record->name)
                ->preload()
                ->searchable()
                ->required(),
        ]);

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->searchable()
                    ->date('Y/m/d'),
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('Y/m/d'),
            ])
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('Department', 'name')
                    ->options(Department::all()->pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('file_path')
                    ->label('File')
                    ->color('success')
                    ->icon('heroicon-s-eye')
                    ->url(fn (CompanyDocument $record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanyDocuments::route('/'),
            'create' => Pages\CreateCompanyDocument::route('/create'),
            'edit' => Pages\EditCompanyDocument::route('/{record}/edit'),
        ];
    }
}
