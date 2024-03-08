<?php

namespace Modules\SellerModule\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Modules\SellerModule\Database\factories\SellerFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    // ...

    // to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ...

    // Specify the guard for the model
    protected $guard = 'seller';
}
