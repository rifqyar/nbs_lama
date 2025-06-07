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
	if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/dokumenbig.png' border='0' class="icon" />
		<font color="#0378C6"> Perencanaan</font> Kegiatan Stuffing
	</span>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<center>
			<table style="margin: 30px 30px 30px 30px;" border="0">
				<tr>
					<td class="form-field-caption">No. Request</td>
					<td class="form-field-caption"> : </td>
					<td class="form-field-caption"><input type="text" name="No_Req" id="no_req" /></td>
				</tr>
				<tr>
					<td class="form-field-caption">Tanggal Request</td>
					<td class="form-field-caption"> : </td>
					<td class="form-field-caption"><input type="text" name="FROM" id="from" /> s/d <input type="text" name="TO" id="to" /></td>
				</tr>
				<tr>
					<td class="form-field-caption" colspan="3" align="right"><a class="link-button" style="height:25" onclick="search_request()"><img src='images/cari.png' border='0' />Cari</a></td>
				</tr>
			</table>
		</center>
	</fieldset>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "> &nbsp;&nbsp;<a href='<?php echo ($HOME); ?><?php echo ($APPID); ?>/add' class="link-button"><img src='images/tambah.png' border="0"> Tambah Perencanaan Stuffing</a>&nbsp;<br /><br /></fieldset>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<center>
			<div id="request_list" style="margin: 5px 5px 5px 5px"></div>
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