<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pemesanan — TravelKu</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;font-size:11px;color:#222;padding:20px}
.header{text-align:center;border-bottom:2px solid #0F1B2D;padding-bottom:12px;margin-bottom:16px}
.header h1{font-size:18px;color:#0F1B2D}.header p{color:#64748B;font-size:10px;margin-top:3px}
.period{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:4px;padding:8px 12px;margin-bottom:14px;font-size:10px;color:#475569}
table{width:100%;border-collapse:collapse;margin-bottom:14px;font-size:10px}
th{background:#0F1B2D;color:#fff;padding:6px 8px;text-align:left;font-size:9px;text-transform:uppercase}
td{padding:6px 8px;border-bottom:1px solid #F1F5F9}
tr:nth-child(even) td{background:#F8FAFC}
.status-paid{color:#065f46;font-weight:700}
.status-pending{color:#92400e;font-weight:700}
.status-cancelled{color:#991b1b;font-weight:700}
.footer{margin-top:16px;text-align:center;font-size:9px;color:#94A3B8;border-top:1px solid #E2E8F0;padding-top:8px}
@media print{.no-print{display:none}}
</style>
</head>
<body>
<div class="no-print" style="margin-bottom:12px">
  <button onclick="window.print()" style="background:#0F1B2D;color:#fff;border:none;padding:7px 16px;border-radius:4px;cursor:pointer;font-size:11px">🖨️ Cetak</button>
  <a href="javascript:history.back()" style="margin-left:10px;color:#64748B;font-size:11px">← Kembali</a>
</div>

<div class="header">
  <h1>🚌 Falles Travel — Laporan Pemesanan</h1>
  <p>Periode: <?= htmlspecialchars($from) ?> s/d <?= htmlspecialchars($to) ?> &nbsp;|&nbsp; Dicetak: <?= date('d/m/Y H:i') ?></p>
</div>

<table>
  <thead>
    <tr>
      <th>Kode Booking</th>
      <th>Tanggal Pesan</th>
      <th>Nama</th>
      <th>Telepon</th>
      <th>Rute</th>
      <th>Berangkat</th>
      <th>Kendaraan</th>
      <th>Pnp</th>
      <th>Total</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($bookings)): ?>
    <tr><td colspan="10" style="text-align:center;padding:20px;color:#94A3B8">Tidak ada data</td></tr>
    <?php else: ?>
    <?php foreach ($bookings as $b): ?>
    <tr>
      <td style="font-weight:700;font-size:9px"><?= htmlspecialchars($b['booking_code']) ?></td>
      <td><?= date('d/m/Y', strtotime($b['created_at'])) ?></td>
      <td><?= htmlspecialchars($b['contact_name']) ?></td>
      <td><?= htmlspecialchars($b['contact_phone']) ?></td>
      <td><?= htmlspecialchars($b['origin']) ?> → <?= htmlspecialchars($b['destination']) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($b['depart_at'])) ?></td>
      <td><?= htmlspecialchars($b['kendaraan']) ?></td>
      <td style="text-align:center"><?= $b['passenger_count'] ?></td>
      <td style="font-weight:700">Rp <?= number_format($b['total_price'], 0, ',', '.') ?></td>
      <td class="status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<div class="footer">
  Total <?= count($bookings) ?> data | Dokumen digenerate otomatis oleh Falles Travel pada <?= date('d/m/Y H:i:s') ?>
</div>
</body>
</html>
