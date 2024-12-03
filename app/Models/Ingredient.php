<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_weight', 'remaining_weight', 'weight_unit', 'email_notification_sent'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('weight', 'weight_unit')
            ->withTimestamps();
    }    

    public function getIsLowStockAttribute(): bool
    {
        return ($this->total_weight / 2) >= ($this->remaining_weight);
    }

    public function getIsLowStockAndEmailNotSentAttribute(): bool
    {
        return ($this->isLowStock) and !($this->email_notification_sent);
    }

    public function getIsStockAvailableAttribute(): bool
    {
        return $this->remaining_weight > 0;
    }
}
