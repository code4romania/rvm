<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Organisations\RelationManagers\UsersRelationManager;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
                    ->columns()
                    ->schema([
                        TextEntry::make('first_name')
                            ->label(__('user.field.first_name')),

                        TextEntry::make('last_name')
                            ->label(__('user.field.last_name')),

                        TextEntry::make('email')
                            ->label(__('user.field.email')),

                        TextEntry::make('phone')
                            ->label(__('user.field.phone')),
                    ]),

                Section::make()
                    ->heading('')
                    ->columns()
                    ->hiddenOn(UsersRelationManager::class)
                    ->schema([
                        TextEntry::make('role')
                            ->label(__('user.field.role')),

                        TextEntry::make('organisation.name')
                            ->label(__('user.field.organisation')),
                    ])
                    ->visible(
                        fn () => auth()->user()->isPlatformAdmin(),
                    ),
            ]);
    }
}
