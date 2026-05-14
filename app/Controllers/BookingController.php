<?php
namespace App\Controllers;

use App\Core\{Database, Request, Response};
use App\Middleware\CsrfMiddleware;
use App\Models\{Booking, Payment, Route, Schedule, SeatLock};
use App\Services\{MapsService, PaymentService};

class BookingController
{
    // ── Beranda ──────────────────────────────────────────────────────────────
    public function index(): void
    {
        $origins = Route::origins();
        require BASE_PATH . '/views/booking/search.php';
    }

    // ── Hasil Pencarian ──────────────────────────────────────────────────────
    public function search(): void
    {
        $origin      = Request::sanitize('origin');
        $destination = Request::sanitize('destination');
        $date        = Request::get('date', date('Y-m-d'));

        if (!$origin || !$destination) Response::redirect('/');

        $routes    = Route::search($origin, $destination);
        $schedules = [];
        foreach ($routes as $route) {
            foreach (Schedule::search($route['id'], $date) as $s) {
                $schedules[] = $s;
            }
        }

        require BASE_PATH . '/views/booking/results.php';
    }

    // ── Pilih Kursi ──────────────────────────────────────────────────────────
    public function selectSeat(string $scheduleId): void
    {
        $schedule = Schedule::findById((int)$scheduleId);
        if (!$schedule) { http_response_code(404); require BASE_PATH . '/views/errors/404.php'; return; }

        SeatLock::cleanup();
        $takenSeats = SeatLock::bookedAndLocked((int)$scheduleId);
        $takenNos   = array_column($takenSeats, 'seat_number');

        require BASE_PATH . '/views/booking/seat-select.php';
    }

    // ── API: Status Kursi (JSON) ──────────────────────────────────────────────
    public function seatStatus(string $scheduleId): void
    {
        try {
            SeatLock::cleanup();
            $taken = SeatLock::bookedAndLocked((int)$scheduleId);
            Response::json(['taken' => $taken]);
        } catch (\Exception $e) {
            error_log('[seatStatus] ' . $e->getMessage());
            Response::json(['taken' => []], 200);
        }
    }

    // ── API: Lock Kursi (AJAX POST) ───────────────────────────────────────────
    public function lockSeat(): void
    {
        // Pastikan hanya AJAX
        if (!Request::isAjax()) {
            Response::json(['error' => 'Forbidden'], 403);
            return; // tidak akan tercapai karena Response::json exit, tapi baik untuk kejelasan
        }

        $data       = Request::json();
        $scheduleId = (int)($data['schedule_id'] ?? 0);
        $seatNo     = (int)($data['seat_number']  ?? 0);
        $userId     = (int)($_SESSION['user_id']  ?? 0);

        if (!$scheduleId || !$seatNo || !$userId) {
            Response::json(['success' => false, 'message' => 'Data tidak valid']);
        }

        try {
            SeatLock::cleanup();
            $ok = SeatLock::lock($scheduleId, $seatNo, $userId);
            Response::json([
                'success' => $ok,
                'message' => $ok ? 'Kursi berhasil di-lock' : 'Kursi sudah tidak tersedia',
            ]);
        } catch (\Throwable $e) {
            error_log('[lockSeat] ' . $e->getMessage());
            // Tampilkan pesan error asli agar mudah debug
            Response::json([
                'success' => false,
                'message' => 'DB Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── API: Info Rute Google Maps ────────────────────────────────────────────
    public function routeInfo(): void
    {
        $origin      = Request::sanitize('origin');
        $destination = Request::sanitize('destination');

        if (!$origin || !$destination) Response::json(['error' => 'Parameter kurang'], 400);

        $result = (new MapsService())->getRouteInfo($origin, $destination);
        if ($result) {
            Response::json($result);
        } else {
            Response::json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    // ── Form Data Penumpang ───────────────────────────────────────────────────
    public function passengerForm(): void
    {
        $scheduleId = (int)Request::post('schedule_id', 0);
        $seatsRaw   = Request::post('seats', '');
        $seats      = array_filter(array_map('intval', explode(',', $seatsRaw)));

        if (!$scheduleId || empty($seats)) Response::redirect('/');

        $schedule = Schedule::findById($scheduleId);
        if (!$schedule) Response::redirect('/');

        $_SESSION['booking_draft'] = [
            'schedule_id' => $scheduleId,
            'seats'       => array_values($seats),
        ];

        require BASE_PATH . '/views/booking/passenger.php';
    }

    // ── Konfirmasi & Buat Booking ─────────────────────────────────────────────
    public function confirm(): void
    {
        $draft = $_SESSION['booking_draft'] ?? null;
        if (!$draft) Response::redirect('/');

        $scheduleId = $draft['schedule_id'];
        $seats      = $draft['seats'];
        $schedule   = Schedule::findById($scheduleId);

        if (!$schedule) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan.';
            Response::redirect('/');
        }

        // Validasi data penumpang
        $passengers = [];
        foreach ($seats as $i => $seatNo) {
            $name = Request::sanitize("passenger_name_$i");
            if (!$name) {
                $_SESSION['error'] = 'Nama penumpang wajib diisi semua.';
                Response::back();
            }
            $passengers[$seatNo] = [
                'name'  => $name,
                'id_no' => Request::sanitize("passenger_id_$i"),
            ];
        }

        $totalPrice   = $schedule['price'] * count($seats);
        $contactName  = Request::sanitize('contact_name');
        $contactPhone = Request::sanitize('contact_phone');
        $contactEmail = Request::sanitize('contact_email');

        Database::beginTransaction();
        try {
            $code      = Booking::generateCode();
            $bookingId = Booking::create([
                'booking_code'    => $code,
                'user_id'         => (int)$_SESSION['user_id'],
                'schedule_id'     => $scheduleId,
                'passenger_count' => count($seats),
                'total_price'     => $totalPrice,
                'contact_name'    => $contactName,
                'contact_phone'   => $contactPhone,
                'contact_email'   => $contactEmail,
                'notes'           => Request::sanitize('notes'),
            ]);

            foreach ($passengers as $seatNo => $p) {
                Booking::addSeat((int)$bookingId, (int)$seatNo, $p['name'], $p['id_no'] ?: null);
            }

            Schedule::decrementSeats($scheduleId, count($seats));
            SeatLock::release($scheduleId, $seats);

            // Buat payment token Midtrans
            $paymentToken = (new PaymentService())->createTransaction([
                'booking_id'   => $bookingId,
                'booking_code' => $code,
                'amount'       => $totalPrice,
                'customer'     => [
                    'name'  => $contactName,
                    'email' => $contactEmail,
                    'phone' => $contactPhone,
                ],
            ]);

            Payment::create([
                'booking_id' => (int)$bookingId,
                'amount'     => $totalPrice,
                'status'     => 'pending',
                'expired_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            ]);

            Database::commit();

            $_SESSION['payment_token'] = $paymentToken;
            unset($_SESSION['booking_draft']);

            Response::redirect("/booking/payment/$bookingId");

        } catch (\Exception $e) {
            Database::rollBack();
            error_log('[BookingController::confirm] ' . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            Response::back();
        }
    }

    // ── Halaman Pembayaran ────────────────────────────────────────────────────
    public function payment(string $id): void
    {
        $booking = Booking::findById((int)$id);
        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
            Response::redirect('/my-bookings');
        }

        $payment      = Payment::findByBooking((int)$id);
        $seats        = Booking::seats((int)$id);
        $paymentToken = $_SESSION['payment_token'] ?? null;
        $cfg          = require BASE_PATH . '/config/payment.php';

        require BASE_PATH . '/views/booking/payment.php';
    }

    // ── E-Tiket / Success ────────────────────────────────────────────────────
    public function success(string $id): void
    {
        $booking = Booking::findById((int)$id);
        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
            Response::redirect('/my-bookings');
        }
        $seats = Booking::seats((int)$id);
        require BASE_PATH . '/views/booking/success.php';
    }

    // ── Riwayat Pesanan ──────────────────────────────────────────────────────
    public function myBookings(): void
    {
        $bookings = Booking::byUser((int)$_SESSION['user_id']);
        require BASE_PATH . '/views/booking/my-bookings.php';
    }

    // ── Detail Satu Pesanan ──────────────────────────────────────────────────
    public function detail(string $id): void
    {
        $booking = Booking::findById((int)$id);
        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
            Response::redirect('/my-bookings');
        }
        $seats   = Booking::seats((int)$id);
        $payment = Payment::findByBooking((int)$id);
        require BASE_PATH . '/views/booking/detail.php';
    }

    // ── DEBUG: Diagnosa masalah seat lock (hapus setelah selesai) ────────────
    public function debugSeat(): void
    {
        $result = ['steps' => [], 'error' => null];
        
        try {
            // Step 1: Session
            $userId = (int)($_SESSION['user_id'] ?? 0);
            $result['steps'][] = "1. Session user_id: $userId";
            if (!$userId) {
                $result['error'] = 'Tidak ada session user_id';
                Response::json($result);
            }

            // Step 2: Parse JSON body
            $data = Request::json();
            $scheduleId = (int)($data['schedule_id'] ?? 0);
            $seatNo     = (int)($data['seat_number']  ?? 0);
            $result['steps'][] = "2. JSON body: schedule_id=$scheduleId, seat_number=$seatNo";
            
            // Step 3: DB connection
            $result['steps'][] = "3. Mencoba koneksi database...";
            \App\Core\Database::fetchOne("SELECT 1 as ping");
            $result['steps'][] = "3. DB OK";

            // Step 4: Check seat_locks table
            $result['steps'][] = "4. Cek tabel seat_locks...";
            \App\Core\Database::fetchAll("SELECT * FROM seat_locks LIMIT 1");
            $result['steps'][] = "4. Tabel seat_locks OK";

            // Step 5: Try cleanup
            \App\Models\SeatLock::cleanup();
            $result['steps'][] = "5. Cleanup OK";

            // Step 6: Try lock
            if ($scheduleId && $seatNo) {
                $ok = \App\Models\SeatLock::lock($scheduleId, $seatNo, $userId);
                $result['steps'][] = "6. Lock result: " . ($ok ? "BERHASIL" : "GAGAL (kursi mungkin sudah diambil)");
                $result['lock_result'] = $ok;
            }

            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $result['file']  = $e->getFile() . ':' . $e->getLine();
            $result['success'] = false;
        }

        Response::json($result);
    }

}