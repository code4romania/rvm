<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public static string $defaultDateDisplayFormat = 'd.m.Y';

    public static string $defaultDateTimeDisplayFormat = 'd.m.Y H:i';

    public static string $defaultDateTimeWithSecondsDisplayFormat = 'd.m.Y H:i:s';

    public static string $defaultTimeDisplayFormat = 'H:i';

    public static string $defaultTimeWithSecondsDisplayFormat = 'H:i:s';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->setDateTimeDisplayFormats();

        $this->configureActions();
        $this->configureForms();
        $this->configureFilters();
        $this->configureInfolists();
        $this->configurePages();
        $this->configureTables();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    protected function configureActions(): void
    {
        //
    }

    protected function configureForms(): void
    {
        Textarea::configureUsing(function (Textarea $component) {
            $component->autosize();
        });
    }

    protected function configureFilters(): void
    {
        //
    }

    protected function configureInfolists(): void
    {
        TextEntry::configureUsing(function (TextEntry $entry) {
            return $entry->placeholder('—');
        });
    }

    protected function configurePages(): void
    {
        //
    }

    protected function configureTables(): void
    {
        Column::configureUsing(function (Column $column) {
            return $column->placeholder('—');
        });
    }

    protected function setDateTimeDisplayFormats(): void
    {
        Table::configureUsing(fn (Table $table) => $table->defaultDateDisplayFormat(static::$defaultDateDisplayFormat));
        Table::configureUsing(fn (Table $table) => $table->defaultDateTimeDisplayFormat(static::$defaultDateTimeDisplayFormat));
        Table::configureUsing(fn (Table $table) => $table->defaultTimeDisplayFormat(static::$defaultTimeDisplayFormat));

        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateDisplayFormat(static::$defaultDateDisplayFormat));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateTimeDisplayFormat(static::$defaultDateTimeDisplayFormat));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultTimeDisplayFormat(static::$defaultTimeDisplayFormat));

        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateDisplayFormat(static::$defaultDateDisplayFormat));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeDisplayFormat(static::$defaultDateTimeDisplayFormat));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeWithSecondsDisplayFormat(static::$defaultDateTimeWithSecondsDisplayFormat));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeDisplayFormat(static::$defaultTimeDisplayFormat));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeWithSecondsDisplayFormat(static::$defaultTimeWithSecondsDisplayFormat));

        Carbon::macro('toFormattedDate', fn () => $this->translatedFormat(FilamentServiceProvider::$defaultDateDisplayFormat));
        Carbon::macro('toFormattedDateTime', fn () => $this->translatedFormat(FilamentServiceProvider::$defaultDateTimeDisplayFormat));
        Carbon::macro('toFormattedDateTimeWithSeconds', fn () => $this->translatedFormat(FilamentServiceProvider::$defaultDateTimeWithSecondsDisplayFormat));
        Carbon::macro('toFormattedTime', fn () => $this->translatedFormat(FilamentServiceProvider::$defaultTimeDisplayFormat));
        Carbon::macro('toFormattedTimeWithSeconds', fn () => $this->translatedFormat(FilamentServiceProvider::$defaultTimeWithSecondsDisplayFormat));
    }
}
