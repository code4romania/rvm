<?php

declare(strict_types=1);

namespace App\Filament\Tables\Actions;

use App\Filament\Tables\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction as BaseAction;

class ExportAction extends BaseAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->exports = collect([
            ExcelExport::make(),
        ]);
    }
}
