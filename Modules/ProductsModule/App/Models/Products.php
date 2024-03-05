<?php

namespace Modules\ProductsModule\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $fillable = ["nama_product","seller_id","harga","deskripsi","stok","berat","foto","category","status","created_at","updated_at"];
    
    protected $cast = [
        "foto" => "array",
        "category" => "array"
    ];  
}
