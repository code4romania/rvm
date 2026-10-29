<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Models\Resource\Subcategory;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Cache;

abstract class FieldGroup extends Grid
{
    abstract public function getGroupKey(): string;

    protected function setUp(): void
    {
        parent::setUp();

        $this->columns();

        $this->visible(function (Get $get) {
            $id = $get('subcategory_id');

            if (blank($id)) {
                return false;
            }

            $subcategory = Cache::driver('array')
                ->rememberForever(
                    'field_groups_subcategory',
                    fn () => Subcategory::find($id)
                );

            return $subcategory->field_group === $this->getGroupKey();
        });
    }
}
