<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title = 'إضافة تجربة جديدة';
$cats = mysqli_fetch_all(mysqli_query($conn,"SELECT * FROM categories ORDER BY category_name"),MYSQLI_ASSOC);
$error='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title   = trim($_POST['title']??'');         $hotel  = trim($_POST['hotel_name']??'');
    $desc    = trim($_POST['description']??'');   $city   = trim($_POST['city']??'');
    $address = trim($_POST['address']??'');       $price  = (float)($_POST['price_per_night']??0);
    $rating  = (float)($_POST['rating']??4.5);   $rooms  = (int)($_POST['available_rooms']??10);
    $cat_id  = (int)($_POST['category_id']??0);  $is_feat= isset($_POST['is_featured'])?1:0;
    $img_name = 'default.jpg';

    if (empty($title)||empty($hotel)||empty($desc)||empty($city)||$price<=0||!$cat_id) {
        $error='يرجى ملء جميع الحقول المطلوبة.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowed=['jpg','jpeg','png','webp'];
            $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
            if (!in_array($ext,$allowed)) { $error='نوع الصورة غير مسموح.'; }
            elseif ($_FILES['image']['size']>5*1024*1024) { $error='حجم الصورة يجب أن يكون أقل من 5MB.'; }
            else {
                $img_name='exp_'.time().'_'.rand(1000,9999).'.'.$ext;
                if (!move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$img_name)) {
                    $error='فشل رفع الصورة.'; $img_name='default.jpg';
                }
            }
        }
        if (!$error) {
            $stmt=mysqli_prepare($conn,"INSERT INTO experiences (category_id,title,hotel_name,description,city,address,image,price_per_night,rating,available_rooms,is_featured) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt,'issssssddii',$cat_id,$title,$hotel,$desc,$city,$address,$img_name,$price,$rating,$rooms,$is_feat);
            if (mysqli_stmt_execute($stmt)) { header('Location: experiences.php?added=1'); exit; }
            else { $error='حدث خطأ أثناء الحفظ.'; }
        }
    }
}
include 'includes/admin_header.php';
?>
<div class="row justify-content-center"><div class="col-lg-9">
<div class="booking-form-card">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-800 text-brown mb-0"><i class="fas fa-plus-circle ms-2 text-gold"></i>إضافة تجربة جديدة</h4>
        <a href="experiences.php" class="btn-outline" style="font-size:13px!important;padding:7px 14px!important;"><i class="fas fa-arrow-right ms-1"></i>رجوع</a>
    </div>
    <?php if ($error): ?><div class="alert alert-danger alert-rahhal mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form id="experienceForm" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-lbl">اسم التجربة <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title" class="form-control form-control-rahhal" placeholder="مثال: تجربة الإقامة الملكية" value="<?php echo htmlspecialchars($_POST['title']??''); ?>">
                <div class="error-msg" id="title_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">اسم الفندق <span class="text-danger">*</span></label>
                <input type="text" id="hotel_name" name="hotel_name" class="form-control form-control-rahhal" placeholder="مثال: فندق روزوود جدة" value="<?php echo htmlspecialchars($_POST['hotel_name']??''); ?>">
                <div class="error-msg" id="hotel_name_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">الفئة <span class="text-danger">*</span></label>
                <select id="category_id" name="category_id" class="form-select form-select-rahhal">
                    <option value="">-- اختر الفئة --</option>
                    <?php foreach($cats as $cat): ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php echo (($_POST['category_id']??'')==$cat['category_id'])?'selected':''; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg" id="category_id_error"></div>
            </div>
            <div class="col-md-6">
                <label class="form-lbl">المدينة <span class="text-danger">*</span></label>
                <select id="city" name="city" class="form-select form-select-rahhal">
                    <option value="">-- اختر المدينة --</option>
                    <?php foreach(['جدة','الرياض','الطائف','الدمام','العلا','أبها','مكة المكرمة','المدينة المنورة'] as $c): ?>
                    <option <?php echo (($_POST['city']??'')===$c)?'selected':''; ?>><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg" id="city_error"></div>
            </div>
            <div class="col-12">
                <label class="form-lbl">العنوان التفصيلي</label>
                <input type="text" name="address" class="form-control form-control-rahhal" placeholder="مثال: طريق الكورنيش الشمالي، جدة" value="<?php echo htmlspecialchars($_POST['address']??''); ?>">
            </div>
            <div class="col-12">
                <label class="form-lbl">وصف التجربة <span class="text-danger">*</span></label>
                <textarea id="description" name="description" class="form-control form-control-rahhal" rows="4" placeholder="اكتب وصفاً تفصيلياً..."><?php echo htmlspecialchars($_POST['description']??''); ?></textarea>
                <div class="error-msg" id="description_error"></div>
            </div>
            <div class="col-md-4">
                <label class="form-lbl">السعر لليلة (ر.س) <span class="text-danger">*</span></label>
                <input type="number" id="price_per_night" name="price_per_night" class="form-control form-control-rahhal" step="0.01" min="0" placeholder="0.00" value="<?php echo htmlspecialchars($_POST['price_per_night']??''); ?>">
                <div class="error-msg" id="price_per_night_error"></div>
            </div>
            <div class="col-md-4">
                <label class="form-lbl">التقييم (1-5)</label>
                <input type="number" name="rating" class="form-control form-control-rahhal" step="0.1" min="1" max="5" value="<?php echo htmlspecialchars($_POST['rating']??'4.5'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-lbl">الغرف المتاحة</label>
                <input type="number" name="available_rooms" class="form-control form-control-rahhal" min="0" value="<?php echo htmlspecialchars($_POST['available_rooms']??'10'); ?>">
            </div>
            <div class="col-12">
                <label class="form-lbl">صورة التجربة</label>
                <input type="file" id="image_upload" name="image" accept="image/*" class="form-control form-control-rahhal">
                <small class="text-muted">يُقبل: JPG, PNG, WEBP — الحجم الأقصى: 5MB</small>
                <div class="mt-2"><img id="image_preview" src="" style="display:none;max-height:150px;border-radius:8px;"></div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" <?php echo isset($_POST['is_featured'])?'checked':''; ?>>
                    <label class="form-check-label fw-700 text-brown" for="is_featured"><i class="fas fa-star ms-1 text-gold"></i>إضافة إلى التجارب المميزة</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-3">
                <button type="submit" class="btn-solid"><i class="fas fa-save ms-2"></i>حفظ التجربة</button>
                <a href="experiences.php" class="btn-outline"><i class="fas fa-times ms-2"></i>إلغاء</a>
            </div>
        </div>
    </form>
</div>
</div></div>
<?php include 'includes/admin_footer.php'; ?>
