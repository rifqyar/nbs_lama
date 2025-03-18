<?php
$db = getDB();
$periode_awal = $_GET['awal'];
$periode_akhir = $_GET['akhir'];

?>


<script type="text/javascript">
    jQuery(function () {
        jQuery("#l_receiving").jqGrid({
            url: '<?= HOME ?>report.pendapatan.delivery/data?awal=<?php echo $periode_awal ?>&akhir=<?php echo $periode_akhir?>',
            mtype: "post",
            datatype: "json",
            colNames: [ 'No Req', 
                        'No Nota', 
                        'Cust Name',                        
                        'Total',                          
                        'Kredit', 
                        'Vessel',
                        'Voy In',
                        'Voy Out',                       
                        'Date Paid', 
                        
                        'Total Hari',                        
                        'Penp Masa I.2',
                        'Penp Masa II',                        
                        'Tamb. SP2 masa I.2',
                        'Tamb. SP2 masa II',
                        'Tamb. SPPB masa I.2',
                        'Tamb. SPPB masa II',                       
                        'Kebersihan',
                        'Mon & Listrik',                        
                        'Lift On', 
                        'ADM' 
                                             
                      ],
            colModel: [
                {name: 'no_request', index: 'no_request', width: 100, align: "center"},
                {name: 'no_nota', index: 'no_nota', width: 110, align: "center"},
                {name: 'cust_name', index: 'cust_name', width: 200, align: "left"},                
                {name: 'total', index: 'total', width: 70, align: "center"},
               
                {name: 'kredit', index: 'kredit', width: 70, align: "center"},
                {name: 'vessel', index: 'vessel', width: 100, align: "center"},
                {name: 'voyage_in', index: 'voyage_in', width: 50, align: "center"},               
                {name: 'voyage_out', index: 'voyage_out', width: 50, align: "center"},                            
                {name: 'date_paid', index: 'date_paid', width: 100, align: "center"},
                
                {name: 'tot_hari', index: 'tot_hari', width: 50, align: "center"},                
                {name: 'penumpukan_masa_1.2', index: 'penumpukan_masa_1.2', width: 100, align: "center"},
                {name: 'penumpukan_masa_II', index: 'penumpukan_masa_II', width: 100, align: "center"},                
                {name: 'tamb_sp2_masa_1.2', index: 'tamb_sp2_masa_1.2', width: 100, align: "center"},                 
                {name: 'tamb_sp2_masa2', index: 'tamb_sp2_masa2', width: 100, align: "center"},                 
                {name: 'tamb_sppb_masa_1.2', index: 'penumpukan_masa1', width: 100, align: "center"},                 
                {name: 'tamb_sppb_masa_2', index: 'penumpukan_masa1', width: 100, align: "center"},                
                {name: 'kebersihan', index: 'penumpukan_masa1', width: 100, align: "center"},
                {name: 'Monitoring dan Listrik', index: 'penumpukan_masa1', width: 100, align: "center"},
                {name: 'lift_on', index: 'lift_on', width: 100, align: "center"},                 
                {name: 'adm', index: 'adm', width: 100, align: "center"}
                
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