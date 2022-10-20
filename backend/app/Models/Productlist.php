<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id',
        'user_id',
        'list_number',
        'member_number',
        'is_user_confirmed',
        'is_employee_validated',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}