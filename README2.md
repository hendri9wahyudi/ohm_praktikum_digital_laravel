# Implementasi Alat Praktikum Digital untuk Verifikasi Hukum Ohm Menggunakan Mikrokontroler

Starter project Laravel + Supabase + Node.js bridge untuk:
- login role: Guru dan Siswa
- materi hukum Ohm
- soal praktikum 4 paket (A/B/C/D)
- verifikasi jawaban
- tampilan sensor tegangan, arus, resistansi, suhu
- grafik hasil dan analisis
- dashboard guru untuk data siswa dan manajemen user

## Isi project
- `database/schema.sql` : schema PostgreSQL/Supabase + sample data
- `app/`, `routes/`, `resources/` : file Laravel inti
- `node/esp32-bridge.js` : contoh bridge Node.js untuk ESP32

## Kredensial sample
- Guru: `guru1 / guru1`
- Siswa Putera: `siswa1 / siswa1`
- Siswa Puteri: `siswa2 / siswa2`

## Cara menjalankan dari nol

### 1. Buat project Laravel
```bash
composer create-project laravel/laravel ohm-praktikum-digital
cd ohm-praktikum-digital
```

### 2. Copy file dari paket ini
Salin isi folder project ini ke project Laravel kamu.

### 3. Install paket yang dibutuhkan
```bash
composer require laravel/sanctum
npm install
npm install chart.js axios
```

### 4. Atur `.env`
Contoh Supabase/PostgreSQL:
```env
APP_NAME="Praktikum Hukum Ohm"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
DB_SSLMODE=require
```

### 5. Import database
Buka Supabase SQL Editor, lalu jalankan file `database/schema.sql`.

### 6. Jalankan Laravel
```bash
php artisan key:generate
php artisan serve
```

### 7. Jalankan frontend asset
```bash
npm run dev
```

### 8. Buka aplikasi
- Login guru: `/`
- Dashboard siswa setelah login
- Dashboard guru setelah login

## Catatan integrasi ESP32
- Endpoint ingest sensor ada di `POST /api/sensors/ingest`
- Node bridge di folder `node/` bisa dipakai sebagai perantara ESP32 ke Laravel
- Saat alat asli jadi, ubah bagian simulasi sensor di `StudentController` agar membaca data asli dari ESP32 atau bridge Node.js

## Alur fitur siswa
1. Login
2. Lihat materi
3. Kerjakan 4 soal
4. Klik **Next** untuk verifikasi jawaban
5. Klik **Start** untuk membaca sensor
6. Klik **Process** untuk grafik dan analisis
7. Klik **Finish** untuk simpan data

## Alur fitur guru
1. Login
2. Lihat data praktikum siswa
3. Edit nilai manual jika diperlukan
4. Simpan ke database
5. Kelola user guru dan siswa
