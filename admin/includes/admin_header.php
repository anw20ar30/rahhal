<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?php echo isset($admin_title)?$admin_title.' | لوحة تحكم رحّال':'لوحة تحكم رحّال'; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css?v=1780769327">
<link rel="icon" type="image/png" href="../assets/images/logo.png">
<style>
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Regular.woff2') format('woff2');font-weight:400;font-display:swap;}
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Medium.woff2') format('woff2');font-weight:500;font-display:swap;}
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Bold.woff2') format('woff2');font-weight:700;font-display:swap;}
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Black.woff2') format('woff2');font-weight:900;font-display:swap;}
body,*{font-family:'ThmanyahSans','Tajawal',sans-serif!important;}
body{background:#f4f4f4;}.admin-wrap{display:flex;min-height:100vh;}/* also: */ .admin-layout{display:flex;min-height:100vh;}
</style>
</head>
<body>
<div class="admin-wrap">
<aside class="admin-side">
    <div class="admin-brand">
        <img src="../assets/images/logo.svg" alt="رحّال" onerror="this.style.display='none'">
        <div><div class="admin-brand-name">رحّال</div><div class="admin-brand-role">لوحة التحكم</div></div>
    </div>
    <nav class="admin-nav">
        <?php $cp=basename($_SERVER['PHP_SELF']); ?>
        <a href="dashboard.php" class="admin-nav-item <?php echo $cp=='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
        <a href="experiences.php" class="admin-nav-item <?php echo in_array($cp,['experiences.php','add-experience.php','edit-experience.php'])?'active':''; ?>"><i class="fas fa-hotel"></i> إدارة التجارب</a>
        <a href="bookings.php" class="admin-nav-item <?php echo $cp=='bookings.php'?'active':''; ?>"><i class="fas fa-calendar-check"></i> الحجوزات</a>
        <a href="users.php" class="admin-nav-item <?php echo $cp=='users.php'?'active':''; ?>"><i class="fas fa-users"></i> المستخدمون</a>
        <a href="categories.php" class="admin-nav-item <?php echo $cp=='categories.php'?'active':''; ?>"><i class="fas fa-tags"></i> الفئات</a>
        <div style="border-top:1px solid rgba(255,255,255,.1);margin:12px 0;"></div>
        <a href="../index.php" class="admin-nav-item" target="_blank"><i class="fas fa-external-link-alt"></i> عرض الموقع</a>
        <a href="logout.php" class="admin-nav-item" style="color:rgba(231,76,60,.8);"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
    </nav>
</aside>
<div class="admin-main">
    <div class="admin-topbar">
        <div class="admin-page-title"><?php echo $admin_title??'لوحة التحكم'; ?></div>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:13px;color:#888;"><i class="fas fa-user-shield ms-1 text-gold"></i><?php echo htmlspecialchars($_SESSION['admin_username']??'مشرف'); ?></span>
            <a href="logout.php" class="btn btn-sm" style="background:rgba(231,76,60,.08);color:#e74c3c;border:none;border-radius:8px;font-family:'Tajawal',sans-serif;"><i class="fas fa-sign-out-alt ms-1"></i>خروج</a>
        </div>
    </div>
    <div class="admin-content">
