<?php
$pageTitle = 'Data Supir';
require BASE_PATH . '/views/layouts/admin.php';
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span> Data Supir
    </div>
    <h1>Manajemen Supir</h1>
    <p>Kelola data supir, SIM, dan gaji pokok.</p>
  </div>
  <a href="<?= adminUrl('/admin/expenses/drivers/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Supir
  </a>
</div>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success"><span class="alert-icon"><i class="fa-solid fa-check-circle"></i></span><?= htmlspecialchars($_SESSION['success']) ?><?php unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-body" style="padding:0">
    <div style="overflow-x:auto">
      <table class="table">
        <thead>
          <tr>
            <th>Nama Supir</th>
            <th>NIK</th>
            <th>Telepon</th>
            <th>No. SIM</th>
            <th>Kadaluarsa SIM</th>
            <th>Gaji Pokok</th>
            <th>Bergabung</th>
            <th>Status</th>
            <th style="width:100px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($drivers)): ?>
            <tr><td colspan="9" style="text-align:center;color:var(--gray-400);padding:32px">Belum ada data supir. <a href="<?= adminUrl('/admin/expenses/drivers/create') ?>">Tambah supir</a>.</td></tr>
          <?php else: ?>
            <?php foreach ($drivers as $d): ?>
            <?php
              $simExp = $d['license_exp'] ? strtotime($d['license_exp']) : null;
              $simWarn = $simExp && $simExp <= strtotime('+30 days');
            ?>
            <tr>
              <td>
                <div style="font-weight:700;color:var(--gray-800)"><?= htmlspecialchars($d['name']) ?></div>
                <?php if ($d['address']): ?>
                  <div style="font-size:.78rem;color:var(--gray-400)"><?= htmlspecialchars(mb_strimwidth($d['address'], 0, 40, '...')) ?></div>
                <?php endif; ?>
              </td>
              <td style="font-size:.82rem"><?= htmlspecialchars($d['nik'] ?? '-') ?></td>
              <td><?= htmlspecialchars($d['phone'] ?? '-') ?></td>
              <td><?= htmlspecialchars($d['license_no'] ?? '-') ?></td>
              <td>
                <?php if ($d['license_exp']): ?>
                  <span style="color:<?= $simWarn ? 'var(--red)' : 'var(--gray-700)' ?>;font-weight:<?= $simWarn ? '700' : '400' ?>">
                    <?= date('d/m/Y', strtotime($d['license_exp'])) ?>
                    <?php if ($simWarn): ?><br><small>⚠ Hampir kadaluarsa</small><?php endif; ?>
                  </span>
                <?php else: ?><span style="color:var(--gray-400)">-</span><?php endif; ?>
              </td>
              <td style="font-weight:600"><?= $idr($d['base_salary']) ?></td>
              <td><?= $d['joined_at'] ? date('d/m/Y', strtotime($d['joined_at'])) : '-' ?></td>
              <td>
                <?php if ($d['status'] === 'active'): ?>
                  <span style="padding:2px 10px;border-radius:20px;background:var(--green-light);color:var(--green);font-size:.78rem;font-weight:600">Aktif</span>
                <?php else: ?>
                  <span style="padding:2px 10px;border-radius:20px;background:var(--gray-100);color:var(--gray-500);font-size:.78rem;font-weight:600">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= adminUrl('/admin/expenses/drivers/'.$d['id'].'/edit') ?>" class="btn btn-sm btn-outline" style="padding:4px 8px"><i class="fa-solid fa-pen"></i></a>
                <form method="POST" action="<?= adminUrl('/admin/expenses/drivers/'.$d['id'].'/delete') ?>" style="display:inline" onsubmit="return confirm('Nonaktifkan supir ini?')">
                  <button type="submit" class="btn btn-sm" style="padding:4px 8px;background:var(--red-light);color:var(--red);border:1px solid var(--red)"><i class="fa-solid fa-ban"></i></button>
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
