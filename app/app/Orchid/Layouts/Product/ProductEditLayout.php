<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Product;

use App\Models\Category;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class ProductEditLayout extends Rows
{
    /**
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('product.name')
                ->title('Naam')
                ->placeholder('Productnaam')
                ->required(),

            Group::make([
                Input::make('product.sku')
                    ->title('SKU')
                    ->placeholder('AB-12345')
                    ->required(),

                Relation::make('product.category_id')
                    ->title('Categorie')
                    ->fromModel(Category::class, 'name')
                    ->required(),
            ]),

            Group::make([
                Input::make('product.price')
                    ->title('Prijs (€)')
                    ->type('number')
                    ->step(0.01)
                    ->min(0)
                    ->required(),

                Input::make('product.stock')
                    ->title('Voorraad')
                    ->type('number')
                    ->min(0)
                    ->required(),
            ]),

            TextArea::make('product.description')
                ->title('Omschrijving')
                ->rows(5),

            CheckBox::make('product.is_active')
                ->title('Status')
                ->placeholder('Product is actief en zichtbaar in de webshop')
                ->sendTrueOrFalse(),
        ];
    }
}
