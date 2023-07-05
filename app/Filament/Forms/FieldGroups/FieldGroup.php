<?php

declare(strict_types=1);

namespace App\Filament\Forms\FieldGroups;

use App\Models\Resource\Subcategory;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Cache;

abstract class FieldGroup extends Grid
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->columns();

        $this->visible(function (array $state, $livewire) {
            $id = data_get($state, 'subcategory_id');

            if (blank($id)) {
                return false;
            }

            $subcategory = Cache::driver('array')
                ->rememberForever(
                    'field_groups_subcategory',
                    fn () => Subcategory::find($id)
                );

            return $subcategory->field_group === \get_class($this);
        });
    }
}
