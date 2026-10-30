<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use App\Orchid\Layouts\Category\CategoryListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'categories' => Category::withCount('products')
                ->filters()
                ->defaultSort('name')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Categorieën';
    }

    public function description(): ?string
    {
        return 'Productcategorieën van de webshop.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.products'];
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Nieuwe categorie')
                ->icon('bs.plus-circle')
                ->modal('categoryModal')
                ->modalTitle('Nieuwe categorie')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            CategoryListLayout::class,

            Layout::modal('categoryModal', CategoryEditLayout::class)
                ->deferred('loadCategory'),
        ];
    }

    public function loadCategory(?Category $category = null): iterable
    {
        return [
            'category' => $category ?? new Category(),
        ];
    }

    public function save(Request $request, ?Category $category = null): void
    {
        $data = $request->validate([
            'category.name'        => ['required', 'string', 'max:255'],
            'category.description' => ['nullable', 'string'],
        ])['category'];

        $category ??= new Category();
        $category->fill($data);
        $category->slug = Str::slug($data['name']);
        $category->save();

        Toast::info('Categorie opgeslagen.');
    }

    public function remove(Request $request): void
    {
        Category::findOrFail($request->get('id'))->delete();

        Toast::info('Categorie verwijderd.');
    }
}
