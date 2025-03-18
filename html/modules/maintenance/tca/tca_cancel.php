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

	.grey-bg {
		background-color: #D3D3D3;
	}

	.white-bg {
		background-color: #FFF;
	}

	.add-form {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		gap: 1em;
	}

	.add-item {
		display: grid;
		gap: 0.5em;
	}

	.hidden {
		display: none;
	}

	.styleTable {
		border-collapse: separate;
	}

	.styleTable TD {
		font-weight: normal !important;
		padding: .4em;
		border-top-width: 0px !important;
	}

	.styleTable TH {
		text-align: center;
		padding: .8em .4em;
	}

	.styleTable TD.first,
	.styleTable TH.first {
		border-left-width: 0px !important;
	}

	.loader {
		background: url(images/loading_green.gif);
		background-repeat: no-repeat;
		background-position: right;
	}

	#Table1 {
		margin-left: auto;
		margin-right: auto;
	}
</style>
<script type="text/javascript">
	var container_list = [];
	var container_list_auto = [];
	var container_list_assoc = [];
	$(function() {
		$('#trx_number').on('input', function() {
			var trx_number = $('#trx_number').val();
			if (trx_number.length >= 3) {
				$("#trx_number").autocomplete({
					minLength: 7,
					source: function(request, response) {
						$("#load_error_request").empty();
						$("#load_error_request").append("<img src='<?= HOME ?>images/animated_loading.gif' />");
						$.getJSON("<?= HOME ?><?= APPID ?>.auto/invoice_number_praya", {
							term: $("#trx_number").val()
						}, function(invoice) {
							if (invoice[0] && invoice[0].NO_REQUEST) {
								$.getJSON("<?= HOME ?><?= APPID ?>.auto/clist_by_cont_praya", {
									NO_REQ: invoice[0].NO_REQUEST,
									CANCEL: "Y"
								}, function(container) {
									if (container && container.length) {
										container_list_auto = container.map(e => {
											return {
												label: e.containerNo,
												value: e
											}
										});
										$("#cont_number").removeAttr("readonly");
										$("#cont_number").removeClass("grey-bg");
										$("#cont_number").addClass("white-bg");
										$("#load_error_request").empty();
									} else {
										$("#load_error_request").empty();
										$("#load_error_request").append("<span>  No data found</span>");
									}
									return false;
								});
							} else {
								$("#load_error_request").empty();
								$("#load_error_request").append("<span>  No data found</span>");
							}
						});
					},
				})
			}
		});

		$('#cont_number').on('input', function() {
			var cont_number = $('#cont_number').val();
			$("#cont_number").autocomplete({
				source: container_list_auto,
				focus: function(event, ui) {
					$("#cont_number").val(ui.item.containerNo);
					return false;
				},
				select: function(event, ui) {
					container_list = [];
					container_list_assoc = [];
					var listCombo = [];
					$("#cont_number").val(ui.item.containerNo);
					if (ui.item.listCombo) {
						listCombo = ui.item.listCombo.split(",").map(e => e.trim())
					}
					if (listCombo.length > 0) {
						listCombo.forEach(e => {
							container_list_auto.forEach(auto => {
								if (e == auto.label) container_list.push(auto.value)
								if (e == auto.label && e != ui.item.containerNo) container_list_assoc.push(auto)
							})
						})
					} else {
						container_list.push(ui.item);
					}
					show_selected_cont(ui.item);
					return false;
				}
			}).data("autocomplete")._renderItem = function(ul, item) {
				return $("<li></li>")
					.data("item.autocomplete", item.value)
					.append("<a align='center'>" + item.value.containerNo + "<br>" + item.value.requestNumber + "</a>")
					.appendTo(ul);
			};
		})

		$.fn.styleTable = function(options) {
			var defaults = {
				css: 'styleTable'
			};
			options = $.extend(defaults, options);

			return this.each(function() {

				input = $(this);
				input.addClass(options.css);

				input.find("tr").live('mouseover mouseout', function(event) {
					if (event.type == 'mouseover') {
						$(this).children("td").addClass("ui-state-hover");
					} else {
						$(this).children("td").removeClass("ui-state-hover");
					}
				});

				input.find("th").addClass("ui-state-default");
				input.find("td").addClass("ui-widget-content");

				input.find("tr").each(function() {
					$(this).children("td:not(:first)").addClass("first");
					$(this).children("th:not(:first)").addClass("first");
				});
			});
		};

	});

	function show_selected_cont($cont_data) {
		$("#add_cont_detail").removeClass("hidden");
		$("#add_container_number").val($cont_data.containerNo);
		$("#add_request_number").val($cont_data.requestNumber);
		$("#add_container_size").val($cont_data.containerSize);
		$("#add_isocode").val($cont_data.isoCode);
		$("#add_pod").val($cont_data.pod);
		$("#add_weight").val($cont_data.weight);
		$("#add_truck_id").val($cont_data.truckId);
		$("#add_truck_number").val($cont_data.truckNumber);
		$("#add_truck_type").val($cont_data.truckType);
	}

	function add_new_cont() {
		if (container_list_assoc.length > 0) {
			var question = confirm(`Container ini sudah pernah dilakukan TCA dengan container : ${container_list_assoc.map(e => e.label).join(", ")} \nApakah ingin melanjutkan?`);
			if (question == "0") {
				return
			}
		}

		load_contlist();
	}

	function load_contlist() {
		var tableTD = ``
		container_list.forEach((element, index) => {
			tableTD += `
			<tr id='content_table_${index}'>
				<td><input type='text' id='container_number_${index}' value='${element.containerNo}' readonly /></td>
				<td><input type='text' id='request_number_${index}' value='${element.requestNumber}' readonly /></td>
				<td><input type='text' id='container_size_${index}' value='${element.containerSize}' readonly /></td>
				<input type='hidden' id='trailer_type_${index}' value='${element.trailerType}' />
				<td><input type='text' id='truck_id_${index}' value='${element.truckId}' readonly /></td>
				<td><input type='text' id='truck_number_${index}' value='${element.truckNumber}' readonly /></td>
				<input type="hidden" id="isocode_${index}" value='${element.isoCode}' />
				<input type="hidden" id="pod_${index}" value='${element.pod}' />
				<input type="hidden" id="weight_${index}" value='${element.weight}' />
			</tr>
			`
		});

		var tableHTML = `
		<table id='Table1' class='full'>
			<tr id='header_table'>
				<th>Container Number</th>
				<th>Request Number</th>
				<th>Container Size</th>
				<th>Truck ID</th>
				<th>Police Number</th>
			</tr>
			${tableTD}
			<tr>
				<td colspan='6'>
						<div id="save_bt_c">
								<input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" width="100" />
						</div>
				</td>
			</tr>
		</table>
		`;

		if (!$("#Table1").length) {
			$("#container_detail").empty();
			$("#container_detail").append(tableHTML);
		} else {
			$("#container_detail").empty();
			$("#container_detail").append(tableHTML);
		}

		$("#Table1").styleTable();

		var save_button = document.querySelector('#save_bt');
		save_button.addEventListener("click", function(event) {
			save_cont();
		})

		if (!container_list.length) {
			$("#container_detail").empty();
		}
	}

	function save_cont() {
		var question = confirm(`Apakah yakin ingin melakukan cancel TCA?`);
		if (question == "0") {
			return
		}
		var url = "<?= HOME ?>maintenance.tca.ajax/save_associate_praya";
		var detail = container_list.map(e => {
			return {
				requestNumber: e.requestNumber,
				containerNo: e.containerNo,
				pod: e.pod,
				weight: e.weight,
				isoCode: e.isoCode
			}
		})
		var payload = {
			truckType: container_list[0].truckType,
			truckNumber: container_list[0].truckNumber,
			createdBy: "Admin Uster",
			tid: container_list[0].truckId,
			axle: container_list[0].trailerType,
			type: "OEC",
			actionCode: "D",
			detail
		}

		$("#save_bt_c").html("<img src='images/animated_loading.gif'/>");
		$.post(url, payload, function(data) {
			// console.log(data)
			xdata = JSON.parse(data);
			console.log(xdata);
			// General Error & Tidak ke hit
			if (xdata.code == 0 && xdata.msg && !xdata.dataRec) {
				alert(xdata.msg);
				$("#save_bt_c").html('<input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" width="100"/>');
				return;
			}
			// Jika Masuk
			alert(xdata.msg)

			if (xdata.code == 1) {
				$("#save_bt_c").html(`<p style="font:12pt bold;color:red">${xdata.msg}</p>`);
			} else {
				$("#save_bt_c").html(`<div><p style="font:12pt bold;color:green">${xdata.msg}</p><input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" width="100"/></div>`);
			}
		});
	}
</script>

<div class="content">
	<div class="main_side">
		<p>
		<h2> <img src="<?= HOME ?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Cancel Association</h2>
		</p>
		<div style="text-align:center;margin-bottom:40px;margin-top:40px;">
			<label style="font-size:15pt;">No. Request : </label><input type="text" name="trx_number" id="trx_number" size="14" style="background-color:#FFF;text-align:center;height:30px;font-size:20pt;" />
			<span id="load_error_request"></span>
			<br />
			<br />
			<label style="font-size:15pt;">No. Container : </label><input type="text" name="cont_number" id="cont_number" size="14" class="grey-bg" style="text-align:center;height:30px;font-size:20pt;" readonly />
			<br />
			<br />
			<div id="add_cont_detail" style="width:100%;" class="hidden">
				<form class="add-form" method="post" id="add_form" action="">
					<div class="add-item">
						<div>Container Number</div>
						<input type="text" name="container_number" id="add_container_number" readonly />
					</div>
					<div class="add-item">
						<div>Request Number</div>
						<input type="text" name="request_number" id="add_request_number" readonly />
					</div>
					<div class="add-item">
						<div>Container Size</div>
						<input type="text" name="container_size" id="add_container_size" readonly />
					</div>
					<div class="add-item">
						<div>Truck ID</div>
						<input type="text" name="truck_id" id="add_truck_id" readonly />
					</div>
					<div class="add-item">
						<div>Truck Number</div>
						<input type="text" name="truck_number" id="add_truck_number" readonly />
					</div>
					<div class="add-item">
						<div></div>
						<input style=" font:12pt bold" type="button" id="add_bt" value="Add" class="link-button" onclick="add_new_cont()" width="100" />
					</div>
					<input type="hidden" id="add_trailer_type" value="40FT4A" />
					<input type="hidden" id="add_truck_type" />
					<input type="hidden" id="add_isocode" />
					<input type="hidden" id="add_pod" />
					<input type="hidden" id="add_weight" />
				</form>
			</div>
			<br />
			<br />

			<div id="container_detail" style="width:100%">
			</div>

		</div>

	</div>
</div>