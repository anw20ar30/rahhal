<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php?redirect=my-bookings.php'); exit; }
require_once '../includes/connection.php';
$base_url = '../'; $page_title = 'حجوزاتي'; $uid = (int)$_SESSION['user_id'];

$cancel_msg='';
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $cid = (int)$_GET['cancel'];
    $chk = mysqli_prepare($conn,"SELECT booking_id FROM bookings WHERE booking_id=? AND user_id=? AND status='confirmed'");
    mysqli_stmt_bind_param($chk,'ii',$cid,$uid); mysqli_stmt_execute($chk); mysqli_stmt_store_result($chk);
    if (mysqli_stmt_num_rows($chk)>0) {
        $upd = mysqli_prepare($conn,"UPDATE bookings SET status='cancelled' WHERE booking_id=?");
        mysqli_stmt_bind_param($upd,'i',$cid); mysqli_stmt_execute($upd);
        $cancel_msg = 'تم إلغاء الحجز بنجاح.';
    } else { $cancel_msg = 'لا يمكن إلغاء هذا الحجز.'; }
}

$stmt = mysqli_prepare($conn,"SELECT b.*,e.title,e.hotel_name,e.city,e.image,c.category_name FROM bookings b JOIN experiences e ON b.experience_id=e.experience_id JOIN categories c ON e.category_id=c.category_id WHERE b.user_id=? ORDER BY b.booking_date DESC");
mysqli_stmt_bind_param($stmt,'i',$uid); mysqli_stmt_execute($stmt);
$bookings = mysqli_fetch_all(mysqli_stmt_get_result($stmt),MYSQLI_ASSOC);

$confirmed=0; $cancelled=0; $total_spent=0;
foreach($bookings as $b) {
    if ($b['status']==='confirmed') { $confirmed++; $total_spent+=$b['total_price']; }
    if ($b['status']==='cancelled') $cancelled++;
}
include '../includes/header.php';
?>
<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb breadcrumb-rahhal mb-3"><li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li><li class="breadcrumb-item active">حجوزاتي</li></ol></nav>
        <h1 class="page-hero-title"><i class="fas fa-suitcase ms-3" style="color:var(--gold-lt);"></i>حجوزاتي</h1>
        <p class="page-hero-sub">مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?>، إليك قائمة جميع حجوزاتك</p>
    </div>
</div>

<section class="section section-alt">
<div class="container">

<?php if ($cancel_msg): ?>
<div class="alert <?php echo str_contains($cancel_msg,'نجاح')?'alert-success':'alert-danger'; ?> alert-rahhal alert-auto-close mb-4">
    <i class="fas <?php echo str_contains($cancel_msg,'نجاح')?'fa-check-circle':'fa-exclamation-circle'; ?> ms-2"></i><?php echo htmlspecialchars($cancel_msg); ?>
</div>
<?php endif; ?>

<div class="row g-3 mb-5">
    <div class="col-md-4"><div class="stat-card"><div class="stat-ico ico-gold"><i class="fas fa-calendar-check"></i></div><div><div class="stat-num"><?php echo $confirmed; ?></div><div class="stat-lbl">حجز مؤكد</div></div></div></div>
    <div class="col-md-4"><div class="stat-card" style="border-color:#e74c3c;"><div class="stat-ico ico-red"><i class="fas fa-times-circle"></i></div><div><div class="stat-num"><?php echo $cancelled; ?></div><div class="stat-lbl">حجز ملغى</div></div></div></div>
    <div class="col-md-4"><div class="stat-card" style="border-color:var(--olive);"><div class="stat-ico ico-olive"><i class="fas fa-money-bill-wave"></i></div><div><div class="stat-num"><?php echo number_format($total_spent,0); ?></div><div class="stat-lbl">ريال إجمالي الإنفاق</div></div></div></div>
</div>

<?php if (empty($bookings)): ?>
<div class="empty-state"><i class="fas fa-calendar-times"></i><h3>لا توجد حجوزات بعد</h3><p>لم تقم بأي حجز حتى الآن. استكشف تجاربنا الرائعة!</p><a href="experiences.php" class="btn-solid mt-3 d-inline-block"><i class="fas fa-compass ms-2"></i>استكشف التجارب</a></div>
<?php else: ?>
<?php
$status_labels=['confirmed'=>'✓ مؤكد','cancelled'=>'✗ ملغى','pending'=>'⏳ معلق'];
foreach($bookings as $b):
    $nights = max(1,(int)ceil((strtotime($b['check_out'])-strtotime($b['check_in']))/86400));
    $can_cancel = $b['status']==='confirmed' && strtotime($b['check_in'])>time();
?>
<div class="booking-card">
    <div class="d-flex gap-4 flex-wrap align-items-start">
        <img src="../assets/images/<?php echo htmlspecialchars($b['image']); ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=300&q=75'" alt="" class="booking-thumb">
        <div class="flex-fill">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-2">
                <div>
                    <h5 class="fw-800 text-brown mb-1"><a href="experience-details.php?id=<?php echo $b['experience_id']; ?>" class="text-decoration-none text-brown"><?php echo htmlspecialchars($b['title']); ?></a></h5>
                    <p class="mb-0 text-muted" style="font-size:13px;"><i class="fas fa-hotel ms-1 text-gold"></i><?php echo htmlspecialchars($b['hotel_name']); ?> &nbsp;·&nbsp; <i class="fas fa-map-marker-alt ms-1 text-gold"></i><?php echo htmlspecialchars($b['city']); ?></p>
                </div>
                <span class="badge-status badge-<?php echo $b['status']; ?>"><?php echo $status_labels[$b['status']]??$b['status']; ?></span>
            </div>
            <div class="row g-2 booking-meta">
                <div class="col-sm-3"><div><i class="fas fa-plane-arrival ms-1 text-gold"></i>وصول</div><strong><?php echo date('d/m/Y',strtotime($b['check_in'])); ?></strong></div>
                <div class="col-sm-3"><div><i class="fas fa-plane-departure ms-1 text-gold"></i>مغادرة</div><strong><?php echo date('d/m/Y',strtotime($b['check_out'])); ?></strong></div>
                <div class="col-sm-2"><div><i class="fas fa-moon ms-1 text-gold"></i>الليالي</div><strong><?php echo $nights; ?></strong></div>
                <div class="col-sm-2"><div><i class="fas fa-door-open ms-1 text-gold"></i>الغرف</div><strong><?php echo $b['rooms']; ?></strong></div>
                <div class="col-sm-2"><div><i class="fas fa-money-bill ms-1 text-gold"></i>الإجمالي</div><strong class="text-gold"><?php echo number_format($b['total_price'],0); ?> ر.س</strong></div>
            </div>
            <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
                <small class="text-muted"><i class="fas fa-clock ms-1"></i>تاريخ الحجز: <?php echo date('d/m/Y H:i',strtotime($b['booking_date'])); ?></small>
                <small class="text-muted">رقم الحجز: <strong>#<?php echo str_pad($b['booking_id'],6,'0',STR_PAD_LEFT); ?></strong></small>
                <div class="ms-auto d-flex gap-2">
                    <a href="experience-details.php?id=<?php echo $b['experience_id']; ?>" class="btn btn-sm" style="background:var(--cream);border:1px solid var(--beige);color:var(--dark-brown);font-family:var(--font);font-weight:600;border-radius:8px;font-size:12px;"><i class="fas fa-eye ms-1"></i>عرض</a>
                    <?php if ($can_cancel): ?>
                    <a href="my-bookings.php?cancel=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-cancel-booking" style="background:rgba(231,76,60,.08);border:1px solid rgba(231,76,60,.3);color:#e74c3c;font-family:var(--font);font-weight:600;border-radius:8px;font-size:12px;"><i class="fas fa-times ms-1"></i>إلغاء</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="text-center mt-4"><a href="experiences.php" class="btn-outline"><i class="fas fa-plus ms-2"></i>حجز تجربة جديدة</a></div>
<?php endif; ?>

</div>
</section>
<?php include '../includes/footer.php'; ?>
