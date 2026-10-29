<?php

declare(strict_types=1);

namespace App\Filament\Infolists\EntryGroups;

use App\Models\Resource\Subcategory;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Cache;

abstract class EntryGroup extends Grid
{
    abstract public function getGroupKey(): string;

    protected function setUp(): void
    {
        parent::setUp();

        $this->columns();

        $this->visible(function (Get $get) {
            $id = $get('subcategory.id');

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
