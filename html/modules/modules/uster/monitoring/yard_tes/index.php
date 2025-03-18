
    
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
	</style>
  </head>
  <body>
  
   <!--<div class="ex" id='box'></div> -->
	<!--
	<div id="buttons">
		  <input type="button" id="activate" value="Activate rectangle">
	</div>
	<h2 id="tes">Owls</h2>
		<img  id="gambar" src="http://media2.juggledesign.com/qtip2/images/demos/spottedowl.jpg"  width="180" height="274" />
	-->
	
    
	
	<meta charset="utf-8">
	<link rel="stylesheet" href="yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="yard/src/css/main.css">
        
        
    <script type="text/javascript" src="tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
	<script src="tooltip/jquery.qtip.min.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/jquery.qtip.min.css" />
	
    <script defer="defer">
	 
	//==========================
	// Buat blok tipe Horisontal
	//==========================
	
	function boxh(id)
	{
		$("body").append(" <div id='"+id+"' class='boxh' ></div>")
	}
	//karena element div-nya harus ter-create dulu, baru bisa 
	function boxhPosition(x,y,id)
	{
		boxh(id);
		document.getElementById(id).style.position  = 'absolute';
		document.getElementById(id).style.top  = y+"px";
		document.getElementById(id).style.left = x+"px";
	}	
	
	
	//========================
	
	
	//==========================
	// Buat blok tipe Vertikal
	//========================
	
	function boxv(id)
	{
		$("body").append(" <div id='"+id+"' class='boxv' ></div>")
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
			for($i=1; $i <= $slot; $i++ )
			{
				for($j = 1; $j <= $row; $j++)
				{
					$db 		= getDB("storage");


					$query_boxh		= "SELECT COUNT(b.NO_CONTAINER) JUMLAH
											FROM BLOCKING_AREA a, PLACEMENT b
											WHERE a.ID = b.ID_BLOCKING_AREA
											AND b.SLOT_='$i'
											AND b.ROW_ = '$j'
											and a.NAME = '$blok'
												";
												
					$result_boxh 	= $db->query($query_boxh);
					$row_boxh   	= $result_boxh->fetchRow();
					$jml_box		= $row_boxh["JUMLAH"];
					
					echo "<script>boxhPosition(".($x-(14*($j+1))).",".($y+(32*($i+1))).",'".$blok.$i.$j."')</script>";
						
					if($jml_box == 0)
					{
						// echo "WHITE";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#FFFFFF';</script>";
						// exit();
					}
					else if($jml_box == 1)
					{
						// echo "YELLOW";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#CCCCFF';</script>";
						// exit();
					}
					else if($jml_box == 2)
					{
						// echo "ORANGE";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#9999FF';</script>";
						// exit();
					}
					else if($jml_box == 3)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#6666FF';</script>";
						// exit();
					}
					else if($jml_box == 4)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#3333FF';</script>";
						// exit();
					}
					else if($jml_box == 5)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#0000FF';</script>";
						// exit();
					}
					else if($jml_box == 6)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#0000CC';</script>";
						// exit();
					}
					else if($jml_box >= 7)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#000099';</script>";
						// exit();
					}	
				}
			}
			
		}
		
		function createBlokV($x,$y,$blok,$slot,$row)
		{
			for($i=1; $i <= $slot; $i++ )
			{
				for($j = 1; $j <= $row; $j++)
				{
					$db 		= getDB("storage");


					$query_boxv		= "SELECT COUNT(b.NO_CONTAINER) JUMLAH
											FROM BLOCKING_AREA a, PLACEMENT b
											WHERE a.ID = b.ID_BLOCKING_AREA
											AND b.SLOT_='$i'
											AND b.ROW_ = '$j'
											and a.NAME = '$blok'
												";
												
					$result_boxv 	= $db->query($query_boxv);
					$row_boxv   	= $result_boxv->fetchRow();
					$jml_box		= $row_boxv["JUMLAH"];
					
					
					echo "<script>boxvPosition(".($x-(32*($i+1))).",".($y+(14*($j+1))).",'".$blok.$i.$j."')</script>";
						
					if($jml_box == 0)
					{
						// echo "WHITE";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#FFFFFF';</script>";
						// exit();
					}
					else if($jml_box == 1)
					{
						// echo "YELLOW";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#CCCCFF';</script>";
						// exit();
					}
					else if($jml_box == 2)
					{
						// echo "ORANGE";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#9999FF';</script>";
						// exit();
					}
					else if($jml_box == 3)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#6666FF';</script>";
						// exit();
					}
					else if($jml_box == 4)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#3333FF';</script>";
						// exit();
					}
					else if($jml_box == 5)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#0000FF';</script>";
						// exit();
					}
					else if($jml_box == 6)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#0000CC';</script>";
						// exit();
					}
					else if($jml_box >= 7)
					{
						// echo "RED";
						echo "<script>document.getElementById('".$blok.$i.$j."').style.backgroundColor  = '#000099';</script>";
						// exit();
					}
				}
			}
			
		}
		
		// createBlokH(500,50,1,27,'U5S');
		
		// createBlokV(160,130,1,16,'U5Q');
		
		// createBlokV(500,130,2,16,'U4A');
		
		// createBlokH(1000,50,1,25,'U4S');
		
		// createBlokH(1000,115,2,25,'U4B');
	
		
		createBlokH(500,50,'U5S',1,27);
		createBlokV(175,145,'U5Q',1,16);
		createBlokV(515,145,'U4A',2,16);
		
		createBlokH(1000,50,'U4S',1,25);
		createBlokH(1000,110,'U4B',2,25);
		createBlokV(480,400,'U5D',2,20);
		
		// echo '<script>$("body").append("</div>")</script>';
		// echo "</div>";
	?>
	<script>
		
	var tes= 'U5S11';
	
	function tip(id,teks)
	{
		$('#'+id).qtip(
			{
				content: {
					// Set the text to an image HTML string with the correct src URL to the loading image you want to use
					// text: '<table><tr><td><img src="images/row_cont.png" width="40" height="40"><br/>TIER 1</td><td>TESU1234567<br>40 | DRY | FCL</td></tr></table>',
					text: teks,
					ajax: {
						url: $(this).attr('rel') // Use the rel attribute of each element for the url to load
					},
					title: {
						text: 'Container', // Give the tooltip a title using each elements text
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
	}
	</script>
	<?php
		
		$query_block	= "SELECT * 
							FROM BLOCKING_AREA";
												
		$result_block 	= $db->query($query_block);
		$row_block   	= $result_block->getAll();
		// $block			= $row_block["NAME"];
		
		
		$blok = 'U5S11';
		
		$tes="<table><tr><td><img src=\"images/row_cont.png\" width=\"40\" height=\"40\"><br/>TIER 1</td><td>GESU1234567<br>40 | DRY | FCL</td></tr></table>";
		
			echo "<script>tip('".$blok."','".$tes."')</script>";
		
	?>
  </body>
</html>