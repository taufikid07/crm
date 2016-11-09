<?php
// $Id: project_manager.php, 2008/03/06
// Author : Fujicon Priangan Perdana, Inc.
require('../../../mainfile.php');

// INSERT
$uid = $xoopsUser->getVar('uid');
$pr			= $_GET['pr'];
$quote		= $_GET['quote'];
$unt		= $_GET['unt'];
$nama_unit	= $_POST['nama_unit'];

$insert	= $_POST['insert'];
$update	= $_POST['update'];
$delete	= $_GET['del'];

// CREATE 
if(isset($insert)){
	$pquery="INSERT INTO ".$xoopsDB->prefix("crm_listunit")." VALUES ('','".$nama_unit."')";
	$res=$xoopsDB->queryF($pquery);	
	$message = 'Successfully';
	redirect_header('../quote_listunit?pr='.$pr.'&quote='.$quote.'', 3, $message);	
}

if (isset($update)){	
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_listunit')." 
	SET nama_unit='$nama_unit' WHERE id_listunit=".$unt;
	$resnp=$xoopsDB->queryF($uquery); 	
	$message = 'Successfully';
	redirect_header('../quote_listunit?pr='.$pr.'&quote='.$quote.'', 3, $message);
}

if($delete=='unit_delete'){
	$queryd = " DELETE FROM ".$xoopsDB->prefix("crm_listunit")." where id_listunit ='".$unt."'";
	$xoopsDB->queryF($queryd);
	$message = 'Successfully';
	redirect_header('../quote_listunit?pr='.$pr.'&quote='.$quote.'', 3, $message);
}




?>