<?php
// Test CVS
//echo $_FILES['uploadfile']['tmp_name'];die;

require_once 'excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('CP1251');

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/



/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/
$data->read($_FILES['uploadfile']['tmp_name']);

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

error_reporting(E_ALL ^ E_NOTICE);
    
                //  echo 'dama';die;
                $db   = getDB();
				$id_vs = $_GET['id'];
				$id_user = $_SESSION["PENGGUNA_ID"];
                if ($_POST['modus'] == 'overwrite'){
                     $delete = "DELETE FROM TB_BOOKING_CONT_AREA WHERE ID_VS = '$id_vs'";
                     $db->query($delete);
                } 

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                $size         	= $data->sheets[0]['cells'][$i][1];
                $type    		= $data->sheets[0]['cells'][$i][2];
                $status    		= $data->sheets[0]['cells'][$i][3];
                $box     		= $data->sheets[0]['cells'][$i][4];
                $teus        	= $data->sheets[0]['cells'][$i][5];
				$tujuan        	= $data->sheets[0]['cells'][$i][6];
				$pelabuhan      = $data->sheets[0]['cells'][$i][7];
                
                $db = getDB();

                  $query 	= "INSERT INTO TB_BOOKING_CONT_AREA
                                (      
								ID_VS,
								SIZE_CONT,
								TYPE_CONT,
								STATUS_CONT,
								BOX,
								ID_PEL_TUJ,
								TEUS,
								PELABUHAN_TUJUAN,
                                CREATE_DATE,
								ID_USER
                                ) VALUES (
                                '$id_vs',
                                '$size',
                                '$type',
                                '$status',
                                '$box',
                                '$tujuan',
                                '$teus',
								'$pelabuhan',
                                SYSDATE,
								'$id_user'
                                )";
                $db->query($query);
                
}

 header('Location: '.HOME.'planning.booking/index');	
?>
