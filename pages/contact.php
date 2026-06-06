<?php
session_start();
require_once '../includes/connection.php';

$base_url = '../';
$page_title = 'تواصل معنا';
$success = '';
$error = '';

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'يرجى ملء الاسم والبريد الإلكتروني والرسالة.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'صيغة البريد الإلكتروني غير صحيحة.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'تم إرسال رسالتك بنجاح! سنتواصل معك خلال 24 ساعة.';
            $_POST = [];
        } else {
            $error = 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
        }
    }
}

include '../includes/header.php';
?>

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-rahhal mb-3">
        <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
        <li class="breadcrumb-item active">تواصل معنا</li>
      </ol>
    </nav>
    <h1 class="page-hero-title"><i class="fas fa-envelope ms-3" style="color:var(--gold-lt);"></i>تواصل معنا</h1>
    <p class="page-hero-sub">فريق رحّال جاهز لمساعدتك في الحجز والاستفسارات.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="sec-eyebrow"><i class="fas fa-info-circle"></i> معلومات التواصل</div>
        <h2 class="sec-title">نحن هنا لمساعدتك</h2>
        <p style="color:#666;line-height:2;margin-bottom:28px;">أرسل رسالتك أو تواصل معنا مباشرة عبر الهاتف والبريد الإلكتروني.</p>

        <div class="contact-info-stack">
          <div class="contact-info-card">
            <i class="fas fa-phone-alt"></i>
            <div><strong>الهاتف</strong><span>920 000 111</span><small>خدمة العملاء 24/7</small></div>
          </div>
          <div class="contact-info-card">
            <i class="fas fa-envelope"></i>
            <div><strong>البريد الإلكتروني</strong><span>info@rahhal.sa</span><small>نرد خلال 24 ساعة</small></div>
          </div>
          <div class="contact-info-card">
            <i class="fas fa-map-marker-alt"></i>
            <div><strong>العنوان</strong><span>جدة، حي الروضة</span><small>المملكة العربية السعودية</small></div>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="info-card" style="padding:36px;">
          <h3 class="fw-800 text-brown mb-4"><i class="fas fa-paper-plane ms-2 text-gold"></i>أرسل لنا رسالة</h3>

          <?php if ($success): ?>
          <div class="alert-rh success mb-4"><i class="fas fa-check-circle ms-2"></i><?= htmlspecialchars($success) ?></div>
          <?php endif; ?>
          <?php if ($error): ?>
          <div class="alert-rh danger mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" novalidate>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-lbl" for="name">الاسم الكامل <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" class="form-inp" placeholder="أدخل اسمك الكامل" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-lbl" for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                <input id="email" type="email" name="email" class="form-inp" placeholder="example@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
              </div>
              <div class="col-12">
                <label class="form-lbl" for="subject">الموضوع</label>
                <select id="subject" name="subject" class="form-inp">
                  <?php $selected = $_POST['subject'] ?? ''; ?>
                  <?php foreach (['استفسار عن الحجز','مشكلة تقنية','اقتراح','شكوى','أخرى'] as $option): ?>
                  <option value="<?= htmlspecialchars($option) ?>" <?= $selected === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-12">
                <label class="form-lbl" for="message">الرسالة <span class="text-danger">*</span></label>
                <textarea id="message" name="message" class="form-inp" rows="6" placeholder="اكتب رسالتك هنا..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-auth"><i class="fas fa-paper-plane ms-2"></i>إرسال الرسالة</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
