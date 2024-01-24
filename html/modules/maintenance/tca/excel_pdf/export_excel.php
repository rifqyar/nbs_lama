<?php
$db = getDB();
$title = $_GET['title'];
$content = rawUrlDecode($_GET['content']);
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$title.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?=$content?>
