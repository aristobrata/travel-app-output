<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Gaji Supir</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Arial', sans-serif; font-size: 11px; color: #1E293B; background:#fff; }
.page { padding: 28px 32px; max-width: 900px; margin: 0 auto; }
.header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:14px; border-bottom:3px solid #0F1B2D; }
.company { font-size:18px; font-weight:700; color:#0F1B2D; }
.company small { display:block; font-size:10px; font-weight:400; color:#64748B; margin-top:2px; }
.report-title { text-align:right; }
.report-title h2 { font-size:14px; font-weight:700; color:#0F1B2D; }
.report-title p  { font-size:10px; color:#64748B; margin-top:3px; }
.meta-row { display:flex; gap:40px; background:#F1F5F9; border-radius:6px; padding:12px 16px; margin-bottom:18px; }
.meta-row .meta-item label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94A3B8; display:block; }
.meta-row .meta-item span  { font-size:13px; font-weight:700; color:#0F1B2D; }
.summary-cards { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:18px; }
.s-card { border:1px solid #E2E8F0; border-radius:6px; padding:10px 14px; }
.s-card .s-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94A3B8; }
.s-card .s-value { font-size:13px; font-weight:700; color:#0F1B2D; margin-top:3px; }
.s-card.green { border-color:#10B981; background:#D1FAE5; }
.s-card.green .s-value { color:#065F46; }
table { width:100%; border-collapse:collapse; font-size:10px; }
thead tr { background:#0F1B2D; color:#fff; }
thead th { padding:8px 10px; text-align:left; font-weight:700; font-size:9px; text-transform:uppercase; letter-spacing:.05em; }
tbody tr:nth-child(even) { background:#F8FAFC; }
tbody tr:last-child td { border-bottom:2px solid #0F1B2D; }
tbody td { padding:7px 10px; border-bottom:1px solid #E2E8F0; vertical-align:middle; }
.text-right { text-align:right; }
.text-center { text-align:center; }
.badge-paid  { display:inline-block; padding:2px 8px; border-radius:20px; background:#D1FAE5; color:#065F46; font-weight:700; font-size:9px; }
.badge-draft { display:inline-block; padding:2px 8px; border-radius:20px; background:#FEF3C7; color:#92400E; font-weight:700; font-size:9px; }
.tfoot td { background:#1A2D45; color:#fff; font-weight:700; font-size:11px; padding:9px 10px; }
.footer { margin-top:24px; padding-top:12px; border-top:1px solid #E2E8F0; display:flex; justify-content:space-between; font-size:9px; color:#94A3B8; }
@media print { @page { margin:1cm; size:A4 landscape; } .no-print { display:none; } }
</style>
</head>
<body>
<?php
$idr = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
$months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$totalBase    = array_sum(array_column($salaries, 'base_salary'));
$totalBonus   = array_sum(array_column($salaries, 'bonus'));
$totalDeduct  = array_sum(array_column($salaries, 'deduction'));
$countPaid    = count(array_filter($salaries, fn($s) => $s['status'] === 'paid'));
?>
<div class="page">
  <div class="header">
    <div class="company">
      Falles Travel
      <small>Sistem Manajemen Pengeluaran Operasional</small>
    </div>
    <div class="report-title">
      <h2>Laporan Gaji Supir</h2>
      <p>Periode: <?= $months[$month] ?? '-' ?> <?= $year ?></p>
      <p>Dicetak: <?= date('d/m/Y H:i') ?></p>
    </div>
  </div>

  <div class="meta-row">
    <div class="meta-item"><label>Periode</label><span><?= $months[$month] ?? 'Semua' ?> <?= $year ?></span></div>
    <div class="meta-item"><label>Total Supir</label><span><?= count($salaries) ?></span></div>
    <div class="meta-item"><label>Sudah Dibayar</label><span><?= $countPaid ?></span></div>
    <div class="meta-item"><label>Belum Dibayar</label><span><?= count($salaries) - $countPaid ?></span></div>
  </div>

  <div class="summary-cards">
    <div class="s-card">
      <div class="s-label">Total Gaji Pokok</div>
      <div class="s-value"><?= $idr($totalBase) ?></div>
    </div>
    <div class="s-card">
      <div class="s-label">Total Bonus</div>
      <div class="s-value"><?= $idr($totalBonus) ?></div>
    </div>
    <div class="s-card">
      <div class="s-label">Total Potongan</div>
      <div class="s-value"><?= $idr($totalDeduct) ?></div>
    </div>
    <div class="s-card green">
      <div class="s-label">Total Gaji Bersih</div>
      <div class="s-value"><?= $idr($total) ?></div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Supir</th>
        <th>Telepon</th>
        <th>Periode</th>
        <th class="text-right">Gaji Pokok</th>
        <th class="text-right">Bonus</th>
        <th class="text-right">Potongan</th>
        <th class="text-right">Gaji Bersih</th>
        <th class="text-center">Trip</th>
        <th>Tgl Bayar</th>
        <th class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($salaries)): ?>
        <tr><td colspan="11" style="text-align:center;padding:20px;color:#94A3B8">Tidak ada data gaji</td></tr>
      <?php else: ?>
        <?php foreach ($salaries as $i => $s): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($s['driver_name']) ?></td>
          <td><?= htmlspecialchars($s['driver_phone'] ?? '-') ?></td>
          <td><?= $months[(int)$s['period_month']] ?> <?= $s['period_year'] ?></td>
          <td class="text-right"><?= $idr($s['base_salary']) ?></td>
          <td class="text-right" style="color:#065F46"><?= $s['bonus'] > 0 ? $idr($s['bonus']) : '-' ?></td>
          <td class="text-right" style="color:#991B1B"><?= $s['deduction'] > 0 ? $idr($s['deduction']) : '-' ?></td>
          <td class="text-right" style="font-weight:700"><?= $idr($s['net_salary']) ?></td>
          <td class="text-center"><?= $s['trip_count'] ?></td>
          <td><?= $s['paid_at'] ? date('d/m/Y', strtotime($s['paid_at'])) : '-' ?></td>
          <td class="text-center">
            <?php if ($s['status'] === 'paid'): ?>
              <span class="badge-paid">Lunas</span>
            <?php else: ?>
              <span class="badge-draft">Draft</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4">TOTAL</td>
        <td class="text-right"><?= $idr($totalBase) ?></td>
        <td class="text-right"><?= $idr($totalBonus) ?></td>
        <td class="text-right"><?= $idr($totalDeduct) ?></td>
        <td class="text-right"><?= $idr($total) ?></td>
        <td colspan="3"></td>
      </tr>
    </tfoot>
  </table>

  <div class="footer">
    <span>Falles Travel &mdash; Laporan Penggajian Supir</span>
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
