<?php
// Author : Taufikid.

require('../../../mainfile.php');

$uid = $xoopsUser->getVar('uid');

//inisialisasi
$pr 		= $_GET['pr'];
$keg 		= $_GET['keg'];
$quote		= $_GET['quote'];
$inv 		= $_GET['inv'];
$delete		= $_GET['del'];
$per 		= $_POST['perusahaan'];
$kgt 		= $_POST['kegiatan'];
$qt			= $_POST['quote'];
$pm 		= $_POST['project_manager'];
$kn 		= $_POST['kontak'];
$no_quote	= $_POST['no_quote'];
$no_invoice	= $_POST['no_invoice'];
$d_invoice	= $_POST['date_invoice'];
$t_invoive	= $_POST['pub_invoice'];
$d_kuitansi	= $_POST['date_kuitansi'];
$t_kuitansi	= $_POST['pub_kui'];
$bhs		= $_POST['bahasa'];
$sub_total	= $_POST['sub_total'];
$tax_percent= $_POST['tax_percent'];
$tax		= $_POST['tax'];
$total_max	= $_POST['total_max'];
$kopstat	= $_POST['kopstat'];
$ttdstat	= $_POST['ttdstat'];
$deskripsi  = str_replace("'","\'",$_POST['deskripsi']);
$deskripsi 	= nl2br($deskripsi); 
$syarat  	= str_replace("'","\'",$_POST['syarat']);
$syarat  	= nl2br($syarat);

// LIST PRODUCT
$qty		= $_POST['quantity'];
$unit		= $_POST['unit'];
$price		= $_POST['price'];
$tot		= $_POST['total'];
$spasi		= $_POST['spasi'];
$ceklist	= $_POST['ceklist'];
$orderby	= $_POST['orderby'];
$desc		= $_POST['des'];

// REKENING
$rek_name	= $_POST['rek_name'];
$bran_name	= $_POST['bran_name'];
$acc_name	= $_POST['acc_name'];
$acc_num	= $_POST['acc_num'];

//ACTION
$create		= $_POST['create'];
$insert		= $_POST['insert'];
$update		= $_POST['update'];
$update_pm	= $_POST['update_pm'];
$update_fin	= $_POST['update_fin'];
$revisi		= $_POST['revisi'];
$duplikat	= $_POST['duplikat'];
$checked	= $_POST['checked'];
$approve	= $_POST['approve'];

// CREATE ===========================================================================
if(isset($create)){
	header('Location: ../invoice_create_next?pr='.$per.'&keg='.$kgt.'&quote='.$qt.'');
}
// VIEW INVOICE ===================================================================
if(isset($_POST['view_invoice'])){
	header('Location: invoice_next?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'');
}

// Cek Alamat
$qalm = $xoopsDB->query("SELECT alm.id_alamat FROM ".$xoopsDB->prefix("crm_alamat")." AS alm 
WHERE alm.id_perusahaan='".$pr."' AND alm.status='1'");
$dalm = $xoopsDB->fetchArray($qalm);
$id_alamat = $dalm['id_alamat'];
// INSERT INVOICE ===================================================================
if(isset($insert)){	
	$sub_invoice = substr($no_invoice,0, 7);
	$sub_thn = substr($no_invoice,-4);
	$xx ="SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q WHERE q.id_perusahaan='".$pr."' AND q.no_invoice LIKE '".$sub_invoice."%' AND q.no_invoice LIKE '%".$sub_thn."'";
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q WHERE q.id_perusahaan='".$pr."' AND q.no_invoice LIKE '".$sub_invoice."%' AND q.no_invoice LIKE '%".$sub_thn."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	//Pengekcekan
	$cekd_inv = substr($dcari['no_invoice'],0, -8);
	$cek_invo = substr($no_invoice,0, -8);
	
	if (empty($xkegiatan) && $cek_invo!=$cekd_inv){
	$qx1="INSERT INTO ".$xoopsDB->prefix("crm_invoice")." VALUES (
	'','$quote','$pr','$keg','$kn','$pm','$no_invoice','$deskripsi','$d_invoice','$t_invoive','$bhs','$syarat',
	'$d_kuitansi','$t_kuitansi','$sub_total','$tax_percent','$tax','$total_max','0', '$kopstat','0','0','0', '$id_alamat')";
	$resx1=$xoopsDB->queryF($qx1);	
	$idi=$xoopsDB->getInsertId();
	// Rekening	
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening_inv")." VALUE ('', '$idi', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);
		foreach($_POST['des'] AS $d=>$desc){
			$d_qty		= $qty[$d];
			$d_unit		= $unit[$d];
			$d_price	= $price[$d];
			$d_total 	= $tot[$d];
			$desx  		= nl2br($desc);
			$desx1 		= str_replace("'","\'",$desx);
			$d_spasi 	= $spasi[$d];
			$d_orderby	= $orderby[$d];
			$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listinvoice")." VALUE ('', '$idi', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total', '$d_orderby')";
			$rlp=$xoopsDB->queryF($qlp);
		
		}	
	}else {
	$message = ' <span style="color:red; size:13px;"> Nomor kode Invoice <b>'.$cekd_inv.'</b> dari No quote '.$dcari['no_invoice'].', SUDAH TERDAFTAR. 
	<br> Silahkan periksa kembali ! Terimakasih</span>';
	redirect_header('../invoice_create?uid='.$uid.'&pr='.$pr.'&keg='.$keg.'&quote='.$quote.'', 4, $message);
	}
	
	// HISTORY Create
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$idi);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invo=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$t_invoive', '$datenow', '$timenow', '<i>Invoice</i> baru dengan nomor <b>$no_invo</b> berhasil ditambahkan. , '$quote','$idi','1')";
	$rtf=$xoopsDB->queryF($qtf);
	// Verifikasi Email	
	$querym=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$pm."");
	$datam = $xoopsDB->fetchArray($querym);
	//
	$qpro=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")." WHERE id_kegiatan=".$keg."");
	$dpro = $xoopsDB->fetchArray($qpro);
	//
	$quser=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$uid);
	$duser = $xoopsDB->fetchArray($quser);	
	 
	// EMAIL 
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Invoice Code : ".$no_invo." need checked PM";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logoimg');
	$mail->Subject = "Requires Check Invoice Code : ".$no_invo." need checked PM";
	$mail->Body = '
	<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
	  <tr>
		<td>
			<table style="width:100%; padding:20px; border-spacing: 10px;">
			  <tr>
				<td style="width:100%; margin:auto; margin-bottom:20px;">
				<img src="cid:logoimg" style="width:100%"/>
				</td>
			  </tr>
			  <tr>
				<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					<table style="width:100%;">
					  <tr>
						<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
						Berikut adalah data invoice yang telah diajukan oleh saudara/i '.$duser['name'].'.	
						</td>
					  </tr>
					  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800; padding:5px;">No. Invoice</td>
							<td width="800" style="background:#eeeeee; padding:5px;">
								'.$no_invo.'
							</td>
						  </tr>
					  <tr>
					  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800; padding:5px;">Project Name</td>
							<td width="800" style="background:#eeeeee; padding:5px;">
								'.$dpro['nama_kegiatan'].'
							</td>
						  </tr>
					  <tr>
						<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For furthet information, please click this link : <br>
						<center>
						<a target="_blank" href="'.XOOPS_URL.'/modules/crm/invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$idi.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
						Click the link below
						<a>
						</center>
						</div></td>
					  </tr>
				</table>
				</td>
			  </tr>
			  <tr>
				<td style="width:100%; margin:auto; text-align:center;">
				<hr />
					Jika Anda termasuk dalam penerima pesan ini, 
					harap informasikan kepada pengirim dan hapus pesan ini dari sistem Anda. Terimakasih <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
				</td>
			  </tr>
			</table>
		</td>
	  </tr>
	</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		if(!$mail->Send())
		{echo "Mailer Error: " . $mail->ErrorInfo;}  	
		else{	
			$message = 'Successfully Insert';
			redirect_header('../invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$idi.'', 2, $message);
		}
}

// EDIT INVOICE =================================================================
if (isset($update)){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_invoice')." 
	SET id_perusahaan='$pr',id_kontak='$kn', project_manager='$pm', id_kegiatan='$keg', deskripsi='$deskripsi', tgl_TerbitInvoice='$d_invoice', terbit_invoice='$t_invoive',
	syarat='$syarat', tgl_TerbitKuitansi='$d_kuitansi', terbit_kuitansi='$t_kuitansi', sub_total='$sub_total', tax_percent='$tax_percent', tax='$tax', total_max='$total_max'
	WHERE id_invoice=".$inv;	
	$resnp=$xoopsDB->queryF($uquery); 
	$query = " DELETE FROM ".$xoopsDB->prefix("crm_listinvoice")." WHERE id_invoice = '$inv' ";
	$resd 		= $xoopsDB->queryF($query);
	
	// History aupd_quote
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$inv);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invo=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Invoice</i> baru dengan nomor <b>$no_invo</b> berhasil diubah.', '$quote','$inv','2')";
	$rtf=$xoopsDB->queryF($qtf);
	
	if(!empty($_POST['id_act'])) {
		foreach($_POST['id_act'] AS $d=>$selected) {
			$des		= $desc[$d];
			$d_qty		= $qty[$d];
			$d_unit		= $unit[$d];
			$d_price	= $price[$d];
			$d_total 	= $tot[$d];
			$desx  		= nl2br($des);
			$desx1 		= str_replace("'","\'",$desx);
			$d_spasi 	= $spasi[$d];
			$d_orderby	= $orderby[$d];
			if(($selected=='aktif')){
		$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listinvoice")." VALUE ('', '$inv', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total', '$d_orderby')";
		$rlp=$xoopsDB->queryF($qlp);
			}
		}
	}
	$message = 'Successfully';
	redirect_header('../invoice_edit?uid='.$uid.'&pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'', 3, $message);
}
// DELETE INVOICE ==========================================================
if($delete=='inv_delete'){
	$queryd = " DELETE FROM ".$xoopsDB->prefix("crm_invoice")." where id_invoice=".$inv."";	
	$res=$xoopsDB->queryF($queryd);
	//History DELETE
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_nvoice")." WHERE id_invoice=".$inv);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invoice=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Invoice</i> dengan nomor <b>$no_invoice</b> berhasil dihapus.', '$quote','$inv','3')";
	$rtf=$xoopsDB->queryF($qtf);
	$message = 'Successfully';
	redirect_header('../invoice_next?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'', 3, $message);
}

// CHECK PM INVOICE ====================================================
if (isset($checked)){	
	$uinvoice= ' UPDATE '.$xoopsDB->prefix('crm_invoice')." SET status=1 WHERE id_quote=".$quote." AND id_invoice=".$inv."";
	$ress=$xoopsDB->queryF($uinvoice);
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q
			INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan
			WHERE q.id_quote=".$quote." AND q.id_invoice=".$inv."";
	$rpr = $xoopsDB->query($qpr);
	$dpr = $xoopsDB->fetchArray($rpr);
	
	$qpc 	= "SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."";
	$rpc 	= $xoopsDB->query($qpc);
	$dpc 	= $xoopsDB->fetchArray($rpc);
	$pmnama = $dpc['name'];
	
	// History Check
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$inv);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invo=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Invoice</i> dengan nomor <b>$no_invo</b> telah diperiksa oleh $pmnama.', '$quote','$inv','4')";
	$rtf=$xoopsDB->queryF($qtf);
	// Verifikasi Email	
	$querym=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$uid."");
	$datam = $xoopsDB->fetchArray($querym);
	
	//Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Invoice Code : ".$dpr['no_invoice']." need approval";
	$mail->AddAddress($email,$name);
	$mail->AddAddress('taufikid07@gmail.com','Finance');
	$mail->AddAddress('invoice@fujicon-japan.com','CHECK IT');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC('andhi@fujicon-japan.com', 'andhi@fujicon-japan.com');
	$mail->AddCC('finance@fujicon-japan.com', 'finance@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Invoice Code : ".$dpr['no_invoice']."";
	$mail->Body = '
	<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logog" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							<span style="color:red"> Email ini dalam perbaiakan tim IT</span> <br>
							Berikut adalah data Invoice yang telah di check oleh '.$dpc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Invoice</td>
								<td width="800" style="background:#eeeeee;">
									'.$dpr['no_invoice'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Nama Project</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Chek Invoice</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpc['uname'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							<a>
							</center>
							</div>
							</td>
						  </tr>
						</table>
					</td>
		  		</tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		$mail->Body;
		if(!$mail->Send())
			{echo "Mailer Error: " . $mail->ErrorInfo;}
		else{	
			$message = 'Successfully';
			redirect_header('../invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'', 3, $message);
		}
}

// APPROVE INVOICE ==========================================================================================
if (isset($approve)){	
	//$ttdstat	= $_POST['ttdstat'];
	//$uqx1= ' UPDATE '. $xoopsDB->prefix('crm_invoice')." SET status=0 WHERE id_perusahaan=".$pr." AND id_kegiatan=".$keg."";
	//$resuqxq=$xoopsDB->queryF($uqx1);
	if (!empty($inv)) {
    $uquos= ' UPDATE '. $xoopsDB->prefix('crm_invoice')." SET status=2, ttdstat='1' WHERE id_invoice=".$inv."";
	$ress=$xoopsDB->queryF($uquos);
	}
	// Email untuk PM
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan
			WHERE q.id_invoice=".$inv."";
	$rpr = $xoopsDB->query($qpr);
	$dpr = $xoopsDB->fetchArray($rpr);
	
	$qpc = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."";
	$rpc = $xoopsDB->query($qpc);
	$dpc = $xoopsDB->fetchArray($rpc);
	
	$qdir = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$uid."";
	$rdir = $xoopsDB->query($qdir);
	$direc = $xoopsDB->fetchArray($rdir);
	$direktur = $direc['name'];
		
	// History Approve
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$inv);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_inv=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Direksi ($direktur) telah menyetujui <i>invoice</i>  dengan nomor <i>invoice</i> <b>$no_inv</b>', '$quote','$inv','6')";
	$rtf=$xoopsDB->queryF($qtf);
	//Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "( Konfirmasi Approve Invoice )";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC('andhi@fujicon-japan.com', 'andhi@fujicon-japan.com');
	$mail->AddCC('finance@fujicon-japan.com', 'finance@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Information - Fujicon Priangan Perdana";
	$mail->Body = '
	<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logog" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							Berikut adalah data Invoice yang telah di Approve oleh '.$direc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
								<td width="800" style="background:#eeeeee;">
									'.$dpr['no_invoice'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Nama Project</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							<a>
							</center>
							</div></td>
						  </tr>
					</table>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; margin:auto; text-align:center;">
					<hr />
					E-mail ini untuk mengkonfirmasi bahwa pembuatan invoice telah disetujui oleh direksi. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		$mail->Body;
		if(!$mail->Send())
			{echo "Mailer Error: " . $mail->ErrorInfo;}
		else{	
			$message = 'Successfully';
			redirect_header('../invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'', 3, $message);
		}
}

// REVISI INVOICE =======================================================
if (isset($revisi)){
	//Quotation Lama
	$qilama=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$inv);
	$diqlama = $xoopsDB->fetchArray($qilama);
	
	// Validasi Jika Nomor quotation Sudah ada	
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS i	WHERE i.id_perusahaan='".$pr."' AND i.no_invoice='".$no_invoice."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	$noilama=$diqlama['no_invoice'];
	
 if ($no_invoice!=$dcari['no_invoice']){
	$qdupli="INSERT INTO ".$xoopsDB->prefix("crm_invoice")." VALUES ('','$quote','$pr', '$keg','$kontak', '$pm', '$no_invoice', '$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
	$resx = $xoopsDB->query($qdupli);
	$dcarix = $xoopsDB->fetchArray($resx);
	$rev=$xoopsDB->getInsertId();
	// Rekening 
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening")." VALUE ('', '$rev', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);	
	// History rev_quote
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$rev);
	$dqt = $xoopsDB->fetchArray($qqt);
	$noinvoice=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow = date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Berhasil merefisi <i>no quotation</i> <b>$noqlama</b> dengan nomor <i>quotation</i> <b>$noquote</b>', '$rev','','4')";
	$rtf=$xoopsDB->queryF($qtf);
	
	if(!empty($_POST['id_act'])) {
		foreach($_POST['id_act'] AS $d=>$selected) {
			$des		= $desc[$d];
			$d_qty		= $qty[$d];
			$d_unit		= $unit[$d];
			$d_price	= $price[$d];
			$d_total 	= $tot[$d];
			$desx  		= nl2br($des);
			$desx1 = str_replace("'","\'",$desx);
			$d_spasi 	= $spasi[$d];
			$d_orderby 	= $orderby[$d];
			
				if(($selected=='aktif')){
				$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listinvoice")." VALUE ('', '$rev', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total','$d_orderby')";
				$rlp=$xoopsDB->queryF($qlp);
				}
		}
	}
}else {
	$message = ' <span style="color:red;">No Invoice sudah terdaftar, Silahkan cek kembali ! <br> Terima Kasih</span>';
	redirect_header('../invoice_next?pr='.$pr.'', 3, $message);
}
	// ================================= EMAIL =====================================
	$querym = "SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$pm." ";
	$resm = $xoopsDB->query($querym);
	$datam = $xoopsDB->fetchArray($resm);
		
	$qpro1 = "SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")." WHERE id_kegiatan=".$keg." ";
	$rpro1 = $xoopsDB->query($qpro1);
	$dpro = $xoopsDB->fetchArray($rpro1);
	
	//EMAIL
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "( Revisi Invoice From Project Manager )";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	
		$mail->IsHTML(true); // send as HTML
		$mail->AddEmbeddedImage('headerd.png', 'logoq');
		$mail->Subject = "Revisi invoice code ".$no_quote." ";
		$mail->Body = '			
		
		<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logoq" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							Berikut adalah data Revisi Invoice yang diajukan oleh '.$datam['name'].'.
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Invoice</td>
								<td width="800" style="background:#eeeeee;">
									'.$noinvoice.'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Nama Project</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpro['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$rev.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							
							<a>
							</center>
							</div></td>
						  </tr>
					</table>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; margin:auto; text-align:center;">
					<hr />
					E-mail ini untuk mengkonfirmasi bahwa pembuatan revisi invoice telah dilakukan. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		
		';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		if(!$mail->Send())
			{echo "Mailer Error: " . $mail->ErrorInfo;}
		else{	
			$message = 'Successfully';
			redirect_header('../invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$rev.'', 3, $message);
		}
}

// DUPLIKAT INVOICE ===============================================
if (isset($duplikat)){	
	//Quotation Lama
	$qqlama=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_quote=".$quote." AND id_invoice=".$inv);
	$dqqlama = $xoopsDB->fetchArray($qqlama);
	
	$sub_invoice = substr($no_invoice,0, 7);
	$sub_thn = substr($no_invoice,-4);
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q WHERE q.id_perusahaan='".$per."' AND q.no_invoice LIKE '".$sub_invoice."%' AND q.no_invoice LIKE '%".$sub_thn."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	
	$noqlma=$dcari['no_invoice'];
	 if (!empty($xkeg) && $no_invoice!=$dcari['no_invoice']){
		 echo 'OK1';
		$qkeg=" INSERT INTO ".$xoopsDB->prefix("crm_kegiatan")." VALUE ('', '$pr', '$xkeg')";
		$rkeg=$xoopsDB->queryF($qkeg);
		$keg1=$xoopsDB->getInsertId();
		$qx1="INSERT INTO ".$xoopsDB->prefix("crm_invoice")." VALUES ('','$quote','$per','$keg1', '$kontak', '$uid', '$no_invoice','$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
		$resx1=$xoopsDB->queryF($qx1);	
	}else if (empty($xkeg) && $no_invoice!=$dcari['no_invoice']){
		echo 'OK2';
		$queryx="INSERT INTO ".$xoopsDB->prefix("crm_invoice")." VALUES ('','$quote','$per','$kgt', '$kontak', '$uid', '$no_invoice','$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0')";
		$resx=$xoopsDB->queryF($queryx);
	}else {
		$message = ' <span style="color:red">Kemungkinan Anda memasukan no invoice yang sudah ada, Silahkan coba kembali !<span>';
		redirect_header('../invoice_next?pr='.$per.'', 3, $message);
	}	
	$duplikat=$xoopsDB->getInsertId();
	// Rekening
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening")." VALUE ('', '$duplikat', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);
	// History dupli_quote
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$duplikat);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invo=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Berhasil menduplikasi <i>no invoice $noqlma</i> dengan nomor <i>invoice</i> <b>$no_invo</b>', '$quote','$duplikat','5')";
	$rtf=$xoopsDB->queryF($qtf);
	
	if(!empty($_POST['id_act'])) {
		foreach($_POST['id_act'] AS $d=>$selected) {
			$des		= $desc[$d];
			$d_qty		= $qty[$d];
			$d_unit		= $unit[$d];
			$d_price	= $price[$d];
			$d_total 	= $tot[$d];
			$desx  		= nl2br($des);
			$desx1 		= str_replace("'","\'",$desx);
			$d_spasi 	= $spasi[$d];
			$d_orderby 	= $orderby[$d];
				if(($selected=='aktif')){
				$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listinvoice")." VALUE ('', '$duplikat', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total', '$d_orderby')";
				$rlp=$xoopsDB->queryF($qlp);
				}
		}
	}
	// EMAIL
	$querym = "SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$pm." ";
	$resm = $xoopsDB->query($querym);
	$datam = $xoopsDB->fetchArray($resm);
	if (!empty($keg) && empty($keg1)){
		$kegiatan=$keg;
	}else if (!empty($keg1) && empty($keg)){
		$kegiatan=$keg1;
	}
	
	$qpro = "SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")." WHERE id_kegiatan=".$keg." ";
	$rpro = $xoopsDB->query($qpro);
	$dpro = $xoopsDB->fetchArray($rpro);
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Invoice Code : ".$no_invo." need checked PM";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logoq');
	$mail->Subject = "Invoice Code : ".$no_invo." need checked PM";
	$mail->Body = '		
		<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logoq" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							Berikut adalah data Quotation yang diajukan oleh '.$datam['name'].'.
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
								<td width="800" style="background:#eeeeee;">
									'.$no_invo.'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Nama Project</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpro['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/invoice_view?pr='.$per.'&quote='.$quote.'&inv='.$duplikat.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							<a>
							</center>
							</div></td>
						  </tr>
					</table>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; margin:auto; text-align:center;">
					<hr />
					E-mail ini untuk mengkonfirmasi bahwa pembuatan duplikasi <i>invoice</i> telah dilakukan. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		if(!$mail->Send())
		{echo "Mailer Error: " . $mail->ErrorInfo;}  
		else {
		$message = 'Successfully';
		redirect_header('../invoice_view?pr='.$per.'&keg='.$keg.'&quote='.$quote.'&inv='.$duplikat.'', 3, $message);
		}
}


// =================================================================== KOPS SURAT INVOICE ===========================================================
if (isset($_POST['kops_invoice'])){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_invoice')." 
	SET kopstat='$kopstat', ttdstat='$ttdstat' WHERE id_invoice=".$inv;
	$resnp=$xoopsDB->queryF($uquery);	
$message = 'Successfully';
redirect_header('../invoice_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'', 3, $message);
}
// =================================================================== TANDA TANGAN INVOICE ====================================================
if (isset($_POST['ttd_invoice'])){
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_invoice')." 
	SET ttdstat='$ttdstat' WHERE id_invoice=".$inv;
	$resnp=$xoopsDB->queryF($uquery); 	
$message = 'Successfully';
redirect_header('../invoice_view?uid='.$uid.'&pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'', 3, $message);
}

$off	=$_GET['off'];
if ($off=='non_send'){
	$uid		= $_GET['uid'];
	$pr 		= $_GET['per'];
	$keg		= $_GET['keg'];
	$q 			= $_GET['q'];
	$inv	= $_GET['inv'];
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_invoice')." SET send_client='0' WHERE id_invoice=".$inv;
	$resx=$xoopsDB->queryF($xquery);
	
	// Email untuk PM
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan WHERE q.id_quote=".$quote."";
	$rpr = $xoopsDB->query($qpr);
	$dpr = $xoopsDB->fetchArray($rpr);
	
	$qpc = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."";
	$rpc = $xoopsDB->query($qpc);
	$dpc = $xoopsDB->fetchArray($rpc);
	
	$qdir = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$uid."";
	$rdir = $xoopsDB->query($qdir);
	$direc = $xoopsDB->fetchArray($rdir);
	$direktur = $direc['name'];
	// History Approve
	$uid = $xoopsUser->getVar('uid');
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Data invoice Telah dikirimkan kepada client dengan nomer invoice', '$quote')";
	$rtf=$xoopsDB->queryF($qtf);
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "delivery invoice to the client with a invoice numbers : ".$dpr['no_quote']."";
	//$mail->AddAddress($email,$name);
	$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC($email, $email);
	//$mail->AddCC('andri@fujicon-japan.com', 'andri@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "delivery invoice to the client with a invoice numbers : ".$dpr['no_quote']."";
	$mail->Body = '
	<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logog" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							Berikut adalah data invoice yang telah di kirimkan kepada klien oleh '.$direc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No invoice</td>
								<td width="800" style="background:#eeeeee;">
									'.$dpr['no_quote'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Nama Project</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/admin/q_view_quote?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							<a>
							</center>
							</div></td>
						  </tr>
					</table>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; margin:auto; text-align:center;">
					<hr />
					E-mail ini untuk mengkonfirmasi bahwa pembuatan invoice telah disetujui oleh direksi. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		echo $mail->Body;
		if(!$mail->Send())
		{echo "Mailer Error: " . $mail->ErrorInfo;}
	$message = 'Successfully';
	redirect_header('invoice', 3, $message);
}
$on	=$_GET['on'];
if ($on=='send'){
	$uid		= $_GET['uid'];
	$pr 		= $_GET['per'];
	$keg		= $_GET['keg'];
	$q 			= $_GET['q'];
	$inv	= $_GET['inv'];
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_invoice')." SET send_client='1' WHERE id_invoice=".$inv;
	$resx=$xoopsDB->queryF($xquery);
	
	// Email untuk PM
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS i
			INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = i.id_kegiatan
			WHERE i.id_invoice=".$inv."";
	$rpr = $xoopsDB->query($qpr);
	$dpr = $xoopsDB->fetchArray($rpr);
	
	$qpc = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."";
	$rpc = $xoopsDB->query($qpc);
	$dpc = $xoopsDB->fetchArray($rpc);
	
	$qdir = " SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$uid."";
	$rdir = $xoopsDB->query($qdir);
	$direc = $xoopsDB->fetchArray($rdir);
	$direktur = $direc['name'];
	
	// History Pengiriman invoice
	$uid = $xoopsUser->getVar('uid');
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice=".$inv);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_invo=$dqt['no_invoice'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Data <i>invoice</i> dengan nomor <i>invoice</i> '$no_invo' telah dikirimkan kepada client dalam bentuk <i>hardfile</i>', '$q','$inv')";
	$rtf=$xoopsDB->queryF($qtf);
	
	
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Delivery invoice to the client with a invoice numbers : ".$dpr['no_quote']."";
	//$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC($email, $email);
	$mail->AddCC('andri@fujicon-japan.com', 'andri@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Delivery invoice to the client with a invoice numbers : ".$dpr['no_quote']."";
	$mail->Body = '
	<table style="width:100%; margin:auto; background: #ccc; background: -moz-linear-gradient( center top, #ccc 30%, #fff 100% ); background: -webkit-gradient( linear, left top, left bottom, color-stop(.2, #ccc), color-stop(1, #fff) );">
		  <tr>
			<td>
				<table style="width:100%; padding:20px; border-spacing: 10px;">
				  <tr>
					<td style="width:100%; margin:auto; margin-bottom:20px;">
					<img src="cid:logog" style="width:100%"/>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; background-color:#fff; margin:auto; border-radius: 10px; padding:5px;">
					
						<table style="width:100%;">
						  <tr>
							<td colspan="2" align="center" style="font-family:Arial, Helvetica, sans-serif; font-weight:800; padding:5px 0; ">
							Berikut adalah data Invoice yang telah di kirimkan kepada klien oleh '.$direc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Invoice</td>
								<td width="800" style="background:#eeeeee;">
									'.$dpr['no_invoice'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Project Name</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/view_invoice?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&inv='.$inv.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link below
							<a>
							</center>
							</div></td>
						  </tr>
					</table>
					</td>
				  </tr>
				  <tr>
					<td style="width:100%; margin:auto; text-align:center;">
					<hr />
					E-mail ini untuk mengkonfirmasi bahwa pembuatan invoice telah disetujui oleh direksi. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format"; //Text Body 
		echo $mail->Body;
		if(!$mail->Send())
		{echo "Mailer Error: " . $mail->ErrorInfo;}
	$message = 'Successfully';
	redirect_header('../invoice', 3, $message);
}

//header('Location: view_invoice?uid='.$uid.'&pr='.$pr.'&keg='.$keg.'&quote='.$inv.'&quote='.$idi.'');
//$message = 'Successfully';
//redirect_header('invoice_list?uid='.$uid.'&pr='.$pr.'&keg='.$keg.'&quote='.$inv.'', 3, $message);
xoops_cp_footer();
?>
