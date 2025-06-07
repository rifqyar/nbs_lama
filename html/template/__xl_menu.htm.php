<?php if (!defined("XLITE_INCLUSION")) die(); ?><?
function get_menu($data, $parent = 0) {
	static $i = 1;
	$tab = str_repeat("\t\t", $i);
	if (isset($data[$parent])) {
		$html = "\n$tab<ul>";
		$i++;
		foreach ($data[$parent] as $v) {
			// $hiddenMenu = array('Receiving', 'Payment Cash', 'Nota Receiving', 'Stripping', 'Perencanaan Stuffing', 'Nota Stuffing', 'History Container Uster', 'Delivery');
			// if(!in_array($v['MENU'], $hiddenMenu) && $$_SESSION['ID_USER'] != '347'){
				$child = get_menu($data, $v['ID_MENU']);
				$html .= "\n\t$tab<li>";
				if($v['LINKNYA'] <> "")
					$html .= '<a href="'.$v['LINKNYA'].'">'.$v['MENU'].'</a>';
				else
					$html .= '<a href="#">'.$v['MENU'].'</a>';
				if ($child) {
					$i--;
					$html .= $child;
					$html .= "\n\t$tab";
				}
			// }
			$html .= '</li>';
		}
		$html .= "\n$tab</ul>";
		return $html;
	} else {
		return false;
	}
}
?><div id="myslidemenu" class="jqueryslidemenu"><?
	//echo $_SESSION["ID_GROUP"];
	$db = getDB();
	$sql = "SELECT * FROM TB_MENU WHERE OTORISASI LIKE '%".$_SESSION["ID_GROUP"]."%' ORDER BY PARENT_ID, MENU_ORDER";
	//echo $sql;
	$rs = $db->query($sql);
	while($row = $rs->fetchRow())
		$datanya[$row['PARENT_ID']][] = $row;
	//debug($datanya);
	//echo $datanya[0][0][MENU];
	/*foreach ($datanya as $key => $value){
		echo "Key: $key; Value: $value<br />\n";
		foreach ($datanya[$key] as $key2 => $value2)
			echo "&nbsp;&nbsp;&nbsp;Key2: $key2; Value2: $value2<br />\n";
	}*/
		
	//echo count($datanya[0])."=".count($datanya);
?><?php	if ($this->isBlockVisible('MENU_GLOBAL')): ?><?
	echo get_menu($datanya);
?><?php	endif; ?><br style="clear: left" /></div>