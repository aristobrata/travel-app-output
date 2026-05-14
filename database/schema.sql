-- ============================================================
--  TravelKu - Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS travel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travel_app;

-- --------------------------------------------------------
-- Users
-- --------------------------------------------------------
CREATE TABLE users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)        NOT NULL,
    email         VARCHAR(150)        NOT NULL UNIQUE,
    phone         VARCHAR(20)         DEFAULT NULL,
    password_hash VARCHAR(255)        NOT NULL,
    role          ENUM('user','admin') NOT NULL DEFAULT 'user',
    avatar        VARCHAR(255)        DEFAULT NULL,
    is_active     TINYINT(1)          NOT NULL DEFAULT 1,
    created_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Vehicles
-- --------------------------------------------------------
CREATE TABLE vehicles (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100)                    NOT NULL,
    type         ENUM('car','minibus','bus')      NOT NULL,
    plate_number VARCHAR(20)                     NOT NULL UNIQUE,
    capacity     TINYINT UNSIGNED                NOT NULL,
    facilities   JSON                            DEFAULT NULL,
    image        VARCHAR(255)                    DEFAULT NULL,
    status       ENUM('active','maintenance','inactive') NOT NULL DEFAULT 'active',
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type   (type),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Routes
-- --------------------------------------------------------
CREATE TABLE routes (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    origin        VARCHAR(100)         NOT NULL,
    destination   VARCHAR(100)         NOT NULL,
    distance_km   DECIMAL(8,2)         NOT NULL DEFAULT 0,
    duration_min  SMALLINT UNSIGNED    NOT NULL DEFAULT 0,
    base_price    DECIMAL(12,0)        NOT NULL,
    polyline      TEXT                 DEFAULT NULL,
    is_active     TINYINT(1)           NOT NULL DEFAULT 1,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_origin      (origin),
    INDEX idx_destination (destination)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Schedules
-- --------------------------------------------------------
CREATE TABLE schedules (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_id      INT UNSIGNED  NOT NULL,
    route_id        INT UNSIGNED  NOT NULL,
    depart_at       DATETIME      NOT NULL,
    arrive_at       DATETIME      NOT NULL,
    available_seats TINYINT UNSIGNED NOT NULL,
    price_override  DECIMAL(12,0) DEFAULT NULL,
    status          ENUM('active','cancelled','completed') NOT NULL DEFAULT 'active',
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE RESTRICT,
    FOREIGN KEY (route_id)   REFERENCES routes(id)   ON DELETE RESTRICT,
    INDEX idx_depart_at  (depart_at),
    INDEX idx_route_date (route_id, depart_at)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Bookings
-- --------------------------------------------------------
CREATE TABLE bookings (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_code    VARCHAR(20)   NOT NULL UNIQUE,
    user_id         INT UNSIGNED  NOT NULL,
    schedule_id     INT UNSIGNED  NOT NULL,
    passenger_count TINYINT UNSIGNED NOT NULL DEFAULT 1,
    total_price     DECIMAL(12,0) NOT NULL,
    contact_name    VARCHAR(100)  NOT NULL,
    contact_phone   VARCHAR(20)   NOT NULL,
    contact_email   VARCHAR(150)  NOT NULL,
    status          ENUM('pending','paid','cancelled','completed') NOT NULL DEFAULT 'pending',
    notes           TEXT          DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)     ON DELETE RESTRICT,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE RESTRICT,
    INDEX idx_user_id    (user_id),
    INDEX idx_status     (status),
    INDEX idx_booking_code (booking_code)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Booking Seats
-- --------------------------------------------------------
CREATE TABLE booking_seats (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id      INT UNSIGNED  NOT NULL,
    seat_number     TINYINT UNSIGNED NOT NULL,
    passenger_name  VARCHAR(100)  NOT NULL,
    passenger_id_no VARCHAR(50)   DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY uq_booking_seat (booking_id, seat_number)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Seat Locks (temporary hold during selection)
-- --------------------------------------------------------
CREATE TABLE seat_locks (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT UNSIGNED     NOT NULL,
    seat_number TINYINT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED     NOT NULL,
    locked_until DATETIME        NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE,
    UNIQUE KEY uq_schedule_seat (schedule_id, seat_number),
    INDEX idx_locked_until (locked_until)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Payments
-- --------------------------------------------------------
CREATE TABLE payments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id      INT UNSIGNED  NOT NULL UNIQUE,
    gateway         VARCHAR(50)   NOT NULL DEFAULT 'midtrans',
    gateway_trx_id  VARCHAR(100)  DEFAULT NULL,
    payment_type    VARCHAR(50)   DEFAULT NULL,
    va_number       VARCHAR(50)   DEFAULT NULL,
    amount          DECIMAL(12,0) NOT NULL,
    status          ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    paid_at         DATETIME      DEFAULT NULL,
    expired_at      DATETIME      DEFAULT NULL,
    raw_response    JSON          DEFAULT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE RESTRICT,
    INDEX idx_gateway_trx (gateway_trx_id),
    INDEX idx_status      (status)
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Route Cache (cache Google Maps results)
-- --------------------------------------------------------
CREATE TABLE route_cache (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cache_key   VARCHAR(64) NOT NULL UNIQUE,
    data        JSON        NOT NULL,
    expires_at  DATETIME    NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Seed: Admin user (password: Admin@1234)
-- --------------------------------------------------------
INSERT INTO users (name, email, phone, password_hash, role) VALUES
('Administrator', 'admin@travelku.id', '081234567890',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- --------------------------------------------------------
-- Seed: Sample vehicles
-- --------------------------------------------------------
INSERT INTO vehicles (name, type, plate_number, capacity, facilities) VALUES
('Toyota Avanza', 'car', 'BA 1234 CD', 6,
 '{"ac":true,"wifi":false,"usb":true}'),
('Toyota HiAce', 'minibus', 'BA 5678 EF', 12,
 '{"ac":true,"wifi":true,"usb":true,"tv":false}'),
('Mercedes Sprinter', 'minibus', 'BA 9012 GH', 16,
 '{"ac":true,"wifi":true,"usb":true,"tv":true}'),
('Isuzu Elf', 'bus', 'BA 3456 IJ', 20,
 '{"ac":true,"wifi":false,"usb":false}');

-- --------------------------------------------------------
-- Seed: Sample routes
-- --------------------------------------------------------
INSERT INTO routes (origin, destination, distance_km, duration_min, base_price) VALUES
('Bukittinggi', 'Padang', 91.0, 120, 80000),
('Bukittinggi', 'Pekanbaru', 188.0, 240, 150000),
('Bukittinggi', 'Medan', 632.0, 720, 350000),
('Padang', 'Pekanbaru', 279.0, 360, 200000),
('Padang', 'Jambi', 418.0, 480, 280000);

-- --------------------------------------------------------
-- Seed: Sample schedules (next 7 days)
-- --------------------------------------------------------
INSERT INTO schedules (vehicle_id, route_id, depart_at, arrive_at, available_seats) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 6 HOUR,  DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 8 HOUR,  6),
(2, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 8 HOUR,  DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 10 HOUR, 12),
(3, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 7 HOUR,  DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 11 HOUR, 16),
(4, 3, DATE_ADD(CURDATE(), INTERVAL 1 DAY) + INTERVAL 20 HOUR, DATE_ADD(CURDATE(), INTERVAL 2 DAY) + INTERVAL 8 HOUR,  20),
(2, 4, DATE_ADD(CURDATE(), INTERVAL 2 DAY) + INTERVAL 6 HOUR,  DATE_ADD(CURDATE(), INTERVAL 2 DAY) + INTERVAL 12 HOUR, 12);
