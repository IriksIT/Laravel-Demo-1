<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Customer;

use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CustomerEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('customer.name')->title('Naam')->required(),

            Group::make([
                Input::make('customer.email')->title('E-mail')->type('email')->required(),
                Input::make('customer.phone')->title('Telefoon'),
            ]),

            Input::make('customer.street')->title('Straat en huisnummer'),

            Group::make([
                Input::make('customer.postal_code')->title('Postcode'),
                Input::make('customer.city')->title('Plaats'),
                Input::make('customer.country')->title('Land')->maxlength(2),
            ]),
        ];
    }
}
