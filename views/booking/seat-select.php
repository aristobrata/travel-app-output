<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Kursi — TravelKu</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFC;color:#1E293B}
nav{background:#0F1B2D;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
nav a.brand{font-family:'DM Serif Display',serif;font-size:1.2rem;color:#fff;text-decoration:none}
.page-wrap{max-width:960px;margin:0 auto;padding:28px 20px}
.back-link{color:#64748B;font-size:.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:20px}
.back-link:hover{color:#0F1B2D}
.layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
/* Journey info */
.journey-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:20px;margin-bottom:16px}
.journey-route{display:flex;align-items:center;gap:10px;font-size:1.1rem;font-weight:700;color:#0F1B2D;margin-bottom:10px}
.journey-arrow{color:#F59E0B}
.journey-meta{display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:.82rem;color:#64748B}
.journey-meta span{display:flex;align-items:center;gap:5px}
/* Seat map */
.seat-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:22px}
.seat-card h3{font-size:1rem;font-weight:700;margin-bottom:6px}
.legend{display:flex;gap:14px;margin-bottom:18px;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:6px;font-size:.78rem;color:#64748B}
.legend-box{width:20px;height:20px;border-radius:5px}
.lb-available{background:#E2E8F0;border:1.5px solid #CBD5E1}
.lb-selected{background:#F59E0B;border:1.5px solid #D97706}
.lb-taken{background:#FEE2E2;border:1.5px solid #fca5a5}
.bus-shell{background:#F8FAFC;border:2px solid #E2E8F0;border-radius:14px;padding:16px;position:relative}
.bus-driver{text-align:right;margin-bottom:10px;font-size:.72rem;color:#94A3B8;padding-right:6px}
.seat-row{display:flex;justify-content:center;gap:6px;margin-bottom:6px;align-items:center}
.seat-gap{width:24px}
.seat{
  width:38px;height:38px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  font-size:.78rem;font-weight:700;cursor:pointer;
  border:1.5px solid #CBD5E1;background:#E2E8F0;color:#475569;
  transition:all .15s;user-select:none;
}
.seat:hover:not(.taken):not(.selected){background:#FEF3C7;border-color:#F59E0B;color:#92400e}
.seat.selected{background:#F59E0B;border-color:#D97706;color:#0F1B2D;transform:scale(1.05)}
.seat.taken{background:#FEE2E2;border-color:#fca5a5;color:#fca5a5;cursor:not-allowed}
.seat.driver{background:#0F1B2D;border-color:#0F1B2D;color:#F59E0B;cursor:default;font-size:.6rem}
/* Summary panel */
.summary-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:22px;position:sticky;top:20px}
.summary-card h3{font-size:1rem;font-weight:700;margin-bottom:16px;color:#0F1B2D}
.selected-seats-list{min-height:48px;margin-bottom:16px}
.seat-chip{display:inline-flex;align-items:center;gap:5px;background:#FEF3C7;color:#92400e;padding:4px 10px;border-radius:6px;font-size:.82rem;font-weight:600;margin:3px}
.seat-chip button{background:none;border:none;cursor:pointer;color:#92400e;padding:0;font-size:.8rem;margin-left:2px}
.price-breakdown{border-top:1px solid #E2E8F0;padding-top:14px;margin-bottom:16px}
.price-row{display:flex;justify-content:space-between;font-size:.85rem;color:#64748B;margin-bottom:6px}
.price-row.total{font-weight:700;font-size:1rem;color:#0F1B2D;border-top:1px dashed #E2E8F0;padding-top:10px;margin-top:4px}
.btn-next{width:100%;padding:11px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:10px;font-weight:700;font-size:.95rem;cursor:pointer;font-family:inherit;transition:all .2s}
.btn-next:hover:not(:disabled){background:#D97706;color:#fff}
.btn-next:disabled{background:#E2E8F0;color:#94A3B8;cursor:not-allowed}
.alert{padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:12px}
.alert-error{background:#FEE2E2;color:#991b1b}
.alert-warning{background:#FEF3C7;color:#92400e}
@media(max-width:700px){.layout{grid-template-columns:1fr}.summary-card{position:static}}
</style>
</head>
<body>
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : ''; ?>
<nav>
  <a href="<?= $base ?>/" class="brand">🚌 Falles<span style="color:#F59E0B">Travel</span></a>
  <div style="display:flex;align-items:center;gap:8px">
    <span style="color:rgba(255,255,255,.6);font-size:.82rem">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
  </div>
</nav>

<?php if (!empty($_SESSION['error'])): ?>
<div style="max-width:960px;margin:16px auto;padding:0 20px">
  <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
  <?php unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="page-wrap">
  <a href="javascript:history.back()" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Hasil Pencarian</a>

  <div class="layout">
    <!-- LEFT -->
    <div>
      <!-- Journey Info -->
      <div class="journey-card">
        <div class="journey-route">
          <?= htmlspecialchars($schedule['origin']) ?>
          <span class="journey-arrow"><i class="fa-solid fa-arrow-right"></i></span>
          <?= htmlspecialchars($schedule['destination']) ?>
        </div>
        <div class="journey-meta">
          <span><i class="fa-regular fa-clock" style="color:#F59E0B"></i>
            <?= date('d F Y', strtotime($schedule['depart_at'])) ?> · <?= date('H:i', strtotime($schedule['depart_at'])) ?> WIB
          </span>
          <span><i class="fa-solid fa-bus" style="color:#F59E0B"></i>
            <?= htmlspecialchars($schedule['vehicle_name']) ?>
          </span>
          <span><i class="fa-solid fa-chair" style="color:#F59E0B"></i>
            <?= $schedule['available_seats'] ?> kursi tersisa
          </span>
          <span><i class="fa-solid fa-tag" style="color:#F59E0B"></i>
            Rp <?= number_format($schedule['price'], 0, ',', '.') ?>/kursi
          </span>
        </div>
      </div>

      <!-- Seat Map -->
      <div class="seat-card">
        <h3><i class="fa-solid fa-couch" style="color:#F59E0B;margin-right:6px"></i> Pilih Kursi</h3>
        <div class="legend">
          <div class="legend-item"><div class="legend-box lb-available"></div> Tersedia</div>
          <div class="legend-item"><div class="legend-box lb-selected"></div> Dipilih</div>
          <div class="legend-item"><div class="legend-box lb-taken"></div> Terisi</div>
        </div>

        <div class="bus-shell">
          <?php
          $capacity = (int)$schedule['capacity'];
          $takenArr = $takenNos ?? [];

          // Helper: render one seat element
          function renderSeat($n, $takenArr, $scheduleId, $price) {
            if ($n === null) { echo '<div style="width:38px;height:38px"></div>'; return; }
            $taken = in_array($n, $takenArr);
            $cls   = $taken ? 'taken' : '';
            $click = $taken ? '' : "toggleSeat($n, $scheduleId, $price)";
            echo "<div class=\"seat $cls\" id=\"seat-$n\" data-seat=\"$n\" onclick=\"$click\">$n</div>";
          }
          ?>

          <!-- BARIS PENGEMUDI: sejajar dengan baris penumpang (struktur kosong+kursi1 | gap | kosong+pengemudi) -->
          <div class="seat-row" style="margin-bottom:10px;padding-bottom:10px;border-bottom:2px dashed #E2E8F0">
            <div style="width:38px;height:38px"></div>
            <?php renderSeat(1, $takenArr, $schedule['id'], $schedule['price']); ?>
            <div class="seat-gap"></div>
            <div style="width:38px;height:38px"></div>
            <div class="seat driver" title="Pengemudi">
              <i class="fa-solid fa-steering-wheel" style="font-size:.95rem"></i>
            </div>
          </div>

          <!-- BARIS PENUMPANG: kursi 2 dst, layout 2+2 -->
          <?php
          $seatNum = 2;
          while ($seatNum <= $capacity):
            $r = [$seatNum <= $capacity ? $seatNum++ : null,
                  $seatNum <= $capacity ? $seatNum++ : null,
                  $seatNum <= $capacity ? $seatNum++ : null,
                  $seatNum <= $capacity ? $seatNum++ : null];
          ?>
          <div class="seat-row">
            <?php renderSeat($r[0], $takenArr, $schedule['id'], $schedule['price']); ?>
            <?php renderSeat($r[1], $takenArr, $schedule['id'], $schedule['price']); ?>
            <div class="seat-gap"></div>
            <?php renderSeat($r[2], $takenArr, $schedule['id'], $schedule['price']); ?>
            <?php renderSeat($r[3], $takenArr, $schedule['id'], $schedule['price']); ?>
          </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>

    <!-- RIGHT: SUMMARY -->
    <div>
      <div class="summary-card">
        <h3>Ringkasan Pemesanan</h3>
        <div style="font-size:.82rem;color:#64748B;margin-bottom:12px">
          <?= htmlspecialchars($schedule['origin']) ?> → <?= htmlspecialchars($schedule['destination']) ?><br>
          <?= date('d F Y', strtotime($schedule['depart_at'])) ?> · <?= date('H:i', strtotime($schedule['depart_at'])) ?> WIB
        </div>

        <div style="font-size:.82rem;font-weight:600;color:#475569;margin-bottom:8px">Kursi Dipilih:</div>
        <div class="selected-seats-list" id="selected-seats-list">
          <div id="no-seat-msg" style="font-size:.82rem;color:#94A3B8;font-style:italic">Belum ada kursi dipilih</div>
        </div>

        <div class="price-breakdown">
          <div class="price-row">
            <span>Harga/kursi</span>
            <span>Rp <?= number_format($schedule['price'], 0, ',', '.') ?></span>
          </div>
          <div class="price-row">
            <span>Jumlah kursi</span>
            <span id="seat-count">0</span>
          </div>
          <div class="price-row total">
            <span>Total</span>
            <span id="total-price">Rp 0</span>
          </div>
        </div>

        <form id="proceed-form" method="POST" action="<?= $base ?>/booking/passenger">
          <?php if (!empty($_SESSION['csrf_token'])): ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <?php endif; ?>
          <input type="hidden" name="schedule_id" value="<?= $schedule['id'] ?>">
          <input type="hidden" name="seats" id="seats-hidden" value="">
          <button type="submit" class="btn-next" id="btn-proceed" disabled>
            Lanjut Isi Data <i class="fa-solid fa-arrow-right"></i>
          </button>
        </form>

        <div style="margin-top:12px;font-size:.75rem;color:#94A3B8;display:flex;align-items:flex-start;gap:5px">
          <i class="fa-solid fa-circle-info" style="margin-top:2px"></i>
          Kursi akan dikunci selama 15 menit. Segera selesaikan pemesanan.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const selectedSeats = new Set();
const pricePerSeat = <?= (int)$schedule['price'] ?>;
const scheduleId   = <?= (int)$schedule['id'] ?>;
const MAX_SEATS    = <?= $schedule['available_seats'] ?>;

function formatRp(n) {
  return 'Rp ' + n.toLocaleString('id-ID');
}

function updateSummary() {
  const arr = [...selectedSeats].sort((a,b) => a - b);
  const list = document.getElementById('selected-seats-list');
  const noMsg = document.getElementById('no-seat-msg');
  const countEl = document.getElementById('seat-count');
  const totalEl = document.getElementById('total-price');
  const hiddenEl = document.getElementById('seats-hidden');
  const btn = document.getElementById('btn-proceed');

  list.innerHTML = arr.map(n =>
    `<span class="seat-chip">Kursi ${n} <button onclick="toggleSeat(${n},${scheduleId},${pricePerSeat})" title="Batal">×</button></span>`
  ).join('') || '<div id="no-seat-msg" style="font-size:.82rem;color:#94A3B8;font-style:italic">Belum ada kursi dipilih</div>';

  countEl.textContent = arr.length;
  totalEl.textContent = formatRp(arr.length * pricePerSeat);
  hiddenEl.value = arr.join(',');
  btn.disabled = arr.length === 0;
}

async function toggleSeat(seatNo, schedId, price) {
  const el = document.getElementById('seat-' + seatNo);
  if (!el || el.classList.contains('taken')) return;

  if (selectedSeats.has(seatNo)) {
    selectedSeats.delete(seatNo);
    el.classList.remove('selected');
    updateSummary();
    return;
  }

  if (selectedSeats.size >= MAX_SEATS) {
    alert('Kursi yang tersedia sudah habis untuk jadwal ini.');
    return;
  }

  // Lock via AJAX
  try {
    const res = await fetch('<?= $base ?>/api/seat/lock', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ schedule_id: schedId, seat_number: seatNo })
    });
    const data = await res.json();
    if (data.success) {
      selectedSeats.add(seatNo);
      el.classList.add('selected');
      updateSummary();
    } else {
      el.classList.add('taken');
      el.onclick = null;
      alert(data.message || 'Kursi sudah tidak tersedia.');
    }
  } catch(e) {
    alert('Gagal mengunci kursi. Periksa koneksi internet Anda.');
  }
}

// Refresh seat status every 30s
setInterval(async () => {
  try {
    const res = await fetch('<?= $base ?>/api/seats/<?= $schedule['id'] ?>');
    const data = await res.json();
    if (!data.taken) return;
    data.taken.forEach(t => {
      const n = t.seat_number;
      if (selectedSeats.has(n)) return; // milik kita sendiri
      const el = document.getElementById('seat-' + n);
      if (el && !el.classList.contains('taken')) {
        el.classList.add('taken');
        el.onclick = null;
      }
    });
  } catch(e) {}
}, 30000);
</script>
</body>
</html>
