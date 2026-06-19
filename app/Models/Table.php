<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $table = 'tables';

    protected $fillable = ['table_number', 'capacity', 'status', 'qr_code'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'table_id');
    }
}
