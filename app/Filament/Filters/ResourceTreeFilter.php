<?php

declare(strict_types=1);

namespace App\Filament\Filters;

use App\Models\Resource\Category;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\BaseFilter;

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
        ]);
    }
}
