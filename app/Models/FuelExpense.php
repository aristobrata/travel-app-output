<?php
namespace App\Models;

use App\Core\Database;

class FuelExpense
{
    public static function all(int $limit = 200, int $offset = 0, array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['vehicle_id'])) { $where[] = "fe.vehicle_id = ?"; $params[] = $filters['vehicle_id']; }
        if (!empty($filters['driver_id']))  { $where[] = "fe.driver_id = ?";  $params[] = $filters['driver_id']; }
        if (!empty($filters['from']))       { $where[] = "fe.trip_date >= ?"; $params[] = $filters['from']; }
        if (!empty($filters['to']))         { $where[] = "fe.trip_date <= ?"; $params[] = $filters['to']; }

        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $params[] = $limit;
        $params[] = $offset;

        return Database::fetchAll(
            "SELECT fe.*, v.name AS vehicle_name, v.plate_number, d.name AS driver_name
             FROM fuel_expenses fe
             LEFT JOIN vehicles v ON v.id = fe.vehicle_id
             LEFT JOIN drivers  d ON d.id = fe.driver_id
             $cond
             ORDER BY fe.trip_date DESC, fe.id DESC
             LIMIT ? OFFSET ?",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT fe.*, v.name AS vehicle_name, v.plate_number, d.name AS driver_name
             FROM fuel_expenses fe
             LEFT JOIN vehicles v ON v.id = fe.vehicle_id
             LEFT JOIN drivers  d ON d.id = fe.driver_id
             WHERE fe.id = ?",
            [$id]
        );
    }

    public static function totalCost(array $filters = []): float
    {
        $where  = [];
        $params = [];
        if (!empty($filters['from']))       { $where[] = "trip_date >= ?";  $params[] = $filters['from']; }
        if (!empty($filters['to']))         { $where[] = "trip_date <= ?";  $params[] = $filters['to']; }
        if (!empty($filters['vehicle_id'])) { $where[] = "vehicle_id = ?"; $params[] = $filters['vehicle_id']; }
        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        return (float)Database::query(
            "SELECT COALESCE(SUM(fuel_price),0) FROM fuel_expenses $cond", $params
        )->fetchColumn();
    }

    public static function monthlyStats(int $year): array
    {
        return Database::fetchAll(
            "SELECT MONTH(trip_date) AS m, COALESCE(SUM(fuel_price),0) AS total, COUNT(*) AS trips
             FROM fuel_expenses WHERE YEAR(trip_date) = ?
             GROUP BY MONTH(trip_date) ORDER BY m",
            [$year]
        );
    }

    public static function create(array $data): int
    {
        return (int)Database::insert(
            "INSERT INTO fuel_expenses
             (schedule_id, vehicle_id, driver_id, trip_date, origin, destination,
              fuel_liters, fuel_price, odometer_km, notes, created_by)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                $data['schedule_id']  ?? null,
                $data['vehicle_id'],
                $data['driver_id']    ?? null,
                $data['trip_date'],
                $data['origin']       ?? null,
                $data['destination']  ?? null,
                $data['fuel_liters']  ?? null,
                $data['fuel_price'],
                $data['odometer_km']  ?? null,
                $data['notes']        ?? null,
                $data['created_by']   ?? null,
            ]
        );
    }

    public static function update(int $id, array $data): void
    {
        Database::query(
            "UPDATE fuel_expenses
             SET vehicle_id=?, driver_id=?, trip_date=?, origin=?, destination=?,
                 fuel_liters=?, fuel_price=?, odometer_km=?, notes=?
             WHERE id=?",
            [
                $data['vehicle_id'],
                $data['driver_id']    ?? null,
                $data['trip_date'],
                $data['origin']       ?? null,
                $data['destination']  ?? null,
                $data['fuel_liters']  ?? null,
                $data['fuel_price'],
                $data['odometer_km']  ?? null,
                $data['notes']        ?? null,
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::query("DELETE FROM fuel_expenses WHERE id=?", [$id]);
    }
}
