<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Servis Kendaraan</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; }
.header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0F1B2D; padding-bottom: 12px; }
.header h1 { font-size: 18px; color: #0F1B2D; }
.header p { color: #555; font-size: 11px; margin-top: 4px; }
.summary-box { display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
.sum-item { flex: 1; min-width: 120px; background: #f5f5f5; border-left: 4px solid #3B82F6; padding: 10px 14px; border-radius: 4px; }
.sum-item .label { font-size: 10px; color: #777; text-transform: uppercase; }
.sum-item .value { font-size: 15px; font-weight: 700; color: #0F1B2D; margin-top: 2px; }
.type-table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
.type-table th { background: #3B82F6; color: #fff; padding: 6px 10px; font-size: 10px; text-align: left; }
.type-table td { padding: 5px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
.type-table tr:nth-child(even) td { background: #f0f4ff; }
table.main { width: 100%; border-collapse: collapse; margin-top: 10px; }
table.main thead th { background: #0F1B2D; color: #fff; padding: 7px 9px; font-size: 10px; text-transform: uppercase; letter-spacing: .04em; }
table.main tbody tr:nth-child(even) { background: #f9f9f9; }
table.main tbody td { padding: 6px 9px; border-bottom: 1px solid #eee; vertical-align: top; font-size: 10.5px; }
table.main tfoot td { background: #DBEAFE; font-weight: 700; padding: 7px 9px; border-top: 2px solid #3B82F6; }
.status-badge { display:inline-block;padding:1px 7px;border-radius:20px;font-size:10px;font-weight:600; }
.footer { margin-top: 24px; font-size: 10px; color: #aaa; text-align: center; }
.section-title { font-size: 12px; font-weight: 700; color: #0F1B2D; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #ddd; }
@media print { body { padding: 0; } .no-print { display: none; } }
</style>
</head>
<body>
<?php
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$fromFmt = $from ? date('d/m/Y', strtotime($from)) : '-';
$toFmt   = $to   ? date('d/m/Y', strtotime($to))   : '-';
$typeLabels = [
    'oli'=>'Ganti Oli','tune_up'=>'Tune Up','ban'=>'Ganti Ban','rem'=>'Servis Rem',
    'ac'=>'Servis AC','mesin'=>'Perbaikan Mesin','bodi'=>'Perbaikan Bodi',
    'kaki_kaki'=>'Kaki-Kaki','lainnya'=>'Lainnya',
];
$statusLabel = ['selesai'=>'Selesai','dalam_servis'=>'Dalam Servis','dijadwalkan'=>'Dijadwalkan'];
$statusColor = ['selesai'=>'#D1FAE5;color:#059669','dalam_servis'=>'#FEF3C7;color:#D97706','dijadwalkan'=>'#DBEAFE;color:#2563EB'];
?>

<div class="header">
  <h1>Laporan Servis Kendaraan</h1>
  <p>Periode: <?= $fromFmt ?> &ndash; <?= $toFmt ?> &nbsp;|&nbsp; Dicetak: <?= date('d/m/Y H:i') ?></p>
</div>

<div class="summary-box">
  <div class="sum-item">
    <div class="label">Total Catatan Servis</div>
    <div class="value"><?= count($records) ?></div>
  </div>
  <div class="sum-item" style="border-color:#0F1B2D">
    <div class="label">Total Biaya Servis</div>
    <div class="value"><?= $idr($total) ?></div>
  </div>
</div>

<?php if (!empty($typeStats)): ?>
<div class="section-title">Rekap Per Jenis Servis</div>
<table class="type-table">
  <thead><tr><th>Jenis Servis</th><th>Jumlah</th><th>Total Biaya</th></tr></thead>
  <tbody>
    <?php foreach ($typeStats as $ts): ?>
    <tr>
      <td><?= $typeLabels[$ts['service_type']] ?? $ts['service_type'] ?></td>
      <td><?= $ts['cnt'] ?>x</td>
      <td style="font-weight:600"><?= $idr($ts['total']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<div class="section-title">Detail Seluruh Servis</div>
<table class="main">
  <thead>
    <tr>
      <th>#</th>
      <th>Tanggal</th>
      <th>Kendaraan</th>
      <th>Jenis Servis</th>
      <th>Bengkel</th>
      <th>Biaya</th>
      <th>Odometer</th>
      <th>Servis Berikutnya</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($records)): ?>
      <tr><td colspan="9" style="text-align:center;color:#aaa;padding:16px">Tidak ada data.</td></tr>
    <?php else: ?>
      <?php $no = 1; foreach ($records as $r): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td style="white-space:nowrap"><?= date('d/m/Y', strtotime($r['service_date'])) ?></td>
        <td>
          <strong><?= htmlspecialchars($r['vehicle_name']) ?></strong><br>
          <span style="color:#888;font-size:10px"><?= htmlspecialchars($r['plate_number']) ?></span>
        </td>
        <td><?= $typeLabels[$r['service_type']] ?? $r['service_type'] ?></td>
        <td><?= htmlspecialchars($r['workshop'] ?? '-') ?></td>
        <td style="font-weight:600"><?= $idr($r['cost']) ?></td>
        <td><?= $r['odometer_km'] ? number_format($r['odometer_km']).' km' : '-' ?></td>
        <td style="font-size:10px"><?= $r['next_service_date'] ? date('d/m/Y', strtotime($r['next_service_date'])) : '-' ?></td>
        <td><span class="status-badge" style="background:<?= $statusColor[$r['status']] ?? '#eee;color:#888' ?>"><?= $statusLabel[$r['status']] ?? $r['status'] ?></span></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="text-align:right">TOTAL (<?= count($records) ?> servis)</td>
      <td><?= $idr($total) ?></td>
      <td colspan="3"></td>
    </tr>
  </tfoot>
</table>

<div class="footer">Laporan ini digenerate otomatis oleh sistem Falles Travel &mdash; <?= date('d/m/Y H:i:s') ?></div>

<div class="no-print" style="margin-top:20px;text-align:center">
  <button onclick="window.print()" style="padding:8px 20px;background:#0F1B2D;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px">
    🖨️ Cetak / Simpan PDF
  </button>
  <a href="javascript:history.back()" style="margin-left:12px;font-size:12px;color:#555">← Kembali</a>
</div>
</body>
</html>
