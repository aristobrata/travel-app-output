<?php
$pageTitle = 'Laporan';
require BASE_PATH . '/views/layouts/admin.php';

$totalPendapatan  = (float)($totals['total_pendapatan']  ?? 0);
$totalBooking     = (int)  ($totals['total_booking']     ?? 0);
$totalLunas       = (int)  ($totals['lunas']             ?? 0);
$totalPending     = (int)  ($totals['pending']           ?? 0);
$totalBatal       = (int)  ($totals['batal']             ?? 0);
$totalPelanggan   = (int)  ($totals['total_pelanggan']   ?? 0);
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Laporan
    </div>
    <h1>Laporan &amp; Analitik</h1>
    <p>Rekap data pemesanan dan pendapatan per periode.</p>
  </div>
</div>

<!-- DATE FILTER -->
<div class="card" style="margin-bottom:20px">
  <div class="card-body" style="padding:16px 24px">
    <form method="GET" action="<?= adminUrl('/admin/reports') ?>" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap">
      <div class="form-group" style="margin:0;flex:1;min-width:150px">
        <label class="form-label" style="margin-bottom:4px">Dari Tanggal</label>
        <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:150px">
        <label class="form-label" style="margin-bottom:4px">Sampai Tanggal</label>
        <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-filter"></i> Filter
      </button>
      <a href="<?= adminUrl('/admin/reports') ?>" class="btn btn-outline">Reset</a>
      <!-- Export buttons -->
      <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap">
        <a href="<?= adminUrl('/admin/reports/pdf/revenue?from=' . $from . '&to=' . $to) ?>"
          class="btn btn-outline btn-sm" target="_blank">
          <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> PDF Pendapatan
        </a>
        <a href="<?= adminUrl('/admin/reports/pdf/booking?from=' . $from . '&to=' . $to) ?>"
          class="btn btn-outline btn-sm" target="_blank">
          <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> PDF Booking
        </a>
        <a href="<?= adminUrl('/admin/reports/pdf/route?from=' . $from . '&to=' . $to) ?>"
          class="btn btn-outline btn-sm" target="_blank">
          <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> PDF Rute
        </a>
      </div>
    </form>
  </div>
</div>

<!-- SUMMARY STATS -->
<div class="stats-grid" style="margin-bottom:24px">
  <div class="stat-card">
    <div class="stat-icon orange"><i class="fa-solid fa-sack-dollar"></i></div>
    <div class="stat-info">
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-value" style="font-size:1.2rem">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></div>
      <div class="stat-sub"><?= $from ?> s/d <?= $to ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon navy"><i class="fa-solid fa-ticket"></i></div>
    <div class="stat-info">
      <div class="stat-label">Total Booking</div>
      <div class="stat-value"><?= $totalBooking ?></div>
      <div class="stat-sub">Semua status</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
    <div class="stat-info">
      <div class="stat-label">Booking Lunas</div>
      <div class="stat-value"><?= $totalLunas ?></div>
      <div class="stat-sub"><?= $totalBooking > 0 ? round($totalLunas / $totalBooking * 100) : 0 ?>% dari total</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="fa-solid fa-clock"></i></div>
    <div class="stat-info">
      <div class="stat-label">Booking Pending</div>
      <div class="stat-value"><?= $totalPending ?></div>
      <div class="stat-sub">Menunggu pembayaran</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
    <div class="stat-info">
      <div class="stat-label">Dibatalkan</div>
      <div class="stat-value"><?= $totalBatal ?></div>
      <div class="stat-sub"><?= $totalBooking > 0 ? round($totalBatal / $totalBooking * 100) : 0 ?>% dari total</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
    <div class="stat-info">
      <div class="stat-label">Pelanggan Aktif</div>
      <div class="stat-value"><?= $totalPelanggan ?></div>
      <div class="stat-sub">Unik dalam periode</div>
    </div>
  </div>
</div>

<!-- CHARTS ROW -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  <!-- DAILY REVENUE CHART -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-chart-line" style="color:var(--green)"></i> Pendapatan Harian</span>
    </div>
    <div class="card-body" style="padding:16px">
      <?php if (empty($daily)): ?>
        <div class="empty-state" style="padding:30px 0"><div class="empty-icon"><i class="fa-solid fa-chart-line"></i></div><h3>Tidak ada data</h3></div>
      <?php else:
        $maxPend = max(array_column($daily, 'pendapatan')) ?: 1;
        $chartH = 140;
      ?>
      <div style="overflow-x:auto">
        <div style="display:flex;align-items:flex-end;gap:4px;height:<?= $chartH ?>px;min-width:<?= count($daily)*30 ?>px;padding-bottom:4px">
          <?php foreach ($daily as $d):
            $h = max(4, round(($d['pendapatan'] / $maxPend) * ($chartH - 20)));
            $color = $d['pendapatan'] > 0 ? 'var(--green)' : 'var(--gray-200)';
          ?>
          <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px" title="<?= $d['tanggal'] ?>: Rp <?= number_format($d['pendapatan'],0,',','.') ?>">
            <div style="width:100%;background:<?= $color ?>;height:<?= $h ?>px;border-radius:3px 3px 0 0;min-width:6px"></div>
            <div style="font-size:.55rem;color:var(--gray-400);transform:rotate(-45deg);transform-origin:center top;white-space:nowrap;margin-top:4px">
              <?= date('d/m', strtotime($d['tanggal'])) ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- BY ROUTE -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-map-signs" style="color:var(--amber)"></i> Pendapatan per Rute</span>
    </div>
    <div class="card-body" style="padding:0">
      <?php if (empty($byRoute)): ?>
        <div class="empty-state" style="padding:30px 0"><div class="empty-icon"><i class="fa-solid fa-map-signs"></i></div><h3>Tidak ada data</h3></div>
      <?php else:
        $maxR = max(array_column($byRoute, 'pendapatan')) ?: 1;
      ?>
      <div style="padding:12px 20px;display:flex;flex-direction:column;gap:10px">
        <?php foreach (array_slice($byRoute, 0, 6) as $r):
          $pct = round(($r['pendapatan'] / $maxR) * 100);
        ?>
        <div>
          <div style="display:flex;justify-content:space-between;margin-bottom:3px;font-size:.82rem">
            <span style="font-weight:600"><?= htmlspecialchars($r['origin']) ?> → <?= htmlspecialchars($r['destination']) ?></span>
            <span style="color:var(--gray-500)">Rp <?= number_format($r['pendapatan'],0,',','.') ?></span>
          </div>
          <div style="background:var(--gray-100);border-radius:20px;height:7px">
            <div style="width:<?= $pct ?>%;background:var(--amber);height:100%;border-radius:20px"></div>
          </div>
          <div style="font-size:.72rem;color:var(--gray-400);margin-top:2px"><?= $r['booking_lunas'] ?> booking lunas · <?= $r['total_penumpang'] ?> penumpang</div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- DAILY TABLE & BY VEHICLE -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  <!-- DAILY TABLE -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-table" style="color:var(--navy)"></i> Rekap Harian</span>
    </div>
    <div class="table-wrap" style="max-height:320px;overflow-y:auto">
      <?php if (empty($daily)): ?>
        <div class="empty-state" style="padding:30px 0"><h3>Tidak ada data</h3></div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Booking</th>
            <th>Lunas</th>
            <th>Batal</th>
            <th>Pendapatan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($daily as $d): ?>
          <tr>
            <td style="font-weight:600;font-size:.82rem"><?= date('d M Y', strtotime($d['tanggal'])) ?></td>
            <td><?= $d['total_booking'] ?></td>
            <td><span style="color:var(--green);font-weight:600"><?= $d['booking_lunas'] ?></span></td>
            <td><span style="color:var(--red);font-weight:600"><?= $d['booking_batal'] ?></span></td>
            <td style="font-weight:600;white-space:nowrap">
              <?= $d['pendapatan'] > 0 ? 'Rp ' . number_format($d['pendapatan'], 0, ',', '.') : '—' ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- BY VEHICLE -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-bus" style="color:var(--blue)"></i> Performa Kendaraan</span>
    </div>
    <div class="table-wrap" style="max-height:320px;overflow-y:auto">
      <?php if (empty($byVehicle)): ?>
        <div class="empty-state" style="padding:30px 0"><h3>Tidak ada data</h3></div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Kendaraan</th>
            <th>Jadwal</th>
            <th>Booking</th>
            <th>Pendapatan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($byVehicle as $v): ?>
          <tr>
            <td>
              <div style="font-weight:600;font-size:.85rem"><?= htmlspecialchars($v['kendaraan']) ?></div>
              <div style="font-size:.72rem;color:var(--gray-400)"><?= htmlspecialchars($v['plate_number']) ?></div>
            </td>
            <td><?= $v['total_jadwal'] ?></td>
            <td><?= $v['total_booking'] ?></td>
            <td style="font-weight:600;font-size:.82rem;white-space:nowrap">
              <?= $v['pendapatan'] > 0 ? 'Rp ' . number_format($v['pendapatan'], 0, ',', '.') : '—' ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- TOP CUSTOMERS -->
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-crown" style="color:var(--amber)"></i> Top Pelanggan</span>
    <span style="font-size:.82rem;color:var(--gray-400)">Berdasarkan total belanja</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($topCustomers)): ?>
      <div class="empty-state"><div class="empty-icon"><i class="fa-solid fa-crown"></i></div><h3>Tidak ada data</h3></div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Total Booking</th>
          <th>Total Belanja</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($topCustomers as $i => $c): ?>
        <tr>
          <td>
            <?php if ($i === 0): ?>
              <span style="font-size:1.2rem">🥇</span>
            <?php elseif ($i === 1): ?>
              <span style="font-size:1.2rem">🥈</span>
            <?php elseif ($i === 2): ?>
              <span style="font-size:1.2rem">🥉</span>
            <?php else: ?>
              <span style="color:var(--gray-400);font-size:.82rem"><?= $i + 1 ?></span>
            <?php endif; ?>
          </td>
          <td style="font-weight:600"><?= htmlspecialchars($c['name']) ?></td>
          <td style="font-size:.82rem;color:var(--gray-500)"><?= htmlspecialchars($c['email']) ?></td>
          <td style="font-size:.82rem"><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
          <td style="font-weight:600;text-align:center"><?= $c['total_booking'] ?></td>
          <td style="font-weight:700;color:var(--green)">Rp <?= number_format($c['total_belanja'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
