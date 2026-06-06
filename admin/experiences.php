<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$admin_title = 'إدارة التجارب';
$msg='';
if (isset($_GET['deleted'])) $msg='تم حذف التجربة بنجاح.';
if (isset($_GET['added']))   $msg='تم إضافة التجربة بنجاح.';
if (isset($_GET['updated'])) $msg='تم تحديث التجربة بنجاح.';
$exps = mysqli_fetch_all(mysqli_query($conn,"SELECT e.*,c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id ORDER BY e.created_at DESC"),MYSQLI_ASSOC);
include 'includes/admin_header.php';
?>
<?php if ($msg): ?><div class="alert alert-success alert-rahhal alert-auto-close mb-4"><i class="fas fa-check-circle ms-2"></i><?php echo $msg; ?></div><?php endif; ?>
<div class="table-card">
    <div class="table-card-head">
        <h5 class="fw-800 text-brown mb-0"><i class="fas fa-hotel ms-2 text-gold"></i>التجارب (<?php echo count($exps); ?>)</h5>
        <a href="add-experience.php" class="btn btn-sm" style="background:var(--gold);color:#fff;border:none;border-radius:8px;font-family:'ThmanyahSans',sans-serif;font-weight:700;padding:8px 16px;"><i class="fas fa-plus ms-1"></i>إضافة تجربة</a>
    </div>
    <div class="table-responsive">
        <table class="table table-rahhal">
            <thead><tr><th>#</th><th>الصورة</th><th>اسم التجربة</th><th>الفندق</th><th>المدينة</th><th>الفئة</th><th>السعر/ليلة</th><th>التقييم</th><th>الغرف</th><th>مميز</th><th>الإجراءات</th></tr></thead>
            <tbody>
                <?php foreach($exps as $e): ?>
                <tr>
                    <td><?php echo $e['experience_id']; ?></td>
                    <td><img src="../assets/images/<?php echo htmlspecialchars($e['image']); ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=60&q=60'" style="width:56px;height:42px;object-fit:cover;border-radius:6px;"></td>
                    <td><div class="fw-700" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($e['title']); ?></div></td>
                    <td style="font-size:13px;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars($e['hotel_name']); ?></td>
                    <td><?php echo htmlspecialchars($e['city']); ?></td>
                    <td><span class="badge-status badge-confirmed" style="font-size:11px;"><?php echo htmlspecialchars($e['category_name']); ?></span></td>
                    <td class="fw-700 text-gold"><?php echo number_format($e['price_per_night'],0); ?></td>
                    <td><span class="text-warning fw-700"><i class="fas fa-star"></i> <?php echo $e['rating']; ?></span></td>
                    <td><?php echo $e['available_rooms']; ?></td>
                    <td><?php echo $e['is_featured']?'<span class="badge-status badge-confirmed">✓</span>':'<span class="badge-status badge-cancelled">لا</span>'; ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="../pages/experience-details.php?id=<?php echo $e['experience_id']; ?>" target="_blank" class="btn btn-sm" style="background:rgba(120,134,107,.12);color:var(--olive);border:none;border-radius:6px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;" title="عرض"><i class="fas fa-eye" style="font-size:11px;"></i></a>
                            <a href="edit-experience.php?id=<?php echo $e['experience_id']; ?>" class="btn btn-sm" style="background:rgba(176,141,87,.12);color:var(--gold);border:none;border-radius:6px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;" title="تعديل"><i class="fas fa-edit" style="font-size:11px;"></i></a>
                            <a href="delete-experience.php?id=<?php echo $e['experience_id']; ?>" class="btn btn-sm btn-delete-exp" style="background:rgba(231,76,60,.12);color:#e74c3c;border:none;border-radius:6px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;" title="حذف"><i class="fas fa-trash" style="font-size:11px;"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($exps)): ?><tr><td colspan="11" class="text-center text-muted py-4">لا توجد تجارب مضافة بعد</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/admin_footer.php'; ?>
