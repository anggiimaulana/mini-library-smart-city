<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorResource\Pages;
use App\Filament\Resources\VisitorResource\RelationManagers;
use App\Models\Major;
use App\Models\StudyProgram;
use App\Models\Visitor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static ?string $label = 'Visitor Account Management';
    protected static ?string $pluralLabel = 'Visitor Account Management';

    protected static ?string $navigationLabel = 'Visitor Account';

    protected static ?string $navigationGroup = 'Visitor Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->dehydrated()
                    ->hiddenOn('create'),

                TextInput::make('secret_code')
                    ->label('Secret Code')
                    ->disabled()
                    ->dehydrated()
                    ->hiddenOn('create'),

                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nim')
                    ->label('NIM')
                    ->placeholder('Enter NIM')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('major_id')
                    ->label('Major')
                    ->options(Major::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive(),

                Select::make('study_program_id')
                    ->label('Study Program')
                    ->options(function (Get $get) {
                        $majorId = $get('major_id');
                        if (!$majorId) {
                            return [];
                        }
                        return StudyProgram::where('major_id', $majorId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                // Boolean pakai Toggle
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                TextInput::make('progress')
                    ->label('Progress')
                    ->default(0)
                    ->disabled(),

                TextInput::make('certificate_url')
                    ->label('Certificate URL')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('name')->label('Name'),
                TextColumn::make('nim')->label('NIM'),
                TextColumn::make('major.name')->label('Major'),
                TextColumn::make('studyProgram.name')->label('Study Program'),
                TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->alignCenter(),
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
            'index' => Pages\ListVisitors::route('/'),
            'create' => Pages\CreateVisitor::route('/create'),
            'edit' => Pages\EditVisitor::route('/{record}/edit'),
        ];
    }
}
