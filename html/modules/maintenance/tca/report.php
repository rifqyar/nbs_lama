<style>
	.content {
		width: 95%;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 10px;
		margin-top: 20px;
	}

	.main_side {
		width: 100%;
		float: left;
		text-align: left;
	}

	.rightside {
		width: 25%;
		float: right;
		text-align: center;
	}

	.ganjil {
		background-color: #FFF;
		/* Warna untuk baris ganjil */
	}

	.genap {
		background-color: #bbe3fe;
		/* Warna untuk baris genap */
	}
</style>
<script type="text/javascript">
	$(function() {
		
		$("#vessel").autocomplete({
			minLength: 3,
			source: "<?= HOME ?><?= APPID ?>.auto/master_vessel_praya",
			focus: function(event, ui) {
				$("#vessel").val(ui.item.vessel_name);
				$("#voyage_in").val(ui.item.voyage_in);
				$("#voyage_out").val(ui.item.voyage_out);
				$("#idvsb").val(ui.item.id_vsb_voyage);
				$("#voyage").val(ui.item.voyage);
				return vessel;
			},
			select: function(event, ui) {
				$("#vessel").val(ui.item.vessel_name);
				return false;
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a align='center'>" + item.vessel_name + "<br>" + item.voyage_in + " - " + item.voyage_out + "</a>")
				.appendTo(ul);

			};

	});
	
	// jumlah yg ditampilkan
	var rowNum = "10";
	$(function() {
		jQuery("#container_list").jqGrid({
			url: "<?= HOME ?><?= APPID ?>.ajax/tca_data_praya?terminal=" + $("#terminal").val() + "&voyage=" + $("#voyage").val() + "&page=" + "1" + "&rowNum=" + rowNum,
			mtype: "post",
			datatype: "json",
			colNames: ['No Container', 'Truck ID', 'Police Number', 'Status', 'Container Activity'],
			colModel: [{
					name: 'No_Container',
					index: 'No_Container',
					width: 133,
					align: "center"
				},
				{
					name: 'Truck_ID',
					index: 'Truck_ID',
					width: 133,
					align: "center"
				},
				{
					name: 'Police_Number',
					index: 'Police_Number',
					width: 133,
					align: "center"
				},
				{
					name: 'Status',
					index: 'Status',
					width: 195,
					align: "center"
				},
				{
					name: 'Cont_Activity',
					index: 'Cont_Activity',
					width: 195,
					align: "center"
				}
			],
			width: 835,
			height: "100%",
			loadonce: false,
			rowNum: rowNum,
			rownumbers: true,
			rownumWidth: 15,
			gridview: true,
			pager: '#pg_l_pay',
			viewrecords: true,
			shrinkToFit: false,
			caption: "Truck Container Association List",
			loadComplete: function(data) {
				var $self = $(this);
				if ($self.jqGrid("getGridParam", "datatype") === "json") {
					setTimeout(function() {
						$(this).trigger("reloadGrid"); // Call to fix client-side sorting
					}, 50);
				}
			},
			onPaging: function(pgButton) {
				var $self = $(this);
				var currentPage = $self.jqGrid("getGridParam", "page");
				var url = "<?= HOME ?><?= APPID ?>.ajax/tca_data_praya?terminal=" + $("#terminal").val() + "&voyage=" + $("#voyage").val() + "&page=" + currentPage + "&rowNum=" + rowNum
				$self.jqGrid("setGridParam", { url, datatype: "json" }).trigger("reloadGrid");
			}
		});
		jQuery("#container_list").jqGrid('navGrid', '#pg_l_pay', {
			del: false,
			add: false,
			edit: false,
			search: true
		});
		jQuery("#container_list").jqGrid('filterToolbar', {
			stringResult: true,
			searchOnEnter: true
		});
		$('#container_list').jqGrid('navGrid', '#pg_l_pay', {
			search: true
		}).navButtonAdd('#pg_l_pay', {
			caption: "Export to Excel",
			buttonicon: "ui-icon-disk",
			onClickButton: function() {
				exportGrid("container_list");
			},
			position: "last"
		});
	});

	function show_association() {
		//$("#container_list").html("<p align=center><img src='<?= HOME ?>images/loadingbox.gif' /></p>");
		var terminal = $("#terminal").val();
		var urls = '<?= HOME ?><?= APPID ?>.ajax/tca_data_praya?terminal=' + $("#terminal").val() + '&voyage=' + $("#voyage").val() + '&page=' + "1" + "&rowNum=" + rowNum;
		$("#container_list").jqGrid('setGridParam', {
			url: urls,
			datatype: "json"
		}).trigger("reloadGrid");
	}

	function exportGrid(table) {
		mya = $("#" + table).getDataIDs(); // Get All IDs
		var data = $("#" + table).getRowData(mya[0]); // Get First row to get the
		// labels
		var colNames = new Array();
		var ii = 0;
		for (var i in data) {
			colNames[ii++] = i;
		} // capture col names

		var html = "<html><head>" +
			"<style>" +
			"table.tableList_1 th {border:1px solid black; text-align:center; " +
			"vertical-align: middle; padding:5px;}" +
			"table.tableList_1 td {border:1px solid black; text-align: left; vertical-align: top; padding:5px;}" +
			"</style>" +
			"</head>" +
			"<body style=&quot;page:land;&quot;> " +
			"<table border=1> <tr><td colspan=6 align=center>" + $("#vessel").val() + "<br/>" + $("#voyage_in").val() + " - " + $("#voyage_out").val() + "</td></tr> ";



		for (var k = 0; k < colNames.length; k++) {
			html = html + "<th>" + colNames[k] + "</th>";
		}
		html = html + "</tr>"; // Output header with end of line
		for (i = 0; i < mya.length; i++) {
			html = html + "<tr>";
			data = $("#" + table).getRowData(mya[i]); // get each row
			for (var j = 0; j < colNames.length; j++) {
				html = html + "<td>" + data[colNames[j]] + "</td>"; // output each Row as
				// tab delimited
			}
			html = html + "</tr>"; // output each row with end of line
		}
		html = html + "</table></body></html>"; // end of line at the end
		//alert(html);
		html = html.replace(/'/g, '&apos;');
		var urlexport = "<?= HOME ?><?= APPID ?>.excel_pdf/export_excel?title=tca_export" + $("#idvsb").val() + "&content=" + encodeURIComponent(html);
		window.open(urlexport, '__blank');
		//  var form = "<form name='pdfexportform' action='generategrid' method='post'>";
		//  form = form + "<input type='hidden' name='pdfBuffer' value='" + html + "'>";
		//  form = form + "</form><script>document.pdfexportform.submit();</sc"
		//      + "ript>";
		//  OpenWindow = window.open('', '');
		//  OpenWindow.document.write(form);
		//  OpenWindow.document.close();
	}
</script>

<div class="content">
	<div class="main_side">
		<p>
		<h1> <img src="<?= HOME ?>images/tca_icon.png" height="10%" width="10%" style="vertical-align:middle"> TCA Report</h1>
		</p>
		<div style="text-align:center;margin-bottom:40px;margin-top:40px;margin-left:20px;">
			<table>
				<tr>
					<td>Vessel</td>
					<td>:</td>
					<td><input type="text" id="vessel" name="vessel" /> - <input type="text" id="voyage" name="voyage" size="8" readonly /></td>
				</tr>
				<tr>
					<td>Voyage In/Out</td>
					<td>:</td>
					<td><input type="text" id="voyage_in" name="voyage_in" size="7" readonly /> - <input type="text" id="voyage_out" name="voyage_out" size="7" readonly /></td>
				</tr>
				<tr>
					<td>ID VSB Voyage</td>
					<td>:</td>
					<td><input type="text" id="idvsb" name="idvsb" readonly /></td>
				</tr>
				<tr>
					<td>Tujuan</td>
					<td>:</td>
					<td><select id="terminal">
							<option value="ALL">--All--</option>
							<option value="OUT">Keluar</option>
							<option value="USTER">Uster</option>
						</select></td>
				</tr>
				<tr>
					<td align="left"><input type="button" id="bt_search" value="Search" title="Search" onclick="show_association()" /></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<table id="container_list"> </table>
			<div id='pg_l_pay'></div>
		</div>

	</div>
</div>