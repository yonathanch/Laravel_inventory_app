@extends('layouts.kai')
@section('page_title', $pageTitle)
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Detail : {{ $produk->nama_produk }}</h4>
            <a href="{{ route('master-data.produk.index') }}">Kembali</a>
        </div>
        <div class="card-body py-4">
            <x-meta-item label="Nama Produk" value="{{ $produk->nama_produk }}" />
            <x-meta-item label="Deskripsi" value="{{ $produk->deskripsi_produk }}" />
            <x-meta-item label="Kategori" value="{{ $produk->kategori->nama_kategori }}" />
            <div class="mt-2">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-dark btn-sm btn-round" data-bs-toggle="modal"
                        data-bs-target="#modalFormVarian">
                        Tambah Varian
                    </button>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="alert alert-info" style="box-shadow: none;">
                            <span>Belum ada variant produk, silahkan tambahkan variant terlebih dahulu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-produk.form-varian />
@endsection
