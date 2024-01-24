<?php
$db = getDB();
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=List_TruckID.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table>
            <tr>
                <th colspan="16">
                    LIST TRUCK ID
                </th>
            </tr>
            <tr>
                <th colspan="16"> 
                    <?=$rowe['NM_PEMILIK'] ?>
                </th>
            </tr>
        </table>
        <br/>

            <table border="1">
                <tr>
                    <th>No.</th>
                    <th>TID</th>
					<th>Truck Number</th>
                    <th>Registrant Name</th>
                    <th>Registrant Phone</th>
                    <th>Company Name</th>
                    <th>Company Address</th>
                    <th>Company Phone</th>
                    <th>Email</th>
                    <th>KIU</th>
                    <th>Expired KIU</th>
					<th>No STNK</th>
					<th>Expired STNK</th>
                    <th>Expired Date</th>
                    <th>User Entry</th>
                    <th>Date Entry</th>
                </tr>
                
<?php

            $query ="SELECT TID,
					 TRUCK_NUMBER,
					 REGISTRANT_NAME,
					 REGISTRANT_PHONE,
					 COMPANY_NAME,
					 COMPANY_ADDRESS,
					 COMPANY_PHONE,
					 EMAIL,
					 KIU,
					 TO_CHAR (EXPIRED_KIU, 'dd-mm-yyyy') EXPIRED_KIU,
					 NO_STNK,
					 TO_CHAR (EXPIRED_STNK, 'dd-mm-yyyy') EXPIRED_STNK,
					 TO_CHAR (EXPIRED_DATE, 'dd-mm-yyyy') EXPIRED_DATE,
					 USER_ENTRY,
					 TO_CHAR (DATE_ENTRY, 'dd-mm-yyy hh:mi:ss') DATE_ENTRY
				FROM TID_REPO
			ORDER BY TID";
  
$result_h = $db->query($query);

$res = $result_h->getAll();
//echo $query;die;
$i = 1;

?>
<?php foreach ($res as $row) {
    $db=getDb();
    ?>
                    <tr>
                        <td><?=$i ?></td>
                        <td><?=$row['TID'] ?></td>
                        <td><?=$row['TRUCK_NUMBER'] ?></td>
                        <td><?=$row['REGISTRANT_NAME'] ?></td>
                        <td><?=$row['REGISTRANT_PHONE'] ?></td>
                        <td><?=$row['COMPANY_NAME'] ?></td>
                        <td><?=$row['COMPANY_ADDRESS'] ?></td>
                        <td><?=$row['COMPANY_PHONE'] ?></td>
                        <td><?=$row['EMAIL'] ?></td>
                        <td><?=$row['KIU'] ?></td>
                        <td><?=$row['EXPIRED_KIU'] ?></td>
                        <td><?=$row['NO_STNK'] ?></td>
                        <td><?=$row['EXPIRED_STNK'] ?></td>
                        <td><?=$row['EXPIRED_DATE'] ?></td>
                        <td><?=$row['USER_ENTRY'] ?></td>
                        <td><?=$row['DATE_ENTRY'] ?></td>
                    </tr>
    <?php
    $i++;
}
?>
            </table>