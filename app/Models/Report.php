<?php
namespace App\Models;

use App\Core\Database;

class Report
{
    // ── Ringkasan Pendapatan ─────────────────────────────────────────────────
    public static function revenueSummary(string $from, string $to): array
    {
        return Database::fetchAll(
            "SELECT
                DATE(b.created_at)          AS tanggal,
                COUNT(b.id)                 AS total_booking,
                SUM(CASE WHEN b.status='paid' THEN 1 ELSE 0 END)         AS booking_lunas,
                SUM(CASE WHEN b.status='cancelled' THEN 1 ELSE 0 END)    AS booking_batal,
                SUM(CASE WHEN b.status='paid' THEN b.total_price ELSE 0 END) AS pendapatan
             FROM bookings b
             WHERE DATE(b.created_at) BETWEEN ? AND ?
             GROUP BY DATE(b.created_at)
             ORDER BY tanggal ASC",
            [$from, $to]
        );
    }

    public static function revenueTotals(string $from, string $to): array
    {
        return Database::fetchOne(
            "SELECT
                COUNT(*)                                                   AS total_booking,
                SUM(CASE WHEN status='paid'      THEN 1 ELSE 0 END)       AS lunas,
                SUM(CASE WHEN status='pending'   THEN 1 ELSE 0 END)       AS pending,
                SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END)       AS batal,
                SUM(CASE WHEN status='paid' THEN total_price ELSE 0 END)  AS total_pendapatan,
                COUNT(DISTINCT user_id)                                    AS total_pelanggan
             FROM bookings
             WHERE DATE(created_at) BETWEEN ? AND ?",
            [$from, $to]
        ) ?? [];
    }

    // ── Laporan Booking Detail ───────────────────────────────────────────────
    public static function bookingDetail(string $from, string $to, string $status = ''): array
    {
        $where = "WHERE DATE(b.created_at) BETWEEN ? AND ?";
        $params = [$from, $to];
        if ($status) {
            $where .= " AND b.status = ?";
            $params[] = $status;
        }
        return Database::fetchAll(
            "SELECT
                b.booking_code, b.created_at, b.status,
                b.contact_name, b.contact_phone,
                b.passenger_count, b.total_price,
                r.origin, r.destination,
                s.depart_at,
                v.name AS kendaraan, v.type AS jenis
             FROM bookings b
             JOIN schedules s ON s.id = b.schedule_id
             JOIN routes    r ON r.id = s.route_id
             JOIN vehicles  v ON v.id = s.vehicle_id
             $where
             ORDER BY b.created_at DESC",
            $params
        );
    }

    // ── Laporan per Rute ─────────────────────────────────────────────────────
    public static function byRoute(string $from, string $to): array
    {
        return Database::fetchAll(
            "SELECT
                r.origin, r.destination,
                COUNT(b.id)                                                AS total_booking,
                SUM(CASE WHEN b.status='paid' THEN 1 ELSE 0 END)          AS booking_lunas,
                SUM(CASE WHEN b.status='paid' THEN b.total_price ELSE 0 END) AS pendapatan,
                SUM(b.passenger_count)                                     AS total_penumpang
             FROM bookings b
             JOIN schedules s ON s.id = b.schedule_id
             JOIN routes    r ON r.id = s.route_id
             WHERE DATE(b.created_at) BETWEEN ? AND ?
             GROUP BY r.id, r.origin, r.destination
             ORDER BY pendapatan DESC",
            [$from, $to]
        );
    }

    // ── Laporan per Kendaraan ────────────────────────────────────────────────
    public static function byVehicle(string $from, string $to): array
    {
        return Database::fetchAll(
            "SELECT
                v.name AS kendaraan, v.type AS jenis, v.plate_number,
                COUNT(DISTINCT s.id)                                        AS total_jadwal,
                COUNT(b.id)                                                 AS total_booking,
                SUM(CASE WHEN b.status='paid' THEN b.total_price ELSE 0 END) AS pendapatan
             FROM vehicles v
             LEFT JOIN schedules s ON s.vehicle_id = v.id
             LEFT JOIN bookings  b ON b.schedule_id = s.id
                   AND DATE(b.created_at) BETWEEN ? AND ?
             WHERE v.status = 'active'
             GROUP BY v.id
             ORDER BY pendapatan DESC",
            [$from, $to]
        );
    }

    // ── Laporan Penumpang ────────────────────────────────────────────────────
    public static function topCustomers(string $from, string $to, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT
                u.name, u.email, u.phone,
                COUNT(b.id)                                                AS total_booking,
                SUM(CASE WHEN b.status='paid' THEN b.total_price ELSE 0 END) AS total_belanja
             FROM users u
             JOIN bookings b ON b.user_id = u.id
             WHERE DATE(b.created_at) BETWEEN ? AND ?
             GROUP BY u.id
             ORDER BY total_belanja DESC
             LIMIT ?",
            [$from, $to, $limit]
        );
    }
}
