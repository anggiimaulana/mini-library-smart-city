<?php

namespace App\Filament\Resources\VisitorProgressResource\Pages;

use App\Filament\Resources\VisitorProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitorProgress extends EditRecord
{
    protected static string $resource = VisitorProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
