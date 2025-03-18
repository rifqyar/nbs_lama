<?php
$db = getDB();
$periode_awal = $_GET['awal'];
$periode_akhir = $_GET['akhir'];
?>

<script type="text/javascript">
    jQuery(function () {
        jQuery("#l_receiving").jqGrid({
            url: '<?= HOME ?>report.pendapatan.all/data?awal=<?php echo $periode_awal ?>&akhir=<?php echo $periode_akhir?>',
            mtype: "post",
            datatype: "json",
            colNames: [ 'No Req',
                        'Cust Name',
                        'No Faktur',
                        'CUR',
                        'Biaya', 
                        'Tgl Bayar', 
                        'Vessel',
                        'VOY IN',
                        'VOY OUT', 
                        'Bongkar Muat'                                                           
                      ],
            colModel: [
                {name: 'no_request', index: 'no_request', width: 100, align: "center"},
                {name: 'cust_name', index: 'cust_name', width: 200, align: "center"},
                {name: 'no_faktur_pajak', index: 'no_faktur_pajak', width: 150, align: "center"},
                {name: 'sign_currency', index: 'sign_currency', width: 30, align: "center"},                
                {name: 'kredit', index: 'kredit', width: 100, align: "right"},
                {name: 'date_paid', index: 'date_paid', width: 70, align: "center"},
                {name: 'vessel', index: 'vessel', width: 200, align: "center"},                
                {name: 'voyage_in', index: 'voyage_in', width: 100, align: "center"},
                {name: 'voyage_out', index: 'voyage_out', width: 100, align: "center"},
                {name: 'bongkar_muat', index: 'bongkar_muat', width: 150, align: "center"}           
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