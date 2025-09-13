<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
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

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $label = 'Content Management';
    protected static ?string $pluralLabel = 'Content Management';

    protected static ?string $navigationLabel = 'Content';

    protected static ?string $navigationGroup = 'Source Management';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->dehydrated()
                    ->hiddenOn('create')
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('content')
                    ->visibility('public')
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Enter description')
                    ->rows(5)
                    ->required(),
                Textarea::make('challenge_solution')
                    ->label('Challenge & Solution')
                    ->placeholder('Enter challenge & solution')
                    ->rows(5)
                    ->required(),
                Textarea::make('technology')
                    ->label('Technology')
                    ->placeholder('Enter technology')
                    ->rows(3)
                    ->required(),
                Textarea::make('implementation')
                    ->label('Implementation Example')
                    ->placeholder('Enter implementation example')
                    ->rows(3)
                    ->required(),
                Select::make('order')
                    ->label('Order')
                    ->searchable()
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('title')->label('Title'),
                TextColumn::make('order')->label('Order'),
                TextColumn::make('created_at')->label('Created At'),
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
