<?php
// Author : Fujicon Priangan Perdana, Inc.
require('../../../mainfile.php');
include_once "convert.php";
$uid = $xoopsUser->getVar('uid');


/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$pr 	= $_GET['pr'];
$keg 	= $_GET['keg'];
$quote	= $_GET['quote'];
$inv	= $_GET['inv'];

$query = " SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt where qt.id_quote =".$quote." AND qt.id_invoice =".$inv."";
$res = $xoopsDB->queryF($query);
$edata = $xoopsDB->fetchArray($res);

//Perusahaan
$qpr = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_perusahaan")." AS p ON qt.id_perusahaan = p.id_perusahaan WHERE qt.id_perusahaan='".$edata['id_perusahaan']."' AND qt.id_invoice='".$edata['id_invoice']."'";
$respr = $xoopsDB->query($qpr);
$dpr = $xoopsDB->fetchArray($respr);

//Client
$qcl = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS np INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON np.id_kontak = k.id_kontak WHERE np.id_kontak='".$edata['id_kontak']."' AND np.id_invoice='".$edata['id_invoice']."' ORDER BY k.nama ASC";
$rescl = $xoopsDB->query($qcl);
$dcl = $xoopsDB->fetchArray($rescl);


$qmanager =" SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
WHERE g.`name` = 'Project Manager'";
$resm = $xoopsDB->query($qmanager);
$datamng = $xoopsDB->fetchArray($resm);
//pemanggilan PM
$query_group = "SELECT * FROM
".$xoopsDB->prefix("users")." AS u
INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.uid = u.uid
INNER JOIN ".$xoopsDB->prefix("groups")." AS g ON g.groupid = gul.groupid
WHERE
u.uid = gul.uid AND
gul.groupid = ".$datamng['groupid']." ORDER BY u.uname ASC ";
$res_group = $xoopsDB->query($query_group);


//Prepared Manager
$qpre = "SELECT * FROM ".$xoopsDB->prefix("users")." AS u WHERE uid='".$uid."'";	
$respre = $xoopsDB->query($qpre);
$dpre = $xoopsDB->fetchArray($respre);

//Project Manager
$qpm = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("users")." AS u ON qt.project_manager = u.uid WHERE uid='".$edata['project_manager']."'";	
$respm = $xoopsDB->query($qpm);
$dpm = $xoopsDB->fetchArray($respm);

// Kegiatan
$qkg = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$edata['id_kegiatan']."' AND qt.id_invoice='".$edata['id_invoice']."' ORDER BY k.nama_kegiatan ASC";
$reskg = $xoopsDB->query($qkg);
$dkp = $xoopsDB->fetchArray($reskg);

//$matu=$edata['bahasa'];
$listpro='';
$qlistk = "SELECT * FROM ".$xoopsDB->prefix("crm_listinvoice")." WHERE id_invoice=".$edata['id_invoice']."";
	$reslist = $xoopsDB->query($qlistk);
	$i=1;
	while($dlist = $xoopsDB->fetchArray($reslist)){
		$qunit = "SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." WHERE id_listunit=".$dlist['unit']."";
		$runit = $xoopsDB->query($qunit);
		$dlistunit = $xoopsDB->fetchArray($runit);
		$str_level = $level == 0 ? _DR_ALL : $level;
		$bhs=$dlist['unit_price'] == '' ? '' : $edata['bahasa'];
	
$listpro.='
	<tr>
		<td style="font-size:11px; width:7%; text-align: left; border: solid 1px #000; padding:1px 5px;" align="center">'.$dlist['quantity'].'</td>
		<td style="font-size:11px; width: 13%; text-align: left; border: solid 1px #000; padding:1px 5px;" align="center">'.$dlistunit['nama_unit'].'</td>
		<td style="font-size:11px; width: 48%; border: solid 1px #000; padding:1px 5px;" align="left">
		<p style="margin-left:'.$dlist['order'].'px;">'.$dlist['description'].'</p>
		
		</td>
		<td style="font-size:11px; width: 1%; border: solid 1px #000; border-right:1px solid #fff; padding:1px 5px;" align="left">';
		if($dlist['unit_price']<0){$listpro.='<span style="color:red;">'.$bhs.'</span>';}
		else{$listpro.=''.$bhs;}
		$listpro.='
		</td>
		<td style="font-size:11px; width: 19%; border: solid 1px #000; padding:5px;" align="right">';
		if($dlist['unit_price']<0){$listpro.='<span style="color:red;">'.number_format($dlist['unit_price'], 0, '.', '.').')</span>';}
		else{$listpro.=''.number_format($dlist['unit_price'], 0, '.', '.');}
		$listpro.='
		</td>
		<td style="font-size:11px; width: 1%; border: solid 1px #000; border-right:1px solid #fff; padding:1px 5px;" align="left">';
		if($dlist['total']<0){$listpro.='<span style="color:red;">'.$bhs.'</span>';}
		else{$listpro.=''.$bhs;}
		$listpro.='
		</td>
		<td style="font-size:11px; width: 19%; text-align: left; border: solid 1px #000; padding:1px 5px;" align="right">';
		if($dlist['total']<0){$listpro.='<span style="color:red;">'.number_format($dlist['total'], 0, '.', '.').'</span>';}
		else{$listpro.=''.number_format($dlist['total'], 0, '.', '.');}
		$listpro.='
		</td>
	</tr>
	
	';
	$i++;
	}
$tgl_ayna=date('d-m-Y');
$q1=$edata['tgl_TerbitInvoice'];
$tglq1= date('d F Y', strtotime($q1));
$datenow = date('d F Y', strtotime($tgl_ayna));
$datet=date('Y/m/d g:i:s A ');
$datenow1 = date('Y F', strtotime($tgl_ayna));
$skrg=date('Y-m-d'); 
$datenow2 = date('Y.m.d', strtotime($tgl_ayna));


$kop = $edata['kopstat'] == 0 ? '
<table style="width: 100%; border: solid 1px #fff;">
	<tr>
		<td style="width:7%;"><b> To :</b></td>
		<td>
			<b> '.$dpr['nama'].' </b>
		</td>
	</tr>
	<tr>
		<td style="width:7%;">&nbsp;</td>
		<td style="width:50%;">'.$dpr['alamat'].'</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td> 
		Phone&nbsp; : '.$dpr['kontak_perusahaan'].' &nbsp;&nbsp;&nbsp; Fax &nbsp; : '.$dpr['fax'].'
		</td>
	</tr>
</table>

':'
<table style="width: 100%; border: solid 1px #ffffff; padding-left:20px;">
	<tr>
		<td><b> To :</b></td>
		<td><b> '.$dpr['nama'].' </b></td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td style="width:50%;"><b> '.$dcl['nama'].' </b></td>
	</tr>
	<tr>
		<td style="width:7%;">&nbsp;</td>
		<td style="width:50%;">'.$dpr['alamat'].'</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td> 
		Phone&nbsp;&nbsp; : '.$dcl['no_hp'].' &nbsp;&nbsp;Email&nbsp;: '.$dcl['email'].'
		</td>
	</tr>
</table>
';	

$ttdstatus = $edata['ttdstat'] == 0 ? '
<table style="width: 90%;border: solid 1px #fff; border-collapse: collapse; margin-left:-20px;" align="center">
	<tbody>
		<tr>
			<td style="font-size:11px; width: 70%; text-align: left; border: solid 1px #fff; font-weight:bold;" align="left"><i>Term and Condition : '.$edata['syarat'].'</i></td>
			<td style="width: 60%; text-align: center; border: solid 1px #fff; padding:5px; font-size:11;" align="center"><b>Bandung, '.$tglq1.'  </b></td>
		</tr>
		<tr>
			<td style="width: 70%; text-align: center; border: solid 1px #fff; font-size:11;" align="center">&nbsp;</td>
			<td style="width: 60%; text-align: center; border: solid 1px #fff; padding:0 5px;" align="center">
			<img src="img/TTD_kosong.png" style="width:62%">
			</td>
		</tr>
	</tbody>
</table>
':'
<table style="width: 90%;border: solid 1px #fff; border-collapse: collapse; margin-left:-20px;" align="center">
	<tbody>
		<tr>
			<td style="width: 70%; text-align: left; border: solid 1px #fff; font-weight:bold; font-size:12;" align="left"><i>Term and Condition : '.$edata['syarat'].'</i></td>
			<td style="width: 60%; text-align: center; border: solid 1px #fff; padding:5px;" align="center"><b>Bandung, '.$tglq1.'  </b></td>
		</tr>
		<tr>
			<td style="width: 70%; text-align: center; border: solid 1px #fff; font-size:11;" align="center">&nbsp;</td>
			<td style="width: 60%; text-align: center; border: solid 1px #fff; padding:0 5px;" align="center">
			<img src="img/TTD_basah.png" style="width:62%">
			</td>
		</tr>
	</tbody>
</table>
';

$kiri=$edata['no_invoice'];
$tengah=$edata['no_invoice'];
$kanan=$edata['no_invoice'];
$amount=$edata['total_max'];
$obj1 = new toWords( $amount , 'japanese yen');
$obj = new toWords( $amount , 'Rupiah');

$cek_matuang = $edata['bahasa']== 'Rp.' ? $obj : $obj1;

$rest = substr($cek_matuang->words,0, -1);  

$ttdstatus = $edata['tax_percent'] == 10 ? '
<span style="font-size:11px;">* This Price Including VAT 10% ('.$edata['bahasa'].'. '.number_format($tax, 0, '.', '.').')</span> ' :
'
<span style="font-size:11px;">* Nilai tidak termasuk pajak </span> ';
// ======================================= PDF ============================================
$tes2='
<style>

div.zone
{
    border: solid 2mm #66AACC;
    border-radius: 3mm;
    padding: 1mm;
    background-color: #FFEEEE;
    color: #440000;
}
div.zone_over
{
    width: 30mm;
    height: 35mm;
    overflow: hidden;
}
.tdtes {
	vertical-align:bottom;
}
.tdtes1 {
	vertical-align:middle;
	padding:10px 0;
}
.tdtes2 {
	padding:10px 0;
}


</style>';

// HTML
$tes2.='

<page style="font-size: 10pt">
<div style="background:url(./img/jajargenjang1b.png) center center no-repeat; position:absolute; z-index:0; width:100%; height:100%; bottom:445px; left:143px;"></div>
<div style="background:url(./img/jajargenjang2.png) center center no-repeat; position:absolute;z-index:1; width:100%; height:100%; bottom:293px; left:-80px;"></div>
<div style="margin-left:-5px; margin-top:10px;">
	<table style="width:100%;border: solid 3px #5544DD" align="center">
  <tr>
    <td rowspan="8" class="tdtes" style="width:17%"><img src="./img/kwitansi.png" alt="" style="width:100px; height:300px;"></td>
    <td colspan="4" class="tdtes" ><span style="color:#069;">No.</span> <span style="font-size:11px;"> &nbsp; # '.$edata['no_invoice'].' </span></td>
  </tr>
  <tr>
    <td class="tdtes" style="width:20%; color:#069">Received From</td>
    <td class="tdtes" style="width:2%">:</td>
    <td colspan="2" class="tdtes" style="width:60%; font-weight:bold; padding-left:10px;">'.$dpr['nama'].'</td>
  </tr>
  <tr>
    <td class="tdtes1" style="color:#069;">Amount</td>
    <td class="tdtes1">:</td>
    <td colspan="2" class="tdtes1" style="text-align:center; font-size:11px; width:280px;"> # '.$rest.' #</td>
  </tr>
  <tr>
    <td style="color:#069; vertical-align:top; padding-top:18px;">
    For Payment of
    </td>
    <td style="color:#069; vertical-align:top; padding-top:18px;">:</td>
    <td colspan="2" class="tdtes" style="padding-left:10px; width:400px;">
    '.$dkp['nama_kegiatan'].' <br />
    <span style="font-style:italic; font-size:8px;">'.$edata['deskripsi'].'</span><br />
    <span style="font-size:10px;">* This price including VAT 10% ('.$edata['bahasa'].''.number_format($edata['tax'], 0, '.', '.').')</span> 
     
    
    </td>
  </tr>
  <tr>
    <td colspan="3" class="tdtes">&nbsp;</td>
    <td class="tdtes" style="text-align:left; padding-left:260px;"> Bandung, '.$tglq1.'</td>
  </tr>
  <tr>
    <td colspan="4" class="tdtes2" style="vertical-align:bottom"><span style="color:#069;"> '.$edata['bahasa'].' </span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="font-size:18px; font-weight:bold;">'.number_format($edata['total_max'], 0, '.', '.').',- </span>
    </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td style="text-align:right; padding-right:20px; vertical-align:bottom;"><u><span style="font-weight:bold;"> Andhitiawarman Nugraha </span></u>
    </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td style="text-align:right; padding-right:45px; vertical-align:top;"><b>President Director</b></td>
  </tr>
</table>
</div>


<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->
</page>

';
$content = $tes2;
// echo"$content";
// convert in PDF
require_once('html2pdf/html2pdf.class.php');
try
{
	$html2pdf = new HTML2PDF('P', 'A4', 'fr');
	//$html2pdf->setDefaultFont('arialunicid0'); //add this line
	$html2pdf->writeHTML($tes2, isset($_GET['vuehtml']));
	$html2pdf->Output(''.$datenow2.'_'.$dkp['nama_kegiatan'].'.pdf');
}
catch(HTML2PDF_exception $e) {	
	echo $tes;
	exit;
}