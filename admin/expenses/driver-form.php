<?php
$pageTitle = isset($driver) ? 'Edit Data Supir' : 'Tambah Supir';
require BASE_PATH . '/views/layouts/admin.php';
$d = $driver ?? [];
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/expenses/drivers') ?>">Data Supir</a>
      <span class="sep">/</span> <?= isset($driver) ? 'Edit' : 'Tambah' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
  </div>
</div>

<?php if (!empty($_SESSION['errors'])): ?>
<div class="alert alert-danger">
  <span class="alert-icon"><i class="fa-solid fa-circle-xmark"></i></span>
  <div><?php foreach ($_SESSION['errors'] as $err): ?><div><?= htmlspecialchars($err) ?></div><?php endforeach; unset($_SESSION['errors']); ?></div>
</div>
<?php endif; ?>

<div class="card" style="max-width:680px">
  <div class="card-body">
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($d['name'] ?? '') ?>" required placeholder="cth. Budi Santoso">
        </div>

        <div class="form-group">
          <label class="form-label">NIK (KTP)</label>
          <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($d['nik'] ?? '') ?>" maxlength="20" placeholder="16 digit NIK">
        </div>

        <div class="form-group">
          <label class="form-label">No. Telepon</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($d['phone'] ?? '') ?>" placeholder="cth. 081234567890">
        </div>

        <div class="form-group">
          <label class="form-label">No. SIM</label>
          <input type="text" name="license_no" class="form-control" value="<?= htmlspecialchars($d['license_no'] ?? '') ?>" placeholder="Nomor SIM">
        </div>

        <div class="form-group">
          <label class="form-label">Kadaluarsa SIM</label>
          <input type="date" name="license_exp" class="form-control" value="<?= htmlspecialchars($d['license_exp'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Gaji Pokok (Rp)</label>
          <input type="number" name="base_salary" class="form-control" value="<?= htmlspecialchars($d['base_salary'] ?? 0) ?>" min="0" step="50000" placeholder="0">
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Bergabung</label>
          <input type="date" name="joined_at" class="form-control" value="<?= htmlspecialchars($d['joined_at'] ?? '') ?>">
        </div>

        <?php if (isset($driver)): ?>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="active"   <?= ($d['status'] ?? '') === 'active'   ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= ($d['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>
        <?php endif; ?>

        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Alamat</label>
          <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap supir"><?= htmlspecialchars($d['address'] ?? '') ?></textarea>
        </div>

      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
        <a href="<?= adminUrl('/admin/expenses/drivers') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
