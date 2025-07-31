<?php

declare(strict_types=1);

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected static bool $canCreateAnother = false;


    protected function getSubheading(): HtmlString
    {
        return new HtmlString(__('news.disclaimer'));
    }
}
