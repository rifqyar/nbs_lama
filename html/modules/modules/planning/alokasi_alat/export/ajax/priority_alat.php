<html>
	<head>
<style>
.content{
	width:100%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;	
}
.leftside{
	width:40%;
	float:left;
	text-align:center;
	margin-top:20px;
	margin-bottom:20px;
}
.rightside{ 
	width:60%;
	float:right;
	text-align:center;
	margin-top:20px;
	margin-bottom:20px;
}
.myfieldset{ 
	border-radius: 6px; /* Opera, Chrome */
	-moz-border-radius: 6px; /* FF */
	margin: 8px 8px 8px 8px;
	background-color: #f6f4e4;
}
.butsave {
    background: none repeat scroll 0 0 #f6f4e4;
    border-color: #f6f4e4 black black #f6f4e4;
    border-style: solid;
    border-width: 2px;
	margin-bottom: 10px;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 20px;
    padding: 4px 10px 3px 7px;
    width: 98%;
}
</style>

<? 
	$id_vs = $_GET['id'];
	$bay_area = $_GET['bay_area'];
	$bay_no = $_GET['bay_no'];
	$posisi = $_GET['posisi'];
	$alat = $_GET['id_alat'];
?>

<script type="text/javascript" src="<?=HOME;?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?=HOME;?>js/stickytooltip.js"></script>
<link type="text/css" href="<?=HOME;?>css/default.css" rel="stylesheet" />
<link type="text/css" href="<?=HOME;?>css/application.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/stickytooltip.css" />
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/jquery-ui-1.8.20.custom.css" />

<!--jqgrid-->
<script type="text/javascript" src="<?=HOME;?>jqueryui/js/grid.locale-en.js"></script>
<script type="text/javascript" src="<?=HOME;?>jqueryui/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="<?=HOME;?>jqueryui/js/jquery.searchFilter.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?=HOME;?>jqueryui/css/ui.jqgrid.css" />
<!--jqgrid-->

<script type="text/javascript">
jQuery(function() {
 jQuery("#l_priority").jqGrid({
	url:'<?=HOME?>datanya/data?q=l_priority&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['NO CONTAINER','GROSS','TUJUAN','YARD POSITION','BAY POSITION','DELETE'], 
	colModel:[
		{name:'no_cont',index:'no_cont', width:120, align:"center"},
		{name:'gross',index:'gross', width:100, align:"center"},		
		{name:'tujuan',index:'tujuan', width:120, align:"center"},
		{name:'yard',index:'yard', width:150, align:"center"},
		{name:'bay',index:'bay', width:150, align:"center"},
		{name:'delete',index:'delete', width:150, align:"center",sortable:false,search:false}
	],
	rowNum:20,
	width: 480,
	height: 120,//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 10,
	gridview: true,
	pager: '#pg_l_priority',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Priority"
 });
  jQuery("#l_priority").jqGrid('navGrid','#pg_l_priority',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_priority").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>
<title>Priority : Alat <?=$alat;?>, Bay <?=$bay_no;?> <?=$posisi;?></title>
</head>
	<body>
	<div class="content">
<fieldset class="myfieldset">
<div class="leftside">
<center>
<h2>Bay <?=$bay_no;?> <? echo strtoupper($posisi);?></h2>
<p><br/></p>
<table width="80%" cellspacing="3" border="0">
<tbody>
<?
		$db = getDB();
		$posisi = $_GET['posisi'];
		$id_vs = $_GET['id_vs'];
		$bay_area = $_GET['bay_area'];
		$no_bay = $_GET['no_bay'];
        $query_bay = "SELECT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID = '$bay_area'";
        $result_   = $db->query($query_bay);
        $bay_      = $result_->fetchRow();
		
        $jumlah_row = $bay_['JML_ROW'];
        $jml_tier_under = $bay_['JML_TIER_UNDER'];
		$jml_tier_on = $bay_['JML_TIER_ON'];
		$width = $jumlah_row+1;
	?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	   
       <tr>   
         <?php
            
			if($posisi=='below')
			{
				$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
			}
			else
			{
				$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
				$result3    = $db->query($query_cell2);
				$blok2      = $result3->getAll();
			}
			
            $n='';
			$br='';
			$tr='';
			// debug($blok2);die;
            foreach ($blok2 as $row8){
                //echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				//echo $tr;			
            
			if ($index%$width != 0) 
			{
				if ($row8['STATUS_STACK'] == 'A')
				{ 
					$cek_alokasi  = "SELECT SIZE_,TYPE_,STATUS_,HZ_,IMO,STAT_ALOKASI FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$bay_area' AND CELL_NUMBER = '$cell_address'";
					$result15  = $db->query($cek_alokasi);
					$alokasi_cek  = $result15->fetchRow();
					
					$cek_tipe  = $alokasi_cek['TYPE_'];
					$cek_size  = $alokasi_cek['SIZE_'];
					$cek_status = $alokasi_cek['STATUS_'];
					$cek_hz  = $alokasi_cek['HZ_'];
					$cek_imo  = $alokasi_cek['IMO'];
					$stat_alokasi = $alokasi_cek['STAT_ALOKASI'];
					
					if($cek_status=='MTY')
					{
					?>			
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/EMPTY.png" height="18" width="20" /></td>
                     </div>
                  
               <?
					}
					else if($cek_hz=='Y')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/CLASS.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if($cek_tipe=='RFR')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/REEFER.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if($cek_tipe=='HC')
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/HC.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40b')||($stat_alokasi=='40btop')||($stat_alokasi=='40bleft')||($stat_alokasi=='40bright'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/40FEETODD.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dtop')||($stat_alokasi=='top'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDTOP.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dright')||($stat_alokasi=='right'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDRIGHT.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else if(($stat_alokasi=='40dleft')||($stat_alokasi=='left'))
					{
			   ?>	
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; "><img src="images/container/OVDLEFT.png" height="18" width="20" /></td>
                     </div>
			   <?	
					}
					else
					{
			   ?>
			   
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66FF33'" align="center" style="width:20px;height:20px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#66FF33; ">&nbsp;</td>
                    </div>
			   
			   <?
					}
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; ">&nbsp;</td>
                     </div>
                  
               <? 
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma; border:1px solid #000000;background-color:#FFF; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }						 
						  else {
						 ?>
						<td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:20px;height:20px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</center>
</div>
	
	<div class="rightside">
	<center>
	<h2>Priority List <? echo $alat;?></h2>
	<br/>
	<table id='l_priority' width="100%"></table> <div id='pg_l_priority'></div>
	</center>
	</div>
</fieldset>
<input id="id_SAVEBUT" class="butsave" type="button" onclick="save()" value="S I M P A N">
</div>	
	</body>
</html>