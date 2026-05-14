<?php
$pageTitle = 'Bensin Per Trip';
require BASE_PATH . '/views/layouts/admin.php';
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span> Bensin
    </div>
    <h1>Bensin Per Trip</h1>
    <p>Riwayat pengeluaran bahan bakar setiap perjalanan.</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="<?= adminUrl('/admin/expenses/report/fuel?from=' . ($filters['from'] ?? date('Y-01-01')) . '&to=' . ($filters['to'] ?? date('Y-m-d'))) ?>" class="btn btn-outline" target="_blank">
      <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> Cetak PDF
    </a>
    <a href="<?= adminUrl('/admin/expenses/fuel/create') ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Data
    </a>
  </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success"><span class="alert-icon"><i class="fa-solid fa-check-circle"></i></span><?= htmlspecialchars($_SESSION['success']) ?><?php unset($_SESSION['success']); ?></div>
<?php endif; ?>

<!-- FILTER -->
<div class="card" style="margin-bottom:20px">
  <div class="card-body" style="padding:16px 24px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div class="form-group" style="margin:0;flex:1;min-width:130px">
        <label class="form-label" style="margin-bottom:4px">Dari</label>
        <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($filters['from'] ?? '') ?>">
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:130px">
        <label class="form-label" style="margin-bottom:4px">Sampai</label>
        <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($filters['to'] ?? '') ?>">
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:150px">
        <label class="form-label" style="margin-bottom:4px">Kendaraan</label>
        <select name="vehicle_id" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($vehicles as $v): ?>
            <option value="<?= $v['id'] ?>" <?= ($filters['vehicle_id'] ?? '') == $v['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($v['name']) ?> (<?= $v['plate_number'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:150px">
        <label class="form-label" style="margin-bottom:4px">Supir</label>
        <select name="driver_id" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($drivers as $d): ?>
            <option value="<?= $d['id'] ?>" <?= ($filters['driver_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($d['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?= adminUrl('/admin/expenses/fuel') ?>" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<!-- SUMMARY -->
<div class="card" style="margin-bottom:8px;background:var(--amber-light);border:1px solid var(--amber)">
  <div class="card-body" style="padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
    <span style="font-weight:600;color:var(--amber-dark)"><i class="fa-solid fa-gas-pump"></i> Total Pengeluaran Bensin</span>
    <span style="font-size:1.2rem;font-weight:700;color:var(--navy)"><?= $idr($total) ?></span>
  </div>
</div>

<!-- TABLE -->
<div class="card">
  <div class="card-body" style="padding:0">
    <div style="overflow-x:auto">
      <table class="table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Kendaraan</th>
            <th>Supir</th>
            <th>Rute</th>
            <th>Liter</th>
            <th>Biaya BBM</th>
            <th>Odometer</th>
            <th style="width:100px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($expenses)): ?>
            <tr><td colspan="8" style="text-align:center;color:var(--gray-400);padding:32px">Belum ada data bensin.</td></tr>
          <?php else: ?>
            <?php foreach ($expenses as $e): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($e['trip_date'])) ?></td>
              <td>
                <div style="font-weight:600"><?= htmlspecialchars($e['vehicle_name'] ?? '-') ?></div>
                <div style="font-size:.78rem;color:var(--gray-400)"><?= htmlspecialchars($e['plate_number'] ?? '') ?></div>
              </td>
              <td><?= htmlspecialchars($e['driver_name'] ?? '-') ?></td>
              <td>
                <?php if ($e['origin'] || $e['destination']): ?>
                  <span style="font-size:.82rem"><?= htmlspecialchars($e['origin'] ?? '') ?> → <?= htmlspecialchars($e['destination'] ?? '') ?></span>
                <?php else: ?><span style="color:var(--gray-400)">-</span><?php endif; ?>
              </td>
              <td><?= $e['fuel_liters'] ? number_format($e['fuel_liters'], 1) . ' L' : '-' ?></td>
              <td style="font-weight:600;color:var(--amber-dark)"><?= $idr($e['fuel_price']) ?></td>
              <td><?= $e['odometer_km'] ? number_format($e['odometer_km']) . ' km' : '-' ?></td>
              <td>
                <a href="<?= adminUrl('/admin/expenses/fuel/'.$e['id'].'/edit') ?>" class="btn btn-sm btn-outline" style="padding:4px 8px"><i class="fa-solid fa-pen"></i></a>
                <form method="POST" action="<?= adminUrl('/admin/expenses/fuel/'.$e['id'].'/delete') ?>" style="display:inline" onsubmit="return confirm('Hapus data ini?')">
                  <button type="submit" class="btn btn-sm" style="padding:4px 8px;background:var(--red-light);color:var(--red);border:1px solid var(--red)"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
