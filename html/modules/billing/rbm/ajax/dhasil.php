<?php
	$id_vsb=$_GET['id'];
	
	//echo $id_vsb;die;
	$db=getDb();
	$qdt="select id_kategori from bil_stv_dtmp where id_vsb_voyage='$id_vsb' group by id_kategori order by id_kategori";

	$rdt=$db->query($qdt);
	
	$gdt=$rdt->getAll();
?>
<script>
	function drh(a,b)
	{
		window.open('<?=HOME;?>billing.rbm.ajax/print_rbm_ktg?id='+b+'&kategori='+a,'_blank'); 
		// <- This is what makes it open in a new window.
	}

	function pranota(a,b,cust_no)
	{
       
        if(confirm('Perhatian: \n 1. Tanggal cetak pranota menjadi acuan tanggal kurs. \n 2. Nominal Kurs pada pranota akan berubah mengikuti tanggal cetak pranota. \nApakah anda yakin untuk mencetak pranota ini? ')){
        	if($("#trade_type").val() == "O"){
				if(cust_no=='')
				{  
					$('#dialog_ukk').load("<?=HOME?>billing.rbm.ajax/dialog_ukk?id="+b+"&id_vsb_voyage=<?=$id_vsb;?>&kategori="+a).dialog({modal:true, height:280,width:520,title: 'Set Generate UKK'});
				}
				else
				{
					window.open('<?=HOME;?>billing.rbm.ajax/print_pranota_rbm_new?id='+b+'&kategori='+a+'&kd_pel='+cust_no,'_blank'); 
				}
				
        	}
        	else {
           		//window.open('<?=HOME;?>billing.rbm.ajax/print_pranota_rbm_new?id='+b+'&kategori='+a,'_blank'); 
				window.open('<?=HOME;?>billing.rbm.ajax/print_pranota_rbm_new?id='+b+'&kategori='+a+'&kd_pel='+cust_no,'_blank'); 
           	}
       	}
		// <- This is what makes it open in a new window.
	}
	
	function drhBaru(a,b)
	{
		window.open('<?=HOME;?>billing.rbm.ajax/print_rbm_ktgbaru?id='+b+'&kategori='+a,'_blank'); 
		// <- This is what makes it open in a new window.
	}

	function final_rbm(a)
	{
		alert(a);
		var url='<?=HOME?>billing.rbm.ajax/final'
		$.post(url,{NO_UKK:a},function(data){
			//alert(data);
			if (data=='success')
			{
				alert(data);
				$("#booking").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data?q=rbm', datatype:"json"}).trigger("reloadGrid");
				close_box();		
				
			}
            else if(data=='failed'){
                alert('Belum cetak pranota. Mohon cetak pranota terlebih dahulu');
            }
		});
		
	}
</script>

<p align="left"><b><i>Report Stevedoring Result</i></b></p>
<div id="dialog_ukk"></div>
<table border="1" >
	<tr>
		<th class="grid-header"><b>No.</b></th>
		<th class="grid-header" width=50><b>Content</b></th>
		<th class="grid-header" width=50><b>Pranota</b></th>
		<th class="grid-header" width=50><b>Kategori</b></th>
		<th class="grid-header" width=150><b>Operator</b></th>
	</tr>
		
	<?php 
	$no=1;
	foreach($gdt as $row)
	{
		$kategori=$row['ID_KATEGORI'];
		$qu 	= "select CUST_NO
					from bil_pnstv_h
					where id_vsb_voyage='$id_vsb' and sts_rpstv='$kategori'";

		$resop   = $db->query($qu);
		$resopid = $resop->fetchRow();
		$ukkict = $resopid['ICTUKK'];	
		$cust_no = $resopid['CUST_NO'];							
	?>
	<tr>
		<td align="center"><?=$no;?></td>
		<td align="center"><img src='images/printer.png' width='15' title='cetak form lama' onclick="drh('<?=$row['ID_KATEGORI'];?>','<?=$id_vsb;?>')" /> <img src='images/printx1.png' width='15' title='cetak form baru' onclick="drhBaru('<?=$row['ID_KATEGORI'];?>','<?=$id_vsb;?>')" /></td>
		<td align="center"><img src='images/printer.png' width='15' title='Cetak Pranota' onclick="pranota('<?=$row['ID_KATEGORI'];?>','<?=$id_vsb;?>','<?=$cust_no;?>')" /> </td>
		<td align="center"><?=$row['ID_KATEGORI'];?></td>
		<td align="center"><?
			$q 	= "select OPERATOR_ID, OPERATOR_NAME
				FROM bil_stv_list
				where id_vsb_voyage='$id_vsb' and id_kategori='$kategori' 
				UNION 
				select OPERATOR_ID, OPERATOR_NAME
				FROM bil_stv_sf
				where id_vsb_voyage='$id_vsb' and id_kategori='$kategori' group by OPERATOR_ID, OPERATOR_NAME order by operator_id
				
				";

$resop   = $db->query($q);
$resopid      = $resop->getAll();

$opid='';
$nox=0;
foreach($resopid as $row)
{
	if($nox=='0')
	{
		$opid=$opid.$row['OPERATOR_ID'].' ['.$row['OPERATOR_NAME'].']';
	}
	else
	{
		$opid=$opid.' & '.$row['OPERATOR_ID'].' ['.$row['OPERATOR_NAME'].']';
	}
	$nox++;
}
echo $opid;
		?></td>
	</tr>
	<?php $no++;}?>
</table>
<br>
<p align="center"><button onclick="final_rbm('<?=$id_vsb;?>')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;F I N A L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></p>
<br>
<hr width="420" />
<br>
<div id="dialogukk"></div>														 