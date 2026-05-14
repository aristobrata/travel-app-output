-- ============================================================
--  TravelKu - Fix: Pastikan tabel seat_locks benar
--  Jalankan ini di phpMyAdmin jika seat_locks bermasalah
-- ============================================================

USE travel_app;

-- Hapus dan buat ulang tabel seat_locks dengan struktur yang benar
DROP TABLE IF EXISTS `seat_locks`;

CREATE TABLE `seat_locks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `seat_number` tinyint(3) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `locked_until` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_schedule_seat` (`schedule_id`,`seat_number`),
  KEY `user_id` (`user_id`),
  KEY `idx_locked_until` (`locked_until`),
  CONSTRAINT `seat_locks_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seat_locks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'seat_locks table fixed!' as status;
