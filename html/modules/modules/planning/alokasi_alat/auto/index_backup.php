<style type="text/css">
.dd
{
	color: purple;
    background-color: #d8da3d; 
	border-color: white;
	border-style: solid;
	border-width: 1px;
}
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}

</style>

<div class="content">
	<h2> <img src="<?=HOME?>images/allo_icon.png" height="7%" width="7%" style="vertical-align:middle"> <font color="#81cefa">Yard </font>
	<font size="3px" color="#606263">Allocation Plan Export</font></h2></p>


<?php

$db	= getDB();

if(!isset($_POST['block_id']))
{
	?>

        <center>
         <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation" method="post">
            	<table style="font-size: 12px; font-weight: bold;">
                   <tr height="10"><td colspan="3"> &nbsp </td></tr>
                       <tr>
                           <td> Yard </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                  <option value="" selected> -- choose --</option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_yard	= $db->query($query_get_yard);
                                    $row_yard		= $result_yard->getAll();
                                    foreach($row_yard as $row)
                                    {
                                ?>
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAMA_YARD'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                           </td>
                       </tr>
                         <tr>
                           <td> Block </td>
                           <td> : </td>
                           <td>
                           
                              <select name="block_id">
                                 <option value="" selected> -- choose --</option>
                                <?php 
                                    $query_get_block     = "SELECT a.* FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";
                                    $result_block	= $db->query($query_get_block);
                                    $row_block		= $result_block->getAll();
                                    foreach($row_block as $row)
                                    {
                                ?>
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAME'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                       </tr>
                        <tr>
                           <td colspan="3">
                                <input type="submit" value=" Go "> </input>
                           </td>
                       </tr>
                </table>
            
            </form>
        </fieldset>
    
        
		</center>
	<?php
}
else
{
    $yard_id     = $_POST['yard_id'];
    
    $query_        = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
    $result_       = $db->query($query_);
    $yard          = $result_->fetchRow();
    $nama_ya       = $yard['NAMA_YARD'];
    
    $id_blok     = $_POST['block_id'];
    $query        = "SELECT MAX(a.SLOT_) JML_SLOT, MAX(a.ROW_) JML_ROW, b.NAME,b.POSISI, b.ID FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' AND b.ID_YARD_AREA='$yard_id' GROUP BY b.NAME, b.ID,b.POSISI";
	//print_r($query);
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    $pos=$max_slot_row['POSISI'];
	if($pos=='vertical')
	{
		$width = $max_slot_row['JML_ROW'];
		$height  = $max_slot_row['JML_SLOT'];
	}
	else
	{
		$width = $max_slot_row['JML_SLOT'];
		$height  = $max_slot_row['JML_ROW'];
	}
	
    
    $name   = $max_slot_row['NAME'];
    $id_test     = $max_slot_row['ID'];
    
     $L = $width * $height;
	
 //   if($width < 40) $m_div = 15;
 //       else $m_div = 20;
	
    // $s	= round((900 / $width) - (($m_div/100)*(900/$width)));
     
     $s = 13;
    
//echo "-----$yard_id---------";
?>

<head>

	<link rel="stylesheet" href="./yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="./yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="./yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="./yard/src/css/main.css">
        
        <script type="text/javascript" src="tooltip/stickytooltip.js"></script>
        <link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
        
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; text-align: center; vertical-align:top;}
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; vertical-align:top; }
	
	
	</style>
	<script>
	var cell  		= new Array();
	var block_name 	= new Array();
	var block_color = new Array();
    var size 	= new Array();
	var type        = new Array();
	var tier        = new Array();
    var vessel        = new Array();
    var kategori        = new Array();
    var id_block        = new Array();
	var block_jml_cont;
	var size_jml_cont;
	var id_book_c;
	block_jml_cont='';
	var count_block = 0;
	var slot  = <?php echo $width;?>;
	var row	  = <?php echo $height;?>;

	var total = row*slot; 
	
	$(function() {
		

		for (var i = 0; i < total; i++)
		{
			cell[i] = 0;
			cell[i] = new Object();
		}
		
		$( "#selectable" ).selectable({
			selected: function() {
				var result = $( "#select-result" ).empty();
				$( ".ui-selected", this ).each(function() {
					var id = $("#selectable li").index(this);
					result.append( id+"," );
					//$("#selectable li").eq(id).attr( "class", "ui-stacking-default");
				});
			}
		});

	});

$(document).ready(function() 
{
        $( "#vessel" ).autocomplete({
		minLength: 3,
		source: "maintenance.yard_allocation.auto/parameter",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NAMA);
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NAMA);
                        $( "#voyage" ).val( ui.item.VOYAGE);
                        $( "#id_vessel" ).val( ui.item.ID);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NAMA + " VOY " + item.VOYAGE + "</a>")
			.appendTo( ul );
	};  
	$( "#tuj_pel" ).autocomplete({
		minLength: 3,
		source: "maintenance.yard_allocation.auto/param_tuj",
		focus: function( event, ui ) {
			$( "#tuj_pel" ).val( ui.item.NAMA);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#tuj_pel" ).val( ui.item.PELABUHAN);
            $( "#id_tuj_pel" ).val( ui.item.ID_PEL);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.PELABUHAN + " [ " + item.ID_PEL + "]<br>"+item.NAMA_NEG+" [ "+item.ID_NEGARA+"]</a>")
			.appendTo( ul );
	};
    $('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#load_layout').load("<?=HOME?>maintenance.yard_allocation/load_layout?id=<?=$yard_id?> #load_layout");   
});

function konfirmasi() 
{	
	question = confirm("data akan disimpan, apakah anda sudah yakin?")
	if (question != "0")	return true;
	else			return false;
}


function cek_kategori_booking() 
{	
	var id_tuj_pels;
	var id_vs;
	var url;
	url="<?=HOME?>maintenance.yard_allocation.auto/cek_availibility";
	id_vs = document.getElementById('id_vessel').value;
	id_tuj_pels = document.getElementById('id_tuj_pel').value;

	
	var size_cont = $("#size_c").val();
	var type_cont = $("#type_c").val();
	var status_cont=$("#status_c").val();
	
	$.post(url,{ID_VS : id_vs,SIZE: size_cont, TYPE: type_cont, STATUS: status_cont, TUJ:id_tuj_pels},function(data) 
				  {
		      		console.log(data);
					var row_data = data;
					var explode = row_data.split(',');
					var ketx = explode[0];
					blok_jml_cont = explode[1];
					size_jml_cont = explode[2];
					id_book_c = explode[3];
					
				    $('#ket').html(ketx);
			      });	
	
}

</script>

</head>
<body>

<center>
         <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation" method="post">
            	<table style="font-size: 12px; font-weight: bold;">
                        <tr height="10"><td colspan="3"> &nbsp </td></tr>
                        
                       <tr>
                           <td> Yard </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                 <option value="<?php echo $row['yard_id']?>" selected><?php echo $nama_ya?></option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_yard	= $db->query($query_get_yard);
                                    $row_yard		= $result_yard->getAll();
                                    
                                    foreach($row_yard as $row)
                                    {
                                ?>
                                
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAMA_YARD'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                       </tr>
                         <tr>
                           <td> Block </td>
                           <td> : </td>
                           <td>
                              <select name="block_id">
                                 <option value="" selected> -- choose --</option>
                                <?php 
                                    $query_get_block     = "SELECT a.* FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";
                                    $result_block	= $db->query($query_get_block);
                                    $row_block		= $result_block->getAll();
                                    foreach($row_block as $row)
                                    {
                                ?>
                                <option value="<?php echo $row['ID']?>"><?php echo $row['NAME'] ?></option>
                                <?php 
                                    }
                                ?>
                             </select>
                           </td>
                       </tr>
                        <tr>
                           <td colspan="3">
                                <input type="submit" value=" Go "> </input>
                           </td>
                       </tr>
                </table>
            
            </form>
        </fieldset>
    
 </center>
<div style="padding-left: 10px; float:left">
	 <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
        <form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
                    <tr>
                            <td>Vessel - voyage</td><td> : </td><td><input type="text" size="15" id="vessel" name="vessel">
                                &nbsp <input type="text" size="5" id="voyage" name="voyage">
                                <input type="hidden" id="id_vessel" name="id_vesseln">
                                 <input type="hidden" id="id_test" name="id_test" value="<?=$id_test;?>">
				</td>
			</tr>
			<tr>
				<td>Size Container</td><td> : </td><td>
                                    <select name="size_c_n" id="size_c">
                                 <option value="" > -- choose -- </option>
                                 <option value="20"> 20" </option>
                                 <option value="40"> 40" </option>
                                  <option value="45"> 45" </option>
                             </select> 
				</td>
			</tr>
            <tr>
				<td>Type Container</td>
				<td> : </td>
				<td>
                    <select name="type_c_n" id="type_c">
					 <option value="" selected> -- choose --</option>
                        <option value="DRY"> DRY </option>
						<option value="FLT"> FLT </option>
						<option value="TNK"> TNK </option>
						<option value="RFR"> RFR </option>
						<option value="OT"> OT </option>
                        <option value="HQ"> HQ </option>
						<option value="OVD"> OVD </option>
                    </select> 
               
				</td>
			</tr>
			<tr>
				<td>Hz</td>
				<td> : </td>
				<td>
                    <select name="hz_c" id="hz_c">
                        <option value="T"> T </option>
						<option value="Y"> Y </option>
                    </select> 
				</td>
			</tr>
			<tr>
				<td>Status Container</td>
				<td> : </td>
				<td>
					<select name="status_c_n" id="status_c" >
					 <option value="" selected> -- choose --</option>
                    <option value="FCL" > FCL </option>
                    <option value="MTY"> MTY </option>
                    </select> 
               
				</td>
			</tr>
			
			<tr>
				<td>POD</td><td> : </td>
				<td><input type="text" size="15" id="tuj_pel" name="tuj_pel_n" onblur="cek_kategori_booking()">&nbsp;
					<input type="text" size="5" id="id_tuj_pel" name="id_tuj_pel_n"  onblur="cek_kategori_booking()">
                    
				</td>
			</tr>
			<tr>
				<td>Activity</td><td> : </td>
				<td><select id="act_ei">
						<option value=''>- choose -</option>
						<option value='Muat'>Export</option>
						<option value='Bongkar'>Import</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Tier</td><td> : </td>
				<td><input type="text" size="15" id="tier" name="tier">
				</td>
			</tr>
			
			<tr>
				<td>Check Availibility <br> Category</td>
				<td> : </td>
				<td><label id="ket" name="ket" width=20></label>               
				</td>
			</tr>
	
			<tr>
				<td colspan="3">Weight Category : <br>
                    <div id="x" class="drag blue">
                    <select data-tooltip="sticky1" name="kategori" id="kategori">
						<option value="" selected> -- choose -- </option>
						<option value="L2"> MTY </option>
                        <option value="L2"> L2 </option>
                        <option value="L1"> L1 </option>
                        <option value="M"> M </option>
                        <option value="H"> H </option>
                        <option value="XH"> XH </option>
                    </select> 
                    </div>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding-left:0px;" align="left">
                    <input type="hidden" id="block_name" value="<?=$block_id?>" >
					<input type="button" value="Alocated" onclick="cek_alokasi()"> &nbsp;
					<input type="submit" value=" Save Alocation " id="db_link" onSubmit="konfirmasi()">
				</td>
			</tr>
		</table>
	</form>

	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation/index" method="post">
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
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
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
	$queryx        = "SELECT INDEX_CELL, SIZE_PLAN_ALLO FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' order by INDEX_CELL ASC";
	//print_r($queryx);
    $resultx       = $db->query($queryx);
    $cellx = $resultx->getall();

?>


<div style="padding-left: 20px; float:left">
<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
	<legend align="center">Information Alocation</legend>
	<br>
	<br>
	<font color='red'><b><i><label id="ket2" name="ket2" width=20></label></i></b><br></font>
	<br>
	<br>
</fieldset>
<br>
<div style="margin-top:0px;border:1px solid black;width:550;height:400;overflow-y:scroll;overflow-x:scroll;">
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<div class="grid" style="float:left">
	<table border="0" width="80%" align="center" >
	<tr align="left" valign="top"><td width='70'></td></tr>
		<tr align="center" valign="top">
			<td align="left" valign="top" style="padding-left: 20px; padding-right: 0px;">
                            <br />
                            <font size=3px><font color="red"><b>BLOK <?php echo $name?></b></font></font>
                            <br /><br />
				<ol id="selectable">
					<?php 
						$j = 1;
						for($i = 1; $i <= $L; $i++)
						{	
							$m = ($width*$j) + 1;
					?>		
					<?
						if($cellx[$i-1]['SIZE_PLAN_ALLO']<>'')
						{
							$c="dd";
							
						}
						else
							$c="ui-state-default";
					?> 
							<li class="<?=$c?>" <?php if (($i%$m) == 0){$j++;	?>style="clear: both;"<?php 
							}?>>
							
							 </li>
					<?php 
						}
					
					?>
				</ol>
			</td>
                </tr>
	</table>
	
</div>
</div>
</div>
<br />
<div style="padding-left:10px; float:left;">
<table border="0">
    <tr height="20"><td></td></tr>
</table>
</div>

<div style="padding-left:10px; float:left;">
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

<?php

}

?>
    
<script>


	function cek_alokasi()
	{
		var selected  = $("#select-result").html();
		var array_s   = selected.split(",");
		var x=(array_s.length-1)*5;
		var jml_c;
		var ket2;
		var size_c_exs=$("#size_c").val();;	
		var url="<?=HOME?>maintenance.yard_allocation.ajax/cek_aloc";
		var blc=$("#id_test").val();
		var max_w="<?=$width?>";
		$.post(url,{BLOK:blc},function(data) 
		{
			if (data!='vertical')
			{
				if ((size_c_exs!=20) && (size_c_exs!=''))
				{
					jml_c=x*2;
				
					if ((array_s[1]-array_s[0])!=1)
					{
						alert('jumlah blok kurang dari jumlah container alokasi');
						ket2='Jumlah blok alokasi container '+(array_s.length-1)+' <br>hanya dapat digunakan untuk '+x+' container 20" <BR>tambahkan '+Math.round((jml_c-x)/5)+' blok lagi dibelakang alokasi sebelumnya untuk alokasi yang sesuai<br>untuk alokasi container '+size_c_exs+'"';
						$('#ket2').html(ket2);
						return false;
						//javascript:onstack();
						//return true;
					}
					else
					{
						javascript:onstack();
						return true;
					}
				}
				else
				{
					javascript:onstack();
					return true;
				}
			}
			else
			{
				//alert(array_s[1]+' '+array_s[0]+' '+max_w);
				if ((size_c_exs!=20) && (size_c_exs!=''))
				{
					jml_c=x*2;
				
					if ((array_s[1]-array_s[0])!=max_w)
					{
						alert('jumlah blok kurang dari jumlah container alokasi');
						ket2='Jumlah cell alokasi container '+(array_s.length-1)+' <br>hanya dapat digunakan untuk '+x+' container 20" <BR>tambahkan '+Math.round((jml_c-x)/5)+' cell lagi dibelakang alokasi sebelumnya untuk alokasi yang sesuai<br>untuk alokasi container '+size_c_exs+'"';
						$('#ket2').html(ket2);
						return false;
						//javascript:onstack();
						//return true;
					}
					else
					{
						javascript:onstack();
						return true;
					}
				}
				else
				{
					javascript:onstack();
					return true;
				}
			}
		});
	}
	
	
	function matrix_translate(index) {
		var r;
		var s;
		
		s = index % slot;
		var temp = index - s;
		r = (temp / slot)+1;

		var matrix = ""+s+"-"+r;
		return matrix;
	}

	//$("#stack").click(function(event) 
	function onstack()
	{
		//event.preventDefault();
		
		var selected  = $("#select-result").html();
		var array_s   = selected.split(",");
        var size_     = $("#size_c").val();
        var type_     = $("#type_c").val();
		var tier_     = $("#tier").val();
        var name 	 = $("#block_name").val();
        var vessel_     = $("#id_vessel").val();
        var id_block_     = $("#id_test").val();
        var kategori     = $("#kategori").val();
                    
		//console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++)
		{
			
			cell[array_s[i]].stack = 1;
                        if ((size_ == '40') && (type_ == 'DRY'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-orange-default");
                        } 
						else if ((size_ == '40') && (type_ == 'HQ'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-ungu-default");
                        } 
						else if ((size_ == '40') && (type_ == 'DG'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
                        } 
						else if ((size_ == '45') && (type_ == 'DRY'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-hijau-default");
                        } 
						else if ((size_ == '45') && (type_ == 'HQ'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-hijau-default");
                        } 
						else if ((size_ == '45') && (type_ == 'DG'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
                        } 
						else if ((size_ == '20') && (type_ == 'DRY'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
                        } 
						else if ((size_ == '20') && (type_ == 'HQ'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
                        } 
						else if ((size_ == '20') && (type_ == 'DG'))
						{
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
						}
						else
							$("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
		}
              
                  var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(block_name[i] == name)
				p = 1;
		}

		if(p == 0)
		{
            block_name[count_block]   = name;
			size[count_block]   = size_;
			type[count_block]   = type_;
			tier[count_block]   = tier_;
            vessel[count_block]   = vessel_;
            id_block[count_block]   = id_block_;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
		}
                  
                  
	//});
	}
	
	$("#destack").click(function(event) {
		  event.preventDefault();
		  var selected = $("#select-result").html();
		  var array_s  = selected.split(",");

		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			console.log("--"+array_s[i]+"--");
			cell[array_s[i]].stack = 0;
			$("#selectable li").eq(array_s[i]).attr( "class", "ui-state-default");
		  }
		});

	$("#set_block").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
        var tier 	 = $("#block_tier").val();
        var kategori 	 = $("#block_kategori").val();
		var posisi 	 = $("#block_posisi").val();
		//console.log("++"+selected+"++");
		var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(block_name[i] == name)
				p = 1;
		}

		if(p == 0)
		{
			block_name[count_block]	 = name;
			block_color[count_block] = color;
            block_tier[count_block]	 = tier;
            block_kategori[count_block] = kategori;
			block_posisi[count_block] = posisi;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #"+color+"; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "  Blok "+name );
            $("#selectable li").eq(array_s[i]).attr( "tier", "  Tier "+tier );
            $("#selectable li").eq(array_s[i]).attr( "kategori", "  Kategori "+kategori );
            $("#selectable li").eq(array_s[i]).attr( "posisi", "  Posisi "+posisi );
		}
	});

	$("#unblock").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
                var tier 	 = $("#block_tier").val();
                var kategori	 = $("#block_kategori").val();
		var posisi 	 = $("#block_posisi").val();
		//console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = "";
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #ffffff; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "" );
                        $("#selectable li").eq(array_s[i]).attr( "tier", "" );
                        $("#selectable li").eq(array_s[i]).attr( "kategori", "" );
                        $("#selectable li").eq(array_s[i]).attr( "posisi", "" );
		}
	});

	$("#save").click(function(event) {
		event.preventDefault();
		//build width and height
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
          var size_     = $("#size_c").val();
                var type_     = $("#type_c").val();
				var tier_     = $("#tier").val();
                 var vessel     = $("#vessel").val();

		//build array of stacking area
		var j = 0;
		var index_stack = new Array();
		for (var i = 0; i < total; i++)
		{
			if(cell[i].stack == 1)
			{
				index_stack[j] = i;
				j++;
			}
		}
		var stack_ 		= index_stack.join(",");
		var stack_str	= "<index>"+stack_+"</index>"; 
		console.log("=="+stack_str+"==");
	
		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block; j++)
		{
			index_block[j] = new Array();
			for (var i = 0; i < total; i++)
			{
				if(cell[i].block == block_name[j])
				{
					index_block[j][p] = i;
					p++;
				}
			}
			p = 0;
		}
		
		var block_str = "";
		for (var j = 0; j < count_block; j++)
		{
			block_str += "<block><size>"+size[j]+"</size><type>"+type[j]+"</type><tier>"+tier[j]+"</tier><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_builder.ajax/yard_save";
		$.post( url, { xml_: xml_str},
			      function(data) {
					  console.log(data);
		      		if(data == "success")
		      		{
		      			$("#file_at").html("<a href='./saved_file.xml'>Save As File ini</a>");
		      		}
			      }
			    );
	});

	$("#db_link").click(function(event) 
	{
		event.preventDefault();
		
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
        var size_     = $("#size_c").val();
		var sts_c     = $("#status_c").val();
        var type_     = $("#type_c").val();
		var tier_     = $("#tier").val();
        var vessel     = $("#id_vessel").val();
        var id_block     = $("#id_test").val();
        var kategori     = $("#kategori").val();
		var e_i = $('#act_ei').val();
		var hz=$('#hz_c').val();
		var id_vs= document.getElementById('id_vessel').value;
		var id_tuj_pels= document.getElementById("id_tuj_pel").value;
                //alert(id_block);
                //var size_     = $("#size").val();
                //var type_     = $("#type").val();

		//build array of stacking area
		var j = 0;
		var index_stack = new Array();
		for (var i = 0; i < total; i++)
		{
			if(cell[i].stack == 1)
			{
				index_stack[j] = i;
				j++;
			}
		}
		var stack_ 	= index_stack.join(",");
		var stack_str	= "<index>"+stack_+"</index>"; 
		console.log("=="+stack_str+"==");
	
		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block; j++)
		{
			index_block[j] = new Array();
			for (var i = 0; i < total; i++)
			{
				if(cell[i].block == block_name[j])
				{
					index_block[j][p] = i;
					p++;
				}
			}
			p = 0;
		}
		
		var block_str = "";
		for (var j = 0; j < count_block; j++)
		{
			block_str += "<block><id_block>"+id_block+"</id_block><kategori>"+kategori+"</kategori><vessel>"+vessel+"</vessel><size>"+size+"</size><type>"+type+"</type><tier>"+tier+"</tier><cell>"+index_block[j].join(",")+"</cell></block>";
		}
		var y_id='<?=$yard_id?>';
		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);	
		//$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=$HOME?>images/loadingbox.gif /><br><br>' });
		var url = "<?=HOME?>maintenance.yard_allocation.ajax/yard_dblink";
		$.post( url, { xml_: xml_str, ID_TUJ : id_tuj_pels,ID_VS: id_vs, STATUS_C: sts_c, ID_BOOK: id_book_c,ACT:e_i,ID_YARD:y_id, HZ:hz},
			      function(data) 
				  {
					alert(data);
		      		console.log(data);
					$('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#load_layout').load("<?=HOME?>maintenance.yard_allocation/load_layout?id=<?=$yard_id?> #load_layout");   
			      }
			    );
                
	});
	
</script>
			
	        <!-- setting tooltip -->
<!--      
                                
-->    <div id="mystickytooltip" class="stickytooltip">

    <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="40%">
			<tr><th colspan='4' class="grid-header" width='20' align="center"><b><font size="1px">Container 20"</font></th><th colspan='4' class="grid-header" width='20' align="center"><font size="1px"><b>Container 40"</b></font></th></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L2</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">2500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">9900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L2</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>3500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>9900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L1</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">10000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">14900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L1</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>10000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>14900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">M</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">15000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">19900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>M</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>15000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>19900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">H</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">20000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">24900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>H</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>20000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>24900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">XH</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">25000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">35000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>XH</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>25000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>35000</td></tr>
    </table>
    <div class="stickystatus"></div>
    </div>
    <!-- end setting tooltip -->

		
    </body>
</div>