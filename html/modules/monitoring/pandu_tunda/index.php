<link type="text/css" href="<?php echo HOME;?>css/default.css" rel="stylesheet" />
<link type="text/css" href="<?php echo HOME;?>css/application.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo HOME;?>css/jquery.slidemenu.css" />
<script type="text/javascript" src="<?php echo HOME;?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo HOME;?>js/jquery.updater.js"></script>

<script type="text/javascript">
setTimeout(function()
{	
	window.location = '<?=HOME?>monitoring.pandu_tunda/';	
},300000);
</script>

<table width="100%" cellspacing="0" align="center" height="81px" border="0" style="margin-top:-14px;">
	<tr align="center">
		<td  class="" align="center">
			<span class="graybrown" align="center">
				<font color="0268AB" align="center" size="+3"> MONITORING</font> <font align="center" size="+3"> RENCANA PERGERAKAN PANDU DAN TUNDA</font>
			</span>
		</td>
	</tr>
</table>

<div style="font-family:arial; font-size:15pt;" align="center">
  <table border='0' cellpadding="1" cellspacing="1" width="100%" >
    <thead>
      <tr align="center">
        <th width="15" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">NO</div></b></th>
        <th width="10%" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Kode PPKB</div></b></th>
        <th width="20%" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Nama Kapal</div></b></th>
        <th width="100" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center"><!--Singkatan<br />-->Nm Agen</div></b></th>
        <th width="60" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">LOA</div></b></th>
        <th width="40" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Pandu Dari</div></b></th>
        <th width="40" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Pandu<br/>Ke</div></b></th>
		<th width="60" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Pandu<br/>Tunda1<br/>Tunda2</div></b></th>
        <th width="140" class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Jadwal</div></b></th>
		<th class="grid-header"><b><div style="font-family:arial; font-size:15pt;" align="center">Keterangan</div></b></th>
      </tr>
    </thead>
	<tr bgcolor="#FFFFFF" height="100%"><td valign="top" colspan="10">
		<marquee scrolldelay="400" height="750" direction="up">
		<table width="100%" border="0" style="table-layout:fixed">
		<?
		$db = getDB();
	    $query = "SELECT KD_PPKB, 
		                 NM_KAPAL, 
						 NM_AGEN, 
						 KP_LOA, 
						 KADE_ASAL, 
						 KADE_TUJUAN, 
						 TGL_JAM_PANDU, 
						 EX_KAPAL, 
						 REMARK_TAMBATAN,
						 M_AWAL_D,
						 M_AKHIR_D,
						 SPK_KD_PANDU,
						 SPK_KD_TUNDA1,
						 SPK_KD_TUNDA2
					FROM KAPAL_PROD.V_MON_RENC_PANDU_TUNDA@PRODLINKX 
					WHERE TO_CHAR(TGL_JAM_PMT_PANDU_D,'yyyymmdd') >= TO_CHAR(sysdate,'yyyymmdd') 
					ORDER BY TGL_JAM_PMT_PANDU_D ASC";

        $result	= $db->query($query);
        $row = $result->getAll();
		
		$no=1;
		foreach($row as $data3)
		{
			if($no == 1)
			{
				$bgcolor2 = "#f9f9f3";
			}
			else if ($no % 2 == 0)
			{
				$bgcolor2 = "#ffffff";
			}
			else
			{
				$bgcolor2 = "#f9f9f3";
			}
			
			if($data3['SPK_KD_PANDU']=="")
			{
				$spk_pandu = "";
			}
			else
			{
				$spk_pandu = "T".$data3['SPK_KD_PANDU'];
			}
			
			if($data3['SPK_KD_TUNDA1']=="")
			{
				$spk_tunda1 = "";
			}
			else
			{
				$spk_tunda1 = $data3['SPK_KD_TUNDA1'];
			}
			
			if($data3['SPK_KD_TUNDA2']=="")
			{
				$spk_tunda2 = "";
			}
			else
			{
				$spk_tunda2 = $data3['SPK_KD_TUNDA2'];
			}
			
	?>  
      <tr align="center" bgcolor="<?=$bgcolor2;?>">
        <td width="45">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$no?></strong></div>
		</td>
        <td width="10%">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['KD_PPKB']?></strong></div>
        </td>
        <td width="20%">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['NM_KAPAL']?><br/></strong></div>
          </td>
        <td width="115">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['NM_AGEN']?><br/></strong></div>
          </td>
        <td width="78">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['KP_LOA']?><br/></strong></div>
          </td>
        <td width="82">
          <div style="font-family:arial; font-size:17;" align="right">
		  <strong><?=$data3['KADE_ASAL']?>  &nbsp; </strong></div> 
          </td>
        <td width="84" align="left">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['KADE_TUJUAN']?>
		  </strong></div>
		  <table>
		  <tr>
		  <td> &nbsp; M.Awal</td><td>: <?=$data3['M_AWAL_D']?></td>
		  </tr>
		  <tr>
		  <td> &nbsp; M.Akhir</td><td>: <?=$data3['M_AKHIR_D']?></td>
		  </tr>
		  </table>		  
        </td>
		<td width="94" align="center">
		  <table>
		  <tr>
		  <td> &nbsp; <font size="3"><b><?=$spk_pandu?></b></font></td>
		  </tr>
		  <tr>
		  <td> &nbsp; <font size="3"><b><?=$spk_tunda1?></b></font></td>
		  </tr>
		  <tr>
		  <td> &nbsp; <font size="3"><b><?=$spk_tunda2?></b></font></td>
		  </tr>
		  </table>		  
        </td>
        <td width="157">
          <div style="font-family:arial; font-size:17;" align="center">
		  <strong><?=$data3['TGL_JAM_PANDU']?><br/><br/></strong></div>
          </td>
		<td align="left">
		  <table>
		  <tr>
		  <td> &nbsp; <b><font size="3">Remark Tambatan</font></b></td><td><b><font size="3">: <?=$data3['REMARK_TAMBATAN']?></font></b></td>
		  </tr>
		  <tr>
		  <td> &nbsp; <b><font size="3">Ex.Kapal</font></b></td><td><b><font size="3">: <?=$data3['EX_KAPAL']?></font></b></td>
		  </tr>		  
		  </table>
		  
        </td>
      </tr>
    <? $no++;
		} ?>
		</table>
		</marquee>
	</tr>	
  </table>
</div>