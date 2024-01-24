<script type="text/javascript">

    function export_excel()
    {
        var pilihan = $("#pilihan").val();
        var param1;
        if (pilihan == '0')
        {
            param1 = $("#bulan").val();
        }
        else
        {
            param1 = $("#pilihan_tahun").val();
        }

        window.open("<?= $HOME ?>report.mos.excel_pdf/export_excel?pilihan=" + pilihan + "&param1=" + param1);
    }

    function check_sw()
    {
        var pilihan = $("#pilihan").val();
        if (pilihan == '0')
        {
            $("#bulan").removeAttr('disabled');
            $("#pilihan_tahun").attr('disabled', 'disabled');
        }
        else
        {
            $("#pilihan_tahun").removeAttr('disabled');
            $("#bulan").attr('disabled', 'disabled');
        }
    }

    $(document).ready(function()
    {
        $("#bulan").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'mm-yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            }
        });
        check_sw();
    });
</script>


<style>
    .ui-datepicker-calendar{
        display:none;
    }
    
    #label_keren{
        width:90px;
        float: left;
    }


</style>

<div style="padding: 20 25 25 35">
<h1>T1 Monthly Operations Summary </h1>

<p style="margin: 25 0 10 0">
    <label id="label_keren">Jenis Laporan</label>
    <select id="pilihan" onchange="check_sw()" class="input_text">

        <option value="0"> Monthly</option>
        <option value="1"> Yearly</option>
    </select>
</p>

<p style="margin: 0 0 10 0 ">
    <label id="label_keren">Pilih Bulan</label> 
    <input type="text" name="bulan" id="bulan" class="input_text">
</p>

<p style="margin: 0 0 15 0">
    <label id="label_keren">Tahun</label>
    <? $dt = date('Y'); ?>
    <select id="pilihan_tahun" class="input_text">
        <?
        for
        ($i = $dt; $i >= 2009; $i--) {
            ?>
            <option <? if ($i == $dt) { ?>selected<? } ?> value="<?= $i; ?>"><?= $i; ?></option>
        <? } ?>
    </select>
</p>

<p>
<label id="label_keren">Export Laporan</label>
<a onclick="export_excel()" style="height: 35px; width:80px;" target="_blank" title="export to excel">
    <img src="<?= HOME ?>images/mexcel2.png" ></a>&nbsp;
<a onclick="export_pdf()" target="_blank" style="height: 35px; width:80px;" title="export to pdf (unrecomended)"><img src="<?= HOME ?>images/mpdf2.png"></a>
</p>

</div>



