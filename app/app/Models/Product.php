<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable = ['category_id', 'name', 'sku', 'description', 'price', 'stock', 'is_active'];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $allowedSorts = ['id', 'name', 'sku', 'price', 'stock', 'created_at'];

    protected $allowedFilters = [
        'name'        => Like::class,
        'sku'         => Like::class,
        'category_id' => Where::class,
        'is_active'   => Where::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
