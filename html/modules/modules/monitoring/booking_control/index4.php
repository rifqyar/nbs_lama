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
    <h1>Booking Limit Control</h1>

    <div id="grid" style="margin-top: 30px">
        <?php
        $db = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=192.168.29.88)(Port=1521)))(CONNECT_DATA=(SERVER=dedicated)(SERVICE_NAME=TOSDBTEST)))";
        $objConnect = ocilogon("OPUS_REPO", "opus_repo", "$db")or die("can't connect to server");

        $strSQL  = "select 
                        *
                    from 
                        M_VSB_VOYAGE                    
                        ";

        $objParse = OCIparse($objConnect, $strSQL);
        oci_execute ($objParse,OCI_DEFAULT);
        $Num_Rows = oci_fetch_all($objParse, $Result); 
        
        

        $Per_Page = 40;   // Per Page  

        $Page = $_GET["Page"];
        if (!$_GET["Page"]) {
            $Page = 1;
        }

        $Prev_Page = $Page - 1;
        $Next_Page = $Page + 1;


        $Prev_Page = $Page - 1;
        $Next_Page = $Page + 1;

        $Page_Start = (($Per_Page * $Page) - $Per_Page);
        if ($Num_Rows <= $Per_Page) {
            $Num_Pages = 1;
        } else if (($Num_Rows % $Per_Page) == 0) {
            $Num_Pages = ($Num_Rows / $Per_Page);
        } else {
            $Num_Pages = ($Num_Rows / $Per_Page) + 1;
            $Num_Pages = (int) $Num_Pages;
        }
        $Page_End = $Per_Page * $Page;
        if ($Page_End > $Num_Rows) {
            $Page_End = $Num_Rows;
        }
        ?>


        <table class="table_alumni" width="100%">

            <tr>
                
                <th>Option</th>
                <th>Vessel</th>
                <th>Voyage In - Out</th>
                <th>Container Limit</th>              
            </tr>
   

            <?php    
            
            for ($i = $Page_Start; $i < $Page_End; $i++){
                ?>
                <tr>
                    
                    <td class="td-tengah"><a href="monitoring.booking_control/edit_req?vessel=<?php echo $Result['VESSEL'][$i]; ?>&voyin=<?php echo $Result['VOYAGE_IN'][$i] ?>&voyout=<?php echo $Result['VOYAGE_OUT'][$i] ?>&cont_limit=<?php echo $Result['CONTAINER_LIMIT'][$i] ?>"><i><font color="blue">Edit</font></i></a></td>
                    <td class="td-tengah"><?php echo $Result['VESSEL'][$i]; ?></td>              
                    <td class="td-tengah"><?php echo $Result['VOYAGE_IN'][$i] . ' | ' . $Result['VOYAGE_OUT'][$i] ?></td>
                    <td class="td-tengah"><?php echo $Result['CONTAINER_LIMIT'][$i]; ?></td>
                </tr>
                <?php
            }
            ?>

        </table>

        <br>  
        Total <?= $Num_Rows; ?> Record : <?= $Num_Pages; ?> Page :  
        <?
        if ($Prev_Page) {
            echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
        }

        for ($i = 1; $i <= $Num_Pages; $i++) {
            if ($i != $Page) {
                echo "[ <a href='monitoring.booking_control/index?Page=$i'>$i</a> ]";
            } else {
                echo "<b> $i </b>";
            }
        }
        if ($Page != $Num_Pages) {
            echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
        }
        oci_close($objConnect);
        ?>  


    </div>


</div>

