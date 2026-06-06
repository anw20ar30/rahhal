<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title = 'لوحة التحكم';

$total_exp  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM experiences"))[0];
$total_users= mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM users"))[0];
$total_book = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM bookings"))[0];
$confirmed  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM bookings WHERE status='confirmed'"))[0];
$cancelled  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM bookings WHERE status='cancelled'"))[0];
$revenue    = mysqli_fetch_row(mysqli_query($conn,"SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE status='confirmed'"))[0];

$latest_bookings = mysqli_fetch_all(mysqli_query($conn,"SELECT b.*,u.first_name,u.last_name,e.title,e.city FROM bookings b JOIN users u ON b.user_id=u.user_id JOIN experiences e ON b.experience_id=e.experience_id ORDER BY b.booking_date DESC LIMIT 8"),MYSQLI_ASSOC);
$top_exp = mysqli_fetch_all(mysqli_query($conn,"SELECT e.*,COUNT(b.booking_id) as bc FROM experiences e LEFT JOIN bookings b ON e.experience_id=b.experience_id GROUP BY e.experience_id ORDER BY bc DESC LIMIT 5"),MYSQLI_ASSOC);

include 'includes/admin_header.php';
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-ico ico-gold"><i class="fas fa-hotel"></i></div><div><div class="stat-num"><?php echo $total_exp; ?></div><div class="stat-lbl">إجمالي التجارب</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card" style="border-color:var(--olive);"><div class="stat-ico ico-olive"><i class="fas fa-users"></i></div><div><div class="stat-num"><?php echo $total_users; ?></div><div class="stat-lbl">المستخدمون</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card" style="border-color:var(--dark-brown);"><div class="stat-ico ico-brown"><i class="fas fa-calendar-check"></i></div><div><div class="stat-num"><?php echo $total_book; ?></div><div class="stat-lbl">إجمالي الحجوزات</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="stat-card"><div class="stat-ico ico-gold"><i class="fas fa-coins"></i></div><div><div class="stat-num"><?php echo number_format($revenue,0); ?></div><div class="stat-lbl">الإيرادات (ر.س)</div></div></div></div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-card-head">
                <h5 class="fw-800 text-brown mb-0"><i class="fas fa-clock ms-2 text-gold"></i>آخر الحجوزات</h5>
                <a href="bookings.php" class="btn btn-sm" style="background:rgba(176,141,87,.1);color:var(--gold);border:1px solid rgba(176,141,87,.3);font-family:'ThmanyahSans',sans-serif;font-size:12px;border-radius:8px;">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table table-rahhal">
                    <thead><tr><th>#</th><th>العميل</th><th>التجربة</th><th>المدينة</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                    <tbody>
                        <?php foreach($latest_bookings as $b): ?>
                        <tr>
                            <td><strong>#<?php echo str_pad($b['booking_id'],4,'0',STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo htmlspecialchars($b['first_name'].' '.$b['last_name']); ?></td>
                            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($b['title']); ?></td>
                            <td><?php echo htmlspecialchars($b['city']); ?></td>
                            <td class="fw-700 text-gold"><?php echo number_format($b['total_price'],0); ?> ر.س</td>
                            <td><span class="badge-status badge-<?php echo $b['status']; ?>"><?php echo ['confirmed'=>'مؤكد','cancelled'=>'ملغى','pending'=>'معلق'][$b['status']]??$b['status']; ?></span></td>
                            <td style="font-size:12px;color:#888;"><?php echo date('d/m/Y',strtotime($b['booking_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($latest_bookings)): ?><tr><td colspan="7" class="text-center text-muted py-4">لا توجد حجوزات بعد</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-table h-100">
            <div class="table-card-head"><h5 class="fw-800 text-brown mb-0"><i class="fas fa-trophy ms-2 text-gold"></i>أكثر تجارب محجوزة</h5></div>
            <div class="p-3">
                <?php foreach($top_exp as $i=>$te): ?>
                <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:<?php echo $i<count($top_exp)-1?'1px dashed var(--beige)':'none'; ?>">
                    <div style="width:32px;height:32px;background:var(--gold);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:13px;flex-shrink:0;"><?php echo $i+1; ?></div>
                    <div class="flex-fill" style="min-width:0;"><div class="fw-700" style="font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($te['title']); ?></div><div style="font-size:11px;color:#888;"><?php echo htmlspecialchars($te['city']); ?></div></div>
                    <span class="badge-status badge-confirmed" style="white-space:nowrap;"><?php echo $te['bc']; ?> حجز</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12"><h5 class="fw-800 text-brown mb-3"><i class="fas fa-bolt ms-2 text-gold"></i>إجراءات سريعة</h5></div>
    <div class="col-md-3"><a href="add-experience.php" class="btn w-100 p-3 d-flex align-items-center gap-2" style="background:var(--gold);color:#fff;border:none;border-radius:var(--radius);font-family:'ThmanyahSans',sans-serif;font-weight:700;text-decoration:none;"><i class="fas fa-plus-circle fs-4"></i><span>إضافة تجربة</span></a></div>
    <div class="col-md-3"><a href="experiences.php" class="btn w-100 p-3 d-flex align-items-center gap-2" style="background:var(--dark-brown);color:#fff;border:none;border-radius:var(--radius);font-family:'ThmanyahSans',sans-serif;font-weight:700;text-decoration:none;"><i class="fas fa-hotel fs-4"></i><span>إدارة التجارب</span></a></div>
    <div class="col-md-3"><a href="bookings.php" class="btn w-100 p-3 d-flex align-items-center gap-2" style="background:var(--olive);color:#fff;border:none;border-radius:var(--radius);font-family:'ThmanyahSans',sans-serif;font-weight:700;text-decoration:none;"><i class="fas fa-calendar-alt fs-4"></i><span>عرض الحجوزات</span></a></div>
    <div class="col-md-3"><a href="users.php" class="btn w-100 p-3 d-flex align-items-center gap-2" style="background:var(--beige);color:var(--dark-brown);border:none;border-radius:var(--radius);font-family:'ThmanyahSans',sans-serif;font-weight:700;text-decoration:none;"><i class="fas fa-users fs-4"></i><span>المستخدمون</span></a></div>
</div>

<?php include 'includes/admin_footer.php'; ?>
