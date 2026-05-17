-- =============================================
-- TAMBAH KATEGORI NOVEL & 5 BUKU NOVEL
-- =============================================

-- Tambah kategori Novel (id=3)
INSERT IGNORE INTO `categories` (`id`, `name`) VALUES (3, 'Novel');

-- =============================================
-- BUKU 9: Laut Bercerita - Leila S. Chudori
-- =============================================
INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(9, 'Laut Bercerita', 'Leila S. Chudori', 'Novel yang mengisahkan perjuangan aktivis mahasiswa di era Orde Baru dan dampaknya terhadap keluarga yang ditinggalkan. Sebuah narasi tentang keberanian, kehilangan, dan pencarian kebenaran.', 89000, 'novel 5.png', 'Kepustakaan Populer Gramedia (KPG)', '2017-07-01', 3);

UPDATE `books` SET `content` = '<h2>Bagian Pertama: Laut</h2>
<p>Namaku Biru Laut. Orang-orang memanggilku Laut. Aku seorang mahasiswa yang percaya bahwa perubahan bisa dimulai dari kampus. Bersama teman-temanku — Alex, Daniel, Sunu, Bona, Gugun, dan Julius — kami membentuk kelompok diskusi yang membahas isu-isu sosial dan politik. Kami membaca buku-buku yang dilarang, mengadakan diskusi di rumah kontrakan, dan menulis pamflet yang kami bagikan secara diam-diam di kampus.</p>
<p>Tahun 1998 adalah tahun yang mengubah segalanya. Krisis ekonomi melanda Indonesia dengan dahsyat. Nilai rupiah anjlok, harga-harga melambung tinggi, dan rakyat semakin sengsara. Kami merasa tidak bisa hanya diam dan membaca buku. Kami harus turun ke jalan. Demonstrasi pertama kami dimulai dengan damai — membawa spanduk, menyanyikan lagu-lagu perjuangan, dan menyuarakan tuntutan reformasi.</p>
<p>Tapi kekuasaan tidak pernah menyukai suara-suara yang menantang. Satu per satu, teman-temanku mulai menghilang. Alex tidak pulang ke kontrakan selama tiga hari. Ketika ia kembali, matanya kosong dan tubuhnya penuh lebam. Ia tidak mau bercerita apa yang terjadi. Daniel menghilang berikutnya, dan ia tidak pernah kembali. Kami mencarinya ke mana-mana — ke rumah sakit, ke kantor polisi, bahkan ke kamar mayat. Tidak ada jejak. Seolah-olah ia ditelan bumi.</p>
<p>Aku menulis surat untuk ibuku setiap minggu, meskipun aku tidak selalu mengirimkannya. Dalam surat-surat itu, aku menceritakan kehidupan kampusku yang biasa-biasa saja — tentang dosen yang membosankan, tentang makanan di kantin yang semakin mahal, tentang kucing liar yang sering tidur di depan pintu kontrakan. Aku tidak pernah menulis tentang demonstrasi, tentang teman-teman yang menghilang, tentang ketakutan yang menyelimuti kami setiap malam.</p>

<h2>Bagian Kedua: Asmara Juga Menunggu</h2>
<p>Kisah ini juga adalah kisah tentang cinta yang tumbuh di tengah ketidakpastian. Aku jatuh cinta pada Anjani, mahasiswi sastra yang memiliki senyum paling tenang yang pernah kulihat. Anjani tidak ikut dalam demonstrasi kami, tapi ia selalu ada ketika kami butuh tempat berlindung. Rumah kontrakannya menjadi tempat kami bersembunyi ketika situasi memanas.</p>
<p>Anjani mengajariku bahwa keberanian tidak selalu berarti berdiri di garis depan. Kadang keberanian adalah tetap membuka pintu rumahmu ketika orang lain menutupnya. Kadang keberanian adalah memasak nasi untuk teman-temanmu yang kelaparan setelah seharian berlari dari gas air mata. Kadang keberanian adalah menunggu — menunggu kabar dari seseorang yang kau cintai, tidak tahu apakah ia masih hidup atau sudah menjadi nama di daftar orang hilang.</p>
<p>Malam-malam kami habiskan dengan membaca puisi dan mendengarkan musik. Aku membacakan puisi Wiji Thukul untuknya — "Hanya satu kata: Lawan!" — dan Anjani akan tersenyum sedih. Ia tahu bahwa setiap kali aku pergi ke kampus, ada kemungkinan aku tidak akan kembali. Tapi ia tidak pernah memintaku berhenti. Ia mengerti bahwa ada hal-hal yang lebih besar dari kita berdua.</p>

<h2>Bagian Ketiga: Tanah dan Air Mata</h2>
<p>Ketika akhirnya giliran aku yang menghilang, dunia tidak berhenti berputar. Ibu tetap memasak setiap pagi, meskipun porsinya selalu lebih — seolah-olah aku akan pulang kapan saja untuk makan. Ayah tetap pergi ke kantor, meskipun ia diam-diam mengunjungi setiap lembaga bantuan hukum yang bisa ia temukan. Adikku, Asmara, yang baru berusia empat belas tahun, tiba-tiba harus belajar menjadi dewasa.</p>
<p>Asmara adalah naratornya yang lain. Melalui matanya, kita melihat bagaimana keluarga yang ditinggalkan harus berjuang dengan ketidakpastian yang menyiksa. Tidak ada kabar, tidak ada jenazah, tidak ada kejelasan. Hanya ruang kosong di meja makan dan pertanyaan yang tidak pernah terjawab. Asmara tumbuh dengan lubang di dadanya — lubang berbentuk kakak laki-laki yang tidak pernah pulang.</p>
<p>Novel ini bercerita tentang laut — tentang kedalaman yang menyimpan rahasia, tentang ombak yang terus bergulung meskipun tidak ada yang mendengarnya, tentang garis pantai yang sabar menunggu. Laut bercerita, dan ceritanya adalah cerita tentang Indonesia yang belum selesai. Tentang luka yang belum sembuh. Tentang kebenaran yang masih tenggelam di dasar.</p>'
WHERE `id` = 9;

UPDATE `books` SET 
  `publisher` = 'Kepustakaan Populer Gramedia (KPG)',
  `publication_date` = '2017-07-01'
WHERE `id` = 9;

-- =============================================
-- BUKU 10: Bumi - Tere Liye
-- =============================================
INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(10, 'Bumi', 'Tere Liye', 'Novel fantasi pertama dari serial Bumi karya Tere Liye. Mengisahkan petualangan Raib, gadis berusia 15 tahun yang bisa menghilang, bersama Ali dan Seli menjelajahi dunia paralel.', 79000, 'novel 6.jpg', 'Gramedia Pustaka Utama', '2014-06-01', 3);

UPDATE `books` SET `content` = '<h2>Bab 1: Gadis yang Bisa Menghilang</h2>
<p>Namaku Raib. Aku bisa menghilang. Bukan seperti sulap atau trik kamera. Aku benar-benar bisa membuat diriku tidak terlihat oleh siapa pun. Kemampuan ini sudah kumiliki sejak kecil, dan selama lima belas tahun hidupku, aku menyembunyikannya dari semua orang — kecuali Mama dan Papa.</p>
<p>Mama selalu bilang, "Jangan pernah tunjukkan kemampuanmu pada siapa pun, Raib. Tidak pada teman sekolahmu, tidak pada guru, tidak pada siapa pun." Aku menurut. Aku menjadi gadis biasa di sekolah — tidak terlalu pintar, tidak terlalu bodoh, tidak terlalu menonjol. Sempurna untuk seseorang yang ingin menyembunyikan rahasia besar.</p>
<p>Tapi di kelas, ada seorang anak laki-laki bernama Ali yang selalu membuatku gugup. Bukan karena aku menyukainya — Ali adalah anak paling aneh di sekolah. Ia sangat pintar, selalu mendapat nilai sempurna dalam setiap ujian, tapi ia juga sangat pendiam dan misterius. Yang membuatku gugup adalah caranya menatapku — seolah-olah ia bisa melihat sesuatu yang tidak bisa dilihat orang lain.</p>

<h2>Bab 2: Tiga Orang dengan Kekuatan Spesial</h2>
<p>Ternyata Ali memang bisa melihat sesuatu yang tidak bisa dilihat orang lain. Ali memiliki kecerdasan luar biasa — otaknya seperti komputer super yang bisa memproses informasi dengan kecepatan yang tidak masuk akal. Ia bisa menghitung, menganalisis, dan memecahkan masalah apa pun dalam hitungan detik. Dan ada satu lagi teman kami: Seli, gadis bertubuh besar yang ternyata memiliki kekuatan fisik yang luar biasa.</p>
<p>Kami bertiga — Raib si gadis yang bisa menghilang, Ali si jenius, dan Seli si kuat — ternyata bukan manusia biasa. Kami adalah keturunan dari klan-klan kuno yang telah ada sejak ribuan tahun lalu. Dan ada dunia lain di luar sana — dunia paralel yang tersembunyi dari mata manusia biasa.</p>
<p>Semuanya berubah ketika seorang tokoh misterius bernama Miss Selena datang ke sekolah kami. Ia mengungkapkan bahwa ada ancaman besar yang mengintai — bukan hanya bagi dunia kami, tapi bagi seluruh dunia paralel. Dan kami bertiga, dengan kemampuan unik kami masing-masing, adalah harapan terakhir untuk menghentikannya.</p>

<h2>Bab 3: Dunia Paralel</h2>
<p>Dunia paralel itu bernama Klan Bumi. Di sana, aturan fisika berbeda. Gravitasi bisa dimanipulasi, waktu tidak bergerak linear, dan makhluk-makhluk yang hanya ada dalam dongeng ternyata nyata. Ada Klan Bulan yang menguasai seni bela diri dan kekuatan fisik. Ada Klan Matahari yang menguasai ilmu pengetahuan dan teknologi. Dan ada Klan Bintang yang misterius dan hampir tidak pernah menampakkan diri.</p>
<p>Petualangan pertama kami membawa kami ke hutan belantara Klan Bumi, di mana kami harus menghadapi Si Tanpa Mahkota — penjahat yang ingin menguasai seluruh dunia paralel. Aku belajar bahwa kemampuanku menghilang bukan sekadar menjadi tidak terlihat, tapi juga bisa memanipulasi cahaya di sekitarku. Ali belajar bahwa kecerdasannya bisa digunakan untuk memecahkan teka-teki kuno yang melindungi artefak-artefak penting. Dan Seli belajar bahwa kekuatannya terhubung dengan energi bumi itu sendiri.</p>
<p>Novel Bumi adalah pintu masuk ke dunia yang penuh petualangan. Sebuah dunia di mana persahabatan diuji, keberanian ditempa, dan setiap orang memiliki peran penting untuk dimainkan. Ini baru permulaan — karena masih ada Bulan, Matahari, Bintang, dan dunia-dunia lain yang menunggu untuk dijelajahi.</p>'
WHERE `id` = 10;

UPDATE `books` SET 
  `publisher` = 'Gramedia Pustaka Utama',
  `publication_date` = '2014-06-01'
WHERE `id` = 10;

-- =============================================
-- BUKU 11: Hujan - Tere Liye
-- =============================================
INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(11, 'Hujan', 'Tere Liye', 'Novel tentang kisah cinta, kehilangan, dan kenangan di tengah bencana alam besar. Mengisahkan Lail dan Esok yang bertemu saat letusan gunung berapi dahsyat melanda kota mereka.', 75000, 'novel 7.png', 'Gramedia Pustaka Utama', '2016-01-01', 3);

UPDATE `books` SET `content` = '<h2>Bab 1: Hari Ketika Dunia Berubah</h2>
<p>Lail berusia tujuh belas tahun ketika gunung itu meletus. Bukan letusan biasa — ini adalah letusan terbesar dalam sejarah modern, memuntahkan jutaan ton abu vulkanik ke atmosfer dan mengubah siang menjadi malam selama berminggu-minggu. Kota tempat Lail tinggal hancur dalam hitungan jam. Rumah-rumah runtuh, jalan-jalan tertutup lahar, dan ribuan orang kehilangan segalanya.</p>
<p>Di tengah kekacauan itu, Lail kehilangan kedua orang tuanya. Ia menjadi yatim piatu dalam sekejap mata, berdiri di antara reruntuhan dengan gaun putih yang dipakainya untuk ujian sekolah pagi itu. Abu vulkanik turun seperti salju abu-abu, menutupi segalanya dengan lapisan kelabu yang menyedihkan.</p>
<p>Tapi di tengah kehancuran itu, Lail bertemu dengan Esok. Esok adalah pemuda yang namanya berarti harapan — harapan akan hari yang lebih baik. Esok menyelamatkan Lail dari reruntuhan bangunan, menggendongnya melewati jalan-jalan yang dipenuhi puing, dan membawanya ke tempat pengungsian. Dari hari itu, hidup mereka tidak pernah terpisah.</p>

<h2>Bab 2: Membangun Kembali</h2>
<p>Tahun-tahun berlalu. Lail dan Esok tumbuh bersama di kota yang perlahan-lahan dibangun kembali. Esok menjadi ilmuwan brilian yang terobsesi dengan penelitian cuaca — ia ingin menciptakan teknologi yang bisa memprediksi bencana alam jauh sebelum terjadi, agar tragedi yang menimpa mereka tidak terulang. Lail menjadi pekerja sosial yang membantu korban-korban bencana menemukan kembali kehidupan mereka.</p>
<p>Cinta mereka tumbuh perlahan, seperti hujan yang dimulai dari gerimis. Tidak dramatis, tidak penuh gairah yang membara — tapi dalam, tenang, dan konsisten. Esok selalu ada ketika Lail membutuhkan bahu untuk bersandar. Lail selalu ada ketika Esok merasa bahwa penelitiannya tidak akan pernah cukup untuk mencegah bencana berikutnya.</p>
<p>Mereka menikah dalam upacara sederhana di taman kota yang baru dibangun, tepat di tempat yang dulu menjadi pusat kehancuran. Hujan turun di hari pernikahan mereka, tapi tidak ada yang mengeluh. Hujan adalah berkah — ia membersihkan abu yang masih tersisa, menyuburkan tanah yang telah lama kering, dan membawa harapan baru bagi kota yang telah bangkit dari kehancuran.</p>

<h2>Bab 3: Teknologi Menghapus Kenangan</h2>
<p>Di masa depan yang digambarkan Tere Liye, teknologi telah berkembang pesat. Salah satu penemuan paling kontroversial adalah mesin yang bisa menghapus kenangan — menghilangkan memori yang menyakitkan dari otak manusia. Banyak korban bencana yang memilih untuk menghapus kenangan traumatis mereka. Mereka tidak ingin lagi mengingat reruntuhan, abu, dan mayat-mayat yang berserakan di jalanan.</p>
<p>Ketika tragedi baru menimpa Lail, ia dihadapkan pada pilihan yang mustahil: apakah ia harus menghapus kenangannya tentang Esok? Menghapus semua kenangan indah bersama orang yang paling dicintainya, hanya agar ia tidak lagi merasa sakit? Apakah menghapus kenangan sama dengan menghapus cinta?</p>
<p>Novel Hujan bertanya kepada kita: apakah lebih baik hidup dengan rasa sakit dari kenangan yang indah, atau hidup tanpa rasa sakit tapi juga tanpa kenangan? Tere Liye mengajak kita merenungkan nilai dari setiap momen yang kita alami — baik yang membahagiakan maupun yang menyakitkan — karena pada akhirnya, kenangan itulah yang membuat kita menjadi manusia.</p>'
WHERE `id` = 11;

UPDATE `books` SET 
  `publisher` = 'Gramedia Pustaka Utama',
  `publication_date` = '2016-01-01'
WHERE `id` = 11;

-- =============================================
-- BUKU 12: Cantik Itu Luka - Eka Kurniawan
-- =============================================
INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(12, 'Cantik Itu Luka', 'Eka Kurniawan', 'Novel epik yang menggabungkan realisme magis dengan sejarah Indonesia. Mengisahkan Dewi Ayu, perempuan cantik yang bangkit dari kuburnya, dan kutukan kecantikan yang menghantui keturunannya.', 95000, 'novel 8.jpg', 'Gramedia Pustaka Utama', '2002-09-01', 3);

UPDATE `books` SET `content` = '<h2>Bagian Pertama: Bangkit dari Kubur</h2>
<p>Pada suatu sore di bulan Maret, Dewi Ayu bangkit dari kuburnya setelah dua puluh satu tahun meninggal. Ia berjalan pulang ke rumahnya dengan gaun putih yang telah usang dimakan tanah, melewati jalan-jalan kota Halimunda yang membeku dalam keterkejutan. Orang-orang yang melihatnya berlarian ketakutan, anak-anak menangis, dan anjing-anjing menggonggong seolah melihat hantu. Tapi Dewi Ayu bukan hantu — ia hidup, bernapas, dan lapar.</p>
<p>Dewi Ayu adalah perempuan tercantik yang pernah dimiliki Halimunda. Kecantikannya bukan jenis kecantikan yang membuat orang terpesona dan jatuh cinta. Kecantikannya adalah jenis yang membuat orang takut — karena setiap laki-laki yang jatuh cinta padanya selalu berakhir tragis. Suami pertamanya mati tenggelam. Suami keduanya mati ditembak tentara. Dan laki-laki ketiga yang mencintainya menjadi gila.</p>
<p>Tapi kisah Dewi Ayu bukan hanya tentang kecantikan dan kutukan. Ini adalah kisah tentang Indonesia — tentang kolonialisme Belanda, pendudukan Jepang, revolusi kemerdekaan, dan kekacauan politik yang menyusul. Melalui kehidupan Dewi Ayu dan keempat putrinya, Eka Kurniawan menenun sejarah bangsa dengan mitos, legenda, dan keajaiban yang membuat batas antara kenyataan dan fantasi menjadi kabur.</p>

<h2>Bagian Kedua: Empat Putri dan Kutukan</h2>
<p>Dewi Ayu memiliki empat orang putri, dan ketiganya mewarisi kecantikan ibunya. Alamanda, si sulung, memiliki kecantikan yang keras dan membara — kecantikan yang menghancurkan setiap laki-laki yang mendekatinya. Adinda, putri kedua, memiliki kecantikan yang lembut dan menyedihkan — kecantikan yang membuat orang ingin menangis. Maya Dewi, putri ketiga, memiliki kecantikan yang ceria dan menipu — kecantikan yang menyembunyikan kegelapan di balik senyumnya.</p>
<p>Dan putri keempat? Dewi Ayu berdoa agar putri keempatnya lahir buruk rupa, agar ia terhindar dari kutukan kecantikan yang telah menghancurkan kehidupan ketiga kakaknya. Doanya dikabulkan — putri keempatnya, yang diberi nama Si Cantik, lahir dengan wajah yang sangat buruk rupa. Tapi ironisnya, justru Si Cantik-lah yang hidupnya paling bahagia.</p>
<p>Melalui kisah empat putri ini, Eka Kurniawan mengeksplorasi paradoks kecantikan dalam masyarakat. Bahwa kecantikan, yang dianggap sebagai anugerah, sebenarnya bisa menjadi kutukan. Bahwa keburukrupaan, yang dianggap sebagai kesialan, justru bisa menjadi pembebasan. Novel ini menantang kita untuk mempertanyakan nilai-nilai yang kita anggap benar tentang kecantikan, kekuasaan, dan kebahagiaan.</p>

<h2>Bagian Ketiga: Halimunda dan Sejarahnya</h2>
<p>Halimunda adalah kota fiktif yang menjadi panggung dari seluruh drama ini. Tapi Halimunda bukan sekadar latar — ia adalah karakter tersendiri. Kota ini memiliki sejarah yang berlapis-lapis, dari masa kerajaan Hindu-Buddha, kolonialisme Belanda, pendudukan Jepang, hingga era kemerdekaan dan Orde Baru. Setiap lapisan sejarah meninggalkan bekas luka di tubuh kota, dan luka-luka itu tidak pernah benar-benar sembuh.</p>
<p>Di Halimunda, hantu-hantu berjalan berdampingan dengan manusia. Pohon-pohon bisa berbicara. Laut menyimpan rahasia yang lebih tua dari peradaban manusia. Dan cinta — cinta yang mustahil, cinta yang terlarang, cinta yang melampaui kematian — adalah kekuatan yang menggerakkan segalanya.</p>
<p>Cantik Itu Luka adalah novel yang menolak untuk direduksi menjadi satu genre. Ia adalah roman sejarah, realisme magis, satire politik, dan tragedi keluarga sekaligus. Eka Kurniawan menulis dengan keberanian yang jarang ditemukan dalam sastra Indonesia — keberanian untuk menggabungkan yang indah dan yang mengerikan, yang suci dan yang profan, yang nyata dan yang mustahil.</p>'
WHERE `id` = 12;

UPDATE `books` SET 
  `publisher` = 'Gramedia Pustaka Utama',
  `publication_date` = '2002-09-01'
WHERE `id` = 12;

-- =============================================
-- BUKU 13: Bulan - Tere Liye
-- =============================================
INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `publisher`, `publication_date`, `category_id`) VALUES
(13, 'Bulan', 'Tere Liye', 'Novel fantasi kedua dari serial Bumi. Raib, Ali, dan Seli melanjutkan petualangan mereka ke Klan Bulan untuk menghadapi ancaman baru yang lebih berbahaya dari sebelumnya.', 79000, 'novel 9.jpg', 'Gramedia Pustaka Utama', '2015-02-01', 3);

UPDATE `books` SET `content` = '<h2>Bab 1: Panggilan dari Klan Bulan</h2>
<p>Setelah petualangan di Klan Bumi, aku — Raib — mengira hidupku akan kembali normal. Kembali ke sekolah, mengerjakan PR, dan berpura-pura menjadi gadis biasa. Tapi aku salah. Dunia paralel tidak pernah membiarkan kita pergi begitu saja. Satu bulan setelah pertempuran dengan Si Tanpa Mahkota, sebuah pesan misterius sampai kepadaku melalui cermin di kamar mandi.</p>
<p>Pesan itu berasal dari Klan Bulan — klan yang terkenal dengan kemampuan bela diri dan kekuatan fisik yang luar biasa. Mereka membutuhkan bantuan kami. Sesuatu yang mengerikan sedang terjadi di wilayah mereka — sesuatu yang mengancam keseimbangan seluruh dunia paralel. Dan hanya kami bertiga — aku dengan kemampuan menghilang, Ali dengan kecerdasannya, dan Seli dengan kekuatannya — yang bisa membantu.</p>
<p>Ali, seperti biasa, sudah menganalisis situasinya sebelum kami sempat bereaksi. Dengan otak jeniusnya, ia telah mengumpulkan data dan membuat peta dari dunia paralel berdasarkan petualangan kami sebelumnya. Seli, dengan kekuatannya yang semakin berkembang, sudah berlatih setiap hari di halaman belakang rumahnya — menghancurkan batu-batu besar yang ia klaim sebagai "latihan ringan."</p>

<h2>Bab 2: Dunia yang Berbeda</h2>
<p>Klan Bulan sangat berbeda dari Klan Bumi. Jika Klan Bumi penuh dengan hutan belantara dan sungai yang mengalir deras, Klan Bulan adalah dunia yang didominasi oleh gunung-gunung tinggi dan lembah-lembah dalam yang tertutup kabut. Gravitasi di sini lebih ringan — kami bisa melompat tiga kali lebih tinggi dari biasanya. Udara tipis dan dingin, dan bulan — bulan yang sangat besar dan terang — selalu terlihat di langit, siang maupun malam.</p>
<p>Penduduk Klan Bulan adalah petarung-petarung tangguh. Mereka berlatih seni bela diri sejak kecil, dan setiap anggota klan memiliki kemampuan bertarung yang luar biasa. Pemimpin mereka, Tamus, adalah petarung terkuat yang pernah kulihat — gerakannya begitu cepat hingga mataku tidak bisa mengikutinya. Tapi Tamus juga bijaksana dan rendah hati, mengingatkanku bahwa kekuatan sejati bukan tentang kemampuan menghancurkan, tapi kemampuan melindungi.</p>
<p>Ancaman yang mereka hadapi datang dari kegelapan itu sendiri. Makhluk-makhluk bayangan yang disebut para Penunggu mulai muncul dari celah-celah antardimensi, menelan cahaya dan kehidupan di mana pun mereka lewat. Tidak ada senjata konvensional yang bisa melukai mereka, dan bahkan petarung-petarung terkuat Klan Bulan kewalahan menghadapinya.</p>

<h2>Bab 3: Kekuatan Baru</h2>
<p>Di Klan Bulan, kami masing-masing menemukan level baru dari kemampuan kami. Aku belajar bahwa kemampuan menghilangku bisa dikembangkan menjadi kemampuan memanipulasi cahaya — aku bisa menciptakan ilusi, membelokkan cahaya menjadi senjata, dan bahkan membuat perisai cahaya yang bisa menahan serangan para Penunggu.</p>
<p>Ali menemukan bahwa kecerdasannya terhubung dengan jaringan informasi yang menghubungkan seluruh dunia paralel. Ia bisa mengakses pengetahuan kuno yang tersimpan dalam kristal-kristal memori yang tersebar di seluruh Klan Bulan. Dengan pengetahuan ini, ia menemukan kelemahan para Penunggu — mereka tidak bisa bertahan di hadapan cahaya bulan yang murni.</p>
<p>Dan Seli — Seli mengalami transformasi yang paling dramatis. Kekuatannya yang sudah luar biasa berlipat ganda di Klan Bulan. Ia bisa menggerakkan gunung-gunung kecil, menciptakan gempa mini, dan yang paling mengejutkan, ia bisa berkomunikasi dengan bumi itu sendiri. Tanah, batu, dan gunung berbicara kepadanya, memberitahunya di mana para Penunggu bersembunyi. Novel ini mengajarkan bahwa persahabatan sejati adalah kekuatan terbesar di semua dunia — baik dunia nyata maupun dunia paralel.</p>'
WHERE `id` = 13;

UPDATE `books` SET 
  `publisher` = 'Gramedia Pustaka Utama',
  `publication_date` = '2015-02-01'
WHERE `id` = 13;
