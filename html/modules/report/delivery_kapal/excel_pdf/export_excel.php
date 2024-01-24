<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$no_ukk = $_GET['no_ukk'];
$kg = $_GET['keg'];

$db = getDB();
$query_p = "SELECT 
					VESSEL NM_KAPAL,
					VOYAGE_IN, 
					VOYAGE_OUT,
					OPERATOR_NAME NM_PEMILIK 
			FROM 
					opus_repo.m_vsb_voyage 
			WHERE 
					id_vsb_voyage='$no_ukk'";
$result_p = $db->query($query_p);
$rowe = $result_p->fetchRow();

$ves = $rowe[NM_KAPAL];
$vin = $rowe[VOYAGE_IN];
$vot = $rowe[VOYAGE_OUT];

 header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=List_" . $no_ukk . ".xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>


<h2><i>Laporan Delivery Kapal</i></h2> 
<h2><i> <?php echo $ves." - ".$vin."/".$vot ;?> </i> </h2>


        <br/>

            <table border="1">
                <tr>
                    <th>No.</th>
                    <th>Container Numb</th>
                    <th>Size</th>					
                    <th>Status</th>
					<th>NO BL</th>					
                    <th>Gate In</th>
                    <th>GateIn OPR</th>
                    <th>Gate Out</th>
					<th>GateOut OPR</th>
                    <th>Customer Name</th>
					<th>Truck NO</th>
					<th>ID REQ</th>
					<th>Invoice</th>
                    
                    
                </tr>
                
<?php


            $query ="SELECT 
                            a.NO_CONTAINER, 
                            a.SIZE_CONT AS SIZE_,                             
                            a.STATUS AS STATUS,                           
                            a.POD AS POD,
                            a.POL AS POL,
                            b.NO_REQUEST, 
                            b.NO_TRX as NO_NOTA, 
                            b.STATUS_PAID as STATUS_PAYMENT,                            
                            TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                            TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,                          
                           c.bl_numb,
                           c.emkl,
						   a.TRUCK_ID,
						   c.ID_REQ,
						   b.NO_TRX,
						   a.GATE_IN_OPR,
						   a.GATE_OUT_OPR
                     FROM 
                            opus_repo.m_cyc_container a left join opus_repo.m_billing b
                            on (a.billing_request_id = b.no_request 
                            and a.vessel_code=b.vessel 
                            and trim(a.voyage_in)=trim(b.voy_in))
                            left join req_delivery_h c 
                            on (trim(b.no_request) = trim(c.id_req))                                                        
                    WHERE 
                            a.vessel='$ves' 
                            and a.voyage_in='$vin' and a.voyage_out= '$vot'
                            and a.E_I='I'
							and c.ID_REQ is not null
							and c.ID_REQ  not like '%U%'";        

$result_h = $db->query($query);

$res = $result_h->getAll();
//echo $query;die;
$i = 1;

?>
<?php foreach ($res as $row) {
    $db=getDb();
    $req=$row['NO_REQUEST'];
    if($kg=='I'){
     
        $qrreq="select EMKL, NO_SPPB from req_delivery_h where id_req='$req'";
        $qrex=$db->query($qrreq);
        $rts=$qrex->fetchRow();

    }    

    ?>
                    <tr>

                        <td><?=$i ?></td>
                        <td><?=$row['NO_CONTAINER'] ?></td>
                        <td><?=$row['SIZE_'] ?></td>						                    
                        <td><?=$row['STATUS'] ?></td>
						<td><?=$row['BL_NUMB'] ?></td> 						
                        <td><?=$row['TGL_GATE_IN'] ?></td>
						<td><?=$row['GATE_IN_OPR'] ?></td>
                        <td><?=$row['TGL_GATE_OUT'] ?></td>
						<td><?=$row['GATE_OUT_OPR'] ?></td>                        
                        <td><?=$row['EMKL'] ?></td>
                        <td><?=$row['TRUCK_ID'] ?></td>
						<td><?=$row['ID_REQ'] ?></td>
						<td><?=$row['NO_TRX'] ?></td>			
                    </tr>
    <?php
    $i++;
}

$query_summary = "select
				size_cont,
				status,				
                case when size_cont = '40' and status = 'FULL' and TIPE_REQ = 'perpanjangan' then jumlah end as emfullext,
                case when size_cont = '40' and status = 'FULL' and TIPE_REQ = 'pertama' then jumlah end as emfullori,
                case when size_cont = '20' and status = 'FULL' and TIPE_REQ = 'perpanjangan' then jumlah end as duafullext,
                case when size_cont = '20' and status = 'FULL' and TIPE_REQ = 'pertama' then jumlah end as duafullori,
				case when size_cont = '40' and status = 'EMPTY' and TIPE_REQ = 'perpanjangan' then jumlah end as emempext,
                case when size_cont = '40' and status = 'EMPTY' and TIPE_REQ = 'pertama' then jumlah end as emempori,
                case when size_cont = '20' and status = 'EMPTY' and TIPE_REQ = 'perpanjangan' then jumlah end as duaempext,
                case when size_cont = '20' and status = 'EMPTY' and TIPE_REQ = 'pertama' then jumlah end as duaempori	
			from (             
                  select 
                        count(no_container) as jumlah,
                        size_cont,
                        status,
                        tipe_req 
                  from(
                        SELECT 
                                a.NO_CONTAINER, 
                                a.SIZE_CONT,                          
                                a.STATUS,                       
                                c.ID_REQ,
                                case when id_req like '%SP2%' then 'perpanjangan'
                                     when id_req like '%REQ%' then 'pertama'
                                end tipe_req                                                     
                        FROM 
                                opus_repo.m_cyc_container a left join opus_repo.m_billing b
                                on (a.billing_request_id = b.no_request 
                                and a.vessel_code=b.vessel 
                                and trim(a.voyage_in)=trim(b.voy_in))
                                left join req_delivery_h c 
                                on (trim(b.no_request) = trim(c.id_req))                                                        
                        WHERE 
                                a.vessel='$ves' 
                                and a.voyage_in='$vin' and a.voyage_out= '$vot'
                                and a.E_I='I'
                                and c.ID_REQ is not null
                                and c.ID_REQ  not like '%U%'                                                   
                                )
                        group by 
                                size_cont,status,tipe_req)
								";
								
$result_summary = $db->query($query_summary);
$res_summary = $result_summary->getAll();
$i = 1;
?>
            </table>
			
	<h1>Summary</h1>		
			
			<table border="1">
			<tr>
				
				<th colspan="4">Awal</th>
				<th colspan="4">Perpanjangan</th>
							
			</tr>
			<tr>
				<th colspan="2">Full</th>
				<th colspan="2">Empty</th>
				<th colspan="2">Full</th>
				<th colspan="2">Empty</th>					
			</tr>
			<tr>
				<th>20</th>
				<th>40</th>
				<th>20</th>
				<th>40</th>
				<th>20</th>
				<th>40</th>
				<th>20</th>
				<th>40</th>				
			</tr>
			
			
	
<?php foreach ($res_summary as $row_summary) {?>    

	
			<tr>
				
				<td><?php echo $row_summary['DUAFULLORI'];?></td>
				<td><?php echo $row_summary['EMFULLORI'];?></td>
				<td><?php echo $row_summary['DUAEMPORI'];?></td>
				<td><?php echo $row_summary['EMEMPORI'];?></td>
				<td><?php echo $row_summary['DUAFULLEXT'];?></td>
				<td><?php echo $row_summary['EMFULLEXT'];?></td>
				<td><?php echo $row_summary['DUAEMPEXT'];?></td>
				<td><?php echo $row_summary['EMEMPEXT'];?></td>
		
				
			</tr>
<?php } ?>			
			
			</table>
			