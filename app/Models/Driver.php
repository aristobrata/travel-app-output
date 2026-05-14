<?php
namespace App\Models;

use App\Core\Database;

class Driver
{
    public static function all(int $limit = 100, int $offset = 0, string $status = ''): array
    {
        if ($status !== '') {
            return Database::fetchAll(
                "SELECT * FROM drivers WHERE status = ? ORDER BY name ASC LIMIT ? OFFSET ?",
                [$status, $limit, $offset]
            );
        }
        return Database::fetchAll(
            "SELECT * FROM drivers ORDER BY name ASC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM drivers WHERE id = ?", [$id]);
    }

    public static function count(string $status = ''): int
    {
        if ($status !== '') {
            return (int)Database::query("SELECT COUNT(*) FROM drivers WHERE status = ?", [$status])->fetchColumn();
        }
        return (int)Database::query("SELECT COUNT(*) FROM drivers")->fetchColumn();
    }

    public static function create(array $data): int
    {
        return (int)Database::insert(
            "INSERT INTO drivers (name, nik, phone, address, license_no, license_exp, base_salary, status, joined_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['nik']         ?? null,
                $data['phone']       ?? null,
                $data['address']     ?? null,
                $data['license_no']  ?? null,
                $data['license_exp'] ?? null,
                $data['base_salary'] ?? 0,
                $data['status']      ?? 'active',
                $data['joined_at']   ?? null,
            ]
        );
    }

    public static function update(int $id, array $data): void
    {
        Database::query(
            "UPDATE drivers SET name=?, nik=?, phone=?, address=?, license_no=?, license_exp=?, base_salary=?, status=?, joined_at=? WHERE id=?",
            [
                $data['name'],
                $data['nik']         ?? null,
                $data['phone']       ?? null,
                $data['address']     ?? null,
                $data['license_no']  ?? null,
                $data['license_exp'] ?? null,
                $data['base_salary'] ?? 0,
                $data['status']      ?? 'active',
                $data['joined_at']   ?? null,
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::query("UPDATE drivers SET status='inactive' WHERE id=?", [$id]);
    }

    public static function licenseSoonExpiring(int $days = 30): array
    {
        return Database::fetchAll(
            "SELECT * FROM drivers WHERE status='active' AND license_exp IS NOT NULL
             AND license_exp <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY license_exp ASC",
            [$days]
        );
    }
}
