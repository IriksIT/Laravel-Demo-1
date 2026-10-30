<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use App\Orchid\Layouts\Product\ProductEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProductEditScreen extends Screen
{
    public ?Product $product = null;

    public function query(Product $product): iterable
    {
        return [
            'product' => $product,
        ];
    }

    public function name(): ?string
    {
        return $this->product?->exists ? 'Product bewerken' : 'Nieuw product';
    }

    public function description(): ?string
    {
        return 'Beheer de gegevens, prijs en voorraad van een product.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.products'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Opslaan')
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make('Verwijderen')
                ->icon('bs.trash3')
                ->confirm('Weet je zeker dat je dit product wilt verwijderen?')
                ->method('remove')
                ->canSee($this->product?->exists ?? false),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(ProductEditLayout::class)
                ->title('Productgegevens')
                ->description('Naam, SKU, categorie, prijs en voorraad.'),
        ];
    }

    public function save(Product $product, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product.name'        => ['required', 'string', 'max:255'],
            'product.sku'         => ['required', 'string', 'max:50', Rule::unique(Product::class, 'sku')->ignore($product)],
            'product.category_id' => ['required', 'exists:categories,id'],
            'product.price'       => ['required', 'numeric', 'min:0'],
            'product.stock'       => ['required', 'integer', 'min:0'],
            'product.description' => ['nullable', 'string'],
            'product.is_active'   => ['boolean'],
        ])['product'];

        $product->fill($data)->save();

        Toast::info('Product opgeslagen.');

        return redirect()->route('platform.products');
    }

    public function remove(Product $product): RedirectResponse
    {
        $product->delete();

        Toast::info('Product verwijderd.');

        return redirect()->route('platform.products');
    }
}
