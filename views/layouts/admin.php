<?php
// views/layouts/admin.php
// Usage: set $pageTitle before including, then include this, render content, include admin-footer.php
$appName = 'TravelKu';
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$subfolder = defined('SUBFOLDER') ? SUBFOLDER : '';
function adminUrl(string $path): string {
    $base = defined('SUBFOLDER') ? SUBFOLDER : '';
    return $base . '/' . ltrim($path, '/');
}
function isActive(string $prefix): string {
    global $currentUri;
    return str_starts_with($currentUri, $prefix) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — <?= $appName ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{
  --navy:#0F1B2D;--navy-mid:#1A2D45;--navy-light:#243C5C;
  --amber:#F59E0B;--amber-dark:#D97706;--amber-light:#FEF3C7;
  --white:#fff;--gray-50:#F8FAFC;--gray-100:#F1F5F9;--gray-200:#E2E8F0;
  --gray-300:#CBD5E1;--gray-400:#94A3B8;--gray-500:#64748B;
  --gray-600:#475569;--gray-700:#334155;--gray-800:#1E293B;
  --green:#10B981;--green-light:#D1FAE5;--red:#EF4444;--red-light:#FEE2E2;
  --blue:#3B82F6;--blue-light:#DBEAFE;--orange:#F97316;--orange-light:#FFEDD5;
  --purple:#8B5CF6;--purple-light:#EDE9FE;
  --font-sans:'Plus Jakarta Sans',sans-serif;
  --font-serif:'DM Serif Display',serif;
  --radius-sm:6px;--radius:10px;--radius-lg:16px;
  --shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow:0 4px 16px rgba(0,0,0,.10);
  --shadow-lg:0 12px 40px rgba(0,0,0,.14);
  --sidebar-w:260px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:15px;scroll-behavior:smooth}
body{font-family:var(--font-sans);background:var(--gray-50);color:var(--gray-800);line-height:1.6;-webkit-font-smoothing:antialiased}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
ul{list-style:none}
input,select,textarea,button{font-family:inherit}

/* ── LAYOUT ── */
.admin-wrap{display:flex;min-height:100vh}

/* ── SIDEBAR ── */
.sidebar{
  width:var(--sidebar-w);flex-shrink:0;
  background:var(--navy);
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;height:100vh;
  overflow-y:auto;z-index:200;
  transition:transform .3s ease;
}
.sidebar-brand{
  padding:24px 20px 20px;
  display:flex;align-items:center;gap:10px;
  border-bottom:1px solid rgba(255,255,255,.08);
}
.sidebar-brand .brand-icon{
  width:38px;height:38px;background:var(--amber);
  border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;
  color:var(--navy);font-size:1.1rem;font-weight:700;
}
.sidebar-brand .brand-text{
  font-family:var(--font-serif);font-size:1.25rem;color:var(--white);
}
.sidebar-brand .brand-sub{
  font-size:.65rem;font-weight:600;letter-spacing:.08em;
  color:var(--amber);text-transform:uppercase;display:block;line-height:1;
}
.sidebar-nav{padding:16px 12px;flex:1}
.nav-section{margin-bottom:24px}
.nav-section-title{
  font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  color:rgba(255,255,255,.35);padding:0 8px;margin-bottom:6px;
}
.nav-item a{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;border-radius:var(--radius-sm);
  color:rgba(255,255,255,.65);font-size:.875rem;font-weight:500;
  transition:all .2s;
}
.nav-item a:hover{color:var(--white);background:rgba(255,255,255,.08)}
.nav-item a.active{color:var(--white);background:var(--amber);color:var(--navy)}
.nav-item a .nav-icon{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}
.nav-item a .nav-badge{
  margin-left:auto;background:var(--red);color:#fff;
  font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:20px;
}
.sidebar-footer{
  padding:16px;border-top:1px solid rgba(255,255,255,.08);
  font-size:.8rem;color:rgba(255,255,255,.4);text-align:center;
}

/* ── MAIN ── */
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}

/* ── TOPBAR ── */
.topbar{
  background:var(--white);border-bottom:1px solid var(--gray-200);
  padding:0 28px;height:62px;display:flex;align-items:center;
  justify-content:space-between;position:sticky;top:0;z-index:100;
  box-shadow:var(--shadow-sm);
}
.topbar-left{display:flex;align-items:center;gap:12px}
.sidebar-toggle{
  display:none;background:none;border:none;cursor:pointer;
  color:var(--gray-600);font-size:1.1rem;padding:6px;
}
.page-title{font-size:1rem;font-weight:600;color:var(--gray-800)}
.topbar-right{display:flex;align-items:center;gap:14px}
.topbar-user{
  display:flex;align-items:center;gap:9px;
  padding:6px 12px;border-radius:var(--radius);
  background:var(--gray-50);border:1px solid var(--gray-200);
  font-size:.85rem;
}
.topbar-user .avatar{
  width:30px;height:30px;border-radius:50%;
  background:var(--amber);color:var(--navy);
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:.8rem;
}
.topbar-user .user-name{font-weight:600;color:var(--gray-700)}
.topbar-link{
  color:var(--gray-500);font-size:.82rem;padding:6px 10px;
  border-radius:var(--radius-sm);transition:all .2s;
  display:flex;align-items:center;gap:6px;
}
.topbar-link:hover{color:var(--red);background:var(--red-light)}

/* ── PAGE CONTENT ── */
.page-content{padding:28px;flex:1}
.page-header{margin-bottom:24px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap}
.page-header-left h1{font-size:1.4rem;font-weight:700;color:var(--gray-800)}
.page-header-left p{font-size:.875rem;color:var(--gray-500);margin-top:2px}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:.78rem;color:var(--gray-400);margin-bottom:4px}
.breadcrumb a:hover{color:var(--amber)}
.breadcrumb .sep{color:var(--gray-300)}

/* ── ALERTS ── */
.alert{padding:12px 16px;border-radius:var(--radius);margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;font-size:.875rem}
.alert-success{background:var(--green-light);color:#065f46;border:1px solid #6ee7b7}
.alert-error{background:var(--red-light);color:#991b1b;border:1px solid #fca5a5}
.alert-info{background:var(--blue-light);color:#1e40af;border:1px solid #93c5fd}
.alert-warning{background:var(--amber-light);color:#92400e;border:1px solid #fcd34d}
.alert-icon{flex-shrink:0;font-size:1rem}
.alert-close{margin-left:auto;background:none;border:none;cursor:pointer;opacity:.5;font-size:1rem;padding:0}
.alert-close:hover{opacity:1}

/* ── CARDS ── */
.card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200)}
.card-header{padding:18px 24px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.card-title{font-size:1rem;font-weight:600;color:var(--gray-800);display:flex;align-items:center;gap:8px}
.card-body{padding:24px}
.card-footer{padding:14px 24px;border-top:1px solid var(--gray-100);background:var(--gray-50);border-radius:0 0 var(--radius-lg) var(--radius-lg)}

/* ── STAT CARDS ── */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:28px}
.stat-card{background:var(--white);border-radius:var(--radius-lg);padding:22px;border:1px solid var(--gray-200);box-shadow:var(--shadow-sm);display:flex;gap:16px;align-items:flex-start}
.stat-icon{width:48px;height:48px;border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.stat-icon.navy{background:var(--navy);color:var(--amber)}
.stat-icon.amber{background:var(--amber-light);color:var(--amber-dark)}
.stat-icon.green{background:var(--green-light);color:var(--green)}
.stat-icon.red{background:var(--red-light);color:var(--red)}
.stat-icon.blue{background:var(--blue-light);color:var(--blue)}
.stat-icon.purple{background:var(--purple-light);color:var(--purple)}
.stat-icon.orange{background:var(--orange-light);color:var(--orange)}
.stat-info{flex:1}
.stat-label{font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--gray-400);margin-bottom:4px}
.stat-value{font-size:1.6rem;font-weight:700;color:var(--gray-800);line-height:1.2}
.stat-sub{font-size:.78rem;color:var(--gray-500);margin-top:3px}

/* ── TABLE ── */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
table th{background:var(--gray-50);color:var(--gray-600);font-size:.75rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:10px 14px;text-align:left;border-bottom:2px solid var(--gray-200)}
table td{padding:12px 14px;border-bottom:1px solid var(--gray-100);font-size:.875rem;color:var(--gray-700);vertical-align:middle}
table tbody tr:hover{background:var(--gray-50)}
table tbody tr:last-child td{border-bottom:none}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:.72rem;font-weight:600}
.badge-pending{background:#FEF3C7;color:#92400e}
.badge-paid{background:var(--green-light);color:#065f46}
.badge-cancelled{background:var(--red-light);color:#991b1b}
.badge-completed{background:var(--blue-light);color:#1e40af}
.badge-active{background:var(--green-light);color:#065f46}
.badge-inactive{background:var(--gray-100);color:var(--gray-500)}
.badge-admin{background:var(--purple-light);color:#5b21b6}
.badge-user{background:var(--blue-light);color:#1e40af}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:var(--radius-sm);font-size:.875rem;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none;white-space:nowrap}
.btn-sm{padding:6px 12px;font-size:.78rem}
.btn-xs{padding:4px 9px;font-size:.72rem}
.btn-primary{background:var(--amber);color:var(--navy)}
.btn-primary:hover{background:var(--amber-dark);color:var(--white)}
.btn-navy{background:var(--navy);color:var(--white)}
.btn-navy:hover{background:var(--navy-mid)}
.btn-success{background:var(--green);color:var(--white)}
.btn-success:hover{filter:brightness(.9)}
.btn-danger{background:var(--red);color:var(--white)}
.btn-danger:hover{filter:brightness(.9)}
.btn-outline{background:transparent;color:var(--gray-700);border:1px solid var(--gray-300)}
.btn-outline:hover{background:var(--gray-100)}
.btn-info{background:var(--blue);color:var(--white)}
.btn-info:hover{filter:brightness(.9)}
.btn-purple{background:var(--purple);color:var(--white)}
.btn-orange{background:var(--orange);color:var(--white)}

/* ── FORMS ── */
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:.85rem;font-weight:600;color:var(--gray-700);margin-bottom:6px}
.form-label .required{color:var(--red);margin-left:2px}
.form-control{
  display:block;width:100%;padding:10px 13px;
  border:1.5px solid var(--gray-300);border-radius:var(--radius-sm);
  font-size:.875rem;color:var(--gray-800);background:var(--white);
  transition:border-color .2s,box-shadow .2s;
}
.form-control:focus{outline:none;border-color:var(--amber);box-shadow:0 0 0 3px rgba(245,158,11,.15)}
.form-control.error{border-color:var(--red)}
.form-error{font-size:.78rem;color:var(--red);margin-top:4px}
.form-hint{font-size:.78rem;color:var(--gray-400);margin-top:4px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
.form-check{display:flex;align-items:center;gap:8px;margin-bottom:8px;cursor:pointer}
.form-check input[type=checkbox]{width:16px;height:16px;cursor:pointer;accent-color:var(--amber)}

/* ── FILTER BAR ── */
.filter-bar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px}
.filter-bar .form-control{width:auto;flex:1;min-width:140px;max-width:240px}
.filter-bar select.form-control{max-width:180px}

/* ── EMPTY STATE ── */
.empty-state{text-align:center;padding:60px 20px;color:var(--gray-400)}
.empty-state .empty-icon{font-size:3rem;margin-bottom:12px;opacity:.4}
.empty-state h3{font-size:1rem;font-weight:600;color:var(--gray-500);margin-bottom:6px}
.empty-state p{font-size:.875rem}

/* ── ACTION BUTTONS IN TABLE ── */
.actions{display:flex;align-items:center;gap:6px;flex-wrap:wrap}

/* ── MODAL ── */
.modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;display:none;align-items:center;justify-content:center;padding:20px}
.modal-backdrop.show{display:flex}
.modal{background:var(--white);border-radius:var(--radius-lg);padding:28px;max-width:480px;width:100%;box-shadow:var(--shadow-lg)}
.modal-title{font-size:1.1rem;font-weight:700;margin-bottom:12px}
.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:22px}

/* ── RESPONSIVE ── */
@media(max-width:900px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.open{transform:translateX(0)}
  .main{margin-left:0}
  .sidebar-toggle{display:block}
  .form-row,.form-row-3{grid-template-columns:1fr}
  .page-content{padding:18px}
}
</style>
</head>
<body>
<div class="admin-wrap">
<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fa-solid fa-route"></i></div>
    <div>
      <span class="sidebar-brand .brand-text" style="font-family:var(--font-serif);font-size:1.2rem;color:#fff">Falles Travel</span>
      <span class="brand-sub">Admin Panel</span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">
      <div class="nav-section-title">Utama</div>
      <ul>
        <li class="nav-item"><a href="<?= adminUrl('/admin') ?>" class="<?= isActive('/admin') && !str_contains($currentUri, '/admin/') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
        </a></li>
      </ul>
    </div>
    <div class="nav-section">
      <div class="nav-section-title">Manajemen</div>
      <ul>
        <li class="nav-item"><a href="<?= adminUrl('/admin/vehicles') ?>" class="<?= isActive('/admin/vehicles') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-bus"></i></span> Kendaraan
        </a></li>
        <li class="nav-item"><a href="<?= adminUrl('/admin/routes') ?>" class="<?= isActive('/admin/routes') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-map-signs"></i></span> Rute
        </a></li>
        <li class="nav-item"><a href="<?= adminUrl('/admin/schedules') ?>" class="<?= isActive('/admin/schedules') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-regular fa-calendar"></i></span> Jadwal
        </a></li>
        <li class="nav-item"><a href="<?= adminUrl('/admin/bookings') ?>" class="<?= isActive('/admin/bookings') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-ticket"></i></span> Pemesanan
        </a></li>
      </ul>
    </div>
    <div class="nav-section">
      <div class="nav-section-title">Akun & Laporan</div>
      <ul>
        <li class="nav-item"><a href="<?= adminUrl('/admin/users') ?>" class="<?= isActive('/admin/users') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Pengguna
        </a></li>
        <li class="nav-item"><a href="<?= adminUrl('/admin/reports') ?>" class="<?= isActive('/admin/reports') ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fa-solid fa-chart-bar"></i></span> Laporan
        </a></li>
      </ul>
    </div>
    <div class="nav-section">
      <div class="nav-section-title">Lainnya</div>
      <ul>
        <li class="nav-item"><a href="<?= adminUrl('/') ?>">
          <span class="nav-icon"><i class="fa-solid fa-house"></i></span> Ke Beranda
        </a></li>
        <li class="nav-item"><a href="<?= adminUrl('/logout') ?>" onclick="return confirm('Yakin keluar?')" style="color:rgba(239,68,68,.8)">
          <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span> Keluar
        </a></li>
      </ul>
    </div>
  </nav>
  <div class="sidebar-footer">© <?= date('Y') ?> Falles Travel</div>
</aside>

<!-- MAIN -->
<div class="main">
  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <span class="page-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></span>
    </div>
    <div class="topbar-right">
      <div class="topbar-user">
        <div class="avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?></div>
        <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
      </div>
      <a href="<?= adminUrl('/logout') ?>" class="topbar-link" onclick="return confirm('Yakin keluar?')">
        <i class="fa-solid fa-right-from-bracket"></i> Keluar
      </a>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <div class="page-content">
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success">
        <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span>
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-error">
        <span class="alert-icon"><i class="fa-solid fa-circle-xmark"></i></span>
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
