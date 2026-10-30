<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Category;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class CategoryEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('category.name')
                ->title('Naam')
                ->required(),

            TextArea::make('category.description')
                ->title('Omschrijving')
                ->rows(3),
        ];
    }
}
