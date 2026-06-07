<?php
require_once 'includes/connection.php';

$keep = ['Desert Rock Resort', 'ICEHOTEL', 'Conrad Maldives Rangali Island', 'Treehotel', 'Fingal Hotel'];
$placeholders = implode(',', array_fill(0, count($keep), '?'));
$types = str_repeat('s', count($keep));

$stmt = mysqli_prepare($conn, "DELETE FROM experiences WHERE hotel_name NOT IN ($placeholders)");
mysqli_stmt_bind_param($stmt, $types, ...$keep);
mysqli_stmt_execute($stmt);
$deleted = mysqli_stmt_affected_rows($stmt);

$descriptions = [
    'Desert Rock Resort' => "فيلات محفورة داخل الجبال الصخرية.\nواحدة من أكثر المشاريع المعمارية جرأة في الشرق الأوسط.\nتجربة تشبه منتجعات الخيال العلمي وسط الطبيعة الصحراوية.",
    'ICEHOTEL' => "يُعاد بناؤه من الثلج والجليد كل عام.\nكل جناح يُصمم كعمل فني مختلف.\nمن أكثر تجارب الإقامة غرابة على مستوى العالم.",
    'Conrad Maldives Rangali Island' => "جناح \"Muraka\" يضع غرفة النوم تحت الماء.\nيمكنك مشاهدة الأسماك والشعاب المرجانية من سريرك.\nمن أكثر تجارب الفنادق ندرة وفخامة.",
    'Treehotel' => "غرف على شكل مكعب مرآة أو عش طائر.\nتجربة معمارية وسط الغابات الشمالية.\nمناسب لمحبي الطبيعة والتصميم.",
    'Fingal Hotel' => "سفينة تاريخية تحولت إلى فندق فاخر.\nتجربة بحرية مختلفة عن الفنادق التقليدية.\nصُنّف ضمن أبرز الفنادق الفريدة عالمياً.",
];

$upd = mysqli_prepare($conn, "UPDATE experiences SET description = ? WHERE hotel_name = ?");
$updated = 0;
foreach ($descriptions as $hotel => $desc) {
    mysqli_stmt_bind_param($upd, 'ss', $desc, $hotel);
    mysqli_stmt_execute($upd);
    $updated += mysqli_stmt_affected_rows($upd);
}

echo "<h2>تم حذف $deleted تجربة قديمة، وتحديث وصف $updated تجربة. التجارب المتبقية هي الخمس المختارة فقط.</h2>";
echo "<p><a href='index.php'>افتح الموقع</a></p>";
