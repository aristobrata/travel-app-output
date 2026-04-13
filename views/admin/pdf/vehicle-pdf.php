<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Kendaraan — TravelKu</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;font-size:12px;color:#222;padding:24px}
.header{text-align:center;border-bottom:2px solid #0F1B2D;padding-bottom:12px;margin-bottom:16px}
.header h1{font-size:18px;color:#0F1B2D}.header p{color:#64748B;font-size:10px;margin-top:3px}
table{width:100%;border-collapse:collapse}
th{background:#0F1B2D;color:#fff;padding:8px 10px;text-align:left;font-size:10px;text-transform:uppercase}
td{padding:8px 10px;border-bottom:1px solid #F1F5F9}
tr:nth-child(even) td{background:#F8FAFC}
.footer{margin-top:16px;text-align:center;font-size:10px;color:#94A3B8;border-top:1px solid #E2E8F0;padding-top:8px}
@media print{.no-print{display:none}}
</style>
</head>
<body>
<div class="no-print" style="margin-bottom:12px">
  <button onclick="window.print()" style="background:#0F1B2D;color:#fff;border:none;padding:7px 16px;border-radius:4px;cursor:pointer">🖨️ Cetak</button>
  <a href="javascript:history.back()" style="margin-left:10px;color:#64748B">← Kembali</a>
</div>
<div class="header">
  <h1>🚌 Falles Travel — Laporan Kendaraan</h1>
  <p>Periode: <?= htmlspecialchars($from) ?> s/d <?= htmlspecialchars($to) ?> &nbsp;|&nbsp; Dicetak: <?= date('d/m/Y H:i') ?></p>
</div>
<table>
  <thead>
    <tr>
      <th>#</th><th>Kendaraan</th><th>Plat</th><th>Jenis</th><th>Jadwal</th><th>Booking</th><th>Pendapatan</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($vehicles)): ?>
    <tr><td colspan="7" style="text-align:center;padding:20px;color:#94A3B8">Tidak ada data</td></tr>
    <?php else: ?>
    <?php foreach ($vehicles as $i => $v): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td style="font-weight:700"><?= htmlspecialchars($v['kendaraan']) ?></td>
      <td><?= htmlspecialchars($v['plate_number']) ?></td>
      <td><?= ucfirst($v['jenis'] ?? '—') ?></td>
      <td><?= $v['total_jadwal'] ?></td>
      <td><?= $v['total_booking'] ?></td>
      <td style="font-weight:700">Rp <?= number_format($v['pendapatan'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
<div class="footer">Dokumen digenerate otomatis oleh Falles Travel pada <?= date('d/m/Y H:i:s') ?></div>
</body>
</html>
