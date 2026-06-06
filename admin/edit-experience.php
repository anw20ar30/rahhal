<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title = 'تعديل التجربة';
$id = (int)($_GET['id']??0);
if (!$id) { header('Location: experiences.php'); exit; }
$stmt=mysqli_prepare($conn,"SELECT * FROM experiences WHERE experience_id=?");
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt);
$exp=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$exp) { header('Location: experiences.php'); exit; }
$cats=mysqli_fetch_all(mysqli_query($conn,"SELECT * FROM categories ORDER BY category_name"),MYSQLI_ASSOC);
$error='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title=$_POST['title']??''; $hotel=$_POST['hotel_name']??''; $desc=$_POST['description']??'';
    $city=$_POST['city']??''; $address=$_POST['address']??''; $price=(float)($_POST['price_per_night']??0);
    $rating=(float)($_POST['rating']??4.5); $rooms=(int)($_POST['available_rooms']??10);
    $cat_id=(int)($_POST['category_id']??0); $is_feat=isset($_POST['is_featured'])?1:0;
    $img_name=$exp['image'];
    if (empty($title)||empty($hotel)||empty($desc)||empty($city)||$price<=0||!$cat_id) { $error='يرجى ملء جميع الحقول المطلوبة.'; }
    else {
        if (!empty($_FILES['image']['name'])) {
            $allowed=['jpg','jpeg','png','webp'];
            $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
            if (in_array($ext,$allowed)&&$_FILES['image']['size']<=5*1024*1024) {
                $nn='exp_'.time().'_'.rand(1000,9999).'.'.$ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$nn)) $img_name=$nn;
            }
        }
        $upd=mysqli_prepare($conn,"UPDATE experiences SET category_id=?,title=?,hotel_name=?,description=?,city=?,address=?,image=?,price_per_night=?,rating=?,available_rooms=?,is_featured=? WHERE experience_id=?");
        mysqli_stmt_bind_param($upd,'issssssddiii',$cat_id,$title,$hotel,$desc,$city,$address,$img_name,$price,$rating,$rooms,$is_feat,$id);
        if (mysqli_stmt_execute($upd)) { header('Location: experiences.php?updated=1'); exit; }
        else { $error='حدث خطأ أثناء التحديث.'; }
    }
    $exp=array_merge($exp,$_POST);
}
include 'includes/admin_header.php';
?>
<div class="row justify-content-center"><div class="col-lg-9">
<div class="booking-form-card">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-800 text-brown mb-0"><i class="fas fa-edit ms-2 text-gold"></i>تعديل التجربة</h4>
        <a href="experiences.php" class="btn-outline" style="font-size:13px!important;padding:7px 14px!important;"><i class="fas fa-arrow-right ms-1"></i>رجوع</a>
    </div>
    <?php if ($error): ?><div class="alert alert-danger alert-rahhal mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form id="experienceForm" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-lbl">اسم التجربة <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="form-control form-control-rahhal" value="<?php echo htmlspecialchars($exp['title']); ?>">
                <div class="error-msg" id="title_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">اسم الفندق <span class="text-danger">*</span></label>
                <input type="text" id="hotel_name" name="hotel_name" class="form-control form-control-rahhal" value="<?php echo htmlspecialchars($exp['hotel_name']); ?>">
                <div class="error-msg" id="hotel_name_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">الفئة <span class="text-danger">*</span></label>
                <select id="category_id" name="category_id" class="form-select form-select-rahhal">
                    <?php foreach($cats as $cat): ?><option value="<?php echo $cat['category_id']; ?>" <?php echo ($exp['category_id']==$cat['category_id'])?'selected':''; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option><?php endforeach; ?>
                </select>
                <div class="error-msg" id="category_id_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">المدينة <span class="text-danger">*</span></label>
                <select id="city" name="city" class="form-select form-select-rahhal">
                    <?php foreach(['جدة','الرياض','الطائف','الدمام','العلا','أبها','مكة المكرمة','المدينة المنورة'] as $c): ?><option <?php echo ($exp['city']===$c)?'selected':''; ?>><?php echo $c; ?></option><?php endforeach; ?>
                </select>
                <div class="error-msg" id="city_error"></div>
            </div>
            <div class="col-12">
                <label class="form-lbl">العنوان</label>
                <input type="text" name="address" class="form-control form-control-rahhal" value="<?php echo htmlspecialchars($exp['address']??''); ?>">
            </div>
            <div class="col-12">
                <label class="form-lbl">وصف التجربة <span class="text-danger">*</span></label>
                <textarea id="description" name="description" class="form-control form-control-rahhal" rows="4"><?php echo htmlspecialchars($exp['description']); ?></textarea>
                <div class="error-msg" id="description_error"></div>
            </div>
            <div class="col-md-4">
                <label class="form-lbl">السعر (ر.س) <span class="text-danger">*</span></label>
                <input type="number" id="price_per_night" name="price_per_night" class="form-control form-control-rahhal" step="0.01" min="0" value="<?php echo $exp['price_per_night']; ?>">
                <div class="error-msg" id="price_per_night_error"></div>
            </div>
            <div class="col-md-4">
                <label class="form-lbl">التقييم</label>
                <input type="number" name="rating" class="form-control form-control-rahhal" step="0.1" min="1" max="5" value="<?php echo $exp['rating']; ?>">
            </div>
            <div class="col-md-4">
                <label class="form-lbl">الغرف المتاحة</label>
                <input type="number" name="available_rooms" class="form-control form-control-rahhal" min="0" value="<?php echo $exp['available_rooms']; ?>">
            </div>
            <div class="col-12">
                <label class="form-lbl">صورة جديدة (اتركها فارغة للإبقاء على الحالية)</label>
                <input type="file" id="image_upload" name="image" accept="image/*" class="form-control form-control-rahhal">
                <div class="mt-2"><img id="image_preview" src="../assets/images/<?php echo htmlspecialchars($exp['image']); ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=200&q=60'" style="max-height:120px;border-radius:8px;object-fit:cover;"></div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" <?php echo $exp['is_featured']?'checked':''; ?>>
                    <label class="form-check-label fw-700 text-brown" for="is_featured"><i class="fas fa-star ms-1 text-gold"></i>تجربة مميزة</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-3">
                <button type="submit" class="btn-solid"><i class="fas fa-save ms-2"></i>حفظ التغييرات</button>
                <a href="experiences.php" class="btn-outline">إلغاء</a>
            </div>
        </div>
    </form>
</div>
</div></div>
<?php include 'includes/admin_footer.php'; ?>
