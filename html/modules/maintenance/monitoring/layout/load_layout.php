<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?=HOME?>yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="<?=HOME?>yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="<?=HOME?>yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?=HOME?>yard/src/css/main.css">
    <script type="text/javascript" src="<?=HOME?>tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=HOME?>tooltip/stickytooltip.css" />

<style type="text/css">
        /*body{margin:0px; font-family:Arial, Sans-Serif; font-size:13px;}*/
        /* dock */
        #dock{margin:0px; padding:0px; list-style:none; position:fixed; top:0px; height:100%; 
              z-index:100; background-color:#f0f0f0; left:0px;}
        #dock > li {width:40px; height:120px; margin: 0 0 1px 0; background-color:#dcdcdc;
                     background-repeat:no-repeat; background-position:left center;}
        
        /*#dock #links {background-image:url(<?=HOME?>images/dock_ganda/links.png);}
        #dock #files {background-image:url(<?=HOME?>images/dock_ganda/files.png);}*/
        #dock #tools {background-image:url(<?=HOME?>images/dock_ganda/tools.png);}
		#dock #helps {background-image:url(<?=HOME?>images/dock_ganda/helps.png);}
		ul li img {vertical-align: middle;}
        #dock > li:hover {background-position:-40px 0px;}
        
        /* panels */
        #dock ul li {padding:5px; border: solid 1px #F1F1F1;}
        #dock ul li:hover {background:#D3DAED url(<?=HOME?>.maintenance.monitoring.layout/item_bkg.png) repeat-x; border: solid 1px #A8D8EB;}
        #dock ul li.header, #dock ul li .header:hover {background:#D3DAED url(<?=HOME?>.maintenance.monitoring.layout/header_bkg.png) repeat-x;border: solid 1px #F1F1F1;}
      
        #dock > li:hover ul {display:block;}
        #dock > li ul {position:absolute; top:0px; left:-180px;  z-index:-1;width:180px; display:none;
                       background-color:#F1F1F1; border:solid 1px #969696; padding:0px; margin:0px; list-style:none;}
        #dock > li ul.docked { display:block;z-index:-2;}
        
        .dock,.undock{float:right;}
       .undock {display:none;}
        #content {margin: 10px 0 0 60px;}
     
    </style>
<script type='text/javascript'>

setInterval(function()
		{
			window.location = "<?=HOME?>maintenance.monitoring.layout/load_layout?id=23";
		},
		300000);

$(document).ready(function() 
{
	
			var docked = 0;
            
            $("#dock li ul").height($(window).height());
            
            $("#dock .dock").click(function(){
                $(this).parent().parent().addClass("docked").removeClass("free");
                
                docked += 1;
                var dockH = ($(window).height()) / docked
                var dockT = 0;               
                
                $("#dock li ul.docked").each(function(){
                    $(this).height(dockH).css("top", dockT + "px");
                    dockT += dockH;
                });
                $(this).parent().find(".undock").show();
                $(this).hide();
                
                if (docked > 0)
                    $("#content").css("margin-left","250px");
                else
                    $("#content").css("margin-left", "60px");
            });
            
             $("#dock .undock").click(function(){
                $(this).parent().parent().addClass("free").removeClass("docked")
                    .animate({left:"-180px"}, 200).height($(window).height()).css("top", "0px");
                
                docked = docked - 1;
                var dockH = ($(window).height()) / docked
                var dockT = 0;               
                
                $("#dock li ul.docked").each(function(){
                    $(this).height(dockH).css("top", dockT + "px");
                    dockT += dockH;
                });
                $(this).parent().find(".dock").show();
                $(this).hide();
                
                if (docked > 0)
                    $("#content").css("margin-left", "250px");
                else
                    $("#content").css("margin-left", "60px");
            });

            $("#dock li").hover(function(){
                $(this).find("ul").animate({left:"40px"}, 200);
            }, function(){
                $(this).find("ul.free").animate({left:"-180px"}, 200);
           });
});

	$.fx.speeds._default = 1000;
	$(function() {
		$( "#dialog" ).dialog({
			autoOpen: false,
			show: "blind",
			hide: "explode"
		});

		$( "#opener" ).click(function() {
			$( "#dialog" ).dialog( "open" );
			return false;
		});
	});
        
        $(function() {
		$( "#dialog2" ).dialog({
			autoOpen: false,
			show: "blind",
			hide: "explode"
		});

		$( "#opener2" ).click(function() {
			$( "#dialog2" ).dialog( "open" );
			return false;
		});
	});
	
	
	function visit(idc)
	{
		var url				= "<?=HOME?>maintenance.monitoring.ajax/vi_cont";
		$.post(url,{IDC : idc},function(data) 
		{
			//alert(idc);
			//alert(data);
		    console.log(data);
			var row_data = data;
			var explode = row_data.split(',');
			var ket_contx = explode[0];
			var ket_sizex= explode[1];
			var ket_typex = explode[2];
			var ket_statusx = explode[3];
			var ket_hzx = explode[4];
			var ket_tonx = explode[5];
			var ket_vesx = explode[6];
			var ket_tujx = explode[7];
			var ket_bsrtx = explode[8];
		
			$('#ket_cont').html(ket_contx);
			$('#ket_size').html(ket_sizex);
			$('#ket_type').html(ket_typex);
			$('#ket_status').html(ket_statusx);
			$('#ket_hz').html(ket_hzx);
			$('#ket_ton').html(ket_tonx);
			$('#ket_ves').html(ket_vesx);
			$('#ket_tuj').html(ket_tujx);
			$('#ket_bsrt').html(ket_bsrtx);
		});
		
		

	}
	
	function kade()
	{
		alert("Coba Kade");
	}
</script>

<style>
.content{
	widtd:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	widtd:100%;
	float:left;
	text-align:left;
}
.tabss{
	margin-top: 20px;
}    
</style>

<?php
	$v_filter1=0;
?>
<script type="text/javascript">
    
function load_block(block_id,slot_id,row_id,yard_id)
{
	var block_id_ = block_id;
    //$('#load_block').load('<?=HOME?>maintenance.monitoring/load_per_block?id='+block_id_+' #load_block');   
	$('#loadx').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>').dialog({modal:true, height:400,width:790});
	$('#loadx').load("<?=HOME?>maintenance.monitoring.sticky_layout/load_row_slot?block_id="+block_id+"&slot_id="+slot_id+"&row_id="+row_id+"&yard_id="+yard_id+"").dialog({modal:true, height:660,width:790});
}

function detail(yard_id)
{
	
	$("#load_layout" ).load('<?=HOME?>maintenance.monitoring/load_layout?id='+yard_id+' #load_layout', function(data){
		
			$( "#load_layout" ).dialog( "open" );
		
		});
	
}
function reset_flt()
{
	alert('Reset Post Data Filter');
}

function yor_p()
{
	$('#loadd').load("<?=HOME?>maintenance.monitoring.layout/yor_performance").dialog({modal:true, height:600,width:500});
}

function vessel_p()
{
	$('#loadv').load("<?=HOME?>maintenance.monitoring.layout/vessel_performance").dialog({modal:true, height:600,width:700});
}

function yd_realization()
{
	$('#loadyd').load("<?=HOME?>maintenance.monitoring.layout/print_yd").dialog({modal:true, height:200,width:180,title: "Yard Realization Print"});
}
function copy_yd()
{
	window.open('<?=HOME?>maintenance.monitoring.print/copy_yd/?id_yard=23','_blank');
}
function cetak_yard()
{
	var yd_id = "23";
	var block_id_nm = $("#block_select").val();
	var explode = block_id_nm.split(',');
		var block_id = explode[0];
		var block_nm = explode[1];
	var slot_yd = Number($("#slot_yd").val());
	
	if(slot_yd%2==0)
	{
		slot_yd=slot_yd-1;
	}
	else
	{
		slot_yd=(slot_yd+1)/2;
	}
	
	//alert(slot_yd);
	if((block_id_nm=="")||(slot_yd==""))
	{
		alert("Data Tidak Lengkap...!!!");
	}
	else
	{
		window.open('<?=HOME?>maintenance.monitoring.print/yard_realization/?id_yard='+yd_id+'&id_block='+block_id+'&nm_block='+block_nm+'&slot='+slot_yd,'_blank');
	}
}

function filter_yard()
{
	$('#load_filter').load("<?=HOME?>maintenance.monitoring.layout/filter").dialog({modal:true, height:300,width:400});
}

function reset_yard()
{

	var url="<?=HOME?>maintenance.yard_builder.ajax/reset_yard";
	
	$.post(url,function(data) 
		{
			console.log(data);
			alert(data);
			window.location = "<?=HOME?>maintenance.monitoring.layout/load_layout?id=23";
		});
}
</script>

<?
	if (isset($_POST['res']))
	{
		unset($_POST['id_vessel']);
		unset($_POST['tonase']);
		unset($_POST['tuj']);
		unset($_POST['size']);
		unset($_POST['type']);
		unset($_POST['res']);
	}
?>
</head>


<ul id="dock">
        <li id="tools">
            <ul class="free">
                <li class="header"><a href="#" class="dock">Dock</a><a href="#" class="undock">Undock</a>Dock Report System</li>				
                <li valign="middle"><button id="but_yor" onclick="yor_p()"><img src="<?=HOME?>images/chart-pie.png" width="40" height="40"></button> YOR Performance</li>
                <li valign="top"><button id="but_gate"><img src="<?=HOME?>images/delivery.png" width="40" height="40"></button> Gate Performance</li>
                <li valign="top"><button id="but_vessel" onclick="vessel_p()"><img src="<?=HOME?>images/ship.png" width="40" height="40"></button> Vessel Performance</li>
				<li valign="top"><button id="but_cont" onclick="copy_yd()"><img src="<?=HOME?>images/contaier.png" width="40" height="40"></button>Copy Yard</li>
				<li valign="top"><button id="but_yd" onclick="yd_realization()" ><img src="<?=HOME?>images/block_yd.png" width="40" height="40"></button> Yard Realization</li>
				<li valign="top"><button id="but_filter" onclick="filter_yard()"><img src="<?=HOME?>images/filter.png" width="40" height="40"></button> Filter Lapangan</li>
				<?if ($_SESSION["ID_GROUP"]==1){?>
					<li valign="top"><button id="rst" onclick="reset_yard()"><img src="<?=HOME?>images/clock_reset.png" width="40" height="40"></button> Reset Yard</li>
					<?}?>
           </ul>
        </li>
		<li id="helps">
            <ul class="free">
				<li>
					<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
						-webkit-border-radius: 10px 10px 10px 10px;">
							<legend>Block</legend>
								<table align="left" width="100">
										<tr align="center"><td class="grid-header">Color</td><td class="grid-header">Block Type</td></tr>
										<tr align="center" bgcolor="#ffffff">
											<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; "></td>
											<td class="grid-cell">Export</td>
										</tr>
										<tr align="center" bgcolor="#ffffff">
											<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#82ff07; "></td>
											<td class="grid-cell">Import</td>
										</tr>
							   </table>
							</fieldset>
				</li>
				<li><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
					-webkit-border-radius: 10px 10px 10px 10px;">
						<legend>Legend Tier</legend>
							<table align="left">
									<tr align="center">
										<td class="grid-header">Color</td><td class="grid-header"> Tier</td>
										<td class="grid-header">Color</td><td class="grid-header"> Tier</td>
									</tr>
									<tr align="center" bgcolor="#ffffff">
										<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#5b0cad; "></td>
										<td class="grid-cell">1 Tier</td>
										<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#f89311; "></td>
										<td class="grid-cell">4 Tier</td>
									</tr>
									<tr align="center" bgcolor="#ffffff">
										<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#df11f8; "></td>
										<td class="grid-cell">2 Tier</td>
										<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FF0033; "></td>
										<td class="grid-cell">5 Tier</td>
									</tr>
									<tr align="center" bgcolor="#ffffff">
										<td align="center" style="width:15px;height:5px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#f8ea11; "></td>
										<td class="grid-cell">3 Tier</td>
									</tr>
									
						   </table>
						</fieldset>
				</li>
           </ul>
        </li>
    </ul>

<body>


	<div style="padding-left: 0px; float:left" id="content">
	<fieldset class="form-fieldset" style="margin:-10px -20px -20px -20px;vertical-align:left; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; ">
    <center>
		<span class="graybrown">
        <?php
            $db             = getDB();
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];
        ?>
        <br>
		<table width="1315"><tr height="5"bgcolor="#cecdcd" align="center" ><td></td></tr>
            <tr height="2"bgcolor="#ffffff" align="center" ><td></td></tr><tr bgcolor="#cecdcd" align="center" ><td>
			<font color="black"><h2>Layout</font> <font color="black">CY Jambi</font></h2></td></tr>
			<tr height="2"bgcolor="#ffffff" align="center" ><td></td></tr>
			<tr height="5"bgcolor="#cecdcd" align="center" ><td></td></tr>
        </table>
        <br>
	<div align="center" style="background-color:#F6F4E4;">
	<table width="100%" cellspacing="0" border="0" bgcolor="#F6F4E4">
	<tbody>
		<td valign="bottom" colspan="4" align="center">
		<table bordercolor="#037ACA" border="0" cellspacing="0" cellpadding="0" align="center">
		<tbody>
    <tr><td colspan="<?=$width?>"><!--<img src="<?=HOME?>images/Dermaga_2.png">-->
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="width:1600px;height:80px;font-size:8px; font-family:Tahoma; background-color:#a2e1f9;" align="left" colspan=4>
			&nbsp;&nbsp;&nbsp;&nbsp;
			
			</td>
		</tr>
		
		<tr>
			<td onclick="kade()" style="width:400px;height:60px;font-size:12px; font-family:Tahoma; border:1px solid #ffffff;background-color:#eaeaea;" align="center">
			<b>Dermaga</b>
			</td>
			
			</td>
		</tr>
		</table>
	</td>
		
	</tr>
    <tr>
         <?php
		 //echo "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_,a.STATUS_BM FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";die;
			/*$query_cell2 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_,a.STATUS_BM FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC";
			*/
			$query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA b,         
                            YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '23' AND a.SIZE_PLAN_PLC IS NULL 
                            UNION 
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40d'
                            UNION
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '20'
                           -- UNION 
                           -- SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40b'
                            ORDER BY INDEX_CELL ASC";
			$result3     = $db->query($query_cell2);
			
            $blok2       = $result3->getAll();
			
         
            foreach ($blok2 as $row){
			// $index_cell_ = $row['INDEX_CELL'];
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos		  = $row['POSISI'];
					$sz_plc	      = $row['SIZE_PLAN_PLC'];
					
					if ($sz_plc=='40d')
					{
						if($pos=='vertical')
						{
							$cr="rowspan=2";
						}
						else if($pos=='horizontal')
							$cr="colspan=2";
					}
					else
						$cr='';
			//echo $index_cell_;
                if ($row['NAME'] <> 'NULL')
				{                
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					
					if(isset($_POST["id_vessel"])||isset($_POST["tonase"])||isset($_POST["tuj"])||isset($_POST["size"])||isset($_POST["type"]))
					{
						/*$idvs=$_POST["id_vessel"];
						$ton=$_POST["tonase"];
						$tuj=$_POST["tuj"];
						$sz=$_POST["size"];
						$type=$_POST["type"];
						
						$query_place = "SELECT filter_cont($id_block,$slot_,$row_,$index_cell_,'$idvs','$ton','$tuj','$sz','$type') AS JUM from dual";
						$filter=1;*/
						
						$idvs	=	$_POST["id_vs"];
						$ton	=	$_POST["tonase"];
						$tuj	=	$_POST["id_pel_tuj"];
						$sz		=	$_POST["size"];
						$type	=	$_POST["type"];
						$sta	=	$_POST["status"];
						
						if ($type <> NULL){
							$query_type = " and a.TYPE_CONT = '$type'";
						} else {
							$query_type = " ";
						}
						
						if ($sz <> NULL){
							$query_sz = " and a.SIZE_ = '$sz'";
						} else {
							$query_sz = " ";
						}
						
						if ($sta <> NULL){
							$query_sta = " and REGEXP_REPLACE (a.STATUS_CONT, '[[:space:]]', '') = '$sta'";
						} else {
							$query_sta = " ";
						}
						
						if ($idvs <> NULL){
							$query_idvs = " and a.ID_VS = '$idvs'";
						} else {
							$query_idvs = " ";
						}
						
						if ($ton <> NULL){
							$query_ton = " and b.KATEGORI_BERAT = '$ton'";
						} else {
							$query_ton = " ";
						}
						
						if ($tuj <> NULL){
							$query_tuj = " and REGEXP_REPLACE (a.ID_PEL_TUJ, '[[:space:]]', '') = '$tuj'";
						} else {
							$query_tuj = " ";
						}
						
						$query_place = "SELECT COUNT(a.ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD a, TB_CONT_JOBSLIP b WHERE a.ID_JOBSLIP = b.ID_JOB_SLIP AND a.ID_BLOCKING_AREA = '$id_block' AND a.SLOT_YARD = '$slot_' AND a.ROW_YARD = '$row_' AND a.ID_CELL = '$index_cell_' AND FLAG_HP IS NULL ". $query_sz . $query_type. $query_idvs . $query_tuj. $query_ton . $query_sta; 
						$filter=1;
                    }
					else
					{
						$query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' AND FLAG_HP IS NULL";
						$filter=0;
					}
                    $result2     = $db->query($query_place);
                    $place       = $result2->fetchRow();
                     
                    // debug($place);die;
                     $placement   = $place['JUM']; ?>
                   
                <?  if (($filter==0)&&($placement <> 0))
				
					{
						if ($placement>=5)
						{
							$color_plc='#FF0033';
						}
						else if ($placement==4)
						{
							$color_plc='#f89311';
						}
						else if ($placement==3)
						{
							$color_plc='#f8ea11';
						}
						else if ($placement==2)
						{
							$color_plc='#df11f8';
						}
						else if ($placement==1)
						{
							$color_plc='#5b0cad';
						}
						
						
						?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')"  onMouseOut="this.style.backgroundColor='<?=$color_plc?>'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff; background-color:<?=$color_plc?>; " <?=$cr?>><blink><font color="white"><?=$placement//echo $row['NAME'];=$index_cell_?><a><? //echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
<!--                     <div id="x" class="drag blue">-->


<!--                     </div>-->

                   <? } 
						else if (($filter==1)&&($placement<>0))
						{
							if ($placement>=5)
							{
								$color_plc='#FF0033';
							}
							else if ($placement==4)
							{
								$color_plc='#f89311';
							}
							else if ($placement==3)
							{
								$color_plc='#f8ea11';
							}
							else if ($placement==2)
							{
								$color_plc='#df11f8';
							}
							else if ($placement==1)
							{
								$color_plc='#5b0cad';
							}
						?>
							
							<td data-tooltip="sticky<?=$index_cell_?>" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#fd78f2'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:<?=$color_plc?>; " onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" <?=$cr?>><blink><font color="white"><a><? echo $placement ?><?//=$st_bm;?><?//=$id_block;?></a></font></blink></td>
						<?
						}
				   else 
				   { ?>

				   <? 
				   if (($st_bm=='Muat')&&($row_==9)&&($slot_<=11))
					{?>
                          <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
						  &nbsp;<?//=$name;?></td>
					
					
					<? 
					}
					else if ($st_bm=='Muat')
					{?>
                          <td data-tooltip="sticky<?=$index_cell_?>" onclick="load_block('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#86BCFF'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF; " <?=$cr?>>
						  &nbsp;<?//=$name;?></td>
					
					
					<? 
						
					}
					else if ($st_bm=='Bongkar')
					{
					?>
							<td data-tooltip="sticky<?=$index_cell_?>" onclick="('<?=$id_block?>','<?=$slot_?>','<?=$row_?>','<?=$yard_id?>')" onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#82ff07'" align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#82ff07; ">
							&nbsp;</td>
						
					<? }
					
					?>	  
<!--                         </div>-->
               <? }  
				} 
				else 
				{
				?>
                     <td align="center" style="width:30px;height:10px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;">
					<? if (($slot_ == 0) AND ($row_ <> 0) AND ($st_bm == NULL)) {
							?><?echo $row_;?><?
					   } else if (($slot_ <> 0) AND ($row_ == 0) AND ($st_bm == NULL)) {
							?><?
								if($slot_==1)
								{
									$c=0;
									$x=0;
								}
								$c=$x+$slot_;	
								echo $c;
								$x=$slot_;
							
							//echo $slot_;?><?
					   } else if (($slot_ == 0) AND ($row_ == 0) AND ($st_bm <> NULL)) {
							?><font size="1pt"><b><?//=$st_bm;?></b></font></font><?
						} 
						if(($row['INDEX_CELL']==398) ||($row['INDEX_CELL']==767))
					{
						?><b><?echo 'Blok';?></b><?
					}					
					if($row['INDEX_CELL']==399)
					{
						?><b><?echo 'R0102';?></b><?
					}
					if($row['INDEX_CELL']==768)
					{
						?><b><?echo 'R03';?></b><?
					}
						
						?>
						<?//=$name;?>
						</td>
              <?}
                if (($row['INDEX_CELL']+1) % $width == 0)
				{ ?>
                 </tr>
                <? 
				}
                ?>      
        <?    } ?>
		
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</div>
</tbody>
    </center>
</fieldset>   
<br />

<br />
 
        <div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">                    
                    <? 
                                    $db         = getDB();
                                    
                                    $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
                                    $result_    = $db->query($query_blok);
                                    $blok       = $result_->fetchRow();
                                
                                    $yard_id    = $blok['ID'];
                               
                                    $query_cell4 = "SELECT a.INDEX_CELL, b.ID, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND b.NAME <> 'NULL' ORDER BY a.INDEX_CELL ASC";
                                    $result4    = $db->query($query_cell4);
                                    $blok4      = $result4->getAll();

                              
                                    foreach($blok4 as $dama){
                            ?>
                                    <div id="sticky<? echo $dama['INDEX_CELL']; ?>" class="atip">                                    
                                    <? 
                                    $query_place = "SELECT NO_CONTAINER,SLOT_YARD,ROW_YARD FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = ".$dama['ID']." AND SLOT_YARD = ".$dama['SLOT_']." AND ROW_YARD = ".$dama['ROW_']." AND ID_CELL = ".$dama['INDEX_CELL']."";
									
                                    $result2     = $db->query($query_place);
                                    $place       = $result2->fetchRow();
                                    if($dama['SLOT_']==1)
									{
										$c=0;
										$x=0;
									}
									$c=$x+$dama['SLOT_'];	
									
									
									
                                    if ($place['NO_CONTAINER'] == ''){?><b>BLOK <?=$dama['NAME']?> SLOT <? echo $c;
									$x=$dama['SLOT_'];
									?> 
									ROW <?=$dama['ROW_']?></b><br>
                                   <font color="red"> <i> -- There is no container heres -- </i></font>
                                    <? } else {
										
									?>
                                       <?php
                                            $query_index_cell   = "SELECT a.ACTIVITY, b.NM_KAPAL, CONCAT(CONCAT(b.VOYAGE_IN,'-'),b.VOYAGE_OUT) VOYAGE, a.NO_CONTAINER, a.SIZE_, a.TON,a.TIER_YARD,a.TYPE_CONT,a.STATUS_CONT, a.ID_PEL_TUJ FROM YD_PLACEMENT_YARD a, RBM_H b WHERE a.ID_VS = b.NO_UKK AND  a.ID_CELL = ".$dama['INDEX_CELL']." AND a.FLAG_HP IS NULL ORDER BY a.TIER_YARD DESC";
                                            $result_index_cell  = $db->query($query_index_cell);
                                            $tier               = $result_index_cell->getAll();
                                            
                                       ?>
									   <b>BLOK <?=$dama['NAME']?> SLOT <?=$place['SLOT_YARD']?> ROW <?=$place['ROW_YARD']?></b>
									   <table>
                                        <?
										
                                            foreach($tier as $row){
                                       ?>
                                           <tr><TD width=10 align='center' style="background-color:#fbfd4a;border-radius: 5px 1px 1px 5px;-moz-border-radius: 5px 1px 1px 5px; -webkit-border-radius: 5px 1px 1px 5px;"><?=$row['TIER_YARD']?></TD><td><img src="<?=HOME?>images/row_cont.png" width="40" height="40"></td>
										   <td>
                                           <b>No Container : </b> <font color="blue"><?=$row['NO_CONTAINER']?></font> &nbsp <b>Tujuan : </b> <font color="blue"><?=$row['ID_PEL_TUJ']?></font><br>
										   <b>Size/Type/Status :</b> <font color="blue"> <?=$row['SIZE_']?>/<?=$row['TYPE_CONT']?>/<?=$row['STATUS_CONT']?></font><BR>
										   <b>Vessel/Voyage :</b> <font color="blue"> <?=$row['NM_KAPAL']?>/<?=$row['VOYAGE']?></font><BR>
										   <b>Activity :</b> <font color="blue"> <?=$row['ACTIVITY']?></font> &nbsp <b>Tonase : </b> <font color="blue"><?=$row['TON']?></font><br><BR>

										   </td></tr>
										   
                                       <? }
									   ?>
									   </TABLE>
                                     <?  }?>
                                    </div>
                   <? }?> 
                        
					
			<div id="stickycoba" class="atip">                                    
                <b><label id="ket_bsrt" name="ket_bsrt"></label></b>
				<table>
					<tr>
					<td><img src="<?=HOME?>images/container_biru.png" width="50" height="50"></td>
					<td>
						<font size=4px><label id="ket_cont" name="ket_cont"></label></font><br>
						Size 	: <label id="ket_size" name="ket_size"></label><br>
						Type 	: <label id="ket_type" name="ket_type"></label><BR>
						Status 	: <label id="ket_status" name="ket_status"></label><BR>
						Hz 		: <label id="ket_hz" name="ket_hz"></label><BR>
						Tonase 	: <label id="ket_ton" name="ket_ton"></label> kg<br>
						Vessel 	: <label id="ket_ves" name="ket_ves"></label><br>
						Tujuan 	: <label id="ket_tuj" name="ket_tuj"></label><br>
					</td>
					</tr>
				</TABLE>
            </div>
                                    </div>

                    <div class="stickystatus"></div>
                    </div>
					
					
</table>
</div>
<div style="padding-left: 0px; float:left;">
<form><div id="loadx"></div></form>
<div id="load_block"></div>
<div id="loadd"></div>
<div id="loadv"></div>
<div id="loadyd"></div>
<div id="loadcy"></div>
<div id="load_filter"></div>

</div>
</body>
</html>