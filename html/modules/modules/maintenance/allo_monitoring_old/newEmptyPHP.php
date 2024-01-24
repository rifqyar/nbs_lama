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
                           <td> Pilih Lapangan </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                  <option value="" selected> -- Pilih --</option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA";
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
                           <td> Pilih Blok </td>
                           <td> : </td>
                           <td>
                           
                             <select name="block_id">
                                 <option value="" selected> -- Pilih --</option>
                                <?php 
                                    $query_get_block     = "SELECT * FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '121'";
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
    $query        = "SELECT MAX(a.SLOT_) JML_SLOT, MAX(a.ROW_) JML_ROW, b.NAME FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' GROUP BY b.NAME";
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    
    $height = $max_slot_row['JML_SLOT'];
    $width  = $max_slot_row['JML_ROW'];
    $name     = $max_slot_row['NAME'];
    
     $L = $width * $height;
	
 //   if($width < 40) $m_div = 15;
 //       else $m_div = 20;
	
    // $s	= round((900 / $width) - (($m_div/100)*(900/$width)));
     
     $s = 25;
    
//echo "-----$yard_id---------";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
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
	var size                = new Array();
	var type                = new Array();
	
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
	</script>
        
<script type='text/javascript'>
$(document).ready(function() 
{
	$('#load_layout').load("<?=HOME?>maintenance.yard_allocation/load_layout?id=<?=$yard_id?> #load_layout");   
});
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
                           <td> Pilih Lapangan </td>
                           <td> : </td>
                           <td>
                             <select name="yard_id" id="yard_id">
                                 <option value="<?php echo $row['yard_id']?>" selected><?php echo $nama_ya?></option>
                                <?php 
                                    $query_get_yard     = "SELECT * FROM YD_YARD_AREA ";
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
                           <td> Pilih Blok </td>
                           <td> : </td>
                           <td>
                             <select name="block_id">
                                 <option value="<?php echo $row['id_blok']?>" selected><?php echo $name?></option>
                                <?php 
                                    $query_get_block     = "SELECT * FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '121'";
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
        <form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>Ukuran Container :
                                    <select name="size" id="size">
                                 <option value="" selected> -- Pilih -- </option>
                                 <option value="20"> 20" </option>
                                 <option value="40"> 40" </option>
                                  <option value="45"> 45" </option>
                             </select> 
				</td>
			</tr>
                        <tr>
				<td>Type Container : 
                                    <select name="type" id="type">
                                 <option value="" selected> -- Pilih -- </option>
                                 <option value="DRY"> DRY </option>
                                 <option value="HQ"> HQ </option>
                                  <option value="DG"> DG </option>
                             </select> 
               
				</td>
			</tr>
			<tr>
				<td>Pilih kategori container yang akan dialokasi : <br>
                                   <div id="x" class="drag blue">
<!--                <input data-tooltip="sticky1" type='text' name='awal' id='awal' style="width:40px;" size='5'> ton s/d  <input data-tooltip="sticky1"type='text' name='akhir' id='akhir' style="width:40px;" size='5'> ton-->
                                   <select data-tooltip="sticky1" name="kategori" id="kategori">
                                 <option value="" selected> -- Pilih -- </option>
                                 <option value="L2"> L2 </option>
                                 <option value="L1"> L1 </option>
                                  <option value="M"> M </option>
                                 <option value="H"> H </option>
                                 <option value="XH"> XH </option>
                             </select> 
                                   </div>		</td>
			</tr>
			<tr>
				<td style="padding-left:0px;" align="left">
					<input type="submit" id="stack" value="Alokasikan" >
                                        
				</td>
			</tr>
		</table>
	</form>
          
	<br />
	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Dealokasikan untuk kategori container tersebut :
				</td>
			</tr>
			<tr>
				<td style="padding-left: 0px;" align="left">
					<input type="submit" id="destack" value="Dealokasikan" >
				</td>
			</tr>
		</table>
	</form>
    </fieldset>
       <div style="clear:both"></div>
<div style="padding-left: 0px; float:left">
            <form id="build_file" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation/index" method="post">
                 <input type="submit" value=" Generate " id="save">
             </form>
</div>
    <br>
    <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 10px">
        <table align="center">
                <tr align="center"><td class="grid-header">Kode Warna</td><td class="grid-header">Size</td><td class="grid-header">Type</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png" width="25px" height="25px"></td><td class="grid-cell">20</td><td class="grid-cell">DRY</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png" width="25px" height="25px"></td><td class="grid-cell">40</td><td class="grid-cell">DRY</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ungu.png" width="25px" height="25px"></td><td class="grid-cell">40</td><td class="grid-cell">HQ</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png" width="25px" height="25px"></td><td class="grid-cell">45</td><td class="grid-cell">DRY</td></tr>
                <tr align="center" bgcolor="#ffffff"><td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png" width="25px" height="25px"></td><td class="grid-cell">20,40,45</td><td class="grid-cell">DG</td></tr>
       </table>
    </fieldset>
</div>

<div style="padding-left: 20px; float:left">
<div style="margin-top:0px;border:1px solid black;width:580;height:440;overflow-y:scroll;overflow-x:scroll;">
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
							<li class="ui-state-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>> &nbsp </li>
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
<br>
<div style="padding-left:10px; float:left;">
<div style="margin-top:0px;border:1px solid black;width:900;height:500;overflow-y:scroll;overflow-x:scroll;">
<p style="width:100%;">
<div id="load_layout"></div>
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
	function matrix_translate(index) {
		var r;
		var s;
		
		s = index % slot;
		var temp = index - s;
		r = (temp / slot)+1;

		var matrix = ""+s+"-"+r;
		return matrix;
	}

	$("#stack").click(function(event) {
		  event.preventDefault();
		  //alert($("#result").html());
		  var selected  = $("#select-result").html();
		  var array_s   = selected.split(",");
                  var size_     = $("#size").val();
                  var type_     = $("#type").val();
		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			//console.log("--"+cell[i]+"--");
			cell[array_s[i]].stack = 1;
                        if ((size_ == '40') && (type_ == 'DRY')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-orange-default");
                        } else if ((size_ == '40') && (type_ == 'HQ')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-ungu-default");
                        } else if ((size_ == '40') && (type_ == 'DG')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
                        } else if ((size_ == '45') && (type_ == 'DRY')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-hijau-default");
                        } else if ((size_ == '45') && (type_ == 'HQ')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-hijau-default");
                        } else if ((size_ == '45') && (type_ == 'DG')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
                        } else if ((size_ == '20') && (type_ == 'DRY')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
                        } else if ((size_ == '20') && (type_ == 'HQ')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
                        } else if ((size_ == '20') && (type_ == 'DG')){
                            $("#selectable li").eq(array_s[i]).attr( "class", "ui-merah-default");
		  }}
              
		//console.log("++"+selected+"++");
		var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(size[i] == size_)
				p = 1;
		}

		if(p == 0)
		{
			size[count_block] = size_;
			type[count_block] = type_;
			count_block++;
		}
                
                for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].size = size_;
		}
                  
                  $('#load_layout').load("<?=HOME?>maintenance.yard_allocation/load_layout?id=<?=$yard_id?> #load_layout");   
		});
	
	$("#destack").click(function(event) {
		  event.preventDefault();
		  //alert($("#result").html());
		  var selected = $("#select-result").html();
		  var array_s  = selected.split(",");

		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			console.log("--"+array_s[i]+"--");
			cell[array_s[i]].stack = 0;
			$("#selectable li").eq(array_s[i]).attr( "class", "ui-state-default");
		  }
                  
                  //console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].size = "";
		}
		});
//
//	$("#reset").click(function(event) {
//		  event.preventDefault();
//		  //alert($("#result").html());
//		  	for (var i = 0; i < total; i++)
//			{
//				cell[i].stack = 0;
//			  	cell[i].block = "";
//			}
//			$("#selectable li").attr( "style", "  border: 1px solid #ffffff; " );
//			$("#selectable li").attr( "title", "" );
//			$("#selectable li").attr( "class", "ui-state-default");
//
//		});
//	
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
			//console.log("--"+cell[array_s[i]].block+"--");
			//console.log("--"+cell[array_s[i]].stack+"--");
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
                
                var size_     = $("#size").val();
                var type_     = $("#type").val();

		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block; j++)
		{
			index_block[j] = new Array();
			for (var i = 0; i < total; i++)
			{
				if(cell[i].block == size_[j])
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
			block_str += "<block><size>"+size_[j]+"</size><type>"+type_[j]+"</type><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_allocation.ajax/yard_save";
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

	$("#db_link").click(function(event) {
		event.preventDefault();
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
			block_str += "<block><size>"+size_[j]+"</size><type>"+type_[j]+"</type><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><block>"block_str"</block>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_allocation.ajax/yard_dblink";
		
		$.post( url, { xml_: xml_str},
			      function(data) {
					alert(data);
		      		console.log(data);
			      }
			    );
	});
	
</script>
         <!-- setting tooltip -->
<!--      
                                
-->    <div id="mystickytooltip" class="stickytooltip">

    <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="40%">
			<tr><th colspan='4' class="grid-header" width='20' align="center"><b><font size="1px">Container 20"</font></th><th colspan='4' class="grid-header" width='20' align="center"><font size="1px"><b>Container 40"</b></font></th></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L2</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">2500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">4900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L2</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>3500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>9900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L1</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">5000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">10900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L1</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>10000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>14900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">M</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">11000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">15900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>M</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>15000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>19900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">H</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">16000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">20900</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>H</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>20000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>24900</td></tr>
			<tr><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">XH</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">21000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">30000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>XH</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>25000</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>30000</td></tr>
    </table>
    <div class="stickystatus"></div>
    </div>
    <!-- end setting tooltip -->
    
    </body>

</html>
