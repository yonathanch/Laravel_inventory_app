<?php

namespace App\Http\Controllers;

use App\Models\KartuStok;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\storeVarianProdukRequest;
use App\Http\Requests\updateVarianProdukRequest;
use App\Models\LaporanKenaikanHarga;

class VarianProdukController extends Controller
{
    public function store(storeVarianProdukRequest $request)
    {
        $fileName = time() . '.' . $request->file('gambar_varian')->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('varian-produk', $request->file('gambar_varian'), $fileName);

        VarianProduk::create([
            'produk_id' => $request->produk_id,
            'nomor_sku' => VarianProduk::generateNomorSku(),
            'nama_varian' => $request->nama_varian,
            'harga_varian' => $request->harga_varian,
            'stok_varian' => $request->stok_varian,
            'gambar_varian' => $fileName
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function update(updateVarianProdukRequest $request, $varian_produk)
    {
        //untuk kartu stok 
        $isAdjusment = false;

        $varian = VarianProduk::findOrFail($varian_produk);

        //cek apakah no sku ada di laporan kenaikan harga
        $existingKenaikanHarga = LaporanKenaikanHarga::where('nomor_sku', $varian->nomor_sku)->where('is_confirmed', false)->first();

        $fileName = $varian->gambar_varian;

         //jika varian produk stoknya di edit maka akan otomatis msk ke historis kartu stok
        if ($request->stok_varian != $varian->stok_varian) {
            $isAdjusment = true;
        }

        if ($request->file('gambar_varian')) {
            Storage::disk('public')->delete('varian-produk/'.$varian->gambar_varian);
            $fileName = time() . '.' . $request->file('gambar_varian')->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('varian-produk', $request->file('gambar_varian'), $fileName);
        }

        $varian->update([
            'nama_varian' => $request->nama_varian,
            'harga_varian' => $request->harga_varian,
            'stok_varian' => $request->stok_varian,
            'gambar_varian' => $fileName
        ]);

        //lanjutan kode untuk laporan kenaikan harga jika request harga_varian lebih besar dari harga_beli laporan maka otomatis button konfirmasinya di laporan kenaikan harga akan hilang
        if ($existingKenaikanHarga && $request->harga_varian >= $existingKenaikanHarga->harga_beli) {
            $existingKenaikanHarga->update([
                'is_confirmed' => true
            ]);
        }

        //jika ada perubahan pada stok varian produk otomatis membuat log historis pd kartu stok
        if ($isAdjusment) {
            KartuStok::create([
                'jenis_transaksi' => 'adjustment',
                'nomor_sku' => $varian->nomor_sku,
                'stok_akhir' => $varian->stok_varian,
                'petugas' => Auth::user()->name,
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil di update'
        ]);
    }

    public function destroy($varian_produk)
    {
        $varian = VarianProduk::findOrFail($varian_produk);
        Storage::disk('public')->delete('varian-produk/' . $varian->gambar_varian);
        $varian->delete();

        toast()->success('Data berhasil dihapus');

        return redirect()->route('master-data.produk.show', $varian->produk_id);
    }

    public function getAllVarianJson()
    {
        $search = request()->query('search');
        //cari data produk dan varian
        $varians = VarianProduk::with('produk')
        ->where(function ($query) use ($search) {
            $query->where('nama_varian', 'like', '%' . $search . '%' )
            ->orWhere('nomor_sku', 'like', '%' . $search . '%')
            ->orWhereHas('produk', function ($query) use ($search) {
                $query->where('nama_produk', 'like', '%' . $search . '%');
            });
        })->get()->map(function($q){
            return[
                'id' => $q->id,
                'text' => $q->produk->nama_produk . " - " . $q->nama_varian,
                'harga' => $q->harga_varian,
                'stok' => $q->stok_varian,
                'nomor_sku' => $q->nomor_sku
            ];
        });

        return response()->json($varians);
    }
}
