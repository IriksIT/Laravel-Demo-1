<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Orchid\Layouts\Order\OrderListLayout;
use Orchid\Screen\Screen;

class OrderListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'orders' => Order::with('customer')
                ->filters()
                ->defaultSort('ordered_at', 'desc')
                ->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'Bestellingen';
    }

    public function description(): ?string
    {
        return 'Alle bestellingen, filterbaar op status en nummer.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.orders'];
    }

    public function layout(): iterable
    {
        return [
            OrderListLayout::class,
        ];
    }
}
