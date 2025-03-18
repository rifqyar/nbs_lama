	<script type="text/javascript" src="<?=HOME?>jquery-accordion/lib/chili-1.7.pack.js"></script>
	<script type="text/javascript" src="<?=HOME?>jquery-accordion/lib/jquery.easing.js"></script>
	<script type="text/javascript" src="<?=HOME?>jquery-accordion/lib/jquery.dimensions.js"></script>
	<script type="text/javascript" src="<?=HOME?>jquery-accordion/jquery.accordion.js"></script>
<script>
jQuery().ready(function(){
		// simple accordion
		jQuery('#list1a').accordion();
		}
	);
</script>
<style>
.basic, #list2, #list3, #navigation { width:20em; }
.basic  {
	width: 570px;
	font-family: verdana;
	border: 1px solid #eeeeee;
}
.basic div {
	background-color: #eee;
}

.basic p {
	margin-bottom : 10px;
	border: none;
	text-decoration: none;
	font-weight: bold;
	font-size: 10px;
	margin: 0px;
	padding: 10px;
}
.basic a {
	cursor:pointer;
	display:block;
	padding:5px;
	margin-top: 0;
	text-decoration: none;
	font-weight: bold;
	font-size: 12px;
	color: #393a3a;
	-moz-border-radius: 10px;
	background-color: #d2d3d4;
	border-top: 1px solid #FFFFFF;
	border-bottom: 1px solid #999;
	align:left;
	-moz-border-radius-bottomright: 50px;
	border-bottom-right-radius: 50px;
	/*background-image: url("AccordionTab0.gif");*/
}
.basic a:hover {
	color: #ffffff;
	background-color: #fc90b1;
	/*background-image: url("AccordionTab2.gif");*/
}
.basic a.selected {
	color: #ffffff;
	align:left;
	/*background-image: url("AccordionTab2.gif");*/
	background: -moz-linear-gradient(center top, #fc477e 0%,#fe6191 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #fe6191),color-stop(1, #fc477e));
}
@media print {
	.basic div, #navigation ul, #list2 dd, #list3 div{
		display: block!important;
		height: auto!important
	}
}

.grid-table2 {
	padding: 8px 10px 8px 8px;
}
.grid-header2 {
	font-family: Arial;
	background:#015994 url(../images/th.gif) repeat-x;
	color:#eef;
	-moz-border-radius: 3px;
	font-weight: bold;
	text-transform:uppercase;
	text-align:middle;
	padding: 7px 8px 7px 8px;
}
.tde_f
{
	font-size: 9px;
}
</style>
<?php
	$db=getDb();
	$query_blok = "select DISTINCT(B.NO_UKK) AS NO_UKK, B.NM_KAPAL, B.VOYAGE_IN, B.NM_PELABUHAN_TUJUAN from YD_PLACEMENT_YARD A, TR_VESSEL_SCHEDULE_ICT B where A.ID_VS=B.NO_UKK";
    $result_    = $db->query($query_blok);
    $blok       = $result_->getAll();
?>

<br>
<h2>Vessel Performance</h2>
<br><br>
<div align='center' id="list1a" class='basic'>
	<?php
		foreach($blok as $row)
		{
	?>
	<a><?php echo $row['NM_KAPAL'].' voy. '.$row['VOYAGE_IN'].' - '.$row['NO_UKK'];?></a>
		<div >
			<table class="grid-table2" border='0' cellpadding="1" cellspacing="1"  width="100%">
			<tr>
			<th class="grid-header2">No Container</th>
			<th class="grid-header2" width="200px">Size - Type - Status</th>
			<th class="grid-header2">Height</th>
			<th class="grid-header2">POD</th>
			<th class="grid-header2">Payment (Anne / SP2)</th>
			<th class="grid-header2">Gate In</th>
			<th class="grid-header2">Placement</th>
			<th class="grid-header2">Confirm</th>
			<th class="grid-header2">E/I</th>
			</tr>
			<?php
			$query_2 	= "select b.NO_CONTAINER, b.SIZE_CONT, b.TYPE_CONT, b.STATUS_CONT,
							case when b.SIZE_CONT='20' THEN '8,6' else '9,6' end HEIGHT,
							b.PEL_TUJ, 'E' KET from tr_nota_anne_ict_h a, tr_nota_anne_ict_d b 
							where TRIM(a.NO_REQ_ANNE)=TRIM(b.NO_REQ_ANNE) AND a.NO_UKK='WANO01120002'";
			$result_2   = $db->query($query_2);
			$blok2      = $result_2->getAll();
			
			foreach($blok2 as $row2){
			?>

			<tr align="center">
				<TD class="tde_f"><?php echo $row2['NO_CONTAINER'];?></td>
				<TD class="tde_f"><?php echo $row2['SIZE_CONT'].' '.$row2['TYPE_CONT'].' '.$row2['STATUS_CONT'];?></td>
				<TD class="tde_f"><?php echo $row2['HEIGHT'];?></td>
				<TD class="tde_f"><?php echo $row2['PEL_TUJ'];?></td>
				<TD class="tde_f">Y</td>
				<TD class="tde_f">-</td>
				<TD class="tde_f">-</td>
				<TD class="tde_f">-</td>
				<TD class="tde_f"><?php echo $row2['KET'];?></td>
			</tr>
			<?}?>
			</table>
			
		</div>
	<?PHP }?>
	<a>Contoh Kapal2</a>
		<div >
			Komponen Kapal2
		</div>
	<a>Contoh Kapal3</a>
		<div >
			Komponen Kapal3
		</div>	
</div>
<br><br>
