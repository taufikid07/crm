<?php
// Author : Taufikid.

require('../../../mainfile.php');

$now=strtotime(date('Y-m-d'));
$uid = $xoopsUser->getVar('uid');
$pr				= $_GET['pr'];
$kn				= $_GET['kn'];
$nama_kontak	= $_POST['nama_kontak'];
$hp				= $_POST['hp'];
$email			= $_POST['email'];

$insert=$_POST['insert'];
$update=$_POST['update'];
$delete=$_GET['delete'];

if(isset($insert)){
	$pquery="INSERT INTO ".$xoopsDB->prefix("crm_kontak")." VALUES ('','$pr','$nama_kontak','$hp','$email')";
	$res=$xoopsDB->queryF($pquery);	
	if($res==true) {
		$message = 'Successfully';
		redirect_header('../kontak?pr='.$pr.'', 3, $message);
		} else {
			redirect_header('../kontak?pr='.$pr.'', 3, $message);
		}
}
else if(isset($update)){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_kontak')." 
	SET nama_kontak='$nama_kontak',
		no_hp='$hp',
		email='$email'	
	WHERE id_kontak=".$kn;
	$resnp=$xoopsDB->queryF($uquery);

	if($resnp==true) {
		$message = 'Successfully';
		redirect_header('../kontak?uid='.$uid.'&pr='.$pr.'', 3, $message);
		} else {
			header('Location: ../kontak?uid='.$uid.'&pr='.$pr.'');
		}
}
else if(isset($delete)){
	$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("crm_kontak")." WHERE id_kontak = '$kn' ");
}
redirect_header('../kontak?uid='.$uid.'&pr='.$pr.'', 3, 'Success');
?>
