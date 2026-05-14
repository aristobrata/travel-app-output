<?php
$pageTitle = 'Jadwal';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Jadwal
    </div>
    <h1>Manajemen Jadwal</h1>
    <p>Kelola jadwal keberangkatan kendaraan.</p>
  </div>
  <a href="<?= adminUrl('/admin/schedules/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Jadwal
  </a>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-regular fa-calendar"></i> Daftar Jadwal</span>
    <span style="font-size:.82rem;color:var(--gray-400)"><?= count($schedules) ?> jadwal</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($schedules)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
        <h3>Belum ada jadwal</h3>
        <p>Mulai tambahkan jadwal keberangkatan.</p>
        <a href="<?= adminUrl('/admin/schedules/create') ?>" class="btn btn-primary" style="margin-top:14px">
          <i class="fa-solid fa-plus"></i> Tambah Jadwal
        </a>
      </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Rute</th>
          <th>Kendaraan</th>
          <th>Berangkat</th>
          <th>Tiba</th>
          <th>Kursi Tersisa</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($schedules as $s): ?>
        <tr>
          <td style="color:var(--gray-400);font-size:.78rem"><?= $s['id'] ?></td>
          <td>
            <div style="font-weight:600;font-size:.875rem">
              <?= htmlspecialchars($s['origin']) ?>
              <i class="fa-solid fa-arrow-right" style="color:var(--amber);font-size:.65rem;margin:0 3px"></i>
              <?= htmlspecialchars($s['destination']) ?>
            </div>
          </td>
          <td>
            <div style="font-size:.85rem"><?= htmlspecialchars($s['vehicle_name']) ?></div>
          </td>
          <td>
            <div style="font-weight:600;font-size:.85rem"><?= date('d/m/Y', strtotime($s['depart_at'])) ?></div>
            <div style="font-size:.75rem;color:var(--amber-dark)"><?= date('H:i', strtotime($s['depart_at'])) ?> WIB</div>
          </td>
          <td>
            <div style="font-size:.85rem"><?= date('d/m/Y', strtotime($s['arrive_at'])) ?></div>
            <div style="font-size:.75rem;color:var(--gray-400)"><?= date('H:i', strtotime($s['arrive_at'])) ?> WIB</div>
          </td>
          <td>
            <?php
              $seats = (int)$s['available_seats'];
              $color = $seats === 0 ? 'var(--red)' : ($seats <= 3 ? 'var(--orange)' : 'var(--green)');
            ?>
            <span style="font-weight:700;color:<?= $color ?>"><?= $seats ?></span>
            <span style="font-size:.75rem;color:var(--gray-400)"> kursi</span>
          </td>
          <td style="font-weight:600;font-size:.85rem">
            Rp <?= number_format($s['price_override'] ?? 0, 0, ',', '.') ?: '<span style="color:var(--gray-400);font-weight:400">—</span>' ?>
          </td>
          <td>
            <?php
              $sc = $s['status'] ?? 'active';
              $badge = match($sc) {
                'active' => 'active', 'cancelled' => 'cancelled', 'completed' => 'completed', default => 'inactive'
              };
              $label = match($sc) {
                'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai', default => $sc
              };
            ?>
            <span class="badge badge-<?= $badge ?>"><?= $label ?></span>
          </td>
          <td>
            <div class="actions">
              <a href="<?= adminUrl('/admin/schedules/' . $s['id'] . '/edit') ?>" class="btn btn-outline btn-xs">
                <i class="fa-solid fa-pencil"></i>
              </a>
              <form method="POST" action="<?= adminUrl('/admin/schedules/' . $s['id'] . '/delete') ?>"
                onsubmit="return confirm('Batalkan jadwal ini?')" style="display:inline">
                <?php if (!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-danger btn-xs" title="Batalkan">
                  <i class="fa-solid fa-ban"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
