<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\VarianProduk;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public $pageTitle = "Stok Barang";
    public function index()
    {
        $pageTitle = $this->pageTitle;
        //kategori all untuk filter kategori
        $kategori = KategoriProduk::all();
        $perPage = request()->query('perPage') ?? 10;
        $search = request()->query('search');
        $rKategori = request()->query('kategori');

        $query = VarianProduk::query();
        //tampilkan data kategori diambil melalui produk karna berurutan kategori->produk->variasi jika dipaksakan variasi langsung ambil dari kategori akan duplicat data
        $query = $query->with('produk', 'produk.kategori');

        if ($search) {
            $query->where('nama_varian','like', '%' . $search . '%')
            ->orWhere('nomor_sku','like', '%' . $search . '%')
            //atau dicari dg produk karna produk ini dlm bentuk relasi makai orWhereHas
            ->orWhereHas('produk', function ($query) use ($search) {
                $query->where('nama_produk','like', '%' . $search . '%');
            });
        }

        //use rkategori karna mencari id kategori bkn text search kalo text seacrh bsa pakai $search
        if ($rKategori) {
            $query->whereHas('produk', function($query) use ($rKategori){
                $query->where('kategori_produk_id', $rKategori);
            });
        }

        //memakai get collection karna ingin gabungkan nama produk dan nama varian contoh : ransel 45 l
        $paginator = $query->paginate($perPage)->appends(request()->query());
        $produk = $paginator->getCollection()->map(function($q){
            return[
                'varian_id' => $q->id,
                'nomor_sku' => $q->nomor_sku,
                'produk' => $q->produk->nama_produk . " " . $q->nama_varian,
                'kategori' => $q->produk->kategori->nama_kategori,
                'stok' => $q->stok_varian,
                'harga' => $q->harga_varian,
            ];
        });

        $paginator->setCollection($produk);
        $produk = $paginator;


        return view('stok-barang.index', compact('pageTitle', 'produk', 'kategori'));
    }
}
