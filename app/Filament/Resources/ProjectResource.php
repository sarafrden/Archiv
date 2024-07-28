<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('info')->required(),
            Forms\Components\Select::make('department_id')
                ->relationship('Department', 'name')
                ->getOptionLabelFromRecordUsing(fn (Department $record) => $record->name)
                ->preload()
                ->searchable()
                ->required()
                ->default(fn () => Auth::user()->type === 'admin' ? null : Auth::user()->department_id) // Default to user's department if not admin
                ->visible(fn () => Auth::user()->type === 'admin'), // Visible only for admin users
            Forms\Components\Hidden::make('department_id')
                ->default(fn () => Auth::user()->type === 'admin' ? null : Auth::user()->department_id)
                ->visible(fn () => Auth::user()->type !== 'admin'), // Hidden field for non-admin users

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Project::query()->DepartmentRestricted()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Project Name'),
                Tables\Columns\TextColumn::make('info')
                ->limit(50),
                Tables\Columns\TextColumn::make('department.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => Auth::user()->type === 'admin')
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
