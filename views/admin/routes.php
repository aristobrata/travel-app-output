<?php
$pageTitle = 'Rute';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Rute
    </div>
    <h1>Manajemen Rute</h1>
    <p>Kelola rute perjalanan yang tersedia.</p>
  </div>
  <a href="<?= adminUrl('/admin/routes/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Rute
  </a>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-map-signs"></i> Daftar Rute</span>
    <span style="font-size:.82rem;color:var(--gray-400)"><?= count($routes) ?> rute</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($routes)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-map-signs"></i></div>
        <h3>Belum ada rute</h3>
        <p>Mulai tambahkan rute perjalanan.</p>
        <a href="<?= adminUrl('/admin/routes/create') ?>" class="btn btn-primary" style="margin-top:14px">
          <i class="fa-solid fa-plus"></i> Tambah Rute
        </a>
      </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Asal → Tujuan</th>
          <th>Jarak</th>
          <th>Estimasi</th>
          <th>Harga Dasar</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($routes as $r): ?>
        <tr>
          <td style="color:var(--gray-400);font-size:.78rem"><?= $r['id'] ?></td>
          <td>
            <div style="font-weight:600;display:flex;align-items:center;gap:6px">
              <span><?= htmlspecialchars($r['origin']) ?></span>
              <i class="fa-solid fa-arrow-right" style="color:var(--amber);font-size:.7rem"></i>
              <span><?= htmlspecialchars($r['destination']) ?></span>
            </div>
          </td>
          <td><?= $r['distance_km'] > 0 ? $r['distance_km'] . ' km' : '—' ?></td>
          <td>
            <?php
              $min = (int)$r['duration_min'];
              if ($min > 0) {
                $h = intdiv($min, 60); $m = $min % 60;
                echo $h > 0 ? "{$h}j " : '';
                echo $m > 0 ? "{$m}m" : '';
              } else echo '—';
            ?>
          </td>
          <td style="font-weight:600">Rp <?= number_format($r['base_price'], 0, ',', '.') ?></td>
          <td>
            <span class="badge badge-<?= $r['is_active'] ? 'active' : 'inactive' ?>">
              <?= $r['is_active'] ? 'Aktif' : 'Nonaktif' ?>
            </span>
          </td>
          <td>
            <div class="actions">
              <a href="<?= adminUrl('/admin/routes/' . $r['id'] . '/edit') ?>" class="btn btn-outline btn-xs">
                <i class="fa-solid fa-pencil"></i>
              </a>
              <form method="POST" action="<?= adminUrl('/admin/routes/' . $r['id'] . '/toggle') ?>" style="display:inline">
                <?php if (!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-outline' : 'btn-success' ?> btn-xs"
                  title="<?= $r['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                  <i class="fa-solid fa-<?= $r['is_active'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                </button>
              </form>
              <form method="POST" action="<?= adminUrl('/admin/routes/' . $r['id'] . '/delete') ?>"
                onsubmit="return confirm('Nonaktifkan rute ini?')" style="display:inline">
                <?php if (!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-danger btn-xs"><i class="fa-solid fa-trash"></i></button>
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
