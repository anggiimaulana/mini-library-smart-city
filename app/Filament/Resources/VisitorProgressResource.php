<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorProgressResource\Pages;
use App\Filament\Resources\VisitorProgressResource\RelationManagers;
use App\Models\VisitorProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitorProgressResource extends Resource
{
    protected static ?string $model = VisitorProgress::class;

    protected static ?string $label = 'Progress Management';
    protected static ?string $pluralLabel = 'Progress Management';

    protected static ?string $navigationLabel = 'Progress';

    protected static ?string $navigationGroup = 'Visitor Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListVisitorProgress::route('/'),
            'create' => Pages\CreateVisitorProgress::route('/create'),
            'edit' => Pages\EditVisitorProgress::route('/{record}/edit'),
        ];
    }
}
