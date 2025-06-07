<?php //if ($_SESSION['ID_USER'] != '347') {
?>
	<!-- <center>
		<div style="padding-top:7px; padding-bottom:17px; padding-left:15px">
			<font color="red">
				<h1>403 - Access Denied</h1>
			</font>
		</div>
	</center> -->
<?php
//} else {
	if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/sp2_p.png' border='0' class="icon" />
		<font color="#0378C6"> Request Perpanjangan</font> Stripping
	</span><br /><br />
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<form id="searchForm" name="searchForm" action="<?= HOME ?><?= APPID ?>" method="POST">
			<div style="padding:10px;font-family:Arial; font-size:8pt; color:#555555; font-weight:bold">Pencarian No Request : <input type="text" id="NO_REQ" name="NO_REQ" /><br /> Tgl Request Stripping &nbsp;&nbsp;: <input type="text" name="FROM" id="from" /> S/D <input type="text" name="TO" id="to" /> &nbsp;&nbsp; <input type="hidden" name="cari" value="search" /><a id="searchButton" onclick="search_request()" class="link-button" style="height:25"><img src='images/cari.png' border='0' />Cari</a><br /></div>
		</form>
	</fieldset>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
		<center>
			<div id="request_list" style="margin: 5px 5px 5px 5px"></div>
		</center>
	</fieldset>
	<script>
		$(function() {

			$("#from").datepicker();
			$("#from").datepicker("option", "dateFormat", "dd/mm/yy");
			$("#to").datepicker();
			$("#to").datepicker("option", "dateFormat", "dd/mm/yy");

			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load('<?= HOME ?><?= APPID ?>/req_list #list', function(data) {});
		});

		function search_request() {
			var from_ = $("#from").val();
			var to_ = $("#to").val();
			var no_req_ = $("#NO_REQ").val();
			var cari_ = "cari";

			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load("<?= HOME ?><?= APPID ?>/req_list #list", {
				from: from_,
				to: to_,
				cari: cari_,
				no_req: no_req_
			}, function(data) {
				console.log(data);
			});

		}

		function change_page($page) {
			var selected_p = $page;

			$('#request_list').html('<p align=center><img src=<?php echo ($HOME); ?>images/loadingbox.gif /></p>');
			$("#request_list").load('<?= HOME ?><?= APPID ?>/req_list?pp=' + selected_p + ' #list', function(data) {});
		}
	</script>
<?php
//} ?>