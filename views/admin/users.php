<?php
$pageTitle = 'Pengguna';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Pengguna
    </div>
    <h1>Manajemen Pengguna</h1>
    <p>Kelola akun pengguna yang terdaftar.</p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-users"></i> Daftar Pengguna</span>
    <span style="font-size:.82rem;color:var(--gray-400)"><?= count($users) ?> pengguna</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($users)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-users"></i></div>
        <h3>Belum ada pengguna</h3>
      </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Role</th>
          <th>Status</th>
          <th>Bergabung</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u):
          $isSelf = (int)$u['id'] === (int)($_SESSION['user_id'] ?? 0);
        ?>
        <tr <?= $isSelf ? 'style="background:var(--amber-light)"' : '' ?>>
          <td style="color:var(--gray-400);font-size:.78rem"><?= $u['id'] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div style="width:32px;height:32px;border-radius:50%;background:var(--navy);color:var(--amber);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
              </div>
              <div>
                <div style="font-weight:600"><?= htmlspecialchars($u['name']) ?></div>
                <?php if ($isSelf): ?><div style="font-size:.7rem;color:var(--amber-dark)">← Akun Anda</div><?php endif; ?>
              </div>
            </div>
          </td>
          <td style="font-size:.85rem"><?= htmlspecialchars($u['email']) ?></td>
          <td style="font-size:.85rem"><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
          <td>
            <span class="badge badge-<?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
              <?= $u['role'] === 'admin' ? '👑 Admin' : '👤 User' ?>
            </span>
          </td>
          <td>
            <span class="badge badge-<?= $u['is_active'] ? 'active' : 'inactive' ?>">
              <?= $u['is_active'] ? '✅ Aktif' : '🚫 Nonaktif' ?>
            </span>
          </td>
          <td style="font-size:.78rem;color:var(--gray-500)">
            <?= date('d/m/Y', strtotime($u['created_at'])) ?>
          </td>
          <td>
            <?php if (!$isSelf): ?>
            <div class="actions">
              <form method="POST" action="<?= adminUrl('/admin/users/' . $u['id'] . '/toggle-active') ?>" style="display:inline">
                <?php if (!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline' : 'btn-success' ?> btn-xs"
                  onclick="return confirm('Ubah status akun ini?')"
                  title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                  <i class="fa-solid fa-<?= $u['is_active'] ? 'user-slash' : 'user-check' ?>"></i>
                </button>
              </form>
              <form method="POST" action="<?= adminUrl('/admin/users/' . $u['id'] . '/toggle-role') ?>" style="display:inline">
                <?php if (!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-purple btn-xs"
                  onclick="return confirm('Ubah role pengguna ini menjadi <?= $u['role'] === 'admin' ? 'user' : 'admin' ?>?')"
                  title="Ubah ke <?= $u['role'] === 'admin' ? 'User' : 'Admin' ?>">
                  <i class="fa-solid fa-user-gear"></i>
                </button>
              </form>
            </div>
            <?php else: ?>
              <span style="font-size:.75rem;color:var(--gray-300)">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
