<?php
//outputRaw();
$page = isset($_POST['page']) ? $_POST['page'] : 1;  // get the requested page
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx']) ? $_POST['sidx'] : 'id_bprp'; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction

if (!$sidx)
    $sidx = 1;
$db = getDB();

$query = "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM M_VSB_VOYAGE@dbint_link";
//echo $query;

$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];

if ($count > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)	

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i = 0;


$query = "select * from M_VSB_VOYAGE@dbint_link";
$res = $db->query($query);

while ($row = $res->fetchRow()) {    

    $responce->rows[$i]['VESSEL'] = $row[VESSEL];
    $responce->rows[$i]['cell'] = array("<a href=monitoring.booking_control/edit_req?cont_limit=$row[CONTAINER_LIMIT]&voyin=$row[VOYAGE_IN]&voyout=$row[VOYAGE_OUT]&vessel=$row[VESSEL]><font color=blue><i>Edit</i></font></a>",$row[VESSEL], $row[VOYAGE_IN], $row[VOYAGE_OUT], $row[CONTAINER_LIMIT]);
    $i++;
}
echo json_encode($responce);
die();
?>