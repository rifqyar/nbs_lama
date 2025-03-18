<style>
    .content{
        width:95%;
        margin-left:auto;
        margin-right:auto;
        margin-bottom: 10px;
        margin-top:20px;
    }
    .main_side{
        width:100%;
        float:left;
        text-align:left;
    }
    .rightside{ 
        width:25%;
        float:right;
        text-align:center;
    }
    .ganjil {
        background-color: #FFF; /* Warna untuk baris ganjil */
    }
    .genap {
        background-color: #bbe3fe; /* Warna untuk baris genap */
    }   

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');
?>
<script type="text/javascript">
    jQuery(function() {
        jQuery("#l_receiving").jqGrid({
            url: '<?= HOME ?>monitoring.booking_control/data',
            mtype: "post",
            datatype: "json",
            colNames: ['Aksi', 'Vessel', 'Voyage In', 'Voyage Out', 'Container Limit'],
            colModel: [
                {name: 'aksi', width: 120, align: "center", sortable: false, search: false},
                {name: 'VESSEL', index: 'VESSEL', width: 200, align: "center"},
                {name: 'VOYAGE_IN', index: 'VOYAGE_IN', width: 176, align: "center"},
                {name: 'VOYAGE_OUT', index: 'VOYAGE_OUT', width: 175, align: "center"},
                {name: 'CONTAINER_LIMIT', index: 'CONTAINER_LIMIT', width: 150, align: "center"},
            ],
            rowNum: 20,
            width: 865,
            height: "100%", //250
            ignoreCase: true,
            rowList: [10, 20, 30, 40, 50, 60],
            loadonce: true,
            rownumbers: true,
            rownumWidth: 15,
            gridview: true,
            pager: '#pg_l_receiving',
            viewrecords: true,
            shrinkToFit: false,
            caption: "Data Kapal dan Booking Limit"
        });
        jQuery("#l_receiving").jqGrid('navGrid', '#pg_l_receiving', {del: false, add: false, edit: false, search: false});
        jQuery("#l_receiving").jqGrid('filterToolbar', {defaultSearch: 'cn', stringResult: true, searchOnEnter: false});

    });


</script>

<div class="content">
    <div class="main_side">
        
        <h1 style="margin-bottom: 50px">Booking Control Limit</h1>
        

        <table id='l_receiving' width="100%"></table> <div id='pg_l_receiving'></div>

        <br/>
        <br/>
    </div>
</div>