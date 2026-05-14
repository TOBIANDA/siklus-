# BAB 5: Rencana Implementasi & Pengujian

## 5.1 Pemilihan Stack Teknologi

Pengembangan platform Siklus menggunakan kombinasi teknologi modern yang dipilih berdasarkan kriteria performa, skalabilitas, dan kecepatan pengembangan. Berikut adalah stack teknologi yang digunakan:

### 1. Frontend
*   **Teknologi:** HTML5, CSS3, Vanilla JavaScript, dan Blade Template Engine.
*   **Alasan Pemilihan:** Blade (bawaan Laravel) memungkinkan integrasi data yang sangat mudah dari *controller* ke antarmuka tanpa memerlukan arsitektur API terpisah, sehingga sangat mempercepat proses *development*. Penggunaan Vanilla JavaScript (bersama dengan DOM *manipulation*) dipilih untuk fungsionalitas dinamis yang spesifik (seperti *toggle password visibility* dan interaksi form) agar meminimalkan ukuran *bundle* dan meningkatkan kecepatan pemuatan halaman. Tailwind CSS (via Vite) juga didukung untuk utilitas gaya secara cepat.

### 2. Backend
*   **Teknologi:** Laravel 12 (PHP 8.2).
*   **Alasan Pemilihan:** Laravel merupakan *framework* PHP MVC (Model-View-Controller) yang sangat matang. Laravel menyediakan fitur bawaan *out-of-the-box* seperti *routing*, sistem *authentication* berlapis, Eloquent ORM untuk pengelolaan *database* relasional dengan mudah, serta proteksi keamanan yang kokoh (CSRF protection, pencegahan SQL Injection). Hal ini menjamin keandalan platform *peer-to-peer* (P2P) peminjaman buku seperti Siklus.

### 3. Database
*   **Teknologi:** MySQL.
*   **Alasan Pemilihan:** MySQL merupakan sistem manajemen basis data relasional (RDBMS) yang stabil, performanya cepat untuk operasi baca-tulis, dan sangat kompatibel dengan Eloquent ORM dari Laravel. Mengingat aplikasi Siklus membutuhkan relasi data yang kompleks (seperti relasi *One-to-Many* dan *Many-to-Many* antar tabel `users`, `books`, `borrow_requests`, dan `messages`), MySQL memberikan struktur tabel yang konsisten dengan integritas referensial yang terjamin (*Foreign Key*).

---

## 5.2 Rencana Implementasi

Proses implementasi dilakukan secara bertahap dan iteratif untuk memastikan setiap modul berfungsi dengan baik sebelum diintegrasikan secara menyeluruh. Tahapan pengerjaannya adalah sebagai berikut:

1.  **Tahap 1: Persiapan Lingkungan & Database**
    *   Menginisialisasi proyek Laravel, mengatur *environment* (`.env`), dan menginstal dependensi (Composer & NPM).
    *   Merancang *schema database*, membuat *migration* tabel, dan menyiapkan *dummy data* menggunakan *Seeder/Factory* untuk keperluan *testing*.
2.  **Tahap 2: Implementasi Autentikasi & Manajemen Pengguna**
    *   Membangun fitur Registrasi, Login, dan Logout.
    *   Membuat halaman Profil Pengguna (*Settings*) untuk menampilkan dan memperbarui data akun.
3.  **Tahap 3: Implementasi Modul Utama (CRUD Buku & Peminjaman)**
    *   Membangun fitur manajemen buku (Tambah, Lihat detail, dan Hapus buku).
    *   Mengimplementasikan alur peminjaman P2P (Mengajukan *request borrow*, memproses *accept/reject* oleh pemilik, dan memperbarui status ketersediaan buku).
4.  **Tahap 4: Implementasi Modul Komunikasi (Chat)**
    *   Membangun fitur perpesanan (*messaging*) antar pengguna terkait buku yang dipinjam.
    *   Menampilkan *thread* obrolan secara dinamis berdasarkan histori pengajuan di *database*.
5.  **Tahap 5: Pengujian & Penyempurnaan Antarmuka (UI/UX)**
    *   Menghilangkan sisa *mock data* (data statis) dan memastikan semua komponen menggunakan data *real* dari *database*.
    *   Merapikan tampilan (*layouting*) dan memastikan aplikasi berjalan responsif di berbagai ukuran layar.

---

## 5.3 Skenario Verifikasi

Pengujian dilakukan menggunakan pendekatan *Black Box Testing* untuk memastikan fungsi sistem berjalan sesuai dengan kebutuhan. Berikut adalah *test case* utama untuk fungsionalitas inti:

### Kasus Uji 1: Autentikasi Pengguna
| No | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 1.1 | Login dengan kredensial valid | Masukkan email & password terdaftar, klik "Log in" | Sistem mengarahkan ke halaman utama (*Home/Dashboard*) | [ ] |
| 1.2 | Login dengan data tidak valid | Masukkan kombinasi email/password salah, klik "Log in" | Muncul pesan error kredensial tidak cocok | [ ] |
| 1.3 | Logout dari sistem | Buka halaman *Settings*, klik tombol "Logout" | Sesi dihentikan, pengguna diarahkan kembali ke halaman *Login* | [ ] |

### Kasus Uji 2: Manajemen Data Buku
| No | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 2.1 | Menambah buku baru | Isi form tambah buku (judul, pengarang, deskripsi), klik simpan | Buku berhasil tersimpan dan muncul di daftar koleksi akun | [ ] |
| 2.2 | Melihat detail buku | Klik salah satu judul buku dari beranda/koleksi | Menampilkan halaman detail informasi buku secara spesifik | [ ] |
| 2.3 | Menghapus buku | Buka detail buku milik sendiri, klik opsi "Delete" | Data buku terhapus dari *database* dan daftar koleksi | [ ] |

### Kasus Uji 3: Transaksi Peminjaman (P2P)
| No | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 3.1 | Mengajukan peminjaman | User A menekan tombol "Borrow" pada buku milik User B | *Request* tercatat, status buku menunggu konfirmasi pemilik | [ ] |
| 3.2 | Menyetujui peminjaman | User B melihat permintaan, lalu menekan tombol "Accept" | Status buku berubah dipinjamkan (*Lent*), alur pesan otomatis terbuka | [ ] |
| 3.3 | Menolak peminjaman | User B melihat permintaan, lalu menekan tombol "Reject" | Status kembali tersedia, peminjam menerima info penolakan | [ ] |

### Kasus Uji 4: Fungsionalitas Pesan (Messaging)
| No | Skenario Pengujian | Langkah-Langkah | Hasil yang Diharapkan | Status |
|---|---|---|---|---|
| 4.1 | Menampilkan daftar pesan | Buka menu *Messages* | Tampil daftar *thread* obrolan terkait transaksi buku aktif | [ ] |
| 4.2 | Mengirim pesan balasan | Buka satu *thread*, ketik pesan di kolom input, tekan "Send" | Pesan baru masuk ke layar *chat* kedua belah pihak secara berurutan | [ ] |
