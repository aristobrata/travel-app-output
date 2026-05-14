<?php
namespace App\Models;
use App\Core\Database;

class User
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO users (name, email, phone, password_hash, role) VALUES (?,?,?,?,?)",
            [$d['name'], $d['email'], $d['phone'] ?? null,
             password_hash($d['password'], PASSWORD_BCRYPT, ['cost' => 12]),
             $d['role'] ?? 'user']
        );
    }

    public static function update(int $id, array $d): void
    {
        $sets = []; $params = [];
        foreach ($d as $k => $v) { $sets[] = "$k = ?"; $params[] = $v; }
        $params[] = $id;
        Database::query("UPDATE users SET " . implode(', ', $sets) . " WHERE id = ?", $params);
    }

    public static function all(int $limit = 50, int $offset = 0): array
    {
        return Database::fetchAll(
            "SELECT id, name, email, phone, role, is_active, created_at
             FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public static function count(): int
    {
        return (int)(Database::fetchOne("SELECT COUNT(*) as c FROM users")['c'] ?? 0);
    }

    public static function verify(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if ($user && (bool)$user['is_active'] && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }
}
