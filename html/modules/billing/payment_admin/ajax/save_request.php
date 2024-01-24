<?
$io = $_POST["IO"];
$nc = $_POST["NC"];
$paid_thru = $_POST["PAID_THRU"];
$date_doc = $_POST["DATE_DOC"];	
$payment = $_POST["PAYMENT"];
$remark = $_POST["REMARK"];
$user=$_SESSION['PENGGUNA_ID'];

$db 			= getDB('pnoadm');
$db2 			= getDB();
	
$query 			= "UPDATE 
							etg_preadvice 
				   SET 
							pay_flg = '$payment',
							dat_xtn_dt = to_char(to_date('$date_doc'|| '235959','dd-mm-yyyyhh24miss'),'yyyymmddhh24miss'),
							pay_thru_dt = to_char(to_date('$paid_thru' || '235959','dd-mm-yyyyhh24miss'),'yyyymmddhh24miss')
				   WHERE 
							ETG_PRE_IOMODE='$io' 
							AND ETG_PRE_CONTNO='$nc'";
$result			= $db->query($query);
//$row			= $result->getAll();	

$query = "INSERT 
				INTO LOG_ADMIN_PAYMENT 
				(HISTDATE, 
				NEW_PAID_THRU, 
				NEW_PAYMENT_STATUS, 
				NO_CONTAINER, 
				REMARK, 
				USER_ID) 
		  VALUES 
				(SYSDATE, 
				to_date('$paid_thru' || '235959','dd-mm-yyyyhh24miss'), 
				'$payment',
				'$nc',
				'$remark',
				'$user')";
$result			= $db2->query($query);
echo("Sukses");
die();
?>