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
