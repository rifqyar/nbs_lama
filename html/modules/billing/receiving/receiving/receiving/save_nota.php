<?php
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

 $id=$_POST['idnt'];
 $user=$_SESSION["USER"];
 $ids=$_SESSION["PENGGUNA_ID"];
 $ip=getRealIpAddr();
 $date=date('d-m-Y H:i:s');
 //print_r($user." ".$ip." ");die;
 $db=  getDB();
 $query3="SELECT ID_REQ,to_char(TOTAL,'999,999,999,999.00') TOTAL_N FROM TB_NOTA_DELIVERY_H WHERE ID_NOTA ='$id' ";
 $result=$db->query($query3);
 $rowd=$result->fetchRow();
 $A=$rowd['ID_REQ'];
 $P=$rowd['TOTAL_N'];
 
 $query="UPDATE TB_NOTA_DELIVERY_H A SET STATUS='SAVED',ID_USER='$ids' WHERE A.ID_NOTA='$id'";
 $db->query($query);
 
 $query2="UPDATE TB_REQ_DELIVERY_H A SET STATUS='SAVED' WHERE A.ID_REQ='$A'";
 $db->query($query2);
 
$to = "ganda@indonesiaport.co.id,mulyadi@indonesiaport.co.id,dendy.wibowo@indonesiaport.co.id,donalda@indonesiaport.co.id,dicky@indonesiaport.co.id";

$subject = "[Nota Manual] SP2 Nomor ".$id;
$message = "Dear all, \n\nTelah tercreate Nota Manual SP2 dengan nomor ".$id." dan Total Nota Rp. ".$P."\n\nBest Regards\n\n".$user." @ ".$ip." ".$date;
$from = "notifikasi.billing@indonesiaport.co.id";
$headers = "From:" .$from;
mail($to,$subject,$message,$headers);

$success = mail($to,$subject,$message,$headers);
	   if (!$success) {
	       echo "Mail to " . $to . " is fail.";
	   }
	   else
	   echo "email sukses";

 header('Location: '.HOME.'billing.delivery/');
 
?>