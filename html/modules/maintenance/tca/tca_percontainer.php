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
									NO_REQ: invoice[0].NO_REQUEST
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
					$("#cont_number").val(ui.item.containerNo);
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

		$("#add_truck_id").autocomplete({
			minLength: 3,
			source: "<?= HOME ?>maintenance.tca.auto/truckid_praya",
			focus: function(event, ui) {
				$(this).val(ui.item.tid);
				$(this).removeClass('loader');
				return false;
			},
			select: function(event, ui) {
				$(this).val(ui.item.tid);
				$("#add_truck_number").val(ui.item.truckNumber);
				$("#add_truck_type").val(ui.item.truckType);
				return false;
			},
			search: function(e, u) {
				$(this).addClass('loader');
			},
			response: function(e, u) {
				$(this).removeClass('loader');
			}
		}).data("autocomplete")._renderItem = function(ul, item) {
			return $("<li></li>")
				.data("item.autocomplete", item)
				.append("<a align='center'>" + item.tid + " - " + item.truckNumber + " <br>" + item.company + "</a>")
				.appendTo(ul);
		};

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

		$("#Table1").styleTable();

	});

	function show_selected_cont($cont_data) {
		$("#add_cont_detail").removeClass("hidden");
		$("#add_container_number").val($cont_data.containerNo);
		$("#add_request_number").val($cont_data.requestNumber);
		$("#add_container_size").val($cont_data.containerSize);
		$("#add_isocode").val($cont_data.isoCode);
		$("#add_pod").val($cont_data.pod);
		$("#add_weight").val($cont_data.weight);
		$("#add_truck_id").val("");
		$("#add_truck_number").val("");
		$("#add_truck_type").val("");
	}

	function load_contlist() {
		var tableTD = ``
		container_list.forEach((element, index) => {
			tableTD += `
			<tr id='content_table_${index}'>
				<td><input type='text' id='container_number_${index}' value='${element.container_no}' readonly /></td>
				<td><input type='text' id='request_number_${index}' value='${element.request_number}' readonly /></td>
				<td><input type='text' id='container_size_${index}' value='${element.container_size}' readonly /></td>
				<input type='hidden' id='trailer_type_${index}' name='trailer_type' value='40FT4A' />
				<td><input type='text' id='truck_id_${index}' value='${element.truck_id}' readonly /></td>
				<td><input type='text' id='truck_number_${index}' value='${element.truck_number}' readonly /></td>
				<input type="hidden" id="isocode_${index}" value='${element.isocode}' />
				<input type="hidden" id="pod_${index}" value='${element.pod}' />
				<input type="hidden" id="weight_${index}" value='${element.weight}' />
				<td colspan='6'><input style='font:12pt bold' type='button' id='delete_bt_${index}' value='Delete' class='link-button' width='100'/></td>
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

		var list_button = document.querySelectorAll('[id^=delete_bt_]');
		list_button.forEach(function(item, index) {
			item.addEventListener("click", function(event) {
				delete_cont(index);
			});
		});

		var save_button = document.querySelector('#save_bt');
		save_button.addEventListener("click", function(event) {
			save_cont();
		})

		if (!container_list.length) {
			$("#container_detail").empty();
		}
	}

	function add_new_cont() {
		if (!$("#add_truck_id").val()) {
			alert("TRUCK ID TIDAK BOLEH KOSONG");
			return
		}
		if (container_list.length == 1 && ($("#add_container_number").val() == container_list[0].container_no)) {
			alert("NO CONTAINER SAMA SILAHKAN CHECK KEMBALI");
			return
		}
		if (container_list.length == 1 && ($("#add_truck_id").val() != container_list[0].truck_id)) {
			alert("TIDAK BISA MELAKUKAN TCA COMBO SILAHKAN CHECK TRUCK ID");
			return
		}
		if (container_list.length == 2) {
			alert("TIDAK BISA MELAKUKAN TCA COMBO LEBIH DARI 2 CONTAINER");
			return
		}

		var cont = {
			container_no: $("#add_container_number").val(),
			request_number: $("#add_request_number").val(),
			container_size: $("#add_container_size").val(),
			isocode: $("#add_isocode").val(),
			pod: $("#add_pod").val(),
			weight: $("#add_weight").val(),
			truck_id: $("#add_truck_id").val(),
			truck_number: $("#add_truck_number").val(),
			truck_type: $("#add_truck_type").val(),
			trailer_type: $("#add_trailer_type").val(),
		};

		container_list.push(cont);

		load_contlist(container_list);
	}

	function delete_cont($idx) {
		var new_arr = [];
		container_list.forEach((element, index) => {
			if (index != $idx) {
				new_arr.push(element)
			}
		});
		container_list = new_arr;

		load_contlist(container_list);
	}

	function save_cont() {
		var question = confirm(`Apakah yakin ingin melakukan TCA?`);
		if (question == "0") {
			return
		}
		var url = "<?= HOME ?>maintenance.tca.ajax/save_associate_praya";
		var detail = container_list.map(e => {
			return {
				requestNumber: e.request_number,
				containerNo: e.container_no,
				pod: e.pod,
				weight: e.weight,
				isoCode: e.isocode
			}
		})
		var payload = {
			truckType: container_list[0].truck_type,
			truckNumber: container_list[0].truck_number,
			createdBy: "Admin Uster",
			tid: container_list[0].truck_id,
			axle: container_list[0].trailer_type,
			type: "OEC",
			actionCode: "R",
			detail
		}

		$("#save_bt_c").html("<img src='images/animated_loading.gif'/>");

		$.post(url, payload, function(data) {
			console.log(data)
			xdata = JSON.parse(data);
			// console.log(xdata);
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
		<h1> <img src="<?= HOME ?>images/tca_icon.png" height="10%" width="10%" style="vertical-align:middle"> TID & Container Association</h1>
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
						<input type="text" name="truck_id" id="add_truck_id" />
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
</div>