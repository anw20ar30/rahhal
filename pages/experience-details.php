<?php
session_start();
require_once '../includes/connection.php';
$base_url = '../';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: experiences.php'); exit; }

$stmt = mysqli_prepare($conn,"SELECT e.*,c.category_name,c.category_icon FROM experiences e JOIN categories c ON e.category_id=c.category_id WHERE e.experience_id=?");
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt);
$exp = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$exp) { header('Location: experiences.php'); exit; }
$page_title = $exp['title'];

$rel_stmt = mysqli_prepare($conn,"SELECT e.*,c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id WHERE e.category_id=? AND e.experience_id!=? ORDER BY e.rating DESC LIMIT 3");
mysqli_stmt_bind_param($rel_stmt,'ii',$exp['category_id'],$id); mysqli_stmt_execute($rel_stmt);
$related = mysqli_fetch_all(mysqli_stmt_get_result($rel_stmt),MYSQLI_ASSOC);

$rating=$exp['rating']; $full=floor($rating); $half=($rating-$full)>=0.5;
include '../includes/header.php';
?>
<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb breadcrumb-rahhal mb-3">
            <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="experiences.php">التجارب</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($exp['title']); ?></li>
        </ol></nav>
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <span class="detail-rating-pill" style="position:static;"><i class="<?php echo htmlspecialchars($exp['category_icon']); ?> ms-1"></i><?php echo htmlspecialchars($exp['category_name']); ?></span>
            <span style="color:rgba(255,255,255,.7);font-size:14px;"><i class="fas fa-map-marker-alt ms-1" style="color:var(--gold-lt);"></i><?php echo htmlspecialchars($exp['city']); ?></span>
        </div>
        <h1 class="page-hero-title mt-2"><?php echo htmlspecialchars($exp['title']); ?></h1>
        <p class="page-hero-sub"><?php echo htmlspecialchars($exp['hotel_name']); ?></p>
    </div>
</div>

<section style="background:var(--cream);padding:50px 0;">
<div class="container">
<div class="detail-layout">
    <main>
        <div class="experience-detail-img mb-4">
            <img src="../assets/images/<?php echo htmlspecialchars($exp['image']); ?>" onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=900&q=75'" alt="<?php echo htmlspecialchars($exp['title']); ?>">
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <span class="detail-rating-pill"><i class="fas fa-star ms-1"></i><?php echo number_format($rating,1); ?></span>
                <div class="stars" style="font-size:1.1rem;">
                    <?php for($i=1;$i<=5;$i++): ?>
                        <?php if($i<=$full): ?><i class="fas fa-star"></i>
                        <?php elseif($i==$full+1&&$half): ?><i class="fas fa-star-half-alt"></i>
                        <?php else: ?><i class="far fa-star" style="color:#ddd;"></i><?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
            <div><span class="bw-price"><?php echo number_format($exp['price_per_night'],0); ?></span><small class="text-muted"> ر.س / ليلة</small></div>
        </div>

        <div class="info-card mb-4">
            <h4 class="fw-800 text-brown mb-3"><i class="fas fa-info-circle ms-2 text-gold"></i>عن هذه التجربة</h4>
            <p style="line-height:2;color:#444;"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
        </div>

        <div class="info-card mb-4">
            <h4 class="fw-800 text-brown mb-3"><i class="fas fa-clipboard-list ms-2 text-gold"></i>تفاصيل الإقامة</h4>
            <div class="detail-info-row"><i class="fas fa-hotel"></i><div><strong>الفندق:</strong> <?php echo htmlspecialchars($exp['hotel_name']); ?></div></div>
            <div class="detail-info-row"><i class="fas fa-map-marker-alt"></i><div><strong>الموقع:</strong> <?php echo htmlspecialchars($exp['city']); ?><?php if($exp['address']): ?>, <?php echo htmlspecialchars($exp['address']); ?><?php endif; ?></div></div>
            <div class="detail-info-row"><i class="fas fa-door-open"></i><div><strong>الغرف المتاحة:</strong> <?php echo (int)$exp['available_rooms']; ?> غرفة</div></div>
            <div class="detail-info-row"><i class="fas fa-money-bill-wave"></i><div><strong>السعر لليلة:</strong> <span class="fw-800 text-gold"><?php echo number_format($exp['price_per_night'],2); ?> ريال سعودي</span></div></div>
        </div>

        <div class="info-card mb-4">
            <h4 class="fw-800 text-brown mb-3"><i class="fas fa-concierge-bell ms-2 text-gold"></i>ما يشمله الحجز</h4>
            <div class="row g-3">
                <?php foreach([['fas fa-wifi','WiFi مجاني'],['fas fa-parking','موقف مجاني'],['fas fa-swimming-pool','مسبح'],['fas fa-utensils','إفطار يومي'],['fas fa-spa','مرافق سبا'],['fas fa-dumbbell','صالة رياضية'],['fas fa-concierge-bell','خدمة الغرف 24/7'],['fas fa-shuttle-van','نقل من المطار']] as $am): ?>
                <div class="col-md-6"><div class="d-flex align-items-center gap-2"><i class="<?php echo $am[0]; ?> text-gold"></i><span style="font-size:14px;"><?php echo $am[1]; ?></span></div></div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <aside>
        <div class="booking-widget">
            <div class="d-flex align-items-baseline gap-2 mb-4">
                <span class="bw-price"><?php echo number_format($exp['price_per_night'],0); ?></span>
                <span class="text-muted fs-6">ريال / ليلة</span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-4 pb-3" style="border-bottom:1px solid var(--beige);">
                <span class="detail-rating-pill"><i class="fas fa-star ms-1"></i><?php echo number_format($rating,1); ?></span>
                <small class="text-muted">تقييم ممتاز</small>
                <span class="ms-auto" style="font-size:13px;color:var(--olive);"><i class="fas fa-door-open ms-1"></i><?php echo (int)$exp['available_rooms']; ?> متاحة</span>
            </div>
            <?php if ($exp['available_rooms']>0): ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="book-experience.php?id=<?php echo $exp['experience_id']; ?>" class="btn-primary-rahhal d-block text-center py-3 mb-3" style="border-radius:var(--radius-sm);font-size:1rem;"><i class="fas fa-calendar-check ms-2"></i>احجز الآن</a>
                <?php else: ?>
                    <a href="login.php" class="btn-primary-rahhal d-block text-center py-3 mb-3" style="border-radius:var(--radius-sm);font-size:1rem;"><i class="fas fa-sign-in-alt ms-2"></i>سجّل الدخول للحجز</a>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn w-100 py-3 mb-3" disabled style="background:#f5f5f5;color:#aaa;border:none;border-radius:var(--radius-sm);font-family:'Tajawal',sans-serif;font-weight:700;">محجوز بالكامل</button>
            <?php endif; ?>
            <div class="mb-3">
                <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-check-circle text-olive"></i><small>إلغاء مجاني خلال 24 ساعة</small></div>
                <div class="d-flex align-items-center gap-2 mb-2"><i class="fas fa-check-circle text-olive"></i><small>تأكيد فوري للحجز</small></div>
                <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-olive"></i><small>ضمان أفضل سعر</small></div>
            </div>
            <hr style="border-color:var(--beige);">
            <div class="text-center">
                <p class="mb-2" style="font-size:13px;font-weight:700;color:#777;">شارك هذه التجربة</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-sm" style="background:#25D366;color:#fff;border-radius:50%;width:34px;height:34px;display:flex;align-items:center;justify-content:center;"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm" style="background:#1DA1F2;color:#fff;border-radius:50%;width:34px;height:34px;display:flex;align-items:center;justify-content:center;"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </aside>
</div>

<?php if (!empty($related)): ?>
<div class="mt-5 pt-4" style="border-top:2px solid var(--beige);">
    <h3 class="fw-800 text-brown mb-4"><i class="fas fa-compass ms-2 text-gold"></i>تجارب مشابهة قد تعجبك</h3>
    <div class="cards-grid">
        <?php foreach($related as $exp): ?>
        <?php include '../includes/experience_card.php'; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
</div>
</section>
<?php include '../includes/footer.php'; ?>
