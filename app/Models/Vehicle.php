<?php
namespace App\Models;
use App\Core\Database;

class Vehicle
{
    public static function all(string $status = 'active'): array
    {
        return Database::fetchAll(
            "SELECT * FROM vehicles WHERE status = ? ORDER BY type, name", [$status]
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM vehicles WHERE id = ?", [$id]);
    }

    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO vehicles
             (name,type,plate_number,chassis_number,engine_number,capacity,
              facilities,tax_due_date,stnk_file,bpkb_file,image,status)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $d['name'],
                $d['type'],
                $d['plate_number'],
                $d['chassis_number'] ?? null,
                $d['engine_number']  ?? null,
                $d['capacity'],
                json_encode($d['facilities'] ?? []),
                $d['tax_due_date']   ?: null,
                $d['stnk_file']      ?? null,
                $d['bpkb_file']      ?? null,
                $d['image']          ?? null,
                $d['status']         ?? 'active',
            ]
        );
    }

    public static function update(int $id, array $d): void
    {
        Database::query(
            "UPDATE vehicles
             SET name=?,type=?,plate_number=?,chassis_number=?,engine_number=?,
                 capacity=?,facilities=?,tax_due_date=?,
                 stnk_file=?,bpkb_file=?,status=?
             WHERE id=?",
            [
                $d['name'],
                $d['type'],
                $d['plate_number'],
                $d['chassis_number'] ?? null,
                $d['engine_number']  ?? null,
                $d['capacity'],
                json_encode($d['facilities'] ?? []),
                $d['tax_due_date']   ?: null,
                $d['stnk_file']      ?? null,
                $d['bpkb_file']      ?? null,
                $d['status'],
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::query("UPDATE vehicles SET status='inactive' WHERE id=?", [$id]);
    }

    public static function count(): int
    {
        return (int)(Database::fetchOne("SELECT COUNT(*) as c FROM vehicles WHERE status='active'")['c'] ?? 0);
    }

    /** Kendaraan yang pajaknya akan jatuh tempo dalam $days hari ke depan */
    public static function taxExpiringSoon(int $days = 30): array
    {
        return Database::fetchAll(
            "SELECT * FROM vehicles
             WHERE status='active'
               AND tax_due_date IS NOT NULL
               AND tax_due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
             ORDER BY tax_due_date ASC",
            [$days]
        );
    }
}
