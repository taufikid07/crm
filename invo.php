<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================

$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p order by p.nama ASC";
$rph= $xoopsDB->query($qph);
$checkInvoice = $xoopsDB->getRowsNum($rph);
$qquot="SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS i ORDER BY i.id_invoice DESC LIMIT 20";
$rq= $xoopsDB->query($qquot);
// Kegiatan
$i=0;
$qt='';
while ($dq = $xoopsDB->fetchArray($rq)){
$i++;
$qkg = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$dq['id_kegiatan']."' AND qt.id_invoice='".$dq['id_invoice']."' ORDER BY qt.id_invoice DESC";
$reskg = $xoopsDB->query($qkg);
$dkg = $xoopsDB->fetchArray($reskg);
$check='';
if ($dq['status'] == '2') {
    $check.= '<span class="label label-success"> Approved </span>';
} elseif ($dq['status'] == '1') {
    $check.= '<span class="label label-info"> Waiting for approved </span>';
} else {
    $check.= '<span class="label label-warning"> Waiting for checked </span>';
}

$check_pdf='';
if ($dq['status'] == '2') {
    $check_pdf.= '
	<center>
	<a href="pdf/invoice_yes?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" data-toggle="tooltip" title="Print invoice" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="pdf/pdf_kwitansi?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" data-toggle="tooltip" title="Print kwitansi" class="btn btn-primary" target="_blank"><i class="icon-download-alt"></i></a>
	<a href="invoice_duplikat?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-inverse"><i class="icon-copy"></i></a>
	<a href="invoice_revisi?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn "><i class="icon-list-alt"></i></a>
	</center>';
} elseif ($dq['status'] == '1' && $dq['project_manager'] == $uid) {
    $check_pdf.= '
	<center>
	<a href="pdf/invoice_no?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="invoice_edit?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-warning"><i class="icon-pencil"></i></a>
	<a href="model/model_invoice?del=inv_delete&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
	</center>';
} elseif ($dq['status'] == '0') {
    $check_pdf.= '
	<center>
	<a href="pdf/invoice_no?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="invoice_edit?uid='.$uid.'&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-warning"><i class="icon-pencil"></i></a>
	<a href="model/model_invoice?del=inv_delete&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
	</center>';
}
	$tmpil=$pm1 == ''? '-' : ''.$date1.'';
$check_pro=$dq['send_client'] == '1' ? 'checked' : '0';
$cek =$dq['send_client'] == 1 ? '<a href="model/model_invoice?off=non_send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-success" onClick="return confirm(\'Are you sure?\')"> ON </a>' : '<a href="model/model_invoice?on=send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-danger" onClick="return confirm(\'Are you sure?\')"> OFF </a>';
	
	$qt.='
	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td><a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >'.$dq['no_invoice'].'</a></td>
		<td style="text-align:center"><a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >'.$dkg['nama_kegiatan'].' <br> <i><span style="font-size:10px;">'.$dkg['deskripsi'].'</span></i> </a></td>
		<td style="text-align:center;">'.$cek.'</td>
		<td style="text-align:center;"><a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >'.$check.'</a></td>
		<td align="center" class="'.$pm1.'">
			<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >
			'.$check_pdf.'
			</a>
		</td>
	</tr>
	';
}
$qhstr = " SELECT * FROM ".$xoopsDB->prefix("crm_history")." AS h where h.id_invoice!=0 ORDER BY h.id_history DESC limit 10	";

//$xx ="SELECT * FROM ".$xoopsDB->prefix("crm_history")." AS h ORDER BY h.id_history DESC limit 10";
//echo $xx;
$rhistory = $xoopsDB->query($qhstr);

$history='';
$i=0;
while($dhst = $xoopsDB->fetchArray($rhistory)){
	$qus=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." AS p WHERE uid=".$dhst['userid']);
	$duser = $xoopsDB->fetchArray($qus);
	$qqt=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS q WHERE id_invoice=".$dhst['id_invoice']);
	$dinvoice = $xoopsDB->fetchArray($qqt);
	
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

$vcom="SELECT * FROM
".$xoopsDB->prefix("crm_perusahaan")." AS p 
WHERE p.id_perusahaan =".$nextq."";
$rcom= $xoopsDB->query($vcom);
$dcom = $xoopsDB->fetchArray($rcom);

echo'
<div id="main-content">
     <!-- BEGIN PAGE CONTAINER-->
     <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
               <h3 class="page-title">
                 Invoice
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
                      Invoice
                   </li>
               </ul>
               <!-- END PAGE TITLE & BREADCRUMB-->
          </div>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row-fluid">
             <!-- BEGIN BLANK PAGE PORTLET-->
             <div class="widget purple">
                 <div class="widget-title">
                     <h4><i class="icon-search"></i> Search Invoice List </h4>
                   <span class="tools">
                       <a href="javascript:;" class="icon-chevron-down"></a>
                       <a href="javascript:;" class="icon-remove"></a>
                   </span>
                 </div>
                 <div class="widget-body">
                 <div class="space15"></div>
                    <!-- BEGIN FORM-->          
                    
                    <div class="row-fluid">
                    <div class="span12">
                        <div class="span10">
                            <form action="invoice_create.html" class="form-horizontal">
                                <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">Company Name</label>
                                    <div class="controls">						
                                        <select class="span12 " data-placeholder="Choose a Category" tabindex="1" id="menu1">
                                            <option value="">Select...</option>
                                            ';
                                            while($dth = $xoopsDB->fetchArray($rph)){
                                                echo'<option value="invoice_next?pr='.$dth['id_perusahaan'].'">'.$dth['nama'].'';
                                            }
                                        echo'
                                        </select>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="span2">
                            <a class="btn btn-success" href="perusahaan"><i class="icon-plus icon-white"></i> Add Company </a>
                        </div>
                    </div>
                    </div>
                    <!-- END FORM-->
                </div>
             </div>
             <!-- TAB BARU-->
             <!-- BEGIN BLANK PAGE PORTLET-->
             <div class="widget purple">
                 <div class="widget-title">
                     <h4><i class="icon-eyes"></i> Update New Quotation </h4>
                   <div class="actions sm-btn-position">
                        &nbsp;
                    </div>
                 </div>
                 <div class="widget-body">
                 <div class="space15"></div>
                    <!-- BEGIN FORM-->      
                    <!-- END FORM-->			
                    <div class="row-fluid">
                    <div class="span12">
                    <div class="widget-body">
                             <table class="table table-striped table-bordered dataTable" id="sample_1">
                                <thead>
                                <tr>
                                    <th class="hidden-phone">No</th>
                                    <th>No Invoice</th>
                                    <th width="55%">Activity Name</th>
                                    <th width="15%">Send to client</th>
                                    <th>Status</th>
                                    <th align="center" width="10%" class="'.$pm1.'">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    '.$qt.'
                                </tbody>	
                                </tbody>
                            </table>
                    </div>
                    </div>
                    </div>
                </div>
             </div>
         </div>
 	
 
		<!-- HISTORY-->
		<div class="row-fluid">
			 <!-- BEGIN CHAT PORTLET-->
			 <div class="widget red">
				 <div class="widget-title">
					 <h4><i id="dash-timeline-icon" class="icon-spinner icon-spin"></i> History</h4>
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
	</div>
</div> ';


/////////////////////////////////////// End Edit Project /////////////////////////////////////////////////////
require(XOOPS_ROOT_PATH.'/footer.php');
?>
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />
<script type="text/javascript">
	 var urlmenu = document.getElementById( 'menu1' );
	 urlmenu.onchange = function() {
		  window.open( this.options[ this.selectedIndex ].value, '_self');
	 };
</script>
<style>
.pm {
		display:none;
	}
	.pm1 {
		display:block;
	}
</style>