<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Order;

use App\Models\Order;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    public $target = 'orders';

    public function columns(): array
    {
        return [
            TD::make('number', 'Nummer')
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Order $order) => Link::make($order->number)
                    ->route('platform.orders.view', $order)),

            TD::make('customer_id', 'Klant')
                ->render(fn (Order $order) => $order->customer?->name),

            TD::make('status', 'Status')
                ->sort()
                ->filter(Select::make()->options(Order::STATUSES)->empty('Alle'))
                ->render(fn (Order $order) => $order->statusLabel()),

            TD::make('total', 'Totaal')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->usingComponent(Currency::class, symbol: '€ '),

            TD::make('ordered_at', 'Besteld op')
                ->sort()
                ->align(TD::ALIGN_RIGHT)
                ->usingComponent(DateTimeSplit::class),
        ];
    }
}
