@extends('layouts.kai')
@section('content')
@section('page_title', $pageTitle)
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
                        <x-filter-by-field term="search" placeholder="Cari kategori produk" />
                    </div>
                    <div class="col-2 col-md-1 ps-2">
                        <x-button-reset-filter route="master-data.kategori-produk.index" />
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3 d-flex justify-content-md-end mt-3 mt-md-0 ">
                <x-kategori-produk.form-kategori-produk />
            </div>
        </div>

        <table class="table mt-5">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px">No</th>
                    <th>Kategori</th>
                    <th class="text-center" style="width: 100px">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>
                            <div class="d-flex alignt-items-center gap-1">
                                <x-kategori-produk.form-kategori-produk id="{{ $item->id }}" />
                                <x-confirm-delete id="{{ $item->id }}"
                                    route="master-data.kategori-produk.destroy" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Data Kategori Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $kategori->links() }}
    </div>
</div>
@endsection
