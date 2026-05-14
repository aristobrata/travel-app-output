<?php
namespace App\Models;
use App\Core\Database;

class Route
{
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM routes WHERE is_active=1 ORDER BY origin, destination");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM routes WHERE id=?", [$id]);
    }

    public static function search(string $origin, string $destination): array
    {
        return Database::fetchAll(
            "SELECT * FROM routes WHERE origin LIKE ? AND destination LIKE ? AND is_active=1",
            ["%$origin%", "%$destination%"]
        );
    }

    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO routes (origin,destination,distance_km,duration_min,base_price) VALUES (?,?,?,?,?)",
            [$d['origin'], $d['destination'], $d['distance_km'], $d['duration_min'], $d['base_price']]
        );
    }

    public static function update(int $id, array $d): void
    {
        Database::query(
            "UPDATE routes SET origin=?,destination=?,distance_km=?,duration_min=?,base_price=?,is_active=? WHERE id=?",
            [$d['origin'], $d['destination'], $d['distance_km'], $d['duration_min'], $d['base_price'], $d['is_active'] ?? 1, $id]
        );
    }

    public static function allAdmin(): array
    {
        return Database::fetchAll("SELECT * FROM routes ORDER BY origin, destination");
    }

    public static function delete(int $id): void
    {
        Database::query("UPDATE routes SET is_active=0 WHERE id=?", [$id]);
    }

    public static function toggleActive(int $id): void
    {
        Database::query("UPDATE routes SET is_active = 1 - is_active WHERE id=?", [$id]);
    }

    public static function origins(): array
    {
        return Database::fetchAll("SELECT DISTINCT origin FROM routes WHERE is_active=1 ORDER BY origin");
    }
}
