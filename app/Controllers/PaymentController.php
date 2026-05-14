<?php
namespace App\Controllers;

use App\Core\{Database, Request, Response};
use App\Models\{Booking, Payment, Schedule};
use App\Services\PaymentService;

class PaymentController
{
    // ── Initiate: buat transaksi Midtrans Snap dari halaman booking ───────────
    public function initiate(): void
    {
        $bookingId = (int)Request::post('booking_id', 0);
        $booking   = Booking::findById($bookingId);

        if (!$booking || (int)$booking['user_id'] !== (int)($_SESSION['user_id'] ?? 0)) {
            Response::json(['success' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        }
        if ($booking['status'] !== 'pending') {
            Response::json(['success' => false, 'message' => 'Booking sudah diproses.']);
        }

        $payment = Payment::findByBooking($bookingId);
        if (!$payment) {
            Response::json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.']);
        }

        try {
            $token = (new PaymentService())->createTransaction([
                'booking_id'   => $bookingId,
                'booking_code' => $booking['booking_code'],
                'amount'       => $booking['total_price'],
                'customer'     => [
                    'name'  => $booking['contact_name'],
                    'email' => $booking['contact_email'],
                    'phone' => $booking['contact_phone'],
                ],
            ]);
            $_SESSION['payment_token'] = $token;
            Response::json(['success' => true, 'token' => $token]);
        } catch (\Exception $e) {
            error_log('[PaymentController::initiate] ' . $e->getMessage());
            Response::json(['success' => false, 'message' => 'Gagal membuat transaksi pembayaran.'], 500);
        }
    }

    // ── Callback/Webhook Midtrans ─────────────────────────────────────────────
    public function callback(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        if (!$payload) { http_response_code(400); exit('Bad Request'); }

        $cfg       = require BASE_PATH . '/config/payment.php';
        $serverKey = $cfg['midtrans']['server_key'] ?? '';

        // Verifikasi signature Midtrans
        $expected = hash('sha512',
            ($payload['order_id']     ?? '') .
            ($payload['status_code']  ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );
        if (($payload['signature_key'] ?? '') !== $expected) {
            http_response_code(403);
            exit('Invalid signature');
        }

        $orderId = $payload['order_id']           ?? '';
        $status  = $payload['transaction_status'] ?? '';
        $trxId   = $payload['transaction_id']     ?? '';
        $payType = $payload['payment_type']       ?? '';

        $booking = Booking::findByCode($orderId);
        if (!$booking) { http_response_code(404); exit('Booking not found'); }

        match (true) {
            in_array($status, ['capture', 'settlement']) =>
                $this->handlePaid($booking['id'], $trxId, $payType, $payload),
            in_array($status, ['cancel', 'expire', 'deny']) =>
                $this->handleFailed($booking['id'], $status, $booking),
            default => null,
        };

        http_response_code(200);
        echo 'OK';
    }

    // ── Halaman setelah pembayaran sukses ─────────────────────────────────────
    public function finish(): void
    {
        $orderId = Request::get('order_id', '');
        $booking = $orderId ? Booking::findByCode($orderId) : null;

        // Jika pembayaran berhasil, update status
        if ($booking && $booking['status'] === 'pending') {
            $trxId = Request::get('transaction_id', '');
            if ($trxId) {
                $this->handlePaid($booking['id'], $trxId, Request::get('payment_type', ''), $_GET);
                $booking = Booking::findById($booking['id']); // reload
            }
        }

        $_SESSION['success'] = $booking
            ? 'Pembayaran berhasil! Tiket Anda sudah dikonfirmasi.'
            : 'Pembayaran selesai.';

        $redirectTo = $booking ? '/booking/' . $booking['id'] : '/my-bookings';
        Response::redirect($redirectTo);
    }

    // ── Halaman pembayaran belum selesai (pending) ────────────────────────────
    public function unfinish(): void
    {
        $orderId = Request::get('order_id', '');
        $booking = $orderId ? Booking::findByCode($orderId) : null;

        $_SESSION['error'] = 'Pembayaran belum diselesaikan. Silakan selesaikan pembayaran Anda.';
        $redirectTo = $booking ? '/booking/' . $booking['id'] . '/payment' : '/my-bookings';
        Response::redirect($redirectTo);
    }

    // ── Halaman error pembayaran ──────────────────────────────────────────────
    public function error(): void
    {
        $orderId = Request::get('order_id', '');
        $booking = $orderId ? Booking::findByCode($orderId) : null;

        $_SESSION['error'] = 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.';
        $redirectTo = $booking ? '/booking/' . $booking['id'] . '/payment' : '/my-bookings';
        Response::redirect($redirectTo);
    }

    // ── Private helpers ───────────────────────────────────────────────────────
    private function handlePaid(int $bookingId, string $trxId, string $payType, array $raw): void
    {
        Database::query(
            "UPDATE payments SET status='paid', gateway_trx_id=?, payment_type=?,
             paid_at=NOW(), raw_response=? WHERE booking_id=?",
            [$trxId, $payType, json_encode($raw), $bookingId]
        );
        Booking::updateStatus($bookingId, 'paid');
    }

    private function handleFailed(int $bookingId, string $reason, array $booking): void
    {
        Database::query(
            "UPDATE payments SET status='failed' WHERE booking_id=?",
            [$bookingId]
        );
        Booking::updateStatus($bookingId, 'cancelled');

        // Kembalikan kursi
        $seats = Booking::seats($bookingId);
        if ($seats) {
            Schedule::incrementSeats((int)$booking['schedule_id'], count($seats));
        }
    }
}
