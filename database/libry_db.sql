-- SQL dump buat database libry
-- Database: `libry_db`

CREATE DATABASE IF NOT EXISTS `libry_db`;
USE `libry_db`;

-- prosedur buat nambahin kolom baru kalo tabelnya udah ada

DELIMITER //
CREATE PROCEDURE IF NOT EXISTS SafeAddColumn(
    IN tableName VARCHAR(255), 
    IN columnName VARCHAR(255), 
    IN columnDefinition TEXT
)
BEGIN
    SET @dbName = DATABASE();
    SET @s = (SELECT IF(
        (SELECT COUNT(*) 
         FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE table_name = tableName 
           AND table_schema = @dbName 
           AND column_name = columnName) > 0,
        'SELECT ''Column Exists''',
        CONCAT('ALTER TABLE `', tableName, '` ADD COLUMN `', columnName, '` ', columnDefinition)
    ));
    PREPARE stmt FROM @s;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END //
DELIMITER ;

-- tabel users

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'member',
  `profile_pic` varchar(255) DEFAULT NULL,
  `theme` varchar(20) NOT NULL DEFAULT 'light',
  `font_size` int(11) NOT NULL DEFAULT 20,
  `streak_count` int(11) NOT NULL DEFAULT 0,
  `last_active_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- user default buat testing
INSERT IGNORE INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`) VALUES
(1, 'Libry', 'Libry', 'libry@mail.com', '$2y$10$rSfpJL.PjSo66YXbpxpRguQXCkuuddYgfK.uV0BV2.5k8RyqVcaMe', 'member');

-- tabel categories (kategori buku)

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabel books

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cover_image` varchar(255) DEFAULT 'logo.png',
  `content` longtext DEFAULT NULL,
  `publisher` varchar(150) DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_book_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tambahin kolom baru kalo belum ada (biar aman kalo import ulang)
CALL SafeAddColumn('users', 'role', 'varchar(50) NOT NULL DEFAULT ''member''');
CALL SafeAddColumn('users', 'profile_pic', 'varchar(255) DEFAULT NULL');
CALL SafeAddColumn('users', 'theme', 'varchar(20) NOT NULL DEFAULT ''light''');
CALL SafeAddColumn('users', 'font_size', 'int(11) NOT NULL DEFAULT 20');
CALL SafeAddColumn('users', 'streak_count', 'int(11) NOT NULL DEFAULT 0');
CALL SafeAddColumn('users', 'last_active_date', 'date DEFAULT NULL');

CALL SafeAddColumn('books', 'description', 'text DEFAULT NULL');
CALL SafeAddColumn('books', 'price', 'decimal(10,2) NOT NULL DEFAULT 0.00');
CALL SafeAddColumn('books', 'cover_image', 'varchar(255) DEFAULT ''logo.png''');
CALL SafeAddColumn('books', 'publisher', 'varchar(150) DEFAULT NULL');
CALL SafeAddColumn('books', 'publication_date', 'date DEFAULT NULL');
CALL SafeAddColumn('books', 'category_id', 'int(11) DEFAULT NULL');
CALL SafeAddColumn('books', 'content', 'longtext DEFAULT NULL');

-- hapus prosedur setelah selesai
DROP PROCEDURE IF EXISTS SafeAddColumn;

-- data awal kategori

INSERT IGNORE INTO `categories` (`id`, `name`) VALUES
(1, 'Produktif'),
(2, 'Muatan Nasional');

-- data awal buku

INSERT IGNORE INTO `books` (`id`, `title`, `author`, `description`, `price`, `cover_image`, `category_id`) VALUES
(1, 'The Pragmatic Programmer', 'David Thomas', 'Your journey to mastery.', 45.00, 'logo.png', 1),
(2, 'Clean Code', 'Robert C. Martin', 'A Handbook of Agile Software Craftsmanship.', 50.00, 'logo.png', 1),
(3, 'Don\'t Make Me Think', 'Steve Krug', 'A Common Sense Approach to Web Usability.', 35.00, 'logo.png', 2),
(4, 'Atomic Habits', 'James Clear', 'An Easy & Proven Way to Build Good Habits & Break Bad Ones.', 20.00, 'logo.png', 1),
(5, 'The Lean Startup', 'Eric Ries', 'How Today''s Entrepreneurs Use Continuous Innovation to Create Radically Successful Businesses.', 25.00, 'logo.png', 1),
(8, 'Bahasa Inggris', 'Kementerian Pendidikan dan Kebudayaan', 'Buku teks Bahasa Inggris untuk siswa SMA/SMK Kelas X. Membahas speaking, listening, reading, dan writing secara komprehensif sesuai kurikulum nasional.', 68000, 'buku muatan nasional 5.png', 2);

-- isi konten dummy kalo masih kosong
UPDATE `books` SET `content` = '<h2>Chapter 1: The Beginning</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec lobortis risus a elit. Etiam tempor. Ut ullamcorper, ligula eu tempor congue, eros est euismod turpis, id tincidunt sapien risus a quam. Maecenas fermentum consequat mi. Donec fermentum. Pellentesque malesuada nulla a mi.</p>

<h2>Chapter 2: The Journey</h2>
<p>Duis sapien sem, aliquet nec, commodo eget, consequat quis, neque. Aliquam faucibus, elit ut dictum aliquet, felis nisl adipiscing sapien, sed malesuada diam lacus eget erat. Cras mollis scelerisque nunc. Nullam arcu. Aliquam consequat. Curabitur augue lorem, dapibus quis, laoreet et, pretium ac, nisi. Aenean magna nisl, mollis quis, molestie eu, feugiat in, orci. In hac habitasse platea dictumst.</p>
<p>Fusce convallis, mauris imperdiet gravida bibendum, nisl turpis suscipit mauris, sed placerat ipsum urna sed risus. In convallis tellus a mauris. Curabitur non elit ut libero tristique sodales. Mauris a lacus. Donec mattis semper leo. In hac habitasse platea dictumst. Vivamus facilisis diam vel magna. Mauris tincidunt sem sed arcu. Nunc posuere.</p>' 
WHERE `content` IS NULL;

-- tabel favourites

CREATE TABLE IF NOT EXISTS `favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabel cart (keranjang belanja)

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabel purchases (riwayat pembelian)

CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `purchased_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `fk_purch_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_purch_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tabel user_book_data (progress baca & highlight)

CREATE TABLE IF NOT EXISTS `user_book_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `progress_percent` int(11) NOT NULL DEFAULT 0,
  `highlighted_html` longtext DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_book_unique` (`user_id`,`book_id`),
  CONSTRAINT `fk_ubd_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ubd_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- bersihin kategori buat database yg udah ada sebelumnya
UPDATE `categories` SET `name` = 'Produktif' WHERE `id` = 1;
UPDATE `categories` SET `name` = 'Muatan Nasional' WHERE `id` = 2;
UPDATE `books` SET `category_id` = 1 WHERE `category_id` IN (3, 4, 5);
DELETE FROM `categories` WHERE `id` IN (3, 4, 5);
