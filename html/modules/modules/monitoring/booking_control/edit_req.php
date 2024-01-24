<style>
    #content{
        margin: 15 15 15 30;         

    }
    
    #parnya{
        margin-bottom: 15px;
        
    }


</style>

<script type='text/javascript'>

    function update_req()
    {
        var url = "<?= HOME; ?>monitoring.booking_control/update_req";
        var ves = $("#vessel").val();
        var voy = $("#voyin").val();
        var voyo = $("#voyout").val();
        var jml_cont = $("#jml_cont").val();

        if (jml_cont == '')
        {
            alert('Entry jumlah container please,..');
            return false;
        }

        else
        {
            //alert('e');
            $.post(url, {VES: ves, VOY: voy, VOYO: voyo, JML_CONT: jml_cont}, function(data) {

                alert("berhasil");
                window.location.href = 'monitoring.booking_control';
            });
        }
    }

</script>


<?php
$vessel = $_GET['vessel'];
$voyin = $_GET['voyin'];
$voyout = $_GET['voyout'];
$cont_limit = $_GET['cont_limit'];
?>

<div id="content">
    <h1 style="margin-bottom: 20px">Edit Booking Limit</h1>

    <p id="parnya">
        <label id="labelnya">Vessel</label><br/>
        <input class="input_text" type="text" id="vessel" value="<?php echo $vessel; ?>" disabled="disable">
    </p>
    <p id="parnya">
        <label id="labelnya">Vovage In</label><br/>
        <input class="input_text" type="text" id="voyin" value="<?php echo $voyin; ?>" disabled="disable">
    </p>
    <p id="parnya">
        <label id="labelnya">Vovage Out</label> <br/>     
        <input class="input_text" type="text" id="voyout" value="<?php echo $voyout; ?>" disabled="disable"> 
    </p>
    <p id="parnya">
        <label id="labelnya">Container Limit</label><br/>
        <input class="input_text" type="text" id="jml_cont" value="<?php echo $cont_limit; ?>">
    </p>

    <button onclick="update_req()" class="button_keren">Edit Request</button>
 

</div>

