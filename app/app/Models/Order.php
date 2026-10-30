<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use AsSource, Filterable, HasFactory;

    public const STATUSES = [
        'pending'   => 'In afwachting',
        'paid'      => 'Betaald',
        'shipped'   => 'Verzonden',
        'delivered' => 'Afgeleverd',
        'cancelled' => 'Geannuleerd',
    ];

    protected $fillable = ['number', 'customer_id', 'status', 'total', 'notes', 'ordered_at'];

    protected $casts = [
        'total'      => 'decimal:2',
        'ordered_at' => 'datetime',
    ];

    protected $allowedSorts = ['id', 'number', 'status', 'total', 'ordered_at'];

    protected $allowedFilters = [
        'number' => Like::class,
        'status' => Where::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items()->sum(\Illuminate\Support\Facades\DB::raw('quantity * unit_price'));
        $this->save();
    }
}
