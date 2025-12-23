<!-- Button trigger modal -->
<button type="button" class="btn {{ $id ? 'btn-secondary btn-icon' : 'btn-dark btn-round' }}" data-bs-toggle="modal"
    data-bs-target="#formProduk{{ $id ?? '' }}">
    @if ($id)
        <i class="fas fa-edit"></i>
    @else
        <span>Tambah Produk Baru</span>
    @endif
</button>

<!-- Modal -->
<div class="modal fade" id="formProduk{{ $id ?? '' }}" tabindex="-1" aria-labelledby="formProdukLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($id)
                @method('PUT')
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="formKategoriLabel">Form Produk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kategori_produk_id" class="form-label">Kategori Produk</label>
                        <select name="kategori_produk_id" id="kategori_produk_id" class="form-control">
                            <option value="">Pilih Kategori</option>
                            {{-- “Pilih kota kamu”
                                 Pilihan:
                                Jakarta (id=1)
                                Bandung (id=2)
                                makanya memakai $item->id --}}
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('kategori_produk_id', $kategori_produk_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_produk_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama_produk"
                            value="{{ old('nama_produk', $nama_produk ?? '') }}">
                        @error('nama_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                        <textarea name="deskripsi_produk" id="deskripsi_produk" cols="30" rows="5" class="form-control">
                            {{ old('deskripsi_produk', $deskripsi_produk ?? '') }}
                        </textarea>
                        @error('deskripsi_produk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
