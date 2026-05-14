<?php
$isEdit   = isset($vehicle);
$pageTitle = $isEdit ? 'Edit Kendaraan' : 'Tambah Kendaraan';
$errors    = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$fac = $isEdit
  ? (is_string($vehicle['facilities']) ? (json_decode($vehicle['facilities'], true) ?? []) : ($vehicle['facilities'] ?? []))
  : [];
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/vehicles') ?>">Kendaraan</a>
      <span class="sep">/</span>
      <?= $isEdit ? 'Edit' : 'Tambah' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
    <p><?= $isEdit ? 'Perbarui informasi kendaraan.' : 'Isi data kendaraan baru.' ?></p>
  </div>
  <a href="<?= adminUrl('/admin/vehicles') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<div style="max-width:780px">
<form method="POST"
  action="<?= $isEdit ? adminUrl('/admin/vehicles/'.$vehicle['id'].'/edit') : adminUrl('/admin/vehicles/create') ?>"
  enctype="multipart/form-data">
  <?php if (!empty($_SESSION['csrf_token'])): ?>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
  <?php endif; ?>

  <!-- ── INFORMASI DASAR ── -->
  <div class="card" style="margin-bottom:18px">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-bus" style="color:var(--amber)"></i> Informasi Dasar</span>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nama Kendaraan <span class="required">*</span></label>
          <input type="text" name="name" class="form-control <?= isset($errors['name'])?'error':'' ?>"
            placeholder="Contoh: Kramat Jati Executive"
            value="<?= htmlspecialchars($isEdit ? $vehicle['name'] : ($_POST['name'] ?? '')) ?>">
          <?php if(!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="form-label">Nomor Plat <span class="required">*</span></label>
          <input type="text" name="plate_number" class="form-control <?= isset($errors['plate_number'])?'error':'' ?>"
            placeholder="BA 1234 XX"
            value="<?= htmlspecialchars($isEdit ? $vehicle['plate_number'] : ($_POST['plate_number'] ?? '')) ?>">
          <?php if(!empty($errors['plate_number'])): ?><div class="form-error"><?= $errors['plate_number'] ?></div><?php endif; ?>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tipe Kendaraan</label>
          <select name="type" class="form-control">
            <?php foreach(['bus'=>'Bus','minibus'=>'Minibus','travel'=>'Travel','shuttle'=>'Shuttle'] as $val=>$lbl): ?>
              <option value="<?= $val ?>"
                <?= (($isEdit ? $vehicle['type'] : ($_POST['type'] ?? '')) === $val) ? 'selected' : '' ?>>
                <?= $lbl ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kapasitas Kursi <span class="required">*</span></label>
          <input type="number" name="capacity" min="1" max="60"
            class="form-control <?= isset($errors['capacity'])?'error':'' ?>"
            placeholder="Jumlah kursi"
            value="<?= htmlspecialchars($isEdit ? $vehicle['capacity'] : ($_POST['capacity'] ?? '')) ?>">
          <?php if(!empty($errors['capacity'])): ?><div class="form-error"><?= $errors['capacity'] ?></div><?php endif; ?>
        </div>
      </div>
      <?php if($isEdit): ?>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="active"   <?= $vehicle['status']==='active'  ?'selected':'' ?>>Aktif</option>
          <option value="inactive" <?= $vehicle['status']!=='active'  ?'selected':'' ?>>Nonaktif</option>
        </select>
      </div>
      <?php endif; ?>

      <div class="form-group">
        <label class="form-label">Fasilitas</label>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);border:1px solid var(--gray-200)">
          <label class="form-check"><input type="checkbox" name="facility_ac"   <?= !empty($fac['ac'])   ?'checked':'' ?>><i class="fa-solid fa-snowflake" style="color:var(--blue)"></i> AC</label>
          <label class="form-check"><input type="checkbox" name="facility_wifi" <?= !empty($fac['wifi']) ?'checked':'' ?>><i class="fa-solid fa-wifi"      style="color:var(--green)"></i> WiFi</label>
          <label class="form-check"><input type="checkbox" name="facility_usb"  <?= !empty($fac['usb'])  ?'checked':'' ?>><i class="fa-solid fa-plug"      style="color:var(--amber)"></i> USB Charging</label>
          <label class="form-check"><input type="checkbox" name="facility_tv"   <?= !empty($fac['tv'])   ?'checked':'' ?>><i class="fa-solid fa-tv"        style="color:var(--purple)"></i> TV/Hiburan</label>
        </div>
      </div>
    </div>
  </div>

  <!-- ── NOMOR RANGKA & MESIN ── -->
  <div class="card" style="margin-bottom:18px">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-barcode" style="color:var(--navy)"></i> Nomor Identifikasi Kendaraan</span>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-car-battery" style="color:var(--gray-400)"></i>
            Nomor Rangka (VIN / Chassis)
          </label>
          <input type="text" name="chassis_number" class="form-control"
            placeholder="Contoh: MHFE1GE1JCJ012345"
            style="font-family:monospace;letter-spacing:.04em"
            value="<?= htmlspecialchars($isEdit ? ($vehicle['chassis_number'] ?? '') : ($_POST['chassis_number'] ?? '')) ?>">
          <div class="form-hint">Nomor rangka tertera di STNK / plat rangka kendaraan.</div>
        </div>
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-gear" style="color:var(--gray-400)"></i>
            Nomor Mesin
          </label>
          <input type="text" name="engine_number" class="form-control"
            placeholder="Contoh: 1TR-FE1234567"
            style="font-family:monospace;letter-spacing:.04em"
            value="<?= htmlspecialchars($isEdit ? ($vehicle['engine_number'] ?? '') : ($_POST['engine_number'] ?? '')) ?>">
          <div class="form-hint">Nomor mesin tertera di blok mesin dan BPKB.</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── PAJAK ── -->
  <div class="card" style="margin-bottom:18px">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-calendar-check" style="color:var(--orange)"></i> Informasi Pajak</span>
    </div>
    <div class="card-body">
      <div class="form-group" style="max-width:280px">
        <label class="form-label">Tanggal Jatuh Tempo Pajak</label>
        <input type="date" name="tax_due_date" class="form-control"
          value="<?= htmlspecialchars($isEdit ? ($vehicle['tax_due_date'] ?? '') : ($_POST['tax_due_date'] ?? '')) ?>">
        <div class="form-hint">Sistem akan menampilkan peringatan 30 hari sebelum jatuh tempo.</div>
      </div>
      <?php
      if ($isEdit && !empty($vehicle['tax_due_date'])):
        $days = (int)ceil((strtotime($vehicle['tax_due_date']) - time()) / 86400);
        $color = $days < 0 ? 'var(--red)' : ($days <= 7 ? 'var(--red)' : ($days <= 30 ? 'var(--orange)' : 'var(--green)'));
        $msg   = $days < 0  ? '⚠️ Pajak sudah kadaluarsa!'
               : ($days === 0 ? '⚠️ Pajak jatuh tempo hari ini!'
               : ($days <= 30 ? "⏰ Jatuh tempo dalam {$days} hari"
               : "✅ Pajak aktif · {$days} hari lagi"));
      ?>
      <div style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;background:var(--gray-50);border-radius:var(--radius-sm);border:1.5px solid <?= $color ?>;font-size:.85rem;font-weight:600;color:<?= $color ?>">
        <?= $msg ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ── DOKUMEN ── -->
  <div class="card" style="margin-bottom:18px">
    <div class="card-header">
      <span class="card-title"><i class="fa-solid fa-folder-open" style="color:var(--amber)"></i> Dokumen Kendaraan</span>
    </div>
    <div class="card-body">
      <div class="form-row">
        <!-- STNK -->
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-id-card" style="color:var(--blue)"></i>
            Upload STNK
          </label>
          <?php if($isEdit && !empty($vehicle['stnk_file'])): ?>
          <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--blue-light);border-radius:var(--radius-sm);margin-bottom:8px;font-size:.82rem">
            <i class="fa-solid fa-circle-check" style="color:var(--blue)"></i>
            <span>File terpasang:</span>
            <a href="<?= adminUrl('/'.ltrim($vehicle['stnk_file'],'/')) ?>" target="_blank"
               style="color:var(--blue);font-weight:600;text-decoration:none">
              <?= basename($vehicle['stnk_file']) ?>
            </a>
            <a href="<?= adminUrl('/'.ltrim($vehicle['stnk_file'],'/')) ?>" target="_blank"
               class="btn btn-outline btn-xs" style="margin-left:auto;color:var(--blue)">
              <i class="fa-solid fa-eye"></i> Lihat
            </a>
          </div>
          <?php endif; ?>
          <input type="file" name="stnk_file" class="form-control"
            accept="image/jpeg,image/png,image/webp,application/pdf"
            style="padding:7px">
          <div class="form-hint">Format: JPG, PNG, WEBP, atau PDF. <?= ($isEdit && !empty($vehicle['stnk_file'])) ? 'Kosongkan jika tidak ingin mengganti.' : '' ?></div>
        </div>

        <!-- BPKB -->
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-book" style="color:var(--purple)"></i>
            Upload BPKB
          </label>
          <?php if($isEdit && !empty($vehicle['bpkb_file'])): ?>
          <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--purple-light);border-radius:var(--radius-sm);margin-bottom:8px;font-size:.82rem">
            <i class="fa-solid fa-circle-check" style="color:var(--purple)"></i>
            <span>File terpasang:</span>
            <a href="<?= adminUrl('/'.ltrim($vehicle['bpkb_file'],'/')) ?>" target="_blank"
               style="color:var(--purple);font-weight:600;text-decoration:none">
              <?= basename($vehicle['bpkb_file']) ?>
            </a>
            <a href="<?= adminUrl('/'.ltrim($vehicle['bpkb_file'],'/')) ?>" target="_blank"
               class="btn btn-outline btn-xs" style="margin-left:auto;color:var(--purple)">
              <i class="fa-solid fa-eye"></i> Lihat
            </a>
          </div>
          <?php endif; ?>
          <input type="file" name="bpkb_file" class="form-control"
            accept="image/jpeg,image/png,image/webp,application/pdf"
            style="padding:7px">
          <div class="form-hint">Format: JPG, PNG, WEBP, atau PDF. <?= ($isEdit && !empty($vehicle['bpkb_file'])) ? 'Kosongkan jika tidak ingin mengganti.' : '' ?></div>
        </div>
      </div>

      <!-- Preview upload -->
      <div id="upload-preview" style="display:none;gap:12px;margin-top:12px" class="form-row">
        <div id="stnk-preview"></div>
        <div id="bpkb-preview"></div>
      </div>
    </div>
  </div>

  <!-- ACTIONS -->
  <div style="display:flex;gap:10px;justify-content:flex-end">
    <a href="<?= adminUrl('/admin/vehicles') ?>" class="btn btn-outline">Batal</a>
    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-<?= $isEdit ? 'floppy-disk' : 'plus' ?>"></i>
      <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kendaraan' ?>
    </button>
  </div>
</form>
</div>

<script>
// Preview gambar sebelum upload
function previewFile(inputId, previewId, label) {
  const input = document.getElementById(inputId);
  const wrap  = document.getElementById(previewId);
  if (!input || !wrap) return;
  input.addEventListener('change', function() {
    if (!this.files[0]) { wrap.innerHTML = ''; return; }
    const file = this.files[0];
    const isImg = file.type.startsWith('image/');
    if (isImg) {
      const reader = new FileReader();
      reader.onload = e => {
        wrap.innerHTML = `
          <div style="font-size:.75rem;font-weight:600;color:var(--gray-500);margin-bottom:5px">${label}</div>
          <img src="${e.target.result}"
            style="max-height:140px;border-radius:8px;border:2px solid var(--gray-200);object-fit:cover">`;
        document.getElementById('upload-preview').style.display = 'flex';
      };
      reader.readAsDataURL(file);
    } else {
      wrap.innerHTML = `
        <div style="font-size:.75rem;font-weight:600;color:var(--gray-500);margin-bottom:5px">${label}</div>
        <div style="padding:12px 16px;background:var(--gray-50);border:2px solid var(--gray-200);border-radius:8px;font-size:.82rem;color:var(--gray-600)">
          <i class="fa-solid fa-file-pdf" style="color:var(--red)"></i> ${file.name}
        </div>`;
      document.getElementById('upload-preview').style.display = 'flex';
    }
  });
}

// Pasang listener setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
  const stnkInput = document.querySelector('input[name="stnk_file"]');
  const bpkbInput = document.querySelector('input[name="bpkb_file"]');
  if (stnkInput) { stnkInput.id = 'stnk-input'; previewFile('stnk-input','stnk-preview','Preview STNK'); }
  if (bpkbInput) { bpkbInput.id = 'bpkb-input'; previewFile('bpkb-input','bpkb-preview','Preview BPKB'); }
});
</script>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
