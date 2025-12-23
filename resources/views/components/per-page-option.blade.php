<div class="d-flex align-items-center">
    <span class="me-1">Tampilkan</span>
    <select name="perPage" id="perPage" class="form-control" onchange="window.location.href = '?perPage=' + this.value"
        style="width: 50px">
        @foreach ($perPageOptions as $item)
            <option value="{{ $item }}" {{ request('perPage', 10) == $item ? 'selected' : '' }}>
                {{ $item }}</option>
        @endforeach
    </select>
</div>
