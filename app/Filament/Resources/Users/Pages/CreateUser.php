<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['token'] = $data['password'];

        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function getRedirectUrl(): string
    {
        return self::$resource::getUrl('index');
    }
}
