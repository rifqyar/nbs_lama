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
 

 header('Location: '.HOME.'billing.delivery/');
 
?>