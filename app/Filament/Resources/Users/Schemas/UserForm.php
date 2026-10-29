<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enum\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('user.field.first_name'))
                            ->maxLength(100)
                            ->required(),

                        TextInput::make('last_name')
                            ->label(__('user.field.last_name'))
                            ->maxLength(100)
                            ->required(),

                        TextInput::make('email')
                            ->label(__('user.field.email'))
                            ->unique(ignoreRecord: true)
                            ->maxLength(200)
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label(__('user.field.phone'))
                            ->maxLength(14)
                            ->tel()
                            ->required(),
                    ]),

                Section::make()
                    ->columns()
                    ->schema([
                        Select::make('role')
                            ->label(__('user.field.role'))
                            ->options(UserRole::options())
                            ->enum(UserRole::class)
                            ->live()
                            ->required(),

                        Select::make('organisation_id')
                            ->relationship('organisation', 'name', fn (Builder $query) => $query->whereActive())
                            ->label(__('user.field.organisation'))
                            ->visible(fn (callable $get) => UserRole::ORG_ADMIN->is($get('role')))
                            ->searchable()
                            ->required(),
                    ])
                    ->visible(
                        fn () => auth()->user()->isPlatformAdmin(),
                    ),
            ]);
    }
}
