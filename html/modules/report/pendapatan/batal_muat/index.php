<style>
    h1{margin-bottom: 20px;}
    #pend{
        margin-bottom: 20px;
    }

</style>

<script type="text/javascript">
    
function export_excel()
{
	var v_periode_awal = $("#periode_awal").val();
        var v_periode_akhir = $("#periode_akhir").val();        
        window.open("<?=$HOME?>report.pendapatan.batal_muat/detail_excel?awal=" + v_periode_awal + "&akhir=" + v_periode_akhir);        
	
}    
    
    $(document).ready(function () {
        $("#periode_awal").datepicker({
            dateFormat: 'yymmdd'
        });

        $("#periode_akhir").datepicker({
            dateFormat: 'yymmdd'
        });
    });
    
    function tampilkan_pendapatan() {
        var v_periode_awal = $("#periode_awal").val();
        var v_periode_akhir = $("#periode_akhir").val();
        $('#detail_pendapatan').load("<?= HOME ?>report.pendapatan.batal_muat/detail?awal=" + v_periode_awal + "&akhir=" + v_periode_akhir);
    }

</script>

<h1>Laporan Pendapatan Batal Muat</h1>

<div id="pend">
    <div style="margin: 0 0 30 0">
        Periode: <input type="text" size="25" id="periode_awal"/> - <input type="text" size="25" id="periode_akhir"/> <button onclick="tampilkan_pendapatan();">Tampilkan</button>
        <button onclick="export_excel();">Export To Excel</button>
    </div>
    <div id="detail_pendapatan" style="margin: 0 0 0 0"></div>
</div>













