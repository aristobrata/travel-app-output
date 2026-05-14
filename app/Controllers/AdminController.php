<?php
namespace App\Controllers;

use App\Core\{Database, Request, Response};
use App\Middleware\CsrfMiddleware;
use App\Models\{Booking, Payment, Route, Schedule, User, Vehicle};

class AdminController
{
    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function dashboard(): void
    {
        $stats = [
            'total_users'     => User::count(),
            'total_vehicles'  => Vehicle::count(),
            'total_schedules' => Schedule::count(),
            'pending'         => Booking::countByStatus('pending'),
            'paid'            => Booking::countByStatus('paid'),
            'cancelled'       => Booking::countByStatus('cancelled'),
            'revenue'         => Booking::recentRevenue(),
        ];
        $recentBookings = Booking::all(10, 0);
        $taxWarning     = Vehicle::taxExpiringSoon(30);
        require BASE_PATH . '/views/admin/dashboard.php';
    }

    // ── Kendaraan ─────────────────────────────────────────────────────────────
    public function vehicles(): void
    {
        $vehicles   = Vehicle::all('active');
        $taxWarning = Vehicle::taxExpiringSoon(30);
        require BASE_PATH . '/views/admin/vehicles.php';
    }

    public function vehicleCreate(): void
    {
        if (Request::isPost()) {
            $errors = Request::validate([
                'name'         => 'required',
                'plate_number' => 'required',
                'capacity'     => 'required|numeric',
            ]);
            if (!$errors) {
                $stnkFile = self::handleUpload('stnk_file', 'stnk');
                $bpkbFile = self::handleUpload('bpkb_file', 'bpkb');
                Vehicle::create([
                    'name'            => Request::sanitize('name'),
                    'type'            => Request::sanitize('type'),
                    'plate_number'    => Request::sanitize('plate_number'),
                    'chassis_number'  => Request::sanitize('chassis_number'),
                    'engine_number'   => Request::sanitize('engine_number'),
                    'capacity'        => (int)Request::post('capacity'),
                    'tax_due_date'    => Request::post('tax_due_date') ?: null,
                    'stnk_file'       => $stnkFile,
                    'bpkb_file'       => $bpkbFile,
                    'facilities'      => [
                        'ac'   => isset($_POST['facility_ac']),
                        'wifi' => isset($_POST['facility_wifi']),
                        'usb'  => isset($_POST['facility_usb']),
                        'tv'   => isset($_POST['facility_tv']),
                    ],
                    'status' => 'active',
                ]);
                $_SESSION['success'] = 'Kendaraan berhasil ditambahkan.';
                Response::redirect('/admin/vehicles');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/vehicle-form.php';
    }

    public function vehicleEdit(string $id): void
    {
        $vehicle = Vehicle::findById((int)$id);
        if (!$vehicle) Response::redirect('/admin/vehicles');
        if (is_string($vehicle['facilities'])) {
            $vehicle['facilities'] = json_decode($vehicle['facilities'], true) ?? [];
        }

        if (Request::isPost()) {
            // Handle file uploads — pertahankan file lama jika tidak ada upload baru
            $stnkFile = self::handleUpload('stnk_file', 'stnk') ?? $vehicle['stnk_file'];
            $bpkbFile = self::handleUpload('bpkb_file', 'bpkb') ?? $vehicle['bpkb_file'];

            Vehicle::update((int)$id, [
                'name'           => Request::sanitize('name'),
                'type'           => Request::sanitize('type'),
                'plate_number'   => Request::sanitize('plate_number'),
                'chassis_number' => Request::sanitize('chassis_number'),
                'engine_number'  => Request::sanitize('engine_number'),
                'capacity'       => (int)Request::post('capacity'),
                'tax_due_date'   => Request::post('tax_due_date') ?: null,
                'stnk_file'      => $stnkFile,
                'bpkb_file'      => $bpkbFile,
                'facilities'     => [
                    'ac'   => isset($_POST['facility_ac']),
                    'wifi' => isset($_POST['facility_wifi']),
                    'usb'  => isset($_POST['facility_usb']),
                    'tv'   => isset($_POST['facility_tv']),
                ],
                'status' => Request::sanitize('status'),
            ]);
            $_SESSION['success'] = 'Kendaraan berhasil diperbarui.';
            Response::redirect('/admin/vehicles');
        }
        require BASE_PATH . '/views/admin/vehicle-form.php';
    }

    public function vehicleDetail(string $id): void
    {
        $vehicle = Vehicle::findById((int)$id);
        if (!$vehicle) Response::redirect('/admin/vehicles');
        if (is_string($vehicle['facilities'])) {
            $vehicle['facilities'] = json_decode($vehicle['facilities'], true) ?? [];
        }
        require BASE_PATH . '/views/admin/vehicle-detail.php';
    }

    /** Upload helper — returns relative path or null */
    private static function handleUpload(string $field, string $prefix): ?string
    {
        if (empty($_FILES[$field]['name'])) return null;
        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $allowed = ['image/jpeg','image/png','image/webp','application/pdf'];
        $mime    = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) return null;

        $ext     = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name    = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $dir     = BASE_PATH . '/public/assets/uploads/';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $dest    = $dir . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
        return 'assets/uploads/' . $name;
    }

    public function vehicleDelete(string $id): void
    {
        Vehicle::delete((int)$id);
        $_SESSION['success'] = 'Kendaraan dinonaktifkan.';
        Response::redirect('/admin/vehicles');
    }

    // ── Rute ─────────────────────────────────────────────────────────────────
    public function routes(): void
    {
        $routes = Route::allAdmin();
        require BASE_PATH . '/views/admin/routes.php';
    }

    public function routeCreate(): void
    {
        if (Request::isPost()) {
            $errors = Request::validate([
                'origin'      => 'required',
                'destination' => 'required',
                'base_price'  => 'required|numeric',
            ]);
            if (!$errors) {
                Route::create([
                    'origin'       => Request::sanitize('origin'),
                    'destination'  => Request::sanitize('destination'),
                    'distance_km'  => (float)Request::post('distance_km', 0),
                    'duration_min' => (int)Request::post('duration_min', 0),
                    'base_price'   => (int)Request::post('base_price'),
                ]);
                $_SESSION['success'] = 'Rute berhasil ditambahkan.';
                Response::redirect('/admin/routes');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/route-form.php';
    }

    public function routeEdit(string $id): void
    {
        $route = Route::findById((int)$id);
        if (!$route) Response::redirect('/admin/routes');

        if (Request::isPost()) {
            Route::update((int)$id, [
                'origin'       => Request::sanitize('origin'),
                'destination'  => Request::sanitize('destination'),
                'distance_km'  => (float)Request::post('distance_km', 0),
                'duration_min' => (int)Request::post('duration_min', 0),
                'base_price'   => (int)Request::post('base_price'),
                'is_active'    => Request::post('is_active', 1),
            ]);
            $_SESSION['success'] = 'Rute berhasil diperbarui.';
            Response::redirect('/admin/routes');
        }
        require BASE_PATH . '/views/admin/route-form.php';
    }

    public function routeDelete(string $id): void
    {
        Route::delete((int)$id);
        $_SESSION['success'] = 'Rute berhasil dinonaktifkan.';
        Response::redirect('/admin/routes');
    }

    public function routeToggle(string $id): void
    {
        Route::toggleActive((int)$id);
        $_SESSION['success'] = 'Status rute berhasil diubah.';
        Response::redirect('/admin/routes');
    }

    // ── Jadwal ────────────────────────────────────────────────────────────────
    public function schedules(): void
    {
        $schedules = Schedule::all(100, 0);
        require BASE_PATH . '/views/admin/schedules.php';
    }

    public function scheduleCreate(): void
    {
        $vehicles = Vehicle::all();
        $routes   = Route::all();

        if (Request::isPost()) {
            $vehicleId = (int)Request::post('vehicle_id');
            $vehicle   = Vehicle::findById($vehicleId);
            if (!$vehicle) {
                $_SESSION['error'] = 'Kendaraan tidak valid.';
                require BASE_PATH . '/views/admin/schedule-form.php';
                return;
            }
            Schedule::create([
                'vehicle_id'      => $vehicleId,
                'route_id'        => (int)Request::post('route_id'),
                'depart_at'       => Request::post('depart_at'),
                'arrive_at'       => Request::post('arrive_at'),
                'available_seats' => (int)$vehicle['capacity'],
                'price_override'  => Request::post('price_override') ?: null,
            ]);
            $_SESSION['success'] = 'Jadwal berhasil ditambahkan.';
            Response::redirect('/admin/schedules');
        }
        require BASE_PATH . '/views/admin/schedule-form.php';
    }

    public function scheduleEdit(string $id): void
    {
        $schedule = Schedule::findById((int)$id);
        if (!$schedule) Response::redirect('/admin/schedules');
        $vehicles = Vehicle::all();
        $routes   = Route::all();

        if (Request::isPost()) {
            Database::query(
                "UPDATE schedules SET vehicle_id=?,route_id=?,depart_at=?,arrive_at=?,price_override=? WHERE id=?",
                [
                    (int)Request::post('vehicle_id'),
                    (int)Request::post('route_id'),
                    Request::post('depart_at'),
                    Request::post('arrive_at'),
                    Request::post('price_override') ?: null,
                    (int)$id,
                ]
            );
            $_SESSION['success'] = 'Jadwal berhasil diperbarui.';
            Response::redirect('/admin/schedules');
        }
        require BASE_PATH . '/views/admin/schedule-form.php';
    }

    public function scheduleDelete(string $id): void
    {
        Database::query("UPDATE schedules SET status='cancelled' WHERE id=?", [(int)$id]);
        $_SESSION['success'] = 'Jadwal dibatalkan.';
        Response::redirect('/admin/schedules');
    }

    // ── Pemesanan ─────────────────────────────────────────────────────────────
    public function bookings(): void
    {
        $status   = Request::get('status', '');
        $bookings = Booking::all(100, 0, $status);
        require BASE_PATH . '/views/admin/bookings.php';
    }

    public function bookingDetail(string $id): void
    {
        $booking = Booking::findById((int)$id);
        if (!$booking) Response::redirect('/admin/bookings');
        $seats   = Booking::seats((int)$id);
        $payment = Payment::findByBooking((int)$id);
        require BASE_PATH . '/views/admin/booking-detail.php';
    }

    // ── Pengguna ──────────────────────────────────────────────────────────────
    public function users(): void
    {
        $users = User::all(100);
        require BASE_PATH . '/views/admin/users.php';
    }

    public function userToggleActive(string $id): void
    {
        $user = User::findById((int)$id);
        if (!$user) Response::redirect('/admin/users');
        if ((int)$id === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['error'] = 'Tidak bisa mengubah status akun sendiri.';
            Response::redirect('/admin/users');
        }
        User::update((int)$id, ['is_active' => $user['is_active'] ? 0 : 1]);
        $_SESSION['success'] = 'Status pengguna berhasil diubah.';
        Response::redirect('/admin/users');
    }

    public function bookingUpdateStatus(string $id): void
    {
        $booking = Booking::findById((int)$id);
        if (!$booking) Response::redirect('/admin/bookings');

        $allowed = ['pending', 'paid', 'cancelled', 'completed'];
        $status  = Request::post('status', '');
        if (!in_array($status, $allowed)) {
            $_SESSION['error'] = 'Status tidak valid.';
            Response::redirect('/admin/bookings/' . $id);
        }

        Booking::updateStatus((int)$id, $status);

        // Jika dibatalkan & sebelumnya pending/paid → kembalikan kursi
        if ($status === 'cancelled' && in_array($booking['status'], ['pending', 'paid'])) {
            \App\Models\Schedule::incrementSeats((int)$booking['schedule_id'], (int)$booking['passenger_count']);
        }

        $_SESSION['success'] = 'Status pemesanan berhasil diubah menjadi ' . $status . '.';
        Response::redirect('/admin/bookings/' . $id);
    }

    public function userToggleRole(string $id): void
    {
        $user = User::findById((int)$id);
        if (!$user) Response::redirect('/admin/users');
        if ((int)$id === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['error'] = 'Tidak bisa mengubah role akun sendiri.';
            Response::redirect('/admin/users');
        }
        $newRole = $user['role'] === 'admin' ? 'user' : 'admin';
        User::update((int)$id, ['role' => $newRole]);
        $_SESSION['success'] = 'Role pengguna berhasil diubah menjadi ' . $newRole . '.';
        Response::redirect('/admin/users');
    }
}