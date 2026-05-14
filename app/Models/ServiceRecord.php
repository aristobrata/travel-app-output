<?php
namespace App\Models;

use App\Core\Database;

class ServiceRecord
{
    public static function all(int $limit = 200, int $offset = 0, array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['vehicle_id']))   { $where[] = "sr.vehicle_id = ?";   $params[] = $filters['vehicle_id']; }
        if (!empty($filters['service_type'])) { $where[] = "sr.service_type = ?"; $params[] = $filters['service_type']; }
        if (!empty($filters['status']))       { $where[] = "sr.status = ?";        $params[] = $filters['status']; }
        if (!empty($filters['from']))         { $where[] = "sr.service_date >= ?"; $params[] = $filters['from']; }
        if (!empty($filters['to']))           { $where[] = "sr.service_date <= ?"; $params[] = $filters['to']; }

        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $params[] = $limit;
        $params[] = $offset;

        return Database::fetchAll(
            "SELECT sr.*, v.name AS vehicle_name, v.plate_number
             FROM service_records sr
             JOIN vehicles v ON v.id = sr.vehicle_id
             $cond
             ORDER BY sr.service_date DESC, sr.id DESC
             LIMIT ? OFFSET ?",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT sr.*, v.name AS vehicle_name, v.plate_number
             FROM service_records sr
             JOIN vehicles v ON v.id = sr.vehicle_id
             WHERE sr.id = ?",
            [$id]
        );
    }

    public static function byVehicle(int $vehicleId, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT * FROM service_records WHERE vehicle_id = ? ORDER BY service_date DESC LIMIT ?",
            [$vehicleId, $limit]
        );
    }

    public static function totalCost(array $filters = []): float
    {
        $where  = [];
        $params = [];
        if (!empty($filters['from']))       { $where[] = "service_date >= ?"; $params[] = $filters['from']; }
        if (!empty($filters['to']))         { $where[] = "service_date <= ?"; $params[] = $filters['to']; }
        if (!empty($filters['vehicle_id'])) { $where[] = "vehicle_id = ?";   $params[] = $filters['vehicle_id']; }
        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        return (float)Database::query(
            "SELECT COALESCE(SUM(cost),0) FROM service_records $cond", $params
        )->fetchColumn();
    }

    public static function scheduledSoon(int $days = 14): array
    {
        return Database::fetchAll(
            "SELECT sr.*, v.name AS vehicle_name, v.plate_number
             FROM service_records sr
             JOIN vehicles v ON v.id = sr.vehicle_id
             WHERE sr.next_service_date IS NOT NULL
               AND sr.next_service_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
               AND sr.status != 'dijadwalkan'
             ORDER BY sr.next_service_date ASC",
            [$days]
        );
    }

    public static function monthlyStats(int $year): array
    {
        return Database::fetchAll(
            "SELECT MONTH(service_date) AS m, COALESCE(SUM(cost),0) AS total, COUNT(*) AS cnt
             FROM service_records WHERE YEAR(service_date) = ?
             GROUP BY MONTH(service_date) ORDER BY m",
            [$year]
        );
    }

    public static function typeStats(array $filters = []): array
    {
        $where  = [];
        $params = [];
        if (!empty($filters['from'])) { $where[] = "service_date >= ?"; $params[] = $filters['from']; }
        if (!empty($filters['to']))   { $where[] = "service_date <= ?"; $params[] = $filters['to']; }
        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        return Database::fetchAll(
            "SELECT service_type, COUNT(*) AS cnt, SUM(cost) AS total
             FROM service_records $cond GROUP BY service_type ORDER BY total DESC",
            $params
        );
    }

    public static function create(array $data): int
    {
        return (int)Database::insert(
            "INSERT INTO service_records
             (vehicle_id, service_date, service_type, description, workshop, cost,
              odometer_km, next_service_km, next_service_date, status, created_by)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                $data['vehicle_id'],
                $data['service_date'],
                $data['service_type']      ?? 'lainnya',
                $data['description']       ?? null,
                $data['workshop']          ?? null,
                $data['cost']              ?? 0,
                $data['odometer_km']       ?? null,
                $data['next_service_km']   ?? null,
                $data['next_service_date'] ?? null,
                $data['status']            ?? 'selesai',
                $data['created_by']        ?? null,
            ]
        );
    }

    public static function update(int $id, array $data): void
    {
        Database::query(
            "UPDATE service_records
             SET vehicle_id=?, service_date=?, service_type=?, description=?, workshop=?,
                 cost=?, odometer_km=?, next_service_km=?, next_service_date=?, status=?
             WHERE id=?",
            [
                $data['vehicle_id'],
                $data['service_date'],
                $data['service_type']      ?? 'lainnya',
                $data['description']       ?? null,
                $data['workshop']          ?? null,
                $data['cost']              ?? 0,
                $data['odometer_km']       ?? null,
                $data['next_service_km']   ?? null,
                $data['next_service_date'] ?? null,
                $data['status']            ?? 'selesai',
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::query("DELETE FROM service_records WHERE id=?", [$id]);
    }

    public static function serviceTypeLabel(string $type): string
    {
        return match($type) {
            'oli'       => 'Ganti Oli',
            'tune_up'   => 'Tune Up',
            'ban'       => 'Ganti Ban',
            'rem'       => 'Servis Rem',
            'ac'        => 'Servis AC',
            'mesin'     => 'Perbaikan Mesin',
            'bodi'      => 'Perbaikan Bodi',
            'kaki_kaki' => 'Servis Kaki-Kaki',
            default     => 'Lainnya',
        };
    }
}
