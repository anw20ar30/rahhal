<?php
session_start();
if (isset($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
require_once '../includes/connection.php';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username']??''); $password = $_POST['password']??'';
    if (empty($username)||empty($password)) { $error='يرجى إدخال اسم المستخدم وكلمة المرور.'; }
    else {
        $stmt = mysqli_prepare($conn,"SELECT * FROM admins WHERE username=?");
        mysqli_stmt_bind_param($stmt,'s',$username); mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if ($admin && password_verify($password,$admin['password'])) {
            $_SESSION['admin_id']=$admin['admin_id']; $_SESSION['admin_username']=$admin['username'];
            header('Location: dashboard.php'); exit;
        } else { $error='اسم المستخدم أو كلمة المرور غير صحيحة.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>دخول الإدارة | رحّال</title>
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
<body class="auth-page" style="background:linear-gradient(135deg,#2f2f2f,#3d2b1e);">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-7">
            <div class="auth-card">
                <div class="auth-header">
                    <img src="../assets/images/logo.svg" alt="رحّال" class="auth-logo" onerror="this.style.display='none'">
                    <h2 class="auth-title">لوحة التحكم</h2>
                    <p class="auth-subtitle">دخول المشرفين فقط</p>
                </div>
                <div class="auth-body">
                    <?php if ($error): ?><div class="alert alert-danger alert-rahhal mb-3"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label-rahhal">اسم المستخدم</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-user text-gold"></i></span>
                                <input type="text" name="username" class="form-control form-control-rahhal" style="border-radius:0 var(--radius-sm) var(--radius-sm) 0!important;" placeholder="admin" value="<?php echo htmlspecialchars($_POST['username']??''); ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-rahhal">كلمة المرور</label>
                            <div class="input-group">
                                <input type="password" id="adminPass" name="password" class="form-control form-control-rahhal" style="border-radius:var(--radius-sm) 0 0 var(--radius-sm)!important;" placeholder="كلمة المرور">
                                <button type="button" class="toggle-password input-group-text btn" data-target="adminPass" style="background:var(--cream);border-color:var(--beige);"><i class="fas fa-eye text-gold"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn-auth"><i class="fas fa-shield-alt ms-2"></i>دخول لوحة التحكم</button>
                    </form>
                    <div class="mt-3 p-3 rounded" style="background:rgba(176,141,87,.08);border:1px dashed var(--beige);">
                        <p class="mb-0" style="font-size:12px;color:#666;"><i class="fas fa-info-circle ms-1 text-gold"></i>المستخدم: <code>admin</code> | كلمة المرور: <code>password</code></p>
                    </div>
                    <div class="text-center mt-3"><a href="../index.php" style="font-size:13px;color:var(--dark-brown);text-decoration:none;"><i class="fas fa-arrow-right ms-1"></i>الموقع الرئيسي</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
