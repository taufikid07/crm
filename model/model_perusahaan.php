<?php
// Author : Taufikid.

require('../../../mainfile.php');

$now=strtotime(date('Y-m-d'));
$uid = $xoopsUser->getVar('uid');
$pr				= $_GET['pr'];
$nama_perusahaan	= str_replace("'","\'",$_POST['nama_perusahaan']);
$kode				= $_POST['kode_perusahaan'];
//$alamat  			= str_replace("'","\'",$_POST['alamat']);
$kontak				= $_POST['kontak_perusahaan'];
$fax				= $_POST['fax'];
$status				= $_POST['status_p'];

$insert=$_POST['insert'];
$update=$_POST['update'];
$delete=$_GET['delete'];

if(isset($insert)){
	$pquery="INSERT INTO ".$xoopsDB->prefix("crm_perusahaan")." VALUES ('','$nama_perusahaan','$kode','-','$kontak','$fax','$status')";
	$res=$xoopsDB->queryF($pquery);		
	if($res==true) {
		$message = 'Successfully';
		redirect_header('../perusahaan', 3, $message);
		} else {
			redirect_header('../perusahaan', 3, $message);
		}
}
else if(isset($update)){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_perusahaan')." 
	SET nama='$nama_perusahaan',
		kode_perusahaan='$kode',
		kontak_perusahaan='$kontak',
		fax='$fax',
		status='$status'	
	WHERE id_perusahaan=".$pr;
	$resnp=$xoopsDB->queryF($uquery);

	if($resnp==true) {
		$message = 'Successfully';
		redirect_header('../perusahaan', 3, $message);
		} else {
			header('Location: ../perusahaan');
		}
}
else if(isset($delete)){
	$xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("crm_perusahaan")." WHERE id_perusahaan = '$pr' ");
}
redirect_header('../perusahaan', 3, 'Success');
?>
