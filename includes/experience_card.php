<?php
if (!isset($exp)) return;
$img_path   = (isset($base_url) ? $base_url : '../') . 'assets/images/' . htmlspecialchars($exp['image'] ?? 'default.jpg');
$detail_url = (isset($base_url) ? $base_url : '../') . 'pages/experience-details.php?id=' . (int)$exp['experience_id'];
$rating     = (float)($exp['rating'] ?? 0);
$full_stars = (int)floor($rating);
$half_star  = ($rating - $full_stars) >= 0.5;
?>
<div class="exp-card">
  <div class="exp-card-img">
    <img src="<?= $img_path ?>" loading="lazy"
         onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=500&q=75'"
         alt="<?= htmlspecialchars($exp['title'] ?? '') ?>">
    <?php if (!empty($exp['is_featured'])): ?>
      <span class="exp-badge gold"><i class="fas fa-star ms-1"></i>مميز</span>
    <?php else: ?>
      <span class="exp-badge"><?= htmlspecialchars($exp['city'] ?? '') ?></span>
    <?php endif; ?>
    <button class="exp-wishlist" title="أضف للمفضلة"><i class="far fa-heart"></i></button>
  </div>
  <div class="exp-card-body">
    <div class="exp-cat"><i class="fas fa-tag ms-1"></i><?= htmlspecialchars($exp['category_name'] ?? '') ?></div>
    <h3 class="exp-name"><a href="<?= $detail_url ?>"><?= htmlspecialchars($exp['title'] ?? '') ?></a></h3>
    <p class="exp-hotel"><i class="fas fa-hotel text-gold ms-1"></i><?= htmlspecialchars($exp['hotel_name'] ?? '') ?></p>
    <p class="exp-loc"><i class="fas fa-map-marker-alt ms-1"></i><?= htmlspecialchars($exp['city'] ?? '') ?></p>
    <div class="exp-rating">
      <span class="stars">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <?php if ($i <= $full_stars): ?>
            <i class="fas fa-star"></i>
          <?php elseif ($i == $full_stars + 1 && $half_star): ?>
            <i class="fas fa-star-half-alt"></i>
          <?php else: ?>
            <i class="far fa-star" style="color:#e0e0e0;"></i>
          <?php endif; ?>
        <?php endfor; ?>
      </span>
      <span class="rating-val"><?= number_format($rating, 1) ?></span>
    </div>
    <div class="exp-footer">
      <div>
        <div class="price-lbl">يبدأ من</div>
        <div class="price-val"><?= number_format($exp['price_per_night'] ?? 0, 0) ?> <small class="price-curr">ر.س</small></div>
        <div class="price-night">/ ليلة</div>
      </div>
      <a href="<?= $detail_url ?>" class="btn-book">احجز الآن <i class="fas fa-arrow-left ms-1"></i></a>
    </div>
  </div>
</div>
