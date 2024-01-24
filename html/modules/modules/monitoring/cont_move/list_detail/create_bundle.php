<?php
	$db = getDB();
	
	$list_cont		= explode('&',$_SERVER["QUERY_STRING"]);
	//echo $list_cont[0]."<br />" ;die;
	//debug($list_cont);die;
	$no_ukk		= $_GET["NO_UKK"]; 
	
	array_pop($list_cont);
	
	
	$jml_cont = count($list_cont);

	//echo $jml_cont;die;
	
	//echo $no_ukk;die;
	
	$query_no_bundle 	="SELECT NVL(MAX(BUNDLE)+1,1) AS JUM 
									  FROM RBM_LIST
									 ";
	$result_no_bundle	= $db->query($query_no_bundle);
	$no_bundle			= $result_no_bundle->fetchRow();
	$no_bundle			= $no_bundle['JUM'];
	//echo $no_bundle;die;
	//echo "tes";

	
	for($i=0; $i<($jml_cont); $i++)
	{
		$no_cont = substr($list_cont[$i],9);
		$box = substr($list_cont[$i],0,8);
		
			if($box=="box2View")
			{
				//$box1[$i]=$no_cont;
				
				$query_request2 ="UPDATE RBM_LIST
								   SET DASAR_BUNDLE = 'Y',
									   BUNDLE='$no_bundle',
                                       TYPE_CONT='DRY',
                                       STATUS_CONT='FCL'
								   WHERE NO_UKK = '$no_ukk'
									 AND NO_CONTAINER = '$no_cont'
									";
				$db->query($query_request2);	
				
				
			}
			else if($box=="box3View")
			{
				$query_request3 ="UPDATE RBM_LIST
								   SET BUNDLE='$no_bundle'
								   WHERE NO_UKK = '$no_ukk'
									 AND NO_CONTAINER = '$no_cont'
									";
				$db->query($query_request3);	
			
			}
				
			
			//echo substr($value,9). "<br>";
			//echo $value . "<br>";
		
		//echo "tes <br />";
		
		//$i=0;
		
		
		
		
		/*
		
		foreach ($list_cont as $value)
		  {
		  
			
			$no_cont = substr($value,9);
			$box = substr($value,0,8);
			
				if(Sbox=="box2View")
				{
					//$box1[$i]=$no_cont;
					
					$query_request ="UPDATE RBM_LIST
									   SET DASAR_BUNDLE = 'Y',
										   BUNDLE='$no_bundle'
									   WHERE NO_UKK = '$no_ukk'
										 AND NO_CONTAINER = '$no_cont'
										";
					$db->query($query_request);	
					
					
				}
				else
				{
					$query_request ="UPDATE RBM_LIST
									   SET BUNDLE='$no_bundle'
									   WHERE NO_UKK = '$no_ukk'
										 AND NO_CONTAINER = '$no_cont'
										";
					$db->query($query_request);	
					
				
				}
				
			$i++;
			
			//echo substr($value,9). "<br>";
			//echo $value . "<br>";
		  }
		  
		 */
		  
	}
	header('Location: '.HOME.'billing.rbm.list_detail/list_detail?id_vessel='.$no_ukk);
  //die;
	//foreach ($list_cont)
	//debug($list_cont);die;
	//$no_ukk=$_GET["no_ukk"];
	//$no_cont=$_GET["no_cont"];
	//$oog=$_GET["oog"];
	
	
	
	//die;
	
?>
