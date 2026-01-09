Laravel Modern Todo List 📝

Aplikasi manajemen tugas sederhana namun elegan yang dibangun menggunakan Laravel. Aplikasi ini dirancang dengan antarmuka yang bersih (clean UI), modern, dan interaktif tanpa perlu memuat ulang halaman (No Page Reload) menggunakan teknologi AJAX/Fetch API.



✨ Fitur Utama

⚡ Interaksi Tanpa Reload (AJAX): Menambah, menandai selesai, dan menghapus tugas terjadi secara instan tanpa refresh browser.

🎨 Desain Minimalis & Modern: Menggunakan Tailwind CSS dengan tema "Clean White" yang profesional.

🔔 Notifikasi Modern: Menggunakan library Notiflix untuk notifikasi toast dan popup konfirmasi yang halus (bukan alert browser kaku).

📱 Responsif: Tampilan menyesuaikan dengan baik di Desktop, Tablet, maupun Mobile.

🔢 Realtime Counter: Penghitung jumlah tugas update secara otomatis saat tugas ditambah atau dihapus.

🛠️ Teknologi yang Digunakan

Backend: Laravel 10/11 (PHP Framework)

Frontend Styling: Tailwind CSS (via CDN)

Scripting: Vanilla JavaScript (Fetch API)

Libraries:

Notiflix (Untuk Notifikasi & Konfirmasi)

FontAwesome (Untuk Ikon)

Database: MySQL

⚙️ Prasyarat

Sebelum memulai, pastikan komputer Anda sudah terinstall:

PHP >= 8.1

Composer

Database Server (MySQL/MariaDB via XAMPP atau Laragon)

🚀 Panduan Instalasi (Step-by-Step)

Ikuti langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

1. Clone atau Download Project

Masuk ke folder proyek Anda melalui terminal/command prompt.

2. Install Dependensi PHP

Jalankan perintah ini untuk mengunduh semua library Laravel yang dibutuhkan:

composer install


3. Konfigurasi Environment (.env)

Salin file konfigurasi contoh:

cp .env.example .env


Buka file .env tersebut dengan teks editor, lalu cari bagian Database dan sesuaikan dengan setting MySQL Anda:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_todo      # <-- Pastikan nama database ini sudah dibuat di phpMyAdmin
DB_USERNAME=root         # <-- Default XAMPP biasanya 'root'
DB_PASSWORD=             # <-- Default XAMPP biasanya kosong


4. Generate Application Key

Wajib dijalankan untuk keamanan sesi Laravel:

php artisan key:generate


5. Migrasi Database

Perintah ini akan membuat tabel todos di database Anda:

php artisan migrate


6. Jalankan Server

Mulai server lokal Laravel:

php artisan serve


Aplikasi sekarang dapat diakses di browser melalui alamat:
👉 https://www.google.com/search?q=http://127.0.0.1:8000

📂 Struktur Kode Penting

Jika Anda ingin memodifikasi aplikasi, berikut adalah file-file kuncinya:

Tampilan (View & JS): resources/views/todos.blade.php

Berisi HTML, Tailwind CSS, dan logika JavaScript (Fetch API & Notiflix).

Controller: app/Http/Controllers/TodoController.php

Menangani logika backend dan mengembalikan respon JSON.

Routing: routes/web.php

Mengatur URL endpoint aplikasi.

Model: app/Models/Todo.php

Konfigurasi interaksi dengan tabel database.

🤝 Lisensi

Proyek ini bersifat Open Source dan bebas digunakan untuk pembelajaran.

Dibuat dengan ❤️ menggunakan Laravel.
