<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Orchid\Layouts\Dashboard\RevenueChart;
use App\Orchid\Layouts\Dashboard\StatusChart;
use App\Orchid\Layouts\Order\OrderListLayout;
use Illuminate\Support\Carbon;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    public function query(): iterable
    {
        $months = collect(range(5, 0))
            ->map(fn (int $i) => Carbon::now()->startOfMonth()->subMonths($i));

        $revenue = $months->map(fn (Carbon $month) => (float) Order::query()
            ->whereBetween('ordered_at', [$month, $month->copy()->endOfMonth()])
            ->whereNotIn('status', ['cancelled'])
            ->sum('total'));

        $statusCounts = Order::query()
            ->selectRaw('status, count(*) as aantal')
            ->groupBy('status')
            ->pluck('aantal', 'status');

        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = $thisMonth->copy()->subMonth();

        $revenueThisMonth = (float) Order::whereBetween('ordered_at', [$thisMonth, now()])->sum('total');
        $revenueLastMonth = (float) Order::whereBetween('ordered_at', [$lastMonth, $thisMonth])->sum('total');

        return [
            'metrics' => [
                'revenue'   => [
                    'value' => '€ '.number_format($revenueThisMonth, 0, ',', '.'),
                    'diff'  => $revenueLastMonth > 0
                        ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
                        : 0,
                ],
                'orders'    => ['value' => Order::count()],
                'pending'   => ['value' => Order::where('status', 'pending')->count()],
                'customers' => ['value' => Customer::count()],
                'products'  => ['value' => Product::where('is_active', true)->count()],
                'lowStock'  => ['value' => Product::where('stock', '<', 10)->count()],
            ],
            'revenueChart' => [
                [
                    'name'   => 'Omzet (€)',
                    'values' => $revenue->map(fn ($v) => round($v))->values()->all(),
                    'labels' => $months->map(fn (Carbon $m) => $m->translatedFormat('M Y'))->values()->all(),
                ],
            ],
            'statusChart' => [
                [
                    'name'   => 'Bestellingen',
                    'values' => collect(Order::STATUSES)->keys()->map(fn ($s) => $statusCounts[$s] ?? 0)->all(),
                    'labels' => array_values(Order::STATUSES),
                ],
            ],
            'orders' => Order::with('customer')->latest('ordered_at')->limit(8)->get(),
        ];
    }

    public function name(): ?string
    {
        return 'Dashboard';
    }

    public function description(): ?string
    {
        return 'Overzicht van omzet, bestellingen en voorraad.';
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Omzet deze maand'   => 'metrics.revenue',
                'Bestellingen'       => 'metrics.orders',
                'In afwachting'      => 'metrics.pending',
                'Klanten'            => 'metrics.customers',
            ]),

            Layout::metrics([
                'Actieve producten'  => 'metrics.products',
                'Lage voorraad (<10)' => 'metrics.lowStock',
            ]),

            Layout::columns([
                RevenueChart::class,
                StatusChart::class,
            ]),

            Layout::block(OrderListLayout::class)
                ->title('Recente bestellingen')
                ->description('De laatste acht bestellingen.'),
        ];
    }
}
