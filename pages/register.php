<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: ../index.php'); exit; }
require_once '../includes/connection.php';
$base_url = '../'; $page_title = 'إنشاء حساب'; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = trim($_POST['first_name']??''); $ln = trim($_POST['last_name']??'');
    $em = trim($_POST['email']??'');      $pw = $_POST['password']??'';
    $cp = $_POST['confirm_password']??''; $ad = trim($_POST['address']??'');
    $mo = trim($_POST['mobile']??'');
    if (empty($fn)||empty($ln)||empty($em)||empty($pw)||empty($mo)) {
        $error = 'جميع الحقول المطلوبة يجب أن تكون مملوءة.';
    } elseif (!filter_var($em,FILTER_VALIDATE_EMAIL)) {
        $error = 'صيغة البريد الإلكتروني غير صحيحة.';
    } elseif (strlen($pw)<6) {
        $error = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.';
    } elseif ($pw!==$cp) {
        $error = 'كلمة المرور وتأكيدها غير متطابقتين.';
    } else {
        $chk = mysqli_prepare($conn,"SELECT user_id FROM users WHERE email=?");
        mysqli_stmt_bind_param($chk,'s',$em); mysqli_stmt_execute($chk); mysqli_stmt_store_result($chk);
        if (mysqli_stmt_num_rows($chk)>0) {
            $error = 'البريد الإلكتروني مسجّل مسبقاً.';
        } else {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $ins  = mysqli_prepare($conn,"INSERT INTO users (first_name,last_name,email,password,address,mobile) VALUES (?,?,?,?,?,?)");
            mysqli_stmt_bind_param($ins,'ssssss',$fn,$ln,$em,$hash,$ad,$mo);
            if (mysqli_stmt_execute($ins)) {
                $_SESSION['user_id']    = mysqli_insert_id($conn);
                $_SESSION['user_name']  = $fn.' '.$ln;
                $_SESSION['user_email'] = $em;
                header('Location: ../index.php?registered=1'); exit;
            } else { $error = 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.'; }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>إنشاء حساب | رحّال</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css?v=1780769327">
<style>
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Regular.woff2') format('woff2');font-weight:400;font-display:swap;}
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Bold.woff2') format('woff2');font-weight:700;font-display:swap;}
@font-face{font-family:'ThmanyahSans';src:url('../assets/fonts/thmanyahsans-Black.woff2') format('woff2');font-weight:900;font-display:swap;}
body,*{font-family:'ThmanyahSans','Tajawal',sans-serif!important;}
</style>
</head>
<body class="auth-page">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="auth-card">
                <div class="auth-header">
                    <img src="../assets/images/logo.svg" alt="رحّال" class="auth-logo" onerror="this.style.display='none'">
                    <h2 class="auth-title">إنشاء حساب جديد</h2>
                    <p class="auth-subtitle">انضم إلى مجتمع رحّال واحجز تجربتك المميزة</p>
                </div>
                <div class="auth-body">
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-rahhal alert-auto-close mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form id="registerForm" method="POST" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="first_name">الاسم الأول <span class="text-danger">*</span></label>
                                <input type="text" id="first_name" name="first_name" class="form-control form-control-rahhal" placeholder="أدخل اسمك الأول" value="<?php echo htmlspecialchars($_POST['first_name']??''); ?>">
                                <div class="error-msg" id="first_name_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="last_name">اسم العائلة <span class="text-danger">*</span></label>
                                <input type="text" id="last_name" name="last_name" class="form-control form-control-rahhal" placeholder="أدخل اسم العائلة" value="<?php echo htmlspecialchars($_POST['last_name']??''); ?>">
                                <div class="error-msg" id="last_name_error"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label-rahhal" for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-envelope text-gold"></i></span>
                                    <input type="email" id="email" name="email" class="form-control form-control-rahhal" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0!important;" placeholder="example@email.com" value="<?php echo htmlspecialchars($_POST['email']??''); ?>">
                                </div>
                                <div class="error-msg" id="email_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="password">كلمة المرور <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control form-control-rahhal" style="border-radius:var(--radius-sm) 0 0 var(--radius-sm)!important;" placeholder="6 أحرف على الأقل">
                                    <button type="button" class="btn toggle-password input-group-text" data-target="password" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-eye text-gold"></i></button>
                                </div>
                                <div class="mt-2" style="height:4px;background:#eee;border-radius:2px;"><div id="password_strength" style="height:100%;width:0;border-radius:2px;transition:all .3s;"></div></div>
                                <div class="error-msg" id="password_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="confirm_password">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control form-control-rahhal" style="border-radius:var(--radius-sm) 0 0 var(--radius-sm)!important;" placeholder="أعد إدخال كلمة المرور">
                                    <button type="button" class="btn toggle-password input-group-text" data-target="confirm_password" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-eye text-gold"></i></button>
                                </div>
                                <div class="error-msg" id="confirm_password_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="mobile">رقم الجوال <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:var(--cream);border-color:var(--beige);font-size:12px;font-weight:700;"><i class="fas fa-flag ms-1"></i>+966</span>
                                    <input type="tel" id="mobile" name="mobile" class="form-control form-control-rahhal" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0!important;" placeholder="05XXXXXXXX" value="<?php echo htmlspecialchars($_POST['mobile']??''); ?>">
                                </div>
                                <div class="error-msg" id="mobile_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-rahhal" for="address">العنوان</label>
                                <input type="text" id="address" name="address" class="form-control form-control-rahhal" placeholder="مثال: الرياض، حي النخيل" value="<?php echo htmlspecialchars($_POST['address']??''); ?>">
                                <div class="error-msg" id="address_error"></div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms" style="font-size:13px;">أوافق على <a href="#" class="text-gold fw-bold">الشروط والأحكام</a> و<a href="#" class="text-gold fw-bold">سياسة الخصوصية</a></label>
                                </div>
                            </div>
                            <div class="col-12"><button type="submit" class="btn-auth"><i class="fas fa-user-plus ms-2"></i>إنشاء الحساب</button></div>
                        </div>
                    </form>
                    <div class="text-center mt-4 pt-3" style="border-top:1px solid var(--beige);">
                        <p class="mb-0" style="font-size:14px;">لديك حساب بالفعل؟ <a href="login.php" class="text-gold fw-bold text-decoration-none">سجّل الدخول <i class="fas fa-arrow-left ms-1"></i></a></p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4"><a href="../index.php" style="font-size:14px;color:var(--dark-brown);text-decoration:none;"><i class="fas fa-arrow-right ms-2"></i>العودة للرئيسية</a></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/validation.js"></script>
</body>
</html>
