<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('login', self::generateLoginFromName($state));
                    }),
                TextInput::make('login')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable()
                    ->afterStateHydrated(function (string $operation, Set $set, ?User $record) {

                        if ($operation === 'edit') {
                            return $set('password', $record?->token);
                        }
                    })
                    ->suffixAction(
                        Action::make('generate')
                            ->label('Generate')
                            ->icon('heroicon-o-key')
                            ->action(function (Set $set) {
                                $password = Str::password(8);
                                $set('password', $password);
                            })
                    ),
                Select::make('type')
                    ->required()
                    ->native(false)
                    ->options([
                        'admin' => 'Admin',
                        'employee' => 'Employee',
                    ])
                    ->default('employee'),
                Select::make('status')
                    ->required()
                    ->native(false)
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
            ]);
    }

    public static function generateLoginFromName(string $name): string
    {
        if (! $name) {
            return '';
        }

        $nameParts = str($name)->trim()->explode(' ');

        if ($nameParts->count() > 1) {
            $login = ucfirst($nameParts->first()) . '_' . strtoupper(substr($nameParts->last(), 0, 1));
        } else {
            $login = ucfirst($nameParts->first()) . '_' . strtoupper(substr(Str::random(), 0, 1));
        }

        while (
            $record = User::query()
            ->where('login', $login)
            ->first('name') and $record->name !== $name
        ) {
            $login = ucfirst($nameParts->first()) . '_´' . strtoupper(substr(Str::random(), 0, 1));
        }

        return $login;
    }
}
