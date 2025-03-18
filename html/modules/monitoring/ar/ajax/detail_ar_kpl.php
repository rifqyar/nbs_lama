<!--	<link rel="stylesheet" href="./trrjs/jquery.treeview.css" />
<script src="./trrjs/jquery.treeview.js" type="text/javascript"></script>-->
<?php
 $u = $_GET['id']; 
  $db = getDB('pyma');
/*$query = "select TO_CHAR(count(1),'999,999,999,999,999,999') JML, a.CURRENCY, TO_CHAR(SUM(a.AMOUNT),'999,999,999,999,999,999.00') AMOUNT_SIMOP, TO_CHAR(SUM(b.TOTAL ),'999,999,999,999,999,999.00') AMOUNT_SIMKEU
			from RKA_TAB_ALL_KPL a ,AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and to_number(b.year_up)=2013 and a.status_akhir IN ('4','5')
			group by a.CURRENCY";*/
$query = "select TO_CHAR(count(1),'999,999,999,999,999,999') JML, a.CURRENCY, TO_CHAR(SUM(a.JUMLAH),'999,999,999,999,999,999.00') AMOUNT_SIMOP, TO_CHAR(SUM(b.AMOUNT_LINE ),'999,999,999,999,999,999.00') AMOUNT_SIMKEU
			from RKA_TAB_ALL_KPL a ,AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and to_number(b.year_up)=2013 and a.status_akhir IN ('4','5')
			group by a.CURRENCY";
			
/*$query1 = " select TO_CHAR(count(1),'999,999,999,999,999,999') JML, a.CURRENCY, TO_CHAR(SUM(a.AMOUNT),'999,999,999,999,999,999.00') AMOUNT_SIMOP, TO_CHAR(SUM(b.TOTAL ),'999,999,999,999,999,999.00') AMOUNT_SIMKEU
			from RKA_TAB_ALL_KPL a ,AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and to_number(b.year_up)=2012 and a.status_akhir IN ('4','5')
			group by a.CURRENCY";*/
$query1 = " select TO_CHAR(count(1),'999,999,999,999,999,999') JML, a.CURRENCY, TO_CHAR(SUM(a.JUMLAH),'999,999,999,999,999,999.00') AMOUNT_SIMOP, TO_CHAR(SUM(b.AMOUNT_LINE ),'999,999,999,999,999,999.00') AMOUNT_SIMKEU
			from RKA_TAB_ALL_KPL a ,AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and to_number(b.year_up)=2012 and a.status_akhir IN ('4','5')
			group by a.CURRENCY";

/*$query2 = " SELECT a.CURRENCY, to_char(sum(a.amount),'999,999,999,999,999,999.00') AMT, TO_CHAR(count(1),'999,999,999,999,999,999') as JML_R FROM RKA_TAB_EXIST_KPL a
			where a.currency is not null
			group by a.CURRENCY";*/
$query2 = " SELECT a.CURRENCY, to_char(sum(a.JUMLAH),'999,999,999,999,999,999.00') AMT, TO_CHAR(count(1),'999,999,999,999,999,999') as JML_R FROM RKA_TAB_EXIST_KPL a
			where a.currency is not null
			group by a.CURRENCY";

$query3 = " SELECT TO_CHAR(count(1),'999,999,999,999,999,999') as JML_R FROM RKA_TAB_EXIST_KPL
			where (to_number(status_akhir)<=3 and to_number(status_akhir)>0) and no_pranota is null";

 $query_1 = $db->query($query1);
 $query_ = $db->query($query);
  $query_2 = $db->query($query2);
  $query_3 = $db->query($query3);
 //$data   = $query_->fetchRow();
 //$data1   = $query_1->fetchRow();
 //$data2   = $query_2->fetchRow();
 $data3   = $query_3->fetchRow();
 
?>
<script>
function export_excel2(t,n,c)
{
	window.open("<?=$HOME?>monitoring.pyma.excel_pdf/export_excel_kpl?tahun="+t+"&inv="+n+"&curr="+c);
	
}
/*jQuery().ready(function(){
		// simple accordion
	jQuery('#list1a').accordion({active:false, collapsible:true});
		$("#browser").treeview({animated: "fast",
		collapsed: true});
});*/

</script>

<!--
<style>
.grid-header2 {
	font-family: Arial;
	background:#015994 url(./images/th.gif) repeat-x;
	color:#eef;
	-moz-border-radius: 2px;
	font-weight: bold;
	text-transform:uppercase;
	text-align:middle;
	padding: 7px 8px 7px 8px;
}
.grid-cell2 {
	color:#777;
	font-size : 10px;
	-moz-border-radius: 2px;
	padding: 6px 6px 6px 6px;
	border-bottom-style:solid #000000;
	background-color:#ffffff;
}
.ui-accordion { width: 100%; height:100%}
.ui-accordion .ui-accordion-header { cursor: pointer; position: relative; margin-top: 1px; zoom: 1;border:0; }
.ui-accordion .ui-accordion-li-fix { display: inline; }
.ui-accordion .ui-accordion-header-active { border-bottom: 0 !important; }
.ui-accordion .ui-accordion-header a { display: block; font-size: 1em; padding: .5em .5em .5em .7em; background-color:#ffffff;}
.ui-accordion-icons .ui-accordion-header a { padding-left: 2.2em; }
.ui-accordion .ui-accordion-header .ui-icon { position: absolute; left: .5em; top: 50%; margin-top: -8px; }
.ui-accordion .ui-accordion-content { border:0;padding: 1em 2.2em; border-top: 0; margin-top: -2px; position: relative; top: 1px; margin-bottom: 2px; overflow: auto; display: none; zoom: 1; }
.ui-accordion .ui-accordion-content-active { border:0;display: block; }
</style>
-->

	<table class="grid-table" cellpadding="1" cellspacing="1"  width="100%">
	  <tr>
		<th class="grid-header2" rowspan="2">NO</th>   
		<th class="grid-header2" rowspan="2">URAIAN</th>
		<th class="grid-header2" rowspan="2">KURS</th>
		<th class="grid-header2" colspan="3">AR 2012</th>
		<th class="grid-header2" colspan="3">AR 2013</th>
		<th class="grid-header2" colspan="2">BELUM DI AR 2012/2013</th>
		<th class="grid-header2" rowspan="2">ACTION</th>
     </tr>
	 <tr>
		<th class="grid-header2">JML NOTA</th>   
		<th class="grid-header2">AMOUNT SIMOP</th>   
		<th class="grid-header2">AMOUNT SIMKEU</th>   
		<th class="grid-header2">JML NOTA</th>   
		<th class="grid-header2">AMOUNT SIMOP</th>   
		<th class="grid-header2">AMOUNT SIMKEU</th>   
		<th class="grid-header2">JML NOTA</th>   
		<th class="grid-header2">AMOUNT SIMOP</th>    
     </tr>
<?php	
	unset($rowspan);
	$rowspan='rowspan="'.$query_1->RecordCount().'"';
	
?>
	 <tr>
		<td CLASS="grid-cell2" align='center' <?=$rowspan?>>A</td>
		<td align='center' CLASS="grid-cell2" <?=$rowspan?>"><b><i>SIMOP 2012 Sudah Invoice</i></b></td>
<?php
	$i=1;
	while($data1 = $query_1->fetchRow()) {
		if($i>1) {
?>
		<tr>
<?php
		}
?>
		<td align='center' CLASS="grid-cell2"><b><?=$data1[CURRENCY];?></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data1[JML];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data1[AMOUNT_SIMOP];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data1[AMOUNT_SIMKEU];?></font></b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2" ><a onclick="export_excel2('2012','I','<?=$data1[CURRENCY];?>')" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" width="80%" height="80%"></a></td>
	</tr>
<?php
		$i++;
	}
	if($i==1) {
?>
		<td align='center' CLASS="grid-cell2"></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</font></b></td>
		<td align='center' CLASS="grid-cell2">0</font></b></td>
		<td align='center' CLASS="grid-cell2">0</b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"></td>
	</tr>
<?php
	}
	
	unset($rowspan);
	$rowspan='rowspan="'.$query_->RecordCount().'"';
?>
	<tr>
		<td align='center' CLASS="grid-cell2" <?=$rowspan?>>B</td>
		<td align='center' CLASS="grid-cell2" <?=$rowspan?>><b><i>SIMOP 2012 Sudah Invoice</i></b></td>
<?php
	$i=1;
	while($data = $query_->fetchRow()) {
		if($i>1) {
?>
		<tr>
<?php
		}
?>
		<td align='center' CLASS="grid-cell2"><b><?=$data[CURRENCY];?></b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data[JML];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data[AMOUNT_SIMOP];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data[AMOUNT_SIMKEU];?></font></b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><a onclick="export_excel2('2013','I','<?=$data[CURRENCY];?>')" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" width="80%" height="80%"></a></td>
	</tr>
<?php
		$i++;
	}
	if($i==1) {
?>
		<td align='center' CLASS="grid-cell2"></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"></td>
	</tr>
<?php
	}
	
	unset($rowspan);
	$rowspan='rowspan="'.$query_2->RecordCount().'"';
?>
	
	<tr >
		<td align='center' CLASS="grid-cell2" <?=$rowspan?>>C</td>
		<td align='center' CLASS="grid-cell2" <?=$rowspan?>><b><i>SIMOP 2012 Belum Invoice</i></b></td>
<?php
	$i=1;
	while($data2 = $query_2->fetchRow()) {
		if($i>1) {
?>
		<tr>
<?php
		}
?>
		<td align='center' CLASS="grid-cell2"><b><?=$data2[CURRENCY];?></b></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data2[JML_R];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data2[AMT];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><a onclick="export_excel2('2012','NI','<?=$data2[CURRENCY];?>')" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" width="80%" height="80%"></a></td>
	</tr>
<?php
		$i++;
	}
	if($i==1) {
?>
		<td align='center' CLASS="grid-cell2"></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</font></b></td>
		<td align='center' CLASS="grid-cell2"></td>
	</tr>
<?php
	}
?>	
	<tr >
		<td align='center' CLASS="grid-cell2">D</td>
		<td align='center' CLASS="grid-cell2"><b><i>SIMOP 2012 Belum Lengkap / Pranota</i></b></td>
		<td align='center' CLASS="grid-cell2"></td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2">0</td>
		<td align='center' CLASS="grid-cell2"><b><font color="red"><?=$data3[JML_R];?></font></b></td>
		<td align='center' CLASS="grid-cell2"><b><font color="red">0</font></b></td>
		<td align='center' CLASS="grid-cell2"><a onclick="export_excel2('2012','','')" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" width="80%" height="80%"></a></td>
	</tr>
	
		
	<!--<tr >
		<td align='center' CLASS="grid-cell2"></td>
		<td align='center' CLASS="grid-cell2" colspan="10">
		
		<div align='left' id="list1a" >
		<h3><a href="#"><b><i>Status N R C 2012</i></b></a></h3>
		<div >
			
			<ul id="browser" class="filetree">
		<? for($i=1;$i<=4;$i++){
			
				if ($i==1)
				{
					$ir='Terminal 1';
					$t=1;
				}
				else if ($i==2)
				{
					$ir='Terminal 2';
					$t=2;
				}
				else if ($i==3)
				{
					$ir='Terminal 3';
					$t=3;
				}
				else
				{
					$ir='Non Terminal';
					$t=9;
				}	
				$query2 = " SELECT to_char(sum(a.amount),'999,999,999,999,999,999.00') AMT,count(1) as JML_R FROM rka_tab_exist a where TERMINAL='$t'";
				$query_2 = $db->query($query2);
				$data2   = $query_2->fetchRow();
			
		?>
		<li class="closed" id="i<?=$i?>"><span class="folder"><table><tr><td width="100"> <?=$ir?></td>
			<td width="101">&nbsp;</td>
			<td width="100" align="right">Jumlah Nota : </td>
			<td width="80" align="right"><?=$data2[JML_R]?></td>
			<td width="30">&nbsp;</td>
			<td width="100" align="right"> Amount :</td>
			<td width="30" align="right">Rp.</td>
			<td width="100" align="right"><?=$data2[AMT]?></td>
		</tr></table></span>
			
			<? for($y=1;$y<=3;$y++){
				if ($y==1)
				{
					$xt='N';
				}
				else if ($y==2)
				{
					$xt='R';
				}
				else
					$xt='C';
				$queryR = " SELECT to_char(nvl(sum(a.amount),0),'999,999,999,999,999,999.00') AMT,count(1) as JML_R FROM rka_tab_exist a WHERE a.STATUS_AKHIR='$xt' and TERMINAL='$t'";
				$query_R = $db->query($queryR);
				$dataR   = $query_R->fetchRow();
			?>
			<ul>
				<li><span class="file">
					<table>
					<tr>
						<td width="100"> Status <?=$xt?></td>
						<td width="85">&nbsp;</td>
						<td width="100" align="right">Jumlah Nota : </td>
						<td width="80" align="right"><?=$dataR[JML_R]?></td>
						<td width="30">&nbsp;</td>
						<td width="100" align="right"> Amount :</td>
						<td width="30" align="right">Rp.</td>
						<td width="100" align="right"><?=$dataR[AMT]?></td>
					</tr>
					</table>
				</span></li>
			</ul>
			<?}?>
		</li>
		<?}
		$queryW = " SELECT to_char(NVL(sum(a.amount),0),'999,999,999,999,999,999.00') AMT,count(1) as JML_R FROM rka_tab_exist a";
				$query_W = $db->query($queryW);
				$dataW   = $query_W->fetchRow();
		
		?>
		<li><span class="folder">
					<table>
					<tr>
						<td width="100"> Total</td>
						<td width="101">&nbsp;</td>
			<td width="100" align="right">Jumlah Nota : </td>
			<td width="80" align="right"><?=$dataW[JML_R]?></td>
			<td width="30">&nbsp;</td>
			<td width="100" align="right"> Amount :</td>
			<td width="30" align="right">Rp.</td>
			<td width="100" align="right"><?=$dataW[AMT]?></td>
					</tr>
					</table>
				</span>
				<? for($y=1;$y<=3;$y++){
				if ($y==1)
				{
					$xt='N';
				}
				else if ($y==2)
				{
					$xt='R';
				}
				else
					$xt='C';
			$queryW = " SELECT to_char(sum(a.amount),'999,999,999,999,999,999.00') AMT,count(1) as JML_R FROM rka_tab_exist a WHERE a.STATUS_AKHIR='$xt'";
				$query_W = $db->query($queryW);
				$dataW   = $query_W->fetchRow();
			?>
			<ul>
				<li><span class="file">
					<table>
					<tr>
						<td width="100"> Status <?=$xt?></td>
						<td width="85">&nbsp;</td>
						<td width="100" align="right">Jumlah Nota : </td>
						<td width="80" align="right"><?=$dataW[JML_R]?></td>
						<td width="30">&nbsp;</td>
						<td width="100" align="right"> Amount :</td>
						<td width="30" align="right">Rp.</td>
						<td width="100" align="right"><?=$dataW[AMT]?></td>
					</tr>
					</table>
				</span></li>
			</ul>
			<?}?>
				
				</li>
	</ul>
		</div>
	
	</div>
		</td>
		</tr>-->	
	</table>
	
	
