<?php
$pageTitle = 'Servis Kendaraan';
require BASE_PATH . '/views/layouts/admin.php';
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');

$serviceTypes = [
    'oli' => 'Ganti Oli', 'tune_up' => 'Tune Up', 'ban' => 'Ganti Ban',
    'rem' => 'Servis Rem', 'ac' => 'Servis AC', 'mesin' => 'Perbaikan Mesin',
    'bodi' => 'Perbaikan Bodi', 'kaki_kaki' => 'Kaki-Kaki', 'lainnya' => 'Lainnya',
];
$statusColor = ['selesai' => 'var(--green)', 'dalam_servis' => 'var(--amber-dark)', 'dijadwalkan' => 'var(--blue)'];
$statusLabel = ['selesai' => 'Selesai', 'dalam_servis' => 'Dalam Servis', 'dijadwalkan' => 'Dijadwalkan'];
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span> Servis Kendaraan
    </div>
    <h1>Riwayat Servis Kendaraan</h1>
    <p>Pantau perawatan dan perbaikan seluruh armada.</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="<?= adminUrl('/admin/expenses/report/service') ?>" class="btn btn-outline" target="_blank">
      <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> Cetak PDF
    </a>
    <a href="<?= adminUrl('/admin/expenses/service/create') ?>" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Servis
    </a>
  </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success"><span class="alert-icon"><i class="fa-solid fa-check-circle"></i></span><?= htmlspecialchars($_SESSION['success']) ?><?php unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (!empty($alerts)): ?>
<div class="alert alert-warning" style="margin-bottom:16px">
  <span class="alert-icon"><i class="fa-solid fa-calendar-check"></i></span>
  <div>
    <strong><?= count($alerts) ?> kendaraan</strong> jadwal servis berikutnya dalam 14 hari:
    <?php foreach ($alerts as $a): ?>
      <span style="display:inline-block;margin:4px 4px 0 0;padding:2px 10px;border-radius:20px;background:var(--amber);color:var(--navy);font-size:.78rem;font-weight:600">
        <?= htmlspecialchars($a['vehicle_name']) ?> — <?= date('d/m/Y', strtotime($a['next_service_date'])) ?>
      </span>
    <?php endforeach; ?>
  </div>
</div>
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
      <div class="form-group" style="margin:0;flex:1;min-width:140px">
        <label class="form-label" style="margin-bottom:4px">Jenis Servis</label>
        <select name="service_type" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($serviceTypes as $k => $l): ?>
            <option value="<?= $k ?>" <?= ($filters['service_type'] ?? '') === $k ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:120px">
        <label class="form-label" style="margin-bottom:4px">Status</label>
        <select name="status" class="form-control">
          <option value="">Semua</option>
          <?php foreach ($statusLabel as $k => $l): ?>
            <option value="<?= $k ?>" <?= ($filters['status'] ?? '') === $k ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="<?= adminUrl('/admin/expenses/service') ?>" class="btn btn-outline">Reset</a>
    </form>
  </div>
</div>

<!-- SUMMARY -->
<div class="card" style="margin-bottom:8px;background:var(--blue-light);border:1px solid var(--blue)">
  <div class="card-body" style="padding:14px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
    <span style="font-weight:600;color:var(--blue)"><i class="fa-solid fa-wrench"></i> Total Biaya Servis</span>
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
            <th>Jenis Servis</th>
            <th>Bengkel</th>
            <th>Biaya</th>
            <th>Servis Berikutnya</th>
            <th>Status</th>
            <th style="width:100px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($records)): ?>
            <tr><td colspan="8" style="text-align:center;color:var(--gray-400);padding:32px">Belum ada data servis.</td></tr>
          <?php else: ?>
            <?php foreach ($records as $r): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($r['service_date'])) ?></td>
              <td>
                <div style="font-weight:600"><?= htmlspecialchars($r['vehicle_name']) ?></div>
                <div style="font-size:.78rem;color:var(--gray-400)"><?= htmlspecialchars($r['plate_number']) ?></div>
              </td>
              <td>
                <span style="padding:2px 10px;border-radius:20px;background:var(--blue-light);color:var(--blue);font-size:.78rem;font-weight:600">
                  <?= $serviceTypes[$r['service_type']] ?? $r['service_type'] ?>
                </span>
              </td>
              <td><?= htmlspecialchars($r['workshop'] ?? '-') ?></td>
              <td style="font-weight:600;color:var(--blue)"><?= $idr($r['cost']) ?></td>
              <td style="font-size:.82rem">
                <?php if ($r['next_service_date']): ?>
                  <?= date('d/m/Y', strtotime($r['next_service_date'])) ?>
                  <?php if ($r['next_service_km']): ?><br><?= number_format($r['next_service_km']) ?> km<?php endif; ?>
                <?php else: ?><span style="color:var(--gray-400)">-</span><?php endif; ?>
              </td>
              <td>
                <span style="padding:2px 10px;border-radius:20px;font-size:.78rem;font-weight:600;background:<?= $statusColor[$r['status']] ?? 'var(--gray-200)' ?>22;color:<?= $statusColor[$r['status']] ?? 'var(--gray-500)' ?>">
                  <?= $statusLabel[$r['status']] ?? $r['status'] ?>
                </span>
              </td>
              <td>
                <a href="<?= adminUrl('/admin/expenses/service/'.$r['id'].'/edit') ?>" class="btn btn-sm btn-outline" style="padding:4px 8px"><i class="fa-solid fa-pen"></i></a>
                <form method="POST" action="<?= adminUrl('/admin/expenses/service/'.$r['id'].'/delete') ?>" style="display:inline" onsubmit="return confirm('Hapus data servis ini?')">
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
