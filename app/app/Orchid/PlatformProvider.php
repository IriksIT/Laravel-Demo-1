<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Dashboard')
                ->icon('bs.speedometer2')
                ->title('Webshop')
                ->route(config('platform.index')),

            Menu::make('Bestellingen')
                ->icon('bs.cart3')
                ->route('platform.orders')
                ->permission('platform.shop.orders')
                ->badge(fn () => \App\Models\Order::where('status', 'pending')->count(), Color::WARNING),

            Menu::make('Producten')
                ->icon('bs.box-seam')
                ->route('platform.products')
                ->permission('platform.shop.products')
                ->active(['*/products', '*/products/*']),

            Menu::make('Categorieën')
                ->icon('bs.tags')
                ->route('platform.categories')
                ->permission('platform.shop.products'),

            Menu::make('Klanten')
                ->icon('bs.people')
                ->route('platform.customers')
                ->permission('platform.shop.customers')
                ->active(['*/customers', '*/customers/*'])
                ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.person-gear')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Orchid documentatie')
                ->title('Docs')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group('Webshop')
                ->addPermission('platform.shop.orders', 'Bestellingen')
                ->addPermission('platform.shop.products', 'Producten & categorieën')
                ->addPermission('platform.shop.customers', 'Klanten'),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
