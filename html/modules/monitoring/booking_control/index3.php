<script type='text/javascript'>

    function create_req()
    {
        var url = "<?= HOME; ?>monitoring.booking_control/save_req";
        var ves = $("#vessel").val();
        var voy = $("#voy").val();
        var voyo = $("#voyo").val();
        var date_muat = $("#tgl_muat").val();
        var date_close = $("#clossing_time").val();
        var date_op = $("#tgl_open_stack").val();
        var ukk = $("#ukk").val();
        var pol = $("#pelabuhan_asal").val();
        var ipol = $("#ipol").val();
        var pod = $("#pelabuhan_tujuan").val();
        var ipod = $("#ipod").val();
        var ship = $("#ship").val();
        var npe = $("#npe").val();
        var peb = $("#peb").val();

        var start_shift = $("#start_shift").val();
        var end_shift = $("#end_shift").val();
        var jml_shift = $("#jml_shift").val();

        var jml_cont = $("#jml_cont").val();

        if (ves == '')
        {
            alert('Entry vessel please,..');
            return false;
        }

        if (jml_cont == '')
        {
            alert('Entry jumlah container please,..');
            return false;
        }

        else
        {
            //alert('e');
            $.post(url, {VES: ves, VOY: voy, VOYO: voyo, JML_CONT: jml_cont}, function(data) {

                alert("berhasil");

            });

        }
    }





    $(document).ready(function()
    {
        src = 'request.anne.auto/pelabuhan_pod';
        $("#pelabuhan_asal").val('JAKARTA, INDOESIA');
        $("#ipol").val('IDJKT');
        $("#nega").val('ID');
        //======================================= autocomplete vessel==========================================//
        $("#vessel").autocomplete({
            minLength: 3,
            source: "request.anne.auto/vessel",
            focus: function(event, ui)
            {
                $("#vessel").val(ui.item.VESSEL);
                return false;
            },
            select: function(event, ui)
            {
                $("#vessel").val(ui.item.VESSEL);
                $("#voy").val(ui.item.VOYAGE_IN);
                $("#voyo").val(ui.item.VOYAGE_OUT);
                if (ui.item.FIRST_ETD == null || ui.item.FIRST_ETD == '') {
                    $("#tgl_muat").val(ui.item.TGL_JAM_BERANGKAT);
                } else {
                    $("#tgl_muat").val(ui.item.FIRST_ETD);
                }
                $("#clossing_time").val(ui.item.CLOSING_TIME_DOC);
                $("#tgl_bongkar").val(ui.item.TGL_JAM_TIBA);
                $("#tgl_open_stack").val(ui.item.OPEN_STACK);
                $("#ukk").val(ui.item.ID_VSB_VOYAGE);
                $("#callsign").val(ui.item.CALL_SIGN);
                return false;
            }
        }).data("autocomplete")._renderItem = function(ul, item)
        {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a align='center'>" + item.VESSEL + " " + item.VOYAGE_IN + " - " + item.VOYAGE_OUT + " <br>" + item.ID_VSB_VOYAGE + "</a>")
                    .appendTo(ul);

        };
        //======================================= autocomplete vessel==========================================//


    });

</script>

<div id="content" style="margin: 10 50 0 50 ">
    <h1>BOOKING CONTROL</h1>

    <p>
        <label>Vessel</label>
        <input type="text" size="25" name="vessel" id="vessel"  style="background-color:#FFFFCC;" /><input type="text" size="5" name="callsign" id="callsign"  style="background-color:#FFFFCC;" />
    </p>

    <p>
        <label>Clossing time Doc.</label>
        <input type="text" size="20" name="clossing_time" id="clossing_time" readonly="readonly"/>
    </p>

    <p>
        <label>Voyage</label>
        <input type="text" size="10" name="voy" id="voy" readonly="readonly"/> - <input type="text" size="10" name="voyo" id="voyo" readonly="readonly"/> <input size="10" name="voyopus" id="voyopus" type="hidden"/>
    </p>

    <p>
        <label>Open stack date</label>
        <input type="text" size="20" name="tgl_open_stack" id="tgl_open_stack" readonly="readonly" />
    </p>

    <p>
        <label>ETA</label>
        <input type="text" size="20" name="tgl_bongkar" id="tgl_bongkar" readonly="readonly"/>
    </p>

    <p>
        <label>ETD</label>
        <input type="text" size="20" id="tgl_muat" name="tgl_muat" value="" readonly="readonly"/>
    </p>

    <p>
        <label>ID VVD</label>
        <input type="text" size="18" name="ukk" id="ukk" readonly="readonly"/>
    </p>

    <p>
        <label>POL</label>
        <input type="text" size="25" name="pelabuhan_asal" id="pelabuhan_asal"/> <input type="text" size="8" id="ipol" readonly="readonly"/>
        <input type="hidden" size="8" id="nega"/>
    </p>

    <p>
        <label>Jumlah Container</label>
        <input type="text" id="jml_cont" name="jml_cont">
    </p>

    <button onclick="create_req()" id="but_create"><img src="<?= HOME; ?>images/create_req.png"/></button>


    <div id="grid" style="margin-top: 30px">
        <?php
        $db = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=192.168.29.88)(Port=1521)))(CONNECT_DATA=(SERVER=dedicated)(SERVICE_NAME=TOSDBTEST)))";
        $conn = ocilogon("OPUS_REPO", "opus_repo", "$db")or die("can't connect to server");

        $query = "select 
                        *
                    from 
                        M_VSB_VOYAGE                    
                        ";

        $query = OCIparse($conn, $query);
        ociexecute($query);
        ?>


        <table width="100%">
            
                <tr>
                    <th>No</th>
                    <th>Option</th>
                    <th>Vessel</th>
                    <th>Voyage In - Out</th>
                    <th>Container Limit</th>              
                </tr>

                <?php while ($row = oci_fetch_array($query, OCI_ASSOC)) { ?>
                    <tr>
                        <td></td>
                        <td><a href="monitoring.booking_control/edit_req?vessel=<?php echo $row['VESSEL'];?>&voyin=<?php echo $row['VOYAGE_IN']?>&voyout=<?php echo $row['VOYAGE_OUT']?>&cont_limit=<?php echo $row['CONTAINER_LIMIT']?>">edit</a></td>
                        <td><?php echo $row['VESSEL']; ?></td>              
                        <td><?php echo $row['VOYAGE_IN'] . '|' . $row['VOYAGE_OUT'] ?></td>
                        <td><?php echo $row['CONTAINER_LIMIT']; ?></td>
                    </tr>
                    <?php
                }
                ?>
            
        </table>
    </div>


</div>

