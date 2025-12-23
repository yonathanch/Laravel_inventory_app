<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeKategoriProdukRequest;
use App\Http\Requests\updateKategoriProdukRequest;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class KategoriProdukController extends Controller
{
    public $pageTitle = 'Kategori Produk';
    
    public function index()
    {
        $pageTitle = $this->pageTitle; 
        $perPage = request()->query('perPage') ?? 10;
        $search = request()->query('search');
        $query = KategoriProduk::query();
        // ATURAN EMAS QUERY BUILDER Semua where, orderBy, filter HARUS sebelum paginate()
        // Karena paginate() itu = EKSEKUSI QUERY ke database. Setelah dieksekusi, query sudah selesai, tidak bisa ditambah filter lagi.
        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%');
        }

        $kategori = $query->paginate($perPage)->appends(request()->query());
        confirmDelete('Hapus kategori produk, Anda Yakin?');
        return view('kategori-produk.index', compact('pageTitle','kategori'));
    }

    public function store(storeKategoriProdukRequest $request)
    {
        KategoriProduk::create([
            'nama_kategori' => $request->nama_kategori
        ]);
        toast()->success('Kategori produk berhasil ditambahkan');
        return redirect()->route('master-data.kategori-produk.index');
    }

    // tidak perlu findOrFail karena laravel otomatis Implicit Route Model Binding:
    // KategoriProduk $kategoriProduk)
    public function update(updateKategoriProdukRequest $request, KategoriProduk $kategoriProduk)
    {
        $kategoriProduk->nama_kategori = $request->nama_kategori;
        $kategoriProduk->save();
        toast()->success('Kategori produk berhasil di ubah');
        return redirect()->route('master-data.kategori-produk.index');
    }

    public function destroy(KategoriProduk $kategoriProduk)
    {   
        $kategoriProduk->delete();
        toast()->success('Kategori produk berhasil dihapus');
        return redirect()->route('master-data.kategori-produk.index');
    }
}
