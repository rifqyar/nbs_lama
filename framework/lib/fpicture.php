<?
	
	function saveImageResized($src,$dst,$width,$height,$frame=false) {

		// Get new dimensions
		list($width_orig, $height_orig)  = getimagesize($src);
		
		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}
		
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$w = imagecolorallocate($image_p,255,255,255);
		imagefill($image_p,2,2,$w);
		
		$image 	 = imagecreatefromjpeg($src);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagedestroy($image);
		
		// frame..
		if ($frame) {
			$image = imagecreatefrompng($frame);
			imagealphablending($image,true);
			imagealphablending($image_p,true);
			imagecopy($image_p,$image,0,0,0,0,$width,$height);
			imagedestroy($image);
		}
		// Output
		imagejpeg($image_p, $dst, 90);
		imagedestroy($image_p);
	}

?>