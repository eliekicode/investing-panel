<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

use function Livewire\str;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return str($this->record->name)->headline();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        if ($this->record->token !== $data['password']) {
            $data['token'] = $data['password'];
        }

        return parent::mutateFormDataBeforeSave($data);
    }

    protected function getRedirectUrl(): ?string
    {
        return self::$resource::getUrl('index');
    }
}
