@extends('layouts.kai')
@section('page_title', $pageTitle)
@section('content')
    <div class="card">
        <div class="card-body py-5">
            <div class="row align-items-center g-2">
                <!-- PerPage + Search -->
                <div class="col-12 col-md-9">
                    <div class="row g-2 align-items-center">
                        <div class="col-4 col-md-3">
                            <x-per-page-option />
                        </div>
                        <div class="col-6 col-md-8 ">
                            <x-filter-by-field term="search" placeholder="Cari Produk" />
                        </div>
                        <div class="col-2 col-md-1 ps-2">
                            <x-button-reset-filter route="master-data.produk.index" />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 d-flex justify-content-md-end mt-3 mt-md-0 ">
                    <x-produk.form-produk />
                </div>
            </div>

            <table class="table mt-5">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 15px">No</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th class="text-center" style="width: 100px">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('master-data.produk.show', $item->id) }}" class="text-decoration-none">
                                    {{ $item->nama_produk }}
                                </a>
                            </td>
                            <td>{{ $item->kategori->nama_kategori }}</td>
                            <td>
                                <div class="d-flex alignt-items-center gap-1">
                                    <x-produk.form-produk id="{{ $item->id }}" />
                                    <x-confirm-delete id="{{ $item->id }}" route="master-data.produk.destroy" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Data Produk Kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $produk->links() }}
        </div>
    </div>
@endsection
