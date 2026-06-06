<?php
session_start();
if (isset($_SESSION['user_id'])) { header('Location: ../index.php'); exit; }
require_once '../includes/connection.php';
$base_url = '../'; $page_title = 'تسجيل الدخول'; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email)||empty($password)) {
        $error = 'يرجى إدخال البريد الإلكتروني وكلمة المرور.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'صيغة البريد الإلكتروني غير صحيحة.';
    } else {
        $stmt = mysqli_prepare($conn,"SELECT user_id,first_name,last_name,email,password FROM users WHERE email=?");
        mysqli_stmt_bind_param($stmt,'s',$email);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_name']  = $user['first_name'].' '.$user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $redirect = $_GET['redirect'] ?? '../index.php';
            header('Location: '.$redirect); exit;
        } else { $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>تسجيل الدخول | رحّال</title>
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
        <div class="col-lg-5 col-md-8">
            <div class="auth-card">
                <div class="auth-header">
                    <img src="../assets/images/logo.svg" alt="رحّال" class="auth-logo" onerror="this.style.display='none'">
                    <h2 class="auth-title">مرحباً بعودتك</h2>
                    <p class="auth-subtitle">سجّل دخولك للوصول إلى حسابك وحجوزاتك</p>
                </div>
                <div class="auth-body">
                    <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success alert-rahhal alert-auto-close mb-4"><i class="fas fa-check-circle ms-2"></i>تم التسجيل بنجاح! مرحباً بك في رحّال.</div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-rahhal alert-auto-close mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <form id="loginForm" method="POST" novalidate>
                        <div class="mb-4">
                            <label class="form-label-rahhal" for="login_email">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-envelope text-gold"></i></span>
                                <input type="email" id="login_email" name="email" class="form-control form-control-rahhal" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0!important;" placeholder="example@email.com" value="<?php echo htmlspecialchars($_POST['email']??''); ?>">
                            </div>
                            <div class="error-msg" id="login_email_error"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-rahhal" for="login_password">كلمة المرور <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="login_password" name="password" class="form-control form-control-rahhal" style="border-radius:var(--radius-sm) 0 0 var(--radius-sm)!important;" placeholder="أدخل كلمة المرور">
                                <button type="button" class="btn toggle-password input-group-text" data-target="login_password" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-eye text-gold"></i></button>
                            </div>
                            <div class="error-msg" id="login_password_error"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember" style="font-size:13px;">تذكرني</label>
                            </div>
                            <a href="#" class="text-gold text-decoration-none" style="font-size:13px;font-weight:600;">نسيت كلمة المرور؟</a>
                        </div>
                        <button type="submit" class="btn-auth"><i class="fas fa-sign-in-alt ms-2"></i>تسجيل الدخول</button>
                    </form>
                    <div class="text-center mt-4 pt-3" style="border-top:1px solid var(--beige);">
                        <p class="mb-0" style="font-size:14px;">ليس لديك حساب؟ <a href="register.php" class="text-gold fw-bold text-decoration-none">سجّل الآن مجاناً <i class="fas fa-arrow-left ms-1"></i></a></p>
                    </div>
                    <div class="mt-3 p-3 rounded" style="background:rgba(176,141,87,.08);border:1px dashed var(--beige);">
                        <p class="mb-0" style="font-size:12px;color:#666;"><i class="fas fa-info-circle ms-1 text-gold"></i>تجريبي: <code>user@rahhal.sa</code> | كلمة المرور: <code>password</code></p>
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
