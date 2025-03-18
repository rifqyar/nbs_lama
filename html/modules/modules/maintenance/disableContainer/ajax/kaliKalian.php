<?php
	$a=rand(1,100);
	$b=rand(1,100);
	
	$c=$a.' x '.$b.'= '; 
?>

<table>
	<tr>
		<td><h2><?=$c;?></h2></td>
		<td><input type="text" name="isiannya" id="isiannya" size="10"/></td>
		
	</tr>
	<tr>
		<td colspan="2"><button onclick="sbmKaliKalian(<?=$a?>,<?=$b?>)"> S U B M I T </button></td>
	</tr>
</table>