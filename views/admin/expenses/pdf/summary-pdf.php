<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Ringkasan Pengeluaran</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Arial',sans-serif; font-size:11px; color:#1E293B; background:#fff; }
.page { padding:28px 32px; max-width:860px; margin:0 auto; }
.header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:14px; border-bottom:3px solid #0F1B2D; }
.company { font-size:18px; font-weight:700; color:#0F1B2D; }
.company small { display:block; font-size:10px; font-weight:400; color:#64748B; margin-top:2px; }
.report-title { text-align:right; }
.report-title h2 { font-size:14px; font-weight:700; color:#0F1B2D; }
.report-title p  { font-size:10px; color:#64748B; margin-top:2px; }
.summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:20px; }
.s-card { border:1px solid #E2E8F0; border-radius:6px; padding:12px 14px; }
.s-card .s-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94A3B8; }
.s-card .s-value { font-size:14px; font-weight:700; color:#0F1B2D; margin-top:4px; }
.s-card.amber  { border-color:#F59E0B; background:#FEF3C7; } .s-card.amber .s-value  { color:#92400E; }
.s-card.blue   { border-color:#3B82F6; background:#DBEAFE; } .s-card.blue .s-value   { color:#1E40AF; }
.s-card.green  { border-color:#10B981; background:#D1FAE5; } .s-card.green .s-value  { color:#065F46; }
.s-card.navy   { border-color:#0F1B2D; background:#0F1B2D; } .s-card.navy .s-value   { color:#F59E0B; } .s-card.navy .s-label { color:rgba(255,255,255,.5); }
.section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#0F1B2D; margin:18px 0 8px; padding:6px 12px; background:#F1F5F9; border-left:3px solid #F59E0B; }
table { width:100%; border-collapse:collapse; font-size:10px; margin-bottom:6px; }
thead tr { background:#1A2D45; color:#fff; }
thead th { padding:7px 10px; text-align:left; font-weight:700; font-size:9px; text-transform:uppercase; letter-spacing:.05em; }
tbody tr:nth-child(even) { background:#F8FAFC; }
tbody td { padding:6px 10px; border-bottom:1px solid #E2E8F0; }
.text-right  { text-align:right; }
.text-center { text-align:center; }
.tfoot-row td { background:#0F1B2D; color:#fff; font-weight:700; padding:7px 10px; }
.grand-total { margin-top:16px; background:#0F1B2D; color:#fff; border-radius:6px; padding:14px 20px; display:flex; justify-content:space-between; align-items:center; }
.grand-total .gt-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.7; }
.grand-total .gt-value { font-size:18px; font-weight:700; color:#F59E0B; }
.footer { margin-top:20px; padding-top:12px; border-top:1px solid #E2E8F0; display:flex; justify-content:space-between; font-size:9px; color:#94A3B8; }
@media print { @page { margin:1cm; size:A4; } .no-print { display:none; } }
</style>
</head>
<body>
<?php
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$svcTypes = ['oli'=>'Ganti Oli','tune_up'=>'Tune Up','ban'=>'Ganti Ban','rem'=>'Servis Rem','ac'=>'Servis AC','mesin'=>'Perbaikan Mesin','bodi'=>'Perbaikan Bodi','kaki_kaki'=>'Kaki-Kaki','lainnya'=>'Lainnya'];
$months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<div class="page">
  <div class="header">
    <div class="company">
      Falles Travel
      <small>Laporan Ringkasan Pengeluaran Operasional</small>
    </div>
    <div class="report-title">
      <h2>Ringkasan Pengeluaran</h2>
      <p>Periode: <?= date('d/m/Y', strtotime($from)) ?> – <?= date('d/m/Y', strtotime($to)) ?></p>
      <p>Dicetak: <?= date('d/m/Y H:i') ?></p>
    </div>
  </div>

  <!-- SUMMARY CARDS -->
  <div class="summary-grid">
    <div class="s-card amber">
      <div class="s-label">Total Bensin</div>
      <div class="s-value"><?= $idr($fuelTotal) ?></div>
    </div>
    <div class="s-card blue">
      <div class="s-label">Total Servis</div>
      <div class="s-value"><?= $idr($serviceTotal) ?></div>
    </div>
    <div class="s-card green">
      <div class="s-label">Total Gaji Supir</div>
      <div class="s-value"><?= $idr($salaryTotal) ?></div>
    </div>
    <div class="s-card navy">
      <div class="s-label">GRAND TOTAL</div>
      <div class="s-value"><?= $idr($grandTotal) ?></div>
    </div>
  </div>

  <!-- BENSIN -->
  <div class="section-title">⛽ Pengeluaran Bensin (<?= count($fuelList) ?> trip)</div>
  <table>
    <thead><tr><th>#</th><th>Tanggal</th><th>Kendaraan</th><th>Supir</th><th>Rute</th><th class="text-right">Liter</th><th class="text-right">Biaya</th></tr></thead>
    <tbody>
      <?php if (empty($fuelList)): ?><tr><td colspan="7" style="text-align:center;padding:14px;color:#94A3B8">Tidak ada data bensin</td></tr>
      <?php else: foreach ($fuelList as $i => $f): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= date('d/m/Y', strtotime($f['trip_date'])) ?></td>
        <td><?= htmlspecialchars($f['vehicle_name'] ?? '-') ?> (<?= $f['plate_number'] ?? '' ?>)</td>
        <td><?= htmlspecialchars($f['driver_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars(($f['origin'] ?? '') . ($f['destination'] ? ' → '.$f['destination'] : '')) ?></td>
        <td class="text-right"><?= $f['fuel_liters'] ? number_format($f['fuel_liters'],1).' L' : '-' ?></td>
        <td class="text-right" style="font-weight:600"><?= $idr($f['fuel_price']) ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
    <tfoot><tr class="tfoot-row"><td colspan="6">TOTAL BENSIN</td><td class="text-right"><?= $idr($fuelTotal) ?></td></tr></tfoot>
  </table>

  <!-- SERVIS -->
  <div class="section-title">🔧 Biaya Servis Kendaraan (<?= count($serviceList) ?> catatan)</div>
  <table>
    <thead><tr><th>#</th><th>Tanggal</th><th>Kendaraan</th><th>Jenis Servis</th><th>Bengkel</th><th>Status</th><th class="text-right">Biaya</th></tr></thead>
    <tbody>
      <?php if (empty($serviceList)): ?><tr><td colspan="7" style="text-align:center;padding:14px;color:#94A3B8">Tidak ada data servis</td></tr>
      <?php else: foreach ($serviceList as $i => $s): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= date('d/m/Y', strtotime($s['service_date'])) ?></td>
        <td><?= htmlspecialchars($s['vehicle_name']) ?> (<?= $s['plate_number'] ?>)</td>
        <td><?= $svcTypes[$s['service_type']] ?? $s['service_type'] ?></td>
        <td><?= htmlspecialchars($s['workshop'] ?? '-') ?></td>
        <td><?= ucfirst(str_replace('_',' ',$s['status'])) ?></td>
        <td class="text-right" style="font-weight:600"><?= $idr($s['cost']) ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
    <tfoot><tr class="tfoot-row"><td colspan="6">TOTAL SERVIS</td><td class="text-right"><?= $idr($serviceTotal) ?></td></tr></tfoot>
  </table>

  <!-- GAJI -->
  <div class="section-title">💰 Gaji Supir (<?= count($salaryList) ?> data)</div>
  <table>
    <thead><tr><th>#</th><th>Supir</th><th>Periode</th><th class="text-right">Gaji Pokok</th><th class="text-right">Bonus</th><th class="text-right">Potongan</th><th class="text-right">Gaji Bersih</th><th class="text-center">Status</th></tr></thead>
    <tbody>
      <?php if (empty($salaryList)): ?><tr><td colspan="8" style="text-align:center;padding:14px;color:#94A3B8">Tidak ada data gaji</td></tr>
      <?php else: foreach ($salaryList as $i => $sal): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td style="font-weight:600"><?= htmlspecialchars($sal['driver_name']) ?></td>
        <td><?= $months[(int)$sal['period_month']] ?> <?= $sal['period_year'] ?></td>
        <td class="text-right"><?= $idr($sal['base_salary']) ?></td>
        <td class="text-right"><?= $sal['bonus'] > 0 ? $idr($sal['bonus']) : '-' ?></td>
        <td class="text-right"><?= $sal['deduction'] > 0 ? $idr($sal['deduction']) : '-' ?></td>
        <td class="text-right" style="font-weight:700"><?= $idr($sal['net_salary']) ?></td>
        <td class="text-center"><?= $sal['status'] === 'paid' ? '✅ Lunas' : '📋 Draft' ?></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
    <tfoot><tr class="tfoot-row"><td colspan="6">TOTAL GAJI BERSIH</td><td class="text-right"><?= $idr($salaryTotal) ?></td><td></td></tr></tfoot>
  </table>

  <!-- GRAND TOTAL -->
  <div class="grand-total">
    <div class="gt-label">TOTAL SELURUH PENGELUARAN</div>
    <div class="gt-value"><?= $idr($grandTotal) ?></div>
  </div>

  <div class="footer">
    <span>Falles Travel &mdash; Laporan Ringkasan Pengeluaran Operasional</span>
    <span>Dicetak pada <?= date('d F Y, H:i') ?> WIB</span>
  </div>
</div>

<div class="no-print" style="text-align:center;padding:16px">
  <button onclick="window.print()" style="background:#0F1B2D;color:#fff;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:13px">
    🖨️ Cetak / Simpan PDF
  </button>
  &nbsp;
  <button onclick="window.history.back()" style="background:#F1F5F9;color:#334155;border:1px solid #CBD5E1;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:13px">
    ← Kembali
  </button>
</div>
</body>
</html>
