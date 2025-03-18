<?


class AJAX {
	var $command;
	var $content;
	
	function AJAX() {
		$this->command = array();
		$this->content = '';
	}
	
	function setContent( $v ) {
		$this->content = $v;
	}
	
	function addCommand( $v ) {
		$this->command[] = $v;
	}
	
	function encode( $ss ) {
		return str_replace('+','%20',urlencode($ss));
	}
	
	function getJSON() {
		return "['".$this->encode($this->content)."','".$this->encode(implode("\n",$this->command))."'];";
	}
}
?>