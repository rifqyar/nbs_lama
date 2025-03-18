<?php
	$id_bg=$_GET['id_bg'];
	$db=getDb();
	$yard_id     = $_GET['id'];
    $no_ukk     = $_GET['no_ukk'];
	
	$size  = $_GET['size'];
	$type  = $_GET['type'];
	$hg	   = $_GET['hg'];
	$hz    = $_GET['hz'];
	$kate  = $_GET['kategori'];
	
	$query3        = "SELECT a.SIZE_CONT FROM tb_booking_cont_area a
                       WHERE a.ID_VS = '$no_ukk' and E_I = 'I'
                                         and a.KATEGORI_GROUP='$kate' and a.SIZE_CONT = '20'";
    $result3       = $db->query($query3);
    $yard3         = $result3->fetchRow();
    $size20        = $yard3['SIZE'];
	
	$query4        = "SELECT a.SIZE_CONT FROM tb_booking_cont_area a
                       WHERE a.ID_VS = '$no_ukk' and E_I = 'I'
                                         and a.KATEGORI_GROUP='$kate' and a.SIZE_CONT = '40'";
    $result4       = $db->query($query4);
    $yard4         = $result4->fetchRow();
    $size40        = $yard4['SIZE'];
	
	$query5        = "SELECT a.SIZE_CONT FROM tb_booking_cont_area a
                       WHERE a.ID_VS = '$no_ukk' and E_I = 'I'
                                         and a.KATEGORI_GROUP='$kate' and a.SIZE_CONT = '45'";
    $result5       = $db->query($query5);
    $yard5         = $result5->fetchRow();
    $size45        = $yard5['SIZE'];
	
	$query2        = "SELECT a.ID_BOOK FROM tb_booking_cont_area a
					   WHERE a.ID_VS = '$no_ukk' and E_I = 'I'
										 and a.SIZE_CONT='$size'
										 and a.TYPE_CONT = '$type'
										 and a.HZ = '$hz'
										 and a.HEIGHT = '$hg'";
    $result2       = $db->query($query2);
    $yard2          = $result2->fetchRow();
    $id_book       = $yard2['ID_BOOK'];
	
    
    $query_        = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
    $result_       = $db->query($query_);
    $yard          = $result_->fetchRow();
    $nama_ya       = $yard['NAMA_YARD'];
    
    $id_blok     = $_GET['id_block'];
    $query        = "SELECT MAX(a.SLOT_) JML_SLOT, MAX(a.ROW_) JML_ROW, b.NAME,b.POSISI, b.ID FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' AND b.ID_YARD_AREA='$yard_id' GROUP BY b.NAME, b.ID,b.POSISI";
	
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
	//$s = 13;
	 if ($width <= 15){
		$s = 30;
	 } else {
		$s = 20;
	}
	$queryx        = "SELECT INDEX_CELL, SIZE_PLAN_ALLO, a.SLOT_, a.ROW_, SIZE_PLAN_PLC FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND a.ID_BLOCKING_AREA = '$id_blok' order by INDEX_CELL ASC";
	//print_r($queryx);
    $resultx       = $db->query($queryx);
    $cellx = $resultx->getall();
?>
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
				});
			}
		});

	});

</script>
<div style="margin-top:0px;border:1px solid black;width:600;height:400;overflow-y:scroll;overflow-x:scroll;">
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
									$slot_ = $cellx[$i-1]['SLOT_'];
									$rew   = $cellx[$i-1]['ROW_'];
									
									$c="ui-orange-default";
							
						} else {
									//echo $cellx[$i-1]['SIZE_PLAN_PLC'];
									$slot_ = $cellx[$i-1]['SLOT_'];
									$rew   = $cellx[$i-1]['ROW_'];
									
								if ($cellx[$i-1]['SIZE_PLAN_PLC']=='40d'){
								//	echo "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$rew'";
									$querya        = "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$rew'";
											//print_r($querya);
									$resulta       = $db->query($querya);
									$cella 		   = $resulta->fetchRow();
									$tier 		   = $cella['JML'];
									
									//echo "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$rew'";
									//print_r($tier);
									
											if ($tier == 1){
												$c="ui-satu-default";
												//print_r($c);
											} else if ($tier == 2){
												$c="ui-dua-default";
												//print_r($c);
											} else if ($tier == 3){
												$c="ui-tiga-default";
												//print_r($c);
											} else if ($tier == 4){
												$c="ui-empat-default";
												//print_r($c);
											} else if ($tier == 5){
												$c="ui-lima-default";
												//print_r($c);
											} else if ($tier == 6){
												$c="ui-enam-default";
												//print_r($c);
											} else if ($tier == 7){
												$c="ui-tujuh-default";
												//print_r($c);
											} else {
												$c="ui-state-default";
												//print_r($c);
											}
								} else if ($cellx[$i-1]['SIZE_PLAN_PLC']=='40b'){
										$c="ui-plus-default";
								} else if ($cellx[$i-1]['SIZE_PLAN_PLC']==20) {
									$querya        = "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$rew'";
											//print_r($querya);
									$resulta       = $db->query($querya);
									$cella 		   = $resulta->fetchRow();
									$tier 		   = $cella['JML'];
									
									//echo "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_blok' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$rew'";
									//print_r($tier);
									
											if ($tier == 1){
												$c="ui-satu-default";
												//print_r($c);
											} else if ($tier == 2){
												$c="ui-dua-default";
												//print_r($c);
											} else if ($tier == 3){
												$c="ui-tiga-default";
												//print_r($c);
											} else if ($tier == 4){
												$c="ui-empat-default";
												//print_r($c);
											} else if ($tier == 5){
												$c="ui-lima-default";
												//print_r($c);
											} else if ($tier == 6){
												$c="ui-enam-default";
												//print_r($c);
											} else if ($tier == 7){
												$c="ui-tujuh-default";
												//print_r($c);
											} else {
												$c="ui-state-default";
												//print_r($c);
											}
								} else {
										$c="ui-state-default";
								}
					}
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
<script>


	function cek_alokasi()
	{
		var selected  = $("#select-result").html();
		var array_s   = selected.split(",");
		var x=(array_s.length-1)*5;
		var jml_c;
		var ket2;
		var size20  = "<?=$size20;?>";
		var size40  = "<?=$size40;?>";
		var size45  = "<?=$size45;?>";
		var url="<?=HOME?>maintenance.yard_allocation.ajax/cek_aloc";
		var blc=$("#id_test").val();
		var max_w="<?=$width?>";
		$.post(url,{BLOK:blc},function(data) 
		{
			if (data!='vertical')
			{
				if ((size40=='40') || (size45=='45'))
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
	
				if ((size40=='40') || (size45=='45'))
				{
					jml_c=x*2;
				
					if ((array_s[1]-array_s[0])!=max_w)
					{
						alert('jumlah blok kurang dari jumlah container alokasi');
						ket2='Jumlah cell alokasi container '+(array_s.length-1)+' <br>hanya dapat digunakan untuk '+x+' container 20" <BR>tambahkan '+Math.round((jml_c-x)/5)+' cell lagi dibelakang alokasi sebelumnya untuk alokasi yang sesuai<br>untuk alokasi container '+size_c_exs+'"';
						$('#ket2').html(ket2);
						return false;
					}
					else
					{
						
						javascript:onstack();
						return true;
						$('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_block="+blid);   		
						$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block="+blid); 
					}
				}
				else
				{
					javascript:onstack();
					return true;
					$('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_block="+blid);   		
					$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block="+blid); 
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
		var tier_     = $("#tier_c").val();
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
            block_name[count_block]   	= name;
			size[count_block]   		= size_;
			type[count_block]   		= type_;
			tier[count_block]  			= tier_;
            vessel[count_block]   		= vessel_;
            id_block[count_block]   	= id_block_;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
		}
        
/*		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);	
		
		var url = "<?=HOME?>planning.yard_allocation_import.ajax/yard_dblink";
		$.post( url, { xml_: xml_str,ID_VS: id_vs, STATUS_C: sts_c, ID_BOOK: id_bk,ID_YARD:y_id, HZ:hz},
			      function(data) 
				  {
					alert(data);
		      		console.log(data);
					$('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout");   
					$('#info_group').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#info_group').load("<?=HOME?>planning.yard_allocation_import/info_group?id_vs=<?=$no_ukk?> #info_group");   
			      }
			    );
                  */
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

	$("#db_link<?=$id_bg?>").click(function(event) 
	{
		event.preventDefault();
		
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
        var size_     = $("#size_c").val();
		var sts_c     = $("#status_c").val();
        var type_     = $("#type_c").val();
		var tier_     = $("#tier_c").val();
        var vessel     = $("#id_vessel").val();
        var id_block     = "<?=$id_test?>";
        var kategori     = $("#kategori").val();
		var id_bk     = $("#id_bk").val();
		var e_i = 'I';
		var hz=$('#hz_c').val();
		var id_vs= document.getElementById('id_vessel').value;

        
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
		
		var url = "<?=HOME?>planning.yard_allocation_import.ajax/yard_dblink";
		$.post( url, { xml_: xml_str,ID_VS: id_vs, STATUS_C: sts_c, ID_BOOK: id_bk,ID_YARD:y_id, HZ:hz, ID_KAT :kategori},
			      function(data) 
				  {
					alert(data);
		      		console.log(data);
					$('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout");   
					$('#info_group').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#info_group').load("<?=HOME?>planning.yard_allocation_import/info_group?id_vs=<?=$no_ukk?> #info_group");   
			      }
			    );
                
	});
	
	function oncl()
	{
		//event.preventDefault();
		
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
        var size_     = $("#size_c").val();
		var sts_c     = $("#status_c").val();
        var type_     = $("#type_c").val();
		var tier_     = $("#tier_c").val();
        var vessel     = $("#id_vessel").val();
        var id_block     = "<?=$id_test?>";
        var kategori     = $("#kategori").val();
		var id_bk     =$("#id_bk").val();
		var e_i = 'I';
		var hz=$('#hz_c').val();
		var id_vs= "<?=$no_ukk?>";

        
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
		var kate='<?=$kate?>';
		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);	
		
		var url = "<?=HOME?>planning.yard_allocation_import.ajax/yard_dblink";
		$.post( url, { xml_: xml_str,ID_VS: id_vs, STATUS_C: sts_c, ID_BOOK: id_bk,ID_YARD:y_id, HZ:hz, KATE:kate, TIER_HEAD:tier_},
		function(data) 
			  {
					alert(data);
		      		console.log(data);
					$('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
					$('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout");   
					window.location = "<?=HOME?>planning.yard_allocation_import/";
					$("#info_group").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=info_group&no_ukks="+vessel, datatype:"json"}).trigger("reloadGrid");	
					
		    }
		);
                
	}
	
	
</script>