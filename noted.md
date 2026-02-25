-logika bisnis transaksi masuk dan keluar beda , transaksi masuk .
Kenapa di transaksi masuk total_harga diambil dari frontend?
-Harga beli ditentukan oleh supplier
-Harga BELUM tentu sama dengan harga_varian di database
-Harga di DB adalah harga lama
-Harga baru datang dari user (frontend)

Jadi backend memang membutuhkan harga dari frontend untuk:
-mencatat harga beli baru
-mendeteksi kenaikan harga
-menyimpan histori harga

kenapa transaksi keluar total harga harus diambil dsari database? bkn fornt end:
-karena Harga jual harus konsisten
-Tidak boleh dipengaruhi user
-Frontend hanya display

nomor sku = kode barang, nomor_transaksi = nomor struk

Urutan crud ajax :kita bikin frontend dan masukan ke data controller


-di kartu stok.blade
          logika kedua jika perpagenya agar data log kosong tiodak ada perpage
                        //                 if (response.meta.last_page > 1) {
                        //                     const meta = response.meta;

                        //                     let paginationHtml =
                        //                         '<nav><ul class="pagination justify-content-center gap-1">';

                        //                     meta.links.forEach(link => {
                        //                         const isNumber = /^\d+$/.test(link.label);
                        //                         if (!isNumber) return;

                        //                         paginationHtml += `
                    //     <li class="page-item">
                    //         <a class="page-link ${link.active ? 'bg-dark text-white' : ''}" href="${link.url}">
                    //             ${link.label}
                    //         </a>
                    //     </li>                                    
                    // `;
                        //                     });

                        //                     paginationHtml += '</ul></nav>';
                        //                     $pagination.html(paginationHtml);
                        //                 } else {
                        //                     $pagination.empty(); // ini penting
                        //                 }


atau make cara yg pertama tapi tambahkan empty agar perpage selalu kosong ketika kita klik datanya:

                     $pagination.empty(); 
                        if (response.meta.total > response.meta.per_page) {
                            const meta = response.meta;

                            let paginationHtml =
                                '<nav><ul class="pagination justify-content-center gap-1">'
                            meta.links.forEach(link => {
                                //untuk menghilangkan previus dan next
                                const isNumber = /^\d+$/.test(link.label);
                                if (!isNumber) return;

                                paginationHtml += `
                                    <li class="page-item">
                                        <a class="page-link ${link.active ? 'bg-dark text-white' : ''}" href="${link.url}">
                                            ${link.label}
                                        </a>
                                    </li>                                    
                                `
                            });
                            paginationHtml += '</ul></nav>';
                            $pagination.html(paginationHtml);
                        }



-di PeriodeStokOpnameController ada sedikit bug dibagian s/d nya seharusnya ketika memakai:
      $periode = $tanggalMulai . ' s/d ' . $tanggalSelesai;
    
    s/d nya itu ada jarak sebenernya tapi di web akan tampil mepet solusinya jika kita ingin meemaksa memkaai s/d

    kita harus pakai &nbsp; misalnya:
      $periode = $tanggalMulai . ' &nbsp;s/d&nbsp; ' . $tanggalSelesai;

    lalu di index.blade folder stok opname/periode:
    <td>{ !!$item['periode'] !!}</td>

    maka hasilnya s/d nya akan ada spasi dg teks sebelum dan sesudah nya.

pada periodestokopnamecontroller:
- Perlu map kalau datanya banyak.
Tidak perlu map kalau datanya satu.
Gunakan map() ketika kamu ingin:

✔ Mengubah isi tiap item
✔ Menambahkan attribute baru
✔ Mengubah struktur data
✔ Menyiapkan data untuk API / view

-penambahan atribut tambahan misalnya:
   $dataPeriode['periode'] = $periode;
    $dataPeriode['jumlah_barang_sesuai'] 
    $dataPeriode['jumlah_barang_selisih'] 
    berfungsi untuk menaruh datanya ke dlm variabel tsb agar di blade kita bsa langsung pake:
     <x-meta-item label="Periode" value="{{ $dataPeriode->periode }}" />
     <x-meta-item label="Jumlah barang" value="{{ $dataPeriode->jumlah_barang }}" />
     <x-meta-item label="Jumlah barang Sesuai" value="{{ $dataPeriode->jumlah_barang_sesuai }}" />

- install laravel excel khsuus php 8.2: composer require maatwebsite/excel:3.1.67

-- permaslahan fitur export kartu stok
itu ada ketika saya mencet kartu stok SKU000003 tapi ketika saya kllik export itu malah ke SKU000001 mlu saya udh coba di sku SKU000002 dan lain lain balik lagi malah ke SKU000001

sebelumnya kodenya seperti ini di kartu-stok.blade:
<button type="button" class="btn btn-default btn-kartu-stok text-primary" data-bs-toggle="modal"
    data-bs-target="#kartuStokModal" data-nomor-sku="{{ $nomor_sku }}">
    Kartu Stok
</button>

<!-- Modal -->
<div class="modal fade" id="kartuStokModal" tabindex="-1" aria-labelledby="kartuStokModalLabel"


Penyebab Utama
1️⃣ Modal dibuat di dalam loop (melalui component)

Di views/stok-barang/index:

@forelse ($produk as $item)
    <x-kartu-stok nomor_sku="{{ $item['nomor_sku'] }}" />
@endforelse

Artinya component <x-kartu-stok> dipanggil berulang sesuai jumlah data. Kalau ada 5 produk → modal ikut dibuat 5 kali.

2️⃣ ID modal ditulis statis (tidak unik)

Di dalam kartu-stok.blade.php kemungkinan ada:

<div class="modal fade" id="kartuStokModal"> dan <div class="modal fade" id="kartuStokModal" tabindex="-1" aria-labelledby="kartuStokModalLabel"

Karena ID tidak berubah, maka hasil akhirnya di browser:
<div id="kartuStokModal">SKU1</div>
<div id="kartuStokModal">SKU2</div>
<div id="kartuStokModal">SKU3</div>

⚠ Padahal dalam HTML: ID harus unik, tidak boleh duplicate.

3️⃣ Browser selalu mengambil ID pertama

Saat tombol memanggil: data-bs-target="#kartuStokModal"

Browser akan:
Menemukan ID pertama
Membuka modal pertama (SKU1)
Export mengambil data SKU1
Walaupun user klik baris ke-5.

🎯 Kenapa Terjadi?

Karena:
Component dipanggil dalam loop
Modal ada di dalam component
ID modal tidak dibuat dinamis
HTML tidak memperbolehkan ID sama

✅ Solusi

Buat ID unik dan data-bs-target berdasarkan SKU:
data-bs-target="#kartuStokModal-{{ $nomor_sku }}"
id="kartuStokModal-{{ $nomor_sku }}"

Sehingga hasilnya:

kartuStokModal-SKU1
kartuStokModal-SKU5
kartuStokModal-SKU10

Sekarang setiap tombol membuka modal yang benar.

🔥 Inti Pelajaran
-Component di dalam loop = isinya ikut ter-render berulang
-ID HTML harus selalu unik
-Jangan hardcode ID jika dipakai dalam loop
-Gunakan variable (SKU / index) sebagai pembeda