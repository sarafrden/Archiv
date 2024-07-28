<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use App\Models\UnselectedFile;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnselectedFileResource\Pages;
use App\Filament\Resources\UnselectedFileResource\RelationManagers;

class UnselectedFileResource extends Resource
{

    use Translatable;
    protected static ?string $model = UnselectedFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $form->schema([
            FileUpload::make('file_path')
                ->label('Upload File')
                ->directory('UnselectedFile'),
            Forms\Components\DatePicker::make('date')->required(),
            Forms\Components\TextInput::make('type')->required(),
            Forms\Components\TextInput::make('number')->required(),
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

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(UnselectedFile::query()->DepartmentRestricted()->orderBy('created_at', 'desc'))
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
                    ->url(fn (UnselectedFile $record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListUnselectedFiles::route('/'),
            'create' => Pages\CreateUnselectedFile::route('/create'),
            'edit' => Pages\EditUnselectedFile::route('/{record}/edit'),
        ];
    }
}
