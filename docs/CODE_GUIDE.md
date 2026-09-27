# Panduan Kode WriteAnything

## Alur request

1. **Route** di `routes/web.php` menerima URL dan menentukan controller atau view.
2. **Controller** memvalidasi input, mengecek hak akses, lalu memanggil model.
3. **Model** mewakili tabel database dan relasi antar data.
4. **Migration** mendefinisikan struktur tabel.
5. **Blade view** menampilkan data dan membuat form untuk request berikutnya.

## Alur membuat kartu

`dashboard.blade.php` mengirim form ke `cards.store` → `CardController@store` memvalidasi dan menyensor kata kasar → relasi `User::cards()` menyimpan kartu dengan status `pending` → admin mengubah status menjadi `approved` → wall publik mengambil kartu approved.

## Alur like, komentar, dan laporan

Ketiga route interaksi berada di luar middleware `auth`, jadi guest dan user login dapat menggunakannya. Like disimpan di session browser, komentar disimpan ke tabel `comments`, dan laporan disimpan ke tabel `reports` untuk ditinjau admin.

## Alur pertemanan

`FriendshipController` menyimpan pasangan `requester_id` dan `addressee_id`. Status `pending` berubah menjadi `accepted` ketika penerima menyetujui. Status ini disimpan di tabel `friendships`, dengan unique constraint agar hubungan yang sama tidak dibuat dua kali.

## Alur admin

`AdminController@guard` memastikan hanya user dengan `role=admin` yang dapat mengakses aksi admin. Ban disimpan sebagai `is_banned=true` tanpa tanggal kedaluwarsa; admin dapat menjalankan aksi yang sama untuk membuka blokir.

Komentar di file PHP menjelaskan keputusan dan aturan bisnis utama. View Blade memakai komentar section agar struktur halaman tetap mudah dipindai tanpa memenuhi setiap baris dengan narasi.
