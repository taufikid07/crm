<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');


$pr 	= $_GET['pr'];
$keg 	= $_GET['keg'];
$quote	= $_GET['quote'];
$inv	= $_GET['inv'];
$skrg	= date('Y-m-d');

// ========================
$perusahaan='';
$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p order by p.nama ASC";
$rph= $xoopsDB->query($qph);
$checkInvoice = $xoopsDB->getRowsNum($rph);
while($rowInvoice = $xoopsDB->fetchArray($rph)){
	$perusahaan.='<option value="'.$rowInvoice['id_perusahaan'].'">('.$rowInvoice['kode_perusahaan'].')&nbsp;&nbsp;&nbsp;'.$rowInvoice['nama'].'</option>';
}


$query = " SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt where id_invoice =".$inv."";
$res = $xoopsDB->queryF($query);
$edata = $xoopsDB->fetchArray($res);

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

//Project Manager
$group_manager='';
while($qg = $xoopsDB->fetchArray($res_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("users")." AS u ON qt.project_manager = u.uid WHERE uid='".$edata['project_manager']."'";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($qg['uid']==$gs['project_manager']){$selected='selected';}
	}
	$group_manager.='
		<option value="'.$qg['uid'].'" '.$selected.'>'.$qg['uname'].'</option>
	';
}

// Terbit Kuitansi
$query_group = "SELECT * FROM ".$xoopsDB->prefix("users")."";
$result_group = $xoopsDB->query($query_group);
$pub_kui='';
while($tl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS np INNER JOIN ".$xoopsDB->prefix("users")." AS u ON np.terbit_kuitansi = u.uid WHERE uid='".$edata['terbit_kuitansi']."' ORDER BY u.uname ASC";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['uid']==$gs['terbit_kuitansi']){$selected='selected';}
	}
	$pub_kui.='
		<option value="'.$tl['uid'].'" '.$selected.'>'.$tl['uname'].'</option>
	';
}
//Client
$query_client = "SELECT *
FROM
".$xoopsDB->prefix("crm_perusahaan")." AS p
INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON k.id_perusahaan = p.id_perusahaan
INNER JOIN ".$xoopsDB->prefix("crm_invoice")." AS q ON q.id_kontak = k.id_kontak
WHERE q.id_perusahaan='".$edata['id_perusahaan']."'
GROUP BY q.id_kontak ";
$res_grup = $xoopsDB->query($query_client);
$grup_client='';
while($tl = $xoopsDB->fetchArray($res_grup)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS np INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON np.id_kontak = k.id_kontak WHERE np.id_kontak='".$edata['id_kontak']."' AND np.id_quote='".$edata['id_quote']."' ORDER BY k.nama ASC";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['id_kontak']==$gs['id_kontak']){$selected='selected';}
	}
	$grup_client.='
		<option value="'.$tl['id_kontak'].'" '.$selected.'>'.$tl['nama'].'</option>
	';
}


// Terbit Invoice
$query_group = "SELECT * FROM ".$xoopsDB->prefix("users")."";
$result_group = $xoopsDB->query($query_group);
$pub_invo='';
while($tl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS np INNER JOIN ".$xoopsDB->prefix("users")." AS u ON np.terbit_invoice = u.uid WHERE uid='".$edata['terbit_invoice']."' ORDER BY u.uname ASC";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['uid']==$gs['terbit_invoice']){$selected='selected';}
	}
	$pub_invo.='
		<option value="'.$tl['uid'].'" '.$selected.'>'.$tl['uname'].'</option>
	';
}
// Terbit Kuitansi
$query_group = "SELECT * FROM ".$xoopsDB->prefix("users")."";
$result_group = $xoopsDB->query($query_group);
$pub_kui='';
while($tl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS np INNER JOIN ".$xoopsDB->prefix("users")." AS u ON np.terbit_kuitansi = u.uid WHERE uid='".$edata['terbit_kuitansi']."' ORDER BY u.uname ASC";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['uid']==$gs['terbit_kuitansi']){$selected='selected';}
	}
	$pub_kui.='
		<option value="'.$tl['uid'].'" '.$selected.'>'.$tl['uname'].'</option>
	';
}
// Kegiatan
$query_group = "SELECT * FROM ".$xoopsDB->prefix("crm_kegiatan")."";
$result_group = $xoopsDB->query($query_group);
$pub_kgt='';
while($tl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$edata['id_kegiatan']."' AND qt.id_quote='".$edata['id_quote']."' ORDER BY k.nama_kegiatan ASC";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($tl['id_kegiatan']==$gs['id_kegiatan']){$selected='selected';}
	}
	$pub_kgt.='
		<option value="'.$tl['id_kegiatan'].'" '.$selected.'>'.$tl['nama_kegiatan'].'</option>
	';
}


// Activity
$act='';
	$qkeg = "SELECT * FROM ".$xoopsDB->prefix("crm_listinvoice")." WHERE id_invoice=".$edata['id_invoice']."";
	$res_keg = $xoopsDB->query($qkeg);
	$i_act=0;
	$breaks = array("<br />","<br>","<br/>"); 
	while($dkeg = $xoopsDB->fetchArray($res_keg)){
		
		$query_group = "SELECT * FROM ".$xoopsDB->prefix("crm_listunit")."";
		$result_group = $xoopsDB->query($query_group);
		$u_lt='';
		while($tl = $xoopsDB->fetchArray($result_group)){
		$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_listinvoice")." AS lp INNER JOIN ".$xoopsDB->prefix("crm_listunit")." AS lu ON lu.id_listunit = lp.unit WHERE unit=".$dkeg['unit']."";	
		$group_s = $xoopsDB->query($group_selected);
		$selected='';
		while($gs = $xoopsDB->fetchArray($group_s)){
			if($tl['id_listunit']==$gs['unit']){$selected='selected';}
		}
		$u_lt.='
			<option value="'.$tl['id_listunit'].'" '.$selected.'>'.$tl['nama_unit'].'</option>
		';
		}
		$vspasi='';
		if ($dkeg['order'] == "0") {
			$vspasi.='-';
		} elseif ($dkeg['order'] == "10") {
			$vspasi.='>';
		} elseif ($dkeg['order'] == "20") {
			$vspasi.='>>';
		} elseif ($dkeg['order'] == "30") {
			$vspasi.='>>>';
		}
		
		
$act.='
	<tr>
		<td>
		<input type="hidden" name="id_act['.$i_act.']" value="non-aktif" class="input-small" >
		<input type="checkbox" name="id_act['.$i_act.']" value="aktif" class="input-small" checked>
		</td>
		<td>
			<select name="spasi[]" class="input-small m-wrap" tabindex="1">
				<option value="'.$dkeg['order'].'">'.$vspasi.'</option>
				<option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>
				<option value="10"> &nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;</option>
				<option value="20"> &nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; </option>
				<option value="30"> &nbsp;&nbsp;&nbsp;&nbsp;>>>&nbsp;&nbsp;&nbsp; </option>
			</select>
		</td>
		<td>
			<textarea name="des[]" class="span12" rows="3">'.$dkeg['description'] = str_ireplace($breaks, "", $dkeg['description']).'</textarea>
		</td>
		<td>
			<select name="unit[]" class="input-medium m-wrap" tabindex="1">
				<option value=""> Choose
				'.$u_lt.'
			</select>
		</td>
		<td><input type="text" name="price[]" id="price_'.$i_act.'" class="input-small changesNo" value="'.$dkeg['unit_price'].'"></td>
		<td><input type="text" name="quantity[]" id="quantity_'.$i_act.'" class="input-small changesNo" value="'.$dkeg['quantity'].'"></td>
		<td><input type="text" name="total[]" id="total_'.$i_act.'" class="input-medium totalLinePrice" value="'.$dkeg['total'].'"></td>
	</tr>';
	$i_act++;}

//Bahasa
$query_group = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice='".$edata['id_invoice']."'";
$result_group = $xoopsDB->query($query_group);
$bhsa='';
while($ttl = $xoopsDB->fetchArray($result_group)){
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt WHERE qt.id_invoice='".$edata['id_invoice']."' AND bahasa='".$edata['bahasa']."'";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($ttl['bahasa']==$gs['bahasa']){$selected='selected';}
	}
	$bahasa='';
	if ($ttl['bahasa'] == "Rp.") {
		$bahasa.='Rupian ( IDR )';
	} elseif ($ttl['bahasa'] == "JPY") {
		$bahasa.='Yen ( JPY )';
	} else {
		$bahasa.='Dollar ( USD )';
	}
	$bhsa.='
		<option value="'.$ttl['bahasa'].'" '.$selected.'>'.$bahasa.'</option>
	';
}
// PAJAK
$queryg = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." WHERE id_invoice='".$edata['id_invoice']."'";
	$resultg = $xoopsDB->query($queryg);
	$pjk='';
	while($ttl = $xoopsDB->fetchArray($resultg)){
		$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt WHERE qt.id_invoice='".$edata['id_invoice']."' AND tax_percent='".$edata['tax_percent']."'";	
		$group_s = $xoopsDB->query($group_selected);
		$selected='';
		while($gs = $xoopsDB->fetchArray($group_s)){
			if($ttl['tax_percent']==$gs['tax_percent']){$selected='selected';}
		}
		$pajak='';
		if ($ttl['tax_percent'] == "10") {
			$pajak.='10 %';
		} else {
			$pajak.='None';
		}
		$pjk.='
			<option value="'.$ttl['tax_percent'].'" '.$selected.'>'.$pajak.'</option>
		';
	}


echo '
<div id="main-content">
     <!-- BEGIN PAGE CONTAINER-->
     <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
               <h3 class="page-title">
                 Invoice Next
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
				   <li>
                       <a href="invoice">Invoice</a>
                       <span class="divider">/</span>
                   </li>
                   <li class="active">
                      Invoice Next
                   </li>
               </ul>
               <!-- END PAGE TITLE & BREADCRUMB-->
          </div>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="widget black">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i> Duplikat invoice </h4>
                        <div class="actions sm-btn-position">
                            <a href="invoice?pr='.$pr.'&keg='.$keg.'&quote='.$quote.'" class="btn btn-primary btn-small"> Back <i class="icon-arrow-right"></i> </a>
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#portlet-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="widget-body form">
                        <!-- BEGIN FORM-->
                        
                        <div class="widget-body">
                         <div class="portlet-body">
                         <div class="space15"></div>				 
                 <form class="cmxform form-horizontal" id="commentForm" name="form_perusahaan" action="model/model_invoice?pr='.$pr.'keg='.$keg.'&quote='.$quote.'&inv='.$edata['id_invoice'].'" method="post">
                             <div class="row-fluid">
                                <div class="span2 billing-form">
                                &nbsp;
                                </div>
                                 <div class="span9 billing-form">
                                     <div class="space10"></div>
                                        <div class="control-group ">
                                             <label class="control-label">No invoice</label>
                                            <input name="no_invoice" type="text" placeholder="" class="span5" value="'.$edata['no_invoice'].'">
                                         </div>
                                        <div class="control-group ">
                                            <label class="control-label">Kode & Name Company</label>									 
                                            <select name="perusahaan" class="input-xxlarge m-wrap" onchange="showCustomer(this.value)">
                                                <option value="">Select Company</option>
                                                '.$perusahaan.'
                                            </select>
                                         </div>
                                         
                                         <div class="control-group ">
                                            <label class="control-label">Activity Name</label>
                                            <select name="kegiatan" id="pret" class="input-xxlarge m-wrap" onchange="showCustomer2(this.value)">
                                                <option value="">Select Activity</option>
                                                
                                            </select>
                                            <span id="state_loader"></span>
                                         </div>
                                         <div class="control-group ">
                                             <label class="control-label">Contact Person</label>
                                             <select name="kontak" id="pret2" class="input-xxlarge m-wrap">
                                                <option value="">Select Contact</option>
                                             </select>
                                             <span id="city_loader"></span>
                                         </div>
                                          <div class="control-group ">
                                             <label class="control-label">Description</label>
                                             <input name="deskripsi" type="text" placeholder="" class="span8" value="'.$edata['deskripsi'].'">
                                         </div>
                                         <div class="control-group ">
                                            <label class="control-label">Project Manager</label>
                                                <select style="width:220px" name="project_manager">
                                                    <option value=""> Choose
                                                    '.$group_manager.'
                                                </select>
                                         </div>
                                 </div>
                                 <div class="span2 billing-form">
                                 &nbsp;
                                 </div>
                             </div>
                             <hr>
                             <div class="space15"></div>
                             <div class="row-fluid">
                                 <div class="span12">
                                     <h4>List Product</h4>
                                     <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">cek</th>
                                                <th class="text-center">Spasi</th>
                                                <th class="text-center span7">Description</th>
                                                <th class="text-center">Unit</th>
                                                <th class="text-center">Unit Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cons">
                                            '.$act.'
                                        </tbody>
                                    </table>
                                    <div class="space10"></div>
                                    <input type="button" value="Add New" id="add_new1" class="btn btn-small">
                                    <input type="button" value="Delete" id="removeButton1" class="btn btn-small">
                                </div>
                             </div>
                             <div class="space15"></div>
                             <div class="row-fluid">
                                <div class="span6 billing-form" style="border:1px solid #000; padding:5px;">
                                 <center><h4>Bank Account</h4></center>
                                 <div class="space5"></div>
                                     <div class="control-group ">
                                         <label class="control-label">Bank Name</label>
                                         <input name="rek_name" type="text" value="Bank Mandiri (Saving)" class="input-large" readonly="readonly">
                                     </div>
                                     <div class="control-group ">
                                         <label class="control-label">Branch Name</label>
                                         <input name="bran_name" type="text" value="Bandung - Kiaracondong" class="input-large" readonly="readonly">
                                     </div>
                                     <div class="control-group ">
                                         <label class="control-label">Account Name</label>
                                         <input name="acc_name" type="text" value="PT. FUJICON PRIANGAN PERDANA" class="input-xlarge" readonly="readonly">
                                     </div>
                                     <div class="control-group ">
                                         <label class="control-label">Account Number</label>
                                         <input name="acc_num" type="text" value="130 00 09017222" class="m-ctrl-medium"readonly="readonly">
                                     </div>
                             </div>
                             <div class="span4 invoice-block pull-right">
                                <table width="100%">
                                    <tr>
                                        <td style="width:50%; text-align:right; padding-left:30px;"><b>SUB TOTAL &nbsp;</b></td>
                                        <td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
                                        <input value="'.$edata['sub_total'].'" name="sub_total" type="number" class="input-medium" id="subTotal">
                                        </b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%; text-align:right; padding-left:30px;"><b>TAX &nbsp; % &nbsp;</b></td>
                                        <td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
                                        <select name="tax_percent" class="input-medium m-wrap" tabindex="1" id="tax">
                                            '.$pjk.'
                                            <option value="">Pilih</option>
                                            <option value="0">None</option>
                                            <option value="10">10%</option>
                                        </select>
                                        </b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%; text-align:right; padding-left:30px;"><b>TAX Amount &nbsp;</b></td>
                                        <td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
                                        <input value="'.$edata['tax'].'" name="tax" type="number" class="input-medium" id="taxAmount" >
                                        </b></td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%; text-align:right; padding-left:30px;"><b>TOTAL &nbsp;</b></td>
                                        <td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
                                        <input value="'.$edata['total_max'].'" name="total_max" type="number" class="input-medium" id="totalAftertax" >
                                        </b></td>
                                    </tr>
                                </table>
                             </div>
                         </div>
                             <div class="row-fluid">
                                 <div class="span6 billing-form">
                                     <h4>published</h4>
                                     <div class="space10"></div>
                                         <div class="control-group ">
                                             <label class="control-label">date publish invoice</label>
                                             <input name="date_invoice" id="dp2" type="text" value="'.$edata['tgl_TerbitInvoice'].'" size="16" class="m-ctrl-medium">
                                             <input name="date_kuitansi" type="hidden" value="'.$edata['tgl_TerbitKuitansi'].'" size="16">
                                             <input name="pub_kui" type="hidden" value="'.$edata['terbit_kuitansi'].'" size="16">
                                         </div>
                                         <div class="control-group ">
                                             <label class="control-label">publish by</label>
                                                <select style="width:220px" name="pub_invoice">
                                                    <option value=""> Choose
                                                    '.$pub_invo.'
                                                </select>
                                         </div>
                                 </div>
                                 <div class="span6 billing-form">
                                     <h4>language and terms</h4>
                                     <div class="space10"></div>
                                         <div class="control-group ">
                                             <label class="control-label">Currency format / used</label>
                                             <select name="bahasa" class="input-large m-wrap" tabindex="1">
                                                '.$bhsa.'
                                                <option value="">Pilih</option>
                                                <option value="Rp.">Rupian ( IDR )</option>
                                                <option value="JPY">Yen ( JPY )</option>
                                                <option value="USD">Dollar ( USD )</option>
                                             </select>
                                         </div>
                                         <div class="control-group ">
                                             <label class="control-label">Terms and Conditions</label>
                                              <textarea name="syarat" class="input-large span8" rows="3">'.$edata['syarat'].'</textarea>
                                         </div>
                                 </div>
                                 <div class="span12">
                                    <div class="row-fluid text-center">
                                        <button class="btn btn-success" type="submit" name="duplikat">Duplicate invoice</button>
                                     </div>
                                 </div>
                                 </form>
                             </div>
                         </div>
                     </div>
                        
                        <!-- END FORM-->
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
 	</div>
</div>
';
$ulistj=" SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." AS u ORDER BY u.nama_unit ASC";
$rulj = $xoopsDB->query($ulistj);
$u_ltj='';
$u_ltj.='<select name="unit[]" class="input-medium m-wrap" tabindex="1"><option value="1">Choose</option>';
while($dluj = $xoopsDB->fetchArray($rulj)){
	$u_ltj.='<option value="'.$dluj['id_listunit'].'">'.$dluj['nama_unit'];
}
$u_ltj.="</select>";

$query1a = "SELECT MAX(id_product) AS id_product FROM ".$xoopsDB->prefix("crm_listinvoice");
$result1a = $xoopsDB->query($query1a);
$datam = $xoopsDB->fetchArray($result1a);
$id_product=$datam['id_product'];
$query = "SELECT * FROM ".$xoopsDB->prefix("crm_listinvoice")." WHERE id_invoice=".$edata['id_invoice']."";
$result = $xoopsDB->query($query);
$row_des = $xoopsDB->getRowsNum($result);
$rowc = isset($id_product) && !empty($id_product) ? $row_des : 0;

/////////////////////////////////////// End Edit Project /////////////////////////////////////////////////////
require(XOOPS_ROOT_PATH.'/footer.php');
?>
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />

<script>
function showCustomer(str)
{
var xmlhttp;    
if (str=="")
  {
  document.getElementById("pret").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("pret").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","invoice_getdup?q="+str,true);
xmlhttp.send();
}

function showCustomer2(str)
{
var xmlhttp;    
if (str=="")
  {
  document.getElementById("pret2").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("pret2").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","invoice_getdup?q2="+str,true);
xmlhttp.send();
}
</script>
<script>
$(document).ready(function(){
		var counter1c = <?php echo $rowc; ?>;
		var i=<?php echo $rowc; ?>;
		$('#add_new1').click(function(){
			i=i+1;
			$('#cons').append(
				'<tr id="text_input1_'+counter1c+'">'+
				'<td class="text-center"><input type="checkbox" name="id_act[]" value="aktif" class="input-small" checked></td>'+
				'<td><select name="spasi[]" class="input-small m-wrap" tabindex="1" style="font-weight:bold;">'+
					'<option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="10"> &nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="20"> &nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; </option>'+
					'<option value="30"> &nbsp;&nbsp;&nbsp;&nbsp;>>>&nbsp;&nbsp;&nbsp; </option>'+
				'</select></td>'+
				'<td><textarea name="des[]" class="span12" rows="3"></textarea></td>'+
				'<td>'+
				<?php echo "'".$u_ltj."<br><br>'+" ?>
					'<a href="q_list_unit_quote?pr=<?php echo $pr ?>" class="btn btn-primary btn-mini"><i class="icon-cogs"></i> Setting</a>'+
				'</td>'+
				'<td><input type="text" name="price[]" id="price_'+i+'" class="input-small changesNo"></td>'+
				'<td><input type="text" name="quantity[]" id="quantity_'+i+'" class="input-small changesNo"></td>'+
				'<td><input type="text" name="total[]" id="total_'+i+'" class="input-medium totalLinePrice"></td>'+
				'</tr>'
			);
		});		
		$("#removeButton1").click(function () {
			if(counter1c==0){
				alert("Tidak ada file yang dapat dihapus ("+counter1c+")");
				return false;
			}
			$("#text_input1_"+counter1c).remove();
			counter1c--;
			
		});
	});
//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateTotal();
});


//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
	type = $(this).data('type');
	
	if(type =='productCode' )autoTypeNo=0;
	if(type =='productName' )autoTypeNo=1; 	
	
	$(this).autocomplete({
		source: function( request, response ) {	 
			 var array = $.map(prices, function (item) {
                 var code = item.split("|");
                 return {
                     label: code[autoTypeNo],
                     value: code[autoTypeNo],
                     data : item
                 }
             });
             //call the filter here
             response($.ui.autocomplete.filter(array, request.term));
		},
		autoFocus: true,	      	
		minLength: 2,
		select: function( event, ui ) {
			var names = ui.item.data.split("|");						
			id_arr = $(this).attr('id');
	  		id = id_arr.split("_");
			$('#itemNo_'+id[1]).val(names[0]);
			$('#itemName_'+id[1]).val(names[1]);
			$('#quantity_'+id[1]).val(1);
			$('#price_'+id[1]).val(names[2]);
			$('#total_'+id[1]).val( 1*names[2] );
			calculateTotal();
		}		      	
	});
});

//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#quantity_'+id[1]).val();
	price = $('#price_'+id[1]).val();
	if( quantity!='' && price !='' ) $('#total_'+id[1]).val( (parseFloat(price)*parseFloat(quantity)).toFixed(0) );	
	calculateTotal();
});

$(document).on('change keyup blur','#tax',function(){
	calculateTotal();
});

//total price calculation 
function calculateTotal(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(0) );
	tax = $('#tax').val();
	if(tax != '' && typeof(tax) != "undefined" ){
		taxAmount = subTotal * ( parseFloat(tax) /100 );
		$('#taxAmount').val(taxAmount.toFixed(0));
		total = subTotal + taxAmount;
	}else{
		$('#taxAmount').val(0);
		total = subTotal;
	}
	$('#totalAftertax').val( total.toFixed(0) );
	calculateAmountDue();
}

$(document).on('change keyup blur','#amountPaid',function(){
	calculateAmountDue();
});

//due amount calculation
function calculateAmountDue(){
	amountPaid = $('#amountPaid').val();
	total = $('#totalAftertax').val();
	if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
		amountDue = parseFloat(total) - parseFloat( amountPaid );
		$('.amountDue').val( amountDue.toFixed(0) );
	}else{
		total = parseFloat(total).toFixed(0);
		$('.amountDue').val( total);
	}

}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}
</script>

<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />