<?php
namespace App\Models;
use App\Core\Database;

class SeatLock
{
    public static function bookedAndLocked(int $scheduleId): array
    {
        $booked = Database::fetchAll(
            "SELECT bs.seat_number, 'booked' as type
             FROM booking_seats bs
             JOIN bookings b ON b.id=bs.booking_id
             WHERE b.schedule_id=? AND b.status IN ('pending','paid')",
            [$scheduleId]
        );
        $locked = Database::fetchAll(
            "SELECT seat_number, 'locked' as type FROM seat_locks
             WHERE schedule_id=? AND locked_until > NOW()",
            [$scheduleId]
        );
        return array_merge($booked, $locked);
    }

    public static function lock(int $scheduleId, int $seatNo, int $userId): bool
    {
        // Cek apakah kursi sudah dikunci user lain
        if (Database::fetchOne(
            "SELECT id FROM seat_locks WHERE schedule_id=? AND seat_number=? AND locked_until>NOW() AND user_id != ?",
            [$scheduleId, $seatNo, $userId]
        )) return false;

        // Cek apakah kursi sudah dipesan
        if (Database::fetchOne(
            "SELECT bs.id FROM booking_seats bs JOIN bookings b ON b.id=bs.booking_id
             WHERE b.schedule_id=? AND bs.seat_number=? AND b.status IN ('pending','paid')",
            [$scheduleId, $seatNo]
        )) return false;

        try {
            Database::query(
                "INSERT INTO seat_locks (schedule_id, seat_number, user_id, locked_until)
                 VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE))
                 ON DUPLICATE KEY UPDATE user_id=VALUES(user_id), locked_until=VALUES(locked_until)",
                [$scheduleId, $seatNo, $userId]
            );
            return true;
        } catch (\Exception $e) {
            error_log('[SeatLock::lock] ' . $e->getMessage());
            return false;
        }
    }

    public static function release(int $scheduleId, array $seatNumbers): void
    {
        if (empty($seatNumbers)) return;
        $ph = implode(',', array_fill(0, count($seatNumbers), '?'));
        Database::query(
            "DELETE FROM seat_locks WHERE schedule_id=? AND seat_number IN ($ph)",
            array_merge([$scheduleId], $seatNumbers)
        );
    }

    public static function cleanup(): void
    {
        Database::query("DELETE FROM seat_locks WHERE locked_until < NOW()");
    }
}
