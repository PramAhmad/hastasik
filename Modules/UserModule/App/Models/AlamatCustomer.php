<?php

namespace Modules\UserModule\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserModule\Database\factories\AlamatCustomerFactory;

class AlamatCustomer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

        'customer_id',
        'nama_penerima',
        'nomor_telepon',
        'alamat',
        'kota',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'provinsi',
        'province_id',
        'city_id',
        'is_utama'
        
    ];
    
    // ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
   
}
