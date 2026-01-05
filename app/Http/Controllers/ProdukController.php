<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeProdukRequest;
use App\Http\Requests\updateProdukRequest;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public $pageTitle = 'Data Produk';
    public function index()
    {
        $query = Produk::query();
        $perPage = request()->query('perPage') ?? 10;
        $search = request()->query('search');
        $pageTitle = $this->pageTitle;

        if ($search) {
            $query->where('nama_produk', 'like', '%' . $search . '%');
        }

        //tamilkan data kategori ambil kolom id dan nama kategori
        $query->with('kategori:id,nama_kategori');
        $produk = $query->orderBy('created_at', 'DESC')->paginate($perPage)->appends(request()->query());
        confirmDelete('Hapus produk akan menghapus semua varian, Anda Yakin?');

        return view('produk.index', compact('pageTitle', 'produk'));
    }

    public function store(storeProdukRequest $request)
    {
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'kategori_produk_id' => $request->kategori_produk_id,
        ]);
        
        toast()->success('Produk berhasil ditambahkan');
        return redirect()->route('master-data.produk.show', $produk->id);
        //karna show butuh id kita tambahkan variabel $produk agar bisa parsing produk->id ke show
    }

    public function update(updateProdukRequest $request, Produk $produk)
    {
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'kategori_produk_id' => $request->kategori_produk_id,
        ]);
        toast()->success('Produk berhasil di ubah');

        return redirect()->route('master-data.produk.index');
    }

    public function show(Produk $produk)
    {
        $pageTitle = 'Show Produk';

        $pageTitle = $this->pageTitle;

        return view('produk.show', compact('produk','pageTitle'));
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        toast()->success('Produk berhasil dihapus');
        return redirect()->route('master-data.produk.index');
    }
}
