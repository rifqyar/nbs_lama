<script type="text/javascript">
function create_dwelling_time(){	
	//var url="<?=HOME;?>report.dwelling_time";
	periode_awal=$("#periode_awal").val();	
	$('#detail_dwelling').load("<?=HOME?>report.pass_bulanan/pass_bulanan?periode_awal="+periode_awal);	
	};
			
function download_excel(){	
	periode_awal=$("#periode_awal").val();	
	window.open("<?=HOME;?>report.pass_bulanan/excel?periode_awal="+periode_awal);	
};

function export_pdf()
{
	periode_awal=$("#periode_awal").val();		
	window.open("<?=$HOME?>report.pass_bulanan/export_pdf?periode_awal="+periode_awal);
	
}
	
$(document).ready(function ()
    {
        $("#periode_awal").datepicker({
            dateFormat: 'yymm',
            maxDate: 0
        });
    });
</script>



<h1 style="margin:10 10 35 10">Laporan Pass Bulanan </h1>


<p style="margin:10 10 10 10">
<label>Pilih Bulan</label>
<input type="text" id="periode_awal">
</p>

<p style="margin:10 10 10 10">
<button onclick="create_dwelling_time()">Preview List Pass Bulanan</button>
<button onclick="download_excel()">To Excel</button>
<!--<button onclick="export_pdf()">To PDF</button>-->
</p>

<div id="detail_dwelling"></div>

