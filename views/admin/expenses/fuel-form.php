<?php
$pageTitle = isset($expense) ? 'Edit Data Bensin' : 'Tambah Data Bensin';
require BASE_PATH . '/views/layouts/admin.php';
$e = $expense ?? [];
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/expenses/fuel') ?>">Bensin</a>
      <span class="sep">/</span> <?= isset($expense) ? 'Edit' : 'Tambah' ?>
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
        <div class="form-group">
          <label class="form-label">Tanggal Trip <span style="color:var(--red)">*</span></label>
          <input type="date" name="trip_date" class="form-control" value="<?= htmlspecialchars($e['trip_date'] ?? date('Y-m-d')) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Kendaraan <span style="color:var(--red)">*</span></label>
          <select name="vehicle_id" class="form-control" required>
            <option value="">-- Pilih Kendaraan --</option>
            <?php foreach ($vehicles as $v): ?>
              <option value="<?= $v['id'] ?>" <?= ($e['vehicle_id'] ?? '') == $v['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['name']) ?> (<?= $v['plate_number'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Supir</label>
          <select name="driver_id" class="form-control">
            <option value="">-- Tanpa Supir --</option>
            <?php foreach ($drivers as $d): ?>
              <option value="<?= $d['id'] ?>" <?= ($e['driver_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($d['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Biaya BBM (Rp) <span style="color:var(--red)">*</span></label>
          <input type="number" name="fuel_price" class="form-control" value="<?= htmlspecialchars($e['fuel_price'] ?? '') ?>" required min="0" step="1000" placeholder="0">
        </div>
        <div class="form-group">
          <label class="form-label">Asal Keberangkatan</label>
          <input type="text" name="origin" class="form-control" value="<?= htmlspecialchars($e['origin'] ?? '') ?>" placeholder="cth. Padang">
        </div>
        <div class="form-group">
          <label class="form-label">Tujuan</label>
          <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($e['destination'] ?? '') ?>" placeholder="cth. Bukittinggi">
        </div>
        <div class="form-group">
          <label class="form-label">Jumlah Liter (L)</label>
          <input type="number" name="fuel_liters" class="form-control" value="<?= htmlspecialchars($e['fuel_liters'] ?? '') ?>" step="0.1" min="0" placeholder="0.0">
        </div>
        <div class="form-group">
          <label class="form-label">Odometer (km)</label>
          <input type="number" name="odometer_km" class="form-control" value="<?= htmlspecialchars($e['odometer_km'] ?? '') ?>" min="0" placeholder="misal: 125000">
        </div>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Catatan</label>
          <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($e['notes'] ?? '') ?></textarea>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
        <a href="<?= adminUrl('/admin/expenses/fuel') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
