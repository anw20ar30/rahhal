<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=dashboard.php');
    exit;
}

require_once '../includes/connection.php';

$base_url = '../';
$page_title = 'لوحة المستخدم';
$uid = (int)$_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');

    if ($first_name === '' || $last_name === '' || $mobile === '') {
        $error = 'يرجى تعبئة الاسم ورقم الجوال.';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, last_name=?, address=?, mobile=? WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, 'ssssi', $first_name, $last_name, $address, $mobile, $uid);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $success = 'تم تحديث بيانات الحساب بنجاح.';
        } else {
            $error = 'تعذر تحديث البيانات، يرجى المحاولة مرة أخرى.';
        }
    }
}

$user_stmt = mysqli_prepare($conn, "SELECT first_name,last_name,email,address,mobile,created_at FROM users WHERE user_id=?");
mysqli_stmt_bind_param($user_stmt, 'i', $uid);
mysqli_stmt_execute($user_stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($user_stmt));

$booking_stmt = mysqli_prepare($conn, "SELECT b.*,e.title,e.hotel_name,e.city,e.image FROM bookings b JOIN experiences e ON b.experience_id=e.experience_id WHERE b.user_id=? ORDER BY b.booking_date DESC LIMIT 3");
mysqli_stmt_bind_param($booking_stmt, 'i', $uid);
mysqli_stmt_execute($booking_stmt);
$bookings = mysqli_fetch_all(mysqli_stmt_get_result($booking_stmt), MYSQLI_ASSOC);

include '../includes/header.php';
?>

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-rahhal mb-3">
        <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
        <li class="breadcrumb-item active">لوحة المستخدم</li>
      </ol>
    </nav>
    <h1 class="page-hero-title"><i class="fas fa-user-circle ms-3" style="color:var(--gold-lt);"></i>لوحة المستخدم</h1>
    <p class="page-hero-sub">إدارة الملف الشخصي، الحجوزات، والعناصر المحفوظة.</p>
  </div>
</div>

<section class="section section-alt">
  <div class="container">
    <?php if ($success): ?><div class="alert-rh success"><i class="fas fa-check-circle ms-2"></i><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert-rh danger"><i class="fas fa-exclamation-circle ms-2"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="dashboard-grid">
      <aside class="filters-panel">
        <div class="dashboard-user">
          <div class="dashboard-avatar"><?= htmlspecialchars(mb_substr($user['first_name'] ?? 'ر', 0, 1, 'UTF-8')) ?></div>
          <h3><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></h3>
          <p><?= htmlspecialchars($user['email'] ?? '') ?></p>
        </div>
        <a class="dashboard-link active" href="#profile"><i class="fas fa-user ms-2"></i>الملف الشخصي</a>
        <a class="dashboard-link" href="#reservations"><i class="fas fa-calendar-check ms-2"></i>الحجوزات</a>
        <a class="dashboard-link" href="#saved"><i class="fas fa-heart ms-2"></i>المحفوظات</a>
        <a class="dashboard-link" href="#settings"><i class="fas fa-cog ms-2"></i>إعدادات الحساب</a>
      </aside>

      <main class="dashboard-main">
        <section id="profile" class="info-card mb-4">
          <h3 class="fw-800 text-brown mb-4"><i class="fas fa-user ms-2 text-gold"></i>الملف الشخصي</h3>
          <form method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-lbl" for="first_name">الاسم الأول</label>
                <input class="form-inp" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-lbl" for="last_name">اسم العائلة</label>
                <input class="form-inp" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-lbl" for="mobile">رقم الجوال</label>
                <input class="form-inp" id="mobile" name="mobile" value="<?= htmlspecialchars($user['mobile'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-lbl">البريد الإلكتروني</label>
                <input class="form-inp" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled>
              </div>
              <div class="col-12">
                <label class="form-lbl" for="address">العنوان</label>
                <input class="form-inp" id="address" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
              </div>
              <div class="col-12">
                <button type="submit" class="btn-auth"><i class="fas fa-save ms-2"></i>حفظ التغييرات</button>
              </div>
            </div>
          </form>
        </section>

        <section id="reservations" class="info-card mb-4">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <h3 class="fw-800 text-brown mb-0"><i class="fas fa-calendar-check ms-2 text-gold"></i>آخر الحجوزات</h3>
            <a href="my-bookings.php" class="btn-outline">عرض الكل</a>
          </div>
          <?php if (empty($bookings)): ?>
            <div class="empty-state" style="padding:40px 20px;"><i class="fas fa-calendar-times"></i><h3>لا توجد حجوزات بعد</h3><p>ابدأ باستكشاف الفنادق والتجارب المتاحة.</p></div>
          <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
            <div class="dashboard-booking">
              <img src="../assets/images/<?= htmlspecialchars($booking['image']) ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=300&q=75'" alt="">
              <div>
                <h4><?= htmlspecialchars($booking['title']) ?></h4>
                <p><?= htmlspecialchars($booking['hotel_name']) ?> - <?= htmlspecialchars($booking['city']) ?></p>
                <small><?= htmlspecialchars($booking['check_in']) ?> إلى <?= htmlspecialchars($booking['check_out']) ?></small>
              </div>
              <span class="badge-status badge-<?= htmlspecialchars($booking['status']) ?>"><?= htmlspecialchars($booking['status']) ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </section>

        <section id="saved" class="info-card mb-4">
          <h3 class="fw-800 text-brown mb-3"><i class="fas fa-heart ms-2 text-gold"></i>العناصر المحفوظة</h3>
          <div class="empty-state" style="padding:40px 20px;">
            <i class="far fa-heart"></i>
            <h3>لا توجد عناصر محفوظة</h3>
            <p>سيظهر هنا ما تحفظه من فنادق وتجارب عند إضافة جدول مفضلات لاحقا.</p>
          </div>
        </section>

        <section id="settings" class="info-card">
          <h3 class="fw-800 text-brown mb-3"><i class="fas fa-shield-alt ms-2 text-gold"></i>إعدادات الحساب</h3>
          <p style="color:#666;margin:0;">البريد الإلكتروني وكلمة المرور محميان. لتغيير كلمة المرور يمكن إضافة تدفق مخصص عند توفر متطلبات الأمان النهائية.</p>
        </section>
      </main>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
