<?php
	$db=getdb();
	$js=$_POST['ID_JS'];
	$block=$_POST['BLOK'];
	$slot=$_POST['SLOT'];
	$row=$_POST['ROW'];
	$tier=$_POST['TIER'];
	$yard=$_POST['YARD'];
	
	$q1="SELECT b.SIZE_	FROM TB_CONT_JOBSLIP b WHERE b.ID_JOB_SLIP=$js";
	//print_r($q1);die;
	$rs1=$db->query($q1);
	$rd1=$rs1->fetchRow();
	$sz=$rd1['SIZE_'];
	$q3=$db->query("SELECT SIZE_PLAN_ALLO from YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA='$block' AND SLOT_='$slot' and ROW_='$row'");
	$res_allo=$q3->fetchRow();
	$blok_allo=$res_allo['SIZE_PLAN_ALLO'];
	if($sz=='20')
	{
		if ($blok_allo=='40d')
		{
			$msg='no';
		}
		else if ($blok_allo=='40b')
		{
			$msg='no';
		}
		else if ($blok_allo=='')
		{
			$msg='no';
		}
		else
		{
			$msg='yes';
		}
	}
	else
	{
		if ($blok_allo=='40d')
		{
			$msg='yes';
		}
		else
		{
			$msg='no';
		}
	}
	
	echo $msg;
?>