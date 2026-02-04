<?php

namespace App\Http\Controllers;

use App\Models\KartuStok;
use App\Models\Transaksi;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\storeTransaksiKeluarRequest;

class TransaksiKeluarController extends Controller
{
    public $pageTitle = 'Transaksi Keluar';
    public $jenisTransaksi = 'pengeluaran';

    public function index()
    {
        $penerima = request()->query('penerima');
        $tanggalAwal = request()->query('tanggal_awal');
        $tanggalAkhir = request()->query('tanggal_akhir');
        $perPage = request()->query('perPage', 10);

        $query = Transaksi::query();
        $query->orderBy('created_at', 'Desc');
        $query->where('jenis_transaksi', $this->jenisTransaksi);

        if ($penerima) {
            $query->where('penerima', 'like', '%' . $penerima . '%');
        }

        if ($tanggalAwal && $tanggalAkhir) {
            $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->whereBetween('created_at', [$tanggalAwal,$tanggalAkhir]);
        }

        $transaksi = $query->paginate($perPage)->appends(request()->query());


        $pageTitle = $this->pageTitle;
        return view('transaksi-keluar.index', compact('pageTitle', 'transaksi'));
    }

    public function create() 
    {
        $pageTitle = $this->pageTitle;
        return view('transaksi-keluar.create', compact('pageTitle'));
    }

    public function show($nomor_transaksi)
    {
        $pageTitle = "Detail" . $this->pageTitle;
        $transaksi = Transaksi::with('items')->where('nomor_transaksi', $nomor_transaksi)->first();
        $transaksi->formated_date = Carbon::parse($transaksi->created_at)->locale('id')->translatedFormat('l, d F Y');
        return view('transaksi-keluar.show', compact('transaksi', 'pageTitle'));
    }

    public function store(storeTransaksiKeluarRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
            ], 422);
        }

        $nomorTransaksi = Transaksi::generateNomorTransaksi($this->jenisTransaksi);
        //request item diambil dari create.blade.php  items: selectedProduk,
        $items = $request->items;

        $transaksi = Transaksi::create([
            'nomor_transaksi' => $nomorTransaksi,
            'jenis_transaksi' => $this->jenisTransaksi,
            'jumlah_barang' => count($items),
            //berbeda dg transaksi masuk total harga kita ambil dari controller bkn blade (subTotal) agar tidak dimanipulasi yaitu dg set total harga transaksi 0
            'total_harga' => 0,
            'keterangan' => $request->keterangan,
            'petugas' => Auth::user()->name,
            'penerima' => $request->penerima,
            'kontak' => $request->kontak,
        ]);

        foreach ($items as $item) {
            //pecah text menjadi array untuk diambil nama produknya data ke 1 yaitu produk bkn beserta varian
            $query = explode('-', $item['text']);
            $varian = VarianProduk::where('nomor_sku', $item['nomor_sku'])->first();

            $transaksi->items()->create([
                'transaksi_id' => $transaksi->id,
                'produk' => $query[0],
                'varian' => $query[1],
                'qty' => $item['qty'],
                //lanjutan total_harga disini harga diambil dari varian bukan frontendnya dan sub_total diambil dari varian->harga varian dikali jumlah qty
                'harga' => $varian->harga_varian,
                'sub_total' => $varian->harga_varian * $item['qty'],
                'nomor_sku' => $item['nomor_sku'],
            ]);
          $varian->decrement('stok_varian', $item['qty']);
          KartuStok::create([
            'nomor_transaksi' => $nomorTransaksi,
            'jenis_transaksi' => 'out',
            'nomor_sku' => $item['nomor_sku'],
            'jumlah_keluar' => $item['qty'],
            'stok_akhir' => $varian->stok_varian,
            'petugas' => Auth::user()->name,
          ]);

          //lanjutan total harga kita set transaksi total_harga adlah hasil perjumlahan dari varian->harga_varian dikali item qty
          $transaksi->total_harga += $varian->harga_varian * $item['qty'];
          $transaksi->save();
        }

        toast()->success('Transaksi masuk berhasil ditambahkan');
        return response()->json([
            'success' =>true,
            'redirect_url' => route('transaksi-keluar.create'),
        ]);
    }

    public function getTransaksiKeluar()
    {
        $search = request()->query('search');
        $transaksi = Transaksi::where('jenis_transaksi', 'pemasukan')
        ->where('nomor_transaksi', 'like', '%' . $search . '%')
        ->get()->map(function ($q){
            return [
                'id' => $q->id,
                'text' => $q->nomor_transaksi,
            ];
        });

        return response()->json($transaksi);
    }

    public function getTransaksiKeluarItems($nomor_transaksi)
    {
        $transaksi = Transaksi::with('items', 'items.varian')->where('nomor_transaksi', $nomor_transaksi)->first();
        $transaksi->tanggal = Carbon::parse($transaksi->created_at)->locale('id')->translatedFormat('l, d F Y');
        return response()->json($transaksi);
    }
}
