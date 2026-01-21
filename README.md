# Volunteer Event Management API

Project ini adalah RESTful API sederhana untuk manajemen event menggunakan Laravel. Fitur utama mencakup autentikasi user, manajemen CRUD event, dan fitur bergabung (join) ke dalam event.

## 1. Cara Install

Pastikan Anda sudah menginstall PHP >= 8.2, Composer, dan MySQL/MariaDB.

1.  **Clone repository**:
    ```bash
    git clone https://github.com/yanxcod3/RestAPI-Event-Management.git
    cd RestAPI-Event-Management
    ```

2.  **Install dependencies**:
    ```bash
    composer install
    ```

3.  **Salin file lingkungan**:
    ```bash
    cp .env.example .env
    ```

4.  **Konfigurasi Database**:
    Buka file `.env` dan sesuaikan pengaturan database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=volunteer_events
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database**:
    ```bash
    php artisan migrate
    ```

7.  **Jalankan Seeder (Opsional)**:
    Untuk mengisi data dummy (User dan Event):
    ```bash
    php artisan db:seed
    ```

## 2. Cara Menjalankan Project

1.  **Jalankan Server Lokal**:
    ```bash
    php artisan serve
    ```
    API akan dapat diakses di `http://127.0.0.1:8000/api`.

## 3. Daftar Endpoint API

Semua endpoint API diawali dengan `/api`. Endpoint yang ditandai (Auth) memerlukan token Bearer yang didapat setelah login.

Dokumentasi API (Postman):  
👉 https://www.postman.com/yanxcod3-4063/workspace/docs-event-management

### Autentikasi
| Method | Endpoint | Deskripsi | Status |
| :--- | :--- | :--- | :--- |
| `POST` | `/register` | Registrasi user baru | Public |
| `POST` | `/login` | Login user untuk mendapatkan token | Public |
| `POST` | `/logout` | Logout dan menghapus token aktif | Auth |

### Manajemen Event
| Method | Endpoint | Deskripsi | Status |
| :--- | :--- | :--- | :--- |
| `GET` | `/events` | Menampilkan daftar semua event (paginated) | Auth |
| `POST` | `/events` | Membuat event baru | Auth |
| `GET` | `/events/{id}` | Menampilkan detail satu event | Auth |
| `PUT` | `/events/{id}` | Memperbarui event (hanya pemilik) | Auth |
| `DELETE` | `/events/{id}` | Menghapus event (hanya pemilik) | Auth |
| `POST` | `/events/{id}/join` | Bergabung ke dalam event | Auth |

## 4. Catatan Asumsi/Desain

1.  **Autentikasi**: Menggunakan **Laravel Sanctum** untuk menangani otentikasi berbasis token API. Token harus dikirimkan melalui header `Authorization: Bearer <token>`.
2.  **Otorisasi**:
    - Siapa pun yang login dapat membuat event.
    - Hanya user yang membuat event (**creator**) yang diizinkan untuk mengubah (`PUT`) atau menghapus (`DELETE`) event tersebut. Ini dikelola melalui `EventPolicy`.
3.  **Relasi Database**:
    - **One-to-Many**: `User` memiliki banyak `Event` (sebagai creator).
    - **Many-to-Many**: `User` dapat bergabung ke banyak `Event`, dan satu `Event` dapat diikuti oleh banyak `User` (pengguna). Tabel pivot `event_user` digunakan untuk ini.
4.  **Validasi**:
    - User tidak dapat bergabung ke event yang sama lebih dari satu kali (validasi di `EventController@join`).
    - Input data divalidasi dengan ketat (misal: `event_date` harus berupa format tanggal yang valid).
5.  **Pagination**: Daftar event menggunakan paginasi default 10 item per halaman untuk menjaga performa.
6.  **Resource API**: Menggunakan `AuthResource` dan `EventResource` untuk memastikan struktur JSON yang konsisten dalam setiap respon API.

## Pertanyaan Wajib

### 1) Bagian tersulit apa dari assignment ini?
Bagian tersulit adalah membuat **format error response yang konsisten**, terutama untuk error validasi bawaan Laravel. Saat ini saya belum sepenuhnya mengimplementasikan custom format response untuk semua kasus validasi.

### 2) Jika diberi waktu 1 minggu, apa yang akan kamu perbaiki?
- Menstandarkan **format error response** untuk semua jenis error (validasi, unauthorized, not found, dll).
- Membuat dokumentasi API lebih lengkap (contoh request/response, status code, dan skenario error).

### 3) Kenapa memilih pendekatan teknis tersebut?
- Menggunakan Laravel Sanctum untuk autentikasi API.
- Menggunakan API Resource untuk memastikan struktur response JSON konsisten dan mudah dikembangkan.
- Menggunakan Policy/Authorization untuk membatasi update/delete hanya untuk creator event.
- Menggunakan Laravel Validator untuk memastikan input tervalidasi dan aman.