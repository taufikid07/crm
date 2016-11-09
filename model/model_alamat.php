<?php
// Author : Taufikid.

require('../../../mainfile.php');

$now=strtotime(date('Y-m-d'));
$uid 			= $xoopsUser->getVar('uid');
$pr				= $_GET['pr'];
$alm			= $_GET['alm'];
$nama_alamat	= $_POST['nama_alamat'];
$status			= $_POST['status'];

$insert=$_POST['insert'];
$update=$_POST['update'];
$delete=$_GET['delete'];

if(isset($insert)){
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_alamat')." SET status='0' WHERE id_perusahaan=".$pr;
	$resx=$xoopsDB->queryF($xquery);
	
	$pquery="INSERT INTO ".$xoopsDB->prefix("crm_alamat")." VALUES ('','$pr','$nama_alamat','1')";
	$res=$xoopsDB->queryF($pquery);	
	if($res==true) {
		$message = 'Successfully';
		redirect_header('../address?pr='.$pr.'', 3, $message);
		} else {
			redirect_header('../address?pr='.$pr.'', 3, $message);
		}
}
if(isset($update)){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_alamat')." 
	SET alamat='$nama_alamat'
	WHERE id_alamat=".$alm;
	$resnp=$xoopsDB->queryF($uquery);

	if($resnp==true) {
		$message = 'Successfully';
		redirect_header('../address?uid='.$uid.'&pr='.$pr.'', 3, $message);
		} else {
			header('Location: ../address?uid='.$uid.'&pr='.$pr.'');
		}
}
if(isset($delete)){
	$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("crm_alamat")." WHERE id_alamat = '$alm' ");
}

// SEND CLIENT Quotation ==================================================
$on	=$_GET['on'];
if ($on=='send'){
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_alamat')." SET status='0' WHERE id_perusahaan=".$pr;
	$resx=$xoopsDB->queryF($xquery);
	
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_alamat')." SET status='1' WHERE id_alamat=".$alm;
	$resx=$xoopsDB->queryF($xquery);
}

redirect_header('../address?uid='.$uid.'&pr='.$pr.'', 3, 'Success');
?>
