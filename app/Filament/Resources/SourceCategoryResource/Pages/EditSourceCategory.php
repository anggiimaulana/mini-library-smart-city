<?php

namespace App\Filament\Resources\SourceCategoryResource\Pages;

use App\Filament\Resources\SourceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceCategory extends EditRecord
{
    protected static string $resource = SourceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
