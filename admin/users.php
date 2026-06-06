<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title='إدارة المستخدمين';
$users=mysqli_fetch_all(mysqli_query($conn,"SELECT u.*,COUNT(b.booking_id) as bc FROM users u LEFT JOIN bookings b ON u.user_id=b.user_id GROUP BY u.user_id ORDER BY u.created_at DESC"),MYSQLI_ASSOC);
include 'includes/admin_header.php';
?>
<div class="table-card">
    <div class="table-card-head"><h5 class="fw-800 text-brown mb-0"><i class="fas fa-users ms-2 text-gold"></i>المستخدمون (<?php echo count($users); ?>)</h5></div>
    <div class="table-responsive">
        <table class="table table-rahhal">
            <thead><tr><th>#</th><th>الاسم</th><th>البريد الإلكتروني</th><th>الجوال</th><th>العنوان</th><th>الحجوزات</th><th>تاريخ التسجيل</th></tr></thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo $u['user_id']; ?></td>
                    <td><div class="d-flex align-items-center gap-2"><div style="width:36px;height:36px;background:var(--gold);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;"><?php echo mb_substr($u['first_name'],0,1); ?></div><div class="fw-700"><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?></div></div></td>
                    <td style="font-size:13px;"><?php echo htmlspecialchars($u['email']); ?></td>
                    <td style="font-size:13px;"><?php echo htmlspecialchars($u['mobile']??'—'); ?></td>
                    <td style="font-size:13px;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($u['address']??'—'); ?></td>
                    <td><span class="badge-status badge-confirmed"><?php echo $u['bc']; ?></span></td>
                    <td style="font-size:12px;color:#888;"><?php echo date('d/m/Y',strtotime($u['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($users)): ?><tr><td colspan="7" class="text-center text-muted py-4">لا يوجد مستخدمون بعد</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
