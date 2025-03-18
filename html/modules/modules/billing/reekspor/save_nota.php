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
// $ip=getRealIpAddr();
 $date=date('d-m-Y H:i:s');
 //print_r($user." ".$ip." ");die;
 $db=  getDB();
 $query3="SELECT ID_REQUEST,TOTAL FROM BH_NOTA WHERE ID_NOTA ='$id' ";
 $result=$db->query($query3);
 $rowd=$result->fetchRow();
 $A=$rowd['ID_REQUEST'];
 $P=$rowd['TOTAL'];
 
 $query="UPDATE BH_NOTA A SET STATUS='SAVED',USER_ID_INVOICE='$ids',INVOICE_DATE=SYSDATE WHERE A.ID_NOTA='$id'";
 $db->query($query);
 
 $query2="UPDATE BH_REQUEST A SET STATUS='SAVED' WHERE A.ID_REQUEST='$A'";
 $db->query($query2);
 
 header('Location: '.HOME.'billing.reekspor/');
 
?>