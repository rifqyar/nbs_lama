<?php

//require_once "../nusoap/nusoap.php";
require_once SITE_LIB."/nusoap/nusoap.php";

//define('ILCS_AUTOCOLLECTION_SERVICES',"ilcsapi.wsdl");
//define('ILCS_AUTOCOLLECTION_SERVICES',SITE_LIB."/eService/ilcsapi.wsdl");
define('ILCS_AUTOCOLLECTION_SERVICES',SITE_LIB."eService/ilcsapi.wsdl");


//print_r(ILCS_AUTOCOLLECTION_SERVICES);die;
	

class eService
{  
	function call_wsdl($wsdl,$modul,$in_data,&$result)
	{
		$client 	= new nusoap_client($wsdl);

		$result = $client->call($modul, $in_data);
		$array_cont = array();
		if ($client->fault) {
			$result = "WSDL FAULT<pre>".var_dump($result)."</pre>";
			return false;
		}
		else {
			$error = $client->getError();
			if ($error) {
				$result = "WSDL ERROR<pre>" . $error . "</pre>";
				return false;
			}
			else {
				return true;
			}
			return false;
		}
	}
	  
	function call_wsdl_via_file($wsdl,$modul,$in_data,&$result)
	{
		$client = new nusoap_client($wsdl,true);

		$result = $client->call($modul, $in_data);
		$array_cont = array();
		if ($client->fault) {
			$result = "WSDL FAULT<pre>".var_dump($result)."</pre>";
			return false;
		} else {
			$error = $client->getError();
			if ($error) {
				$result = "WSDL ERROR.
								<br>Please check your wsdl url (get @nusoal_lib.php). 
								<br>If the wsdl url blank (in browser), some possible Error : 
								<br>1. No Access to wsdl, please check your connectivity. (please ping the wsdl ip).
								<br>2. Make sure that last wsdl script has no syntax errors (or comments some line in wsdl script typically index file).
								<br><pre>" . $error . "</pre>";
				return false;
			}
			else {
				
				if ($modul == 'releaseamount'){
					$res = 'releaseamountResult';
				} else if ($modul == 'holdamount'){
					$res = 'holdamountResult';				
				} else if ($modul == 'updateholdamount'){
					$res = 'updateholdamountResult';
				} else if ($modul == 'paymentrelease'){
					$res = 'paymentreleaseResult';
				}			
				
				$result["$res"]["billInfo1"] = str_replace("{lt}", "<", $result["$res"]["billInfo1"]);
				$result["$res"]["billInfo1"] = str_replace("{gt}", ">", $result["$res"]["billInfo1"]);
				
				return true;
			}
			return false;
		}
	}

	function test()
	{
		echo "masuk";
		$wsdl = 'ILCS_AUTOCOLLECTION_SERVICES';
		$client = new nusoap_client($wsdl,true);

		print_r($client);die;
	}

}

function array2xml($array, $xml = false){
    if($xml === false){
        $xml = new SimpleXMLElement('<root/>');
    }
    foreach($array as $key => $value){
        if(is_array($value)){
            array2xml($value, $xml->addChild($key));
        }else{
            $xml->addChild($key, $value);
        }
    }
    return $xml->asXML();
}


?>