<?php
$pageTitle = isset($record) ? 'Edit Data Servis' : 'Tambah Data Servis';
require BASE_PATH . '/views/layouts/admin.php';
$r = $record ?? [];
$serviceTypes = [
    'oli' => 'Ganti Oli', 'tune_up' => 'Tune Up', 'ban' => 'Ganti Ban',
    'rem' => 'Servis Rem', 'ac' => 'Servis AC', 'mesin' => 'Perbaikan Mesin',
    'bodi' => 'Perbaikan Bodi', 'kaki_kaki' => 'Kaki-Kaki', 'lainnya' => 'Lainnya',
];
?>
<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/expenses/service') ?>">Servis</a>
      <span class="sep">/</span> <?= isset($record) ? 'Edit' : 'Tambah' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
  </div>
</div>

<?php if (!empty($_SESSION['errors'])): ?>
<div class="alert alert-danger"><span class="alert-icon"><i class="fa-solid fa-circle-xmark"></i></span><div><?php foreach ($_SESSION['errors'] as $err): ?><div><?= htmlspecialchars($err) ?></div><?php endforeach; unset($_SESSION['errors']); ?></div></div>
<?php endif; ?>

<div class="card" style="max-width:700px">
  <div class="card-body">
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label class="form-label">Kendaraan <span style="color:var(--red)">*</span></label>
          <select name="vehicle_id" class="form-control" required>
            <option value="">-- Pilih Kendaraan --</option>
            <?php foreach ($vehicles as $v): ?>
              <option value="<?= $v['id'] ?>" <?= ($r['vehicle_id'] ?? '') == $v['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['name']) ?> (<?= $v['plate_number'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Servis <span style="color:var(--red)">*</span></label>
          <input type="date" name="service_date" class="form-control" value="<?= htmlspecialchars($r['service_date'] ?? date('Y-m-d')) ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Jenis Servis <span style="color:var(--red)">*</span></label>
          <select name="service_type" class="form-control" required>
            <?php foreach ($serviceTypes as $k => $l): ?>
              <option value="<?= $k ?>" <?= ($r['service_type'] ?? 'lainnya') === $k ? 'selected' : '' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="selesai"       <?= ($r['status'] ?? 'selesai') === 'selesai'       ? 'selected' : '' ?>>Selesai</option>
            <option value="dalam_servis"  <?= ($r['status'] ?? '') === 'dalam_servis'          ? 'selected' : '' ?>>Dalam Servis</option>
            <option value="dijadwalkan"   <?= ($r['status'] ?? '') === 'dijadwalkan'           ? 'selected' : '' ?>>Dijadwalkan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Nama Bengkel</label>
          <input type="text" name="workshop" class="form-control" value="<?= htmlspecialchars($r['workshop'] ?? '') ?>" placeholder="cth. Bengkel Pak Budi">
        </div>
        <div class="form-group">
          <label class="form-label">Biaya Servis (Rp) <span style="color:var(--red)">*</span></label>
          <input type="number" name="cost" class="form-control" value="<?= htmlspecialchars($r['cost'] ?? '') ?>" required min="0" step="1000">
        </div>
        <div class="form-group">
          <label class="form-label">Odometer Saat Servis (km)</label>
          <input type="number" name="odometer_km" class="form-control" value="<?= htmlspecialchars($r['odometer_km'] ?? '') ?>" min="0">
        </div>
        <div class="form-group">
          <label class="form-label">Jadwal Servis Berikutnya</label>
          <input type="date" name="next_service_date" class="form-control" value="<?= htmlspecialchars($r['next_service_date'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Odometer Servis Berikutnya (km)</label>
          <input type="number" name="next_service_km" class="form-control" value="<?= htmlspecialchars($r['next_service_km'] ?? '') ?>" min="0">
        </div>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Keterangan / Deskripsi</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Ganti oli mesin + filter..."><?= htmlspecialchars($r['description'] ?? '') ?></textarea>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
        <a href="<?= adminUrl('/admin/expenses/service') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>
<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
