<?php
 $tl = xliteTemplate('home.htm');

 $db = getDB();
 $get_cont		= "SELECT ID_PEL, PELABUHAN FROM MASTER_PELABUHAN";
 $result_cont	= $db->query($get_cont);
 $row_cont		= $result_cont->getAll();
	
 $satu =  "<select id='box2View' multiple='multiple' style='height:300px;width:400px;'>";	
 foreach($row_cont as $row)
	{	
		$a_pel	= $row["ID_PEL"];
		$b_pel	= $row["PELABUHAN"];
		$value  = str_replace(' ','_',$b_pel);
		$c_list	.= "<option value='$a_pel' onclick=change2('$a_pel','$value')>[ $a_pel ] - $b_pel</option>";
									
	}
 $dua     = "</select>"; 
 $collect = $satu.$c_list.$dua;
 
 $get_cont2		= "SELECT KD_PELABUHAN, NM_PELABUHAN FROM V_MST_PELABUHAN@prodlinkx";
 $result_cont2	= $db->query($get_cont2);
 $row_cont2		= $result_cont2->getAll();
 
 $empat =  "<select id='box1View' multiple='multiple' style='height:300px;width:400px;'>";	
 foreach($row_cont2 as $row)
	{	
		$y_pel	= $row["KD_PELABUHAN"];
		$x_pel	= $row["NM_PELABUHAN"];
		$value  = str_replace(' ','_',$x_pel);
		$y_list	.= "<option value='$y_pel' onclick=change1('$y_pel','$value')>[ $y_pel ] - $x_pel</option>";
									
	}
 $lima     = "</select>"; 
 $collect2 = $empat.$y_list.$lima;

 $tl->assign("collect",$collect);
 $tl->assign("collect2",$collect2);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

