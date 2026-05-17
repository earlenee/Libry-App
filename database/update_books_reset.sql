-- Reset highlighted_html agar konten baru yang ditampilkan bukan konten lama yang sudah di-highlight
-- Bookmark/stabilo yang sudah dibuat sebelumnya akan hilang karena konten berubah
UPDATE `user_book_data` SET `highlighted_html` = NULL;
