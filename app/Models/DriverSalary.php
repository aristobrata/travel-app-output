<?php
namespace App\Models;

use App\Core\Database;

class DriverSalary
{
    public static function all(int $limit = 200, int $offset = 0, array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['driver_id']))    { $where[] = "ds.driver_id = ?";    $params[] = $filters['driver_id']; }
        if (!empty($filters['status']))       { $where[] = "ds.status = ?";        $params[] = $filters['status']; }
        if (!empty($filters['period_year']))  { $where[] = "ds.period_year = ?";   $params[] = $filters['period_year']; }
        if (!empty($filters['period_month'])) { $where[] = "ds.period_month = ?";  $params[] = $filters['period_month']; }

        $cond = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $params[] = $limit;
        $params[] = $offset;

        return Database::fetchAll(
            "SELECT ds.*, d.name AS driver_name, d.phone AS driver_phone
             FROM driver_salaries ds
             JOIN drivers d ON d.id = ds.driver_id
             $cond
             ORDER BY ds.period_year DESC, ds.period_month DESC, d.name ASC
             LIMIT ? OFFSET ?",
            $params
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT ds.*, d.name AS driver_name, d.phone AS driver_phone, d.base_salary AS driver_base
             FROM driver_salaries ds
             JOIN drivers d ON d.id = ds.driver_id
             WHERE ds.id = ?",
            [$id]
        );
    }

    public static function findByDriverPeriod(int $driverId, int $month, int $year): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM driver_salaries WHERE driver_id=? AND period_month=? AND period_year=?",
            [$driverId, $month, $year]
        );
    }

    public static function totalPaid(array $filters = []): float
    {
        $where  = ["status='paid'"];
        $params = [];
        if (!empty($filters['period_year']))  { $where[] = "period_year = ?";  $params[] = $filters['period_year']; }
        if (!empty($filters['period_month'])) { $where[] = "period_month = ?"; $params[] = $filters['period_month']; }
        $cond = 'WHERE ' . implode(' AND ', $where);
        return (float)Database::query(
            "SELECT COALESCE(SUM(net_salary),0) FROM driver_salaries $cond", $params
        )->fetchColumn();
    }

    public static function yearlyStats(int $year): array
    {
        return Database::fetchAll(
            "SELECT period_month AS m, SUM(net_salary) AS total, COUNT(*) AS cnt
             FROM driver_salaries WHERE period_year = ? AND status = 'paid'
             GROUP BY period_month ORDER BY period_month",
            [$year]
        );
    }

    public static function countTripsByDriverMonth(int $driverId, int $month, int $year): int
    {
        return (int)Database::query(
            "SELECT COUNT(*) FROM fuel_expenses
             WHERE driver_id = ? AND MONTH(trip_date) = ? AND YEAR(trip_date) = ?",
            [$driverId, $month, $year]
        )->fetchColumn();
    }

    public static function create(array $data): int
    {
        $net = ($data['base_salary'] ?? 0) + ($data['bonus'] ?? 0) - ($data['deduction'] ?? 0);
        return (int)Database::insert(
            "INSERT INTO driver_salaries
             (driver_id, period_month, period_year, base_salary, bonus, deduction,
              net_salary, trip_count, paid_at, status, notes, created_by)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $data['driver_id'],
                $data['period_month'],
                $data['period_year'],
                $data['base_salary'] ?? 0,
                $data['bonus']       ?? 0,
                $data['deduction']   ?? 0,
                $net,
                $data['trip_count']  ?? 0,
                $data['paid_at']     ?? null,
                $data['status']      ?? 'draft',
                $data['notes']       ?? null,
                $data['created_by']  ?? null,
            ]
        );
    }

    public static function update(int $id, array $data): void
    {
        $net = ($data['base_salary'] ?? 0) + ($data['bonus'] ?? 0) - ($data['deduction'] ?? 0);
        Database::query(
            "UPDATE driver_salaries
             SET driver_id=?, period_month=?, period_year=?, base_salary=?, bonus=?,
                 deduction=?, net_salary=?, trip_count=?, paid_at=?, status=?, notes=?
             WHERE id=?",
            [
                $data['driver_id'],
                $data['period_month'],
                $data['period_year'],
                $data['base_salary'] ?? 0,
                $data['bonus']       ?? 0,
                $data['deduction']   ?? 0,
                $net,
                $data['trip_count']  ?? 0,
                $data['paid_at']     ?? null,
                $data['status']      ?? 'draft',
                $data['notes']       ?? null,
                $id,
            ]
        );
    }

    public static function markPaid(int $id): void
    {
        Database::query(
            "UPDATE driver_salaries SET status='paid', paid_at=CURDATE() WHERE id=?",
            [$id]
        );
    }

    public static function delete(int $id): void
    {
        Database::query("DELETE FROM driver_salaries WHERE id=?", [$id]);
    }

    public static function monthName(int $m): string
    {
        return ['','Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'][$m] ?? '';
    }
}
