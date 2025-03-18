<?php 
outputraw();

$id_vessel = $_GET['id_vessel'];?>
<br><br>

<h3 style="margin-bottom:20px;">Upload Baplie Export</h3>                    
<form method="post" enctype="multipart/form-data" action="<?=HOME?>planning.new_baplie/proses?id_vessel=<?=$id_vessel;?>">  
<input name="userfile" type="file">                        
    <input name="upload" type="submit" value="import">                        
</form>



