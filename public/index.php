<?php
declare(strict_types=1);

// ── Bootstrap ────────────────────────────────────────────────────────────────
// BASE_PATH dan SUBFOLDER bisa sudah di-define dari root index.php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// SUBFOLDER: deteksi otomatis dari DOCUMENT_ROOT
// Jika sudah di-define dari root index.php, gunakan nilai tersebut
if (!defined('SUBFOLDER')) {
    $_dr  = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $_ap  = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');
    define('SUBFOLDER', str_replace($_dr, '', $_ap) ?: '');
}

// Load config (hanya jika belum di-load)
if (!defined('APP_CONFIG_LOADED')) {
    define('APP_CONFIG_LOADED', true);
    require BASE_PATH . '/config/app.php';
    date_default_timezone_set('Asia/Jakarta');
    $appConfig = require BASE_PATH . '/config/app.php';
    if ($appConfig['debug'] ?? false) {
        ini_set('display_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        error_reporting(0);
    }

    // Autoloader PSR-4
    spl_autoload_register(function (string $class): void {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) return;
        $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
        $file = BASE_PATH . '/app/' . $relative . '.php';
        if (file_exists($file)) require $file;
    });

    // Session
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

// ── Router ───────────────────────────────────────────────────────────────────
use App\Core\Router;
use App\Controllers\{AdminController, AuthController, BookingController, PaymentController, ReportController, ExpenseController};
use App\Middleware\{AdminMiddleware, AuthMiddleware};

$router = new Router();

// ── Auth Routes ──────────────────────────────────────────────────────────────
$router->get('/login',    [AuthController::class, 'loginForm']);
$router->post('/login',   [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register',[AuthController::class, 'register']);
$router->get('/logout',   [AuthController::class, 'logout']);

// ── Booking (Public) ─────────────────────────────────────────────────────────
$router->get('/',                     [BookingController::class, 'index']);
$router->get('/search',               [BookingController::class, 'search']);
$router->get('/seat/{scheduleId}',    [BookingController::class, 'selectSeat'],  [AuthMiddleware::class]);
$router->get('/api/seats/{scheduleId}',[BookingController::class, 'seatStatus'], [AuthMiddleware::class]);
$router->post('/api/seat/lock',       [BookingController::class, 'lockSeat'],    [AuthMiddleware::class]);
$router->post('/booking/passenger',   [BookingController::class, 'passengerForm'],[AuthMiddleware::class]);
$router->post('/booking/confirm',     [BookingController::class, 'confirm'],     [AuthMiddleware::class]);
$router->get('/booking/{id}/payment', [BookingController::class, 'payment'],     [AuthMiddleware::class]);
$router->get('/booking/{id}/success', [BookingController::class, 'success'],     [AuthMiddleware::class]);
$router->get('/my-bookings',          [BookingController::class, 'myBookings'],  [AuthMiddleware::class]);
$router->get('/booking/{id}',         [BookingController::class, 'detail'],      [AuthMiddleware::class]);
$router->get('/api/route-info',       [BookingController::class, 'routeInfo']);

// ── Payment ──────────────────────────────────────────────────────────────────
$router->post('/payment/initiate',    [PaymentController::class, 'initiate'],    [AuthMiddleware::class]);
$router->post('/payment/callback',    [PaymentController::class, 'callback']);
$router->post('/payment/webhook',     [PaymentController::class, 'callback']); // alias
$router->get('/payment/finish',       [PaymentController::class, 'finish']);
$router->get('/payment/unfinish',     [PaymentController::class, 'unfinish']);
$router->get('/payment/error',        [PaymentController::class, 'error']);

// ── Admin Routes ─────────────────────────────────────────────────────────────
$adm = [AdminMiddleware::class];

$router->get('/admin',                      [AdminController::class, 'dashboard'],    $adm);

// Kendaraan
$router->get('/admin/vehicles',             [AdminController::class, 'vehicles'],     $adm);
$router->get('/admin/vehicles/create',      [AdminController::class, 'vehicleCreate'],$adm);
$router->post('/admin/vehicles/create',     [AdminController::class, 'vehicleCreate'],$adm);
$router->get('/admin/vehicles/{id}',        [AdminController::class, 'vehicleDetail'], $adm);
$router->get('/admin/vehicles/{id}/edit',   [AdminController::class, 'vehicleEdit'],  $adm);
$router->post('/admin/vehicles/{id}/edit',  [AdminController::class, 'vehicleEdit'],  $adm);
$router->post('/admin/vehicles/{id}/delete',[AdminController::class, 'vehicleDelete'],$adm);

// Rute
$router->get('/admin/routes',               [AdminController::class, 'routes'],       $adm);
$router->get('/admin/routes/create',        [AdminController::class, 'routeCreate'],  $adm);
$router->post('/admin/routes/create',       [AdminController::class, 'routeCreate'],  $adm);
$router->get('/admin/routes/{id}/edit',     [AdminController::class, 'routeEdit'],    $adm);
$router->post('/admin/routes/{id}/edit',    [AdminController::class, 'routeEdit'],    $adm);
$router->post('/admin/routes/{id}/delete',  [AdminController::class, 'routeDelete'],  $adm);
$router->post('/admin/routes/{id}/toggle',  [AdminController::class, 'routeToggle'],  $adm);

// Jadwal
$router->get('/admin/schedules',            [AdminController::class, 'schedules'],    $adm);
$router->get('/admin/schedules/create',     [AdminController::class, 'scheduleCreate'],$adm);
$router->post('/admin/schedules/create',    [AdminController::class, 'scheduleCreate'],$adm);
$router->get('/admin/schedules/{id}/edit',  [AdminController::class, 'scheduleEdit'], $adm);
$router->post('/admin/schedules/{id}/edit', [AdminController::class, 'scheduleEdit'], $adm);
$router->post('/admin/schedules/{id}/delete',[AdminController::class,'scheduleDelete'],$adm);

// Pemesanan
$router->get('/admin/bookings',             [AdminController::class, 'bookings'],     $adm);
$router->get('/admin/bookings/{id}',        [AdminController::class, 'bookingDetail'],$adm);
$router->post('/admin/bookings/{id}/status',[AdminController::class, 'bookingUpdateStatus'],$adm);

// Pengguna
$router->get('/admin/users',                    [AdminController::class, 'users'],         $adm);
$router->post('/admin/users/{id}/toggle-active',[AdminController::class, 'userToggleActive'],$adm);
$router->post('/admin/users/{id}/toggle-role',  [AdminController::class, 'userToggleRole'], $adm);

// Laporan
$router->get('/admin/reports',                  [ReportController::class, 'index'],        $adm);
$router->get('/admin/reports/pdf/revenue',      [ReportController::class, 'exportRevenuePdf'],$adm);
$router->get('/admin/reports/pdf/booking',      [ReportController::class, 'exportBookingPdf'],$adm);
$router->get('/admin/reports/pdf/route',        [ReportController::class, 'exportRoutePdf'],  $adm);
$router->get('/admin/reports/pdf/vehicle',      [ReportController::class, 'exportVehiclePdf'],$adm);

// ── Pengeluaran Operasional ───────────────────────────────────────────────────
// Dashboard
$router->get('/admin/expenses',                          [ExpenseController::class, 'dashboard'],     $adm);

// Supir
$router->get('/admin/expenses/drivers',                  [ExpenseController::class, 'drivers'],       $adm);
$router->get('/admin/expenses/drivers/create',           [ExpenseController::class, 'driverCreate'],  $adm);
$router->post('/admin/expenses/drivers/create',          [ExpenseController::class, 'driverCreate'],  $adm);
$router->get('/admin/expenses/drivers/{id}/edit',        [ExpenseController::class, 'driverEdit'],    $adm);
$router->post('/admin/expenses/drivers/{id}/edit',       [ExpenseController::class, 'driverEdit'],    $adm);
$router->post('/admin/expenses/drivers/{id}/delete',     [ExpenseController::class, 'driverDelete'],  $adm);

// Bensin
$router->get('/admin/expenses/fuel',                     [ExpenseController::class, 'fuel'],          $adm);
$router->get('/admin/expenses/fuel/create',              [ExpenseController::class, 'fuelCreate'],    $adm);
$router->post('/admin/expenses/fuel/create',             [ExpenseController::class, 'fuelCreate'],    $adm);
$router->get('/admin/expenses/fuel/{id}/edit',           [ExpenseController::class, 'fuelEdit'],      $adm);
$router->post('/admin/expenses/fuel/{id}/edit',          [ExpenseController::class, 'fuelEdit'],      $adm);
$router->post('/admin/expenses/fuel/{id}/delete',        [ExpenseController::class, 'fuelDelete'],    $adm);

// Servis
$router->get('/admin/expenses/service',                  [ExpenseController::class, 'service'],       $adm);
$router->get('/admin/expenses/service/create',           [ExpenseController::class, 'serviceCreate'], $adm);
$router->post('/admin/expenses/service/create',          [ExpenseController::class, 'serviceCreate'], $adm);
$router->get('/admin/expenses/service/{id}/edit',        [ExpenseController::class, 'serviceEdit'],   $adm);
$router->post('/admin/expenses/service/{id}/edit',       [ExpenseController::class, 'serviceEdit'],   $adm);
$router->post('/admin/expenses/service/{id}/delete',     [ExpenseController::class, 'serviceDelete'], $adm);

// Gaji Supir
$router->get('/admin/expenses/salaries',                 [ExpenseController::class, 'salaries'],      $adm);
$router->get('/admin/expenses/salaries/create',          [ExpenseController::class, 'salaryCreate'],  $adm);
$router->post('/admin/expenses/salaries/create',         [ExpenseController::class, 'salaryCreate'],  $adm);
$router->get('/admin/expenses/salaries/{id}/edit',       [ExpenseController::class, 'salaryEdit'],    $adm);
$router->post('/admin/expenses/salaries/{id}/edit',      [ExpenseController::class, 'salaryEdit'],    $adm);
$router->post('/admin/expenses/salaries/{id}/pay',       [ExpenseController::class, 'salaryPay'],     $adm);
$router->post('/admin/expenses/salaries/{id}/delete',    [ExpenseController::class, 'salaryDelete'],  $adm);

// Laporan PDF Pengeluaran
$router->get('/admin/expenses/report/fuel',              [ExpenseController::class, 'reportFuel'],    $adm);
$router->get('/admin/expenses/report/service',           [ExpenseController::class, 'reportService'], $adm);
$router->get('/admin/expenses/report/salary',            [ExpenseController::class, 'reportSalary'],  $adm);
$router->get('/admin/expenses/report/summary',           [ExpenseController::class, 'reportSummary'], $adm);

// ── Dispatch ─────────────────────────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'];
$uri    = $_SERVER['REQUEST_URI'];

// Strip query string dulu
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Strip subfolder prefix dari URI agar routing bekerja

$subfolder = defined('SUBFOLDER') ? SUBFOLDER : '';
if ($subfolder !== '' && str_starts_with($uri, $subfolder)) {
    $uri = substr($uri, strlen($subfolder));
}

// Pastikan URI dimulai dengan /
if ($uri === '' || $uri === false) {
    $uri = '/';
} elseif ($uri[0] !== '/') {
    $uri = '/' . $uri;
}

$router->dispatch($method, $uri);
