<?php
$pageTitle = 'Detail Kendaraan';
$fac = is_string($vehicle['facilities'])
  ? (json_decode($vehicle['facilities'], true) ?? [])
  : ($vehicle['facilities'] ?? []);
$taxDays = !empty($vehicle['tax_due_date'])
  ? (int)ceil((strtotime($vehicle['tax_due_date']) - time()) / 86400)
  : null;
$taxColor = $taxDays === null ? 'var(--gray-400)'
  : ($taxDays < 0   ? 'var(--red)'
  : ($taxDays <= 7  ? 'var(--red)'
  : ($taxDays <= 30 ? 'var(--orange)'
  : 'var(--green)')));
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/vehicles') ?>">Kendaraan</a>
      <span class="sep">/</span>
      Detail
    </div>
    <h1><?= htmlspecialchars($vehicle['name']) ?></h1>
    <p>Informasi lengkap kendaraan.</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="<?= adminUrl('/admin/vehicles/' . $vehicle['id'] . '/edit') ?>" class="btn btn-primary">
      <i class="fa-solid fa-pencil"></i> Edit
    </a>
    <a href="<?= adminUrl('/admin/vehicles') ?>" class="btn btn-outline">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>

<?php if ($taxDays !== null && $taxDays <= 30): ?>
<div class="alert <?= $taxDays < 0 ? 'alert-error' : 'alert-warning' ?>">
  <span class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
  <?php if ($taxDays < 0): ?>
    <strong>Pajak kendaraan ini sudah KADALUARSA!</strong> Segera perpanjang pajak.
  <?php elseif ($taxDays === 0): ?>
    <strong>Pajak jatuh tempo HARI INI!</strong> Segera bayar pajak.
  <?php else: ?>
    Pajak kendaraan ini jatuh tempo dalam <strong><?= $taxDays ?> hari</strong>
    (<?= date('d F Y', strtotime($vehicle['tax_due_date'])) ?>). Segera rencanakan perpanjangan.
  <?php endif; ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

  <!-- KIRI -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- INFO DASAR -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-bus" style="color:var(--amber)"></i> Informasi Kendaraan</span>
        <span class="badge badge-<?= $vehicle['status']==='active'?'active':'inactive' ?>" style="font-size:.82rem">
          <?= $vehicle['status']==='active'?'✅ Aktif':'🚫 Nonaktif' ?>
        </span>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:4px">Nama Kendaraan</div>
            <div style="font-weight:700;font-size:1.1rem;color:var(--navy)"><?= htmlspecialchars($vehicle['name']) ?></div>
          </div>
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:4px">Tipe</div>
            <div style="font-weight:600">
              <?php echo match($vehicle['type']??''){
                'bus'=>'<i class="fa-solid fa-bus" style="color:var(--amber)"></i> Bus',
                'minibus'=>'<i class="fa-solid fa-van-shuttle" style="color:var(--amber)"></i> Minibus',
                'travel'=>'<i class="fa-solid fa-car-side" style="color:var(--amber)"></i> Travel',
                default=>'<i class="fa-solid fa-truck" style="color:var(--amber)"></i> '.ucfirst($vehicle['type']??'-')
              }; ?>
            </div>
          </div>
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:4px">Nomor Plat</div>
            <code style="font-size:1rem;font-weight:800;color:var(--navy);background:var(--amber-light);padding:4px 12px;border-radius:6px;letter-spacing:.06em">
              <?= htmlspecialchars($vehicle['plate_number']) ?>
            </code>
          </div>
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:4px">Kapasitas Kursi</div>
            <div style="font-weight:700;font-size:1.2rem;color:var(--navy)">
              <?= $vehicle['capacity'] ?> <span style="font-size:.85rem;font-weight:400;color:var(--gray-400)">kursi</span>
            </div>
          </div>
        </div>

        <!-- Fasilitas -->
        <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--gray-100)">
          <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:10px">Fasilitas</div>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <?php
            $fasItems = [
              'ac'   => ['label'=>'AC','icon'=>'fa-snowflake','color'=>'var(--blue)'],
              'wifi' => ['label'=>'WiFi','icon'=>'fa-wifi','color'=>'var(--green)'],
              'usb'  => ['label'=>'USB Charging','icon'=>'fa-plug','color'=>'var(--amber)'],
              'tv'   => ['label'=>'TV/Hiburan','icon'=>'fa-tv','color'=>'var(--purple)'],
            ];
            $hasFac = false;
            foreach ($fasItems as $key => $item):
              if (!empty($fac[$key])):
                $hasFac = true;
            ?>
            <div style="display:flex;align-items:center;gap:7px;padding:7px 14px;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:8px;font-size:.85rem;font-weight:600">
              <i class="fa-solid <?= $item['icon'] ?>" style="color:<?= $item['color'] ?>"></i>
              <?= $item['label'] ?>
            </div>
            <?php endif; endforeach; ?>
            <?php if (!$hasFac): ?>
              <span style="color:var(--gray-400);font-size:.85rem;font-style:italic">Tidak ada fasilitas tercatat</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- NOMOR RANGKA & MESIN -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-barcode" style="color:var(--navy)"></i> Nomor Identifikasi</span>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:6px">
              <i class="fa-solid fa-car-battery"></i> Nomor Rangka (Chassis)
            </div>
            <?php if (!empty($vehicle['chassis_number'])): ?>
              <code style="font-size:.95rem;font-weight:700;color:var(--navy);background:var(--gray-100);padding:6px 12px;border-radius:6px;letter-spacing:.05em;display:block;word-break:break-all">
                <?= htmlspecialchars($vehicle['chassis_number']) ?>
              </code>
            <?php else: ?>
              <span style="color:var(--gray-300);font-style:italic;font-size:.875rem">Belum diisi</span>
            <?php endif; ?>
          </div>
          <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:6px">
              <i class="fa-solid fa-gear"></i> Nomor Mesin
            </div>
            <?php if (!empty($vehicle['engine_number'])): ?>
              <code style="font-size:.95rem;font-weight:700;color:var(--navy);background:var(--gray-100);padding:6px 12px;border-radius:6px;letter-spacing:.05em;display:block;word-break:break-all">
                <?= htmlspecialchars($vehicle['engine_number']) ?>
              </code>
            <?php else: ?>
              <span style="color:var(--gray-300);font-style:italic;font-size:.875rem">Belum diisi</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- KANAN -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- PAJAK -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-calendar-check" style="color:var(--orange)"></i> Status Pajak</span>
      </div>
      <div class="card-body">
        <?php if (!empty($vehicle['tax_due_date'])): ?>
          <div style="text-align:center;padding:16px 0">
            <div style="font-size:.78rem;color:var(--gray-400);margin-bottom:6px">Jatuh Tempo</div>
            <div style="font-size:1.4rem;font-weight:800;color:<?= $taxColor ?>">
              <?= date('d F Y', strtotime($vehicle['tax_due_date'])) ?>
            </div>
            <div style="margin-top:10px;padding:8px 16px;background:<?= $taxDays<0?'var(--red-light)':($taxDays<=30?'var(--amber-light)':'var(--green-light)') ?>;border-radius:8px;font-weight:700;font-size:.9rem;color:<?= $taxColor ?>">
              <?php
                if ($taxDays < 0)      echo '⚠️ KADALUARSA ' . abs($taxDays) . ' hari lalu';
                elseif ($taxDays === 0) echo '⚠️ Jatuh tempo HARI INI';
                elseif ($taxDays <= 30) echo '⏰ ' . $taxDays . ' hari lagi';
                else                    echo '✅ ' . $taxDays . ' hari lagi';
              ?>
            </div>
          </div>
        <?php else: ?>
          <div style="text-align:center;padding:20px;color:var(--gray-400)">
            <i class="fa-solid fa-calendar-xmark" style="font-size:2rem;margin-bottom:8px;display:block;opacity:.3"></i>
            <div style="font-size:.875rem">Tanggal pajak belum diisi</div>
            <a href="<?= adminUrl('/admin/vehicles/'.$vehicle['id'].'/edit') ?>"
               class="btn btn-outline btn-sm" style="margin-top:12px">
              <i class="fa-solid fa-plus"></i> Isi Sekarang
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- DOKUMEN -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-folder-open" style="color:var(--amber)"></i> Dokumen</span>
        <a href="<?= adminUrl('/admin/vehicles/'.$vehicle['id'].'/edit') ?>" class="btn btn-outline btn-xs">
          <i class="fa-solid fa-upload"></i> Upload
        </a>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:12px">

        <!-- STNK -->
        <div style="border:1.5px solid <?= !empty($vehicle['stnk_file'])?'var(--blue)':'var(--gray-200)' ?>;border-radius:10px;overflow:hidden">
          <div style="padding:10px 14px;background:<?= !empty($vehicle['stnk_file'])?'var(--blue-light)':'var(--gray-50)' ?>;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-id-card" style="color:<?= !empty($vehicle['stnk_file'])?'var(--blue)':'var(--gray-300)' ?>;font-size:1.1rem"></i>
            <div style="flex:1">
              <div style="font-weight:700;font-size:.85rem;color:<?= !empty($vehicle['stnk_file'])?'var(--blue)':'var(--gray-400)' ?>">STNK</div>
              <div style="font-size:.72rem;color:var(--gray-400)">Surat Tanda Nomor Kendaraan</div>
            </div>
            <?php if (!empty($vehicle['stnk_file'])): ?>
              <span style="background:var(--blue);color:#fff;padding:2px 7px;border-radius:4px;font-size:.65rem;font-weight:700">✓ ADA</span>
            <?php else: ?>
              <span style="background:var(--gray-200);color:var(--gray-500);padding:2px 7px;border-radius:4px;font-size:.65rem;font-weight:700">BELUM</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($vehicle['stnk_file'])): ?>
            <?php $ext = strtolower(pathinfo($vehicle['stnk_file'], PATHINFO_EXTENSION)); ?>
            <?php if (in_array($ext, ['jpg','jpeg','png','webp'])): ?>
              <div style="padding:8px;background:#fff">
                <img src="<?= adminUrl('/'.ltrim($vehicle['stnk_file'],'/')) ?>"
                  style="width:100%;max-height:160px;object-fit:cover;border-radius:6px;cursor:pointer"
                  onclick="window.open(this.src,'_blank')">
              </div>
            <?php endif; ?>
            <div style="padding:8px 14px;display:flex;gap:6px">
              <a href="<?= adminUrl('/'.ltrim($vehicle['stnk_file'],'/')) ?>" target="_blank"
                 class="btn btn-info btn-xs" style="flex:1;justify-content:center">
                <i class="fa-solid fa-eye"></i> Lihat
              </a>
              <a href="<?= adminUrl('/'.ltrim($vehicle['stnk_file'],'/')) ?>" download
                 class="btn btn-outline btn-xs" style="flex:1;justify-content:center">
                <i class="fa-solid fa-download"></i> Unduh
              </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- BPKB -->
        <div style="border:1.5px solid <?= !empty($vehicle['bpkb_file'])?'var(--purple)':'var(--gray-200)' ?>;border-radius:10px;overflow:hidden">
          <div style="padding:10px 14px;background:<?= !empty($vehicle['bpkb_file'])?'var(--purple-light)':'var(--gray-50)' ?>;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-book" style="color:<?= !empty($vehicle['bpkb_file'])?'var(--purple)':'var(--gray-300)' ?>;font-size:1.1rem"></i>
            <div style="flex:1">
              <div style="font-weight:700;font-size:.85rem;color:<?= !empty($vehicle['bpkb_file'])?'var(--purple)':'var(--gray-400)' ?>">BPKB</div>
              <div style="font-size:.72rem;color:var(--gray-400)">Buku Pemilik Kendaraan Bermotor</div>
            </div>
            <?php if (!empty($vehicle['bpkb_file'])): ?>
              <span style="background:var(--purple);color:#fff;padding:2px 7px;border-radius:4px;font-size:.65rem;font-weight:700">✓ ADA</span>
            <?php else: ?>
              <span style="background:var(--gray-200);color:var(--gray-500);padding:2px 7px;border-radius:4px;font-size:.65rem;font-weight:700">BELUM</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($vehicle['bpkb_file'])): ?>
            <?php $ext = strtolower(pathinfo($vehicle['bpkb_file'], PATHINFO_EXTENSION)); ?>
            <?php if (in_array($ext, ['jpg','jpeg','png','webp'])): ?>
              <div style="padding:8px;background:#fff">
                <img src="<?= adminUrl('/'.ltrim($vehicle['bpkb_file'],'/')) ?>"
                  style="width:100%;max-height:160px;object-fit:cover;border-radius:6px;cursor:pointer"
                  onclick="window.open(this.src,'_blank')">
              </div>
            <?php endif; ?>
            <div style="padding:8px 14px;display:flex;gap:6px">
              <a href="<?= adminUrl('/'.ltrim($vehicle['bpkb_file'],'/')) ?>" target="_blank"
                 class="btn btn-purple btn-xs" style="flex:1;justify-content:center">
                <i class="fa-solid fa-eye"></i> Lihat
              </a>
              <a href="<?= adminUrl('/'.ltrim($vehicle['bpkb_file'],'/')) ?>" download
                 class="btn btn-outline btn-xs" style="flex:1;justify-content:center">
                <i class="fa-solid fa-download"></i> Unduh
              </a>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>

  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
