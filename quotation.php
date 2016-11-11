<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');

$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================

/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$queryp2 = " SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p	ORDER BY p.id_perusahaan ASC";
$res_pro = $xoopsDB->query($queryp2);
$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p order by p.nama ASC";
$rph= $xoopsDB->query($qph);
/////////////////////////////////////////////// NEW Action ///////////////////////////////////////////////////
$queryp2 = " SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
			INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.groupid = g.groupid
			WHERE g.`name` = 'Webmasters' AND gul.uid = ".$uid."";
$res_pro2 = $xoopsDB->query($queryp2);
$datap2 = $xoopsDB->fetchArray($res_pro2);

$queryp3 = " SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
			INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.groupid = g.groupid
			WHERE g.`groupid` = '15' AND gul.uid = ".$uid."";
$res_pro3 = $xoopsDB->query($queryp3);
$datap3 = $xoopsDB->fetchArray($res_pro3);

$nextq = $_GET['nextq'];
$qquot="SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS q ORDER BY q.id_quote DESC LIMIT 20";
$rq= $xoopsDB->query($qquot);

// Kegiatan
$i=0;
$qt='';
while ($dq = $xoopsDB->fetchArray($rq)){
$i++;
$qkg = "SELECT * FROM ".$xoopsDB->prefix("crm_quotation")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$dq['id_kegiatan']."' AND qt.id_quote='".$dq['id_quote']."' ORDER BY k.nama_kegiatan DESC";
$reskg = $xoopsDB->query($qkg);
$dkg = $xoopsDB->fetchArray($reskg);
$uquery1=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$dq['project_manager']);
if ($dq['project_manager'] == $uid || $datap2['uid'] == $uid || $datap3['uid'] == $uid) {
    $pm1='pm1';
	} else {
		$pm1='pm';
	}


$check='';
if ($dq['status'] == '2') {
    $check.= '<span class="label label-success"> Approved </span>';
} elseif ($dq['status'] == '1') {
    $check.= '<span class="label label-info">Waiting for approval</span>';
} else {
    $check.= '<span class="label label-warning"> Waiting for check </span>';
}

$check_pdf='';
if ($dq['status'] == '2') {
    $check_pdf.= '
	<center>
	<a href="pdf/quote_yes?quote='.$dq['id_quote'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a> 
	<a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-primary" data-toggle="tooltip" title="View quote" ><i class="icon-eye-open"></i><a>
	<a href="quote_duplikat?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-inverse" data-toggle="tooltip" title="Duplikat quote" ><i class="icon-copy"></i></a>
	<a href="quote_revisi?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn " data-toggle="tooltip" title="Revisi quote" ><i class="icon-list-alt"></i></a>
	</center>';
} elseif ($dq['status'] == '1' && $dq['project_manager'] == $uid) {
    $check_pdf.= '
	<center>
	<a href="pdf/quote_no?quote='.$dq['id_quote'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="quote_edit?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-warning"><i class="icon-pencil"></i></a>
	<a href="model/model_quote?del=quo_delete&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
	</center>';
} elseif ($dq['status'] == '0') {
    $check_pdf.= '
	<center>
	<a href="pdf/quote_no?quote='.$dq['id_quote'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="quote_edit?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-warning"><i class="icon-pencil"></i></a>
	<a href="model/model_quote?del=quo_delete&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
	</center>';
}
$tmpil=$pm1 == ''? '-' : ''.$date1.'';
//$check_pro=$dq['send_client'] == '1' ? 'checked' : '0';
	$cek =$dq['send_client'] == 1 ? '<a href="model/model_quote?off=non_send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" class="btn btn-success" onClick="return confirm(\'Are you sure?\')"> Delivered </a>' : '<a data-toggle="modal" href="#form-content" class="btn btn-warning">Ready To Send</a>
<!-- Start Modal Bootstap untuk aksi ready to send -->

   <div id="form-content" class="modal hide fade in" style="align: center; ">
	 <div class="modal-header">
	  <a class="close" data-dismiss="modal">X</a>
	   <h3>Keterangan</h3>
   </div>
	 <div>
	  <form class="contact" action="model/model_quote?on=send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'" method="POST"  enctype="multipart/form-data">
		<fieldset>
	     <div class="modal-body">
	      <ul class="nav nav-list">
	       <li class="nav-header">Silahkan Isi Keterangan</li>
		   <li><textarea class="input-xlarge" name="keterangan" id="keterangan" rows="8">
		    </textarea></li>
		  </ul> 
	     </div>
		</fieldset>
	   
	  </div>
	  <div class="modal-footer">
	  <button class="btn btn-success" type="submit" name="on" >Deal</button>
	   <a href="#" class="btn btn-danger" data-dismiss="modal">Cancel</a>
	   </form>
  		</div>
	</div>
  
   </div>
 </div>
 <!--END-->
	';

$delivery = $dq['status'] == '2' ? $cek : '-';
	
	$qt.='
	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td><a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'">'.$dq['no_quote'].'</a></td>
		<td class="hidden-phone" style="text-align:center;">
		<a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'">'.$dkg['nama_kegiatan'].' 
		<br> <span style="font-style:italic; font-size:10px;"> '.$dkg['deskripsi'].'</span><a>
		
		</td>
		<td align="center" class="'.$pm1.'">'.$check_pdf.'</td>
		<td style="text-align:center;">'.$delivery.'</td>
		<td style="text-align:center;"><a href="quote_view?pr='.$dq['id_perusahaan'].'&quote='.$dq['id_quote'].'">'.$check.'</a></td>
	</tr>';
}

$qhstr = " SELECT * FROM ".$xoopsDB->prefix("crm_history")." AS h WHERE h.id_invoice=0 ORDER BY h.id_history DESC limit 10	";
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
                     Quotation
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
                          Quotation
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
              </div>
            </div>
            <!-- END PAGE HEADER-->
            <div class="row-fluid">
                <div class="widget purple">
				 <div class="widget-title">
					 <h4><i class="icon-search"></i> Search Quotation List </h4>
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
												echo'<option value="quote_next?pr='.$dth['id_perusahaan'].'">'.$dth['nama'].'';
											}
										echo'
										</select>
									</div>
								</div>
								</div>
							</form>
						</div>
						<div class="span2" align="center">
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
							 <th style="text-align:center;">No Quotation</th>
							 <th class="hidden-phone" width="55%" style="text-align:center;">Activity Name</th>
							 <th align="center" width="10%" class="'.$pm1.'">Action</th>
							 <th width="15%" style="text-align:center;">Delivery Report</th>
							 <th style="text-align:center;">Status</th>
										
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
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
';




require(XOOPS_ROOT_PATH.'/footer.php');
?>

<script type="text/javascript">
	 var urlmenu = document.getElementById( 'menu1' );
	 urlmenu.onchange = function() {
		  window.open( this.options[ this.selectedIndex ].value, '_self');
	 };
</script>
<!--<script>
 $(function() {
//twitter bootstrap script
  $("button#submit").click(function(){
  $.ajax({
  	   	type: "POST",
		url: ""
		data: $('form.contact').serialize(),
   		success: function(msg){
       		     $("#thanks").html(msg)
 		         $("#form-content").modal('hide');	
 		        },
			error: function(){
				alert("failure");
				}
      			});
	});
});
</script> -->
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />