
    
    <!DOCTYPE HTML>
<html>
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
		document.getElementById(id).style.position  = 'fixed';
		document.getElementById(id).style.top  = y+"px";
		document.getElementById(id).style.left = x+"px";
	}	
	
	function createBlokH(x,y,slot,row,blok)
	{
		for(var i=0; i < slot; i++ )
		{
			for(var j = 0; j < row; j++)
			{
				
				boxhPosition(x-(14*(j+1)),y+(32*(i+1)),blok+i+j);
				
				<?
					// $db = getDB("storage");
					
				
				?>
				
				//var url			= "{$HOME}{$APPID}.ajax/del_cont";
				// $.post(url,{NO_CONT: $no_cont, NO_REQ : no_req_, BP_ID : $bp_id},function(data){
				// console.log(data);
					// if(data == "OK")
					// {
						// document.getElementById(blok+i+j).style.backgroundColor  = 'yellow';	
					// }
					
				// });
				
				
			
			}
		}
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
		document.getElementById(id).style.position  = 'fixed';
		document.getElementById(id).style.top  = y+"px";
		document.getElementById(id).style.left = x+"px";
	}	
	
	function createBlokV(x,y,slot,row,blok)
	{
		for(var i=0; i < slot; i++ )
		{
			for(var j = 0; j < row; j++)
			{
				
				boxvPosition(x-(32*(i+1)),y+(14*(j+1)),blok+i+j);
				
				
			}
		}
	}
	
	//========================
	
	createBlokH(500,50,1,27,'U5S');
	
	createBlokV(160,120,1,16,'U5Q');
	
	createBlokV(500,120,2,16,'U4A');
	
	 
	
	// function createBlok()
	// {
		// for(var n = 0; n < 23; n++) {
		  
			// box = new Kinetic.Rect({
								  // x: 320 +(13*(n+1)),
								  // y: 50,
								// width: 13,
								// height: 32,
								// // fill: 'green',
								// stroke: 'none',
								// strokeWidth: 1,
								// id : "blok"+n
								// });
			
		// }
	// }	 
	
	var tes= 'U5S00';
	
	$('#'+tes).qtip(
		{
			content: {
				// Set the text to an image HTML string with the correct src URL to the loading image you want to use
				text: '<table><tr><td><img src="images/row_cont.png" width="40" height="40"><br/>TIER 1</td><td>TESU1234567<br>40 | DRY | FCL</td></tr></table>',
				ajax: {
					url: $(this).attr('rel') // Use the rel attribute of each element for the url to load
				},
				title: {
					text: 'Container' + $(this).text(), // Give the tooltip a title using each elements text
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
 
	
	
	
      
	
	
// });
	  
    </script>
  </body>
</html>