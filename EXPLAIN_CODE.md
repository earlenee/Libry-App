# 🧠 Penjelasan Kode LIBRY — Untuk Anak SMK Kelas 10

Dokumen ini menjelaskan bagian-bagian kode yang **unik dan penting** di project LIBRY. Penjelasannya dibuat semudah mungkin supaya bisa dipahami siswa SMK kelas 10.

---

## Daftar Isi

1. [Koneksi Database](#1-koneksi-database)
2. [Prepared Statement (Anti SQL Injection)](#2-prepared-statement-anti-sql-injection)
3. [Password Hashing (Keamanan Password)](#3-password-hashing-keamanan-password)
4. [Session — Mengingat Siapa yang Login](#4-session--mengingat-siapa-yang-login)
5. [Redirect — Memindahkan Halaman Otomatis](#5-redirect--memindahkan-halaman-otomatis)
6. [AJAX & Fetch — Kirim Data Tanpa Reload](#6-ajax--fetch--kirim-data-tanpa-reload)
7. [navigator.sendBeacon — Simpan Data Saat Tab Ditutup](#7-navigatorsendbeacon--simpan-data-saat-tab-ditutup)
8. [Debounce — Mengurangi Spam Request](#8-debounce--mengurangi-spam-request)
9. [htmlspecialchars — Mencegah Serangan XSS](#9-htmlspecialchars--mencegah-serangan-xss)
10. [LocalStorage — Menyimpan Data di Browser](#10-localstorage--menyimpan-data-di-browser)
11. [Upload File — Mengunggah Gambar](#11-upload-file--mengunggah-gambar)
12. [Scroll Event — Mendeteksi Posisi Baca](#12-scroll-event--mendeteksi-posisi-baca)
13. [Template Literal — Cara Keren Nulis String](#13-template-literal--cara-keren-nulis-string)
14. [Include — Komponen yang Bisa Dipakai Ulang](#14-include--komponen-yang-bisa-dipakai-ulang)
15. [Ternary Operator — IF Singkat](#15-ternary-operator--if-singkat)

---

## 1. Koneksi Database

📁 **File:** `config/koneksi.php`

```php
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
```

### Penjelasan Simpel:
Ini kayak **nyambungin kabel** antara website kita ke database MySQL. Tanpa ini, website ga bisa ambil atau simpan data apapun.

- `new mysqli(...)` = bikin koneksi baru ke database
- `$conn` = variabel yang nyimpen koneksi itu (kayak remot TV, yang kita pake buat ngontrol database)
- `die()` = kalau gagal konek, hentikan program dan tampilin pesan error

### Kenapa Penting?
Semua halaman yang butuh data dari database **harus** panggil file ini dulu pakai `require '../config/koneksi.php'`.

---

## 2. Prepared Statement (Anti SQL Injection)

📁 **File:** `actions/auth_signin.php`

```php
// CARA YANG BENAR ✅
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
```

```php
// CARA YANG SALAH ❌ (BAHAYA!)
$query = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($query);
```

### Penjelasan Simpel:
Bayangin kamu punya formulir dan ada tanda `?` di tempat yang harus diisi. Kamu kasih formulirnya ke MySQL dulu (prepare), baru kemudian isi datanya (bind_param). Jadi MySQL udah tau mana yang "formulir" dan mana yang "isian", sehingga **ga bisa ditipu** oleh input jahat.

Kalau pakai cara yang salah, hacker bisa ketik sesuatu kayak `' OR 1=1 --` di username, dan langsung bisa login tanpa password! Ini namanya **SQL Injection**.

### Arti Huruf di `bind_param`:
| Huruf | Tipe Data | Contoh |
|-------|-----------|--------|
| `s` | String (teks) | "Libry" |
| `i` | Integer (angka bulat) | 42 |
| `d` | Double (desimal) | 3.14 |

Contoh: `bind_param("si", $nama, $id)` artinya parameter pertama string, kedua integer.

---

## 3. Password Hashing (Keamanan Password)

📁 **File:** `actions/auth_signup.php` dan `auth_signin.php`

```php
// Saat REGISTRASI — enkripsi password sebelum disimpan
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Saat LOGIN — cek apakah password yang diketik cocok
if (password_verify($password, $user['password'])) {
    // login berhasil!
}
```

### Penjelasan Simpel:
Password **TIDAK BOLEH** disimpan langsung di database. Kenapa? Karena kalau database-nya dibobol hacker, semua password user ketahuan!

Makanya kita pakai `password_hash` yang mengubah password jadi kode acak yang mustahil ditebak:

```
Input:  "libry123"
Output: "$2y$10$xK3jF8H7dL9mN2pQ4rS6..."  (setiap kali beda!)
```

`password_verify` yang keren banget — dia bisa ngecek apakah "libry123" cocok dengan kode acak tadi, **tanpa** perlu tau password aslinya.

---

## 4. Session — Mengingat Siapa yang Login

📁 **File:** Hampir semua file

```php
session_start();  // WAJIB di baris pertama setiap file yang pakai session

// Simpan data ke session (saat login berhasil)
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['name'] = $user['name'];

// Ambil data dari session (di halaman lain)
$user_name = $_SESSION['name'];  // "Libry"

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
```

### Penjelasan Simpel:
Session itu kayak **gelang VIP** di konser. Pas kamu login, server kasih gelang (session ID). Setiap kali kamu pindah halaman, server liat gelangmu dan langsung tau kamu siapa.

Tanpa session, setiap buka halaman baru kamu harus login ulang — bayangkan betapa repotnya!

### Hal Penting:
- `session_start()` **WAJIB** dipanggil sebelum kode apapun (bahkan sebelum spasi atau enter!)
- `session_destroy()` = hapus gelang (logout)
- Data session disimpan di server, bukan di browser

---

## 5. Redirect — Memindahkan Halaman Otomatis

📁 **File:** `actions/auth_signin.php`

```php
header("Location: ../page/dashboard.php");
exit;
```

### Penjelasan Simpel:
`header("Location: ...")` itu kayak ngomong ke browser: *"Eh pindah ke halaman ini ya!"* — dan browser langsung pindah otomatis.

`exit` setelah redirect itu **WAJIB**. Kalau ga ada, kode di bawahnya masih bisa jalan walaupun user udah dipindahin. Ini bisa jadi celah keamanan!

---

## 6. AJAX & Fetch — Kirim Data Tanpa Reload

📁 **File:** `components/top_navbar.php` (Global Search)

```javascript
fetch('../actions/search_books.php?q=' + encodeURIComponent(query))
  .then(r => r.json())
  .then(books => {
    // proses data buku yang diterima
    console.log(books); // [{id: 1, title: "...", ...}, ...]
  });
```

### Penjelasan Simpel:
Biasanya kalau kita mau ambil data dari server, halaman harus di-refresh. Tapi pakai `fetch`, kita bisa **ambil data dari server di belakang layar** tanpa reload!

Ini yang bikin fitur search dropdown bisa muncul langsung begitu kamu ngetik — tanpa halaman kedip-kedip.

### Cara Kerjanya:
1. User ketik "fisika" di search bar
2. JavaScript kirim request ke `search_books.php?q=fisika`
3. PHP cari di database, hasilnya dikirim balik dalam format JSON
4. JavaScript terima hasilnya dan tampilin di dropdown

### Apa itu JSON?
JSON itu format data yang gampang dibaca komputer DAN manusia:
```json
[
  {"id": 1, "title": "Fisika Modern", "author": "Prof. Hendra", "price": 85000},
  {"id": 2, "title": "Fisika Dasar", "author": "Dr. Siti", "price": 65000}
]
```

---

## 7. navigator.sendBeacon — Simpan Data Saat Tab Ditutup

📁 **File:** `page/read.php`

```javascript
window.addEventListener('beforeunload', () => {
  const scrolled = calculateProgress();
  if (bookId > 0 && scrolled > currentProgress) {
    const data = JSON.stringify({ book_id: bookId, progress: scrolled });
    const blob = new Blob([data], { type: 'application/json' });
    navigator.sendBeacon('../actions/sync_book_data.php', blob);
  }
});
```

### Penjelasan Simpel:
Ini salah satu kode **paling keren** di project ini!

Masalahnya: Saat user menutup tab browser, kita cuma punya waktu **sepersekian detik** untuk menyimpan data. Kalau pakai `fetch` biasa, datanya sering gagal terkirim karena browser udah keburu ditutup.

`navigator.sendBeacon` itu kayak **mengirim surat kilat** — begitu kamu klik tombol tutup, datanya langsung dikirim dan browser **menjamin** suratnya sampai, meskipun tabnya udah ditutup.

### Kenapa Pakai Blob?
`sendBeacon` ga bisa langsung kirim JSON biasa. Makanya kita bungkus dulu datanya jadi `Blob` (Binary Large Object) — anggap aja ini kayak memasukkan surat ke dalam amplop dulu sebelum dikirim.

---

## 8. Debounce — Mengurangi Spam Request

📁 **File:** `components/top_navbar.php` (Global Search)

```javascript
let searchTimeout;
function globalSearch(query) {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetch('../actions/search_books.php?q=' + query)
      .then(r => r.json())
      .then(books => { /* tampilkan hasil */ });
  }, 250);
}
```

### Penjelasan Simpel:
Bayangin kamu ngetik "fisika" di search bar. Tanpa debounce, setiap huruf yang kamu ketik langsung kirim request ke server:
- "f" → request 1
- "fi" → request 2
- "fis" → request 3
- "fisi" → request 4
- "fisik" → request 5
- "fisika" → request 6

Itu **6 request** untuk satu kata! Server bisa kelelahan.

Dengan debounce, kita bilang: *"Tunggu 250ms dulu. Kalau dalam 250ms user ngetik lagi, batalkan yang sebelumnya."* Jadinya cuma **1 request** yang terkirim!

### Cara Kerjanya:
- `setTimeout` = set timer 250ms
- `clearTimeout` = batalkan timer sebelumnya
- Jadi setiap kali user ngetik, timer di-reset. Request baru dikirim hanya kalau user berhenti ngetik selama 250ms.

---

## 9. htmlspecialchars — Mencegah Serangan XSS

📁 **File:** Hampir semua file PHP

```php
// AMAN ✅
<h1><?php echo htmlspecialchars($book['title']); ?></h1>

// BAHAYA ❌
<h1><?php echo $book['title']; ?></h1>
```

### Penjelasan Simpel:
Bayangin ada user jahat yang isi judul buku dengan:
```
<script>alert('Hacked!')</script>
```

Kalau kita langsung tampilin tanpa `htmlspecialchars`, kode JavaScript itu bakal **dijalankan di browser** semua pengunjung! Hacker bisa curi data, redirect ke website palsu, dll. Ini namanya **XSS (Cross-Site Scripting)**.

`htmlspecialchars` mengubah karakter berbahaya jadi aman:
- `<` jadi `&lt;`
- `>` jadi `&gt;`
- `"` jadi `&quot;`

Sehingga browser menampilkan teks `<script>alert('Hacked!')</script>` apa adanya, bukan menjalankannya.

---

## 10. LocalStorage — Menyimpan Data di Browser

📁 **File:** `components/sidebar.php`

```javascript
// Simpan status sidebar ke localStorage
if (sidebar.classList.contains('closed')) {
  localStorage.setItem('sidebarState', 'closed');
} else {
  localStorage.setItem('sidebarState', 'open');
}

// Ambil status sidebar saat halaman dibuka
if (localStorage.getItem('sidebarState') === 'closed') {
  document.getElementById('main-sidebar').classList.add('closed');
}
```

### Penjelasan Simpel:
`localStorage` itu kayak **catatan kecil** yang disimpan di browser. Beda sama session (yang ada di server), localStorage ada di **komputer user**.

Ini berguna untuk menyimpan preferensi kecil yang tidak perlu dikirim ke server — misalnya status sidebar terbuka atau tertutup.

### Perbedaan localStorage vs Session:
| | localStorage | Session (PHP) |
|---|---|---|
| Disimpan di | Browser user | Server |
| Hilang saat | User hapus manual | Logout / timeout |
| Bisa diakses dari | JavaScript | PHP |
| Contoh penggunaan | Status sidebar | Data login user |

---

## 11. Upload File — Mengunggah Gambar

📁 **File:** `actions/upload_pfp.php`

```php
// validasi tipe file yang boleh diupload
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$file_info = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($file_info, $file['tmp_name']);

if (!in_array($mime_type, $allowed_types)) {
    echo json_encode(['status' => 'error', 'message' => 'File type not allowed']);
    exit;
}

// pindahkan file ke folder tujuan
move_uploaded_file($file['tmp_name'], $destination);
```

### Penjelasan Simpel:
Saat user upload gambar, file-nya pertama disimpan di **folder sementara** (`tmp_name`). Kita harus:

1. **Validasi** — Cek apakah benar itu file gambar, bukan virus/script berbahaya. Caranya pakai `finfo_file` yang ngecek isi file beneran (bukan cuma ekstensinya, karena hacker bisa rename virus.exe jadi gambar.jpg!)

2. **Pindahkan** — `move_uploaded_file` mindahin file dari folder sementara ke folder yang kita tentukan (`asset/avatars/`)

3. **Simpan path di database** — Supaya nanti bisa ditampilin

---

## 12. Scroll Event — Mendeteksi Posisi Baca

📁 **File:** `page/read.php`

```javascript
function calculateProgress() {
  const height = document.documentElement.scrollHeight 
               - document.documentElement.clientHeight;
  if (height <= 0) return 100;
  
  const winScroll = document.body.scrollTop 
                 || document.documentElement.scrollTop;
  return Math.min(Math.round((winScroll / height) * 100), 100);
}
```

### Penjelasan Simpel:
Ini rumusnya simple:

```
Progress = (Sudah di-scroll / Total yang bisa di-scroll) × 100%
```

- `scrollHeight` = tinggi total halaman (termasuk yang belum terlihat)
- `clientHeight` = tinggi layar yang kelihatan
- `scrollTop` = seberapa jauh user sudah scroll ke bawah

Contoh: Kalau halaman total 2000px, layar 500px, dan user sudah scroll 750px:
```
Progress = (750 / (2000 - 500)) × 100 = 50%
```

Kalau hasilnya lebih dari 100, kita cap di 100 pakai `Math.min`.

---

## 13. Template Literal — Cara Keren Nulis String

📁 **File:** `components/top_navbar.php`

```javascript
// Cara biasa (ribet, harus sambung-sambungin)
html += '<a href="detail.php?id=' + book.id + '">' + book.title + '</a>';

// Pakai template literal (jauh lebih gampang dibaca!)
html += `<a href="detail.php?id=${book.id}">${book.title}</a>`;
```

### Penjelasan Simpel:
Template literal pakai tanda **backtick** (`` ` ``) bukan petik biasa. Kelebihannya:

1. Bisa **masukkan variabel** langsung pakai `${variabel}`
2. Bisa **banyak baris** tanpa perlu `+` atau `\n`
3. Jauh **lebih gampang dibaca**

Ini fitur JavaScript modern (ES6) yang wajib dikuasai!

---

## 14. Include — Komponen yang Bisa Dipakai Ulang

📁 **File:** Semua halaman di `page/`

```php
<?php include '../components/sidebar.php'; ?>
<?php include '../components/top_navbar.php'; ?>
```

### Penjelasan Simpel:
Bayangin kamu punya 12 halaman dan semua butuh sidebar yang sama. Kalau kamu copy-paste kode sidebar ke 12 file, terus suatu hari mau ubah satu tombol di sidebar... kamu harus edit **12 file**! 😱

Dengan `include`, kamu cukup tulis kode sidebar di **1 file** (`sidebar.php`), lalu panggil di semua halaman. Mau ubah? Cukup edit **1 file** aja, otomatis berubah di semua halaman.

Ini prinsip **DRY** (Don't Repeat Yourself) — jangan mengulang kode yang sama!

---

## 15. Ternary Operator — IF Singkat

📁 **File:** Banyak file

```php
// Cara biasa (4 baris)
if ($is_purchased) {
    $label = '✓ You own this book';
} else {
    $label = $price_formatted;
}

// Pakai ternary operator (1 baris!)
$label = $is_purchased ? '✓ You own this book' : $price_formatted;
```

### Penjelasan Simpel:
Format ternary operator:
```
$hasil = (kondisi) ? nilai_jika_true : nilai_jika_false;
```

Ini kayak bertanya: *"Apakah buku sudah dibeli? Kalau iya, tampilkan 'You own this book'. Kalau belum, tampilkan harganya."*

Juga bisa dipakai di JavaScript:
```javascript
const badge = book.owned ? 'Owned' : 'Rp. 85.000';
```

---

## 🎯 Tips Belajar

1. **Jangan hafalin** — Pahami konsepnya, kodenya bisa dicari lagi
2. **Coba ubah-ubah** — Cara terbaik belajar coding adalah eksperimen
3. **Baca error message** — Error itu bukan musuh, tapi petunjuk
4. **Google itu teman** — Programmer profesional juga googling tiap hari
5. **Mulai dari yang kecil** — Pahami satu konsep, baru lanjut ke yang berikutnya

---

*Dokumen ini dibuat untuk membantu siswa memahami konsep-konsep pemrograman web yang digunakan dalam project LIBRY.*
