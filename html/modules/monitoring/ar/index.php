
<style>
.content2{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side2{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}
</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');


$db=getDb('pyma');
$query="select KD_CABANG,NM_CABANG from mst_cabang order by to_number(KD_CABANG)";
$rr=$db->query($query);
$data_cb=$rr->getAll();
?>


<script type="text/javascript">	
$(document).ready(function() 
{	
	$('#loadx').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#loadx').load("<?=HOME?>monitoring.ar.ajax/detail_ar");
	
	$('#loadx2').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#loadx2').load("<?=HOME?>monitoring.ar.ajax/detail_ar_kpl");
	/*
	$('#loadx3').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#loadx3').load("<?=HOME?>monitoring.ar.ajax/detail_ar");
	
	$('#loadx4').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#loadx4').load("<?=HOME?>monitoring.ar.ajax/detail_ar");
	*/
	$( "#tabs" ).tabs();
	$( "#tabspage" ).tabs();
	$( "#tabspage1" ).tabs();
	
});

function dash()
{
	$('#dashboard').load("<?=HOME?>monitoring.ar.ajax/dash").dialog({modal:true, height:500,width:800, title:'Dashboard AR - PYMA'});
}
</script>




<div class="content2">
	<div class="main_side2">
	<p>
	<h2> <img src="<?=HOME?>images/bookd.png" height="7%" width="7%" style="vertical-align:middle"> <font color="#81cefa">Monitoring </font>
	<font size="3px" color="#606263">AR SIMKEU</font></h2></p>
	<!--<a class="link-button" style="height:25" onclick="add_ba()">
            <img border="0" src="<?=HOME?>images/tambah.png">
            Insert BA
            </a>-->
	<p><br/></p>
	<!--<table id='ar_keu' width="100%"></table> <div id='pg_ar'></div>-->
	<br>
		<table> <tr><td>Pilih Kode Cabang </td><td> : </td><td><select id="kd_cabang">
		<? foreach($data_cb as $row){
			$vcb=$row['KD_CABANG'];
			$ncb=$row['NM_CABANG'];
		?>
		<option value="<?=$vcb?>"><?=$ncb?></option>
		<?}?></td></tr>
		</table>
	</select>
	<br>
	
	<div id="tabs">
<ul>
<li><a href="#tabs-1">Jasa Barang</a></li>
<li><a href="#tabs-2">Jasa Kapal</a></li>
<li><a href="#tabs-3">Jasa Petikemas</a></li>
<li><a href="#tabs-4">Jasa Rupa-Rupa</a></li>
</ul>
<div id="tabs-1">	
	<div id="loadx" align='center'></div>
	<br>
	<button onclick="dash()"><img src="<?=HOME?>images/DASHBOARD.png" width="80%" height="80%"></button>
</div>
<div id="tabs-2">
	<div id="loadx2" align='center'></div>
</div>
<div id="tabs-3">

</div>
<div id="tabs-4">


</div>
	
</div>
	<br/>
	<br/>
	</div>
</div>
<div>
<div id="dashboard"></div>
</div>