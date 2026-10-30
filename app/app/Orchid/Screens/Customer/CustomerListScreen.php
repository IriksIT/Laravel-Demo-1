<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Customer;

use App\Models\Customer;
use App\Orchid\Layouts\Customer\CustomerListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CustomerListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'customers' => Customer::withCount('orders')
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Klanten';
    }

    public function description(): ?string
    {
        return 'Alle geregistreerde klanten.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.customers'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Nieuwe klant')
                ->icon('bs.plus-circle')
                ->route('platform.customers.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            CustomerListLayout::class,
        ];
    }
}
