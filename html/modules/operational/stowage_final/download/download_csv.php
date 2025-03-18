 <?php 

$id_vessel = $_GET["id_vessel"];
$db  = getDB();
$rows = "SELECT NO_CONTAINER, SIZE_, TYPE_CONT FROM YD_PLACEMENT_YARD WHERE ID_VS = '$id_vessel'";
$data = $db->query($rows);
$data_ = $data->getAll();

// create a file pointer connected to the output stream
//$output = fopen('php://output', 'w');

// output the column headings
//fputcsv($output,array('No Container', 'Size', 'Type'));

foreach ($data_ as $row){
$csvFile .= $row['NO_CONTAINER'].",". $row['SIZE_'].",". $row['TYPE_CONT']."\r\n";
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=stowage_final.csv');
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

echo $csvFile;


?>
