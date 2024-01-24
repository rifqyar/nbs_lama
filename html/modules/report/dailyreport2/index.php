<!--<style>
.ui-datepicker-calendar {
    display: none;
}?
</style>-->


<script type="text/javascript">

    function genReport() {
        var paramperiod = $('#tanggal1').val();
        
        window.open("<?= $HOME ?>report.dailyreport2/export_excel?param="+paramperiod);
    }

    $(document).ready(function ()
    {
        $("#tanggal1").datepicker({
            dateFormat: 'yymm',
            maxDate: 0
        });
    });


</script>


<div class="content">

    <p>
        <img src="<?= HOME ?>images/chart.png" height="7%" width="7%" style="vertical-align:middle">
        <b> <font color='#69b3e2' size='4px'>Report </font> </b>
        <font color='#888b8d' size='4px'>
        Daily Production Volume & Productivity Report 
        </font>
    </p>
    <br>
    <hr width="100%" color="#dfddd0"/>
    <br>
    <table>

        <tr>
            <td>Period Month : </td>
            <td>
                <input type="text" id="tanggal1">
        </td>
        <td>
            <button onclick="genReport()">Generate to Excel</button>
        </td>
        </tr>

    </table>
    <div>
        <br>
        <br>
        <hr width="100%"  color="#dfddd0"/>
        <br>