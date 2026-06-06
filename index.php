<?php
session_start();
require_once 'includes/connection.php';
$base_url   = '';
$page_title = 'الرئيسية';

$cats     = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM categories ORDER BY category_id LIMIT 8"), MYSQLI_ASSOC);
$featured = mysqli_fetch_all(mysqli_query($conn, "SELECT e.*, c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id WHERE e.is_featured=1 ORDER BY e.rating DESC LIMIT 5"), MYSQLI_ASSOC);
$popular  = mysqli_fetch_all(mysqli_query($conn, "SELECT e.*, c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id ORDER BY e.created_at DESC LIMIT 8"), MYSQLI_ASSOC);

$te = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM experiences"))[0] ?? 0;
$tc = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(DISTINCT city) FROM experiences"))[0] ?? 0;
$tb = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM bookings"))[0] ?? 0;

include 'includes/header.php';
?>

<!-- ═══ HERO (Figma style) ═══ -->
<section class="fig-hero">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <h1 class="fig-hero-title">انسَ زحمة العمل،<br>وابدأ <em>إجازتك القادمة</em></h1>
        <p class="fig-hero-sub">اكتشف تجارب إقامة استثنائية في أجمل وجهات المملكة. منتجعات فاخرة، فنادق تراثية، ومخيمات صحراوية بانتظارك.</p>
        <a href="pages/experiences.php" class="fig-hero-btn">استكشف الآن <i class="fas fa-arrow-left"></i></a>

        <div class="fig-stats">
          <div class="fig-stat">
            <div class="fig-stat-ico"><i class="fas fa-hotel"></i></div>
            <div><div class="fig-stat-num counter-num" data-target="<?= (int)$te ?>"><?= (int)$te ?></div><div class="fig-stat-lbl">تجربة فندقية</div></div>
          </div>
          <div class="fig-stat">
            <div class="fig-stat-ico"><i class="fas fa-map-marked-alt"></i></div>
            <div><div class="fig-stat-num counter-num" data-target="<?= (int)$tc ?>"><?= (int)$tc ?></div><div class="fig-stat-lbl">وجهة سياحية</div></div>
          </div>
          <div class="fig-stat">
            <div class="fig-stat-ico"><i class="fas fa-smile"></i></div>
            <div><div class="fig-stat-num counter-num" data-target="<?= max((int)$tb,150) ?>"><?= max((int)$tb,150) ?></div><div class="fig-stat-lbl">عميل سعيد</div></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="fig-hero-img">
          <img src="assets/images/hotel1.jpg"
               onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80'"
               alt="تجربة إقامة فاخرة">
        </div>
      </div>
    </div>

    <!-- شريط البحث الأفقي -->
    <form class="fig-search" action="pages/experiences.php" method="GET">
      <div class="fig-search-item">
        <i class="fas fa-search"></i>
        <div class="fld"><label>الوجهة أو التجربة</label><input type="text" name="search" placeholder="ابحث..."></div>
      </div>
      <div class="fig-search-item">
        <i class="fas fa-city"></i>
        <div class="fld"><label>المدينة</label>
          <select name="city">
            <option value="">كل الوجهات</option>
            <option>نيوم</option><option>السويد</option>
            <option>المالديف</option><option>إدنبرة</option>
          </select>
        </div>
      </div>
      <div class="fig-search-item">
        <i class="fas fa-th-large"></i>
        <div class="fld"><label>النوع</label>
          <select name="category">
            <option value="">كل الفئات</option>
            <?php foreach ($cats as $c): ?>
            <option value="<?= (int)$c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <button type="submit" class="fig-search-btn"><i class="fas fa-search"></i> بحث</button>
    </form>
  </div>
</section>

<!-- ═══ الأكثر اختياراً (Most Picked) ═══ -->
<section class="section">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <div class="sec-eyebrow"><i class="fas fa-crown"></i> الأكثر اختياراً</div>
        <h2 class="sec-title mb-0">تجارب مختارة بعناية</h2>
      </div>
      <a href="pages/experiences.php?featured=1" class="btn-outline">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
    <?php if (!empty($featured)): ?>
    <div class="fig-masonry">
      <?php foreach ($featured as $exp): include 'includes/fig_card.php'; endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-hotel"></i><h3>لا توجد تجارب مميزة بعد</h3></div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ الوجهات ═══ -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center mb-5">
      <div class="sec-eyebrow"><i class="fas fa-map-marked-alt"></i> الوجهات</div>
      <h2 class="sec-title">اكتشف أجمل الوجهات</h2>
    </div>
    <div class="fig-cat-grid">
      <?php
      $dests = [
        ['نيوم',     'desert-rock.jpg',     'المملكة العربية السعودية'],
        ['السويد',   'treehotel.jpg',       'الغابات الشمالية'],
        ['المالديف', 'conrad-maldives.jpg', 'المحيط الهندي'],
        ['إدنبرة',   'fingal-hotel.jpg',    'اسكتلندا'],
      ];
      foreach ($dests as $d):
      ?>
      <a href="pages/experiences.php?city=<?= urlencode($d[0]) ?>" class="fig-card" style="height:200px;">
        <img src="assets/images/<?= $d[1] ?>" onerror="this.src='https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=400&q=80'" alt="<?= htmlspecialchars($d[0]) ?>">
        <div class="fig-card-overlay"></div>
        <div class="fig-card-body">
          <div class="fig-card-title" style="font-size:18px;"><i class="fas fa-map-marker-alt ms-1"></i><?= htmlspecialchars($d[0]) ?></div>
          <div class="fig-card-loc"><?= htmlspecialchars($d[2]) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ الأحدث ═══ -->
<section class="section">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <div class="sec-eyebrow"><i class="fas fa-fire"></i> جديد</div>
        <h2 class="sec-title mb-0">أحدث التجارب المضافة</h2>
      </div>
      <a href="pages/experiences.php" class="btn-outline">استكشف الكل <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
    <?php if (!empty($popular)): ?>
    <div class="fig-cat-grid">
      <?php foreach ($popular as $exp): include 'includes/fig_card.php'; endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-search"></i><h3>لا توجد تجارب بعد</h3><p><a href="admin/login.php">أضف من لوحة التحكم</a></p></div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ لماذا رحّال ═══ -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center mb-5">
      <div class="sec-eyebrow"><i class="fas fa-shield-alt"></i> لماذا رحّال؟</div>
      <h2 class="sec-title">تجربة سفر لا مثيل لها</h2>
    </div>
    <div class="features-grid">
      <div class="feature-box"><div class="feat-ico"><i class="fas fa-medal"></i></div><h4 class="feat-title">تجارب حصرية مختارة</h4><p class="feat-desc">نختار لك أفضل التجارب التي تضمن إقامة لا تُنسى</p></div>
      <div class="feature-box"><div class="feat-ico"><i class="fas fa-lock"></i></div><h4 class="feat-title">حجز آمن ومضمون</h4><p class="feat-desc">تأكيد فوري مع ضمان كامل لحقوقك كمسافر</p></div>
      <div class="feature-box"><div class="feat-ico"><i class="fas fa-headset"></i></div><h4 class="feat-title">دعم عملاء 24/7</h4><p class="feat-desc">فريقنا جاهز لمساعدتك في أي وقت طوال رحلتك</p></div>
      <div class="feature-box"><div class="feat-ico"><i class="fas fa-tag"></i></div><h4 class="feat-title">أفضل الأسعار</h4><p class="feat-desc">نضمن أفضل الأسعار مع خصومات حصرية للأعضاء</p></div>
    </div>
  </div>
</section>

<!-- ═══ CTA Band ═══ -->
<section class="section">
  <div class="container">
    <div class="fig-cta">
      <div>
        <div class="fig-cta-title">هل تملك فندقاً أو منتجعاً؟</div>
        <div class="fig-cta-sub">انضم إلى رحّال واعرض تجاربك أمام آلاف المسافرين</div>
      </div>
      <a href="pages/register.php" class="fig-hero-btn">سجّل الآن <i class="fas fa-arrow-left"></i></a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
