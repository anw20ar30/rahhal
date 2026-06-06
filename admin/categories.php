<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title='إدارة الفئات';
$msg=''; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['add_cat'])) {
    $name=trim($_POST['cat_name']??''); $icon=trim($_POST['cat_icon']??'fas fa-hotel');
    if (!empty($name)) {
        $stmt=mysqli_prepare($conn,"INSERT INTO categories (category_name,category_icon) VALUES (?,?)");
        mysqli_stmt_bind_param($stmt,'ss',$name,$icon);
        mysqli_stmt_execute($stmt)?($msg='تم إضافة الفئة بنجاح.'):($error='حدث خطأ.');
    }
}
if (isset($_GET['delete'])) {
    $did=(int)$_GET['delete'];
    $s=mysqli_prepare($conn,"DELETE FROM categories WHERE category_id=?");
    mysqli_stmt_bind_param($s,'i',$did); mysqli_stmt_execute($s); $msg='تم حذف الفئة.';
}
$cats=mysqli_fetch_all(mysqli_query($conn,"SELECT c.*,COUNT(e.experience_id) as ec FROM categories c LEFT JOIN experiences e ON c.category_id=e.category_id GROUP BY c.category_id ORDER BY c.category_id"),MYSQLI_ASSOC);
include 'includes/admin_header.php';
?>
<?php if($msg): ?><div class="alert alert-success alert-rahhal alert-auto-close mb-4"><i class="fas fa-check-circle ms-2"></i><?php echo $msg; ?></div><?php endif; ?>
<?php if($error): ?><div class="alert alert-danger alert-rahhal alert-auto-close mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo $error; ?></div><?php endif; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="booking-form-card">
            <h5 class="fw-800 text-brown mb-3"><i class="fas fa-plus ms-2 text-gold"></i>إضافة فئة جديدة</h5>
            <form method="POST">
                <div class="mb-3"><label class="form-lbl">اسم الفئة</label><input type="text" name="cat_name" class="form-control form-control-rahhal" placeholder="مثال: منتجعات بحرية"></div>
                <div class="mb-3"><label class="form-lbl">أيقونة (Font Awesome)</label><input type="text" name="cat_icon" class="form-control form-control-rahhal" placeholder="fas fa-hotel" value="fas fa-hotel"></div>
                <button type="submit" name="add_cat" class="btn-primary-rahhal w-100"><i class="fas fa-plus ms-1"></i>إضافة الفئة</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-head"><h5 class="fw-800 text-brown mb-0"><i class="fas fa-tags ms-2 text-gold"></i>الفئات (<?php echo count($cats); ?>)</h5></div>
            <div class="table-responsive">
                <table class="table table-rahhal">
                    <thead><tr><th>#</th><th>الأيقونة</th><th>اسم الفئة</th><th>عدد التجارب</th><th>حذف</th></tr></thead>
                    <tbody>
                        <?php foreach($cats as $cat): ?>
                        <tr>
                            <td><?php echo $cat['category_id']; ?></td>
                            <td><i class="<?php echo htmlspecialchars($cat['category_icon']); ?> text-gold fs-5"></i></td>
                            <td class="fw-700"><?php echo htmlspecialchars($cat['category_name']); ?></td>
                            <td><span class="badge-status badge-confirmed"><?php echo $cat['ec']; ?></span></td>
                            <td><?php if($cat['ec']==0): ?><a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-delete-exp" style="background:rgba(231,76,60,.1);color:#e74c3c;border:none;border-radius:6px;padding:5px 10px;font-family:'ThmanyahSans',sans-serif;font-size:12px;"><i class="fas fa-trash"></i></a><?php else: ?><span style="font-size:11px;color:#aaa;">لا يمكن الحذف</span><?php endif; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
