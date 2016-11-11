<?php
// Author : Taufikid.

require('../../../mainfile.php');

$uid = $xoopsUser->getVar('uid');

//Inisialisasi
$pr 		= $_GET['pr'];
$quote		= $_GET['quote'];
$pm 		= $_POST['project_manager'];
$kn 		= $_POST['kontak'];
$no_quote 	= $_POST['no_quote'];
$keg 		= $_POST['kegiatan'];
$xkeg		= $_POST['xkegiatan'];
$d_invoice	= $_POST['date_invoice'];
$t_invoive	= $_POST['pub_invoice'];
$d_kuitansi	= $_POST['date_kuitansi'];
$t_kuitansi	= $_POST['pub_kuitansi'];
$bhs		= $_POST['bahasa'];
$sub_total	= $_POST['sub_total'];
$tax		= $_POST['tax'];
$tax_percent= $_POST['tax_percent'];
$total_max	= $_POST['total_max'];
$codeq		= $_POST['codeq'];
$deskripsi  = str_replace("'","\'",$_POST['deskripsi']);
$deskripsi  = nl2br($deskripsi); 
$syarat  	= str_replace("'","\'",$_POST['syarat']);
$syarat  	= nl2br($syarat);
$ttdstat	= $_POST['ttdstat'];
$keterangan = $_POST['keterangan'];

//Product
$qty		= $_POST['quantity'];
$unit		= $_POST['unit'];
$price		= $_POST['price'];
$tot		= $_POST['total'];
$spasi		= $_POST['spasi'];
$ceklist	= $_POST['ceklist'];
$orderby	= $_POST['orderby'];
$desc		= $_POST['des'];

//Rekening
$rek_name	= $_POST['rek_name'];
$bran_name	= $_POST['bran_name'];
$acc_name	= $_POST['acc_name'];
$acc_num	= $_POST['acc_num'];


//ACTION
$insert		= $_POST['insert'];
$update		= $_POST['update'];
$update_pm	= $_POST['update_pm'];
$update_fin	= $_POST['update_fin'];
$revisi		= $_POST['revisi'];
$duplikat	= $_POST['duplikat'];
$checked	= $_POST['checked'];
$approve	= $_POST['approve'];
$delete		= $_GET['del'];
// Cek Alamat
$qalm = $xoopsDB->query("SELECT alm.id_alamat FROM ".$xoopsDB->prefix("crm_alamat")." AS alm 
WHERE alm.id_perusahaan='".$pr."' AND alm.status='1'");
$dalm = $xoopsDB->fetchArray($qalm);
$id_alamat = $dalm['id_alamat'];
// INSERT Quotation =================================================================
if(isset($insert)){
	$sub_quote = substr($no_quote,0, 7);
	$sub_thn = substr($no_quote,-4);
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q WHERE q.id_perusahaan='".$pr."' AND q.no_quote LIKE '".$sub_quote."%' AND q.no_quote LIKE '%".$sub_thn."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	if (!empty($xkeg) && $no_quote!=$dcari['no_quote']){
		$qkeg=" INSERT INTO ".$xoopsDB->prefix("crm_kegiatan")." VALUE ('', '$pr', '$xkeg')";
		$rkeg=$xoopsDB->queryF($qkeg);
		//id
		$keg1=$xoopsDB->getInsertId();
		$qx1="INSERT INTO ".$xoopsDB->prefix("crm_quotation")." VALUES ('','$pr','$kn', '$pm', '$no_quote', '$keg1','$deskripsi', '$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
		$resx1=$xoopsDB->queryF($qx1);	
	}else if (empty($xkeg) && $no_quote!=$dcari['no_quote']){
		$queryx="INSERT INTO ".$xoopsDB->prefix("crm_quotation")." VALUES ('','$pr','$kn', '$pm', '$no_quote', '$keg', '$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
		$resx=$xoopsDB->queryF($queryx);
	}else {
		$message = ' Kemungkinan Anda memasukan no quotation yang sudah ada, Silahkan coba kembali';
		//redirect_header('../quote_next?pr='.$pr.'', 3, $message);
	}	
	// Input id Quote	
	$idq=$xoopsDB->getInsertId();
	// CREATE REKENING
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening")." VALUE ('', '$idq', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);
	// CREATE HISTORY
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$idq);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$t_invoive', '$datenow', '$timenow', '<i>Quotation</i> baru dengan nomor <b>$no_quote</b> berhasil ditambahkan.', '$idq','','1')";
	$rtf=$xoopsDB->queryF($qtf);
	foreach($_POST['des'] AS $d=>$des){
		$d_qty		= $qty[$d];
		$d_unit		= $unit[$d];
		$d_price	= $price[$d];
		$d_total 	= $tot[$d];
		$desx  		= nl2br($des);
		$desx1 		= str_replace("'","\'",$desx);
		$d_spasi 	= $spasi[$d];
		$d_orderby	= $orderby[$d];
		$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listproduct")." VALUE ('', '$idq', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total', '$d_orderby')";
		$rlp=$xoopsDB->queryF($qlp);
	} 
	// EMAIL Create
	$querym = "SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$pm." ";
	$resm = $xoopsDB->query($querym);
	$datam = $xoopsDB->fetchArray($resm);
	if (!empty($keg) && empty($keg1)){
		$kegiatan=$keg;
	}else if (!empty($keg1) && empty($keg)){
		$kegiatan=$keg1;
	}
	$qpro = "SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")." WHERE id_kegiatan=".$kegiatan." ";
	$rpro = $xoopsDB->query($qpro);
	$dpro = $xoopsDB->fetchArray($rpro);	
	
	//off sedang percobaan
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Quotation Code : ".$no_quote." need checked PM";
	$mail->AddAddress($email,$name);
	$mail->AddAddress('darismanhendra@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logoq');
	$mail->Subject = "Quotation Code : ".$no_quote." need checked PM";
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
						  <tr style="background:#eeeeee; padding:10px;">
								<td width="400" style="background:#eeeeee; font-weight:800;">No Quotation</td>
								<td width="600" style="background:#eeeeee;">
									'.$no_quote.'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:10px;">
							<td width="400" style="background:#eeeeee; font-weight:800;">Project Name</td>
							<td width="600" style="background:#eeeeee;">
								'.$dpro['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:10px;">
							<td width="400" style="background:#eeeeee; font-weight:800;">Additional Information</td>
							<td width="600" style="background:#eeeeee;">
								'.$deskripsi.'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For furthet information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&keg='.$kegiatan.'&quote='.$idq.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa pembuatan quotation telah dilakukan. <br><br>
					<b> Copyright © 2016 PT. Fujicon Priangan Perdana. </b>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>';
		$mail->AltBody = "This is the body when user views in plain text format";
		if(!$mail->Send())
		{echo "Mailer Error: " . $mail->ErrorInfo;} 
		else
		{	
			$message = 'Successfully Insert';
			redirect_header('../quote_view?pr='.$pr.'&quote='.$idq.'', 3, $message);
		}
		
}

// UPDATE Quotation =============================================================
if (isset($update)){	
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_quotation')." 
	SET id_perusahaan='$pr', id_kontak='$kn', project_manager='$pm', id_kegiatan='$keg',deskripsi='$deskripsi', tgl_TerbitInvoice='$d_invoice', terbit_invoice='$t_invoive',bahasa='$bhs', syarat='$syarat', tgl_TerbitKuitansi='$d_kuitansi', terbit_kuitansi='$t_kuitansi', sub_total='$sub_total', tax_percent='$tax_p', tax='$tax', total_max='$total_max'
	WHERE id_quote=".$quote;
	$resnp=$xoopsDB->queryF($uquery); 	
	// Rekening =================
	$qrk=' UPDATE '. $xoopsDB->prefix('crm_rekening')." 
	SET nama_rek='$rek_name',branch='$bran_name', account_name='$acc_name', account_number='$acc_num'
	WHERE id_quote=".$quote;
	$resrk=$xoopsDB->queryF($qrk);
	$query 	= " DELETE FROM ".$xoopsDB->prefix("crm_listproduct")." WHERE id_quote = '$quote' ";
	$resd 	= $xoopsDB->queryF($query);
	
	// History Update
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	$qt= "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." ";
	$uid = $xoopsUser->getVar('uid');
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Quotation</i> dengan nomor <b>$no_quote</b> berhasil diubah.', '$quote','','2')";
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
			$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listproduct")." VALUE ('', '$quote', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total','$d_orderby')";
			$rlp=$xoopsDB->queryF($qlp);
			}
		}
	}
$message = 'Successfully Update';
redirect_header('../quote_edit?uid='.$uid.'&pr='.$pr.'&quote='.$quote.'', 3, $message);
}

// DELETE Quotation ==================================================
if($delete=='quo_delete'){	
	//History DELETE
	$uid = $xoopsUser->getVar('uid');
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Quotation</i> dengan nomor <b>$no_quote</b> berhasil dihapus.', '$quote','','3')";
	$rtf=$xoopsDB->queryF($qtf);
	
	$queryd = " DELETE FROM ".$xoopsDB->prefix("crm_quotation")." where id_quote=".$quote."";	
	$res=$xoopsDB->queryF($queryd);	
	$message = 'Successfully';
	redirect_header('../quotation', 3, $message);
}

// CHECK PM Quotation =====================================================
if (isset($checked)){
	$uquos= ' UPDATE '.$xoopsDB->prefix('crm_quotation')." SET status=1 WHERE id_quote=".$quote."";
	$ress=$xoopsDB->queryF($uquos);
	
	$qpr=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan WHERE q.id_quote=".$quote."");
	$dpr = $xoopsDB->fetchArray($qpr);
	
	$qpc=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."");
	$dpc = $xoopsDB->fetchArray($qpc);
	$pmnama = $dpc['name'];
	// History Check
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	$uid = $xoopsUser->getVar('uid');
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', '<i>Quotation</i> dengan nomor <b>$no_quote</b> telah diperiksa oleh $pmnama.', '$quote','','4')";
	$rtf=$xoopsDB->queryF($qtf);
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Konfirmasi Approve Quotation Code : ".$dpr['no_quote']."";
	$mail->AddAddress($email,$name);
	$mail->AddAddress('darismanhendra@gmail.com','Finance');
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	//$mail->AddCC('andhi@fujicon-japan.com', 'andhi@fujicon-japan.com');
	//$mail->AddCC('finance@fujicon-japan.com', 'finance@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Confirmation, Quotation Code : ".$dpr['no_quote']." has been approved";
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
							Berikut adalah data Quotation yang telah di check oleh '.$dpc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
								<td width="800" style="background:#eeeeee;">
									'.$dpr['no_quote'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Project Name</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['nama_kegiatan'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Additional Information</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpr['deskripsi'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
							<td width="200" style="background:#eeeeee; font-weight:800;">Check Quotation</td>
							<td width="800" style="background:#eeeeee;">
								'.$dpc['uname'].'
							</td>
						  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> For further information, please click this link : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa pembuatan quotation telah dilakukan. <br><br>
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
		else
		{	
			$message = 'Successfully Approved';
			redirect_header('../quote_view?pr='.$pr.'&quote='.$quote.'', 3, $message);
		}
	
}

// APPROVEL Quotation =============================================================
if (isset($approve)){	
	if (!empty($ttdstat)){
		$uquos= ' UPDATE '.$xoopsDB->prefix('crm_quotation')." SET status=2, ttdstat=1 WHERE id_quote=".$quote."";
		$ress=$xoopsDB->queryF($uquos);
	}else{
		$uquos= ' UPDATE '.$xoopsDB->prefix('crm_quotation')." SET status=2, ttdstat=1 WHERE id_quote=".$quote."";
		$ress=$xoopsDB->queryF($uquos);
	}
	// Email untuk PM
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q
			INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan
			WHERE q.id_quote=".$quote."";
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
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Direksi ($direktur) telah menyetujui <i>quotation</i>  dengan nomor <i>quotation</i> <b>$no_quote</b>', '$quote','','6')";
	$rtf=$xoopsDB->queryF($qtf);
	
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Konfirmasi Approve Quotation Code : ".$dpr['no_quote']."";
	$mail->AddAddress($email,$name);
	$mail->AddAddress('darismanhendra@gmail.com','Finance');
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	//$mail->AddCC('andhi@fujicon-japan.com', 'andhi@fujicon-japan.com');
	//$mail->AddCC('finance@fujicon-japan.com', 'finance@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Confirmation, Quotation Code : ".$dpr['no_quote']." has been approved";
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
							Berikut adalah data Quotation yang telah di Approve oleh '.$direc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
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
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa pembuatan quotation telah disetujui oleh direksi. <br><br>
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
		else
		{	
			$message = 'Successfully Approved';
			redirect_header('../quote_view?pr='.$pr.'&quote='.$quote.'', 3, $message);
		}
}

// REVISI QUOTATION ==============================================
if (isset($revisi)){	
	//Quotation Lama
	$qqlama=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqqlama = $xoopsDB->fetchArray($qqlama);
	
	// Validasi Jika Nomor quotation Sudah ada	
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q	WHERE q.id_perusahaan='".$pr."' AND q.no_quote='".$no_quote."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	$noqlama=$dqqlama['no_quote'];
	
 if ($no_quote!=$dcari['no_quote']){
	$qdupli="INSERT INTO ".$xoopsDB->prefix("crm_quotation")." VALUES ('','$pr','$kontak', '$pm', '$no_quote', '$keg', '$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
	$resx = $xoopsDB->query($qdupli);
	$dcarix = $xoopsDB->fetchArray($resx);
	$rev=$xoopsDB->getInsertId();
	// Rekening 
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening")." VALUE ('', '$rev', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);	
	// History rev_quote
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$rev);
	$dqt = $xoopsDB->fetchArray($qqt);
	$noquote=$dqt['no_quote'];
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
				$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listproduct")." VALUE ('', '$rev', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total','$d_orderby')";
				$rlp=$xoopsDB->queryF($qlp);
				}
		}
	}
}else {
	$message = ' <span style="color:red;">No Quotation sudah terdaftar, Silahkan cek kembali ! <br> Terima Kasih</span>';
	redirect_header('../quote_next?pr='.$pr.'', 3, $message);
}
	
	// ================================= EMAIL =====================================
	$querym = "SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$pm." ";
	$resm = $xoopsDB->query($querym);
	$datam = $xoopsDB->fetchArray($resm);
	$qpro1 = "SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")." WHERE id_kegiatan=".$keg." ";
	$rpro1 = $xoopsDB->query($qpro1);
	$dpro = $xoopsDB->fetchArray($rpro1);
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "( Cheked Revisi Quotation From Project Manager )";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('taufikid07@gmail.com','Leave');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logoq');
	$mail->Subject = "Revisi quotation code ".$no_quote." ";
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
							Berikut adalah data revisi quotation yang diajukan oleh '.$datam['name'].'.
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
								<td width="800" style="background:#eeeeee;">
									'.$noquote.'
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
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$rev.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa pembuatan revisi quotation telah dilakukan. <br><br>
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
		else
		{	$message = 'Successfully Revision';
			redirect_header('../quote_view?pr='.$pr.'&quote='.$rev.'', 3, $message);
		}
	
	
}

// DUPLIKAT QUOTATION ===============================================
if (isset($duplikat)){	
	//Quotation Lama
	$qqlama=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqqlama = $xoopsDB->fetchArray($qqlama);
	
	$sub_quote = substr($no_quote,0, 7);
	$sub_thn = substr($no_quote,-4);
	$qcari=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q WHERE q.id_perusahaan='".$pr."' AND q.no_quote LIKE '".$sub_quote."%' AND q.no_quote LIKE '%".$sub_thn."'");
	$dcari = $xoopsDB->fetchArray($qcari);
	$noqlma=$dcari['no_quote'];
		 if (!empty($xkeg) && $nok!=$dcari['no_quote']){
			$qkeg=" INSERT INTO ".$xoopsDB->prefix("crm_kegiatan")." VALUE ('', '$pr', '$xkeg')";
			$rkeg=$xoopsDB->queryF($qkeg);
			$keg1=$xoopsDB->getInsertId();
			$qx1="INSERT INTO ".$xoopsDB->prefix("crm_quotation")." VALUES ('','$pr','$kn', '$pm', '$nok', '$keg1','$deskripsi', '$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
			$resx1=$xoopsDB->queryF($qx1);	
		}else if (empty($xkeg) && $nok!=$dcari['no_quote']){
			$queryx="INSERT INTO ".$xoopsDB->prefix("crm_quotation")." VALUES ('','$pr','$kn', '$uid', '$nok', '$keg', '$deskripsi','$d_invoice', '$t_invoive', '$bhs', '$syarat', '$d_kuitansi', '$t_invoive', '$sub_total','$tax_percent', '$tax', '$total_max','0','0','0','$codeq','0', '$id_alamat')";
			$resx=$xoopsDB->queryF($queryx);
			
		}else {
			$message = ' <span style="color:red">Kemungkinan Anda memasukan no quotation yang sudah ada, Silahkan coba kembali !<span>';
			redirect_header('../quote_next?pr='.$pr.'', 3, $message);
		}		
	$duplikat=$xoopsDB->getInsertId();
	// Rekening
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_rekening")." VALUE ('', '$duplikat', '$rek_name', '$bran_name', '$acc_name', '$acc_num')";
	$rtf=$xoopsDB->queryF($qtf);
	// History dupli_quote
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$duplikat);
	$dqt = $xoopsDB->fetchArray($qqt);
	$noquote=$dqt['no_quote'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Berhasil menduplikasi <i>no quotation $noqlma</i> dengan nomor <i>quotation</i> <b>$no_quote</b>', '$duplikat','','5')";
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
				$qlp=" INSERT INTO ".$xoopsDB->prefix("crm_listproduct")." VALUE ('', '$duplikat', '$desx1', '$d_qty', '$d_unit', '$d_spasi', '$d_price', '$d_total', '$d_orderby')";
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
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $datam['email']; //Reply to this email ID
	$email = $datam['email']; // Recipients email ID
	$name = $datam['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Quotation Code : ".$noquote." need checked PM";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logoq');
	$mail->Subject = "Quotation Code : ".$noquote." need checked PM";
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
									'.$noquote.'
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
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$duplikat.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa pembuatan duplikasi <i>quotation</i> telah dilakukan. <br><br>
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
			else {
			$message = 'Successfully';
			redirect_header('../quote_next?pr='.$pr.'', 3, $message);
			}
}


// SEND CLIENT Quotation ==================================================
$off	=$_GET['off'];
if ($off=='non_send'){
	$uid		= $_GET['uid'];
	$quote		= $_GET['quote'];
	$pr 		= $_GET['pr'];
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_quotation')." SET send_client='0' WHERE id_quote=".$quote;
	$resx=$xoopsDB->queryF($xquery);
	
	// Email untuk PM
	$qpr = " SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q
			INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan
			WHERE q.id_quote=".$quote."";
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
	
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Data Quotation Telah dikirimkan kepada client dengan nomer quotation', '$quote','')";
	$rtf=$xoopsDB->queryF($qtf);
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Delivery quotation to the client with a quotation numbers : ".$dpr['no_quote']."";
	//$mail->AddAddress($email,$name);
	$mail->AddAddress('darismanhendra@gmail.com','Finance');
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC($email, $email);
	//$mail->AddCC('andri@fujicon-japan.com', 'andri@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Delivery quotation to the client with a quotation numbers : ".$dpr['no_quote']."";
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
							Berikut adalah data Quotation yang telah di batalkan pengiriman kepada klien oleh '.$direc['name'].'
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
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
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa quotation dibatalkan pengiriman kepada client. <br><br>
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
	redirect_header('quotation', 3, $message);
}
$on	=$_GET['on'];
if ($on=='send'){
	$uid		= $_GET['uid'];
	$quote		= $_GET['quote'];
	$pr 		= $_GET['pr'];
	$keterangan = $_POST['keterangan'];
	$xquery=' UPDATE '. $xoopsDB->prefix('crm_quotation')." SET keterangan='$keterangan', send_client='1' WHERE id_quote=".$quote;
	$resx=$xoopsDB->queryF($xquery);
	
	// Email untuk PM
	$qpr=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q
			INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON k.id_kegiatan = q.id_kegiatan
			WHERE q.id_quote=".$quote."");
	$dpr = $xoopsDB->fetchArray($qpr);
	
	$qpc=$xoopsDB->query(" SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$dpr['project_manager']."");
	$dpc = $xoopsDB->fetchArray($qpc);
	
	$qdir=$xoopsDB->query(" SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE u.uid=".$uid."");
	$direc = $xoopsDB->fetchArray($qdir);
	$direktur = $direc['name'];
	// History Pengiriman quotation
	
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote=".$quote);
	$dqt = $xoopsDB->fetchArray($qqt);
	$no_quote=$dqt['no_quote'];
	date_default_timezone_set('Asia/Jakarta');
	$datenow= date("Y-m-d");
	$timenow = date('H:i:s');
	$qtf=" INSERT INTO ".$xoopsDB->prefix("crm_history")." VALUE ('', '$uid', '$datenow', '$timenow', 'Data <i>quotation</i> dengan nomor <i>quotation</i> '$no_quote' telah dikirimkan kepada client dalam bentuk <i>hardfile</i>', '$quote','')";
	$rtf=$xoopsDB->queryF($qtf);
	
	// Email
	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "hendradarisman34@gmail.com"; // 
	$mail->Password = "darisman94"; // SMTP password
	//$mail->Username = "fujicon.link2015@gmail.com"; // 
	//$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpc['email']; //Reply to this email ID
	$email = $dpc['email']; // Recipients email ID
	$name = $dpc['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "Delivery quotation to the client with a quotation numbers : ".$dpr['no_quote']."";
	//$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	//$mail->AddAddress('invoice@fujicon-japan.com','Finance');
	$mail->AddAddress('darismanhendra@gmail.com');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->AddCC($email, $email);
	//$mail->AddCC('andri@fujicon-japan.com', 'andri@fujicon-japan.com');
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
	$mail->IsHTML(true); // send as HTML
	$mail->AddEmbeddedImage('headerd.png', 'logog');
	$mail->Subject = "Delivery quotation to the client with a quotation numbers : ".$dpr['no_quote']."";
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
							Berikut adalah data Quotation yang telah di kirimkan kepada klien oleh '.$direc['name'].'

							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:5px;">
								<td width="200" style="background:#eeeeee; font-weight:800;">No Quotation</td>
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
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
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
					E-mail ini untuk mengkonfirmasi bahwa quotation telah terkirim kepada client. <br><br>
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
	redirect_header('../quotation', 3, $message);
}

// Kops Surat Quotation =================================================
if (isset($_POST['kops_quote'])){
	$uid		= $_GET['uid'];
	$quote		= $_GET['quote'];
	$pr 		= $_GET['pr'];
	$kopstat	= $_POST['kopstat'];
	$ttdstat	= $_POST['ttdstat'];
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_quotation')." 
	SET kopstat='$kopstat', ttdstat='$ttdstat' WHERE id_quote=".$quote;
	$resnp=$xoopsDB->queryF($uquery); 	
$message = 'Successfully';
redirect_header('quote_view?pr='.$pr.'&quote='.$quote.'', 3, $message);
}
// Tanda Tangan Quotation ===============================================
if (isset($_POST['ttd_quote'])){
	$uid		= $_GET['uid'];
	$quote		= $_GET['quote'];
	$pr 		= $_GET['pr'];
	$ttdstat	= $_POST['ttdstat'];
	$uquery=' UPDATE '. $xoopsDB->prefix('crm_quotation')." 
	SET ttdstat='$ttdstat' WHERE id_quote=".$quote;
	$resnp=$xoopsDB->queryF($uquery); 	
$message = 'Successfully';
redirect_header('quote_view?pr='.$pr.'&quote='.$quote.'', 3, $message);
}
//$message = 'Successfully';
//redirect_header('view_quote?pr='.$pr.'', 3, $message);
xoops_cp_footer();
?>
