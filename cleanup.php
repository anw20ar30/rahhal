<?php
require_once 'includes/connection.php';

$keep = ['Desert Rock Resort', 'ICEHOTEL', 'Conrad Maldives Rangali Island', 'Treehotel', 'Fingal Hotel'];
$placeholders = implode(',', array_fill(0, count($keep), '?'));
$types = str_repeat('s', count($keep));

$stmt = mysqli_prepare($conn, "DELETE FROM experiences WHERE hotel_name NOT IN ($placeholders)");
mysqli_stmt_bind_param($stmt, $types, ...$keep);
mysqli_stmt_execute($stmt);

echo "<h2>تم حذف " . mysqli_stmt_affected_rows($stmt) . " تجربة قديمة. التجارب المتبقية هي الخمس المختارة فقط.</h2>";
echo "<p><a href='index.php'>افتح الموقع</a></p>";
