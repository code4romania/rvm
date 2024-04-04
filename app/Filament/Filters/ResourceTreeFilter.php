<?php

declare(strict_types=1);

namespace App\Filament\Filters;

use App\Models\Resource\Category;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class ResourceTreeFilter extends BaseFilter
{
    protected string | Closure | null $attribute = null;

    public function attribute(string | Closure | null $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttribute(): string
    {
        return $this->evaluate($this->attribute) ?? $this->getName();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $categories = Category::query()
            ->with('subcategories.types')
            ->get();

        $this->form(fn () => [
            Select::make('category')
                ->label(__('resource.fields.category'))
                ->options($categories->pluck('name', 'id'))
                ->reactive(),

            Select::make('subcategory')
                ->label(__('resource.fields.subcategory'))
                ->options(
                    fn (callable $get) => $categories
                        ->firstWhere('id', $get('category'))
                        ?->subcategories
                        ->pluck('name', 'id')
                )
                ->hidden(fn (callable $get) => ! $get('category'))
                ->reactive(),

            Select::make('type')
                ->label(__('resource.fields.type'))
                ->options(
                    fn (callable $get) => $categories
                        ->firstWhere('id', $get('category'))
                        ?->subcategories
                        ->firstwhere('id', $get('subcategory'))
                        ?->types
                        ->pluck('name', 'id')
                )
                ->hidden(fn (Select $component) => empty($component->getOptions())),
        ])
            ->query(function (Builder $query, array $data) use ($categories) {
                $categoryID = $data['category'];
                $categoryData = $categories->firstWhere('id', $categoryID);
                $subcategory = $categoryData?->subcategories->firstWhere('id', $data['subcategory']);
                $subcategoryID = $subcategory?->id;
                $type = $subcategory?->types->firstWhere('id', $data['type']);
                $typeID = $type?->id;

                return $query->when($categoryID, fn (Builder $query) => $query->where('category_id', $categoryID))
                    ->when($subcategoryID, fn (Builder $query) => $query->where('subcategory_id', $subcategoryID))
                    ->when($typeID, fn (Builder $query) => $query->whereHas('types', fn (Builder $query) => $query->where('type_id', $typeID)));
            });
    }
}
