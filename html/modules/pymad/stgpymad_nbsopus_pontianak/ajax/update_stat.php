<?
$db=getdb();
$id = $_POST['AID'];
$lid = sizeof($id)-1;
$conf=$_POST['PYMASTAT'];
//$SCHEME = $_SESSION['_siteconf']['name'];
//var_dump($id);exit;
//include(HOME.'framework/lib/acl.php');
//$acl = new ACL();
//$acl->load();
//$aclist = $acl->getLogin()->info; 
//$userid = $aclist['USERID'];
$userid = $_SESSION["NAMA_PENGGUNA"];
for($no=0;$no<=$lid;$no++){
$query="update BILLING.PYMA_STAGING set confirmation_status = '$conf',update_by='$userid',update_date=SYSDATE where trx_number = '".$id[$no]."'";
//var_dump($query);exit;
$db->query($query);
}
?>