<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    public $target = 'categories';

    public function columns(): array
    {
        return [
            TD::make('name', 'Naam')
                ->sort()
                ->filter(Input::make())
                ->render(fn (Category $category) => ModalToggle::make($category->name)
                    ->modal('categoryModal')
                    ->modalTitle('Categorie bewerken')
                    ->method('save')
                    ->asyncParameters(['category' => $category->id])),

            TD::make('slug', 'Slug'),

            TD::make('description', 'Omschrijving'),

            TD::make('products_count', 'Producten')
                ->align(TD::ALIGN_RIGHT),

            TD::make('Acties')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Category $category) => Button::make('Verwijderen')
                    ->icon('bs.trash3')
                    ->confirm('Alle producten in deze categorie worden ook verwijderd. Doorgaan?')
                    ->method('remove', ['id' => $category->id])),
        ];
    }
}
