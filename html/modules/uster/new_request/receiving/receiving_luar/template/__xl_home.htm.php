<?php if ($_SESSION['ID_USER'] != '347') {
?>
	<center>
		<div style="padding-top:7px; padding-bottom:17px; padding-left:15px">
			<font color="red">
				<h1>403 - Access Denied</h1>
			</font>
		</div>
	</center>
<?php
} else {
	if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/document-open.png' border='0' class="icon" />
		<font color="#0378C6"> Request</font> Receiving Dari Luar
	</span><br /><br />
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<center>
			<table style="margin: 30px 30px 30px 30px;" border="0">
				<tr>
					<td class="form-field-caption">No. Request</td>
					<td class="form-field-caption"> : </td>
					<td class="form-field-caption" align="left"><input type="text" size="20" name="No_Req" id="no_req" /></td>
				</tr>
				<tr>
					<td class="form-field-caption">Tanggal Request</td>
					<td class="form-field-caption"> : </td>
					<td class="form-field-caption" align="left"><input type="text" size="20" name="FROM" id="from" /> s/d <input type="text" size="20" name="TO" id="to" /></td>
				</tr>
				<tr>
					<td class="form-field-caption"></td>
					<td class="form-field-caption"></td>
					<td>&nbsp;&nbsp; <a class="link-button" style="height:25" onclick="search_request()"><img src='images/cari.png' border='0' />Cari</a></td>
				</tr>
			</table>
		</center>
	</fieldset>
	<div style="padding-top:7px; padding-left:15px">
		<font color="#0066CC"><a onclick="window.open('<?php echo ($HOME); ?><?php echo ($APPID); ?>/add','_self')" class="link-button" style="height:25"><img src='images/sp2p.png' border="0"> Tambah Request Receiving</a></font>
	</div>
	</div><br />
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<center>
			<div id="request_list" style="border:thin inset; margin: 5px 5px 5px 5px"></div>
		</center>
	</fieldset>
	<script>
		$(function() {

			$("#from").datepicker();
			$("#from").datepicker("option", "dateFormat", "yy-mm-dd");
			$("#to").datepicker();
			$("#to").datepicker("option", "dateFormat", "yy-mm-dd");

			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load('<?= HOME ?><?= APPID ?>/req_list #list', function(data) {});
		});

		function search_request() {
			var from_ = $("#from").val();
			var to_ = $("#to").val();
			var cari_ = "cari";
			var no_req_ = $("#no_req").val();
			var url = "<?= HOME ?><?= APPID ?>/req_list #list";

			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load(url, {
				NO_REQ: no_req_,
				FROM: from_,
				TO: to_,
				CARI: cari_
			}, function(data) {

			});

		}

		function change_page($page) {
			var selected_p = $page;
			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load('<?= HOME ?><?= APPID ?>/req_list?pp=' + selected_p + ' #list', function(data) {});
		}
	</script>
<?php
} ?>