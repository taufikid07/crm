<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================

$quote=$_GET['quote'];
$pr=$_GET['pr'];

$query = " SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt where id_quote =".$quote."";
$res = $xoopsDB->queryF($query);
$edata = $xoopsDB->fetchArray($res);
//Perusahaan
$qpr = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt 
INNER JOIN ".$xoopsDB->prefix("crm_perusahaan")." AS p ON qt.id_perusahaan = p.id_perusahaan 
WHERE qt.id_perusahaan='".$edata['id_perusahaan']."' AND qt.id_quote='".$edata['id_quote']."'";
$respr = $xoopsDB->query($qpr);
$dpr = $xoopsDB->fetchArray($respr);

//Alamat Perusahaan
$qalamat =$xoopsDB->query("SELECT a.alamat FROM ".$xoopsDB->prefix("crm_alamat")." AS a WHERE a.id_perusahaan=".$pr);
$dalamat = $xoopsDB->fetchArray($qalamat);

//Client
$qcl = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS np INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON np.id_kontak = k.id_kontak WHERE np.id_kontak='".$edata['id_kontak']."' AND np.id_quote='".$edata['id_quote']."' ORDER BY k.nama ASC";
$rescl = $xoopsDB->query($qcl);
$dcl = $xoopsDB->fetchArray($rescl);

$qmanager =" SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
WHERE g.`name` = 'Project Manager'";
$resm = $xoopsDB->query($qmanager);
$datamng = $xoopsDB->fetchArray($resm);
//pemanggilan PM
$query_group = "SELECT * FROM ".$xoopsDB->prefix("users")." AS u
INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.uid = u.uid
INNER JOIN ".$xoopsDB->prefix("groups")." AS g ON g.groupid = gul.groupid
WHERE u.uid = gul.uid AND gul.groupid = ".$datamng['groupid']." ORDER BY u.uname ASC ";
$res_group = $xoopsDB->query($query_group);
//Project Manager
$qpm = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt INNER JOIN ".$xoopsDB->prefix("users")." AS u ON qt.project_manager = u.uid WHERE uid='".$edata['project_manager']."'";	
$respm = $xoopsDB->query($qpm);
$dpm = $xoopsDB->fetchArray($respm);

// Kegiatan
$qkg = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$edata['id_kegiatan']."' AND qt.id_quote='".$edata['id_quote']."' ORDER BY k.nama_kegiatan ASC";
$reskg = $xoopsDB->query($qkg);
$dkp = $xoopsDB->fetchArray($reskg);

$matu=$edata['bahasa'];
$listpro='';
$qlistk = "SELECT * FROM ".$xoopsDB->prefix("crm_listproduct")." WHERE id_quote=".$edata['id_quote']." ORDER BY orderby ASC";
$reslist = $xoopsDB->query($qlistk);
$i=1;
while($dlist = $xoopsDB->fetchArray($reslist)){
	$qunit = "SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." WHERE id_listunit=".$dlist['unit'];
	$runit = $xoopsDB->query($qunit);
	$dlistunit = $xoopsDB->fetchArray($runit);	
	$bhs=$dlist['total'] == '' ? '' : $edata['bahasa'];
	$listpro.='
		<tr>
			<td style="text-align:center;">'.$dlist['quantity'].'</td>
			 <td style="text-align:center;">  '.$dlistunit['nama_unit'].'</td>
			 <td class="hidden-480"><p style="margin-left:'.$dlist['order'].'px;">'.$dlist['description'].'</p></td>
			 <td style="text-align:right;">'; 
				if($dlist['unit_price']<0){$listpro.='<span style="color:red;">'.$bhs.' '.number_format($dlist['unit_price']).'</span>';}
				else{$listpro.=''.$bhs.' '.number_format($dlist['unit_price']);}
			 $listpro.='
			 </td>
			 <td style="text-align:right;">';
				if($dlist['total']<0){$listpro.='<span style="color:red;">'.$bhs.' '.number_format($dlist['total']).'</span>';}
				else{$listpro.=''.$bhs.' '.number_format($dlist['total']);}
			   $listpro.='
			   </td>
		</tr>';
		$i++;}
$skrg=date('Y-m-d');
$quo=$edata['tgl_TerbitInvoice'];
$tgl_quote= date('d F Y', strtotime($quo));

//Kop surat
$query_group = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote='".$edata['id_quote']."'";
$result_group = $xoopsDB->query($query_group);
$statuskop='';
while($tl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt WHERE qt.id_quote='".$edata['id_quote']."' AND qt.kop_stat='".$edata['kopstat']."'";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['kopstat']==$gs['kopstat']){$selected='selected';}
	}
	$stat = $tl['kopstat'] == 0 ? "Contact Perusahaan" : "Contact Person";
	$statuskop.=' <option value="'.$tl['kopstat'].'" '.$selected.'>'.$stat.'</option> ';
}

//TTD surat
$query_group = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." WHERE id_quote='".$edata['id_quote']."'";
$result_group = $xoopsDB->query($query_group);
$ttds='';
while($ttl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt WHERE qt.id_quote='".$edata['id_quote']."' AND qt.ttdstat='".$edata['ttdstat']."'";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($ttl['ttdstat']==$gs['ttdstat']){$selected='selected';}
	}
	$stat = $ttl['ttdstat'] == 0 ? "Tanpa Tanda Tangan" : "Dengan Tanda Tangan";
	$ttds.=' <option value="'.$ttl['ttdstat'].'" '.$selected.'>'.$stat.'</option> ';
}
$pttd=$edata['status'] == '2' ? '
<label class="control-label">Tanda Tangan</label>
<div class="controls">
	<select name="ttdstat" class="input-large m-wrap" tabindex="1">
		'.$ttds.'
		<option value="">Pilih</option>
		<option value="1">Dengan Tanda Tangan</option>
		<option value="0">Tanpa Tanda Tangan</option>
	</select>
</div>
' : '<input type="hidden" name="ttdstat" value="'.$edata['ttdstat'].'" >';
// REKENING
$qrek = "SELECT * FROM ".$xoopsDB->prefix("crm_rekening")." WHERE id_quote='".$edata['id_quote']."'";
$resrek = $xoopsDB->query($qrek);
$datarek = $xoopsDB->fetchArray($resrek);
//Prepared Manager
$qpre = "SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE uid='".$uid."'";	
$respre = $xoopsDB->query($qpre);
$dpre = $xoopsDB->fetchArray($respre);

$kop = $edata['kopstat'] == 0 ? '
<table>
	<tr class="kpl_surat">
		<td style="width:30px;">To : </td>
		<td>'.$dpr['nama'].'</td>
	</tr>
	<tr class="kpl_surat">
		<td>&nbsp;</td>
		<td>'.$dalamat['alamat'].'</td>
	</tr>
	<tr class="kpl_surat">
		<td>&nbsp;</td>
		<td>'.$dpr['kota_perusahaan'].'</td>
	</tr>
	<tr class="kpl_surat2">
		<td>&nbsp;</td>
		<td>
		Phone&nbsp; : '.$dpr['kontak_perusahaan'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fax: '.$dpr['fax'].' </td>
	</tr>
	<tr class="kpl_surat">
		<td style="width:30px;" colspan="2">&nbsp;</td>
	</tr>
</table>' : 
'
<table>
	<tr class="kpl_surat">
		<td style="width:30px;">To :</td>
		<td>'.$dpr['nama'].'</td>
	</tr>
	<tr class="kpl_surat">
		<td>&nbsp;</td>
		<td>'.$dcl['nama'].'</td>
	</tr>
	<tr class="kpl_surat">
		<td style="width:30px;">&nbsp;</td>
		<td>'.$dalamat['alamat'].'</td>
	</tr>
	<tr class="kpl_surat2">
		<td>&nbsp;</td>
		<td>
		Phone&nbsp; : '.$dpr['kontak_perusahaan'].' &nbsp;&nbsp;&nbsp; Fax &nbsp; : '.$dpr['fax'].'</td>
	</tr>
	<tr class="kpl_surat">
		<td style="width:30px;" colspan="2">&nbsp;</td>
	</tr>
</table>
';

$ttdstatus = $edata['ttdstat'] == 0 ? '
<table class="ttd">
	<tr>
		<td> Bandung,   <b> '.$tgl_quote.'  </b></td>
	</tr>
	<tr>
		<td>
		<img src="img/TTD_kosong.png" style="width:60%">
		</td>
	</tr>
</table>' :
'<table class="ttd">
	<tr>
		<td> Bandung,   <b> '.$tgl_quote.'  </b></td>
	</tr>
	<tr>
		<td>
		<img src="img/TTD_basah.png" style="width:60%">
		</td>
	</tr>
</table>';
$check=$edata['ttdstat'] == '1' ? 'checked' : '0';

//echo $edata['project_manager'].'WOOI'.$uid.'';
$submit='';
if ($edata['status'] == '0' && $uid == $edata['project_manager']){
	$submit.='
	<form name="form_perusahaan" action="model/model_quote?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&uid='.$uid.'" class="form-horizontal" method="post">
	<button class="btn btn-success btn-large" type="submit" name="checked"><i class="icon-check"></i>Check</button>
	<a href="quote_edit?pr='.$pr.'&quote='.$quote.'" class="btn btn-warning btn-large hidden-pencil"> Edit<i class="icon-pencil"></i></a>
	<a href="quotation" class="btn btn-success btn-large hidden-print"> List <i class="icon-check"></i></a>
	</form>
	</form>	';
}else if ($edata['status'] == '0'){
	$submit.='
	<div class="alert alert-block alert-warning fade in">
	  <h4 class="alert-heading"><u>'.$duser['name'].'</u></h4>
	  <p><br>
		  Quotation ini Masih Menunggu Pengecekan dari Project Manager. <br>Terima kasih.
	  </p>
	</div>
	<a href="quotation" class="btn btn-success btn-large hidden-print"> List <i class="icon-check"></i></a>
	<a href="pdf/quote_no?quote='.$quote.'" data-toggle="tooltip" title="Print quote" class="btn btn-primary btn-large" target="_blank">Download<i class="icon-arrow-down"></i></a>';
}else if ($edata['status'] == '1' && $uid=='10' || $edata['status'] == '1' && $uid=='154'){
	$submit.='
	<form name="form_perusahaan" action="model/model_quote?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'&uid='.$uid.'" class="form-horizontal" method="post">
	<button class="btn btn-success btn-large" type="submit" name="approve"><i class="icon-check"></i>Approve</button>
	<form>
	<a href="quote_edit?pr='.$pr.'&quote='.$quote.'" class="btn btn-warning btn-large hidden-print"> Edit  <i class="icon-pencil"></i></a>
	<a href="pdf/quote_no?quote='.$quote.'" data-toggle="tooltip" title="Print quote" class="btn btn-primary btn-large" target="_blank">Download<i class="icon-arrow-down"></i></a>
	<a href="quotation" class="btn btn-success btn-large hidden-print"> List <i class="icon-check"></i></a>';
}else if ($edata['status'] == '1'){
	$submit.='
	<div class="alert alert-block alert-info fade in">
	  <h4 class="alert-heading"><u>'.$duser['name'].'</u></h4>
	  <p><br>
		  Quotation ini Masih Menunggu Approvel dari Direksi atau Finance. <br>Terima kasih.
	  </p>
	</div>
	<a href="pdf/quote_no?quote='.$quote.'" data-toggle="tooltip" title="Print quote" class="btn btn-primary btn-large" target="_blank">Download<i class="icon-arrow-down"></i></a>
	<a href="quotation" class="btn btn-success btn-large hidden-print"> List <i class="icon-check"></i></a>';
}else if ($edata['status']=='2'){
	$submit.='
	<div class="alert alert-block alert-success fade in">
	  <h4 class="alert-heading"><u>'.$duser['name'].'</u></h4>
	  <p><br>
		  Quotation ini sudah berhasil diapprove. <br>Apabila ada kesalahan sistem silahkan hubungi tim IT. Terima kasih.
	  </p>
	</div>
	<a href="quotation" class="btn btn-success btn-large hidden-print"> List  <i class="icon-check"></i></a>
	<a href="pdf/quote_yes?quote='.$quote.'" data-toggle="tooltip" title="Print quote" class="btn btn-primary btn-large" target="_blank">Download <i class="icon-arrow-down"></i></a>';
}

// INSERT REPLY
if (isset($_GET['add_reply'])){
	$koment	= $_GET['koment'];
	$reply	= $_GET['reply'];
	$rmail	= $_GET['rmail'];
	$nowr=date("Y-m-d H:i:s");
	$qrep ="INSERT INTO ".$xoopsDB->prefix("crm_reply")." VALUES ('', '$koment', '$uid', '$nowr', '$reply', '$rmail')";
	$xoopsDB->queryF($qrep);
	$irep=$xoopsDB->getInsertId();
	$qquote=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q
								INNER JOIN ".$xoopsDB->prefix("crm_komentar")." AS k ON k.id_quote = q.id_quote
								INNER JOIN ".$xoopsDB->prefix("crm_reply")." AS r ON r.id_komentar = k.id_komentar
								WHERE q.id_quote = ".$quote." AND k.id_komentar = ".$koment." AND r.id_reply = ".$irep);
	$dquote = $xoopsDB->fetchArray($qquote);	
	$qkm=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_komentar")." WHERE id_komentar=".$koment);
	$dkm = $xoopsDB->fetchArray($qkm);
	$qpos=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$uid);
	$dpos = $xoopsDB->fetchArray($qpos);
	$qurpl=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$rmail);
	$durpl = $xoopsDB->fetchArray($qurpl);
	require "phpmailer/PHPMailerAutoload";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $qpos['email']; //Reply to this email ID
	$email = $durpl['email']; // Recipients email ID
	$name = $durpl['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "No Quote ".$dquote['no_quote']." Post Reply";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
		$mail->IsHTML(true); // send as HTML
		$mail->Subject = "No Quote ".$dquote['no_quote']." - Balas ";
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
							Post Reply oleh '.$dpos['name'].' pada kode quotation '.$dquote['no_quote'].'.
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:30px;">
								<td width="200" style="background:#eeeeee; font-weight:800; padding:15px;">Kode Quotation</td>
								<td width="800" style="background:#eeeeee; padding:15px;">
									'.$dquote['no_quote'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:30px;">
								<td width="200" style="background:#eeeeee; font-weight:800; padding:15px;">Komentar</td>
								<td width="800" style="background:#eeeeee; padding:15px;">
									'.$dkm['komentar'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:30px;">
								<td width="200" style="background:#eeeeee; font-weight:800; padding:15px;">Balasan Komentar</td>
								<td width="800" style="background:#eeeeee; padding:15px;">
									'.$reply.'
								</td>
							  </tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> Untuk melihat secara detailnya silahkan masuk ke link berikut : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link post below
							
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
					E-mail ini untuk menginformasikan post reply. <br><br>
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
	header('Location:quote_view?pr='.$pr.'&quote='.$quote);
}
// KOMENTAR
if (isset($_GET['komen'])){
	$komentar	= $_GET['komentar'];
	$emailto	= $_GET['emailto'];
	$quotek		= $_GET['quote'];
	$pr			= $_GET['pr'];
	$now=date("Y-m-d H:i:s");
	$qcom ="INSERT INTO ".$xoopsDB->prefix("crm_komentar")." VALUES ('', '$quotek', '$uid', '$now', '$komentar', '$emailto')";
	$xoopsDB->queryF($qcom);
	
	$ikom=$xoopsDB->getInsertId();
	$qquote=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q
								INNER JOIN ".$xoopsDB->prefix("crm_komentar")." AS k ON k.id_quote = q.id_quote
								WHERE q.id_quote = ".$quotek." AND k.id_komentar = ".$ikom);
	$dquote = $xoopsDB->fetchArray($qquote);	
	$qpos=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$uid);
	$dpos = $xoopsDB->fetchArray($qpos);
	$qukom=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$emailto);
	$dukom = $xoopsDB->fetchArray($qukom);
	require "phpmailer/PHPMailerAutoload";
	$mail = new PHPMailer();
	$mail->Host     = "ssl://mail.gmail.com"; // SMTP server Gmail
	$mail->Mailer   = "smtp";
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "fujicon.link2015@gmail.com"; // 
	$mail->Password = "fujicon2015*"; // SMTP password
	$webmaster_email = $dpos['email']; //Reply to this email ID
	$email = $dukom['email']; // Recipients email ID
	$name = $dukom['uname']; // Recipient's name
	$mail->From = $webmaster_email;
	$mail->FromName = "No Quote ".$dquote['no_quote']." Post Comments";
	$mail->AddAddress($email,$name);
	//$mail->AddAddress('taufikid07@gmail.com','Finance');
	$mail->AddReplyTo($webmaster_email,"namawebmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
		$mail->IsHTML(true); // send as HTML
		$mail->Subject = "No Quote ".$dquote['no_quote']." - Komentar";
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
							Post Komentar oleh '.$dpos['name'].'.
							</td>
						  </tr>
						  <tr style="background:#eeeeee; padding:30px;">
								<td width="200" style="background:#eeeeee; font-weight:800; padding:15px;">Kode Quotation</td>
								<td width="800" style="background:#eeeeee; padding:15px;">
									'.$dquote['no_quote'].'
								</td>
							  </tr>
						  <tr>
						  <tr style="background:#eeeeee; padding:30px;">
								<td width="200" style="background:#eeeeee; font-weight:800; padding:15px;">Komentar</td>
								<td width="800" style="background:#eeeeee; padding:15px;">
									'.$komentar.'
								</td>
							  </tr>
						  <tr>
						  <tr>
							<td colspan="2"><div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px"><br> Untuk melihat secara detailnya silahkan masuk ke link berikut : <br>
							<center>
							<a target="_blank" href="'.XOOPS_URL.'/modules/crm/quote_view?pr='.$pr.'&quote='.$quote.'" style="background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer;"> 
							Click the link post below
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
					E-mail ini untuk menginformasikan post komentar. <br><br>
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
	header('Location:quote_view?pr='.$pr.'&quote='.$quote);
}
$komentar='';
$qkomen ="SELECT * FROM ".$xoopsDB->prefix("crm_komentar")." WHERE id_quote='".$edata['id_quote']."'";
$rkomen = $xoopsDB->query($qkomen);
while ($dkomen = $xoopsDB->fetchArray($rkomen)){
	$qukm=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$dkomen['uid']);
	$dukm = $xoopsDB->fetchArray($qukm);
	$datekom = date('d M Y H:i', strtotime($dkomen['tanggal'] ));
	$komentar.='
	<a href="#" class="pull-left">
		<img alt="" src="img/avatar1.jpg" class="media-object">
	</a>
	<div class="media-body">
		 <h4 class="media-heading">'.$dukm['name'].'  </h4>
		 <span>'.$datekom.' | <a href="#add'.$dkomen['id_komentar'].'" id="fancybox">Reply</a></span>
		 <p>'.$dkomen['komentar'].'</p><hr>';
		 $qreply ="SELECT * FROM ".$xoopsDB->prefix("crm_reply")." WHERE id_komentar='".$dkomen['id_komentar']."'";
		 $rreply = $xoopsDB->query($qreply);
		while ($dreply = $xoopsDB->fetchArray($rreply)){
			$qurpl=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$dreply['uid']);
			$durpl = $xoopsDB->fetchArray($qurpl);
			$daterep = date('d M Y H:i', strtotime($dreply['tanggal'] ));
			$komentar.='<div class="media">
				 <a href="#" class="pull-left">
					 <img alt="" src="img/avatar1.jpg" class="media-object">
				 </a>
				 <div class="media-body">
					 <h4 class="media-heading">'.$durpl['name'].' </h4>
					 <span>'.$daterep.'
					<p>'.$dreply['reply'].'</p>
				 </div>
			 </div>
			 <!--end media-->
			 <hr>';
		}
		$komentar.='
		<!-- =========================== ADD GROUP NAME ============================== -->
		<div id="add'.$dkomen['id_komentar'].'" style="display: none;">			
			<div class="judul_fan"><center><h3>Reply Comment</h3></center></div>
			<div class="row-fluid">
				<div class="span12">
					<!-- BEGIN VALIDATION STATES-->
					<div class="widget black">
						<div class="widget-title">
							<h4><i class="icon-reorder"></i> Comment <b>'.$dukm['name'].'</b></h4>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
								<a href="#portlet-config" data-toggle="modal" class="config"></a>
								<a href="javascript:;" class="reload"></a>
								<a href="javascript:;" class="remove"></a>
							</div>
						</div>
						<div class="widget-body form">
							<form class="cmxform form-horizontal" id="commentForm" action="quote_view?pr='.$pr.'&quote='.$quote.'" method="GET">
								<div class="control-group ">
									<label for="cname" class="control-label">Message</label>
									<textarea class="span12" rows="8" name="reply" required="required"></textarea>
								</div>					
								<div class="form-actions center">';
									if ($dkomen['uid']=='10'){ $komentar.='<input type="hidden" name="rmail" value="10">';
									}else if ($dkomen['uid']=='131'){ $komentar.='<input type="hidden" name="rmail" value="131">';
									}else { $komentar.='<input type="hidden" name="rmail" value="'.$dkomen['uid'].'">'; }
									$komentar.='
									<input type="hidden" name="quote" value="'.$quote.'">
									<input type="hidden" name="pr" value="'.$pr.'">
									<input type="hidden" name="koment" value="'.$dkomen['id_komentar'].'">
									<button class="btn btn-success" type="submit" name="add_reply">Kirim</button>
								</div>					
							</form>
						</div>
					</div>
				</div>
			</div>								
		</div>
		<!-- =========================== END GROUP NAME ============================== -->
	</div>';
}
// FORM KOMENTAR
$fkomen='';
$fkomen.='
<div class="post-comment">
	<h4>Post Comments</h4>
	<form action="quote_view?pr='.$pr.'&quote='.$quote.'" method="GET">
	 <label>Message</label>
	 <textarea class="span12" rows="6" name="komentar" required="required"></textarea>';
	 if ($uid=='10'){ $fkomen.='<input type="hidden" name="emailto" value="'.$edata['project_manager'].'">';}
	 else { $fkomen.='<input type="hidden" name="emailto" value="10">';}
	 $fkomen.='
	 <input type="hidden" name="quote" value="'.$quote.'">
	 <input type="hidden" name="pr" value="'.$pr.'">
	 <p><button class="btn" type="submit" name="komen">Post Comment</button></p>
	</form>
</div>';

// HISTORY
$qhstr = " SELECT * FROM ".$xoopsDB->prefix("crm_history")." AS h WHERE h.id_quote=".$quote." ORDER BY h.id_history DESC limit 10	";
$rhistory = $xoopsDB->query($qhstr);
$history='';
$i=0;
while($dhst = $xoopsDB->fetchArray($rhistory)){
	$qus=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." AS p WHERE uid=".$dhst['userid']);
	$duser = $xoopsDB->fetchArray($qus);
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q WHERE id_quote=".$dhst['id_quote']);
	$dquote = $xoopsDB->fetchArray($qqt);
	
	$zebra= ($i % 2==0) ? 'message-body msg-in' : 'message-body msg-out';
	$ndate = date('d F Y', strtotime($dhst['new_date'] ));
	$ntime = date('g:i a', strtotime($dhst['new_time'] ));
	$i++;
	$history.='
	<!-- Comment -->
	 <div class="msg-time-chat">
		 <a class="message-img" href="#"><i class="icon-bullhorn"></i></a>';
		 if ($dhst['kode_history']==1){
			 $history.='
			 <div class="message-body msg-out">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		 }else if ($dhst['kode_history']==2){
			 $history.='
			 <div class="message-body msg-edit">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		 }else if ($dhst['kode_history']==3){
			 $history.='
			 <div class="message-body msg-cek">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		 }else if ($dhst['kode_history']==4){
			 $history.='
			 <div class="message-body msg-in">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		 }else if ($dhst['kode_history']==5){
			 $history.='
			 <div class="message-body msg-dup">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		 }else {
			 $history.='
			 <div class="message-body msg-ok">
				 <span class="arrow"></span>
				 <div class="text">
					 <p class="attribution"><a href="#">'.$duser['uname'].'</a> at '.$ntime.', '.$ndate.'</p>
					 <p>'.$dhst['komentar'].'</p>
				 </div>
			 </div>';
		}
		$history.='
	 </div>
	 <!-- /comment -->
	';
} 

// ==================================================== MAIN PAGE ==================================================


echo'
	<div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     C R M
                 </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li>
                           <a href="#">Fujicon Priangan Perdana</a>
                           <span class="divider">/</span>
                       </li>
					   <li>
                           <a href="perusahaan">CRM</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                          View Quotation
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
              </div>
            </div>
            <!-- END PAGE HEADER-->
            <div class="row-fluid">
             <div class="span12">
                 <!-- BEGIN BLANK PAGE PORTLET-->
                 <div class="widget purple">
                     <div class="widget-title">
                         <h4><i class="icon-edit"></i> quotation Page </h4>
                       <span class="tools">
                           <a href="javascript:;" class="icon-chevron-down"></a>
                           <a href="javascript:;" class="icon-remove"></a>
                       </span>
                     </div>
                     <div class="widget-body">
                        <div class="row-fluid">
                             <div class="span12 header_quote">
                                <div class="row-fluid invoice-list">
                                    <div class="span6">
                                        <div class="head_logo">
                                            <img src="img/LOGO_FPP_2D.png" width="100" height="100">
                                        </div>
                                        <div class="head_address">
                                            <table style="color:#000;">	
                                                <tr>
                                                    <td class="name_address"> <b>FUJICON PRIANGAN PERDANA </b></td>
                                                </tr>
                                                <tr class="title_address">
                                                    <td>Metro Trade Center (MTC) Kav J 31</td>
                                                </tr>
                                                <tr class="title_address">
                                                    <td>Jl. Soekarno-Hatta 590, Bandung-40286.</td>
                                                </tr>
                                                <tr class="title_address">
                                                    <td>Phone : +62-22-7537676/+62-22-7537631 </td>
                                                </tr>
                                                <tr class="title_address">
                                                    <td>url &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: http://fujicon-japan.com</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="head_qn">
                                            QUOTATION
                                        </div>
                                        <div class="head_pqn">
                                            Page 1 of 1
                                        </div>
                                    </div>
                                    <div class="span6 head_qn">
                                </div>
                             </div>
                         </div>
                         </div>
                         <div class="space20"></div>
                         <div class="space20"></div>
                         <div class="row-fluid invoice-list">
                            <div class="span1">
                                &nbsp;
                            </div>
                             <div class="span5">
                                '.$kop.'
                             </div>
                         </div>
                         <div class="row-fluid invoice-list">
                             <div class="span5">
                             &nbsp;
                             </div>
                             <div class="span7">
                                 <div class="quot_code">
                                 Quotation #'.$edata['no_quote'].'
                                 </div>
                             </div>
                         </div>                     
                         <div class="row-fluid">
                            <div class="widget-body">
                                <div class="quotation">
                                    <div class="text-quot">
                                Thank you very much for the trush given to us.<br>
                                With all due rescpect, we here by submit quotation related activities are listed bellow.
                                    </div>
                                </div>
                            </div>
                         </div>
                         
                         <div class="row-fluid">
                            <div class="widget-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="header_th">
                                    <th><center> PIC / Sales Person </center></th>
                                    <th><center> Project Name / Code </center></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="content_th">
                                    <td><center> <b>'.$dpm['name'].'</b> </center></td>
                                    <td><center> <b>'.$dkp['nama_kegiatan'].'</b> <br> <i>'.$edata['deskripsi'].'</i> </center></td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                         </div>		
                         
                         <div class="row-fluid">
                            <div class="widget-body">
                             <table class="table table-bordered table-hover">
                                 <thead>
                                 <tr >
                                     <th style="text-align:center; width:100px;" colspan="2">Quantity</th>
                                     <th style="text-align:center;" class="hidden-480">Description</th>
                                     <th style="text-align:center;" class="hidden-480">Unit Price</th>
                                     <th style="text-align:center; width:170px;">Total</th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                    '.$listpro.'
                                 </tbody>
                             </table>
                            </div>
                         </div>
                         
                         <div class="row-fluid">
                            <div class="widget-body">
                            <div class="span6 pull-left">
                                
                                <table width="450" style="border-collapse: collapse; padding-top:5px;">
                                  <tr>
                                    <td style="border: solid 1px #000;">
                                        <table>
                                            <tr>
                                    <td colspan="3">Bank Account</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"> '.$datarek['nama_rek'].'</td>
                                  </tr>
                                  <tr>
                                    <td style="width:25%">Branch Name</td>
                                    <td style="width:2%">:</td>
                                    <td style="width:73%"> '.$datarek['branch'].'</td>
                                  </tr>
                                  <tr>
                                    <td>Account Name</td>
                                    <td>:</td>
                                    <td> '.$datarek['account_name'].'</td>
                                  </tr>
                                  <tr>
                                    <td>Account Number</td>
                                    <td>:</td>
                                    <td> '.$datarek['account_number'].'</td>
                                  </tr>
                                        </table>
                                    </td>
                                  </tr>
                                </table>
                            </div>
                             <div class="span4 invoice-block pull-right">
                                <table width="100%">
                                    <tr>
                                        <td style="width:45%; text-align:right; padding-left:30px; padding:10px;"> <b>SUB TOTAL</b> &nbsp;</td>
                                        <td style="width:10%; text-align:right; border:2px solid #ddd; border-right:2px solid #fff;"><b>'.$matu.'</b></td>
                                        <td style="width:45%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>'.number_format($edata['sub_total']).'</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:45%; text-align:right; padding-left:30px; padding:10px;"><b>TAX</b>10% &nbsp;</td>
                                        <td style="width:10%; text-align:right; border:2px solid #ddd; border-right:2px solid #fff;"><b>'.$matu.'</b></td>
                                        <td style="width:45%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>'.number_format($edata['tax']).'</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:45%; text-align:right; padding-left:30px; padding:10px;"> <b>TOTAL</b> &nbsp;</td>
                                        <td style="width:10%; text-align:right; border:2px solid #ddd; border-right:2px solid #fff;"><b>'.$matu.'</b></td>
                                        <td style="width:45%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>'.number_format($edata['total_max']).'</b></td>
                                    </tr>
                                </table>
                             </div>
                            </div>
                         </div>
                         <div class="space20"></div>
                         <div class="row-fluid">
                            <div class="widget-body">
                             <div class="span5 pull-left">
                                <table class="term" style="font-size:11px;">
                                    <tr>
                                        <td> Term and Condition : '.$edata['syarat'].' </td>
                                    </tr>
                                    <tr>
                                    <td style="width: 70%; text-align: center; border: solid 1px #fff; font-size:10; vertical-align:top;">
                                    
                                </tr>
                                </table>
                                <div class="space20"></div>
                                <div class="space20"></div>
                                <div class="space20"></div>
                                <div class="space20"></div>
                                <div class="space20"></div>
                                
                                <div class="widget green">
                                <div class="widget-title">
                                    <h4><i class="icon-reorder"></i> Menu Pilihan</h4>
                                    <span class="tools">
                                    <a href="javascript:;" class="icon-chevron-down"></a>
                                    <a href="javascript:;" class="icon-remove"></a>
                                    </span>
                                </div>
                                <div class="widget-body">
                                    <!-- BEGIN FORM-->
                                    <form action="model/model_quote?uid='.$uid.'&pr='.$edata['id_perusahaan'].'&quote='.$edata['id_quote'].'" class="form-horizontal" method="post">
                                        <div class="control-group">
                                            <label class="control-label">Kop Surat</label>
                                            <div class="controls">
                                                <select name="kopstat" class="input-large m-wrap" tabindex="1">
                                                    '.$statuskop.'
                                                    <option value="">Pilih</option>
                                                    <option value="0">Contact Perusahaan</option>
                                                    <option value="1">Contact Person</option>
                                                </select>
                                            </div>
                                            '.$pttd.'
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn blue" name="kops_quote"><i class="icon-ok"></i> Save</button>
                                            </div>
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>						
                             </div>
                             <div class="span4 pull-right">
                                '.$ttdstatus.'
                                <!-- START KOMEN-->
                                <div class="media">
                                     <h3>Comments</h3>
                                     <hr>
                                     '.$komentar.'							 
                                 </div>	 
                                '.$fkomen.'
                                <!-- END KOMEN-->
                             </div>
                            </div>
                         </div>
                         <div class="space20"></div>
                        <div class="row-fluid">
                            <div class="widget-body">
                            <div class="span12 footer_quote">
                                 <div class="text-center">
                                 <div class="space20"></div>
                                 <div class="space20"></div>
                                 <div class="space20"></div>
                                 <div class="space20"></div>
                                    <div class="footer_header">
                                         THANKS FOR INVESTING YOUR PRECIOUS TIME WITH US
                                    </div>
                                    <div class="title_footer">
                                        http://fujicon-japan.com
                                    </div>
                                 </div>
                             </div>
                         </div>
                         <div class="space20"></div>
                         <div class="space20"></div>
                         <div class="row-fluid text-center">
                         '.$submit.'
                         </div>
                            </div>
                        </div>
                 </div>
                 <!-- END BLANK PAGE PORTLET-->
             </div>
         </div>
 
			<!-- HISTORY-->
            <div class="row-fluid">
                 <!-- BEGIN CHAT PORTLET-->
                 <div class="widget red">
                     <div class="widget-title">
                         <h4><i id="dash-timeline-icon" class="icon-spinner icon-spin"></i> History for Quotation '.$edata['no_quote'].'</h4>
                                <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                                </span>
                     </div>
                     <div class="widget-body">
                         <div class="timeline-messages">
                             '.$history.'
                         </div>
                     </div>
                 </div>
                 <!-- END CHAT PORTLET-->
            </div>
                        
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
';

require(XOOPS_ROOT_PATH.'/footer.php');
?>
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />