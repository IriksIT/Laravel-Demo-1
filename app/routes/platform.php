<?php

declare(strict_types=1);

use App\Orchid\Screens\Category\CategoryListScreen;
use App\Orchid\Screens\Customer\CustomerEditScreen;
use App\Orchid\Screens\Customer\CustomerListScreen;
use App\Orchid\Screens\Order\OrderListScreen;
use App\Orchid\Screens\Order\OrderViewScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Product\ProductEditScreen;
use App\Orchid\Screens\Product\ProductListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Dashboard
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Producten
Route::screen('products/create', ProductEditScreen::class)
    ->name('platform.products.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.products')
        ->push('Nieuw', route('platform.products.create')));

Route::screen('products/{product}/edit', ProductEditScreen::class)
    ->name('platform.products.edit')
    ->breadcrumbs(fn (Trail $trail, $product) => $trail
        ->parent('platform.products')
        ->push($product->name, route('platform.products.edit', $product)));

Route::screen('products', ProductListScreen::class)
    ->name('platform.products')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Producten', route('platform.products')));

// Categorieën
Route::screen('categories', CategoryListScreen::class)
    ->name('platform.categories')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Categorieën', route('platform.categories')));

// Klanten
Route::screen('customers/create', CustomerEditScreen::class)
    ->name('platform.customers.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.customers')
        ->push('Nieuw', route('platform.customers.create')));

Route::screen('customers/{customer}/edit', CustomerEditScreen::class)
    ->name('platform.customers.edit')
    ->breadcrumbs(fn (Trail $trail, $customer) => $trail
        ->parent('platform.customers')
        ->push($customer->name, route('platform.customers.edit', $customer)));

Route::screen('customers', CustomerListScreen::class)
    ->name('platform.customers')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Klanten', route('platform.customers')));

// Bestellingen
Route::screen('orders/{order}', OrderViewScreen::class)
    ->name('platform.orders.view')
    ->breadcrumbs(fn (Trail $trail, $order) => $trail
        ->parent('platform.orders')
        ->push($order->number, route('platform.orders.view', $order)));

Route::screen('orders', OrderListScreen::class)
    ->name('platform.orders')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Bestellingen', route('platform.orders')));

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));
