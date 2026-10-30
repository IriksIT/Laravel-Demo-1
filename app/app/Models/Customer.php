<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Screen\AsSource;

class Customer extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'street', 'postal_code', 'city', 'country'];

    protected $allowedSorts = ['id', 'name', 'email', 'city', 'created_at'];

    protected $allowedFilters = [
        'name'  => Like::class,
        'email' => Like::class,
        'city'  => Like::class,
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
