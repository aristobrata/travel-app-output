<?php
$pageTitle = 'Detail Pemesanan';
require BASE_PATH . '/views/layouts/admin.php';
?>

<div class="page-header">
  <div class="page-header-left">
    <div class="breadcrumb">
      <a href="<?= adminUrl('/admin') ?>">Dashboard</a>
      <span class="sep">/</span>
      <a href="<?= adminUrl('/admin/bookings') ?>">Pemesanan</a>
      <span class="sep">/</span>
      Detail #<?= $booking['id'] ?>
    </div>
    <h1>Detail Pemesanan</h1>
    <p>Informasi lengkap pemesanan <strong><?= htmlspecialchars($booking['booking_code']) ?></strong></p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="<?= adminUrl('/admin/bookings') ?>" class="btn btn-outline">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

  <!-- LEFT COLUMN -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- BOOKING INFO -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-ticket" style="color:var(--amber)"></i> Informasi Pemesanan</span>
        <span class="badge badge-<?= $booking['status'] ?>" style="font-size:.82rem;padding:4px 12px">
          <?= match($booking['status']) {
            'pending' => '⏳ Pending',
            'paid' => '✅ Terbayar',
            'cancelled' => '❌ Dibatalkan',
            'completed' => '✔ Selesai',
            default => ucfirst($booking['status'])
          } ?>
        </span>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Kode Booking</div>
            <code style="font-size:1rem;font-weight:700;color:var(--navy);background:var(--amber-light);padding:3px 10px;border-radius:5px">
              <?= htmlspecialchars($booking['booking_code']) ?>
            </code>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Tanggal Pesan</div>
            <div style="font-weight:600"><?= date('d F Y, H:i', strtotime($booking['created_at'])) ?> WIB</div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Rute</div>
            <div style="font-weight:600;display:flex;align-items:center;gap:6px">
              <?= htmlspecialchars($booking['origin']) ?>
              <i class="fa-solid fa-arrow-right" style="color:var(--amber);font-size:.7rem"></i>
              <?= htmlspecialchars($booking['destination']) ?>
            </div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Kendaraan</div>
            <div style="font-weight:600"><?= htmlspecialchars($booking['vehicle_name']) ?></div>
            <div style="font-size:.75rem;color:var(--gray-400)"><?= htmlspecialchars($booking['plate_number'] ?? '') ?></div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Keberangkatan</div>
            <div style="font-weight:600;color:var(--amber-dark)"><?= date('d F Y', strtotime($booking['depart_at'])) ?></div>
            <div style="font-size:.85rem"><?= date('H:i', strtotime($booking['depart_at'])) ?> WIB</div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Perkiraan Tiba</div>
            <div style="font-weight:600"><?= date('d F Y', strtotime($booking['arrive_at'])) ?></div>
            <div style="font-size:.85rem"><?= date('H:i', strtotime($booking['arrive_at'])) ?> WIB</div>
          </div>
        </div>
        <?php if (!empty($booking['notes'])): ?>
        <div style="margin-top:16px;padding:12px;background:var(--amber-light);border-radius:var(--radius-sm);border-left:3px solid var(--amber)">
          <div style="font-size:.75rem;font-weight:700;color:var(--amber-dark);margin-bottom:3px">Catatan</div>
          <div style="font-size:.875rem"><?= htmlspecialchars($booking['notes']) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- CONTACT INFO -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-user"></i> Data Kontak</span>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px">
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Nama</div>
            <div style="font-weight:600"><?= htmlspecialchars($booking['contact_name']) ?></div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Telepon</div>
            <div style="font-weight:600"><?= htmlspecialchars($booking['contact_phone']) ?></div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-400);margin-bottom:3px">Email</div>
            <div style="font-weight:600;font-size:.85rem"><?= htmlspecialchars($booking['contact_email']) ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- PASSENGERS -->
    <?php if (!empty($seats)): ?>
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-users"></i> Data Penumpang</span>
        <span style="font-size:.82rem;color:var(--gray-400)"><?= count($seats) ?> penumpang</span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Kursi</th>
              <th>Nama Penumpang</th>
              <th>No. Identitas</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($seats as $seat): ?>
            <tr>
              <td>
                <span style="width:32px;height:32px;background:var(--navy);color:var(--amber);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem">
                  <?= $seat['seat_number'] ?>
                </span>
              </td>
              <td style="font-weight:600"><?= htmlspecialchars($seat['passenger_name']) ?></td>
              <td style="font-family:monospace;color:var(--gray-500)">
                <?= htmlspecialchars($seat['passenger_id_no'] ?? '—') ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- RIGHT COLUMN -->
  <div style="display:flex;flex-direction:column;gap:20px">

    <!-- PAYMENT INFO -->
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-credit-card" style="color:var(--green)"></i> Pembayaran</span>
      </div>
      <div class="card-body">
        <div style="text-align:center;padding:14px 0;border-bottom:1px solid var(--gray-100);margin-bottom:14px">
          <div style="font-size:.78rem;color:var(--gray-400);margin-bottom:4px">Total Tagihan</div>
          <div style="font-size:1.8rem;font-weight:700;color:var(--navy)">
            Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
          </div>
          <div style="font-size:.78rem;color:var(--gray-400);margin-top:2px">
            <?= $booking['passenger_count'] ?> penumpang
          </div>
        </div>
        <?php if ($payment): ?>
          <div style="font-size:.82rem;display:flex;flex-direction:column;gap:8px">
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">Gateway</span>
              <span style="font-weight:600;text-transform:uppercase"><?= htmlspecialchars($payment['gateway']) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">Tipe Bayar</span>
              <span style="font-weight:600"><?= htmlspecialchars($payment['payment_type'] ?? '—') ?></span>
            </div>
            <?php if (!empty($payment['va_number'])): ?>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">No. VA</span>
              <code style="font-size:.82rem"><?= htmlspecialchars($payment['va_number']) ?></code>
            </div>
            <?php endif; ?>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">Status</span>
              <span class="badge badge-<?= $payment['status'] === 'paid' ? 'paid' : ($payment['status'] === 'failed' ? 'cancelled' : 'pending') ?>">
                <?= ucfirst($payment['status']) ?>
              </span>
            </div>
            <?php if (!empty($payment['paid_at'])): ?>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">Dibayar</span>
              <span><?= date('d/m/Y H:i', strtotime($payment['paid_at'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($payment['expired_at'])): ?>
            <div style="display:flex;justify-content:space-between">
              <span style="color:var(--gray-500)">Kadaluarsa</span>
              <span style="color:var(--red)"><?= date('d/m/Y H:i', strtotime($payment['expired_at'])) ?></span>
            </div>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div style="text-align:center;color:var(--gray-400);font-size:.85rem;padding:10px 0">
            <i class="fa-solid fa-circle-info"></i> Belum ada data pembayaran
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- UPDATE STATUS -->
    <?php if (in_array($booking['status'], ['pending', 'paid'])): ?>
    <div class="card">
      <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-sliders"></i> Update Status</span>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
        <?php if ($booking['status'] === 'pending'): ?>
        <form method="POST" action="<?= adminUrl('/admin/bookings/' . $booking['id'] . '/status') ?>">
          <?php if (!empty($_SESSION['csrf_token'])): ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <?php endif; ?>
          <input type="hidden" name="status" value="paid">
          <button type="submit" class="btn btn-success" style="width:100%" onclick="return confirm('Tandai sebagai terbayar?')">
            <i class="fa-solid fa-check"></i> Tandai Terbayar
          </button>
        </form>
        <?php endif; ?>
        <?php if ($booking['status'] !== 'cancelled'): ?>
        <form method="POST" action="<?= adminUrl('/admin/bookings/' . $booking['id'] . '/status') ?>">
          <?php if (!empty($_SESSION['csrf_token'])): ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <?php endif; ?>
          <input type="hidden" name="status" value="cancelled">
          <button type="submit" class="btn btn-danger" style="width:100%" onclick="return confirm('Batalkan pemesanan ini?')">
            <i class="fa-solid fa-ban"></i> Batalkan Pemesanan
          </button>
        </form>
        <?php endif; ?>
        <?php if ($booking['status'] === 'paid'): ?>
        <form method="POST" action="<?= adminUrl('/admin/bookings/' . $booking['id'] . '/status') ?>">
          <?php if (!empty($_SESSION['csrf_token'])): ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <?php endif; ?>
          <input type="hidden" name="status" value="completed">
          <button type="submit" class="btn btn-navy" style="width:100%" onclick="return confirm('Tandai perjalanan selesai?')">
            <i class="fa-solid fa-flag-checkered"></i> Tandai Selesai
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<?php require BASE_PATH . '/views/layouts/admin-footer.php'; ?>
