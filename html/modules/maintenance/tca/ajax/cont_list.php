<?php
    $db = getDB();
    $id_req = $_POST['NO_REQ'];
    $jn_req = substr($id_req,0,2);

    if($jn_req != 'BM'){
        $query = "SELECT A.NO_CONTAINER, A.PIN_NUMBER FROM REQ_DELIVERY_D A, M_CYC_CONTAINER@DBINT_LINK B
                WHERE A.NO_CONTAINER = B.NO_CONTAINER AND A.VESSEL_CODE = B.VESSEL_CODE
                AND A.VOYAGE_IN = B.VOYAGE_IN AND A.VOYAGE_OUT = B.VOYAGE_OUT 
                AND B.CONT_LOCATION = 'Yard' AND B.ACTIVITY = 'PLACEMENT IMPORT' AND A.ID_REQ='$id_req'";
    }
    else {
        $query = "SELECT D.NO_CONTAINER, D.PIN_NUMBER FROM REQ_BATALMUAT_H A, REQ_BATALMUAT_D D, M_CYC_CONTAINER@DBINT_LINK B
                WHERE A.ID_REQ = D.ID_REQ AND D.NO_CONTAINER = B.NO_CONTAINER AND A.VESSEL_CODE = B.VESSEL_CODE
                AND A.VOYAGE_IN = B.VOYAGE_IN AND A.VOYAGE_OUT = B.VOYAGE_OUT 
                AND B.CONT_LOCATION = 'Yard' AND B.ACTIVITY = 'PLACEMENT EXPORT' AND A.ID_REQ='$id_req'";    
    }
    
    $contlist = $db->query($query)->getAll();
    $jum = count($contlist);
    /*$qgenpin = "SELECT * FROM   ( SELECT F_PINNUMBER, F_ACTIVEPIN FROM TB_TCAPIN@DBINT_LINK WHERE F_ACTIVEPIN = 'N' ORDER BY DBMS_RANDOM.RANDOM)
            WHERE  rownum <= $jum";
    $pinlist = $db->query($qgenpin)->getAll();*/
    //print_r($pinlist); die();
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
    <form method="post" id="formsubmit" action="<?=$HOME?>maintenance.tca.ajax/save_associate">
<table id = 'Table1'>
    <tr >
        <th>NO</th>
        <th>NO CONTAINER</th>
        <th>TRUCK ID</th> 
        <th>POLICE NUMBER</th>
        <th>PIN NUMBER</th>        
        <th>REMARKS</th>
    </tr>
        <?php $i=0; $f=0;
         foreach($contlist as $cl){?>
        <tr>
            <td ><?=$i+1;?></td>
            <td ><?=$cl['NO_CONTAINER']?> 
                <input type="hidden" id="nocont_<?=$i?>" name="nocont[]" value="<?=$cl['NO_CONTAINER']?>" /> 
            </td>
            <td ><input type="text" name="truckid[]" id="truckid_<?=$i?>" class="truckidclass"/></td>
            <td ><input type="text" name="trucknum_<?=$i?>" id="trucknum_<?=$i?>" readonly/>
                <input type="hidden" id="total" name="total" value="<?=$jum?>" />
                <input type="hidden" id="id_req" name="id_req" value="<?=$id_req?>"/>
            </td>            
            <td>
                <input readonly type="text" name="pinnumber[]" id="pinnumber_<?=$i?>" value="<?=$cl['PIN_NUMBER']?>"/>
            </td>
            <td>
                <div id="message_<?=$i?>"></div>
            </td>
        </tr>
        <?php  $i++;
                $f++;
         } ?>
        <tr>
            <td colspan='6'>
                <div id="save_bt">
                    <input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" onclick="save_associate()" width="100"/>
                </div>
            </td>
        </tr>
</table>
        </form>
    <?php } else { echo "No Data Found"; } ?>
    <script>
        <?php $i=0;  foreach($contlist as $cl){ ?>
        $( "#truckid_<?=$i?>" ).autocomplete({
            minLength: 3,
            source: "<?=HOME?>maintenance.tca.auto/truckid",
            focus: function( event, ui ) 
            {
                $( "#truckid_<?=$i?>" ).val( ui.item.TRUCK_ID);
                $(this).removeClass('loader');
                return false;                
            },
            select: function( event, ui ) 
            {
                $( "#truckid_<?=$i?>" ).val( ui.item.TRUCK_ID);   
                $( "#trucknum_<?=$i?>" ).val( ui.item.TRUCK_NUMBER);
                return false;
            },
            search: function (e, u) {
                 $(this).addClass('loader');
            },
            response: function (e, u) {
                $(this).removeClass('loader');
            }
        }).data( "autocomplete" )._renderItem = function( ul, item ) 
        {
            return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a align='center'>" + item.TRUCK_ID + " - " +item.TRUCK_NUMBER+" <br>"+ item.COMPANY_NAME +"</a>")
            .appendTo( ul );

        };
        <?php $i++; } ?>
        
          function save_associate(){
              /*var nocont=$('input[name="nocont[]"]').map(function() { return $(this).val()}).get();
              var truckid=$('input[name="truckid[]"]').map(function() { return $(this).val()}).get();
              var id_req = $('#id_req').val();
              var url = "<?=HOME?>maintenance.tca.ajax/save_associate";
              $.post(url,{nocont:nocont,truckid:truckid,id_req:id_req},function(data){
                  
              });*/
                var radioval = $('input[name="terminal"]:checked', '#list').val();
                var url = "<?=HOME?>maintenance.tca.ajax/save_associate";
                var id_req = $('#id_req').val();
                var i = 0;
                var nocont = [];
                var truck = [];
                var pin = [];
              $.each($('[id^=nocont_]'), function (i, item) {
                var cont_no = $(this).val();
                nocont[i]   = cont_no;
                var trck_no = $("#truckid_"+i).val();
                truck[i]    = trck_no; 
                var pin_no  = $("#pinnumber_"+i).val();
                pin[i]  = pin_no;
                //alert(grade+i);
                //$("#message_"+i).html("<img src='images/animated_loading.gif'/>");
                
                i++;
              });
              
              $("#save_bt").html("<img src='images/animated_loading.gif'/>");
              
              $.post(url,{nocont:nocont,truckid:truck,pinnumber:pin,id_req:id_req,terminal:radioval},function(data){
                    xdata = JSON.parse(data);
                    var tot = xdata.length;
                    //alert(xdata[0].out_msg);
                    //alert(xdata[1].out_msg);
                    var j = 0;
                    for (j=0;j<tot;j++){
                        if(xdata[j].out_rsp == 'OK'){
                            $("#message_"+j).html("<img src='images/ok_card.png'/> <font> "+xdata[j].out_msg+"<font/>");
                        }
                        else {
                            $("#message_"+j).html("<img src='images/cont_red_alert.png'/> <font> "+xdata[j].out_msg+"<font/>");
                        }    
                    }
                    
                $("#save_bt").html('<input style="font:12pt bold" type="button" id="save_bt" value="Save" class="link-button" onclick="save_associate()" width="100"/>');
                });
        }
    </script>
</div>