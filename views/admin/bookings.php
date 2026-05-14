<?php
$pageTitle = 'Pemesanan';
$currentStatus = $_GET['status'] ?? '';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Pemesanan
    </div>
    <h1>Manajemen Pemesanan</h1>
    <p>Pantau dan kelola semua pemesanan tiket.</p>
  </div>
</div>

<!-- STATUS FILTER -->
<div class="filter-bar" style="margin-bottom:20px">
  <?php
    $statuses = ['' => 'Semua', 'pending' => 'Pending', 'paid' => 'Terbayar', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
    foreach ($statuses as $val => $label):
  ?>
  <a href="<?= adminUrl('/admin/bookings') . ($val ? '?status=' . $val : '') ?>"
    class="btn btn-sm <?= $currentStatus === $val ? 'btn-primary' : 'btn-outline' ?>">
    <?= $label ?>
  </a>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="fa-solid fa-ticket"></i>
      <?= $currentStatus ? ucfirst($currentStatus) : 'Semua' ?> Pemesanan
    </span>
    <span style="font-size:.82rem;color:var(--gray-400)"><?= count($bookings) ?> data</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($bookings)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-ticket"></i></div>
        <h3>Tidak ada pemesanan</h3>
        <p><?= $currentStatus ? "Tidak ada pemesanan dengan status \"$currentStatus\"." : 'Belum ada pemesanan masuk.' ?></p>
      </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Kode Booking</th>
          <th>Penumpang</th>
          <th>Rute</th>
          <th>Keberangkatan</th>
          <th>Penumpang</th>
          <th>Total</th>
          <th>Status</th>
          <th>Tanggal Pesan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td style="color:var(--gray-400);font-size:.78rem"><?= $b['id'] ?></td>
          <td>
            <code style="background:var(--gray-100);padding:2px 7px;border-radius:4px;font-size:.78rem;font-weight:600">
              <?= htmlspecialchars($b['booking_code']) ?>
            </code>
          </td>
          <td>
            <div style="font-weight:600;font-size:.875rem"><?= htmlspecialchars($b['contact_name']) ?></div>
            <div style="font-size:.75rem;color:var(--gray-400)"><?= htmlspecialchars($b['user_name'] ?? '—') ?></div>
          </td>
          <td style="font-size:.85rem">
            <?= htmlspecialchars($b['origin']) ?>
            <i class="fa-solid fa-arrow-right" style="color:var(--amber);font-size:.65rem;margin:0 3px"></i>
            <?= htmlspecialchars($b['destination']) ?>
          </td>
          <td>
            <div style="font-size:.85rem;font-weight:600"><?= date('d/m/Y', strtotime($b['depart_at'])) ?></div>
            <div style="font-size:.75rem;color:var(--amber-dark)"><?= date('H:i', strtotime($b['depart_at'])) ?> WIB</div>
          </td>
          <td style="text-align:center">
            <span style="font-weight:600"><?= $b['passenger_count'] ?></span>
            <span style="font-size:.75rem;color:var(--gray-400)"> org</span>
          </td>
          <td style="font-weight:600;white-space:nowrap">
            Rp <?= number_format($b['total_price'], 0, ',', '.') ?>
          </td>
          <td>
            <span class="badge badge-<?= $b['status'] ?>">
              <?php
                echo match($b['status']) {
                  'pending' => '⏳ Pending',
                  'paid' => '✅ Terbayar',
                  'cancelled' => '❌ Dibatalkan',
                  'completed' => '✔ Selesai',
                  default => ucfirst($b['status'])
                };
              ?>
            </span>
          </td>
          <td style="font-size:.78rem;color:var(--gray-500)">
            <?= date('d/m/Y H:i', strtotime($b['created_at'])) ?>
          </td>
          <td>
            <a href="<?= adminUrl('/admin/bookings/' . $b['id']) ?>" class="btn btn-info btn-xs">
              <i class="fa-solid fa-eye"></i> Detail
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
