<?php
// Connection to USTER
$db = getDB("storage");

$page = $_GET['page']; // Page number
$pageSize = $_GET['limit']; // Number of rows per page
$start = $_GET['start']; // Starting row number for the page
$search = strtoupper($_GET['search']); // Convert search keyword to uppercase

// Query to fetch total count
$totalQuery = "SELECT COUNT(*) AS total FROM MST_PELANGGAN ";
if (!empty($search)) {
    $totalQuery .= "WHERE KD_PBM LIKE '%$search%' OR NM_PBM LIKE '%$search%'";
}
$result = $db->query($totalQuery);
$totalRow = $result->fetchRow();
$totalRows = $totalRow['TOTAL'];

// Query to fetch paginated data
$query = "SELECT * FROM (
              SELECT a.*, ROWNUM rnum FROM (
                  SELECT * FROM MST_PELANGGAN";
if (!empty($search)) {
    $query .= " WHERE KD_PBM LIKE '%$search%' OR NM_PBM LIKE '%$search%'";
}
$query .= " ) a
              WHERE ROWNUM <= :endRow
          )
          WHERE rnum >= :startRow
          ORDER BY NO_ACCOUNT_PBM DESC";

// Bind parameters for pagination
$queryParams = array(
    'startRow' => $start + 1, // Adding 1 to start from 1-based index
    'endRow' => $start + $pageSize // End row for the page
);

// Prepare and execute the query with pagination parameters
$result = $db->query($query, $queryParams);
$rows = $result->getAll();

$data = array(
    'total' => $totalRows,
    'data' => $rows,
);

echo json_encode($data);
?>
