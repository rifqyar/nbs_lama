<?php 
outputRaw();
$db = getDB(dbint);
$periode_awal = $_GET[periode_awal];
$periode_akhir = $_GET[periode_akhir];
$status = $_GET[status];

//echo $status;
//echo $periode_awal;
//echo $periode_akhir;
//die();

if ($status == "I"){ 

$query = "select 
          case 
                when cyc_cont_iso like '%2%' then '20'
                when cyc_cont_iso like '%4%' then '40'
                when cyc_cont_iso like '%9%' then '45'
                when cyc_cont_iso like '%L%' then '45'                
          end ukuran,
          cdv_vsl_name VESSEL, 
          cyc_cont_operator OPERATOR_CODE,
          cdg_oper_name OPERATOR_NAME,
          cyc_cont_contno AS NO_CONTAINER, 
          cyc_cont_point, 
          cyc_cont_class E_I, 
          cyc_cont_iso ISO_CODE, 
          cyc_cont_status STATUS, 
          cyc_cont_invessel VESSEL_CODE, 
          cyc_cont_invoyage VOYAGE, 
          truck_out,
		  truck_outb,
          discharge_confirm,
		  discharge_confirmb,
          round(truck_out-discharge_confirm,2) as selisih 
from                     
        (select           
                           B.CDV_VSL_name,
                           a.cyc_cont_operator,
                           c.cdg_oper_name, 
                           a.cyc_cont_contno, 
                            a.cyc_cont_point, 
                            a.cyc_cont_class, 
                            a.cyc_cont_iso, 
                            a.cyc_cont_status, 
                            a.cyc_cont_invessel, 
                            a.cyc_cont_invoyage, 
                            a.cyc_cont_indate,       --tanggal discharge confirm
                            a.cyc_cont_intime,      --jam discharge confirm
                             to_char(to_date(a.cyc_cont_indate || a.cyc_cont_intime, 'yyyymmddhh24miss'), 'yyyymmdd hh24:mi:ss') as discharge_confirmb,
                            to_date(a.cyc_cont_indate || a.cyc_cont_intime, 'yyyymmddhh24miss') as discharge_confirm,                   
                            a.cyc_cont_outdate, --tanggal truck out 
                            a.cyc_cont_outtime, --jam truck out 
                            to_date(a.cyc_cont_outdate||a.cyc_cont_outtime, 'yyyymmddhh24miss') as truck_out,
                            to_char(to_date(a.cyc_cont_outdate||a.cyc_cont_outtime, 'yyyymmddhh24miss'),'yyyymmdd hh24:mi:ss') as truck_outb         
                    from 
                            pnoadm.cyc_container a left join pnoadm.cdv_vessel b 
                            on(a.cyc_cont_invessel = b.cdv_vsl_code) 
                            left join cdg_operator c
                            on(a.cyc_cont_operator = c.cdg_oper_code) 
                    where 
                            a.cyc_cont_class = 'II'                            
                            and a.cyc_cont_indate >= '$periode_awal' and a.cyc_cont_indate < '$periode_akhir')";
				
$res = $db->query($query); ?>

<div style="margin:20 20 20 20">

<h1>Dwelling Time Container Import</h1>
    <table class="table_alumni">
	<tr>
	<th>No</th>
	<th>No Container</th>
	<th>Ukuran</th>
	<th>Vessel</th>
	<th>Voyage</th>
	<th>Carrier</th>	
	<th>E/I</th>
	
	<th>ISO CODE</th>
	<th>F/E</th>	
	<th>Discharge Confirm</th>
	<th>Truck Out</th>
	<th>Dwelling Time</th>	
	</tr>
</div>
<?php 
$c=1;
while ($row = $res->fetchRow()){?>
<tr>
	<td class="td-tengah"><?=$c++;?></td>
	<td class="td-kiri"><?=$row[NO_CONTAINER];?></td>
	<td class="td-kiri"><?=$row[UKURAN];?></td>
	<td class="td-kiri"><?=$row[VESSEL];?></td>
	<td class="td-kiri"><?=$row[VOYAGE];?></td>
	<td class="td-kiri"><?=$row[OPERATOR_CODE];?></td>	
	<td class="td-kiri"><?=$row[E_I];?></td>
	<td class="td-kiri"><?=$row[ISO_CODE];?></td>
	<td class="td-tengah"><?=$row[STATUS];?></td>
	<td class="td-kiri"><?=$row[DISCHARGE_CONFIRMB];?></td>
	<td class="td-kiri"><?=$row[TRUCK_OUTB];?></td>
	<td class="td-kiri"><?=$row[SELISIH];?></td>	
</tr>
<?php } ?>

</table>
<?php }

else {

$query = "SELECT 
          case 
                when cyc_cont_iso like '%2%' then '20'
                when cyc_cont_iso like '%4%' then '40'
                when cyc_cont_iso like '%9%' then '45'
                when cyc_cont_iso like '%L%' then '45'                
          end ukuran,		  
		  CDV_VSL_NAME VESSEL, 
          cyc_cont_operator OPERATOR_CODE,          
          CYC_CONT_CONTNO NO_CONTAINER,          
          cyc_cont_class E_I, 
          cyc_cont_iso ISO_CODE, 
          cyc_cont_status STATUS, 
          cyc_cont_outvessel VESSEL_CODE, 
          cyc_cont_outvoyage VOYAGE, 
          truck_in, 
          loading_confirm,
		  to_char(truck_in, 'yyyymmdd hh24:mi:ss') as TRUCK_INB, 
          to_char(loading_confirm,'yyyymmdd hh24:mi:ss')as LOADING_CONFIRMB,		  
          round(loading_confirm-truck_in,2) as selisih 
from                     
        (select           
                           B.CDV_VSL_name, 
                           a.cyc_cont_operator,
                           c.cdg_oper_name,
                           a.cyc_cont_contno, 
                            a.cyc_cont_point, 
                            a.cyc_cont_class, 
                            a.cyc_cont_iso, 
                            a.cyc_cont_status, 
                            a.cyc_cont_outvessel, 
                            a.cyc_cont_outvoyage, 
                            a.cyc_cont_indate,       
                            a.cyc_cont_intime,      
                            to_date(a.cyc_cont_indate || a.cyc_cont_intime, 'yyyymmddhh24miss') as truck_in,                   
                            a.cyc_cont_outdate,  
                            a.cyc_cont_outtime, 
                            to_date(a.cyc_cont_outdate||a.cyc_cont_outtime, 'yyyymmddhh24miss') as loading_confirm         
                    from 
                            pnoadm.cyc_container a left join pnoadm.cdv_vessel b 
                            on(a.cyc_cont_outvessel = b.cdv_vsl_code)
                            left join cdg_operator c
                            on(a.cyc_cont_operator = c.cdg_oper_code)   
                    where 
                            a.cyc_cont_class = 'XX'
                            and a.cyc_cont_indate > '$periode_awal' and a.cyc_cont_indate < '$periode_akhir')";	

//echo $query2;
//die();							
				
$res = $db->query($query); ?>

<div style="margin:20 20 20 20">

<h1>Dwelling Time Container Export</h1>
    <table class="table_alumni">
	<tr>
	<th>No</th>
	<th>No Container</th>
	<th>Ukuran</th>
	<th>Vessel</th>
	<th>Voyage</th>
	<th>Carrier</th>	
	<th>E/I</th>	
	<th>ISO CODE</th>
	<th>F/E</th>
	<th>TRUCK IN</th>	
	<th>LOADING Confirm</th>	
	<th>Dwelling Time</th>	
	</tr>
</div>
<?php 
$d=1;
while ($row = $res->fetchRow()){?>
<tr>
	<td class="td-tengah"><?=$d++;?></td>
	<td class="td-kiri"><?=$row[NO_CONTAINER];?></td>
	<td class="td-kiri"><?=$row[UKURAN];?></td>
	<td class="td-kiri"><?=$row[VESSEL];?></td>
	<td class="td-kiri"><?=$row[VOYAGE];?></td>
	<td class="td-kiri"><?=$row[OPERATOR_CODE];?></td>	
	<td class="td-kiri"><?=$row[E_I];?></td>
	<td class="td-kiri"><?=$row[ISO_CODE];?></td>
	<td class="td-tengah"><?=$row[STATUS];?></td>
	<td class="td-kiri"><?=$row[TRUCK_INB];?></td>
	<td class="td-kiri"><?=$row[LOADING_CONFIRMB];?></td>	
	<td class="td-kiri"><?=$row[SELISIH];?></td>	
</tr>
<?php } ?>

</table>
<?php } ?>