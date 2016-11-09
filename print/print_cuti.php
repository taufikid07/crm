<?php
require('../../../mainfile.php');

echo'
<script>window.print();</script>';
?>
<style>
/*
CSS RESET
*/
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
#bwrap {width:100%;}
.ptop {
	text-align:center;
	font-weight:bold;
	font-size:18px;
}
.pcontent {
	width:90%;
	font-size:10px;
	padding:0 10px 0 30px;
	text-align:center;
}
.pjudul {text-align:left; padding:0 0 5px 0;}
.tjudul {padding:5px 0px;}
p.line {
    border-style: solid;
    border-width: 0px 0px 1px;
	width:80%;
}
p.linex {
    border-style: solid;
    border-width: 0px 0px 1px;
	width:100%;
}
.tline {
    border-style: solid;
    border-width: 0px 0px 1px;
	width:40%;
}
.isi { width:100%; font-size:10px;
}
th.tcont, td.tcont {
    border: 3px solid #000000;
    border-collapse: collapse;
	padding:3px 0;
}
td.cjudul {
    border: 1px solid #000000;
	vertical-align:bottom;
	padding:3px;
}
td.cttd {
    border: 1px solid #000000;
	vertical-align:top;
	padding:3px;
}
.cuti1 {border-collapse: collapse; width:100%; height:50px; border:1px solid #000; font-size:12px;}
table.tisi, th.tisi, td.tisi {
    border: 1px solid #000000;
    border-collapse: collapse;
	padding:3px 3px;
}
@media print {
	.page-break  {page-break-before:auto; }
    }

</style>

<?php	
$uid = $_SESSION['xoopsUserId'];
$id_pcuti	=$_GET['id_pcuti'];
$qcuti=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("ct_permohonan_cuti")." WHERE id_pcuti=".$id_pcuti);
$datapc	= $xoopsDB->fetchArray($qcuti);
$tglmulai	= date("d F Y", strtotime($datapc['tgl_mulai']));
$tglakhir	= date("d F Y", strtotime($datapc['tgl_akhir']));
$pengajuan 	= date("d F Y", strtotime($datapc['tgl_pengajuan']));
//Jenis Cuti
$uquery1=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("ct_jenis_cuti")." WHERE id_jcuti=".$datapc['id_jcuti']);
$datal = $xoopsDB->fetchArray($uquery1);
//Perulanagan jenis cuti
$qjenis="SELECT * FROM ".$xoopsDB->prefix("ct_jenis_cuti")." AS jc ORDER BY id_jcuti ASC";
$resj = $xoopsDB->query($qjenis);
$jenis='';
while ($dataj = $xoopsDB->fetchArray($resj)){
	if ($dataj['nama_jcuti']=='Cuti karena alasan penting'){
		$jenis.='<td class="cjudul"><input type="checkbox">'.$dataj['nama_jcuti'].' . . . . . . . . . .</td>';
	}else if ($dataj['nama_jcuti']==$datal['nama_jcuti']) {
		$jenis.='<td class="cjudul"><input type="checkbox" checked>'.$dataj['nama_jcuti'].'</td>';
	}else {
		$jenis.='<td class="cjudul"><input type="checkbox">'.$dataj['nama_jcuti'].'</td>';
	}
}

//Perulanagan Persetujuan cuti
$qcek="SELECT * FROM ".$xoopsDB->prefix("ct_cek_cuti")." AS jc WHERE id_pcuti=".$id_pcuti;
$rescek = $xoopsDB->query($qcek);
$cekc='';
while ($datack = $xoopsDB->fetchArray($rescek)){
	if ($datack['status_cek']=='1'){
		$cekc.='
		<input type="checkbox" checked> Cuti disetujui penuh <br>
		<input type="checkbox"> Cuti akan diberikan ...... hari kerja, dari tanggal ...-...-....., sampai dengan tanggal ...-...-..... <br>
		<input type="checkbox"> Alasan lain, ....................................................................................................<br>
		...............................................................................................................................
		';
	}else if ($datack['status_cek']=='2') {
		$cekc.='
		<input type="checkbox"> Cuti disetujui penuh <br>
		<input type="checkbox" checked> Cuti akan diberikan <u>'.$datack['jml_cek'].'</u> hari kerja, dari tanggal <u>'.$datack['tgl_on'].'</u> sampai dengan tanggal <u>'.$datack['tgl_off'].'</u> <br>
		<input type="checkbox"> Alasan lain, ....................................................................................................<br>
		................................................................................................................................
		';
	}else {
		$cekc.='
		<input type="checkbox"> Cuti disetujui penuh <br>
		<input type="checkbox"> Cuti akan diberikan ...... hari kerja, dari tanggal ...-...-....., sampai dengan tanggal ...-...-..... <br>
		<input type="checkbox" checked> Alasan lain, '.$datack['ket'].'<br>
		................................................................................................................................
		';
	}
}

//KJOIN
$qjoin=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("ct_kjoin")." WHERE id_kjoin=".$datapc['id_kjoin']);
$djoin = $xoopsDB->fetchArray($qjoin);
$hc = date("d F Y", strtotime($djoin['hak_cuti']));
$yearhc = date("Y", strtotime($djoin['hak_cuti']));
//USER
$qus=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$datapc['uid']);
$dus = $xoopsDB->fetchArray($qus);
//PM
$qpm=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$djoin['approval']);
$dpm = $xoopsDB->fetchArray($qpm);
$now=date('Y-m-d'); 
$tgl_now=date("d F Y", strtotime($now));

//HRD
$qhrd=$xoopsDB->query("SELECT u.name FROM fujicon_groups AS g INNER JOIN fujicon_groups_users_link AS gul ON gul.groupid = g.groupid
INNER JOIN fujicon_users AS u ON gul.uid = u.uid WHERE g.name ='HRD' AND u.uid!='".$uid."'");
$dhrd = $xoopsDB->fetchArray($qhrd);


		echo'
		<div class="page-break">
		<div id="bwrap">
			<div class="pcontent">
				<div class="pjudul">
				<br>
					<table style="width:100%; font-size:12px;" border="1">
					  <tr>
						<td colspan="4" height="100"><img src="../images/header.png" width="600px" style="text-align:center; padding:3px 0 0 0"></td>
					  </tr>
					  <tr>
						<td width="200">Nama</td>
						<td width="350">: '.$dus['name'].'</td>
						<td width="300">Tanggal Mulai Cuti</td>
						<td width="180">: '.$tglmulai.'</td>
					  </tr>
					  <tr>
						<td>Jabatan</td>
						<td>: '.$djoin['jabatan'].'</td>
						<td>Tanggal kembali masuk kerja</td>
						<td>: '.$tglakhir.'</td>
					  </tr>
					  <tr>
						<td>Departemen</td>
						<td>: '.$djoin['divisi'].'</td>
						<td>Tanggal diajukan</td>
						<td>: '.$pengajuan.'</td>
					  </tr>
					</table>
				</div>
				<br>
				<table class="cuti1">
				  <tr>
					<td colspan="9" class="cjudul">Jenis Cuti yang diambil :  </td>
				  </tr>
				  <tr>
					'.$jenis.'
				  </tr>
				  <tr>
					<td colspan="9" class="cjudul">Nomor telepon yang bisa dihubungi selama cuti : '.$datapc['contact'].'</td>
				  </tr>
				</table>
				<br>
				<table class="cuti1">
				  <tr>
					<td class="cjudul">Keputusan Managemen : </td>
				  </tr>
				  <tr>
					<td class="cjudul">
					'.$cekc.'
					</td>
				  </tr>
				</table>
				<br>
				<table class="cuti1">
				  <tr>
					<td class="cttd">
					Pemohon,  
					<br><br><br>
					...............................<br>
					'.$dus['name'].'
					</td>
					<td class="cttd">
					Menyetujui, 
					<br><br><br>
					...............................<br>
					PM. '.$dpm['name'].'
					
					</td>
					<td class="cttd">
					Mengetahui, 
					<br><br><br>
					.................................<br>
					HRD. '.$dhrd['name'].'
					</td>
				  </tr>
				</table>
				
			</div>
		</div>
		<br>
		<hr>
		</div>
		';
?>