<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Boolean;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    public $target = 'products';

    public function columns(): array
    {
        return [
            TD::make('sku', 'SKU')
                ->sort()
                ->filter(Input::make())
                ->width('130px'),

            TD::make('name', 'Naam')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Product $product) => Link::make($product->name)
                    ->route('platform.products.edit', $product)),

            TD::make('category_id', 'Categorie')
                ->render(fn (Product $product) => $product->category?->name),

            TD::make('price', 'Prijs')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->usingComponent(Currency::class, symbol: '€ '),

            TD::make('stock', 'Voorraad')
                ->sort()
                ->align(TD::ALIGN_RIGHT),

            TD::make('is_active', 'Actief')
                ->sort()
                ->align(TD::ALIGN_CENTER)
                ->usingComponent(Boolean::class),

            TD::make('created_at', 'Aangemaakt')
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make('Acties')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Product $product) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Bewerken')
                            ->route('platform.products.edit', $product)
                            ->icon('bs.pencil'),

                        Button::make('Verwijderen')
                            ->icon('bs.trash3')
                            ->confirm('Weet je zeker dat je dit product wilt verwijderen?')
                            ->method('remove', ['id' => $product->id]),
                    ])),
        ];
    }
}
