<?php
namespace App\Models;
use App\Core\Database;

class Schedule
{
    public static function search(int $routeId, string $date): array
    {
        return Database::fetchAll(
            "SELECT s.*, v.name as vehicle_name, v.type as vehicle_type, v.capacity,
                    v.facilities, r.origin, r.destination, r.distance_km, r.duration_min,
                    COALESCE(s.price_override, r.base_price) as price
             FROM schedules s
             JOIN vehicles v ON v.id=s.vehicle_id
             JOIN routes   r ON r.id=s.route_id
             WHERE s.route_id=? AND DATE(s.depart_at)=? AND s.status='active'
             ORDER BY s.depart_at",
            [$routeId, $date]
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT s.*, v.name as vehicle_name, v.type as vehicle_type, v.capacity,
                    v.facilities, v.plate_number, r.origin, r.destination,
                    r.distance_km, r.duration_min,
                    COALESCE(s.price_override, r.base_price) as price
             FROM schedules s
             JOIN vehicles v ON v.id=s.vehicle_id
             JOIN routes   r ON r.id=s.route_id
             WHERE s.id=?",
            [$id]
        );
    }

    public static function all(int $limit = 50, int $offset = 0): array
    {
        return Database::fetchAll(
            "SELECT s.*, v.name as vehicle_name, r.origin, r.destination
             FROM schedules s
             JOIN vehicles v ON v.id=s.vehicle_id
             JOIN routes   r ON r.id=s.route_id
             ORDER BY s.depart_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO schedules (vehicle_id,route_id,depart_at,arrive_at,available_seats,price_override)
             VALUES (?,?,?,?,?,?)",
            [$d['vehicle_id'], $d['route_id'], $d['depart_at'], $d['arrive_at'],
             $d['available_seats'], $d['price_override'] ?? null]
        );
    }

    public static function decrementSeats(int $id, int $count = 1): void
    {
        Database::query(
            "UPDATE schedules SET available_seats=available_seats-? WHERE id=? AND available_seats>=?",
            [$count, $id, $count]
        );
    }

    public static function incrementSeats(int $id, int $count = 1): void
    {
        Database::query("UPDATE schedules SET available_seats=available_seats+? WHERE id=?", [$count, $id]);
    }

    public static function count(): int
    {
        return (int)(Database::fetchOne("SELECT COUNT(*) as c FROM schedules WHERE status='active'")['c'] ?? 0);
    }
}
