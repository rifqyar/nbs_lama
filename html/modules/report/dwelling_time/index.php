<script type="text/javascript">
function create_dwelling_time(){	
	//var url="<?=HOME;?>report.dwelling_time";
	periode_awal=$("#periode_awal").val();	
	periode_akhir=$("#periode_akhir").val();
	status = $("#status").val();
	
	$('#detail_dwelling').load("<?=HOME?>report.dwelling_time/dwelling_time?periode_awal="+periode_awal+"&periode_akhir="+periode_akhir+"&status="+status);	
	};
			
function download_excel(){	
	periode_awal=$("#periode_awal").val();	
	periode_akhir=$("#periode_akhir").val();
	status = $("#status").val();
	//window.open("excel");	
	window.open("<?=HOME;?>report.dwelling_time/excel?periode_awal="+periode_awal+"&periode_akhir="+periode_akhir+"&status="+status);	
};

function download_pdf_praya(){	
	periode_awal=$("#periode_awal").val();	
	periode_akhir=$("#periode_akhir").val();
	status = $("#status").val();

	//window.open("excel");	
	window.open("<?=HOME;?>report.dwelling_time/dwelling_time_praya?periode_awal="+periode_awal+"&periode_akhir="+periode_akhir+"&status="+status);	
};
	
$(document).ready(function ()
    {
        $("#periode_awal").datepicker({
            dateFormat: 'yymmdd',
            maxDate: 0
        });
		
		$("#periode_akhir").datepicker({
            dateFormat: 'yymmdd',
            maxDate: 0
        });		
    });


</script>



<h1 style="margin:10 10 30 10">Laporan Dwelling Time </h1>

<p style="margin:0 10 10 10">
Export / Import
 <select id="status">
  <option value="E">Export</option>
  <option value="I">Import</option> 
</select> 

</p>

<p style="margin:10 10 10 10">
<label>Periode Awal</label>
<input type="text" id="periode_awal">
</p>

<p style="margin:10 10 10 10">
<label>Periode Akhir</label>
<input type="text" id="periode_akhir">
</p>
<p style="margin:10 10 10 10">
<!-- <button onclick="create_dwelling_time()">Create Dwelling Time</button> -->
<!-- <button onclick="download_excel()">Excel</button> -->
<button onclick="download_pdf_praya()">Cetak PDF</button>
</p>

<div id="detail_dwelling"></div>

