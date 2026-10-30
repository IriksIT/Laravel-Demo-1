<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Customer;

use App\Models\Customer;
use App\Models\Order;
use App\Orchid\Layouts\Customer\CustomerEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\Currency;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CustomerEditScreen extends Screen
{
    public ?Customer $customer = null;

    public function query(Customer $customer): iterable
    {
        return [
            'customer' => $customer,
            'orders'   => $customer->exists
                ? $customer->orders()->latest('ordered_at')->get()
                : collect(),
        ];
    }

    public function name(): ?string
    {
        return $this->customer?->exists ? $this->customer->name : 'Nieuwe klant';
    }

    public function description(): ?string
    {
        return 'Klantgegevens en bestelhistorie.';
    }

    public function permission(): ?iterable
    {
        return ['platform.shop.customers'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Opslaan')
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make('Verwijderen')
                ->icon('bs.trash3')
                ->confirm('Alle bestellingen van deze klant worden ook verwijderd. Doorgaan?')
                ->method('remove')
                ->canSee($this->customer?->exists ?? false),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(CustomerEditLayout::class)
                ->title('Klantgegevens')
                ->description('Contact- en adresgegevens van de klant.'),

            Layout::table('orders', [
                TD::make('number', 'Nummer')
                    ->render(fn (Order $order) => Link::make($order->number)
                        ->route('platform.orders.view', $order)),
                TD::make('status', 'Status')
                    ->render(fn (Order $order) => $order->statusLabel()),
                TD::make('total', 'Totaal')
                    ->align(TD::ALIGN_RIGHT)
                    ->usingComponent(Currency::class, symbol: '€ '),
                TD::make('ordered_at', 'Besteld op')
                    ->align(TD::ALIGN_RIGHT)
                    ->usingComponent(DateTimeSplit::class),
            ])->title('Bestellingen')
              ->canSee($this->customer?->exists ?? false),
        ];
    }

    public function save(Customer $customer, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer.name'        => ['required', 'string', 'max:255'],
            'customer.email'       => ['required', 'email', Rule::unique(Customer::class, 'email')->ignore($customer)],
            'customer.phone'       => ['nullable', 'string', 'max:50'],
            'customer.street'      => ['nullable', 'string', 'max:255'],
            'customer.postal_code' => ['nullable', 'string', 'max:20'],
            'customer.city'        => ['nullable', 'string', 'max:100'],
            'customer.country'     => ['nullable', 'string', 'size:2'],
        ])['customer'];

        $customer->fill($data)->save();

        Toast::info('Klant opgeslagen.');

        return redirect()->route('platform.customers');
    }

    public function remove(Customer $customer): RedirectResponse
    {
        $customer->delete();

        Toast::info('Klant verwijderd.');

        return redirect()->route('platform.customers');
    }
}
