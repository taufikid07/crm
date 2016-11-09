<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

if(isset($_GET['q']) && !empty($_GET['q'])){
	
$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p
INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_perusahaan = p.id_perusahaan
WHERE p.id_perusahaan = '".$_GET['q']."' ORDER BY p.nama ASC";
$rph= $xoopsDB->query($qph);
	echo'<option value=""> Select Activity</option>';
	while($rowInvoice = $xoopsDB->fetchArray($rph)){
		echo'<option value="'.$rowInvoice['id_kegiatan'].'">'.$rowInvoice['nama_kegiatan'].'</option>';
	}

}
else if(isset($_GET['q2']) && !empty($_GET['q2'])){

$qkeg=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS pr 
INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS kn ON kn.id_perusahaan = pr.id_perusahaan
WHERE pr.id_perusahaan = '".$_GET['q']."' ORDER BY pr.nama ASC";
$rkeg= $xoopsDB->query($qkeg);
	echo'<option value=""> Select Contact</option>';
	while($rowQuote = $xoopsDB->fetchArray($rkeg)){
		echo'<option value="'.$rowQuote['id_kontak'].'">'.$rowQuote['nama'].'</option>';
	}	

}
?>