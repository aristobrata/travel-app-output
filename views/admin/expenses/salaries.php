<?php
$pageTitle = 'Gaji Supir';
require BASE_PATH . '/views/layouts/admin.php';
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$currentYear  = (int)($_GET['year']  ?? date('Y'));
$currentMonth = (int)($_GET['month'] ?? 0);
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span> Gaji Supir
    </div>
    <h1>Penggajian Supir</h1>
    <p>Kelola dan rekap pembayaran gaji supir setiap bulan.</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="<?= adminUrl('/admin/expenses/report/salary?year='.$currentYear.'&month='.($currentMonth ?: date('n'))) ?>" class="btn btn-outline" target="_blank">
      <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> Cetak PDF
    </a>
    <a href="<?= adminUrl('/admin/expenses/salaries/create') ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Input Gaji
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
      <div class="form-group" style="margin:0;flex:1;min-width:140px">
        <label class="form-label" style="margin-bottom:4px">Tahun</label>
        <select name="year" class="form-control">
          <?php for ($y = date('Y'); $y >= date('Y')-4; $y--): ?>
            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:140px">
        <label class="form-label" style="margin-bottom:4px">Bulan</label>
        <select name="month" class="form-control">
          <option value="0">Semua Bulan</option>
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= $months[$m] ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:150px">
        <label class="form-label" style="margin-bottom:4px">Supir</label>
        <select name="driver_id" class="form-control">
          <option value="">Semua Supir</option>
          <?php foreach ($drivers as $d): ?>
            <option value="<?= $d['id'] ?>" <?= ($_GET['driver_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:120px">
        <label class="form-label" style="margin-bottom:4px">Status</label>
        <select name="status" class="form-control">
          <option value="">Semua</option>
          <option value="draft" <?= ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
          <option value="paid"  <?= ($_GET['status'] ?? '') === 'paid'  ? 'selected' : '' ?>>Lunas</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?= adminUrl('/admin/expenses/salaries') ?>" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<!-- SUMMARY -->
<div class="card" style="margin-bottom:8px;background:var(--green-light);border:1px solid var(--green)">
  <div class="card-body" style="padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
    <span style="font-weight:600;color:var(--green)"><i class="fa-solid fa-money-bill-wave"></i> Total Gaji (<?= count($salaries) ?> data)</span>
    <span style="font-size:1.2rem;font-weight:700;color:var(--navy)"><?= $idr($totalNet) ?></span>
  </div>
</div>

<!-- TABLE -->
<div class="card">
  <div class="card-body" style="padding:0">
    <div style="overflow-x:auto">
      <table class="table">
        <thead>
          <tr>
            <th>Periode</th>
            <th>Supir</th>
            <th>Gaji Pokok</th>
            <th>Bonus</th>
            <th>Potongan</th>
            <th>Gaji Bersih</th>
            <th>Trip</th>
            <th>Tgl Bayar</th>
            <th>Status</th>
            <th style="width:120px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($salaries)): ?>
            <tr><td colspan="10" style="text-align:center;color:var(--gray-400);padding:32px">Belum ada data gaji.</td></tr>
          <?php else: ?>
            <?php foreach ($salaries as $s): ?>
            <tr>
              <td style="white-space:nowrap;font-weight:600"><?= $months[(int)$s['period_month']] ?> <?= $s['period_year'] ?></td>
              <td>
                <div style="font-weight:600"><?= htmlspecialchars($s['driver_name']) ?></div>
                <div style="font-size:.78rem;color:var(--gray-400)"><?= htmlspecialchars($s['driver_phone'] ?? '') ?></div>
              </td>
              <td><?= $idr($s['base_salary']) ?></td>
              <td style="color:var(--green)"><?= $s['bonus'] > 0 ? '+'.$idr($s['bonus']) : '-' ?></td>
              <td style="color:var(--red)"><?= $s['deduction'] > 0 ? '-'.$idr($s['deduction']) : '-' ?></td>
              <td style="font-weight:700;font-size:1rem"><?= $idr($s['net_salary']) ?></td>
              <td style="text-align:center"><?= $s['trip_count'] ?></td>
              <td><?= $s['paid_at'] ? date('d/m/Y', strtotime($s['paid_at'])) : '-' ?></td>
              <td>
                <?php if ($s['status'] === 'paid'): ?>
                  <span style="padding:3px 10px;border-radius:20px;background:var(--green-light);color:var(--green);font-size:.78rem;font-weight:600"><i class="fa-solid fa-check"></i> Lunas</span>
                <?php else: ?>
                  <span style="padding:3px 10px;border-radius:20px;background:var(--amber-light);color:var(--amber-dark);font-size:.78rem;font-weight:600">Draft</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($s['status'] !== 'paid'): ?>
                  <form method="POST" action="<?= adminUrl('/admin/expenses/salaries/'.$s['id'].'/pay') ?>" style="display:inline" onsubmit="return confirm('Tandai gaji ini sebagai lunas?')">
                    <button type="submit" class="btn btn-sm" style="padding:3px 7px;background:var(--green-light);color:var(--green);border:1px solid var(--green)" title="Tandai Lunas"><i class="fa-solid fa-check"></i></button>
                  </form>
                <?php endif; ?>
                <a href="<?= adminUrl('/admin/expenses/salaries/'.$s['id'].'/edit') ?>" class="btn btn-sm btn-outline" style="padding:3px 7px" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <form method="POST" action="<?= adminUrl('/admin/expenses/salaries/'.$s['id'].'/delete') ?>" style="display:inline" onsubmit="return confirm('Hapus data gaji ini?')">
                  <button type="submit" class="btn btn-sm" style="padding:3px 7px;background:var(--red-light);color:var(--red);border:1px solid var(--red)" title="Hapus"><i class="fa-solid fa-trash"></i></button>
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
