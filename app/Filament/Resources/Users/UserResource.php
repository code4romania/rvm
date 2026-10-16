<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Enum\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Tables\Actions\ExportAction;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return __('user.label.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('user.label.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('')
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
                    ->heading('')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('first_name')
                    ->label(__('user.field.first_name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('last_name')
                    ->label(__('user.field.last_name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->label(__('user.field.email'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('role')
                    ->label(__('user.field.role'))
                    ->description(function (User $record) {
                        if (
                            ! $record->belongsToOrganisation() ||
                            auth()->user()->belongsToOrganisation()
                        ) {
                            return null;
                        }

                        return $record->organisation->name;
                    })
                    ->sortable()
                    ->toggleable(),

            ])
            ->filters([
                SelectFilter::make('role')
                    ->multiple()
                    ->label(__('user.field.role'))
                    ->options(UserRole::options())
                    ->hidden(fn () => auth()->user()->belongsToOrganisation()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ])
            ->headerActions([
                ExportAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
