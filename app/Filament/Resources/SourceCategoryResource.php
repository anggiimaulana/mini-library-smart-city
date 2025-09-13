<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SourceCategoryResource\Pages;
use App\Filament\Resources\SourceCategoryResource\RelationManagers;
use App\Models\SourceCategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SourceCategoryResource extends Resource
{
    protected static ?string $model = SourceCategory::class;

    protected static ?string $label = 'Source Category';
    protected static ?string $pluralLabel = 'Source Category';

    protected static ?string $navigationLabel = 'Source Category';

    protected static ?string $navigationGroup = 'Source Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('name')->label('Name'),
                TextColumn::make('created_at')->label('Created At'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSourceCategories::route('/'),
            'create' => Pages\CreateSourceCategory::route('/create'),
            'edit' => Pages\EditSourceCategory::route('/{record}/edit'),
        ];
    }
}
