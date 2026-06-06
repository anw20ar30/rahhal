<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php?redirect='.urlencode($_SERVER['REQUEST_URI'])); exit; }
require_once '../includes/connection.php';
$base_url = '../';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: experiences.php'); exit; }

$stmt = mysqli_prepare($conn,"SELECT e.*,c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id WHERE e.experience_id=?");
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt);
$exp = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$exp) { header('Location: experiences.php'); exit; }
$page_title = 'حجز: '.$exp['title'];
$error=''; $success=false; $booking_id=0;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $ci    = $_POST['check_in']  ?? '';
    $co    = $_POST['check_out'] ?? '';
    $rooms = (int)($_POST['rooms'] ?? 1);
    $total = (float)($_POST['total_price'] ?? 0);
    $uid   = (int)$_SESSION['user_id'];
    if (empty($ci)||empty($co)) { $error='يرجى تحديد تاريخي الوصول والمغادرة.'; }
    elseif (strtotime($co)<=strtotime($ci)) { $error='يجب أن يكون تاريخ المغادرة بعد تاريخ الوصول.'; }
    elseif ($rooms<1||$rooms>$exp['available_rooms']) { $error='عدد الغرف غير صحيح.'; }
    elseif ($total<=0) { $error='يرجى التحقق من التفاصيل وإعادة الحساب.'; }
    else {
        $ins = mysqli_prepare($conn,"INSERT INTO bookings (user_id,experience_id,check_in,check_out,rooms,total_price) VALUES (?,?,?,?,?,?)");
        mysqli_stmt_bind_param($ins,'iissid',$uid,$id,$ci,$co,$rooms,$total);
        if (mysqli_stmt_execute($ins)) { $booking_id=mysqli_insert_id($conn); $success=true; }
        else { $error='حدث خطأ أثناء الحجز. يرجى المحاولة مرة أخرى.'; }
    }
}
include '../includes/header.php';
?>
<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb breadcrumb-rahhal mb-3">
            <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="experience-details.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($exp['title']); ?></a></li>
            <li class="breadcrumb-item active">الحجز</li>
        </ol></nav>
        <h1 class="page-hero-title"><i class="fas fa-calendar-check ms-3" style="color:var(--gold-light);"></i>احجز تجربتك الآن</h1>
    </div>
</div>

<section style="background:var(--cream);padding:50px 0;">
<div class="container">
<?php if ($success): ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="booking-form-card text-center py-5">
            <div style="width:90px;height:90px;background:rgba(39,174,96,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;"><i class="fas fa-check-circle" style="font-size:3rem;color:#27ae60;"></i></div>
            <h2 class="fw-900 text-brown mb-3">تم الحجز بنجاح! 🎉</h2>
            <p class="text-muted mb-1">رقم الحجز: <strong class="text-gold">#<?php echo str_pad($booking_id,6,'0',STR_PAD_LEFT); ?></strong></p>
            <p class="mb-4">تم تأكيد حجزك في <strong><?php echo htmlspecialchars($exp['hotel_name']); ?></strong>.</p>
            <div class="info-card mb-4" style="text-align:right;">
                <div class="info-item"><i class="fas fa-hotel"></i><div><strong>التجربة:</strong> <?php echo htmlspecialchars($exp['title']); ?></div></div>
                <div class="info-item"><i class="fas fa-calendar-alt"></i><div><strong>الوصول:</strong> <?php echo date('d/m/Y',strtotime($_POST['check_in'])); ?></div></div>
                <div class="info-item"><i class="fas fa-calendar-alt"></i><div><strong>المغادرة:</strong> <?php echo date('d/m/Y',strtotime($_POST['check_out'])); ?></div></div>
                <div class="info-item"><i class="fas fa-money-bill-wave"></i><div><strong>الإجمالي:</strong> <span class="fw-900 text-gold"><?php echo number_format((float)$_POST['total_price'],2); ?> ر.س</span></div></div>
            </div>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="my-bookings.php" class="btn-solid"><i class="fas fa-list-alt ms-2"></i>عرض حجوزاتي</a>
                <a href="experiences.php" class="btn-outline"><i class="fas fa-compass ms-2"></i>استكشف المزيد</a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="booking-form-card">
            <h3 class="fw-800 text-brown mb-4"><i class="fas fa-calendar-alt ms-2 text-gold"></i>تفاصيل الحجز</h3>
            <?php if ($error): ?><div class="alert alert-danger alert-rahhal alert-auto-close mb-4"><i class="fas fa-exclamation-circle ms-2"></i><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <form id="bookingForm" method="POST">
                <input type="hidden" id="price_per_night_val" value="<?php echo $exp['price_per_night']; ?>">
                <input type="hidden" id="total_price" name="total_price" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-rahhal" for="check_in"><i class="fas fa-plane-arrival ms-1 text-gold"></i>تاريخ الوصول <span class="text-danger">*</span></label>
                        <input type="date" id="check_in" name="check_in" class="form-control form-control-rahhal" min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($_POST['check_in']??''); ?>">
                        <div class="error-msg" id="check_in_error"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-rahhal" for="check_out"><i class="fas fa-plane-departure ms-1 text-gold"></i>تاريخ المغادرة <span class="text-danger">*</span></label>
                        <input type="date" id="check_out" name="check_out" class="form-control form-control-rahhal" min="<?php echo date('Y-m-d',strtotime('+1 day')); ?>" value="<?php echo htmlspecialchars($_POST['check_out']??''); ?>">
                        <div class="error-msg" id="check_out_error"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-rahhal" for="rooms"><i class="fas fa-door-open ms-1 text-gold"></i>عدد الغرف <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="qty-btn btn" data-target="rooms" data-action="minus" style="background:var(--beige);border:none;width:38px;height:38px;border-radius:8px;font-weight:900;font-size:1.1rem;">−</button>
                            <input type="number" id="rooms" name="rooms" class="form-control form-control-rahhal text-center" style="max-width:80px;" value="<?php echo (int)($_POST['rooms']??1); ?>" min="1" max="<?php echo $exp['available_rooms']; ?>">
                            <button type="button" class="qty-btn btn" data-target="rooms" data-action="plus" style="background:var(--gold);color:#fff;border:none;width:38px;height:38px;border-radius:8px;font-weight:900;font-size:1.1rem;">+</button>
                        </div>
                        <small class="text-muted">(متاح: <?php echo $exp['available_rooms']; ?> غرفة)</small>
                        <div class="error-msg" id="rooms_error"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-rahhal">ملخص سريع</label>
                        <div class="info-card" style="padding:12px 16px;">
                            <div class="d-flex justify-content-between mb-1" style="font-size:13px;"><span>عدد الليالي:</span><strong id="nights_display">—</strong></div>
                            <div class="d-flex justify-content-between" style="font-size:14px;"><span class="fw-700">الإجمالي:</span><strong id="total_price_display" style="color:var(--gold);">—</strong></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-rahhal">طلبات خاصة (اختياري)</label>
                        <textarea class="form-control form-control-rahhal" rows="3" placeholder="مثال: غرفة على المستوى العالي..."></textarea>
                    </div>
                    <div class="col-12"><button type="submit" class="btn-auth"><i class="fas fa-lock ms-2"></i>تأكيد الحجز الآن</button></div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="booking-widget">
            <img src="../assets/images/<?php echo htmlspecialchars($exp['image']); ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=500&q=75'" alt="" class="w-100 mb-3 rounded" style="height:200px;object-fit:cover;">
            <h5 class="fw-800 text-brown mb-1"><?php echo htmlspecialchars($exp['title']); ?></h5>
            <p class="text-muted mb-1" style="font-size:13px;"><i class="fas fa-hotel ms-1 text-gold"></i><?php echo htmlspecialchars($exp['hotel_name']); ?></p>
            <p class="text-muted mb-3" style="font-size:13px;"><i class="fas fa-map-marker-alt ms-1 text-gold"></i><?php echo htmlspecialchars($exp['city']); ?></p>
            <hr style="border-color:var(--beige);">
            <table class="price-summary-table w-100">
                <tbody>
                    <tr><td>السعر لليلة</td><td class="text-end"><?php echo number_format($exp['price_per_night'],0); ?> ر.س</td></tr>
                    <tr><td>عدد الليالي</td><td class="text-end" id="nights_display_2">—</td></tr>
                    <tr><td>عدد الغرف</td><td class="text-end" id="rooms_display">1 غرفة</td></tr>
                    <tr class="total-row"><td>الإجمالي</td><td class="text-end text-gold" id="total_display_2">—</td></tr>
                </tbody>
            </table>
            <hr style="border-color:var(--beige);">
            <div class="mb-2 d-flex align-items-center gap-2"><i class="fas fa-shield-alt text-olive"></i><small>حجز آمن ومشفر</small></div>
            <div class="mb-2 d-flex align-items-center gap-2"><i class="fas fa-undo text-olive"></i><small>إلغاء مجاني خلال 24 ساعة</small></div>
        </div>
    </div>
</div>
<?php endif; ?>
</div>
</section>
<?php include '../includes/footer.php'; ?>
