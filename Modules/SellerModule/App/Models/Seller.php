<?php

namespace Modules\SellerModule\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SellerModule\Database\factories\SellerFactory;

class Seller extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', // tambahkan 'user_id' ke dalam fillable
        'nama_toko',
        'nama_pemilik',
        'no_hp',
        'email',
        'foto',
        'deskripsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'alamat',
        'longitude',
        'latitude',
        'status',
    ];
    // to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
