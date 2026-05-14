<?php
namespace App\Models;
use App\Core\Database;

class Payment
{
    public static function create(array $d): string
    {
        return Database::insert(
            "INSERT INTO payments (booking_id,gateway,amount,status,expired_at) VALUES (?,?,?,?,?)",
            [$d['booking_id'], $d['gateway'] ?? 'midtrans',
             $d['amount'], $d['status'] ?? 'pending', $d['expired_at'] ?? null]
        );
    }

    public static function findByBooking(int $bookingId): ?array
    {
        return Database::fetchOne("SELECT * FROM payments WHERE booking_id=?", [$bookingId]);
    }

    public static function updateByBookingId(int $bookingId, array $data): void
    {
        $sets = []; $params = [];
        foreach ($data as $k => $v) { $sets[] = "$k=?"; $params[] = $v; }
        $params[] = $bookingId;
        Database::query("UPDATE payments SET " . implode(',', $sets) . " WHERE booking_id=?", $params);
    }

    public static function updateByTrxId(string $trxId, array $data): void
    {
        $sets = []; $params = [];
        foreach ($data as $k => $v) { $sets[] = "$k=?"; $params[] = $v; }
        $params[] = $trxId;
        Database::query("UPDATE payments SET " . implode(',', $sets) . " WHERE gateway_trx_id=?", $params);
    }
}
