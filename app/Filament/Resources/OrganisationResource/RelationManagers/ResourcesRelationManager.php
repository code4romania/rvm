<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationResource\RelationManagers;

use App\Models\County;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'resources';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('category')->options([
                    'adapost' => 'Adăpostire',
                    'transport' => 'Transport',
                    'salvare' => 'Salvare',
                    'telecomunicatii' => 'Telecomunicații',
                    'it_c' => 'IT&C',
                    'other' => 'Altele',
                ])->required(),

                Select::make('subcategory')
                    ->label('Subcategory')
                    ->required()
                    ->options(
                        fn (callable $get) =>match ($get('category')) {
                            'adapost' =>['Corturi','Rulote','Cazare','Altele'],
                            'transport' =>['Rutier','Maritim','Feroviar','Aerian','Altele'],
                            'salvare' =>['Câini utilitari','Altele'],
                            'telecomunicatii' =>['Radiocomunicații','Televiziune','Radiodifuziune','Altele'],
                            'it_c' =>['Hardware','Software','Altele'],
                            default =>['Altele'],
                        }
                    )
                    ->hidden(function (callable $get) {
                        return $get('category') === 'altele';
                    })
                    ->searchable()
                    ->reactive(),
                TextInput::make('subcategory_other')
                    ->label('Subcategory other')
                    ->hidden(function (callable $get) {
                        return $get('category') !== 'altele';
                    })
                    ->maxLength(255),
                TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Textarea::make('description')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('has_transport')
                    ->options([
                        'heroicon-o-x-circle',
                        'heroicon-o-x' => 'false',
                        'heroicon-o-check' => 'true',

                    ])
                    ->colors([
                        'secondary',
                        'danger' => 'false',
                        'success' => 'true',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->modalHeading(__('organisation.modal.heading'))
                    ->modalSubheading(__('organisation.modal.subheading'))
                    ->modalWidth('4xl')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
