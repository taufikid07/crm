<?php
// Author : Fujicon Priangan Perdana, Inc.
require('../../../mainfile.php');
$uid = $xoopsUser->getVar('uid');


/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$quote=$_GET['quote'];
$pr=$_GET['pr'];
$query = " SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt where id_quote =".$quote."";
$res = $xoopsDB->queryF($query);
$edata = $xoopsDB->fetchArray($res);

//Perusahaan, PM, Kontak, Kegiatan, Alamat
$qpr = "SELECT p.`status`, p.fax, p.kontak_perusahaan, p.nama,
us.`name`, kg.nama_kegiatan
FROM ".$xoopsDB->prefix("crm_quotation")." AS qt 
INNER JOIN ".$xoopsDB->prefix("crm_perusahaan")." AS p ON qt.id_perusahaan = p.id_perusahaan 
INNER JOIN fujicon_users AS us ON us.uid = qt.project_manager
INNER JOIN fujicon_crm_kegiatan AS kg ON kg.id_kegiatan = qt.id_kegiatan
WHERE qt.id_perusahaan='".$edata['id_perusahaan']."' AND qt.id_quote='".$edata['id_quote']."'";
$respr = $xoopsDB->query($qpr);
$dpr = $xoopsDB->fetchArray($respr);

//Alamat Perusahaan
$qalamat =$xoopsDB->query("SELECT a.alamat FROM ".$xoopsDB->prefix("crm_alamat")." AS a WHERE a.id_perusahaan=".$pr." AND a.status=1");
$dalamat = $xoopsDB->fetchArray($qalamat);

//Client
$qcl = "SELECT k.nama_kontak, k.no_hp, k.email FROM ".$xoopsDB->prefix("crm_quotation")." AS np INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON np.id_kontak = k.id_kontak WHERE np.id_kontak='".$edata['id_kontak']."' AND np.id_quote='".$edata['id_quote']."' ORDER BY k.nama ASC";
$rescl = $xoopsDB->query($qcl);
$dcl = $xoopsDB->fetchArray($rescl);

//$matu=$edata['bahasa'];
$listpro='';
$qlistk = "SELECT lp.description,lp.quantity,lp.unit,lp.order,lp.unit_price,lp.total,lp.orderby 
FROM ".$xoopsDB->prefix("crm_listproduct")." AS lp WHERE id_quote=".$edata['id_quote']." ORDER BY orderby ASC";
$reslist = $xoopsDB->query($qlistk);

$i=1;
while($dlist = $xoopsDB->fetchArray($reslist)){
	$qunit = "SELECT nama_unit FROM ".$xoopsDB->prefix("crm_listunit")." WHERE id_listunit=".$dlist['unit'];
	$runit = $xoopsDB->query($qunit);
	$dlistunit = $xoopsDB->fetchArray($runit);
	$str_level = $level == 0 ? _DR_ALL : $level;
	$bhs=$dlist['total'] == '' ? '' : $edata['bahasa'];		
$listpro.='
	<tr>
		<td class="td1" align="center">'.$dlist['quantity'].'</td>
		<td class="td2" align="center">'.$dlistunit['nama_unit'].'</td>
		<td class="td3" align="left"><span style="margin-left:'.$dlist['order'].'px;">'.$dlist['description'].'</span></td>
		<td class="td4" align="left">';
		if($dlist['unit_price']<0){$listpro.='<span style="color:red;">'.$bhs.'</span>';}
		if($dlist['unit_price']==''){$listpro.='';}
		else{$listpro.=''.$bhs;}
		$listpro.='
		</td>
		<td class="td5" align="right">';
		if($dlist['unit_price']<0){$listpro.='<span style="color:red;">'.number_format($dlist['unit_price'], 0, '.', '.').'</span>';}
		else{$listpro.=''.number_format($dlist['unit_price'], 0, '.', '.');}
		$listpro.='
		</td>
		<td class="td6" align="left">';
		if($dlist['total']<0){$listpro.='<span style="color:red;">'.$bhs.'</span>';}
		if($dlist['total']==''){$listpro.='';}
		else{$listpro.=''.$bhs;}
		$listpro.='
		</td>
		<td class="td7" align="right">';
		if($dlist['total']<0){$listpro.='<span style="color:red;">'.number_format($dlist['total'], 0, '.', '.').'</span>';}
		else{$listpro.=''.number_format($dlist['total'], 0, '.', '.');}
		$listpro.='
		</td>
	</tr>';
	$i++;
}
$tgl_ayna=date('d-m-Y');
$q1=$edata['tgl_TerbitInvoice'];
$tglq1= date('d F Y', strtotime($q1));
$datenow = date('d F Y', strtotime($tgl_ayna));
$datet=date('Y/m/d g:i:s A ');
$datenow1 = date('Y F', strtotime($tgl_ayna));
$skrg=date('Y-m-d'); 
$datenow2 = date('Y.m.d', strtotime($skrg));

$kiri=$edata['no_quote'];
$tengah=$edata['no_quote'];
$kanan=$edata['no_quote'];

$kop = $edata['kopstat'] == 0 ? '
<table style="width: 100%; border: solid 1px #fff;">
	<tr>
		<td style="width:7%;"><b> To :</b></td>
		<td> <b> '.$dpr['nama'].' </b> </td>
	</tr>
	<tr>
		<td style="width:7%;">&nbsp;</td>
		<td style="width:50%;">'.$dalamat['alamat'].'</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>  Phone&nbsp; : '.$dpr['kontak_perusahaan'].' &nbsp;&nbsp;&nbsp; Fax &nbsp; : '.$dpr['fax'].' </td>
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
		<td style="width:50%;"><b> '.$dcl['nama_kontak'].' </b></td>
	</tr>
	<tr>
		<td style="width:7%;">&nbsp;</td>
		<td style="width:50%;">'.$dalamat['alamat'].'</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td> Phone&nbsp;&nbsp; : '.$dcl['no_hp'].' &nbsp;&nbsp;Email&nbsp;: '.$dcl['email'].' </td>
	</tr>
</table>
';	

$ttdstatus = $edata['ttdstat'] == 0 ? '
<table class="table-ttd" align="center">
	<tbody>
		<tr>
			<td class="tdttd1" align="left"><i>Term and Condition : <br>'.$edata['syarat'].'</i></td>
			<td class="tdttd2" align="left">&nbsp;</td>
			<td class="tdttd3" align="center"> 
				Bandung, '.$tglq1.' <br>
				<img src="img/TTD_kosong.png" style="width:66%">
			</td>
		</tr>	
	</tbody>
</table>
':'
<table class="table-ttd" align="center">
	<tbody>
		<tr>
			<td class="tdttd1" align="left"><i>Term and Condition : <br>'.$edata['syarat'].'</i></td>
			<td class="tdttd2" align="left">&nbsp;</td>
			<td class="tdttd3" align="center">
				Bandung, '.$tglq1.'  <br>
				<img src="img/TTD_basah.png" style="width:66%">
			</td>
		</tr>	
	</tbody>
</table>
';

// ======================================= PDF ============================================
// HTML
$tes2.='
<link rel="stylesheet" type="text/css" href="img/style_pdf.css" media="screen" />
<page backtop="45mm" backbottom="35mm" backleft="10mm" backright="10mm">
<page_header>
<div class="draft_ok"></div>
        <table class="tabel-foothead">
            <tr>
                <td style="text-align: Center; width: 100%; z-index:1;">
					<img src="img/header_draf.png" style="width:725px;">
				</td>
            </tr>			
        </table>
		<div style="width: 100%; padding-top:57px; padding-right:32px; z-index: 2; position: absolute; text-align:right;">
		Page [[page_cu]] of [[page_nb]]
		</div>
</page_header>
<page_footer>
	<div class="foothead"></div>
	<table class="tabel-foothead">
		<tr>
			<td style="text-align:Center; width: 100%;">
				<img src="img/footer.png" style="width:725px;">
			</td>
		</tr>
	</table>
</page_footer>
'.$kop.'
<div class="special">
	<table>
		<tr>
			<td class="bottomLeft">&nbsp;</td>
			<td class="bottomRight">
			<span style="font-weight:bold;"> QUOTATION</span>  # '.$edata['no_quote'].' </td>
		</tr>
	</table>
</div>

<!-- TERIMAKASIH ||||||||||||||||||||||||||||||||||||||||| -->
<table style="margin-left:-20px; width: 106%; align:center;">
	<tr style="vertical-align: top">
		<td>
			<div class="zone" style="text-align: center; vertical-align: middle;">
				Thank you very much for the trust given to us.<br>
				With all due respect, we hereby submit quotation related activities are listed below.
			</div>
		</td>	
	</tr>
</table>
<br>
<!-- PROJECT MANAGER ||||||||||||||||||||||||||||||||||||||||| -->
<table class="table-pm">
	<thead>
		<tr>
			<th class="th-pm" align="center"> PIC / Sales Person</th>
			<th class="th-npm" align="center"> Project Name / Code</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td-pm" align="center">
				<b> '.$dpr['name'].' </b><br><i>&nbsp;</i><br>&nbsp;
			</td>
			<td class="td-keg" align="center">
				<b> '.$dpr['nama_kegiatan'].'  </b><br><i>'.$edata['deskripsi'].'</i><br>&nbsp;
			</td>
		</tr>
	</tbody>
</table>
<!-- Tabel DATA ||||||||||||||||||||||||||||||||||||||||| -->
<table class="table-data" align="center">
	<thead>
		<tr>
			<th class="th1" align="center" colspan="2">Quantity</th>
			<th class="th2" align="center">Work Description</th>
			<th class="th3" colspan="2"align="center">Unit Price</th>
			<th class="th4" colspan="2" align="center">Total Price</th>
		</tr>
	</thead>
	<tbody>
		'.$listpro.'
		<tr>
			<td class="tdd1" align="center">&nbsp;</td>
			<td class="tdd1" align="center">&nbsp;</td>
			<td class="tdd1" align="center">&nbsp;</td>
			<td class="tdd1" colspan="2" align="center">&nbsp;</td>
			<td class="tdd1" colspan="2" align="center">&nbsp;</td>
		</tr>		
	</tbody>
</table>
<!-- PERHITUNGAN SUB TOTAL ||||||||||||||||||||||||||||||||||||||||| -->
<table class="table-total" align="center">
	<tbody>
		<tr>
			<td class="tdt1" align="left" rowspan="3">
				<table width="200" style="border-collapse: collapse; padding-top:5px;">
				  <tr>
					<td style="border: solid 1px #000;">
						<table>
							<tr>
					<td colspan="3">Bank Account</td>
				  </tr>
				  <tr>
					<td colspan="3">Bank Mandiri (Saving)</td>
				  </tr>
				  <tr>
					<td width="62">Branch Name</td>
					<td width="1">:</td>
					<td width="115"> Bandung - Kiaracondong</td>
				  </tr>
				  <tr>
					<td>Account Name</td>
					<td>:</td>
					<td> PT. FUJICON PRIANGAN PERDANA</td>
				  </tr>
				  <tr>
					<td>Account Number</td>
					<td>:</td>
					<td> 130 00 09017222</td>
				  </tr>
						</table>
					</td>
				  </tr>
				</table>
			</td>
			<td class="tdt2" align="right"><b>SUB TOTAL </b></td>
			<td class="tdt3" align="left">'.$edata['bahasa'].'</td>
			<td class="tdt4" align="right"><b> '.number_format($edata['sub_total'], 0, '.', '.').' </b></td>
		</tr>
		<tr>
			<td class="tdt5" align="right"> <b> TAX <span style="font-size:9px;">10%</span> </b> </td>
			<td class="tdt6" align="left"> '.$edata['bahasa'].' </td>
			<td class="tdt7" align="right"> <b> '.number_format($edata['tax'], 0, '.', '.').' </b> </td>
		</tr>
		<tr>
			<td class="tdt5" align="right"> <b> TOTAL </b> </td>
			<td class="tdt6" align="left"> '.$edata['bahasa'].' </td>
			<td class="tdt7" align="right"> <b> '.number_format($edata['total_max'], 0, '.', '.').' </b> </td>
		</tr>
	</tbody>
</table>
<br>
<!-- TANGGAL ||||||||||||||||||||||||||||||||||||||||| -->
'.$ttdstatus.'
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
	$html2pdf->setDefaultFont('arialunicid0'); //add this line
	$html2pdf->writeHTML($tes2, isset($_GET['vuehtml']));
	$html2pdf->Output('Draf_Quotation-'.$datenow2.'-'.$dpr['nama_kegiatan'].'.pdf');
}
catch(HTML2PDF_exception $e) {	
	echo $tes;
	exit;
}