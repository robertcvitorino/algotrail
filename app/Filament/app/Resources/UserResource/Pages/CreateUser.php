<?php

namespace App\Filament\app\Resources\UserResource\Pages;

use App\Filament\app\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
