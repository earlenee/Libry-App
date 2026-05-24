-- =============================================
-- TAMBAH BUKU PRODUKTIF RPL, TKJ, TJA, PG
-- =============================================

INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(14, 'Dasar-Dasar Rekayasa Perangkat Lunak', 'Tim Kurikulum SMK', 'Buku panduan lengkap untuk siswa SMK jurusan RPL (Rekayasa Perangkat Lunak) yang mencakup dasar pemrograman, algoritma, dan basis data.', 65000, 'logo.png', 'Kemdikbud', '2023-01-01', 1),
(15, 'Administrasi Sistem Jaringan TKJ', 'Budi Santoso', 'Materi komprehensif untuk Teknik Komputer dan Jaringan (TKJ), membahas instalasi OS server, konfigurasi jaringan, dan keamanan sistem.', 72000, 'logo.png', 'Penerbit Andi', '2022-05-10', 1),
(16, 'Teknik Jaringan Akses Telekomunikasi (TJA)', 'Ahmad Riyadi', 'Buku pegangan untuk siswa TJA yang mencakup teknologi fiber optik, komunikasi nirkabel, dan instalasi jaringan akses.', 68000, 'logo.png', 'Informatika', '2021-08-15', 1),
(17, 'Pengantar Produksi Grafika (PG)', 'Siti Aminah', 'Panduan teknis dan praktis mengenai proses desain, pracetak, pencetakan, dan penyelesaian akhir dalam industri grafika.', 70000, 'logo.png', 'Gramedia Pustaka Utama', '2023-03-20', 1);

UPDATE `books` SET `content` = '<h2>Bab 1: Pengantar Pemrograman</h2><p>Pemrograman adalah proses menulis, menguji, dan memelihara kode yang membangun suatu program komputer. Bagian ini membahas sejarah bahasa pemrograman, konsep dasar algoritma, serta pengenalan struktur data yang sering digunakan dalam pengembangan perangkat lunak.</p><h2>Bab 2: Database Management System</h2><p>Data merupakan elemen krusial dalam perangkat lunak modern. Bab ini akan memandu Anda memahami konsep relasional, perintah-perintah SQL dasar, serta bagaimana menghubungkan aplikasi dengan database.</p>' WHERE `id` = 14;

UPDATE `books` SET `content` = '<h2>Bab 1: Konsep Dasar Jaringan</h2><p>Jaringan komputer memungkinkan berbagai perangkat untuk saling berkomunikasi. Bab ini mencakup topologi jaringan, model OSI, dan protokol TCP/IP yang menjadi fondasi internet modern.</p><h2>Bab 2: Konfigurasi Server</h2><p>Mempelajari langkah-langkah praktis dalam mengonfigurasi layanan server seperti DNS, DHCP, Web Server, dan FTP Server menggunakan sistem operasi berbasis Linux.</p>' WHERE `id` = 15;

UPDATE `books` SET `content` = '<h2>Bab 1: Teknologi Fiber Optik</h2><p>Kabel serat optik mengubah cara data ditransmisikan di seluruh dunia. Di bab ini, kita membahas prinsip pemantulan internal total, jenis-jenis serat optik, dan teknik penyambungan kabel menggunakan splicer.</p><h2>Bab 2: Komunikasi Nirkabel</h2><p>Membahas prinsip dasar gelombang radio, arsitektur jaringan seluler, dan teknologi transmisi modern seperti 4G dan 5G dalam jaringan akses.</p>' WHERE `id` = 16;

UPDATE `books` SET `content` = '<h2>Bab 1: Desain dan Pracetak</h2><p>Sebelum sebuah produk dicetak, ada tahapan persiapan yang sangat penting. Bab ini mengupas teori warna CMYK, resolusi gambar, dan persiapan file digital untuk mesin cetak.</p><h2>Bab 2: Teknik Pencetakan</h2><p>Mengenal berbagai teknik pencetakan seperti cetak offset, sablon, flexografi, dan cetak digital beserta kelebihan dan kekurangannya untuk berbagai jenis produk grafika.</p>' WHERE `id` = 17;
