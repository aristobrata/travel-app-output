<?php
namespace App\Controllers;

use App\Core\{Database, Request, Response};
use App\Models\{Driver, DriverSalary, FuelExpense, ServiceRecord, Vehicle, Schedule};

class ExpenseController
{
    // ─────────────────────────────────────────────────────────────────────────
    // DASHBOARD PENGELUARAN
    // ─────────────────────────────────────────────────────────────────────────
    public function dashboard(): void
    {
        $year  = (int)Request::get('year', date('Y'));
        $month = (int)Request::get('month', date('n'));

        $fuelMonthly    = FuelExpense::monthlyStats($year);
        $serviceMonthly = ServiceRecord::monthlyStats($year);
        $salaryMonthly  = DriverSalary::yearlyStats($year);

        $totalFuel    = FuelExpense::totalCost(['from' => "$year-$month-01", 'to' => date("$year-$month-t")]);
        $totalService = ServiceRecord::totalCost(['from' => "$year-$month-01", 'to' => date("$year-$month-t")]);
        $totalSalary  = DriverSalary::totalPaid(['period_year' => $year, 'period_month' => $month]);

        $serviceAlerts = ServiceRecord::scheduledSoon(14);
        $licenseAlerts = Driver::licenseSoonExpiring(30);

        require BASE_PATH . '/views/admin/expenses/dashboard.php';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPIR (DRIVERS)
    // ─────────────────────────────────────────────────────────────────────────
    public function drivers(): void
    {
        $drivers = Driver::all(200, 0);
        require BASE_PATH . '/views/admin/expenses/drivers.php';
    }

    public function driverCreate(): void
    {
        if (Request::isPost()) {
            $errors = Request::validate(['name' => 'required']);
            if (!$errors) {
                Driver::create([
                    'name'        => Request::sanitize('name'),
                    'nik'         => Request::sanitize('nik'),
                    'phone'       => Request::sanitize('phone'),
                    'address'     => Request::sanitize('address'),
                    'license_no'  => Request::sanitize('license_no'),
                    'license_exp' => Request::post('license_exp') ?: null,
                    'base_salary' => (int)str_replace(['.', ','], '', Request::post('base_salary', '0')),
                    'joined_at'   => Request::post('joined_at') ?: null,
                    'status'      => 'active',
                ]);
                $_SESSION['success'] = 'Data supir berhasil ditambahkan.';
                Response::redirect('/admin/expenses/drivers');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/expenses/driver-form.php';
    }

    public function driverEdit(string $id): void
    {
        $driver = Driver::findById((int)$id);
        if (!$driver) Response::redirect('/admin/expenses/drivers');

        if (Request::isPost()) {
            Driver::update((int)$id, [
                'name'        => Request::sanitize('name'),
                'nik'         => Request::sanitize('nik'),
                'phone'       => Request::sanitize('phone'),
                'address'     => Request::sanitize('address'),
                'license_no'  => Request::sanitize('license_no'),
                'license_exp' => Request::post('license_exp') ?: null,
                'base_salary' => (int)str_replace(['.', ','], '', Request::post('base_salary', '0')),
                'joined_at'   => Request::post('joined_at') ?: null,
                'status'      => Request::sanitize('status'),
            ]);
            $_SESSION['success'] = 'Data supir berhasil diperbarui.';
            Response::redirect('/admin/expenses/drivers');
        }
        require BASE_PATH . '/views/admin/expenses/driver-form.php';
    }

    public function driverDelete(string $id): void
    {
        Driver::delete((int)$id);
        $_SESSION['success'] = 'Supir dinonaktifkan.';
        Response::redirect('/admin/expenses/drivers');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BENSIN PER TRIP
    // ─────────────────────────────────────────────────────────────────────────
    public function fuel(): void
    {
        $filters = [
            'vehicle_id' => Request::get('vehicle_id'),
            'driver_id'  => Request::get('driver_id'),
            'from'       => Request::get('from', date('Y-m-01')),
            'to'         => Request::get('to',   date('Y-m-d')),
        ];
        $expenses = FuelExpense::all(500, 0, $filters);
        $total    = FuelExpense::totalCost($filters);
        $vehicles = Vehicle::all('active');
        $drivers  = Driver::all(200, 0, 'active');
        require BASE_PATH . '/views/admin/expenses/fuel.php';
    }

    public function fuelCreate(): void
    {
        $vehicles = Vehicle::all('active');
        $drivers  = Driver::all(200, 0, 'active');

        if (Request::isPost()) {
            $errors = Request::validate([
                'vehicle_id' => 'required',
                'trip_date'  => 'required',
                'fuel_price' => 'required|numeric',
            ]);
            if (!$errors) {
                FuelExpense::create([
                    'vehicle_id'   => (int)Request::post('vehicle_id'),
                    'driver_id'    => Request::post('driver_id') ?: null,
                    'trip_date'    => Request::post('trip_date'),
                    'origin'       => Request::sanitize('origin'),
                    'destination'  => Request::sanitize('destination'),
                    'fuel_liters'  => Request::post('fuel_liters') ?: null,
                    'fuel_price'   => (int)str_replace(['.', ','], '', Request::post('fuel_price', '0')),
                    'odometer_km'  => Request::post('odometer_km') ?: null,
                    'notes'        => Request::sanitize('notes'),
                    'created_by'   => $_SESSION['user_id'] ?? null,
                ]);
                $_SESSION['success'] = 'Data bensin berhasil ditambahkan.';
                Response::redirect('/admin/expenses/fuel');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/expenses/fuel-form.php';
    }

    public function fuelEdit(string $id): void
    {
        $expense  = FuelExpense::findById((int)$id);
        if (!$expense) Response::redirect('/admin/expenses/fuel');
        $vehicles = Vehicle::all('active');
        $drivers  = Driver::all(200, 0, 'active');

        if (Request::isPost()) {
            FuelExpense::update((int)$id, [
                'vehicle_id'  => (int)Request::post('vehicle_id'),
                'driver_id'   => Request::post('driver_id') ?: null,
                'trip_date'   => Request::post('trip_date'),
                'origin'      => Request::sanitize('origin'),
                'destination' => Request::sanitize('destination'),
                'fuel_liters' => Request::post('fuel_liters') ?: null,
                'fuel_price'  => (int)str_replace(['.', ','], '', Request::post('fuel_price', '0')),
                'odometer_km' => Request::post('odometer_km') ?: null,
                'notes'       => Request::sanitize('notes'),
            ]);
            $_SESSION['success'] = 'Data bensin berhasil diperbarui.';
            Response::redirect('/admin/expenses/fuel');
        }
        require BASE_PATH . '/views/admin/expenses/fuel-form.php';
    }

    public function fuelDelete(string $id): void
    {
        FuelExpense::delete((int)$id);
        $_SESSION['success'] = 'Data bensin dihapus.';
        Response::redirect('/admin/expenses/fuel');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SERVIS KENDARAAN
    // ─────────────────────────────────────────────────────────────────────────
    public function service(): void
    {
        $filters = [
            'vehicle_id'   => Request::get('vehicle_id'),
            'service_type' => Request::get('service_type'),
            'status'       => Request::get('status'),
            'from'         => Request::get('from', date('Y-01-01')),
            'to'           => Request::get('to',   date('Y-m-d')),
        ];
        $records  = ServiceRecord::all(500, 0, $filters);
        $total    = ServiceRecord::totalCost($filters);
        $vehicles = Vehicle::all('active');
        $alerts   = ServiceRecord::scheduledSoon(14);
        require BASE_PATH . '/views/admin/expenses/service.php';
    }

    public function serviceCreate(): void
    {
        $vehicles = Vehicle::all('active');

        if (Request::isPost()) {
            $errors = Request::validate([
                'vehicle_id'   => 'required',
                'service_date' => 'required',
                'cost'         => 'required|numeric',
            ]);
            if (!$errors) {
                ServiceRecord::create([
                    'vehicle_id'        => (int)Request::post('vehicle_id'),
                    'service_date'      => Request::post('service_date'),
                    'service_type'      => Request::sanitize('service_type'),
                    'description'       => Request::sanitize('description'),
                    'workshop'          => Request::sanitize('workshop'),
                    'cost'              => (int)str_replace(['.', ','], '', Request::post('cost', '0')),
                    'odometer_km'       => Request::post('odometer_km') ?: null,
                    'next_service_km'   => Request::post('next_service_km') ?: null,
                    'next_service_date' => Request::post('next_service_date') ?: null,
                    'status'            => Request::sanitize('status') ?: 'selesai',
                    'created_by'        => $_SESSION['user_id'] ?? null,
                ]);
                $_SESSION['success'] = 'Data servis berhasil ditambahkan.';
                Response::redirect('/admin/expenses/service');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/expenses/service-form.php';
    }

    public function serviceEdit(string $id): void
    {
        $record   = ServiceRecord::findById((int)$id);
        if (!$record) Response::redirect('/admin/expenses/service');
        $vehicles = Vehicle::all('active');

        if (Request::isPost()) {
            ServiceRecord::update((int)$id, [
                'vehicle_id'        => (int)Request::post('vehicle_id'),
                'service_date'      => Request::post('service_date'),
                'service_type'      => Request::sanitize('service_type'),
                'description'       => Request::sanitize('description'),
                'workshop'          => Request::sanitize('workshop'),
                'cost'              => (int)str_replace(['.', ','], '', Request::post('cost', '0')),
                'odometer_km'       => Request::post('odometer_km') ?: null,
                'next_service_km'   => Request::post('next_service_km') ?: null,
                'next_service_date' => Request::post('next_service_date') ?: null,
                'status'            => Request::sanitize('status') ?: 'selesai',
            ]);
            $_SESSION['success'] = 'Data servis berhasil diperbarui.';
            Response::redirect('/admin/expenses/service');
        }
        require BASE_PATH . '/views/admin/expenses/service-form.php';
    }

    public function serviceDelete(string $id): void
    {
        ServiceRecord::delete((int)$id);
        $_SESSION['success'] = 'Data servis dihapus.';
        Response::redirect('/admin/expenses/service');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GAJI SUPIR
    // ─────────────────────────────────────────────────────────────────────────
    public function salaries(): void
    {
        $filters = [
            'driver_id'    => Request::get('driver_id'),
            'status'       => Request::get('status'),
            'period_year'  => (int)Request::get('year',  date('Y')),
            'period_month' => (int)Request::get('month', 0),
        ];
        if (!$filters['period_month']) unset($filters['period_month']);

        $salaries = DriverSalary::all(500, 0, $filters);
        $drivers  = Driver::all(200, 0, 'active');
        $totalNet = array_sum(array_column($salaries, 'net_salary'));
        require BASE_PATH . '/views/admin/expenses/salaries.php';
    }

    public function salaryCreate(): void
    {
        $drivers = Driver::all(200, 0, 'active');

        if (Request::isPost()) {
            $errors = Request::validate([
                'driver_id'    => 'required',
                'period_month' => 'required',
                'period_year'  => 'required',
            ]);
            if (!$errors) {
                $driverId = (int)Request::post('driver_id');
                $month    = (int)Request::post('period_month');
                $year     = (int)Request::post('period_year');

                // cek duplikat
                if (DriverSalary::findByDriverPeriod($driverId, $month, $year)) {
                    $_SESSION['error'] = 'Data gaji untuk supir & periode ini sudah ada.';
                    require BASE_PATH . '/views/admin/expenses/salary-form.php';
                    return;
                }

                $tripCount = DriverSalary::countTripsByDriverMonth($driverId, $month, $year);

                DriverSalary::create([
                    'driver_id'    => $driverId,
                    'period_month' => $month,
                    'period_year'  => $year,
                    'base_salary'  => (int)str_replace(['.', ','], '', Request::post('base_salary', '0')),
                    'bonus'        => (int)str_replace(['.', ','], '', Request::post('bonus', '0')),
                    'deduction'    => (int)str_replace(['.', ','], '', Request::post('deduction', '0')),
                    'trip_count'   => $tripCount,
                    'paid_at'      => Request::post('paid_at') ?: null,
                    'status'       => Request::post('paid_at') ? 'paid' : 'draft',
                    'notes'        => Request::sanitize('notes'),
                    'created_by'   => $_SESSION['user_id'] ?? null,
                ]);
                $_SESSION['success'] = 'Data gaji berhasil disimpan.';
                Response::redirect('/admin/expenses/salaries');
            }
            $_SESSION['errors'] = $errors;
        }
        require BASE_PATH . '/views/admin/expenses/salary-form.php';
    }

    public function salaryEdit(string $id): void
    {
        $salary  = DriverSalary::findById((int)$id);
        if (!$salary) Response::redirect('/admin/expenses/salaries');
        $drivers = Driver::all(200, 0, 'active');

        if (Request::isPost()) {
            DriverSalary::update((int)$id, [
                'driver_id'    => (int)Request::post('driver_id'),
                'period_month' => (int)Request::post('period_month'),
                'period_year'  => (int)Request::post('period_year'),
                'base_salary'  => (int)str_replace(['.', ','], '', Request::post('base_salary', '0')),
                'bonus'        => (int)str_replace(['.', ','], '', Request::post('bonus', '0')),
                'deduction'    => (int)str_replace(['.', ','], '', Request::post('deduction', '0')),
                'trip_count'   => (int)Request::post('trip_count', 0),
                'paid_at'      => Request::post('paid_at') ?: null,
                'status'       => Request::post('status', 'draft'),
                'notes'        => Request::sanitize('notes'),
            ]);
            $_SESSION['success'] = 'Data gaji berhasil diperbarui.';
            Response::redirect('/admin/expenses/salaries');
        }
        require BASE_PATH . '/views/admin/expenses/salary-form.php';
    }

    public function salaryPay(string $id): void
    {
        DriverSalary::markPaid((int)$id);
        $_SESSION['success'] = 'Gaji ditandai lunas.';
        Response::redirect('/admin/expenses/salaries');
    }

    public function salaryDelete(string $id): void
    {
        DriverSalary::delete((int)$id);
        $_SESSION['success'] = 'Data gaji dihapus.';
        Response::redirect('/admin/expenses/salaries');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // LAPORAN PENGELUARAN (PDF/Print)
    // ─────────────────────────────────────────────────────────────────────────
    public function reportFuel(): void
    {
        $from = Request::get('from', date('Y-01-01'));
        $to   = Request::get('to',   date('Y-m-d'));
        $filters  = ['from' => $from, 'to' => $to];
        $expenses = FuelExpense::all(1000, 0, $filters);
        $total    = FuelExpense::totalCost($filters);
        require BASE_PATH . '/views/admin/expenses/pdf/fuel-pdf.php';
    }

    public function reportService(): void
    {
        $from = Request::get('from', date('Y-01-01'));
        $to   = Request::get('to',   date('Y-m-d'));
        $filters = ['from' => $from, 'to' => $to];
        $records  = ServiceRecord::all(1000, 0, $filters);
        $total    = ServiceRecord::totalCost($filters);
        $typeStats = ServiceRecord::typeStats($filters);
        require BASE_PATH . '/views/admin/expenses/pdf/service-pdf.php';
    }

    public function reportSalary(): void
    {
        $year  = (int)Request::get('year',  date('Y'));
        $month = (int)Request::get('month', date('n'));
        $filters  = ['period_year' => $year, 'period_month' => $month];
        $salaries = DriverSalary::all(1000, 0, $filters);
        $total    = array_sum(array_column($salaries, 'net_salary'));
        require BASE_PATH . '/views/admin/expenses/pdf/salary-pdf.php';
    }

    public function reportSummary(): void
    {
        $from = Request::get('from', date('Y-01-01'));
        $to   = Request::get('to',   date('Y-m-d'));
        $year = (int)date('Y', strtotime($from));

        $fuelTotal    = FuelExpense::totalCost(['from' => $from, 'to' => $to]);
        $serviceTotal = ServiceRecord::totalCost(['from' => $from, 'to' => $to]);
        $salaryTotal  = DriverSalary::totalPaid(['period_year' => $year]);
        $grandTotal   = $fuelTotal + $serviceTotal + $salaryTotal;

        $fuelList     = FuelExpense::all(1000, 0, ['from' => $from, 'to' => $to]);
        $serviceList  = ServiceRecord::all(1000, 0, ['from' => $from, 'to' => $to]);
        $salaryList   = DriverSalary::all(1000, 0, ['period_year' => $year]);
        require BASE_PATH . '/views/admin/expenses/pdf/summary-pdf.php';
    }
}
