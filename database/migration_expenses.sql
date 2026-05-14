-- ============================================================
--  TravelKu - Modul Pengeluaran Operasional
--  Migration: expenses, service_records, driver_salaries
-- ============================================================

USE travel_app;

-- --------------------------------------------------------
-- Supir (Drivers)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS drivers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)   NOT NULL,
    nik         VARCHAR(20)    DEFAULT NULL UNIQUE,
    phone       VARCHAR(20)    DEFAULT NULL,
    address     TEXT           DEFAULT NULL,
    license_no  VARCHAR(30)    DEFAULT NULL,
    license_exp DATE           DEFAULT NULL,
    base_salary DECIMAL(12,0)  NOT NULL DEFAULT 0,
    status      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    joined_at   DATE           DEFAULT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Pengeluaran Bensin Per Trip
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS fuel_expenses (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    schedule_id  INT UNSIGNED  DEFAULT NULL,        -- opsional, bisa tanpa trip terjadwal
    vehicle_id   INT UNSIGNED  NOT NULL,
    driver_id    INT UNSIGNED  DEFAULT NULL,
    trip_date    DATE          NOT NULL,
    origin       VARCHAR(100)  DEFAULT NULL,
    destination  VARCHAR(100)  DEFAULT NULL,
    fuel_liters  DECIMAL(8,2)  DEFAULT NULL,
    fuel_price   DECIMAL(12,0) NOT NULL,             -- total harga bahan bakar
    odometer_km  INT UNSIGNED  DEFAULT NULL,
    notes        TEXT          DEFAULT NULL,
    created_by   INT UNSIGNED  DEFAULT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE SET NULL,
    INDEX idx_trip_date   (trip_date),
    INDEX idx_vehicle     (vehicle_id),
    INDEX idx_driver      (driver_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Riwayat Servis / Perawatan Kendaraan
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS service_records (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id   INT UNSIGNED  NOT NULL,
    service_date DATE          NOT NULL,
    service_type ENUM(
        'oli',
        'tune_up',
        'ban',
        'rem',
        'ac',
        'mesin',
        'bodi',
        'kaki_kaki',
        'lainnya'
    ) NOT NULL DEFAULT 'lainnya',
    description  TEXT          DEFAULT NULL,
    workshop     VARCHAR(150)  DEFAULT NULL,         -- nama bengkel
    cost         DECIMAL(12,0) NOT NULL DEFAULT 0,
    odometer_km  INT UNSIGNED  DEFAULT NULL,
    next_service_km  INT UNSIGNED DEFAULT NULL,
    next_service_date DATE     DEFAULT NULL,
    receipt_file VARCHAR(255)  DEFAULT NULL,
    status       ENUM('selesai','dalam_servis','dijadwalkan') NOT NULL DEFAULT 'selesai',
    created_by   INT UNSIGNED  DEFAULT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    INDEX idx_service_date (service_date),
    INDEX idx_vehicle      (vehicle_id),
    INDEX idx_service_type (service_type)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Gaji Supir
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS driver_salaries (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    driver_id    INT UNSIGNED  NOT NULL,
    period_month TINYINT UNSIGNED NOT NULL,          -- 1-12
    period_year  SMALLINT UNSIGNED NOT NULL,
    base_salary  DECIMAL(12,0) NOT NULL DEFAULT 0,
    bonus        DECIMAL(12,0) NOT NULL DEFAULT 0,
    deduction    DECIMAL(12,0) NOT NULL DEFAULT 0,   -- potongan
    net_salary   DECIMAL(12,0) NOT NULL DEFAULT 0,   -- gaji bersih (base+bonus-deduction)
    trip_count   SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    paid_at      DATE          DEFAULT NULL,
    status       ENUM('draft','paid') NOT NULL DEFAULT 'draft',
    notes        TEXT          DEFAULT NULL,
    created_by   INT UNSIGNED  DEFAULT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE RESTRICT,
    UNIQUE KEY uq_driver_period (driver_id, period_month, period_year),
    INDEX idx_period (period_year, period_month),
    INDEX idx_status (status)
) ENGINE=InnoDB;
