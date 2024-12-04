<?php

namespace App\Filament\app\Resources\TrailResource\Pages;

use App\Filament\app\Resources\TrailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrail extends EditRecord
{
    protected static string $resource = TrailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
