<?php
$pageTitle = 'Pengeluaran Operasional';
require BASE_PATH . '/views/layouts/admin.php';

$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$months = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// Build monthly chart data
$fuelMap = $svcMap = $salMap = [];
for ($i = 1; $i <= 12; $i++) { $fuelMap[$i] = $svcMap[$i] = $salMap[$i] = 0; }
foreach ($fuelMonthly    as $r) $fuelMap[(int)$r['m']] = (float)$r['total'];
foreach ($serviceMonthly as $r) $svcMap[(int)$r['m']]  = (float)$r['total'];
foreach ($salaryMonthly  as $r) $salMap[(int)$r['m']]  = (float)$r['total'];
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Pengeluaran
    </div>
    <h1>Pengeluaran Operasional</h1>
    <p>Rekap bensin, servis kendaraan, dan gaji supir.</p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <form method="GET" style="display:flex;gap:8px;align-items:center">
      <select name="year" class="form-control" style="width:auto" onchange="this.form.submit()">
        <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--): ?>
          <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>
      <select name="month" class="form-control" style="width:auto" onchange="this.form.submit()">
        <?php for ($m = 1; $m <= 12; $m++): ?>
          <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>><?= $months[$m] ?></option>
        <?php endfor; ?>
      </select>
    </form>
    <a href="<?= adminUrl('/admin/expenses/report/summary') ?>" class="btn btn-outline btn-sm" target="_blank">
      <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> Laporan Ringkasan
    </a>
  </div>
</div>

<?php if (!empty($serviceAlerts)): ?>
<div class="alert alert-warning" style="margin-bottom:16px">
  <span class="alert-icon"><i class="fa-solid fa-wrench"></i></span>
  <div>
    <strong><?= count($serviceAlerts) ?> kendaraan</strong> jadwal servis berikutnya dalam 14 hari ke depan.
  </div>
</div>
<?php endif; ?>
<?php if (!empty($licenseAlerts)): ?>
<div class="alert" style="background:var(--purple-light);border-color:var(--purple);margin-bottom:16px">
  <span class="alert-icon" style="color:var(--purple)"><i class="fa-solid fa-id-card"></i></span>
  <div style="color:var(--purple)">
    <strong><?= count($licenseAlerts) ?> supir</strong> SIM-nya akan kadaluarsa dalam 30 hari.
  </div>
</div>
<?php endif; ?>

<!-- STAT CARDS -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:24px">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--amber-light);color:var(--amber-dark)"><i class="fa-solid fa-gas-pump"></i></div>
    <div class="stat-info">
      <div class="stat-label">Bensin Bulan Ini</div>
      <div class="stat-value"><?= $idr($totalFuel) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--blue-light);color:var(--blue)"><i class="fa-solid fa-wrench"></i></div>
    <div class="stat-info">
      <div class="stat-label">Servis Bulan Ini</div>
      <div class="stat-value"><?= $idr($totalService) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-light);color:var(--green)"><i class="fa-solid fa-wallet"></i></div>
    <div class="stat-info">
      <div class="stat-label">Gaji Supir Bulan Ini</div>
      <div class="stat-value"><?= $idr($totalSalary) ?></div>
    </div>
  </div>
  <div class="stat-card" style="border:2px solid var(--navy)">
    <div class="stat-icon" style="background:var(--navy);color:var(--amber)"><i class="fa-solid fa-calculator"></i></div>
    <div class="stat-info">
      <div class="stat-label">Total Pengeluaran</div>
      <div class="stat-value" style="color:var(--navy)"><?= $idr($totalFuel + $totalService + $totalSalary) ?></div>
    </div>
  </div>
</div>

<!-- CHART -->
<div class="card" style="margin-bottom:24px">
  <div class="card-header">
    <h3 class="card-title"><i class="fa-solid fa-chart-area" style="color:var(--amber)"></i> Tren Pengeluaran <?= $year ?></h3>
  </div>
  <div class="card-body">
    <canvas id="expenseChart" height="90"></canvas>
  </div>
</div>

<!-- QUICK LINKS -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-bottom:24px">
  <a href="<?= adminUrl('/admin/expenses/fuel') ?>" class="card" style="padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .2s" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow=''">
    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--amber-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="fa-solid fa-gas-pump fa-lg" style="color:var(--amber-dark)"></i>
    </div>
    <div>
      <div style="font-weight:700;color:var(--gray-800)">Bensin Per Trip</div>
      <div style="font-size:.82rem;color:var(--gray-500)">Catat dan pantau pengeluaran BBM</div>
    </div>
    <i class="fa-solid fa-chevron-right" style="color:var(--gray-300);margin-left:auto"></i>
  </a>

  <a href="<?= adminUrl('/admin/expenses/service') ?>" class="card" style="padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .2s" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow=''">
    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--blue-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="fa-solid fa-screwdriver-wrench fa-lg" style="color:var(--blue)"></i>
    </div>
    <div>
      <div style="font-weight:700;color:var(--gray-800)">Servis Kendaraan</div>
      <div style="font-size:.82rem;color:var(--gray-500)">Riwayat dan jadwal perawatan armada</div>
    </div>
    <i class="fa-solid fa-chevron-right" style="color:var(--gray-300);margin-left:auto"></i>
  </a>

  <a href="<?= adminUrl('/admin/expenses/salaries') ?>" class="card" style="padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .2s" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow=''">
    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--green-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="fa-solid fa-money-bill-wave fa-lg" style="color:var(--green)"></i>
    </div>
    <div>
      <div style="font-weight:700;color:var(--gray-800)">Gaji Supir</div>
      <div style="font-size:.82rem;color:var(--gray-500)">Penggajian bulanan dan slip gaji</div>
    </div>
    <i class="fa-solid fa-chevron-right" style="color:var(--gray-300);margin-left:auto"></i>
  </a>

  <a href="<?= adminUrl('/admin/expenses/drivers') ?>" class="card" style="padding:20px;display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .2s" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow=''">
    <div style="width:50px;height:50px;border-radius:var(--radius);background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="fa-solid fa-user-tie fa-lg" style="color:var(--purple)"></i>
    </div>
    <div>
      <div style="font-weight:700;color:var(--gray-800)">Data Supir</div>
      <div style="font-size:.82rem;color:var(--gray-500)">Kelola supir dan informasi SIM</div>
    </div>
    <i class="fa-solid fa-chevron-right" style="color:var(--gray-300);margin-left:auto"></i>
  </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const labels = <?= json_encode(array_values($months)) ?>.slice(1);
const fuel   = <?= json_encode(array_values($fuelMap)) ?>;
const svc    = <?= json_encode(array_values($svcMap)) ?>;
const sal    = <?= json_encode(array_values($salMap)) ?>;

new Chart(document.getElementById('expenseChart'), {
  type: 'bar',
  data: {
    labels,
    datasets: [
      { label: 'Bensin',  data: fuel, backgroundColor: '#F59E0B99', borderColor: '#F59E0B', borderWidth: 2 },
      { label: 'Servis',  data: svc,  backgroundColor: '#3B82F699', borderColor: '#3B82F6', borderWidth: 2 },
      { label: 'Gaji',    data: sal,  backgroundColor: '#10B98199', borderColor: '#10B981', borderWidth: 2 },
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'top' } },
    scales: {
      x: { stacked: false },
      y: {
        stacked: false,
        ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' }
      }
    }
  }
});
</script>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
