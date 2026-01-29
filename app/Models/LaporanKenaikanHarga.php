<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKenaikanHarga extends Model
{
    protected $guarded = ['id'];

    //buat relationship ke varian untuk ambil nama produk karna di varian ada nama produk dan memakai nomor_sku sbg relasi nya
    public function varian ()
    {
        return $this->belongsTo(VarianProduk::class, 'nomor_sku', 'nomor_sku');
    }
}
