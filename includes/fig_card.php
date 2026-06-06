<?php
if (!isset($exp)) return;
$img = (isset($base_url) ? $base_url : '../') . 'assets/images/' . htmlspecialchars($exp['image'] ?? 'default.jpg');
$url = (isset($base_url) ? $base_url : '../') . 'pages/experience-details.php?id=' . (int)$exp['experience_id'];
$rating = (float)($exp['rating'] ?? 0);
?>
<a href="<?= $url ?>" class="fig-card">
  <img src="<?= $img ?>" loading="lazy"
       onerror="this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80'"
       alt="<?= htmlspecialchars($exp['title'] ?? '') ?>">
  <div class="fig-card-overlay"></div>
  <span class="fig-card-price"><?= number_format($exp['price_per_night'] ?? 0, 0) ?> ر.س / ليلة</span>
  <span class="fig-card-rating"><i class="fas fa-star"></i><?= number_format($rating, 1) ?></span>
  <div class="fig-card-body">
    <div class="fig-card-cat"><?= htmlspecialchars($exp['category_name'] ?? '') ?></div>
    <div class="fig-card-title"><?= htmlspecialchars($exp['title'] ?? '') ?></div>
    <div class="fig-card-loc"><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($exp['city'] ?? '') ?></div>
  </div>
</a>
