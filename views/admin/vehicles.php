<?php
$pageTitle = 'Kendaraan';
require BASE_PATH . '/views/layouts/admin.php';

function taxDaysLeft(string $date): int {
    return (int)ceil((strtotime($date) - time()) / 86400);
}
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span> Kendaraan
    </div>
    <h1>Manajemen Kendaraan</h1>
    <p>Kelola armada kendaraan, dokumen, dan informasi pajak.</p>
  </div>
  <a href="<?= adminUrl('/admin/vehicles/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Tambah Kendaraan
  </a>
</div>

<?php if (!empty($taxWarning)): ?>
<div class="alert alert-warning">
  <span class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
  <div>
    <strong><?= count($taxWarning) ?> kendaraan</strong> pajaknya jatuh tempo dalam 30 hari:
    <div style="display:flex;gap:7px;flex-wrap:wrap;margin-top:8px">
      <?php foreach ($taxWarning as $tw):
        $d = taxDaysLeft($tw['tax_due_date']);
        $c = $d <= 7 ? 'var(--red)' : 'var(--orange)';
      ?>
        <a href="<?= adminUrl('/admin/vehicles/' . $tw['id']) ?>"
           style="background:#fff;border:1.5px solid <?= $c ?>;color:<?= $c ?>;padding:3px 10px;border-radius:6px;font-size:.78rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
          <i class="fa-solid fa-bus"></i> <?= htmlspecialchars($tw['name']) ?>
          — <?= $d < 0 ? 'KADALUARSA!' : ($d === 0 ? 'Hari ini!' : $d . ' hari') ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa-solid fa-bus"></i> Daftar Kendaraan</span>
    <span style="font-size:.82rem;color:var(--gray-400)"><?= count($vehicles) ?> kendaraan aktif</span>
  </div>
  <div class="table-wrap">
    <?php if (empty($vehicles)): ?>
      <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-bus"></i></div>
        <h3>Belum ada kendaraan</h3>
        <p>Mulai tambahkan kendaraan armada Anda.</p>
        <a href="<?= adminUrl('/admin/vehicles/create') ?>" class="btn btn-primary" style="margin-top:14px">
          <i class="fa-solid fa-plus"></i> Tambah Pertama
        </a>
      </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama & Plat</th>
          <th>No. Rangka / Mesin</th>
          <th>Tipe</th>
          <th>Kapasitas</th>
          <th>Fasilitas</th>
          <th>Pajak</th>
          <th>Dokumen</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vehicles as $v):
          $fac = is_string($v['facilities']) ? (json_decode($v['facilities'], true) ?? []) : ($v['facilities'] ?? []);
          $taxDays = !empty($v['tax_due_date']) ? taxDaysLeft($v['tax_due_date']) : null;
          $taxColor = $taxDays === null ? 'var(--gray-300)'
            : ($taxDays < 0   ? 'var(--red)'
            : ($taxDays <= 7  ? 'var(--red)'
            : ($taxDays <= 30 ? 'var(--orange)'
            : 'var(--green)')));
        ?>
        <tr>
          <td style="color:var(--gray-400);font-size:.78rem"><?= $v['id'] ?></td>
          <td>
            <div style="font-weight:600"><?= htmlspecialchars($v['name']) ?></div>
            <code style="font-size:.72rem;background:var(--gray-100);padding:1px 6px;border-radius:4px;margin-top:2px;display:inline-block">
              <?= htmlspecialchars($v['plate_number']) ?>
            </code>
          </td>
          <td style="font-size:.78rem">
            <div style="display:flex;align-items:center;gap:5px;margin-bottom:3px">
              <i class="fa-solid fa-car-battery" style="color:var(--gray-400);width:13px;font-size:.7rem"></i>
              <span style="font-family:monospace;color:var(--gray-700)">
                <?= !empty($v['chassis_number']) ? htmlspecialchars($v['chassis_number']) : '<span style="color:var(--gray-300)">—</span>' ?>
              </span>
            </div>
            <div style="display:flex;align-items:center;gap:5px">
              <i class="fa-solid fa-gear" style="color:var(--gray-400);width:13px;font-size:.7rem"></i>
              <span style="font-family:monospace;color:var(--gray-700)">
                <?= !empty($v['engine_number']) ? htmlspecialchars($v['engine_number']) : '<span style="color:var(--gray-300)">—</span>' ?>
              </span>
            </div>
          </td>
          <td>
            <?php $icon = match($v['type']??''){
              'bus'=>'<i class="fa-solid fa-bus"></i>','minibus'=>'<i class="fa-solid fa-van-shuttle"></i>',
              'travel'=>'<i class="fa-solid fa-car-side"></i>',default=>'<i class="fa-solid fa-truck"></i>'
            }; ?>
            <span style="display:flex;align-items:center;gap:5px;font-size:.85rem"><?= $icon ?> <?= ucfirst($v['type']??'-') ?></span>
          </td>
          <td style="text-align:center">
            <span style="font-weight:700;font-size:1.05rem"><?= $v['capacity'] ?></span>
            <div style="font-size:.7rem;color:var(--gray-400)">kursi</div>
          </td>
          <td>
            <div style="display:flex;gap:3px;flex-wrap:wrap">
              <?php if(!empty($fac['ac'])): ?><span class="badge badge-active" style="font-size:.62rem;padding:2px 5px">❄ AC</span><?php endif; ?>
              <?php if(!empty($fac['wifi'])): ?><span class="badge badge-active" style="font-size:.62rem;padding:2px 5px">📶 WiFi</span><?php endif; ?>
              <?php if(!empty($fac['usb'])): ?><span class="badge badge-active" style="font-size:.62rem;padding:2px 5px">🔌 USB</span><?php endif; ?>
              <?php if(!empty($fac['tv'])): ?><span class="badge badge-active" style="font-size:.62rem;padding:2px 5px">📺 TV</span><?php endif; ?>
              <?php if(empty($fac['ac'])&&empty($fac['wifi'])&&empty($fac['usb'])&&empty($fac['tv'])): ?>
                <span style="color:var(--gray-300);font-size:.75rem">—</span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <?php if(!empty($v['tax_due_date'])): ?>
              <div style="font-weight:700;font-size:.82rem;color:<?= $taxColor ?>">
                <?= date('d/m/Y',strtotime($v['tax_due_date'])) ?>
              </div>
              <div style="font-size:.7rem;color:<?= $taxColor ?>">
                <?php
                  if($taxDays<0) echo '<i class="fa-solid fa-circle-xmark"></i> Kadaluarsa';
                  elseif($taxDays===0) echo '<i class="fa-solid fa-triangle-exclamation"></i> Hari ini!';
                  else echo '<i class="fa-regular fa-clock"></i> '.$taxDays.' hari';
                ?>
              </div>
            <?php else: ?>
              <span style="color:var(--gray-300);font-size:.78rem">Belum diisi</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;flex-direction:column;gap:4px">
              <?php if(!empty($v['stnk_file'])): ?>
                <a href="<?= adminUrl('/' . ltrim($v['stnk_file'],'/')) ?>" target="_blank"
                   class="btn btn-outline btn-xs" style="color:var(--blue);justify-content:center">
                  <i class="fa-solid fa-id-card"></i> STNK ✓
                </a>
              <?php else: ?>
                <span style="font-size:.7rem;color:var(--gray-300);display:flex;align-items:center;gap:3px">
                  <i class="fa-solid fa-file-circle-xmark"></i> STNK —
                </span>
              <?php endif; ?>
              <?php if(!empty($v['bpkb_file'])): ?>
                <a href="<?= adminUrl('/' . ltrim($v['bpkb_file'],'/')) ?>" target="_blank"
                   class="btn btn-outline btn-xs" style="color:var(--purple);justify-content:center">
                  <i class="fa-solid fa-book"></i> BPKB ✓
                </a>
              <?php else: ?>
                <span style="font-size:.7rem;color:var(--gray-300);display:flex;align-items:center;gap:3px">
                  <i class="fa-solid fa-file-circle-xmark"></i> BPKB —
                </span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <span class="badge badge-<?= $v['status']==='active'?'active':'inactive' ?>">
              <?= $v['status']==='active'?'Aktif':'Nonaktif' ?>
            </span>
          </td>
          <td>
            <div class="actions">
              <a href="<?= adminUrl('/admin/vehicles/'.$v['id']) ?>" class="btn btn-info btn-xs" title="Detail">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="<?= adminUrl('/admin/vehicles/'.$v['id'].'/edit') ?>" class="btn btn-outline btn-xs" title="Edit">
                <i class="fa-solid fa-pencil"></i>
              </a>
              <form method="POST" action="<?= adminUrl('/admin/vehicles/'.$v['id'].'/delete') ?>"
                onsubmit="return confirm('Nonaktifkan kendaraan ini?')" style="display:inline">
                <?php if(!empty($_SESSION['csrf_token'])): ?>
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-danger btn-xs" title="Nonaktifkan">
                  <i class="fa-solid fa-ban"></i>
                </button>
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
