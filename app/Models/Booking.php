<?php
namespace App\Models;
use App\Core\Database;

class Booking
{
    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO bookings
             (booking_code,user_id,schedule_id,passenger_count,total_price,
              contact_name,contact_phone,contact_email,notes)
             VALUES (?,?,?,?,?,?,?,?,?)",
            [$d['booking_code'], $d['user_id'], $d['schedule_id'],
             $d['passenger_count'], $d['total_price'],
             $d['contact_name'], $d['contact_phone'], $d['contact_email'],
             $d['notes'] ?? null]
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT b.*, s.depart_at, s.arrive_at, s.id as schedule_id,
                    r.origin, r.destination,
                    v.name as vehicle_name, v.type as vehicle_type, v.plate_number
             FROM bookings b
             JOIN schedules s ON s.id=b.schedule_id
             JOIN routes    r ON r.id=s.route_id
             JOIN vehicles  v ON v.id=s.vehicle_id
             WHERE b.id=?",
            [$id]
        );
    }

    public static function findByCode(string $code): ?array
    {
        return Database::fetchOne(
            "SELECT b.*, s.depart_at, s.arrive_at, s.id as schedule_id,
                    r.origin, r.destination,
                    v.name as vehicle_name, v.type as vehicle_type, v.plate_number
             FROM bookings b
             JOIN schedules s ON s.id=b.schedule_id
             JOIN routes    r ON r.id=s.route_id
             JOIN vehicles  v ON v.id=s.vehicle_id
             WHERE b.booking_code=?",
            [$code]
        );
    }

    public static function byUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT b.*, s.depart_at, r.origin, r.destination, v.type as vehicle_type
             FROM bookings b
             JOIN schedules s ON s.id=b.schedule_id
             JOIN routes    r ON r.id=s.route_id
             JOIN vehicles  v ON v.id=s.vehicle_id
             WHERE b.user_id=? ORDER BY b.created_at DESC",
            [$userId]
        );
    }

    public static function all(int $limit = 50, int $offset = 0, string $status = ''): array
    {
        if ($status) {
            return Database::fetchAll(
                "SELECT b.*, u.name as user_name, r.origin, r.destination, s.depart_at
                 FROM bookings b
                 JOIN users     u ON u.id=b.user_id
                 JOIN schedules s ON s.id=b.schedule_id
                 JOIN routes    r ON r.id=s.route_id
                 WHERE b.status=? ORDER BY b.created_at DESC LIMIT ? OFFSET ?",
                [$status, $limit, $offset]
            );
        }
        return Database::fetchAll(
            "SELECT b.*, u.name as user_name, r.origin, r.destination, s.depart_at
             FROM bookings b
             JOIN users     u ON u.id=b.user_id
             JOIN schedules s ON s.id=b.schedule_id
             JOIN routes    r ON r.id=s.route_id
             ORDER BY b.created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public static function updateStatus(int $id, string $status): void
    {
        Database::query("UPDATE bookings SET status=? WHERE id=?", [$status, $id]);
    }

    public static function seats(int $bookingId): array
    {
        return Database::fetchAll(
            "SELECT * FROM booking_seats WHERE booking_id=? ORDER BY seat_number", [$bookingId]
        );
    }

    public static function addSeat(int $bookingId, int $seatNo, string $name, ?string $idNo = null): void
    {
        Database::query(
            "INSERT INTO booking_seats (booking_id,seat_number,passenger_name,passenger_id_no) VALUES (?,?,?,?)",
            [$bookingId, $seatNo, $name, $idNo]
        );
    }

    public static function generateCode(): string
    {
        return 'TRV-' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');
    }

    public static function countByStatus(string $status): int
    {
        return (int)(Database::fetchOne(
            "SELECT COUNT(*) as c FROM bookings WHERE status=?", [$status]
        )['c'] ?? 0);
    }

    public static function recentRevenue(): float
    {
        $row = Database::fetchOne(
            "SELECT SUM(total_price) as t FROM bookings
             WHERE status='paid' AND MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())"
        );
        return (float)($row['t'] ?? 0);
    }
}
