<?php
$db = getDB();
$periode_awal = $_GET['awal'];
$periode_akhir = $_GET['akhir'];
?>

<script type="text/javascript">
    jQuery(function () {
        jQuery("#l_receiving").jqGrid({
            url: '<?= HOME ?>report.pendapatan.hicoscan/data?awal=<?php echo $periode_awal ?>&akhir=<?php echo $periode_akhir?>',
            mtype: "post",
            datatype: "json",
            colNames: [ 'No Req',
                        'No Nota',
                        'Tgl Bayar',
                        'Cust Name',
                        'Vessel', 
                        'Voy In', 
                        'Voy Out',
                        'Adm',
                        'Size', 
                        'Type', 
                        'Status',
                        'Qty', 
                        'Tot Hari', 
                        'Tgl Awal', 
                        'Tgl Akhir', 
                        'Lift ON',                                      
                        'Lift Off'                                                                             
                      ],
            colModel: [
                {name: 'no_request', index: 'no_request', width: 100, align: "center"},
                {name: 'no_nota', index: 'no_nota', width: 110, align: "center"},
                {name: 'Tanggal Bayar', index: 'tanggal_bayar', width: 60, align: "center"},
                {name: 'cust_name', index: 'cust_name', width: 170, align: "left"},                
                {name: 'vessel', index: 'vessel', width: 100, align: "left"},
                {name: 'voyage_in', index: 'voyage_in', width: 50, align: "center"},
                {name: 'voyage_out', index: 'voyage_out', width: 50, align: "center"},                
                {name: 'adm', index: 'adm', width: 50, align: "center"},
                {name: 'size', index: 'size', width: 20, align: "center"},
                {name: 'type', index: 'type', width: 20, align: "left"},
                {name: 'status', index: 'status', width: 20, align: "center"},
                {name: 'qty', index: 'qty', width: 20, align: "center"},
                {name: 'tothari', index: 'tothari', width: 20, align: "center"},
                {name: 'tgl_awal', index: 'tgl_awal', width: 60, align: "center"},
                {name: 'tgl_akhir', index: 'tgl_akhir', width: 60, align: "center"},
                {name: 'lift_on', index: 'lift_on', width: 60, align: "center"},             
                {name: 'lift_off', index: 'lift_off', width: 100, align: "center"}                           
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
            caption: "Laporan Pendapatan"
        });
        jQuery("#l_receiving").jqGrid('navGrid', '#pg_l_receiving', {del: false, add: false, edit: false, search: false});
        jQuery("#l_receiving").jqGrid('filterToolbar', {defaultSearch: 'cn', stringResult: true, searchOnEnter: false});

    });
</script>

<div class="content">
    <div class="main_side">
              
        <table id='l_receiving' width="100%"></table> <div id='pg_l_receiving'></div>        

    </div>
</div>
<?php die(); ?>