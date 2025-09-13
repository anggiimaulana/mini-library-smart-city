<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceResource\Pages;
use App\Filament\Resources\ResourceResource\RelationManagers;
use App\Models\Content;
use App\Models\Resources as ModelResource;
use App\Models\SourceCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResourceResource extends Resource
{
    protected static ?string $model = ModelResource::class;

    protected static ?string $label = 'Resource Management';
    protected static ?string $pluralLabel = 'Resource Management';

    protected static ?string $navigationLabel = 'Resource';

    protected static ?string $navigationGroup = 'Source Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required(),
                Select::make('content_id')
                    ->label('Content')
                    ->options(Content::pluck('title', 'id'))
                    ->searchable(),
                TextInput::make('author')
                    ->label('Author')
                    ->placeholder('Enter author')
                    ->required(),
                TextInput::make('year')
                    ->label('Year')
                    ->placeholder('Enter year')
                    ->required(),
                Select::make('source_category_id')
                    ->label('Source Category')
                    ->options(SourceCategory::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('link')
                    ->label('Url Source')
                    ->placeholder('Enter link source')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('title')->label('Title'),
                TextColumn::make('content.title')->label('Content'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }
}
