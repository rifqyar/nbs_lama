<style type="text/css">
.bongkar
{
	color: purple;
    background-image: url(images/tes7.png);
	border-color: white;
	border-style: solid;
	border-width: 1px;
}

.muat
{
	color: purple;
    background-image: url(images/tes4.png);
	border-color: white;
	border-style: solid;
	border-width: 1px;
}


</style>

<?php

$db	= getDB();

if(!isset($_POST['block_id']))
{
	?>
        <center>
         <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor" method="post">
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
                           
                              <select name="block_id" id="block_id">
                                 <option value="" selected> -- Pilih --</option>
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
    $query        = "SELECT MAX(a.SLOT_) JML_SLOT, MAX(a.ROW_) JML_ROW, b.NAME, b.ID FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' GROUP BY b.NAME, b.ID";
	//print_r($query);
    $result       = $db->query($query);
    $max_slot_row = $result->fetchRow();
    
    $width = $max_slot_row['JML_SLOT'];
    $height  = $max_slot_row['JML_ROW'];
    $name   = $max_slot_row['NAME'];
    $id_test     = $max_slot_row['ID'];
    
     $L = $width * $height;
	
 //   if($width < 40) $m_div = 15;
 //       else $m_div = 20;
	
    // $s	= round((900 / $width) - (($m_div/100)*(900/$width)));
     
     $s =13;
    
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
	var block_name 	= new Array();
	var block_color = new Array();
        var kategori        = new Array();
        var id_block        = new Array();
	
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
	$('#load_layout').load("<?=HOME?>maintenance.yard_editor/load_layout?id=<?=$yard_id?> #load_layout");   
});

function konfirmasi() {	
	question = confirm("data akan disimpan, apakah anda sudah yakin?")
	if (question != "0")	return true;
	else			return false;
}
	
</script>

</head>
<body>
<center>
         <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor" method="post">
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
                                 <option value="" selected> -- Pilih --</option>
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
        <form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
              
			<tr height="30">
				<td>Ketegori</td><td> : </td><td>
                                    <select name="kategori" id="kategori">
                                 <option value="" selected> -- Pilih -- </option>
                                 <option value="b"> Bongkar </option>
                                 <option value="m"> Muat </option>
                             </select> 
				</td>
			</tr>
			<tr height="30">
				<td colspan="3" style="padding-left:0px;" align="left">
                     
					<input type="submit" id="stack" value="Alokasikan" > &nbsp 
					<input type="hidden" id="id_blok" value="<?=$id_blok?>">
					<input type="hidden" id="yard_id_nama" value="<?=$row['yard_id']?>">
                   <input type="submit" value=" Save Block " id="db_link" onSubmit="konfirmasi()">
                                       
				</td>
			</tr>
		</table>
	</form>

	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr height="30">
				<td>
					Dealokasikan untuk kategori container tersebut :
				</td>
			</tr>
			<tr height="30">
				<td style="padding-left: 0px;" align="left">
					<input type="submit" id="destack" value="Dealokasikan" >
				</td>
			</tr>
		</table>
	</form>
    </fieldset>
   
</div>
<?
	$queryx        = "SELECT a.INDEX_CELL, a.STATUS_BM FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' order by INDEX_CELL ASC";
	//print_r($queryx);
    $resultx       = $db->query($queryx);
    $cellx = $resultx->getall();
	//echo $cellx[0]['SIZE_PLAN_ALLO'];
?>


<div style="padding-left: 20px; float:left">
<div style="margin-top:0px;border:1px solid black;width:580;height:300;overflow-y:scroll;overflow-x:scroll;">
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
						if($cellx[$i-1]['STATUS_BM'] == 'Bongkar')
						{
							$c="bongkar";
							
						}
						else 
							$c="muat";
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
          var kategori     = $("#kategori").val();
		  
		//  alert(kategori);
                    
		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			//console.log("--"+cell[i]+"--");
			cell[array_s[i]].stack = 1;
              if (kategori == 'b') {
				//alert(kategori);
				$("#selectable li").eq(array_s[i]).attr( "class", "bongkar");
              } else {
               $("#selectable li").eq(array_s[i]).attr( "class", "muat");
		  }}
              
                  var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(block_name[i] == name)
				p = 1;
		}

		if(p == 0)
		{
                        block_name[count_block]   = name;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
		}
                  
                  
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
		//build width and height
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
                var kategori     = $("#kategori").val();

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
			block_str += "<block><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_editor.ajax/yard_save";
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
		//build width and height
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
                var kategori     = $("#kategori").val();
             //  alert(id_blok);
                var id_block     = $("#id_blok").val(); 
				var yard_id     = $("#yard_id_nama").val();
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
			block_str += "<block><id_block>"+id_block+"</id_block><kategori>"+kategori+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_editor.ajax/yard_dblink";
		$.post( url, { xml_: xml_str},
			      function(data) 
				  {
					//alert(yard_id);
					alert(data);
		      		console.log(data);
			      }
			    );
				
                $('#load_layout').load("<?=HOME?>maintenance.yard_editor/load_layout?id=41 #load_layout");   
	});
	
</script>
         <!-- setting tooltip -->
<!--      
       
    
    </body>

</html>
