<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TravelKu — Pesan Tiket Bus & Travel Online</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= (defined('SUBFOLDER') ? SUBFOLDER : '') ?>/assets/css/app.css">
<style>
.hero{background:linear-gradient(135deg,#0F1B2D 0%,#1A2D45 60%,#243C5C 100%);min-height:480px;display:flex;align-items:center;padding:48px 0;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")}
.hero-content{position:relative;z-index:1;text-align:center;color:#fff;max-width:680px;margin:0 auto;padding:0 20px}
.hero-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3);color:#F59E0B;padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:600;margin-bottom:20px}
.hero h1{font-family:'DM Serif Display',serif;font-size:2.8rem;line-height:1.2;margin-bottom:14px}
.hero h1 span{color:#F59E0B}
.hero p{font-size:1rem;color:rgba(255,255,255,.7);margin-bottom:36px}
.search-card{background:#fff;border-radius:20px;padding:32px;box-shadow:0 24px 64px rgba(0,0,0,.25);max-width:700px;margin:0 auto}
.search-card h2{font-size:1.1rem;font-weight:700;color:#0F1B2D;margin-bottom:20px;display:flex;align-items:center;gap:8px}
.search-grid{display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end}
.form-group{margin:0}
label{display:block;font-size:.78rem;font-weight:600;color:#64748B;margin-bottom:5px;text-transform:uppercase;letter-spacing:.04em}
.input-wrap{position:relative}
.input-wrap .icon{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94A3B8;font-size:.85rem}
input,select{display:block;width:100%;padding:10px 12px 10px 34px;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;font-family:inherit;color:#1E293B;background:#fff;transition:border-color .2s}
input:focus,select:focus{outline:none;border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
.btn-search{padding:10px 24px;background:#F59E0B;color:#0F1B2D;border:none;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;font-family:inherit;white-space:nowrap;transition:all .2s;display:flex;align-items:center;gap:7px;height:44px}
.btn-search:hover{background:#D97706;color:#fff}
.features{padding:64px 0;background:#F8FAFC}
.features h2{font-family:'DM Serif Display',serif;font-size:2rem;color:#0F1B2D;text-align:center;margin-bottom:8px}
.features p.sub{text-align:center;color:#64748B;margin-bottom:40px}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px}
.feature-card{background:#fff;border-radius:16px;padding:28px 22px;text-align:center;border:1px solid #E2E8F0;box-shadow:0 2px 8px rgba(0,0,0,.05);transition:all .3s}
.feature-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,.1)}
.feature-icon{width:52px;height:52px;border-radius:14px;background:#0F1B2D;color:#F59E0B;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 14px}
.feature-card h3{font-size:.95rem;font-weight:700;color:#1E293B;margin-bottom:6px}
.feature-card p{font-size:.82rem;color:#64748B;line-height:1.6}
.popular{padding:56px 0;background:#fff}
.popular h2{font-family:'DM Serif Display',serif;font-size:2rem;color:#0F1B2D;text-align:center;margin-bottom:8px}
.routes-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-top:32px}
.route-card{background:#F8FAFC;border-radius:14px;padding:20px;border:1px solid #E2E8F0;transition:all .2s;cursor:pointer}
.route-card:hover{border-color:#F59E0B;background:#FFF;box-shadow:0 4px 16px rgba(0,0,0,.08)}
.route-cities{display:flex;align-items:center;gap:8px;font-weight:700;color:#0F1B2D;font-size:.95rem}
.route-arrow{color:#F59E0B}
.route-info{font-size:.78rem;color:#64748B;margin-top:6px}
.route-price{font-size:1rem;font-weight:700;color:#F59E0B;margin-top:8px}
footer{background:#0F1B2D;color:rgba(255,255,255,.6);padding:32px 0;text-align:center;font-size:.85rem}
footer .footer-brand{font-family:'DM Serif Display',serif;font-size:1.3rem;color:#fff;margin-bottom:8px}
footer a{color:rgba(255,255,255,.5);text-decoration:none}
.alert{padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:.875rem;display:flex;align-items:center;gap:8px}
.alert-error{background:#FEE2E2;color:#991b1b;border:1px solid #fca5a5}
.alert-success{background:#D1FAE5;color:#065f46;border:1px solid #6ee7b7}
@media(max-width:700px){.search-grid{grid-template-columns:1fr}.hero h1{font-size:2rem}}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php $base = defined('SUBFOLDER') ? SUBFOLDER : ''; ?>
<nav style="background:#0F1B2D;padding:0 24px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 2px 12px rgba(0,0,0,.2)">
  <a href="<?= $base ?>/" style="font-family:'DM Serif Display',serif;font-size:1.3rem;color:#fff;text-decoration:none">
    🚌 Travel<span style="color:#F59E0B">Ku</span>
  </a>
  <div style="display:flex;align-items:center;gap:10px">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="<?= $base ?>/my-bookings" style="color:rgba(255,255,255,.75);font-size:.875rem;text-decoration:none;padding:6px 12px;border-radius:6px;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background='transparent'">
        📋 Pesanan Saya
      </a>
      <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
        <a href="<?= $base ?>/admin" style="color:rgba(255,255,255,.75);font-size:.875rem;text-decoration:none;padding:6px 12px;border-radius:6px">
          ⚙️ Admin
        </a>
      <?php endif; ?>
      <a href="<?= $base ?>/logout" style="background:#F59E0B;color:#0F1B2D;padding:7px 16px;border-radius:7px;font-size:.875rem;font-weight:700;text-decoration:none"
        onclick="return confirm('Yakin keluar?')">
        Keluar
      </a>
    <?php else: ?>
      <a href="<?= $base ?>/login" style="color:rgba(255,255,255,.75);font-size:.875rem;text-decoration:none;padding:6px 14px;border-radius:6px;border:1px solid rgba(255,255,255,.2)">Masuk</a>
      <a href="<?= $base ?>/register" style="background:#F59E0B;color:#0F1B2D;padding:7px 16px;border-radius:7px;font-size:.875rem;font-weight:700;text-decoration:none">Daftar</a>
    <?php endif; ?>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <div class="hero-badge"><i class="fa-solid fa-shield-check"></i> Aman, Nyaman & Tepat Waktu</div>
    <h1>Pesan Tiket <span>Bus & Travel</span><br>Semudah Satu Klik</h1>
    <p>Temukan jadwal perjalanan terbaik dengan harga terjangkau. Pilih kursi favoritmu dan nikmati perjalanan nyaman.</p>
  </div>
</section>

<!-- SEARCH FORM -->
<div style="background:#F8FAFC;padding:0 20px 48px">
  <div class="search-card" style="margin-top:-48px;position:relative;z-index:10">
    <h2><i class="fa-solid fa-magnifying-glass" style="color:#F59E0B"></i> Cari Jadwal Perjalanan</h2>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="GET" action="<?= $base ?>/search">
      <div class="search-grid">
        <div class="form-group">
          <label>Kota Asal</label>
          <div class="input-wrap">
            <i class="fa-solid fa-location-dot icon"></i>
            <input type="text" name="origin" list="origins-list" placeholder="Dari mana?"
              value="<?= htmlspecialchars($_GET['origin'] ?? '') ?>" required>
            <datalist id="origins-list">
              <?php foreach ($origins as $o): ?>
                <option value="<?= htmlspecialchars($o['origin']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>
        </div>
        <div class="form-group">
          <label>Kota Tujuan</label>
          <div class="input-wrap">
            <i class="fa-solid fa-flag icon"></i>
            <input type="text" name="destination" placeholder="Ke mana?"
              value="<?= htmlspecialchars($_GET['destination'] ?? '') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label>Tanggal Berangkat</label>
          <div class="input-wrap">
            <i class="fa-regular fa-calendar icon"></i>
            <input type="date" name="date"
              value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>"
              min="<?= date('Y-m-d') ?>" required>
          </div>
        </div>
        <button type="submit" class="btn-search">
          <i class="fa-solid fa-search"></i> Cari
        </button>
      </div>
    </form>
  </div>
</div>

<!-- FEATURES -->
<section class="features">
  <div class="container">
    <h2>Kenapa Pilih Falles Travel?</h2>
    <p class="sub">Ribuan penumpang telah mempercayai perjalanan mereka bersama kami</p>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fa-solid fa-shield-check"></i></div>
        <h3>Aman & Terpercaya</h3>
        <p>Armada terawat dengan pengemudi berpengalaman dan bersertifikat.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
        <h3>Booking Kilat</h3>
        <p>Proses pemesanan cepat hanya dalam beberapa menit, kapan saja di mana saja.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fa-solid fa-ticket"></i></div>
        <h3>E-Tiket Instan</h3>
        <p>Tiket langsung dikirim ke email atau bisa dicek di aplikasi kapan pun.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
        <h3>Dukungan 24 Jam</h3>
        <p>Tim kami siap membantu Anda kapan saja melalui live chat maupun telepon.</p>
      </div>
    </div>
  </div>
</section>

<!-- POPULAR ROUTES -->
<?php
use App\Models\Route;
$popularRoutes = Route::all();
?>
<?php if (!empty($popularRoutes)): ?>
<section class="popular">
  <div class="container">
    <h2>Rute Populer</h2>
    <p style="text-align:center;color:#64748B">Destinasi favorit pilihan penumpang kami</p>
    <div class="routes-grid">
      <?php foreach (array_slice($popularRoutes, 0, 8) as $r): ?>
      <a href="<?= $base ?>/search?origin=<?= urlencode($r['origin']) ?>&destination=<?= urlencode($r['destination']) ?>&date=<?= date('Y-m-d') ?>"
        style="text-decoration:none;color:inherit">
        <div class="route-card">
          <div class="route-cities">
            <?= htmlspecialchars($r['origin']) ?>
            <span class="route-arrow"><i class="fa-solid fa-arrow-right"></i></span>
            <?= htmlspecialchars($r['destination']) ?>
          </div>
          <div class="route-info">
            <?php if ($r['distance_km'] > 0): ?><i class="fa-solid fa-road"></i> <?= $r['distance_km'] ?> km &nbsp;<?php endif; ?>
            <?php if ($r['duration_min'] > 0):
              $h = intdiv($r['duration_min'], 60); $m = $r['duration_min'] % 60;
              $dur = ($h > 0 ? "{$h}j " : '') . ($m > 0 ? "{$m}m" : '');
            ?><i class="fa-regular fa-clock"></i> <?= $dur ?><?php endif; ?>
          </div>
          <div class="route-price">Mulai Rp <?= number_format($r['base_price'], 0, ',', '.') ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer>
  <div class="footer-brand">🚌 Falles Travel</div>
  <p>Solusi perjalanan bus & travel terpercaya di Sumatera Barat</p>
  <p style="margin-top:8px">
    <a href="<?= $base ?>/login">Login</a> &nbsp;·&nbsp;
    <a href="<?= $base ?>/register">Daftar</a>
    <?php if (!empty($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin'): ?>
      &nbsp;·&nbsp; <a href="<?= $base ?>/admin">Admin Panel</a>
    <?php endif; ?>
  </p>
  <p style="margin-top:12px;font-size:.75rem">© <?= date('Y') ?> Falles Travel. All rights reserved.</p>
</footer>

</body>
</html>
