<?php
namespace App\Controllers;

use App\Core\Request;
use App\Models\Report;

class ReportController
{
    private function dates(): array
    {
        $from = Request::get('from', date('Y-m-01'));
        $to   = Request::get('to',   date('Y-m-d'));
        // Basic validation
        if (!strtotime($from)) $from = date('Y-m-01');
        if (!strtotime($to))   $to   = date('Y-m-d');
        return [$from, $to];
    }

    public function index(): void
    {
        [$from, $to] = $this->dates();
        $totals       = Report::revenueTotals($from, $to);
        $daily        = Report::revenueSummary($from, $to);
        $byRoute      = Report::byRoute($from, $to);
        $byVehicle    = Report::byVehicle($from, $to);
        $topCustomers = Report::topCustomers($from, $to, 10);
        require BASE_PATH . '/views/admin/reports.php';
    }

    public function exportRevenuePdf(): void
    {
        [$from, $to] = $this->dates();
        $daily  = Report::revenueSummary($from, $to);
        $totals = Report::revenueTotals($from, $to);
        require BASE_PATH . '/views/admin/pdf/revenue-pdf.php';
        exit;
    }

    public function exportBookingPdf(): void
    {
        [$from, $to] = $this->dates();
        $status   = Request::get('status', '');
        $bookings = Report::bookingDetail($from, $to, $status);
        require BASE_PATH . '/views/admin/pdf/booking-pdf.php';
        exit;
    }

    public function exportRoutePdf(): void
    {
        [$from, $to] = $this->dates();
        $routes = Report::byRoute($from, $to);
        require BASE_PATH . '/views/admin/pdf/route-pdf.php';
        exit;
    }

    public function exportVehiclePdf(): void
    {
        [$from, $to] = $this->dates();
        $vehicles = Report::byVehicle($from, $to);
        require BASE_PATH . '/views/admin/pdf/vehicle-pdf.php';
        exit;
    }
}
