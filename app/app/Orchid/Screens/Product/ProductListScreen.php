<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use App\Orchid\Layouts\Product\ProductListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProductListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'products' => Product::with('category')
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Producten';
    }

    public function description(): ?string
    {
        return 'Overzicht van alle producten in de webshop.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.products'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nieuw product')
                ->icon('bs.plus-circle')
                ->route('platform.products.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ProductListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Product::findOrFail($request->get('id'))->delete();

        Toast::info('Product verwijderd.');
    }
}
