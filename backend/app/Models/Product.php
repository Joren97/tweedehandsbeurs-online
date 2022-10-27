<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'price_id',
        'productlist_id',
    ];

    public function productList()
    {
        return $this->belongsTo(Productlist::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}