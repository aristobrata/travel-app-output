<?php
$isEdit = isset($route);
$pageTitle = $isEdit ? 'Edit Rute' : 'Tambah Rute';
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/routes') ?>">Rute</a>
      <span class="sep">/</span>
      <?= $isEdit ? 'Edit' : 'Tambah' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
    <p><?= $isEdit ? 'Perbarui informasi rute.' : 'Isi data rute perjalanan baru.' ?></p>
  </div>
  <a href="<?= adminUrl('/admin/routes') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div style="max-width:700px">
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-map-signs"></i> Data Rute</span>
  </div>
  <div class="card-body">
    <form method="POST" action="<?= $isEdit ? adminUrl('/admin/routes/' . $route['id'] . '/edit') : adminUrl('/admin/routes/create') ?>">
      <?php if (!empty($_SESSION['csrf_token'])): ?>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kota Asal <span class="required">*</span></label>
          <input type="text" name="origin" class="form-control <?= isset($errors['origin']) ? 'error' : '' ?>"
            placeholder="Contoh: Padang"
            value="<?= htmlspecialchars($isEdit ? $route['origin'] : ($_POST['origin'] ?? '')) ?>">
          <?php if (!empty($errors['origin'])): ?><div class="form-error"><?= $errors['origin'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label">Kota Tujuan <span class="required">*</span></label>
          <input type="text" name="destination" class="form-control <?= isset($errors['destination']) ? 'error' : '' ?>"
            placeholder="Contoh: Pekanbaru"
            value="<?= htmlspecialchars($isEdit ? $route['destination'] : ($_POST['destination'] ?? '')) ?>">
          <?php if (!empty($errors['destination'])): ?><div class="form-error"><?= $errors['destination'] ?></div><?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Jarak (km)</label>
          <input type="number" name="distance_km" step="0.01" min="0" class="form-control"
            placeholder="0"
            value="<?= htmlspecialchars($isEdit ? $route['distance_km'] : ($_POST['distance_km'] ?? '')) ?>">
          <div class="form-hint">Kosongkan jika tidak diketahui</div>
        </div>
        <div class="form-group">
          <label class="form-label">Estimasi Waktu (menit)</label>
          <input type="number" name="duration_min" min="0" class="form-control"
            placeholder="0"
            value="<?= htmlspecialchars($isEdit ? $route['duration_min'] : ($_POST['duration_min'] ?? '')) ?>">
          <div class="form-hint">Contoh: 120 = 2 jam</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Harga Dasar (Rp) <span class="required">*</span></label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:.85rem">Rp</span>
          <input type="number" name="base_price" min="0" class="form-control <?= isset($errors['base_price']) ? 'error' : '' ?>"
            style="padding-left:36px"
            placeholder="80000"
            value="<?= htmlspecialchars($isEdit ? $route['base_price'] : ($_POST['base_price'] ?? '')) ?>">
        </div>
        <?php if (!empty($errors['base_price'])): ?><div class="form-error"><?= $errors['base_price'] ?></div><?php endif; ?>
      </div>

      <?php if ($isEdit): ?>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-control">
          <option value="1" <?= ($route['is_active'] ?? 1) ? 'selected' : '' ?>>Aktif</option>
          <option value="0" <?= !($route['is_active'] ?? 1) ? 'selected' : '' ?>>Nonaktif</option>
        </select>
      </div>
      <?php endif; ?>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <a href="<?= adminUrl('/admin/routes') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-<?= $isEdit ? 'floppy-disk' : 'plus' ?>"></i>
          <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Rute' ?>
        </button>
      </div>
    </form>
  </div>
</div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
