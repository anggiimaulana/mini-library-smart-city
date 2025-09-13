<?php

namespace App\Filament\Resources\ResourceResource\Pages;

use App\Filament\Resources\ResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResource extends CreateRecord
{
    protected static string $resource = ResourceResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // Pastikan file disimpan sebagai string
    //     if (is_array($data['file'])) {
    //         $data['file'] = $data['file'][0];
    //     }

    //     return $data;
    // }
}
