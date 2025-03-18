<?php
require_lib('praya.php');
$db = getDB();
$id_req = $_POST['NO_REQ'];
$jn_req = substr($id_req, 0, 2);

// $trailer_type = get_trailer_type();
$contlist = get_tca_container($id_req);
$jum = count($contlist);

function get_tca_container($id_request)
{
    set_time_limit(360);

    try {
        $payload = array(
            "idRequest" => $id_request,
            "orgId" => PRAYA_ITPK_PNK_ORG_ID,
            "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID
        );

        $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/getRequestTca", 'POST', getTokenPraya());
        $response = json_decode($response['response'], true);

        if ($response['code'] == 1 && !empty($response["dataRec"])) {
            return $response['dataRec'];
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

// function get_trailer_type()
// {
//     set_time_limit(360);

//     try {
//         $payload = array(
//             "search" => "TRAILERTYPE",
//             "terminalCode" => PRAYA_ITPK_PNK_TERMINAL_CODE
//         );

//         $response = sendDataFromUrl($payload, PRAYA_API_MASTER . "/api/commonCodeList", 'POST', getTokenPraya());
//         $response = json_decode($response['response'], true);

//         if ($response['code'] == 1 && !empty($response["dataRec"])) {
//             return $response['dataRec'][0]['detailList'];
//         }
//     } catch (Exception $ex) {
//         echo $ex->getMessage();
//     }
// }
// 
?>

<div id="contlist">
    <script type="text/javascript">
        (function($) {
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
        })(jQuery);

        $(document).ready(function() {
            $("#Table1").styleTable();
        });
    </script>
    <style>
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
    <?php if (count($contlist) > 0) { ?>
        <form method="post" id="formsubmit" action="<?= $HOME ?>maintenance.tca.ajax/save_associate">
            <table id='Table1'>
                <tr>
                    <th>NO</th>
                    <th>NO CONTAINER</th>
                    <th>SIZE</th>
                    <!-- <th>TRAILER TYPE / AXLE</th> -->
                    <th>TRUCK ID</th>
                    <th>POLICE NUMBER</th>
                    <th>REMARKS</th>
                </tr>
                <?php $i = 0;
                $f = 0;
                foreach ($contlist as $cl) { ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= $cl['containerNo'] ?>
                            <input type="hidden" id="nocont_<?= $i ?>" name="nocont[]" value="<?= $cl['containerNo'] ?>" />
                        </td>
                        <td><input type="text" id="size_<?= $i ?>" value="<?= $cl['containerSize'] ?>" readonly /></td>
                        <!-- <td>
                            <select name="trailertype[]" id="trailertype_<?php //$i 
                                                                            ?>">
                                <?php
                                // foreach ($trailer_type as $tt) {
                                //     echo "<option value=" . $tt['value2'] . ">" . $tt['value'] . "</option>";
                                // } 
                                ?>
                            </select>
                        </td> -->
                        <input type="hidden" id="trailertype_<?= $i ?>" value="40FT4A" />
                        <td><input type="text" name="truckid[]" id="truckid_<?= $i ?>" class="truckidclass" /></td>
                        <td><input type="text" name="trucknum_<?= $i ?>" id="trucknum_<?= $i ?>" readonly />
                            <input type="hidden" id="total" name="total" value="<?= $jum ?>" />
                            <input type="hidden" id="id_req" name="id_req" value="<?= $id_req ?>" />
                            <input type="hidden" name="trucktype_<?= $i ?>" id="trucktype_<?= $i ?>" />
                            <input type="hidden" id="isocode_<?= $i ?>" value="<?= $cl['isoCode'] ?>" />
                            <input type="hidden" id="pod_<?= $i ?>" value="<?= $cl['pod'] ?>" />
                            <input type="hidden" id="weight_<?= $i ?>" value="<?= $cl['weight'] ?>" />
                        </td>
                        <td>
                            <div id="message_<?= $i ?>"></div>
                        </td>
                    </tr>
                <?php $i++;
                    $f++;
                } ?>
                <tr>
                    <td colspan='6'>
                        <div id="save_bt">
                            <input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" onclick="save_associate()" width="100" />
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    <?php } else {
        echo "No Data Found";
    } ?>
    <script>
        <?php $i = 0;
        foreach ($contlist as $cl) { ?>
            $("#truckid_<?= $i ?>").autocomplete({
                minLength: 3,
                source: "<?= HOME ?>maintenance.tca.auto/truckid_praya",
                focus: function(event, ui) {
                    $("#truckid_<?= $i ?>").val(ui.item.tid);
                    $(this).removeClass('loader');
                    return false;
                },
                select: function(event, ui) {
                    $("#truckid_<?= $i ?>").val(ui.item.tid);
                    $("#trucknum_<?= $i ?>").val(ui.item.truckNumber);
                    $("#trucktype_<?= $i ?>").val(ui.item.truckType);
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
        <?php $i++;
        } ?>

        function save_associate() {
            var question = confirm(`Apakah yakin ingin melakukan TCA?`);
            if (question == "0") {
                return
            }
            var url = "<?= HOME ?>maintenance.tca.ajax/save_associate_praya";
            var request_number = $('#id_req').val();
            var idx = 0;
            var tids = [];
            var created_by = "Admin Uster";
            var action_code = "R";
            var datas = [];
            var id_req = $('#id_req').val();
            $.each($('[id^=nocont_]'), function(idx, item) {
                var cont_no = $(this).val();
                var iso_code = $("#isocode_" + idx).val();
                var pod = $("#pod_" + idx).val();
                var weight = $("#weight_" + idx).val();
                var size = $("#size_" + idx).val();
                var truck_number = $("#trucknum_" + idx).val();
                var truck_id = $("#truckid_" + idx).val();
                tids[idx] = truck_id;
                var truck_type = $("#trucktype_" + idx).val();
                var axle = $("#trailertype_" + idx).val();

                datas.push({
                    index: idx,
                    no_container: cont_no,
                    iso_code,
                    pod,
                    weight,
                    size,
                    truck_number,
                    tid: truck_id,
                    truck_type,
                    axle
                })

                idx++;
            });

            var unique_tids = tids.filter(function(element, index, self) {
                return element && index === self.indexOf(element);
            });
            var multiple = "N";
            if (!unique_tids.length) {
                alert("BELUM ADA TRUCK ID YANG DI INPUT");
                return;
            }
            if (unique_tids.length > 1) {
                multiple = "Y";
            }

            var err = false;
            for (var i = 0; i < unique_tids.length; i++) {
                var count = 0;
                var axles = [];
                var sizes = [];
                for (var j = 0; j < datas.length; j++) {
                    if (datas[j].tid && datas[j].tid == unique_tids[i]) {
                        count++;
                        axles.push(datas[j].axle);
                        sizes.push(datas[j].size);
                    }
                }
                if (count > 2) {
                    alert("TRUCK ID DOUBLE SILAHKAN CHECK TRUCK ID");
                    err = true;
                    break;
                }
                if (count == 2 && (axles[0].substr(0, 2) == 20 || axles[1].substr(0, 2) == 20)) {
                    alert("TIDAK BISA MELAKUKAN TCA COMBO SILAHKAN CHECK TRAILER TYPE");
                    err = true;
                    break;
                }
                // combo hanya bisa 2 container size 20 
                if (count == 2 && (sizes[0] == 40 || sizes[1] == 40)) {
                    alert("TIDAK BISA MELAKUKAN TCA COMBO SILAHKAN CHECK TRAILER TYPE");
                    err = true;
                    break;
                }
                if (count == 2 && axles[0] != axles[1]) {
                    alert("TIDAK BISA MELAKUKAN TCA COMBO TRAILER TYPE TIDAK SESUAI SILAHKAN CHECK TRAILER TYPE");
                    err = true;
                    break;
                }
            }
            if (err) return;

            var payload;
            if (multiple == "N") {
                var detail = [];
                var truckType;
                var truckNumber;
                var axle;
                datas.forEach(e => {
                    if (unique_tids[0] == e.tid) {
                        detail.push({
                            requestNumber: id_req,
                            containerNo: e.no_container,
                            pod: e.pod,
                            weight: e.weight,
                            isoCode: e.iso_code
                        })
                        truckType = e.truck_type
                        truckNumber = e.truck_number
                        axle = e.axle
                    }
                });
                payload = {
                    truckType,
                    truckNumber,
                    createdBy: created_by,
                    tid: unique_tids[0],
                    axle,
                    type: "OEC",
                    actionCode: action_code,
                    detail
                };
            } else {
                var arr = [];
                unique_tids.forEach((value, index) => {
                    var data = {
                        createdBy: created_by,
                        tid: value,
                        type: "OEC",
                        actionCode: action_code,
                        detail: []
                    };
                    for (var i = 0; i < datas.length; i++) {
                        if (datas[i].tid == value) {
                            data['truckType'] = datas[i]['truck_type'];
                            data['truckNumber'] = datas[i]['truck_number'];
                            data['axle'] = datas[i]['axle'];
                            data['detail'].push({
                                requestNumber: request_number,
                                containerNo: datas[i]['no_container'],
                                pod: datas[i]['pod'],
                                weight: datas[i]['weight'] || 0,
                                isoCode: datas[i]['iso_code'],
                            })
                        }

                    }
                    arr.push(data);
                });
                payload = {
                    multiple: "Y",
                    data: arr
                };
            }

            $("#save_bt").html("<img src='images/animated_loading.gif'/>");

            // console.log(payload);
            // return

            $.post(url, payload, function(data) {
                xdata = JSON.parse(data);
                // console.log(xdata);
                // General Error & Tidak ke hit
                if (xdata.code == 0 && xdata.msg && !xdata.dataRec) {
                    alert(xdata.msg);
                    $("#save_bt").html('<input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" onclick="save_associate()" width="100"/>');
                    return;
                }
                // Jika Masuk
                if (multiple == 'N') {
                    if (xdata.code == 0) {
                        alert(xdata.msg);
                    }
                    if (xdata.hasOwnProperty("dataRec")) {
                        var affectedIdx = [];
                        datas.forEach((e) => {
                            if (payload.tid == e.tid) {
                                affectedIdx.push(e.index);
                            }
                        });
                        affectedIdx.forEach((e) => {
                            if (xdata.code == 0) {
                                $("#message_" + e).html("<img src='images/cont_red_alert.png'/> <font> " + xdata.msg + "<font/>");
                            } else {
                                $("#message_" + e).html("<img src='images/ok_card.png'/> <font> " + xdata.msg + "<font/>");

                            }
                        })
                    }
                } else {
                    if (xdata.code == 0) {
                        var err = [];
                        xdata.dataRec?.failedTid.forEach(element => {
                            err.push(element.tid + " : " + element.message)
                        });
                        var errs = err.join("\n");
                        alert(errs);
                        var successIdx = [];
                        var failedIdx = [];
                        datas.forEach((e) => {
                            xdata.dataRec?.successTid.forEach(success => {
                                if (success.tid == e.tid) {
                                    successIdx.push({
                                        index: e.index,
                                        msg: success.message
                                    });
                                }
                            });
                            xdata.dataRec?.failedTid.forEach(failed => {
                                if (failed.tid == e.tid) {
                                    failedIdx.push({
                                        index: e.index,
                                        msg: failed.message
                                    });
                                }
                            });
                        });
                        successIdx.forEach((e) => {
                            $("#message_" + e.index).html("<img src='images/ok_card.png'/> <font> " + e.msg + "<font/>");
                        })
                        failedIdx.forEach((e) => {
                            $("#message_" + e.index).html("<img src='images/cont_red_alert.png'/> <font> " + e.msg + "<font/>");
                        })
                    } else {
                        alert("Success");
                        var affectedIdx = [];
                        datas.forEach((e) => {
                            xdata.dataRec?.successTid.forEach(success => {
                                if (success.tid == e.tid) {
                                    affectedIdx.push({
                                        index: e.index,
                                        msg: "Success"
                                    });
                                }
                            });
                        });
                        affectedIdx.forEach((e) => {
                            $("#message_" + e.index).html("<img src='images/ok_card.png'/> <font> " + e.msg + "<font/>");
                        })
                    }
                }

                $("#save_bt").html('<input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" onclick="save_associate()" width="100"/>');
            });

        }
    </script>
</div>