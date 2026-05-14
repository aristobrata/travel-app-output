<?php
$pageTitle = 'Dashboard';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Admin</a>
      <span class="sep">/</span> Dashboard
    </div>
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></strong>! Berikut ringkasan hari ini.</p>
  </div>
  <span style="font-size:.8rem;color:var(--gray-400)">
    <i class="fa-regular fa-clock"></i> <?= date('d F Y, H:i') ?> WIB
  </span>
</div>

<!-- PERINGATAN PAJAK -->
<?php if (!empty($taxWarning)): ?>
<div class="alert alert-warning" style="margin-bottom:20px">
  <span class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
  <div>
    <strong><?= count($taxWarning) ?> kendaraan</strong> pajaknya akan jatuh tempo dalam 30 hari ke depan.
    <div style="margin-top:7px;display:flex;gap:7px;flex-wrap:wrap">
      <?php foreach ($taxWarning as $tw):
        $d = (int)ceil((strtotime($tw['tax_due_date']) - time()) / 86400);
        $c = $d <= 7 ? 'var(--red)' : 'var(--orange)';
      ?>
      <a href="<?= adminUrl('/admin/vehicles/' . $tw['id']) ?>"
         style="background:#fff;border:1.5px solid <?= $c ?>;color:<?= $c ?>;padding:3px 10px;border-radius:6px;font-size:.78rem;font-weight:700;text-decoration:none">
        <i class="fa-solid fa-bus"></i> <?= htmlspecialchars($tw['name']) ?>
        — <?= $d < 0 ? 'Kadaluarsa!' : ($d === 0 ? 'Hari ini!' : $d.' hari') ?>
      </a>
      <?php endforeach; ?>
      <a href="<?= adminUrl('/admin/vehicles') ?>" style="font-size:.78rem;color:var(--amber-dark);font-weight:600;align-self:center;text-decoration:none">
        Lihat semua →
      </a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- STAT CARDS -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon navy"><i class="fa-solid fa-users"></i></div>
    <div class="stat-info">
      <div class="stat-label">Total Pengguna</div>
      <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
      <div class="stat-sub">Terdaftar</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fa-solid fa-bus"></i></div>
    <div class="stat-info">
      <div class="stat-label">Kendaraan Aktif</div>
      <div class="stat-value"><?= number_format($stats['total_vehicles']) ?></div>
      <div class="stat-sub">Armada tersedia</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fa-regular fa-calendar-check"></i></div>
    <div class="stat-info">
      <div class="stat-label">Jadwal Aktif</div>
      <div class="stat-value"><?= number_format($stats['total_schedules']) ?></div>
      <div class="stat-sub">Perjalanan tersedia</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="fa-solid fa-clock-rotate-left"></i></div>
    <div class="stat-info">
      <div class="stat-label">Pending</div>
      <div class="stat-value"><?= number_format($stats['pending']) ?></div>
      <div class="stat-sub">Menunggu bayar</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
    <div class="stat-info">
      <div class="stat-label">Terbayar</div>
      <div class="stat-value"><?= number_format($stats['paid']) ?></div>
      <div class="stat-sub">Booking sukses</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange"><i class="fa-solid fa-sack-dollar"></i></div>
    <div class="stat-info">
      <div class="stat-label">Pendapatan Bulan Ini</div>
      <div class="stat-value" style="font-size:1.1rem">Rp <?= number_format($stats['revenue'], 0, ',', '.') ?></div>
      <div class="stat-sub"><?= date('F Y') ?></div>
    </div>
  </div>
</div>

<!-- QUICK ACTIONS + SYSTEM STATUS -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px">
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-bolt" style="color:var(--amber)"></i> Aksi Cepat</span>
    </div>
    <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <a href="<?= adminUrl('/admin/vehicles/create') ?>" class="btn btn-navy btn-sm" style="justify-content:center">
        <i class="fa-solid fa-plus"></i> Kendaraan
      </a>
      <a href="<?= adminUrl('/admin/routes/create') ?>" class="btn btn-navy btn-sm" style="justify-content:center">
        <i class="fa-solid fa-plus"></i> Rute
      </a>
      <a href="<?= adminUrl('/admin/schedules/create') ?>" class="btn btn-primary btn-sm" style="justify-content:center">
        <i class="fa-solid fa-plus"></i> Jadwal
      </a>
      <a href="<?= adminUrl('/admin/bookings?status=pending') ?>" class="btn btn-outline btn-sm" style="justify-content:center">
        <i class="fa-solid fa-clock"></i> Cek Pending
        <?php if ($stats['pending'] > 0): ?>
          <span style="background:var(--red);color:#fff;border-radius:10px;padding:0 6px;font-size:.68rem;margin-left:2px"><?= $stats['pending'] ?></span>
        <?php endif; ?>
      </a>
      <a href="<?= adminUrl('/admin/users') ?>" class="btn btn-outline btn-sm" style="justify-content:center">
        <i class="fa-solid fa-users"></i> Pengguna
      </a>
      <a href="<?= adminUrl('/admin/reports') ?>" class="btn btn-outline btn-sm" style="justify-content:center">
        <i class="fa-solid fa-chart-bar"></i> Laporan
      </a>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-circle-info" style="color:var(--blue)"></i> Info Sistem</span>
    </div>
    <div class="card-body" style="padding:16px">
      <?php $infos = [
        ['PHP Version', PHP_VERSION],
        ['Database',    'Terhubung'],
        ['Timezone',    date_default_timezone_get()],
        ['Mode',        strtoupper($_ENV['APP_ENV'] ?? 'dev')],
        ['App Name',    $_ENV['APP_NAME'] ?? 'TravelKu'],
        ['App URL',     defined('APP_URL') ? APP_URL : '-'],
      ]; ?>
      <?php foreach ($infos as [$lbl, $val]): ?>
      <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--gray-100);font-size:.82rem">
        <span style="color:var(--gray-500)"><?= $lbl ?></span>
        <span style="font-weight:600;color:var(--gray-700);max-width:200px;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($val) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- RECENT BOOKINGS -->
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-ticket" style="color:var(--amber)"></i> Pemesanan Terbaru</span>
    <a href="<?= adminUrl('/admin/bookings') ?>" class="btn btn-outline btn-sm">Lihat Semua</a>
  </div>
  <div class="table-wrap">
    <?php if (empty($recentBookings)): ?>
      <div class="empty-state"><div class="empty-icon"><i class="fa-solid fa-inbox"></i></div><h3>Belum ada pemesanan</h3></div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Penumpang</th>
          <th>Rute</th>
          <th>Berangkat</th>
          <th>Total</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentBookings as $b): ?>
        <tr>
          <td><code style="background:var(--gray-100);padding:2px 6px;border-radius:4px;font-size:.75rem"><?= htmlspecialchars($b['booking_code']) ?></code></td>
          <td>
            <div style="font-weight:600;font-size:.875rem"><?= htmlspecialchars($b['contact_name']) ?></div>
            <div style="font-size:.72rem;color:var(--gray-400)"><?= htmlspecialchars($b['user_name'] ?? '') ?></div>
          </td>
          <td style="font-size:.85rem">
            <?= htmlspecialchars($b['origin']) ?>
            <i class="fa-solid fa-arrow-right" style="color:var(--amber);font-size:.6rem;margin:0 3px"></i>
            <?= htmlspecialchars($b['destination']) ?>
          </td>
          <td>
            <div style="font-size:.82rem;font-weight:600"><?= date('d/m/Y', strtotime($b['depart_at'])) ?></div>
            <div style="font-size:.72rem;color:var(--amber-dark)"><?= date('H:i', strtotime($b['depart_at'])) ?> WIB</div>
          </td>
          <td style="font-weight:600;font-size:.85rem">Rp <?= number_format($b['total_price'], 0, ',', '.') ?></td>
          <td>
            <span class="badge badge-<?= $b['status'] ?>">
              <?= match($b['status']) {
                'pending'  =>'⏳ Pending','paid'=>'✅ Terbayar',
                'cancelled'=>'❌ Batal','completed'=>'✔ Selesai',
                default=>ucfirst($b['status'])
              } ?>
            </span>
          </td>
          <td>
            <a href="<?= adminUrl('/admin/bookings/' . $b['id']) ?>" class="btn btn-outline btn-xs">
              <i class="fa-solid fa-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
