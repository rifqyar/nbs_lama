<?php
class TabbedPane {

	var $name;
	var $tabs;
	var $width,$height;
	var $active;
	var $tabclass;
	var $panelstyle;
	
	function TabbedPane( $name,$width='100%',$height='',$style='',$panelstyle='border: solid 1px #333333;') {
		if ($name=='') $name='multitab'.rand(1000,9999);
		$this->name		= $name;
		
		$this->width	= $width;
		$this->height	= $height;
		
		if ($style!='') $style.='-';
		$this->tabclass = $style;
		
		$this->panelstyle = $panelstyle;
	}
	
	function add($tab,$title=false,$content='') {		
		$this->tabs[] = array(
									'id'=>$tab,
									'title'=> ($title)?$title:$tab,
									'text'=> $content,
								  );
	}

	function addReff($tab,$title=false,$reff=false) {		
		$this->tabs[] = array(
									'id'=>$tab,
									'title'=> ($title)?$title:$tab,
									'text'=> '<script language="javascript">moveContent("'.
												$reff.'","pane_'.$this->name.'_'.$tab.'");</script>',
								  );
	}
	
	function setActive( $tab ) {
		$this->active = $tab;
	}
	
	function render() {
		$tl = xliteTemplate('TabbedPane.default.htm','_main');
		
		$tl->assign('name',$this->name);
		$tl->assign('width',$this->width);
		$tl->assign('height',$this->height);
		$tl->assign('tab_class',$this->tabclass);
		$tl->assign('panelstyle',$this->panelstyle);
		
		#create tabs..
		$ids = array();
		foreach( $this->tabs as $tab ) {
			$ids[] = "'".$this->name.'_'.$tab['id']."'";
		}
		$tl->assign('tabs',$this->tabs);
				
		#active tab..
		$tl->assign('ids', implode(',',$ids));
		
		$tl->assign('activetab', ($this->active)?$this->active:$this->tabs[0]['id'] );
	
		return $tl->render();	
	}
}

function generateTabLink( $data, $selurl=false, $tabstyle='' ) {
	if (!is_array($data)) return '';
	
	if ($tabstyle!='') $tabstyle .= '-';
	
	$res = '<ul class="'.$tabstyle.'tabs">';
	foreach ($data as $tab) {
		$tabclass = (($selurl && $selurl==$tab['href']) || 
					 (isset($tab['active']) && $tab['active']) )?'tab-active':'tab-normal';

		$res .= '<li class="'.$tabclass.'" onclick="loadPage(\''.$tab['href'].'\')">'.
				'<span class="tab-item"><span class="tab-item-0">'.
				'<a href="'.$tab['href'].'" title="'.(isset($tab['tooltip'])?$tab['tooltip']:'click to view..').
				'" style="cursor:default;">'.$tab['label'].'</a></span><span class="tab-item-1"></span></span></li>';
	}
	$res .= '</ul>';
	
	return $res;
}


?>