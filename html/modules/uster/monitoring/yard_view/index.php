
    
 <!--   <!DOCTYPE HTML> -->
<html lang="en">
  <head>
  <style>
	
	.boxh
	{
	width:14px;
	height:32px;
	border:1px solid gray;
	}
	
	.boxv
	{
	width:32px;
	height:14px;
	border:1px solid gray;
	}
	
	.title
	{
	position:relative;
	top:800px;
	left:1000px;
	font-size:20px;;
	}
	
	.cari
	{
	position:relative;
	font-size:18px;
	}
	</style>
  </head>
  <body>
  
    
	
	<meta charset="utf-8">
	<link rel="stylesheet" href="yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="yard/src/css/main.css">
        
        
    <!-- <script type="text/javascript" src="tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" /> -->
	<script src="tooltip/jquery.qtip.min.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/jquery.qtip.min.css" />
	
    <script defer="defer">
	 
	//==========================
	// Buat blok tipe Horisontal
	//==========================
	
	// $("body").append(" <div id='"+id+"' class='boxh' ></div>")
	
	
	function boxh(id)
	{
		$("body").append(" <div id='"+id+"' class='boxh tooltip' ></div>");
		// $("body").append(" <div id='numrow"+id+"'  ></div>");
		// $("body").append(" <div id='numslot"+id+"'  ></div>");
	}
	//karena element div-nya harus ter-create dulu, baru bisa 
	function boxhPosition(x,y,id)
	{
		boxh(id);
		document.getElementById(id).style.position  = 'absolute';
		document.getElementById(id).style.top  = y+"px";
		document.getElementById(id).style.left = x+"px";
		
		// document.getElementById("numrow"+id).style.position  = 'absolute';
		// document.getElementById("numrow"+id).style.top  = y+35+"px";
		// document.getElementById("numrow"+id).style.left = x+3+"px";
		
		// document.getElementById("numrow"+id).innerHTML="1";
	}	
	
	
	//========================
	
	
	//==========================
	// Buat blok tipe Vertikal
	//========================
	
	function boxv(id)
	{
		$("body").append(" <div id='"+id+"' class='boxv tooltip' ></div>")
	}
	//karena element div-nya harus ter-create dulu, baru bisa 
	function boxvPosition(x,y,id)
	{
		boxv(id);
		document.getElementById(id).style.position  = 'absolute';
		document.getElementById(id).style.top  = y+"px";
		document.getElementById(id).style.left = x+"px";
	}	
	
	
	//========================

	  
    </script>
	<?php
		
		
		function createBlokH($x,$y,$blok,$slot,$row)
		{
			for($i=0; $i < $slot; $i++ )
			{
				
				
				for($j = 0; $j < $row; $j++)
				{
					$db 		= getDB("storage");


					$query_boxh		= "SELECT COUNT(b.NO_CONTAINER) JUMLAH
											FROM BLOCKING_AREA a, PLACEMENT b, MASTER_CONTAINER c
											WHERE a.ID = b.ID_BLOCKING_AREA
											AND c.NO_CONTAINER = b.NO_CONTAINER
											AND b.SLOT_='$i'+1
											AND b.ROW_ = '$j'+1
											AND a.NAME = '$blok'
											AND c.LOCATION = 'IN_YARD'
												";
												
					$result_boxh 	= $db->query($query_boxh);
					$row_boxh   	= $result_boxh->fetchRow();
					$jml_box		= $row_boxh["JUMLAH"];
					
					echo "<script>boxhPosition(".($x-(14*($j))).",".($y+(32*($i))).",'".$blok."-".($i+1)."-".($j+1)."')</script>";
					
					if($i == $slot-1)
					{
					echo "<script>$(\"body\").append(\" <div id='numrow".$blok.($i+1).($j+1)."'  ></div>\");</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.position  = 'absolute';</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.top  = ".($y+(32*($i)))."+35+\"px\";</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.left = ".($x-(14*($j)))."+3+\"px\";</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").innerHTML=\"".($j+1)."\";</script>";
					}
						
					if($jml_box == 0)
					{
						// echo "WHITE";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#FFFFFF';</script>";
						// exit();
					}
					else if($jml_box == 1)
					{
						// echo "YELLOW";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#CCCCFF';</script>";
						// exit();
					}
					else if($jml_box == 2)
					{
						// echo "ORANGE";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#9999FF';</script>";
						// exit();
					}
					else if($jml_box == 3)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#6666FF';</script>";
						// exit();
					}
					else if($jml_box == 4)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#3333FF';</script>";
						// exit();
					}
					else if($jml_box == 5)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#0000FF';</script>";
						// exit();
					}
					else if($jml_box == 6)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#0000CC';</script>";
						// exit();
					}
					else if($jml_box >= 7)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#000099';</script>";

						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').innerHTML  = '<div style=\"color:white;text-align:center;\">F</div>';</script>";
						// exit();
					}	
				}
				
				echo "<script>$(\"body\").append(\" <div id='numslot".$blok.($i+1)."'  ></div>\");</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.position  = 'absolute';</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.top  = ".($y+(32*($i+1)))."-20+\"px\";</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.left = ".($x-(14*($j+1)))."+17+\"px\";</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").innerHTML=\"".($i+1)."\";</script>";
			}
			
		}
		
		function createBlokV($x,$y,$blok,$slot,$row)
		{
			for($i=0; $i < $slot; $i++ )
			{
				for($j = 0; $j < $row; $j++)
				{
					$db 		= getDB("storage");


					$query_boxv		= "SELECT COUNT(b.NO_CONTAINER) JUMLAH
											FROM BLOCKING_AREA a, PLACEMENT b, MASTER_CONTAINER c
											WHERE a.ID = b.ID_BLOCKING_AREA
											AND c.NO_CONTAINER = b.NO_CONTAINER
											AND b.SLOT_='$i'+1
											AND b.ROW_ = '$j'+1
											AND a.NAME = '$blok'
											AND c.LOCATION = 'IN_YARD'
												";
												
					$result_boxv 	= $db->query($query_boxv);
					$row_boxv   	= $result_boxv->fetchRow();
					$jml_box		= $row_boxv["JUMLAH"];
					
					
					echo "<script>boxvPosition(".($x-(32*($i))).",".($y+(14*($j))).",'".$blok."-".($i+1)."-".($j+1)."')</script>";
						
					if($i == $slot-1)
					{
					echo "<script>$(\"body\").append(\" <div id='numrow".$blok.($i+1).($j+1)."'  ></div>\");</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.position  = 'absolute';</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.top  = ".($y+(14*($j)))."+3+\"px\";</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").style.left = ".($x-(32*($i)))."-15+\"px\";</script>";
					echo "<script>document.getElementById(\"numrow".$blok.($i+1).($j+1)."\").innerHTML=\"".($j+1)."\";</script>";	
					}
					
					if($jml_box == 0)
					{
						// echo "WHITE";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#FFFFFF';</script>";
						// exit();
					}
					else if($jml_box == 1)
					{
						// echo "YELLOW";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#CCCCFF';</script>";
						// exit();
					}
					else if($jml_box == 2)
					{
						// echo "ORANGE";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#9999FF';</script>";
						// exit();
					}
					else if($jml_box == 3)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#6666FF';</script>";
						// exit();
					}
					else if($jml_box == 4)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#3333FF';</script>";
						// exit();
					}
					else if($jml_box == 5)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#0000FF';</script>";
						// exit();
					}
					else if($jml_box == 6)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#0000CC';</script>";
						// exit();
					}
					else if($jml_box >= 7)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.backgroundColor  = '#000099';</script>";
						echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').innerHTML  = '<div style=\"color:white;text-align:center;\">F</div>';</script>";
						// echo "<script>document.getElementById('".$blok."-".($i+1)."-".($j+1)."').style.borderColor  = '#FF0000';</script>";
						// exit();
					}
				}
				
				echo "<script>$(\"body\").append(\" <div id='numslot".$blok.($i+1)."'  ></div>\");</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.position  = 'absolute';</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.top  = ".($y+(14*($j+1)))."-9+\"px\";</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").style.left = ".($x-(32*($i+1)))."+47+\"px\";</script>";
				echo "<script>document.getElementById(\"numslot".$blok.($i+1)."\").innerHTML=\"".($i+1)."\";</script>";
			}
			
		}
		
		$db 		= getDB("storage");
		
		$query_gen_block	= "SELECT NAME, SLOT_, ROW_, X_BLOK, Y_BLOK, X_LABEL, Y_LABEL, TIPE_BLOK
									FROM BLOCKING_AREA
									WHERE AKTIF='Y'";
												
		$result_gen_block 	= $db->query($query_gen_block);
		$data_gen_block   	= $result_gen_block->getAll();
		
		foreach($data_gen_block as $block)
		{
			
			
			if($block['TIPE_BLOK']=='H')
			{
				createBlokH($block['X_BLOK'],$block['Y_BLOK'],$block['NAME'],$block['SLOT_'],$block['ROW_']);  echo "<div style=\"position:absolute;left:".$block['X_LABEL']."px;top:".$block['Y_LABEL']."px;z-index:-1;font-size:15\"><b>".$block['NAME']."</b></div>";
			}
			else
			{
				createBlokV($block['X_BLOK'],$block['Y_BLOK'],$block['NAME'],$block['SLOT_'],$block['ROW_']);  echo "<div style=\"position:absolute;left:".$block['X_LABEL']."px;top:".$block['Y_LABEL']."px;z-index:-1;font-size:15\"><b>".$block['NAME']."</b></div>";
			}
			
		}
		
		
	?>
	<script>
		
	$(function() {	
		$( "#NO_CONT" ).autocomplete({
			minLength: 3,
			source: "<?=HOME?><?=APPID?>.auto/container",
			focus: function( event, ui ) {
				$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
				return false;
			},
			select: function( event, ui ) {
				$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
				$( "#POSISI" ).val(ui.item.NAME+"-"+ui.item.SLOT_+"-"+ui.item.ROW_);
				$( "#valid" ).val( "1" );
				$( "#slot" ).focus();
				return false;
			}
		})
		
		.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li style='text-align:left;font-size:18px; '></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.NO_CONTAINER + " / "+item.SIZE_+" "+item.TYPE_+"</a>" )
				.appendTo( ul );
		};
	});
	
	$(document).ready(function()
	{
		// Make sure to only match links to wikipedia with a rel tag
		$('.tooltip').each(function()
		{
			// We make use of the .each() loop to gain access to each element via the "this" keyword...
			$(this).qtip(
			{
			// var url = '<?=HOME?><?=APPID?>/update_tgl_delivery';
				content: {
					// Set the text to an image HTML string with the correct src URL to the loading image you want to use
					text: '<img class="throbber" src="/projects/qtip/images/throbber.gif" alt="Loading..." />',
					ajax: {
						url: "<?=HOME?><?=APPID?>/tes?id="+$(this).attr('id') // Use the rel attribute of each element for the url to load
					},
					title: {
						text: 'Container ' + $(this).attr('id'), // Give the tooltip a title using each elements text
						button: true
					}
				},
				position: {
					at: 'bottom center', // Position the tooltip above the link
					my: 'top center',
					viewport: $(window), // Keep the tooltip on-screen at all times
					effect: false // Disable positioning animation
				},
				show: {
					event: 'click',
					solo: true // Only show one tooltip at a time
				},
				hide: 'unfocus',
				style: {
					classes: 'qtip-wiki qtip-light qtip-shadow'
				}
			})
		})
	 
		// Make sure it doesn't follow the link when we click it
		.click(function(event) { event.preventDefault(); });
	});
	
	</script>
	
	<div class="cari">
		NO CONTAINER : <input style="font-size:20px; font-style:italic; font-weight:bold; text-transform:uppercase" tabindex="1" class="suggestuwriter" placeholder="CONTAINER" type="text" name="NO_CONT" ID="NO_CONT" size="15"  />
		 BLOK-SLOT-ROW :
		<input style="font-size:20px; font-style:italic; font-weight:bold; text-transform:uppercase" tabindex="1" class="suggestuwriter" placeholder="POSISI " type="text" name="POSISI" ID="POSISI" size="9"  />
		<!--<button id="btn1" onclick="getfocus()">CARI</button> -->
	</div>
	<div class="title"><b>IPC PONTIANAK - USTER</b></div>
	
  </body>
</html>