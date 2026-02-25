<?php

namespace App\Http\Controllers;

use App\Models\ItemStokOpname;
use App\Models\PeriodeStokOpname;
use App\Models\VarianProduk;
use Illuminate\Http\Request;

class ItemStokOpnameController extends Controller
{
    public function updateProduk(Request $request)
    {
        $periodeId = $request->periode_id;
        $periode = PeriodeStokOpname::findOrFail($periodeId);
        $produk = VarianProduk::all();

        if (!$periode->is_active) {
            return response([
                'success' => false,
                'message' => 'Periode stok opname tidak aktif',
                'redirect_url' => route('stok-opname.periode.show', $periodeId)
            ]);
        }

        if (count($periode->items) == count($produk) ) {
            return response([
                'success' => false,
                'message' => 'Data sudah terupdate, tidak ada data baru yang ditambahkan',
                'redirect_url' => route('stok-opname.periode.show', $periodeId)
            ]);
        }


        foreach ($produk as $item) {
            ItemStokOpname::updateOrCreate(
                ['periode_stok_opname_id' => $periodeId, 'nomor_sku' => $item->nomor_sku],
                ['jumlah_stok' => $item->stok_varian]
            );
        }

        $periode->is_completed = 0;
        $periode->jumlah_barang = count($produk);
        $periode->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Produk berhasil diupdate',
            'redirect_url' => route('stok-opname.periode.show', $periodeId)
        ]);

    }
}
