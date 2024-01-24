<?php
    $db = getDB();
    $id_cont = $_POST['CONT_NO'];
    $query = " SELECT F_TCATN, F_TCACN, F_TCAST, F_TCARQ, B.TRUCK_NUMBER  FROM OPUS_REPO.TB_ASSOCIATION A 
            LEFT JOIN TID_REPO B ON A.F_TCATN = B.TID WHERE F_TCAST = 'R' AND A.F_TCACN = '$id_cont'";
    $contlist = $db->query($query)->getAll();
    $jum = count($contlist);
?>

<div id="contlist">
<script type="text/javascript">

    (function ($) {
        $.fn.styleTable = function (options) {
            var defaults = {
                css: 'styleTable'
            };
            options = $.extend(defaults, options);

            return this.each(function () {

                input = $(this);
                input.addClass(options.css);

                input.find("tr").live('mouseover mouseout', function (event) {
                    if (event.type == 'mouseover') {
                        $(this).children("td").addClass("ui-state-hover");
                    } else {
                        $(this).children("td").removeClass("ui-state-hover");
                    }
                });

                input.find("th").addClass("ui-state-default");
                input.find("td").addClass("ui-widget-content");

                input.find("tr").each(function () {
                    $(this).children("td:not(:first)").addClass("first");
                    $(this).children("th:not(:first)").addClass("first");
                });
            });
        };
    })(jQuery);

    $(document).ready(function () {
        $("#Table1").styleTable();
    });

</script>
<style>
    .styleTable { border-collapse: separate; }
    .styleTable TD { font-weight: normal !important; padding: .4em; border-top-width: 0px !important; }
    .styleTable TH { text-align: center; padding: .8em .4em; }
    .styleTable TD.first, .styleTable TH.first { border-left-width: 0px !important; }
    .loader
    {
        background: url(images/loading_green.gif);
        background-repeat: no-repeat;
        background-position: right;
    }
    #Table1 {
        margin-left:auto;
        margin-right:auto;
    }
</style>
    <?php if(count($contlist) > 0) { ?>
    <form method="post" id="formsubmit" action="<?=$HOME?>maintenance.tca.ajax/save_cancel">
<table id = 'Table1'>
    <tr >
        <th>NO</th>
        <th>NO CONTAINER</th>
        <th>TRUCK NUMBER</th>
        <th>TRUCK ID</th>        
    </tr>
        <?php $i=0; 
        foreach($contlist as $cl){?>
        <tr>
            <td ><?=$i+1;?></td>
            <td ><?=$cl['F_TCACN']?> 
                <input type="hidden" id="nocont" name="nocont" value="<?=$cl['F_TCACN']?>" /> 
                <input type="hidden" id="nocont" name="id_req" value="<?=$cl['F_TCARQ']?>" /> 
            </td>
            <td ><input type="hidden" name="truckid" id="truckid" class="truckidclass" value="<?=$cl['F_TCATN']?>"/> <?=$cl['F_TCATN']?> </td>
            <td ><input type="hidden" name="trucknum" id="trucknum" readonly value="<?=$cl['TRUCK_NUMBER']?>"/> <?=$cl['TRUCK_NUMBER']?></td>
        </tr>
        <?php  $i++; } ?>
        <tr>
            <td colspan='6'>
                <div id="save_bt">
                    <input style="font:12pt bold" type="button" id="save_bt" value="Cancel TCA" class="link-button" onclick="save_cancel()" width="100"/>
                    <p id="message"></p>
                </div>
            </td>
        </tr>
</table>
        </form>
    <?php } else { echo "No Data Found"; } ?>
    <script>
        
          function save_cancel(){
              
                var url = "<?=HOME?>maintenance.tca.ajax/save_cancel";
                var id_req = $('#id_req').val();
                var nocont = $('#nocont').val();;
                var truck = $('#truckid').val();;
              
              $("#save_bt").html("<img src='images/animated_loading.gif'/>");
              
              $.post(url,{nocont:nocont,truckid:truck,id_req:id_req},function(data){
                    xdata = JSON.parse(data);
                    alert(xdata.out_rsp);
                    if(xdata.out_rsp == 'OK'){
                        $("#save_bt").html("<img src='images/ok_card.png'/> <font> "+xdata.out_msg+"<font/>");
                    }
                    else {
                        $("#save_bt").html("<img src='images/cont_red_alert.png'/> <font> "+xdata.out_msg+"<font/>");
                    }                        
                //$("#save_bt").html('<input style="font:12pt bold" type="button" id="save_bt" value="Cancel TCA" class="link-button" onclick="save_cancel()" width="100"/>');
                });
        }
    </script>
</div>