<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Customer;

use App\Models\Customer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CustomerListLayout extends Table
{
    public $target = 'customers';

    public function columns(): array
    {
        return [
            TD::make('name', 'Naam')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Customer $customer) => Link::make($customer->name)
                    ->route('platform.customers.edit', $customer)),

            TD::make('email', 'E-mail')
                ->sort()
                ->filter(Input::make()),

            TD::make('phone', 'Telefoon'),

            TD::make('city', 'Plaats')
                ->sort()
                ->filter(Input::make()),

            TD::make('orders_count', 'Bestellingen')
                ->align(TD::ALIGN_RIGHT),

            TD::make('created_at', 'Klant sinds')
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),
        ];
    }
}
