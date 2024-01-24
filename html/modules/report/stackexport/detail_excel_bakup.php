<?php 
$noukk = $_GET['noukk'];

$db = getDB();

$query_vessel = "select 
                        vessel, 
                        voyage_in, 
                        voyage_out, 
			vessel_code						
		from 
                        m_vsb_voyage@dbint_link 
                where 
                        id_vsb_voyage = '$noukk'";
$result		  = $db->query($query_vessel);
$hasil		  = $result->fetchRow();

$vessel 	  = $hasil['VESSEL'];
$voy_in       = $hasil['VOYAGE_IN'];
$voy_out      = $hasil['VOYAGE_OUT'];
$vescode      = $hasil['VESSEL_CODE'];


//detail container							
$query1 = "SELECT
            ETD,
            KODE_PBM,
            ID_REQ,
            NO_CONTAINER, 
            SIZE_CONT,
            TYPE_CONT,
            STATUS_CONT,
            HZ,
            HEIGHT, 
            DURASI,
            DURASI_NUMPUK,
            GATE_IN_DATE,           
            ATD,
                  CASE                                     
                    WHEN DURASI_NUMPUK > 10 THEN (TO_CHAR(ATD, 'YYYYMMDD') - TO_CHAR(GATE_IN_DATE, 'YYYYMMDD') - TO_CHAR(ETD,'YYYYMMDD')) +1  
        END AS MASA2,
                    CASE 
                    --WHEN DURASI_NUMPUK > 10  THEN DURASI - DURASI_NUMPUK 
                    WHEN DURASI_NUMPUK <= 10 AND DURASI_NUMPUK > 5 THEN DURASI - DURASI_NUMPUK                     
        END AS MASA3             
FROM(                    
                    SELECT                  
                                CASE WHEN Z.ID_REQ LIKE 'A%' THEN Z.TGL_MUAT
                                     WHEN Z.ID_REQ LIKE 'UA%' THEN F.TGL_DELIVERY
                                END AS ETD,
                                Z.KODE_PBM,
                                a.ID_REQ,
                                a.NO_CONTAINER,
                                a.SIZE_CONT,
                                a.TYPE_CONT,
                                a.STATUS_CONT,
                                a.HZ,
                                a.HEIGHT,                         
                                case 
                                    when A.ID_REQ LIKE 'A%' THEN (to_date(to_char(to_date(C.ATD,'yyyymmddhh24miss'),'yyyymmdd'),'yyyymmdd') - to_date(TO_CHAR (TO_DATE (B.GATE_IN_DATE, 'yyyymmddhh24miss'), 'yyyymmdd'),'yyyymmdd'))+1
                                    when A.ID_REQ LIKE 'UA%' THEN  (to_date(to_char(to_date(C.ATD,'yyyymmddhh24miss'),'yyyymmdd'),'yyyymmdd') - f.start_stack)+1
                                END AS DURASI, 
                                case 
                                    when A.ID_REQ LIKE 'A%' THEN (to_date(to_char(to_date(Z.TGL_MUAT,'yyyymmddhh24miss'),'yyyymmdd'),'yyyymmdd') - to_date(TO_CHAR (TO_DATE (B.GATE_IN_DATE, 'yyyymmddhh24miss'), 'yyyymmdd'),'yyyymmdd'))+1
                                    when A.ID_REQ LIKE 'UA%' THEN (F.TGL_DELIVERY - F.START_STACK)+1
                                END AS DURASI_NUMPUK, 
                                case 
                                    when A.ID_REQ LIKE 'A%' THEN  to_date(TO_CHAR (TO_DATE (B.GATE_IN_DATE, 'yyyymmddhh24miss'), 'yyyymmdd'),'yyyymmdd')
                                     when A.ID_REQ LIKE 'UA%' THEN F.START_STACK
                            END AS GATE_IN_DATE,
                            TO_DATE(TO_CHAR(TO_DATE(C.ATD,'YYYYMMDDHH24MISS'),'YYYYMMDD'),'YYYYMMDD') AS ATD       
                    FROM 
                          REQ_RECEIVING_D A
                            LEFT JOIN
                            M_CYC_CONTAINER@dbint_link B
                            ON (TRIM (A.NO_CONTAINER) = TRIM (B.NO_CONTAINER)
                            AND TRIM (A.VESSEL) = TRIM (B.VESSEL)
                            AND TRIM (A.VOYAGE_IN) = TRIM (B.VOYAGE_IN)
                            AND TRIM (A.VOYAGE_OUT) = TRIM (B.VOYAGE_OUT))                    
                            LEFT JOIN 
                            M_VSB_VOYAGE@dbint_link C 
                            ON (TRIM(B.VESSEL) = TRIM(C.VESSEL)
                            AND TRIM(B.VOYAGE_IN) = TRIM(C.VOYAGE_IN)
                            AND TRIM(B.VOYAGE_OUT) = TRIM(C.VOYAGE_OUT))                   
                            LEFT JOIN uster.REQUEST_DELIVERY E
                            ON (TRIM(A.ID_REQ) = TRIM(E.NO_REQ_ICT))
                            LEFT JOIN USTER.CONTAINER_DELIVERY F
                            ON(E.NO_REQUEST = F.NO_REQUEST 
                            AND A.NO_CONTAINER = F.NO_CONTAINER)
                            LEFT JOIN REQ_RECEIVING_H Z 
                            ON (A.ID_REQ = Z.ID_REQ) 
                    WHERE     
                            trim(A.VESSEL) =  '$vessel'
                            AND trim(A.VOYAGE_IN) = '$voy_in'
                            AND trim(A.VOYAGE_OUT) = '$voy_out'
                            AND B.GATE_IN_DATE IS NOT NULL)";
							
//echo $query1;die;
$result1 = $db->query($query1);
$baris = $result1->getAll();
?>

<?php 
//header("Content-type: application/octet-stream");
//header("Content-Disposition: attachment; filename=$vescode$voy_in$voy_out.xls");
//header("Pragma: no-cache");
//header("Expires: 0");
?>

<h1>Data Stacking Extension Export</h1>
<h3><?=$vessel?> VOY <?=$voy_in?>/<?=$voy_out?></h3>
<br>
<table border="1">
    <tr>
		<th>NO</th>
		<th>NO REQUEST</th>
		<th>NO CONTAINER</th>
		<th>SIZE </th>
		<th>TYPE</th>
		<th>STATUS</th>		
		<th>Hz</th>		
		<th>GATE IN DATE</th>
                <th>ETD</th>
                <th>DURASI NUMPUK</th>
                <th>DURASI</th>
		<th>ATD</th>		
		<th>KURANG MASA 2</th>
		<th>KURANG MASA 3</th>
		<th>EMKL</th>              
		
    </tr>	
	
	<?
	$no=0;
	foreach ($baris as $row){		
	$no++;			
	$id_req = $row['ID_REQ'];	
	
	?>
	<tr>
		 <td><?=$no?></td>
		 <td><?=$id_req; ?></td>
		 <td><?=$row['NO_CONTAINER']?></td>
		 <td><?=$row['SIZE_CONT']?></td>
		 <td><?=$row['TYPE_CONT']?></td>
		 <td><?=$row['STATUS_CONT']?></td>
		 <td><?=$row['HZ']?></td>
		 <td><?=$row['GATE_IN_DATE']?></td>
                 <td><?=$row['ETD']?></td>
                 <td><?=$row['DURASI_NUMPUK']?></td>
                 <td><?=$row['DURASI']?></td>
		<td><?=$row['ATD']?></td>                
                <td><?=$row['MASA2']?></td>
                <td><?=$row['MASA3']?></td>
                <td><?=$row['KODE_PBM']?></td>                
                
                
		  
	</tr>
	
	<? } ?>
</table>

<?php die(); ?>