<?php
	$db=getdb();
	$cont=$_POST['NO_CONT'];
	$no_ukk=$_POST['NO_UKK'];
	$block=$_POST['BLOK'];
	$slot=$_POST['SLOT'];
	$row=$_POST['ROW'];
	$tier=$_POST['TIER'];
	$yard=$_POST['YARD'];
	
	$q1="SELECT b.SIZE_, b.ID_BOOK	FROM ISWS_LIST_CONTAINER b WHERE b.NO_CONTAINER='$cont' AND b.NO_UKK='$no_ukk'";
	//print_r($q1);die;
	$rs1=$db->query($q1);
	$rd1=$rs1->fetchRow();
	$sz=TRIM($rd1['SIZE_']);
	$id_book=TRIM($rd1['ID_BOOK']);
	
	$q3=$db->query("SELECT SIZE_PLAN_ALLO, ID_KATEG, SIZE_PLAN_PLC from YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA='$block' AND SLOT_='$slot' and ROW_='$row'");
	$res_allo=$q3->fetchRow();
	$blok_allo=TRIM($res_allo['SIZE_PLAN_ALLO']);
	$blok_plc=TRIM($res_allo['SIZE_PLAN_PLC']);
	$blok_ktg=TRIM($res_allo['ID_KATEG']);
	
	if($sz=='20')
	{
		if (($blok_allo == '') OR ($blok_allo == NULL))
		{
			$msg='no_alokasi';
		}
		else
		{
			if(($blok_plc == NULL) OR ($blok_plc == ''))
			{
				if (($blok_ktg <> NULL) OR ($blok_ktg <> ''))
				{
					if ($blok_ktg == $id_book)
					{
						$msg='yes';
					}
					else
					{
						$msg='tidak_alokasi';
					}
				}
				else
				{
					$msg='no_alokasi';
				}
			}
			else
			{
				if($blok_plc <> '20')
				{
					$msg='tidak_alokasi';
				}
				else
				{
					$msg='yes';
				}
			}
		}
		
		/*
		if ($blok_allo=='40d')
		{
			$msg='no';
		}
		else if ($blok_allo=='40b')
		{
			$msg='no';
		}
		else
		{
			$msg='yes';
		}
		*/
	}
	else
	{	
		if (($blok_allo == '') OR ($blok_allo == NULL))
		{
			$msg='no_alokasi';
		}
		else
		{
			if(($blok_plc == NULL) OR ($blok_plc == ''))
			{
				if (($blok_ktg <> NULL) OR ($blok_ktg <> ''))
				{
					if ($blok_ktg == $id_book)
					{
						$msg='yes';
					}
					else
					{
						$msg='tidak_alokasi';
					}
				}
				else
				{
					$msg='no_alokasi';
				}
			}
			else
			{
				if($blok_plc <> '40d')
				{
					$msg='tidak_alokasi';
				}
				else
				{
					$msg='yes';
				}
			}
		}
	
		/*
		if ($blok_allo=='40d')
		{
			$msg='yes';
		}
		else
		{
			$msg='no';
		}
		*/
	}
	
	echo $msg;
?>