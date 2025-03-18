<?php
	$db=getDb();
	$nota=$_GET['idn'];
	
	$query1="/* Formatted on 02-May-14 2:03:52 PM (QP5 v5.163.1008.3004) */
					SELECT a.no_nota,
						   a.no_request,
						   a.cust_name,
						   TO_CHAR (a.kredit, '999,999,999,999') kredit,
						   b.JENIS,
						   b.VESSEL,
						   b.VOYIN,
						   b.VOYOUT
					  FROM TTH_NOTA_ALL2 a,
						   (SELECT id_req no_req,
								   'ANNE' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_RECEIVING_H
							UNION
							SELECT id_req no_req,
								   'SP2' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_DELIVERY_H
							UNION
							SELECT id_request no_req,
								   'HICO' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_HICOSCAN
							UNION
							SELECT id_req no_req,
								   'TRANS' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_TRANSHIPMENT_H
							UNION
							SELECT id_request no_req,
								   'BH' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM BH_REQUEST
							UNION
							SELECT id_req no_req,
								   'REEX' JENIS,
								   VESSEL,
								   VOYAGE_IN VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_STACKEXT_H
							UNION
							SELECT id_req no_req,
								   'RXP' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_DT VOYOUT
							  FROM REQ_REEXPORT_H) b
					 WHERE TRIM (a.NO_NOTA) = TRIM ('".$nota."')
						   AND TRIM (a.no_request) = TRIM (b.no_req)";
		//print_r($query);die;
		$rs1 	= $db->query($query1);
		$row1 	= $rs1->fetchRow();
		$nonota = $row1['NO_NOTA'];
		$noreq  = $row1['NO_REQUEST'];
		$cust_name = $row1['CUST_NAME'];
		$kredit    = $row1['KREDIT'];
		$vessel    = $row1['VESSEL'];
		$voyin     = $row1['VOYIN'];
		$voyout    = $row1['VOYOUT'];
		$jenis     = $row1['JENIS'];

	
?>
<script>
function via()
{
	$('#vias').load("<?=HOME?>billing.cetaknota.ajax/via").dialog({modal:true, height:120,width:220, title : "Via Payment"});
}
function set_via(i)
{
	$('#via').val(i);
	$('#vias').dialog('destroy').remove();
	$('#mainform2').append('<div id="vias"></div>');
}

function save_payment()
{
	var nonota_ = '<?=$nonota?>';
	var noreq_  = '<?=$noreq?>';
	var jenis_  = '<?=$jenis?>';
	var vessel_ = '<?=$vessel?>';
	var voyin_  = '<?=$voyin?>';
	var voyout_ = '<?=$voyout?>';
	var via_ 	= $('#via').val();
	var cms_    = $('#cms').val();
	var url="billing.cetaknota.ajax/save_payment";
	
	if(via=='')
	{
		alert ('Please choose payment via');
		return false;
	}
	else
	{
		
		question = confirm("data akan ditransfer, cek apakah data sudah benar?")
		if (question != "0") {
			$.blockUI({ message: '<h1><br>Please wait... Transfer Payment</h1><br><img src=images/loadingbox.gif /><br><br>' });
			$.post(url,{IDN: nonota_,IDR:noreq_, VIA:via_, JENIS:jenis_, CMS:cms_, VESSEL:vessel_, VOYIN : voyin_, VOYOUT:voyout_},function(data){	
					alert(data);
					if (jenis_ == 'ANNE'){
						window.open("<?=HOME?>billing.receiving.print/print_nota_lunas?no_req="+noreq_,'_blank');
					} else if (jenis_=='SP2'){	
						window.open("<?=HOME?>billing.delivery.print/print_nota_lunas?no_req="+noreq_,'_blank');
					} else if (jenis_=='HICO'){
						window.open("<?=HOME?>print/print_nota_hicoscan?pl="+nonota_,'_blank');
					} else if (jenis_=='TRANS'){
						window.open("<?=HOME?>print/print_nota_transhipment?pl="+nonota_,'_blank');
					} else if (jenis_=='BH'){
						window.open("<?=HOME?>print/print_nota_behandle?pl="+nonota_,'_blank');
					} else if (jenis_=='REEX'){
						window.open("<?=HOME?>print/print_nota_hicoscan?pl="+nonota_,'_blank');
					} else if (jenis_=='RXP'){
						window.open("<?=HOME?>billing.perp_export.print/print_nota_lunas?no_req="+noreq_,'_blank');
					}		
			});
		}
	}
}


</script>

	<table>
			<tr>
				<td>No. Nota</td>
				<td> : </td>
				<td><b><?=$nonota?></b></td>
			</tr>
			<tr>
				<td>No. Request</td>
				<td> : </td>
				<td><b><?=$noreq?></b></td>
			</tr>
			<tr>
				<td>Jenis Nota</td>
				<td> : </td>
				<td><b><?=$jenis?></b></td>
			</tr>
			<tr>
				<td>Nama Customer</td>
				<td> : </td>
				<td><b><?=$cust_name?></b></td>
			</tr>
			<tr>
				<td>Vessel/Voyage</td>
				<td> : </td>
				<td><b><?=$vessel?>/<?=$voyin?>-<?=$voyout?></b></td>
			</tr>
			<tr>
				<td>Total</td>
				<td> : </td>
				<td><b><?=$kredit?></b></td>
			</tr>
			<tr>
				<td>CMS</td>
				<td> : </td>
				<td>
					<select id='cms'>
					<option value='0'>Non CMS</option>
					<option value='1'>CMS</option>
					</select></td>
			</tr>
			<tr>
				<td>Paid Via</td>
				<td> : </td>
				<td>
					<!--<input type="text" size="15" id="via" onclick="via()"/>-->
					<select id="via">
			<?php
				$query="select BANK_ACCOUNT_ID, RECEIPT_ACCOUNT from xpi2_ar_receipt_method_v where kode_cabang = 'TPK' AND CURRENCY_CODE='IDR'";
				$rs = $db->query($query);
				while($row = $rs->fetchRow()) {
			?>
					<option value="<?=$row['BANK_ACCOUNT_ID']?>"><?=$row['RECEIPT_ACCOUNT']?></option>
			<?		
				}
			?>		
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>
					<button onclick="save_payment()"> P A I D</button>
				</td>
			</tr>

			</table>
			<form id="mainform2">
				<div id='vias'></div>
				
		</form>
