<?php
$pageTitle = isset($salary) ? 'Edit Data Gaji' : 'Input Gaji Supir';
require BASE_PATH . '/views/layouts/admin.php';
$s = $salary ?? [];
$months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin/expenses') ?>">Pengeluaran</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/expenses/salaries') ?>">Gaji Supir</a>
      <span class="sep">/</span> <?= isset($salary) ? 'Edit' : 'Input' ?>
    </div>
    <h1><?= $pageTitle ?></h1>
  </div>
</div>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger"><span class="alert-icon"><i class="fa-solid fa-circle-xmark"></i></span><?= htmlspecialchars($_SESSION['error']) ?><?php unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card" style="max-width:660px">
  <div class="card-body">
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Supir <span style="color:var(--red)">*</span></label>
          <select name="driver_id" class="form-control" required id="driverSelect" onchange="fillBaseSalary(this)">
            <option value="">-- Pilih Supir --</option>
            <?php foreach ($drivers as $d): ?>
              <option value="<?= $d['id'] ?>" data-salary="<?= $d['base_salary'] ?>" <?= ($s['driver_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($d['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Bulan <span style="color:var(--red)">*</span></label>
          <select name="period_month" class="form-control" required>
            <?php for ($m = 1; $m <= 12; $m++): ?>
              <option value="<?= $m ?>" <?= ($s['period_month'] ?? date('n')) == $m ? 'selected' : '' ?>><?= $months[$m] ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tahun <span style="color:var(--red)">*</span></label>
          <select name="period_year" class="form-control" required>
            <?php for ($y = date('Y'); $y >= date('Y')-3; $y--): ?>
              <option value="<?= $y ?>" <?= ($s['period_year'] ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Gaji Pokok (Rp)</label>
          <input type="number" name="base_salary" id="baseSalary" class="form-control" value="<?= htmlspecialchars($s['base_salary'] ?? 0) ?>" min="0" step="50000" oninput="calcNet()">
        </div>
        <div class="form-group">
          <label class="form-label">Jumlah Trip Bulan Ini</label>
          <input type="number" name="trip_count" class="form-control" value="<?= htmlspecialchars($s['trip_count'] ?? 0) ?>" min="0">
        </div>
        <div class="form-group">
          <label class="form-label">Bonus (Rp)</label>
          <input type="number" name="bonus" id="bonus" class="form-control" value="<?= htmlspecialchars($s['bonus'] ?? 0) ?>" min="0" step="50000" oninput="calcNet()">
        </div>
        <div class="form-group">
          <label class="form-label">Potongan (Rp)</label>
          <input type="number" name="deduction" id="deduction" class="form-control" value="<?= htmlspecialchars($s['deduction'] ?? 0) ?>" min="0" step="50000" oninput="calcNet()">
        </div>

        <!-- PREVIEW GAJI BERSIH -->
        <div style="grid-column:1/-1;background:var(--navy);border-radius:var(--radius);padding:16px 20px;display:flex;justify-content:space-between;align-items:center">
          <span style="color:rgba(255,255,255,.7);font-size:.9rem">Gaji Bersih</span>
          <span id="netSalaryDisplay" style="color:var(--amber);font-size:1.3rem;font-weight:700">Rp 0</span>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Dibayar</label>
          <input type="date" name="paid_at" class="form-control" value="<?= htmlspecialchars($s['paid_at'] ?? '') ?>">
          <small style="color:var(--gray-400);font-size:.78rem">Isi jika sudah dibayarkan</small>
        </div>
        <?php if (isset($salary)): ?>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-control">
            <option value="draft" <?= ($s['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="paid"  <?= ($s['status'] ?? '') === 'paid'  ? 'selected' : '' ?>>Lunas</option>
          </select>
        </div>
        <?php endif; ?>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Catatan</label>
          <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($s['notes'] ?? '') ?></textarea>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
        <a href="<?= adminUrl('/admin/expenses/salaries') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function fillBaseSalary(sel) {
  const opt = sel.options[sel.selectedIndex];
  const sal = opt.dataset.salary ?? 0;
  document.getElementById('baseSalary').value = sal;
  calcNet();
}
function calcNet() {
  const base = parseInt(document.getElementById('baseSalary').value) || 0;
  const bonus = parseInt(document.getElementById('bonus').value) || 0;
  const ded = parseInt(document.getElementById('deduction').value) || 0;
  const net = base + bonus - ded;
  document.getElementById('netSalaryDisplay').textContent = 'Rp ' + net.toLocaleString('id-ID');
}
calcNet();
</script>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
