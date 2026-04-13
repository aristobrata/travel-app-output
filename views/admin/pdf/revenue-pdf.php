<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pendapatan — TravelKu</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;font-size:12px;color:#222;padding:24px}
.header{text-align:center;border-bottom:2px solid #0F1B2D;padding-bottom:14px;margin-bottom:20px}
.header h1{font-size:20px;color:#0F1B2D}
.header p{color:#64748B;font-size:11px;margin-top:4px}
.period{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;padding:10px 14px;margin-bottom:18px;font-size:11px;color:#475569}
.stats{display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.stat-box{flex:1;min-width:130px;border:1px solid #E2E8F0;border-radius:6px;padding:10px 12px;background:#fff}
.stat-box .label{font-size:9px;text-transform:uppercase;letter-spacing:.06em;color:#94A3B8;margin-bottom:3px}
.stat-box .value{font-size:15px;font-weight:700;color:#0F1B2D}
table{width:100%;border-collapse:collapse;margin-bottom:18px}
th{background:#0F1B2D;color:#fff;padding:7px 10px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:.05em}
td{padding:7px 10px;border-bottom:1px solid #F1F5F9;font-size:11px}
tr:nth-child(even) td{background:#F8FAFC}
.total-row td{font-weight:700;background:#FEF3C7;border-top:2px solid #F59E0B}
.footer{margin-top:20px;text-align:center;font-size:10px;color:#94A3B8;border-top:1px solid #E2E8F0;padding-top:10px}
@media print{body{padding:10px}.no-print{display:none}}
</style>
</head>
<body>
<div class="no-print" style="margin-bottom:16px">
  <button onclick="window.print()" style="background:#0F1B2D;color:#fff;border:none;padding:8px 18px;border-radius:5px;cursor:pointer;font-size:12px">
    🖨️ Cetak / Simpan PDF
  </button>
  <a href="javascript:history.back()" style="margin-left:10px;color:#64748B;font-size:12px">← Kembali</a>
</div>

<div class="header">
  <h1>🚌 Falles Travel</h1>
  <p>Laporan Pendapatan</p>
</div>

<div class="period">
  📅 Periode: <strong><?= htmlspecialchars($from) ?></strong> s/d <strong><?= htmlspecialchars($to) ?></strong>
  &nbsp;&nbsp;|&nbsp;&nbsp; Dicetak: <?= date('d/m/Y H:i') ?> WIB
</div>

<?php
$totalPendapatan = array_sum(array_column($daily, 'pendapatan'));
$totalBooking = array_sum(array_column($daily, 'total_booking'));
$totalLunas = array_sum(array_column($daily, 'booking_lunas'));
?>

<div class="stats">
  <div class="stat-box">
    <div class="label">Total Pendapatan</div>
    <div class="value">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></div>
  </div>
  <div class="stat-box">
    <div class="label">Total Booking</div>
    <div class="value"><?= $totalBooking ?></div>
  </div>
  <div class="stat-box">
    <div class="label">Booking Lunas</div>
    <div class="value"><?= $totalLunas ?></div>
  </div>
  <div class="stat-box">
    <div class="label">Rata-rata/Hari</div>
    <div class="value">Rp <?= count($daily) > 0 ? number_format($totalPendapatan / count($daily), 0, ',', '.') : '0' ?></div>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Total Booking</th>
      <th>Lunas</th>
      <th>Dibatalkan</th>
      <th>Pendapatan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($daily as $d): ?>
    <tr>
      <td><?= date('d F Y', strtotime($d['tanggal'])) ?></td>
      <td><?= $d['total_booking'] ?></td>
      <td><?= $d['booking_lunas'] ?></td>
      <td><?= $d['booking_batal'] ?></td>
      <td>Rp <?= number_format($d['pendapatan'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td>TOTAL</td>
      <td><?= $totalBooking ?></td>
      <td><?= $totalLunas ?></td>
      <td><?= array_sum(array_column($daily, 'booking_batal')) ?></td>
      <td>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
    </tr>
  </tfoot>
</table>

<div class="footer">
  Dokumen ini digenerate otomatis oleh sistem TravelKu pada <?= date('d/m/Y H:i:s') ?>
</div>
</body>
</html>
