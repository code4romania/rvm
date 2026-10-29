<?php

declare(strict_types=1);

use App\Filament\Forms\FieldGroups\FieldGroup;
use App\Models\Resource\Subcategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Subcategory::query()->get() as $subcategory) {
            if ($subcategory->field_group === null) {
                continue;
            }
            try {
                $instance = new $subcategory->field_group(0);
                if ($instance instanceof FieldGroup) {
                    $value = $instance->getGroupKey();
                    Subcategory::query()->where('id', $subcategory->id)->update(['field_group' => $value]);
                }
            } catch (Exception $exception) {
                Log::info($exception->getMessage());
            }
        }
    }
};
