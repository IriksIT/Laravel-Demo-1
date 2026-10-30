<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    protected $allowedSorts = ['id', 'name', 'created_at'];

    protected $allowedFilters = ['name' => Like::class];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
