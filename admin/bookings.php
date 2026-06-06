<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title='إدارة الحجوزات';
$bookings=mysqli_fetch_all(mysqli_query($conn,"SELECT b.*,u.first_name,u.last_name,u.email,e.title,e.city,e.hotel_name FROM bookings b JOIN users u ON b.user_id=u.user_id JOIN experiences e ON b.experience_id=e.experience_id ORDER BY b.booking_date DESC"),MYSQLI_ASSOC);
include 'includes/admin_header.php';
?>
<div class="table-card">
    <div class="table-card-head">
        <h5 class="fw-800 text-brown mb-0"><i class="fas fa-calendar-check ms-2 text-gold"></i>جميع الحجوزات (<?php echo count($bookings); ?>)</h5>
        <div class="d-flex gap-2">
            <span class="badge-status badge-confirmed"><?php echo count(array_filter($bookings,fn($b)=>$b['status']==='confirmed')); ?> مؤكد</span>
            <span class="badge-status badge-cancelled"><?php echo count(array_filter($bookings,fn($b)=>$b['status']==='cancelled')); ?> ملغى</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-rahhal">
            <thead><tr><th>#</th><th>العميل</th><th>التجربة</th><th>المدينة</th><th>الوصول</th><th>المغادرة</th><th>الغرف</th><th>الإجمالي</th><th>الحالة</th><th>تاريخ الحجز</th></tr></thead>
            <tbody>
                <?php foreach($bookings as $b): ?>
                <tr>
                    <td><strong>#<?php echo str_pad($b['booking_id'],4,'0',STR_PAD_LEFT); ?></strong></td>
                    <td><div class="fw-700" style="font-size:13px;"><?php echo htmlspecialchars($b['first_name'].' '.$b['last_name']); ?></div><div style="font-size:11px;color:#888;"><?php echo htmlspecialchars($b['email']); ?></div></td>
                    <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;"><?php echo htmlspecialchars($b['title']); ?><div style="font-size:11px;color:#888;"><?php echo htmlspecialchars($b['hotel_name']); ?></div></td>
                    <td><?php echo htmlspecialchars($b['city']); ?></td>
                    <td><?php echo date('d/m/Y',strtotime($b['check_in'])); ?></td>
                    <td><?php echo date('d/m/Y',strtotime($b['check_out'])); ?></td>
                    <td class="text-center"><?php echo $b['rooms']; ?></td>
                    <td class="fw-700 text-gold"><?php echo number_format($b['total_price'],0); ?> ر.س</td>
                    <td><span class="badge-status badge-<?php echo $b['status']; ?>"><?php echo ['confirmed'=>'مؤكد','cancelled'=>'ملغى','pending'=>'معلق'][$b['status']]??$b['status']; ?></span></td>
                    <td style="font-size:12px;color:#888;"><?php echo date('d/m/Y H:i',strtotime($b['booking_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($bookings)): ?><tr><td colspan="10" class="text-center text-muted py-4">لا توجد حجوزات بعد</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
