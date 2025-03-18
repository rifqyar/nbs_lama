<?php

define('XLITE_COMPILED_PREFIX','__xl_');
define('XLITE_COMPILED_SUFFIX','.php');
define('XLITE_INCLUSION',1);

class XTemplate {
	var	$source;
	var $fromfile = true;
	var $vars;
	var $blocks;

	function XTemplate($fname) {
		$this->load($fname);
		$this->vars 	= array();
		$this->blocks 	= array();
	}

	function getCompiledName($fname) {
		if ($fname)
			$pi = pathinfo($fname);
		return $pi['dirname'] . '/' . XLITE_COMPILED_PREFIX . $pi['basename'] . XLITE_COMPILED_SUFFIX;
	}

	function load($fname) {
		if (isset($_GET['_forcerewrite']))
			$_SESSION['_forcerewrite'] = $_GET['_forcerewrite'];
		if (isset($_SESSION['_forcerewrite'])) {
			$rewrite  = $_SESSION['_forcerewrite']==1?true:false;
		} else $rewrite=false;
		//if ($rewrite) echo 'rewrite!'; else echo 'use compiled';

		$compiled = $this->getCompiledName($fname);
		if (!$rewrite && file_exists($compiled)) {
			// compiled..
			$this->source = $compiled;
		} else {
			require_once 'xlite.php';
			require_once 'xlitecompiler.php';

			// shall we compile..
			$tl = new XLiteCompiler();
			$tl->load( $fname, true );
			$tl->parse();

			$compsrc = '<'.'?php if (!defined("XLITE_INCLUSION")) die(); ?'.'>'.$tl->render();
			if ( file_put_contents($compiled,$compsrc) ) {
				$this->source = $compiled;
			} else {
				$this->source   = $tl->render();
				$this->fromfile = false;
			}
		}
	}

	function assign($key, $val=false, $prefix='') {
		if (is_array($key)) {
			foreach ($key as $k=> $v)
				$this->vars[$prefix.$k] = $v;
		} else {
			$this->vars[$prefix.$key] = $val;
		}
	}

	function get($key) {
		if (isset($this->vars[$key])) return  $this->vars[$key];
		return false;
	}

	function display( $blockname , $visible=true) {
		$this->blocks[$blockname] = $visible;
	}

	function isBlockVisible( $name ) {
		return isset($this->blocks[$name]) && $this->blocks[$name];
	}

	function includeURL($url) {
		$res = file_get_contents($url);
		if ($res===false) return false;

		echo $res;
		return true;
	}

	function renderSelectOptions($options,$selected,$list,$key=0,$label=1) {
		if ($key=='0') $key=0;
		if ($label=='1') $label=1;

		if (is_array($options)) {
			foreach($options as $opt) {
				echo '<option value="'.$opt['value'].'"';
				if ($opt['value']==$selected) echo ' selected="selected" ';
				echo '>'.$opt['label'].'</option>';
			}
		}

		if ($list && is_array($list)) {
			foreach($list as $opt) {
				echo '<option value="'.$opt[$key].'"';
				if ($opt[$key]==$selected) echo ' selected="selected" ';
				echo '>'.$opt[$label].'</option>';
			}
		}

	}

	function renderDateTime($type,$name,$v) {

		if ($v=='') {
			
			#############
			# DEFAULT JAM PELINDO DI SET 00-00-0000 00:00:00
			$d = getdate();

			$day = $d['mday'];
			$mon = $d['mon'];
			$yr  = $d['year'];
			$hh  = $d['hours'];
			$mm  = $d['minutes'];
			/*
			$day = '00';
			$mon = '00';
			$yr  = '0000';
			$hh  = '00';
			$mm  = '00';
			*/

			
			
		} else if (is_array($v)) {
			$yr  = isset($v['y'])?$v['y']:'0001';
			$mon = isset($v['m'])?$v['m']:'01';
			$day = isset($v['d'])?$v['d']:'01';
			$hh  = isset($v['hh'])?$v['hh']:'00';
			$mm  = isset($v['mm'])?$v['mm']:'00';
			$ss  = isset($v['ss'])?$v['ss']:'00';
		} else {
			list($yr,$mon,$day,$hh,$mm) = sscanf( $v, '%d-%d-%d %d:%d' );
			if (!$hh) $hh = '00';
			if (!$mm) $mm = '00';
		}

		## render..
		$res = '';

		if ($type=='month') {
			$months = array(
				'00'=>'00',
				'01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
				'05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug',
				'09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'
			);

			$res .= "<select name='$name"."[m]'>";
			foreach ($months as $k=>$v)
				if ($k==$mon)
					$res .= "<option value='$k' selected='selected'>$v</option>";
				else
					$res .= "<option value='$k'>$v</option>";

			$res .=	"</select> "
			     .  "<input name='$name"."[y]' type='text' size='4' maxlength='4' value='$yr' />  ";
		}

		if ($type=='datetime' || $type=='date') {
			$day = padd($day,2);
			$res .= "<input id='cal_".$name."_day' name='$name"."[d]' type='text' 
							size='2' maxlength='2' value='$day' 
							readonly='readonly' class='calendarreadonly' /> / ";
			
			
			$months = array(
				'00'=>'00',
				'01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr',
				'05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug',
				'09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'
			);

			foreach ($months as $k=>$v)
				if ($k==$mon)
					$res .= "<input id='cal_".$name."_month' 
									name='$name"."[m]' type='text' 
									size='2' maxlength='2' 
									value='$k' readonly='readonly' 
									class='calendarreadonly' /> / ";
									
			$res    .=  "<input id='cal_".$name."' name='$name"."[y]' 
								type='text' size='4' maxlength='4' 
								value='$yr' class='split-date' 
								readonly='readonly' /> ";
								
			$res    .= '<a href="javascript:void(0);" id="fd-but-cal_'.$name.'" 
							onclick="showCalendar(\'cal_'.$name.'\')" 
							onFocus="showCalendar(\'cal_'.$name.'\')" 
							class="date-picker-control"  
							title="Show Calendar"><span> </span></a>';
			
		}

		if ($type=='datetime' || $type=='time') {
			$hh = padd($hh,2);
			$mm = padd($mm,2);
			$res .= "<input id='cal_".$name."_hh' name='$name"."[hh]' type='text' size='2' maxlength='2' value='$hh' />:"
			     .  "<input id='cal_".$name."_mm' name='$name"."[mm]' type='text' size='2' maxlength='2' value='$mm' /> ";
		}

		echo $res;
	}

	function _render() {
		global $_q;
		global $_acl;


		$HOME	= HOME;
		$false 	= false;		// dirty hack..

		foreach ($this->vars as $k=>$v) $$k = $v;

		if ($this->fromfile) {
			require $this->source;
		} else {
			eval( '?'.'>'.$this->source);
		}
	}

	function renderToScreen() {
		$this->_render();
	}

	function render() {
		ob_start();
		$this->_render();
		return ob_get_clean();
	}

	function renderToFile($fname) {
		file_put_contents($fname,$this->render());
	}

}

?>