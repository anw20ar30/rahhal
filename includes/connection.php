<?php
// =====================================================
// رحّال - اتصال قاعدة البيانات
// =====================================================
define('DB_HOST', getenv('MYSQL_HOST')     ?: getenv('MYSQLHOST')     ?: 'localhost');
define('DB_USER', getenv('MYSQL_USER')     ?: getenv('MYSQLUSER')     ?: 'root');
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQL_DATABASE') ?: getenv('MYSQLDATABASE') ?: 'rahhal_db');
define('DB_PORT', (int)(getenv('MYSQL_PORT') ?: getenv('MYSQLPORT') ?: 3306));

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if (!$conn) {
    die('<div style="text-align:center;padding:50px;font-family:Tajawal,Arial;direction:rtl;">
        <h2 style="color:#c00">خطأ في الاتصال بقاعدة البيانات</h2>
        <p>' . mysqli_connect_error() . '</p>
        <p>يرجى التأكد من تشغيل MySQL في XAMPP وصحة إعدادات الاتصال.</p>
    </div>');
}

mysqli_set_charset($conn, 'utf8mb4');

function clean($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return mysqli_real_escape_string($conn, $data);
}
