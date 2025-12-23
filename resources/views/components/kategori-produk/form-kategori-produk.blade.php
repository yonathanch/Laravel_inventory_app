<!-- Button trigger modal -->
<button type="button" class="btn  {{ $id ? 'btn-secondary btn-icon' : 'btn-dark btn-round' }}" data-bs-toggle="modal"
    data-bs-target="#formKategori{{ $id ?? '' }}">
    @if ($id)
        <i class="fas fa-edit"></i>
    @else
        <span>Tambah Kategori Baru</span>
    @endif
</button>

<!-- Modal -->
<div class="modal fade" id="formKategori{{ $id ?? '' }}" tabindex="-1" aria-labelledby="formKategoriLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($id)
                @method('PUT')
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="formKategoriLabel">Form Kategori Produk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" id="name_kategori"
                            value="{{ old('nama_kategori', $nama_kategori ?? '') }}">
                        @error('nama_kategori')
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
