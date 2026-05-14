-- ============================================================
-- Migration: Tambah kolom dokumen kendaraan
-- Jalankan query ini di phpMyAdmin atau MySQL CLI
-- ============================================================

ALTER TABLE `vehicles`
  ADD COLUMN `chassis_number`  VARCHAR(50)  NULL DEFAULT NULL COMMENT 'Nomor rangka'   AFTER `plate_number`,
  ADD COLUMN `engine_number`   VARCHAR(50)  NULL DEFAULT NULL COMMENT 'Nomor mesin'    AFTER `chassis_number`,
  ADD COLUMN `tax_due_date`    DATE         NULL DEFAULT NULL COMMENT 'Pajak jatuh tempo' AFTER `engine_number`,
  ADD COLUMN `stnk_file`       VARCHAR(255) NULL DEFAULT NULL COMMENT 'File STNK'      AFTER `tax_due_date`,
  ADD COLUMN `bpkb_file`       VARCHAR(255) NULL DEFAULT NULL COMMENT 'File BPKB'      AFTER `stnk_file`;
