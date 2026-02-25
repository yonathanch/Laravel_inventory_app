<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal"
        data-bs-target="#formInputItem{{ $item['id'] ?? '' }}">
        Laporkan
    </button>

    <!-- Modal -->
    <div class="modal fade" id="formInputItem{{ $item['id'] ?? '' }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="formInputItemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('stok-opname.input-data.update', $item['id']) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="formInputItemLabel">Form Input Stok Opname</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="produk" class="form-label">Produk</label>
                        <input type="text" id="produk" name="produk" class="form-control"
                            value="{{ $item['produk'] }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomor_sku" class="form-label">Nomor SKU</label>
                        <input type="text" id="nomor_sku" name="nomor_sku" class="form-control"
                            value="{{ $item['nomor_sku'] }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
                        <input type="number" id="jumlah_stok" name="jumlah_stok" class="form-control"
                            value="{{ $item->jumlah_stok }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_dilaporkan" class="form-label">Jumlah Dilaporkan</label>
                        <input type="number" id="jumlah_dilaporkan" name="jumlah_dilaporkan" class="form-control"
                            value="{{ $item->jumlah_dilaporkan }}">
                        @error('jumlah_dilaporkan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea id="keterangan" cols="30" rows="5" name="keterangan" class="form-control">
                            {{ $item->keterangan }}
                        </textarea>
                        @error('keterangan')
                            <small class="text-danger">{{ $messsage }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-secondary">Laporkan</button>
                </div>
            </form>
        </div>
    </div>

</div>
