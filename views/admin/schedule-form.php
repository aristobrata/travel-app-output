<?php
$isEdit = isset($schedule);
$pageTitle = $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/schedules') ?>">Jadwal</a>
      <span class="sep">/</span>
      <?= $isEdit ? 'Edit' : 'Tambah' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
    <p><?= $isEdit ? 'Perbarui informasi jadwal.' : 'Isi data jadwal keberangkatan baru.' ?></p>
  </div>
  <a href="<?= adminUrl('/admin/schedules') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div style="max-width:700px">
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-regular fa-calendar-plus"></i> Data Jadwal</span>
  </div>
  <div class="card-body">
    <form method="POST" action="<?= $isEdit ? adminUrl('/admin/schedules/' . $schedule['id'] . '/edit') : adminUrl('/admin/schedules/create') ?>">
      <?php if (!empty($_SESSION['csrf_token'])): ?>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kendaraan <span class="required">*</span></label>
          <select name="vehicle_id" class="form-control" required>
            <option value="">— Pilih Kendaraan —</option>
            <?php foreach ($vehicles as $v): ?>
              <option value="<?= $v['id'] ?>"
                <?= (($isEdit ? $schedule['vehicle_id'] : ($_POST['vehicle_id'] ?? '')) == $v['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['plate_number']) ?> · <?= $v['capacity'] ?> kursi)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Rute <span class="required">*</span></label>
          <select name="route_id" class="form-control" required>
            <option value="">— Pilih Rute —</option>
            <?php foreach ($routes as $r): ?>
              <option value="<?= $r['id'] ?>"
                <?= (($isEdit ? $schedule['route_id'] : ($_POST['route_id'] ?? '')) == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['origin']) ?> → <?= htmlspecialchars($r['destination']) ?>
                (Rp <?= number_format($r['base_price'], 0, ',', '.') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Waktu Berangkat <span class="required">*</span></label>
          <input type="datetime-local" name="depart_at" class="form-control" required
            value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($schedule['depart_at'])) : ($_POST['depart_at'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Waktu Tiba <span class="required">*</span></label>
          <input type="datetime-local" name="arrive_at" class="form-control" required
            value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($schedule['arrive_at'])) : ($_POST['arrive_at'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Harga Override (Rp)</label>
        <div style="position:relative">
          <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-400);font-size:.85rem">Rp</span>
          <input type="number" name="price_override" min="0" class="form-control" style="padding-left:36px"
            placeholder="Kosongkan untuk gunakan harga rute"
            value="<?= htmlspecialchars($isEdit ? ($schedule['price_override'] ?? '') : ($_POST['price_override'] ?? '')) ?>">
        </div>
        <div class="form-hint">Isi jika harga jadwal ini berbeda dari harga dasar rute.</div>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <a href="<?= adminUrl('/admin/schedules') ?>" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-<?= $isEdit ? 'floppy-disk' : 'plus' ?>"></i>
          <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Jadwal' ?>
        </button>
      </div>
    </form>
  </div>
</div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
