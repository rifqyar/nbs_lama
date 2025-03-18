<?php
	$db=getDb();
    $yard_id     = $_GET['id'];
    $no_ukk     = $_GET['id_vs'];
    $query_        = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
    $result_       = $db->query($query_);
    $yard          = $result_->fetchRow();
    $nama_ya       = $yard['NAMA_YARD'];
	$id_bg	=1;
?>
<script>

	
$(document).ready(function() 
{
    $('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout");   
	
	$('#info_group').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#info_group').load("<?=HOME?>planning.yard_allocation_import/info_group?id_vs=<?=$no_ukk?> #info_group");   
});

function plan_t(a,b,c,d,e,f,g)
{
	$( "#size_c" ).val(a);
	$( "#type_c" ).val(b);
	$( "#status_c" ).val(c);
	$( "#hg_cn" ).val(d);
	$( "#id_bk" ).val(e);
	$( "#hz_c" ).val(f);
	$( "#tier_c" ).val(g);	
}

function load_lp()
{
	var blid=$('#block_id').val();
	$.ajaxSetup ({
    // Disable caching of AJAX responses
	
    cache: false
	});
	
	$('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_vs=<?=$no_ukk?>&id_block="+blid);   
	$('#lp<?=$id_bg?>').load("<?=HOME?>planning.yard_allocation_import.ajax/load_lapangan?id=<?=$yard_id?>&id_vs=<?=$no_ukk?>&id_block="+blid+"&id_bg=<?=$id_bg?>");   
}

</script>
<head>
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; text-align: center; vertical-align:top;}
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; vertical-align:top; }
	</style>
	
</head>
<body>
<center>
	<br>
	<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">	
	<div id="info_group"></div>
		
	</fieldset>
</center>
<br>
<div style="padding-left: 0px; float:left">
	<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
    <form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>Size</td><td> : </td>
				<td>
                    <input type="text" name="size_c_n" id="size_c" size="6"/>
				</td>
			</tr>
            <tr>
				<td>Type</td>
				<td> : </td>
				<td>
                    <input type="text" name="type_c_n" id="type_c"  size="6"/>
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td> : </td>
				<td>
					<input type="text" name="status_c_n" id="status_c"  size="6"/>
				</td>
			</tr>
			<tr>
				<td>Height</td>
				<td> : </td>
				<td>
					<input type="text" name="hg_cn" id="hg_cn"  size="6"/>
				</td>
			</tr>
				<tr>
				<td>ID Book</td>
				<td> : </td>
				<td>
					<input type="text" name="id_bk" id="id_bk"  size="6"/>
				</td>
			</tr>
			<tr>
				<td>Hz</td>
				<td> : </td>
				<td>
					<input type="text" name="hz_c" id="hz_c"  size="6"/>
				</td>
			</tr>
			<tr>
				<td>Tier</td>
				<td> : </td>
				<td>
					<input type="text" name="tier_c" id="tier_c"  size="6"/>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding-left:0px;" align="left">
                    <input type="hidden" id="block_name" value="<?=$block_id?>" >
					<input type="button" value="Alocated" onclick="cek_alokasi()"> &nbsp
					<!--<input type="submit" value=" Save Alocation " id="db_link<?=$id_bg?>" onSubmit="konfirmasi()">-->
					<input type="button" value=" Save Alocation " onclick="oncl()">
				</td>
			</tr>
		</table>
	</form>
	

	<form enctype="multipart/form-data" action="<?=HOME?>planning.yard_allocation_import.ajax/load_bagan?id="<?=yard_id?>"&id_vs="<?=$no_ukk?>" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Dealocation :
				</td>
			</tr>
			<tr>
				<td style="padding-left: 0px;" align="left">
					<input type="submit" id="destack" value="Dealocated" >
					
				</td>
			</tr>
		</table>
	</form>
    </fieldset>

    <br>
    <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 3px">
        <table align="center">
                <tr align="center"><td class="grid-header">Colour Code</td><td class="grid-header">Size</td><td class="grid-header">Type</td></tr>
                <tr align="center" bgcolor="#ffffff">
					<td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png" width="25px" height="25px">
					</td>
					<td class="grid-cell">20</td><td class="grid-cell">DRY</td>
				</tr>
                <tr align="center" bgcolor="#ffffff">
					<td class="grid-cell">
						<img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png" width="25px" height="25px"></td><td class="grid-cell">40
					</td>
					<td class="grid-cell">DRY</td>
				</tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ungu.png" width="25px" height="25px"></td><td class="grid-cell">40</td><td class="grid-cell">HQ</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png" width="25px" height="25px"></td><td class="grid-cell">45</td><td class="grid-cell">HQ</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png" width="25px" height="25px"></td><td class="grid-cell">20,40,45</td><td class="grid-cell">DG</td></tr>
				<tr align="center" bgcolor="#ffffff">
					<td class="grid-cell">
						<img src="yard/src/css/excite-bike/images/20flt.png" width="25px" height="25px"></td><td class="grid-cell">20,40
					</td>
					<td class="grid-cell">RFR</td>
				</tr>
				<tr align="center" bgcolor="#ffffff">
					<td class="grid-cell">
						<img src="yard/src/css/excite-bike/images/40rfr.png" width="25px" height="25px"></td><td class="grid-cell">20,40
					</td>
					<td class="grid-cell">TNK</td>
				</tr>
				<tr align="center" bgcolor="#ffffff">
					<td class="grid-cell">
						<img src="yard/src/css/excite-bike/images/abu2.png" width="25px" height="25px"></td><td class="grid-cell">20,40
					</td>
					<td class="grid-cell">OT</td>
				</tr>
				<tr align="center" bgcolor="#ffffff">
					<td class="grid-cell">
						<img src="yard/src/css/excite-bike/images/OVD.png" width="25px" height="25px"></td><td class="grid-cell">20,40
					</td>
					<td class="grid-cell">OVD</td>
				</tr>
       </table>
    </fieldset>
	<br>

</div>

<?
?>

<!--
<div style="padding-left: 10px; float:left">
<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
	<legend align="center">Information Alocation</legend>
	<br>
	<br>
	<font color='red'><b><i><label id="ket2" name="ket2" width=20></label></i></b><br></font>
	<br>
	<br>
</fieldset>
-->
<br>
<div style="padding-left: 10px; padding-top: 0px;float:left">

</div>
<div style="padding-left:10px; float:left;">
	<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; -webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
	<div id="info"></div>
</fieldset>
<br>
<table border="0">
    <div id="lp<?=$id_bg?>"></div>
</table>
</div>
<br />
<div style="padding-left:10px; float:left;">
<table border="0">
    <tr height="20"><td></td></tr>
</table>
</div>

<div style="padding-left:0px; float:left;">
<div style="margin-top:0px;border:1px solid black;width:860;height:800;overflow-y:scroll;overflow-x:scroll;">
<br>
<br>
<p style="width:100%;">
<div id="load_layout" ALIGN="center" width="100%"></div>
</p>
</div>
</div>
<div style="padding-left:10px; float:left;">
<table border="0">
    <tr height="20"><td></td></tr>
</table>
</div>


    

</center>
</body>