<?
	require_lib('antixss/kses.php');
	
	global $KSES_FILTER;
	
	$KSES_FILTER['mini'] = array(
				'b' => array(),
                'i' => array(),
				'u' => array(),
				'ul' => array(),
				'ol' => array(),
				'li' => array(),
				'strong' => array(),
                'a' => array('href'  => array('minlen' => 3, 'maxlen' => 50),
                             'title' => array('valueless' => 'n')),
				'center'=>array(),
                'div' => array(
							 'id' => array('minlen'=>1, 'maxlen'=>50 ),
							 'align' => array('minlen'=>1, 'maxlen'=>15),
							 'class' => array('minlen' => 1, 'maxlen' => 50),
							 'style' => array('minlen' => 8, 'maxlen' => 250),
                             'dummy' => array('valueless' => 'y')),
                'span' => array(
							 'id' => array('minlen'=>1, 'maxlen'=>50 ),
							 'class' => array('minlen' => 1, 'maxlen' => 50),
							 'style' => array(),
                             'dummy' => array('valueless' => 'y')),
                'p' => array('align' => 1,
							 'style' => array('minlen' => 8, 'maxlen' => 250),
                             'dummy' => array('valueless' => 'y')),
                'img' => array(
							'src' => 1, 
							'border' => array('minval'=>0, 'maxval'=>10),
							'width' => array('minval'=>0, 'maxval'=>1000),
							'height' => array('minval'=>0, 'maxval'=>1000)
								), # FIXME
                'font' => array( 
							'size' =>array('minval' => 4, 'maxval' => 20),
							'color'=>array('minlen'=>3,'maxlen'=>7),
							'face'=>array('minlen'=>3,'maxlen'=>30) 
						   ),
                'br' => array(),
				'hr' => array( 'width'=>array('minlen' => 1, 'maxlen' => 4) ),
				);
				
	$KSES_FILTER['rich'] = array(
				'b' => array(),
                'i' => array(),
				'u' => array(),
				'ul' => array(),
				'ol' => array(),
				'li' => array(),
				'strong' => array(),
                'a' => array('href'  => array('minlen' => 3, 'maxlen' => 50),
                             'title' => array('valueless' => 'n')),
				'center'=>array(),
                'div' => array(
							 'id' => array('minlen'=>1, 'maxlen'=>50),
							 'align' => array('minlen'=>1, 'maxlen'=>15),
							 'class' => array('minlen' => 1, 'maxlen' => 50),
							 'style' => array('minlen' => 8, 'maxlen' => 250),
                             'dummy' => array('valueless' => 'y')),
                'span' => array(
							 'id' => array('minlen'=>1, 'maxlen'=>50 ),
							 'class' => array('minlen' => 1, 'maxlen' => 50),
							 'style' => array(),
                             'dummy' => array('valueless' => 'y')),
                'p' => array('align' => 1,
							 'style' => array('minlen' => 8, 'maxlen' => 250),
                             'dummy' => array('valueless' => 'y')),
                'img' => array(
							'src' => 1, 
							'border' => array('minval'=>0, 'maxval'=>10),
							'width' => array('minval'=>0, 'maxval'=>1000),
							'height' => array('minval'=>0, 'maxval'=>1000)
								), # FIXME
                'font' => array( 
							'size' =>array('minval' => 4, 'maxval' => 20),
							'color'=>array('minlen'=>3,'maxlen'=>7),
							'face'=>array('minlen'=>3,'maxlen'=>30) 
						   ),
                'br' => array(),
				'hr' => array( 'width'=>array('minlen' => 1, 'maxlen' => 4) ),
				'object' => array(
							'id' => array('minlen'=>1, 'maxlen'=>50),
							'classid' => array('maxlen'=>60),
							),
				'param'	=> array(
							'name'=> array('minlen'=>1, 'maxlen'=>50 ),
							'value'=> array('minlen'=>1, 'maxlen'=>250 ),
							),
				'embed' => array(
							'name'=> array('minlen'=>1, 'maxlen'=>50 ),
							'src'=> array('maxlen'=>250 ),
							'width' => array('minval'=>0, 'maxval'=>1000),
							'height' => array('minval'=>0, 'maxval'=>1000)
							),
				'code' => array(),
				'table' => array(
							'bgcolor'=>array('maxlen'=>8),
							'background'=>array('maxlen'=>250),
							'class' => array('minlen' => 1, 'maxlen' => 50),
							'style' => array('minlen' => 8, 'maxlen' => 250),
							'border' => array('minval'=>0, 'maxval'=>10),
							'width' => array('minval'=>0, 'maxval'=>1000),
							'height' => array('minval'=>0, 'maxval'=>1000)
					),
				'tr' => array(
							'bgcolor'=>array('maxlen'=>8),
							'class' => array('minlen' => 1, 'maxlen' => 50),
							'style' => array('minlen' => 8, 'maxlen' => 250),
					),
				'td' => array(
							'bgcolor'=> array('maxlen'=>8),
							'class' => array('minlen' => 1, 'maxlen' => 50),
							'style' => array('minlen' => 8, 'maxlen' => 250),
							'align'=> array('maxlen'=>10 ),
							'valign'=> array('maxlen'=>10 ),
					),
				
				);	
				
				
	function XSS_cleanxss($input, $filter='mini') {
		global $KSES_FILTER;
		
		$allowed = (isset($KSES_FILTER[$filter]))?$KSES_FILTER[$filter]:$KSES_FILTER['mini']; 
		
		//if (get_magic_quotes_gpc())
			$input = stripslashes($input);
		return kses((get_magic_quotes_gpc())?stripslashes($input):$input, 
			$allowed, array('http', 'https'));
	}	
?>