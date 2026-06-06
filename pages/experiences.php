<?php
session_start();
require_once '../includes/connection.php';
$base_url = '../'; $page_title = 'استكشف التجارب';

$search   = trim($_GET['search']   ?? '');
$city     = trim($_GET['city']     ?? '');
$cat_id   = (int)($_GET['category'] ?? 0);
$sort     = $_GET['sort']   ?? 'newest';
$featured = (int)($_GET['featured'] ?? 0);
$min_price = max(0, (int)($_GET['min_price'] ?? 0));
$max_price = max(0, (int)($_GET['max_price'] ?? 0));
$page     = max(1,(int)($_GET['page'] ?? 1));
$per_page = 9; $offset = ($page-1)*$per_page;

$where = ['1=1']; $params = []; $types = '';

if ($search!=='') {
    $where[] = "(e.title LIKE ? OR e.hotel_name LIKE ? OR e.city LIKE ?)";
    $s = "%$search%"; $params = array_merge($params,[$s,$s,$s]); $types .= 'sss';
}
if ($city!=='') { $where[] = "e.city=?"; $params[] = $city; $types .= 's'; }
if ($cat_id>0)  { $where[] = "e.category_id=?"; $params[] = $cat_id; $types .= 'i'; }
if ($featured)  { $where[] = "e.is_featured=1"; }
if ($min_price > 0) { $where[] = "e.price_per_night>=?"; $params[] = $min_price; $types .= 'i'; }
if ($max_price > 0) { $where[] = "e.price_per_night<=?"; $params[] = $max_price; $types .= 'i'; }

$order_by = match($sort) {
    'price_asc'  => 'e.price_per_night ASC',
    'price_desc' => 'e.price_per_night DESC',
    'rating'     => 'e.rating DESC',
    default      => 'e.created_at DESC',
};
$wsql = implode(' AND ',$where);

// Count
$cs = mysqli_prepare($conn,"SELECT COUNT(*) FROM experiences e WHERE $wsql");
if ($types && $params) mysqli_stmt_bind_param($cs,$types,...$params);
mysqli_stmt_execute($cs);
$total_count = mysqli_fetch_row(mysqli_stmt_get_result($cs))[0];
$total_pages = ceil($total_count/$per_page);

// Main query
$stmt = mysqli_prepare($conn,"SELECT e.*,c.category_name FROM experiences e JOIN categories c ON e.category_id=c.category_id WHERE $wsql ORDER BY $order_by LIMIT ? OFFSET ?");
$ap = array_merge($params,[$per_page,$offset]); $at = $types.'ii';
mysqli_stmt_bind_param($stmt,$at,...$ap);
mysqli_stmt_execute($stmt);
$experiences = mysqli_fetch_all(mysqli_stmt_get_result($stmt),MYSQLI_ASSOC);

$categories = mysqli_fetch_all(mysqli_query($conn,"SELECT * FROM categories ORDER BY category_id"),MYSQLI_ASSOC);
$cities_r   = mysqli_query($conn,"SELECT DISTINCT city FROM experiences ORDER BY city");
$cities     = mysqli_fetch_all($cities_r,MYSQLI_ASSOC);

function buildPageUrl($p) { $params=$_GET; $params['page']=$p; return '?'.http_build_query($params); }

include '../includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb"><ol class="breadcrumb breadcrumb-rahhal mb-3">
            <li class="breadcrumb-item"><a href="../index.php">الرئيسية</a></li>
            <li class="breadcrumb-item active">التجارب</li>
        </ol></nav>
        <h1 class="page-hero-title"><i class="fas fa-compass ms-3" style="color:var(--gold-light);"></i>
            <?php echo $search ? "نتائج البحث عن: \"$search\"" : ($city ? "تجارب في $city" : 'استكشف جميع التجارب'); ?>
        </h1>
        <p class="page-hero-subtitle"><?php echo $total_count; ?> تجربة متاحة<?php echo $city?" في $city":''; ?></p>
    </div>
</div>

<section style="background:var(--cream);padding:50px 0;">
<div class="container">
<div class="exp-layout">

<!-- الفلاتر -->
<aside>
<div class="filters-panel">
    <?php if ($search||$city||$cat_id||$featured): ?>
    <a href="experiences.php" class="btn btn-sm w-100 mb-3" style="background:rgba(176,141,87,.1);color:var(--dark-brown);border:1px solid var(--beige);font-family:'Tajawal',sans-serif;font-weight:700;border-radius:8px;"><i class="fas fa-times ms-1"></i>مسح الفلاتر</a>
    <?php endif; ?>
    <form method="GET" id="filterForm">
        <?php if ($search): ?><input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
        <div class="mb-4">
            <div class="filter-sec-title"><i class="fas fa-map-marker-alt ms-2"></i>المدينة</div>
            <div class="filter-check">
                <div class="form-check mb-2"><input class="form-check-input" type="radio" name="city" value="" id="ca" <?php echo !$city?'checked':''; ?> onchange="this.form.submit()"><label class="form-check-label" for="ca">كل المدن</label></div>
                <?php foreach($cities as $c): ?>
                <div class="form-check mb-2"><input class="form-check-input" type="radio" name="city" value="<?php echo htmlspecialchars($c['city']); ?>" id="ci_<?php echo md5($c['city']); ?>" <?php echo ($city===$c['city'])?'checked':''; ?> onchange="this.form.submit()"><label class="form-check-label" for="ci_<?php echo md5($c['city']); ?>"><?php echo htmlspecialchars($c['city']); ?></label></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-4">
            <div class="filter-sec-title"><i class="fas fa-th-large ms-2"></i>نوع التجربة</div>
            <div class="filter-check">
                <div class="form-check mb-2"><input class="form-check-input" type="radio" name="category" value="0" id="caa" <?php echo !$cat_id?'checked':''; ?> onchange="this.form.submit()"><label class="form-check-label" for="caa">كل الفئات</label></div>
                <?php foreach($categories as $cat): ?>
                <div class="form-check mb-2"><input class="form-check-input" type="radio" name="category" value="<?php echo $cat['category_id']; ?>" id="cat_<?php echo $cat['category_id']; ?>" <?php echo ($cat_id==$cat['category_id'])?'checked':''; ?> onchange="this.form.submit()"><label class="form-check-label" for="cat_<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></label></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-4">
            <div class="filter-sec-title"><i class="fas fa-star ms-2"></i>خيارات إضافية</div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="featured" value="1" id="feat" <?php echo $featured?'checked':''; ?> onchange="this.form.submit()"><label class="form-check-label" for="feat">التجارب المميزة فقط</label></div>
        </div>
        <div class="mb-4">
            <div class="filter-sec-title"><i class="fas fa-money-bill-wave ms-2"></i>السعر لليلة</div>
            <div class="row g-2">
                <div class="col-6">
                    <input class="form-ctrl" type="number" min="0" name="min_price" value="<?php echo $min_price ?: ''; ?>" placeholder="أدنى">
                </div>
                <div class="col-6">
                    <input class="form-ctrl" type="number" min="0" name="max_price" value="<?php echo $max_price ?: ''; ?>" placeholder="أقصى">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-search w-100 justify-content-center" style="border-radius:var(--r-sm);">تطبيق</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
    </form>
</div>
</aside>

<!-- قائمة التجارب -->
<div>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-3 bg-white rounded-rh" style="box-shadow:var(--sh-sm);">
        <div><span class="fw-700" style="color:var(--dark-brown);"><?php echo $total_count; ?> نتيجة</span>
        <?php if ($search): ?><small class="text-muted me-2">لـ "<?php echo htmlspecialchars($search); ?>"</small><?php endif; ?></div>
        <form method="GET" class="d-flex align-items-center gap-2">
            <?php foreach($_GET as $k=>$v): if ($k!=='sort'): ?><input type="hidden" name="<?php echo htmlspecialchars($k); ?>" value="<?php echo htmlspecialchars($v); ?>"><?php endif; endforeach; ?>
            <label style="font-size:13px;font-weight:700;color:var(--dark-brown);white-space:nowrap;"><i class="fas fa-sort ms-1"></i>ترتيب:</label>
            <select name="sort" class="form-select form-select-sm" style="border-color:var(--beige);font-family:'Tajawal',sans-serif;font-size:13px;min-width:150px;" onchange="this.form.submit()">
                <option value="newest" <?php echo $sort=='newest'?'selected':''; ?>>الأحدث</option>
                <option value="price_asc" <?php echo $sort=='price_asc'?'selected':''; ?>>السعر: من الأقل</option>
                <option value="price_desc" <?php echo $sort=='price_desc'?'selected':''; ?>>السعر: من الأعلى</option>
                <option value="rating" <?php echo $sort=='rating'?'selected':''; ?>>الأعلى تقييماً</option>
            </select>
        </form>
    </div>

    <?php if (empty($experiences)): ?>
    <div class="empty-state"><i class="fas fa-search-minus"></i><h3>لا توجد نتائج</h3><p>جرّب البحث بكلمات أخرى أو غيّر الفلاتر.</p><a href="experiences.php" class="btn-solid mt-3">عرض كل التجارب</a></div>
    <?php else: ?>
    <div class="cards-grid">
        <?php foreach($experiences as $exp): ?>
        <?php include '../includes/experience_card.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php if ($total_pages>1): ?>
    <nav class="mt-5" aria-label="الصفحات">
        <ul class="pagination justify-content-center" style="gap:6px;">
            <?php if ($page>1): ?><li class="page-item"><a class="page-link" href="<?php echo buildPageUrl($page-1); ?>" style="border-radius:8px;font-family:'ThmanyahSans',sans-serif;border-color:var(--beige);"><i class="fas fa-chevron-right"></i></a></li><?php endif; ?>
            <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
            <li class="page-item <?php echo $p==$page?'active':''; ?>"><a class="page-link" href="<?php echo buildPageUrl($p); ?>" style="border-radius:8px;font-family:'ThmanyahSans',sans-serif;<?php echo $p==$page?'background:var(--gold);border-color:var(--gold);':'border-color:var(--beige);color:var(--dark-brown);'; ?>"><?php echo $p; ?></a></li>
            <?php endfor; ?>
            <?php if ($page<$total_pages): ?><li class="page-item"><a class="page-link" href="<?php echo buildPageUrl($page+1); ?>" style="border-radius:8px;font-family:'ThmanyahSans',sans-serif;border-color:var(--beige);"><i class="fas fa-chevron-left"></i></a></li><?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>
</div>
</div>
</section>

<?php include '../includes/footer.php'; ?>
