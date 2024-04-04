<?php

namespace Modules\SellerModule\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SellerModule\Database\factories\OrderSellerFactory;

class OrderSeller extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): OrderSellerFactory
    {
        //return OrderSellerFactory::new();
    }
}
