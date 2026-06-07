<?php
session_start();
require_once '../includes/connection.php';

$base_url = '../';
$page_title = 'من نحن';

$stats = [
    ['value' => '10+', 'label' => 'وجهات سياحية'],
    ['value' => '500+', 'label' => 'تجربة مميزة'],
    ['value' => '10k+', 'label' => 'مسافر سعيد'],
    ['value' => '4.9', 'label' => 'متوسط التقييم'],
];

$values = [
    ['icon' => 'fas fa-gem', 'title' => 'الجودة والتميز', 'text' => 'نختار كل تجربة بعناية لضمان إقامة مريحة بمعايير عالية.'],
    ['icon' => 'fas fa-handshake', 'title' => 'الثقة والشفافية', 'text' => 'نوضح تفاصيل التجربة والأسعار قبل الحجز حتى يعرف المسافر ما ينتظره.'],
    ['icon' => 'fas fa-leaf', 'title' => 'الاستدامة', 'text' => 'ندعم الوجهات والمشاريع المحلية التي تحافظ على هوية المكان.'],
    ['icon' => 'fas fa-headset', 'title' => 'الدعم المستمر', 'text' => 'فريقنا حاضر لمساعدة الضيوف قبل الرحلة وأثناءها وبعدها.'],
];

include '../includes/header.php';
?>

<div class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-rahhal mb-3">
        <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
        <li class="breadcrumb-item active">من نحن</li>
      </ol>
    </nav>
    <h1 class="page-hero-title"><i class="fas fa-info-circle ms-3" style="color:var(--gold-lt);"></i>من نحن</h1>
    <p class="page-hero-sub">رحّال منصة عربية لحجز تجارب إقامة مختارة في أجمل وجهات المملكة.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="row align-items-center g-5 mb-5">
      <div class="col-lg-6">
        <div class="sec-eyebrow"><i class="fas fa-compass"></i> قصتنا</div>
        <h2 class="sec-title">إقامة لا تشبه الحجز التقليدي</h2>
        <p style="color:#555;line-height:2;margin-bottom:16px;">
          نؤمن أن السفر يبدأ من اختيار المكان الصحيح. لذلك تجمع رحّال بين الفنادق، المنتجعات، والإقامات المميزة في تجربة بحث وحجز واضحة وسهلة.
        </p>
        <p style="color:#555;line-height:2;margin-bottom:24px;">
          نحافظ على تجربة عربية RTL متقنة، ونقدم بطاقات واضحة، صورا عملية، أسعارا مباشرة، ومسارا بسيطا للحجز.
        </p>
        <div class="about-stats">
          <?php foreach ($stats as $stat): ?>
          <div>
            <div class="about-stat-value"><?= htmlspecialchars($stat['value']) ?></div>
            <div class="about-stat-label"><?= htmlspecialchars($stat['label']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="about-image">
          <img src="../assets/images/about-hero.jpg"
               onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=900&q=80'"
               alt="رحّال">
        </div>
      </div>
    </div>

    <div class="text-center mb-5">
      <div class="sec-eyebrow"><i class="fas fa-heart"></i> قيمنا</div>
      <h2 class="sec-title">ما يميز رحّال</h2>
    </div>
    <div class="features-grid">
      <?php foreach ($values as $value): ?>
      <div class="feature-box">
        <div class="feat-ico"><i class="<?= htmlspecialchars($value['icon']) ?>"></i></div>
        <h4 class="feat-title"><?= htmlspecialchars($value['title']) ?></h4>
        <p class="feat-desc"><?= htmlspecialchars($value['text']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section testimonials">
  <div class="container text-center">
    <h2 style="color:#fff;font-weight:900;font-size:2rem;margin-bottom:16px;">جاهز لرحلتك القادمة؟</h2>
    <p style="color:rgba(255,255,255,.8);margin-bottom:28px;font-size:15px;">استكشف التجارب المتاحة واحجز إقامتك بخطوات بسيطة.</p>
    <a href="experiences.php" class="btn-solid" style="border-radius:var(--r-sm);padding:14px 36px;font-size:16px;">
      <i class="fas fa-compass ms-2"></i>استكشف التجارب الآن
    </a>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
