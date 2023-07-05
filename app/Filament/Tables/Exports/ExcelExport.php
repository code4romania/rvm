<?php

declare(strict_types=1);

namespace App\Filament\Tables\Exports;

use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Exports\ExcelExport as BaseExcelExport;

class ExcelExport extends BaseExcelExport
{
    public function setUp(): void
    {
        $this->withFilename(
            fn ($resource) => sprintf(
                '%s-%s',
                now()->format('Y_m_d-H_i_s'),
                Str::slug($resource::getPluralModelLabel())
            )
        );

        $this->fromTable();
    }
}
