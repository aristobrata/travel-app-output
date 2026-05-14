<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Bensin Per Trip</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; }
.header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0F1B2D; padding-bottom: 12px; }
.header h1 { font-size: 18px; color: #0F1B2D; }
.header p { color: #555; font-size: 11px; margin-top: 4px; }
.summary-box { display: flex; gap: 16px; margin-bottom: 16px; }
.sum-item { flex: 1; background: #f5f5f5; border-left: 4px solid #F59E0B; padding: 10px 14px; border-radius: 4px; }
.sum-item .label { font-size: 10px; color: #777; text-transform: uppercase; letter-spacing: .05em; }
.sum-item .value { font-size: 16px; font-weight: 700; color: #0F1B2D; margin-top: 2px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
thead th { background: #0F1B2D; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; }
tbody tr:nth-child(even) { background: #f9f9f9; }
tbody td { padding: 7px 10px; border-bottom: 1px solid #eee; vertical-align: top; }
tfoot td { background: #FEF3C7; font-weight: 700; padding: 8px 10px; border-top: 2px solid #F59E0B; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; background: #FEF3C7; color: #D97706; }
.footer { margin-top: 24px; font-size: 10px; color: #aaa; text-align: center; }
@media print { body { padding: 0; } .no-print { display: none; } }
</style>
</head>
<body>
<?php
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$fromFmt = $from ? date('d/m/Y', strtotime($from)) : '-';
$toFmt   = $to   ? date('d/m/Y', strtotime($to))   : '-';
$totalLiter = array_sum(array_column($expenses, 'fuel_liters'));
?>

<div class="header">
  <h1>Laporan Pengeluaran Bensin Per Trip</h1>
  <p>Periode: <?= $fromFmt ?> &ndash; <?= $toFmt ?> &nbsp;|&nbsp; Dicetak: <?= date('d/m/Y H:i') ?></p>
</div>

<div class="summary-box">
  <div class="sum-item">
    <div class="label">Total Trip</div>
    <div class="value"><?= count($expenses) ?></div>
  </div>
  <div class="sum-item">
    <div class="label">Total Liter</div>
    <div class="value"><?= number_format($totalLiter, 1) ?> L</div>
  </div>
  <div class="sum-item" style="border-color:#0F1B2D">
    <div class="label">Total Biaya BBM</div>
    <div class="value"><?= $idr($total) ?></div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Tanggal</th>
      <th>Kendaraan</th>
      <th>Supir</th>
      <th>Rute</th>
      <th>Liter</th>
      <th>Biaya BBM</th>
      <th>Odometer</th>
      <th>Catatan</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($expenses)): ?>
      <tr><td colspan="9" style="text-align:center;color:#aaa;padding:20px">Tidak ada data.</td></tr>
    <?php else: ?>
      <?php $no = 1; foreach ($expenses as $e): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td style="white-space:nowrap"><?= date('d/m/Y', strtotime($e['trip_date'])) ?></td>
        <td>
          <strong><?= htmlspecialchars($e['vehicle_name'] ?? '-') ?></strong><br>
          <span style="color:#888;font-size:10px"><?= htmlspecialchars($e['plate_number'] ?? '') ?></span>
        </td>
        <td><?= htmlspecialchars($e['driver_name'] ?? '-') ?></td>
        <td style="font-size:10px"><?= htmlspecialchars(($e['origin'] ?? '') . ($e['destination'] ? ' → ' . $e['destination'] : '')) ?></td>
        <td><?= $e['fuel_liters'] ? number_format($e['fuel_liters'], 1) . ' L' : '-' ?></td>
        <td style="font-weight:600"><?= $idr($e['fuel_price']) ?></td>
        <td><?= $e['odometer_km'] ? number_format($e['odometer_km']) . ' km' : '-' ?></td>
        <td style="font-size:10px;color:#555"><?= htmlspecialchars($e['notes'] ?? '') ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="text-align:right">TOTAL (<?= count($expenses) ?> trip)</td>
      <td><?= number_format($totalLiter, 1) ?> L</td>
      <td><?= $idr($total) ?></td>
      <td colspan="2"></td>
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
