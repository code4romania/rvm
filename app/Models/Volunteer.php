<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\VolunteerRole;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'email',
        'phone',
        'birthday',
        'county_id',
        'city_id',
        'specialization',
        'cnp',
        'accreditation',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function getTableColumns(): array
    {
        return [

            TextColumn::make('name')
                ->label(__('volunteer.fields.name')),
            TextColumn::make('email')
                ->label(__('volunteer.fields.email')),
            TextColumn::make('phone')
                ->label(__('volunteer.fields.phone')),
            TextColumn::make('specialization')
                ->label(__('volunteer.fields.specialization')),
            ToggleColumn::make('accreditation')
                ->label(__('volunteer.fields.accreditation')),

        ];
    }

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('last_name')
                ->label(__('volunteer.fields.last_name'))
                ->maxLength(255)
                ->required(),
            TextInput::make('first_name')
                ->label(__('volunteer.fields.first_name'))
                ->maxLength(255)
                ->required(),
            DatePicker::make('birthday')
                ->label(__('volunteer.fields.birthday'))
                ->required(),
            TextInput::make('cnp')
                ->label(__('volunteer.fields.cnp')),
            TextInput::make('email')
                ->label(__('volunteer.fields.email'))
                ->email(),

            TextInput::make('phone')
                ->label(__('volunteer.fields.phone')),
            TextInput::make('specialization')
                ->label(__('volunteer.fields.specialization')),
            Select::make('role')
                ->label(__('volunteer.fields.role'))
                ->options(VolunteerRole::options())
                ->required(),
            Toggle::make('accreditation')
                ->label(__('volunteer.fields.accreditation')),
            Section::make(__('Localizare'))
                ->schema([

                    Select::make('county_id')
                        ->label(__('general.county'))
                        ->options(County::pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label(__('general.city'))
                        ->required()
                        ->options(
                            fn (callable $get) => County::find($get('county_id'))
                                ?->cities
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->reactive(),
                ]),
        ];
    }
}
