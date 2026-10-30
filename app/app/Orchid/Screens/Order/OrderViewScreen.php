<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class OrderViewScreen extends Screen
{
    public ?Order $order = null;

    public function query(Order $order): iterable
    {
        $order->load(['customer', 'items.product']);

        return [
            'order' => $order,
            'items' => $order->items,
        ];
    }

    public function name(): ?string
    {
        return 'Bestelling '.$this->order?->number;
    }

    public function description(): ?string
    {
        return 'Details, regels en status van de bestelling.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.orders'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Terug')
                ->icon('bs.arrow-left')
                ->route('platform.orders'),

            Button::make('Status opslaan')
                ->icon('bs.check-circle')
                ->method('updateStatus'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::columns([
                Layout::legend('order', [
                    Sight::make('number', 'Nummer'),
                    Sight::make('customer_id', 'Klant')
                        ->render(fn (Order $order) => Link::make($order->customer->name)
                            ->route('platform.customers.edit', $order->customer)),
                    Sight::make('ordered_at', 'Besteld op')
                        ->render(fn (Order $order) => $order->ordered_at->format('d-m-Y H:i')),
                    Sight::make('total', 'Totaal')
                        ->render(fn (Order $order) => '€ '.number_format((float) $order->total, 2, ',', '.')),
                ])->title('Overzicht'),

                Layout::rows([
                    Select::make('order.status')
                        ->title('Status')
                        ->options(Order::STATUSES),

                    TextArea::make('order.notes')
                        ->title('Opmerkingen')
                        ->rows(4),
                ])->title('Status'),
            ]),

            Layout::table('items', [
                TD::make('product', 'Product')
                    ->render(fn (OrderItem $item) => $item->product?->name),
                TD::make('sku', 'SKU')
                    ->render(fn (OrderItem $item) => $item->product?->sku),
                TD::make('quantity', 'Aantal')
                    ->align(TD::ALIGN_RIGHT),
                TD::make('unit_price', 'Stukprijs')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (OrderItem $item) => '€ '.number_format((float) $item->unit_price, 2, ',', '.')),
                TD::make('line_total', 'Regeltotaal')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (OrderItem $item) => '€ '.number_format($item->lineTotal(), 2, ',', '.')),
            ])->title('Bestelregels'),
        ];
    }

    public function updateStatus(Order $order, Request $request): void
    {
        $data = $request->validate([
            'order.status' => ['required', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'order.notes'  => ['nullable', 'string'],
        ])['order'];

        $order->fill($data)->save();

        Toast::info('Bestelling bijgewerkt.');
    }
}
