<?php
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__.'/connection.php';
$cp = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= isset($page_title)?$page_title.' | رحّال':'رحّال - منصة تجارب الإقامة المميزة' ?></title>
<meta name="description" content="رحّال - اكتشف تجارب إقامة استثنائية في أجمل الوجهات السعودية">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= $base_url??'../' ?>assets/css/style.css?v=1780769327">
<link rel="icon" type="image/svg+xml" href="<?= $base_url??'../' ?>assets/images/logo.png">
</head>
<body>

<!-- شريط الإعلانات -->
<div class="promo-bar">
  <i class="fas fa-tag ms-2"></i>
  احجز الآن واحصل على خصم 15% على أول حجز!
  <a href="<?= $base_url??'../' ?>pages/experiences.php">استكشف التجارب</a>
</div>

<!-- الهيدر -->
<header class="site-header" id="siteHeader">
<div class="container">
  <nav class="navbar-inner">

    <!-- اللوغو -->
    <a href="<?= $base_url??'../' ?>index.php" class="nav-logo">
      <img src="<?= $base_url??'../' ?>assets/images/logo.png" alt="رحّال" style="height:70px;width:auto;">
    </a>

    <!-- زر الجوال -->
    <button class="nav-toggle ms-auto" id="navToggle" onclick="toggleNav()">
      <i class="fas fa-bars"></i>
    </button>

    <!-- الروابط -->
    <ul class="nav-links" id="navLinks">
      <li><a href="<?= $base_url??'../' ?>index.php"
             class="<?= ($cp==='index.php')?'active':'' ?>">الرئيسية</a></li>
      <li><a href="<?= $base_url??'../' ?>pages/hotels.php"
             class="<?= in_array($cp, ['hotels.php','hotel-details.php'], true)?'active':'' ?>">الفنادق</a></li>
      <li><a href="<?= $base_url??'../' ?>pages/experiences.php"
             class="<?= ($cp==='experiences.php')?'active':'' ?>">التجارب</a></li>
      <li class="nav-dropdown">
        <a href="#" onclick="return false">الوجهات <i class="fas fa-chevron-down" style="font-size:10px;margin-right:4px;"></i></a>
        <div class="nav-dropdown-menu">
          <a href="<?= $base_url??'../' ?>pages/experiences.php?city=نيوم"><i class="fas fa-map-marker-alt ms-2 text-gold"></i>نيوم</a>
          <a href="<?= $base_url??'../' ?>pages/experiences.php?city=السويد"><i class="fas fa-map-marker-alt ms-2 text-gold"></i>السويد</a>
          <a href="<?= $base_url??'../' ?>pages/experiences.php?city=المالديف"><i class="fas fa-map-marker-alt ms-2 text-gold"></i>المالديف</a>
          <a href="<?= $base_url??'../' ?>pages/experiences.php?city=إدنبرة"><i class="fas fa-map-marker-alt ms-2 text-gold"></i>إدنبرة</a>
        </div>
      </li>
      <?php if(isset($_SESSION['user_id'])): ?>
      <li><a href="<?= $base_url??'../' ?>pages/my-bookings.php"
             class="<?= ($cp==='my-bookings.php')?'active':'' ?>">حجوزاتي</a></li>
      <?php endif; ?>
      <li><a href="<?= $base_url??'../' ?>pages/about.php"
             class="<?= ($cp==='about.php')?'active':'' ?>">من نحن</a></li>
      <li><a href="<?= $base_url??'../' ?>pages/contact.php"
             class="<?= ($cp==='contact.php')?'active':'' ?>">تواصل معنا</a></li>
    </ul>

    <!-- أزرار المستخدم -->
    <div class="nav-actions" id="navActions">
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="nav-dropdown">
          <button class="btn-user">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['user_name']??'حسابي') ?>
            <i class="fas fa-chevron-down" style="font-size:10px;"></i>
          </button>
          <div class="nav-dropdown-menu" style="left:0;right:auto;">
            <a href="<?= $base_url??'../' ?>pages/my-bookings.php">
              <i class="fas fa-calendar-check ms-2 text-gold"></i>حجوزاتي
            </a>
            <hr style="border-color:var(--border);margin:6px 0;">
            <a href="<?= $base_url??'../' ?>pages/logout.php" style="color:#e74c3c;">
              <i class="fas fa-sign-out-alt ms-2"></i>خروج
            </a>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= $base_url??'../' ?>pages/login.php" class="btn-ghost">دخول</a>
        <a href="<?= $base_url??'../' ?>pages/register.php" class="btn-solid">تسجيل مجاني</a>
      <?php endif; ?>
    </div>

  </nav>
</div>
</header>

<script>
function toggleNav(){
  document.getElementById('navLinks').classList.toggle('open');
  document.getElementById('navActions').classList.toggle('open');
}
window.addEventListener('scroll',()=>{
  document.getElementById('siteHeader').classList.toggle('scrolled',scrollY>60);
});
</script>
