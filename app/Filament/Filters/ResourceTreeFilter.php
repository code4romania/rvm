<?php

declare(strict_types=1);

namespace App\Filament\Filters;

use App\Models\Resource\Category;
use Cache;
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

        $categories = Cache::driver('array')
            ->rememberForever(
                'resource-tree-filter-categories',
                fn () => Category::query()
                    ->with('subcategories.types')
                    ->get()
            );

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
                $subcategory = $categories->firstWhere('id', $data['category'])
                    ?->subcategories
                    ->firstWhere('id', $data['subcategory']);

                $type = $subcategory?->types
                    ->firstWhere('id', $data['type']);

                return $query->when($data['category'], fn (Builder $query, $value) => $query->where('category_id', $value))
                    ->when($subcategory?->id, fn (Builder $query, $value) => $query->where('subcategory_id', $value))
                    ->when($type?->id, fn (Builder $query, $value) => $query->whereRelation('types', 'type_id', $value));
            });
    }
}
