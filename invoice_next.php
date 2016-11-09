<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================
$pr 	= $_GET['pr'];
$quote 	= $_GET['quote']; 

$queryp2=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
			INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.groupid = g.groupid
			WHERE g.`name` = 'Webmasters' AND gul.uid = ".$uid."");
$datap2 = $xoopsDB->fetchArray($queryp2);

$queryp3=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("groups")." AS g
			INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.groupid = g.groupid
			WHERE g.`groupid` = '15' AND gul.uid = ".$uid."");
$datap3 = $xoopsDB->fetchArray($queryp3);

/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$qquot="SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p
INNER JOIN ".$xoopsDB->prefix("crm_invoice")." AS q ON q.id_perusahaan = p.id_perusahaan
WHERE p.id_perusahaan =".$pr." ORDER BY q.id_invoice DESC";
$rq= $xoopsDB->query($qquot);

// Kegiatan
$i=0;
$qt='';
while ($dq = $xoopsDB->fetchArray($rq)){
$i++;
$qkg = "SELECT * FROM ".$xoopsDB->prefix("crm_invoice")." AS qt INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS k ON qt.id_kegiatan = k.id_kegiatan WHERE k.id_kegiatan='".$dq['id_kegiatan']."' AND qt.id_invoice='".$dq['id_invoice']."' ORDER BY k.nama_kegiatan DESC";
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
    $check.= '<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" ><span class="label label-success"> Approved </span></a>';
} elseif ($dq['status'] == '1') {
    $check.= '<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" ><span class="label label-info"> Waiting for approved </span></a>';
} else {
    $check.= '<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" ><span class="label label-warning"> Waiting for checked </span></a>';
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
} elseif ($dq['status'] == '1' || $dq['project_manager'] == $uid) {
    $check_pdf.= '
	<center>
	<a href="pdf/invoice_no?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" data-toggle="tooltip" title="Print quote" class="btn btn-primary" target="_blank"><i class="icon-arrow-down"></i></a>
	<a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-primary"><i class="icon-eye-open"></i><a>
	<a href="invoice_edit?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-warning"><i class="icon-pencil"></i></a>
	<a href="model/model_invoice?del=inv_delete&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
	</center>';
} else {
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
$cek =$dq['send_client'] == 1 ? '<a href="model/medel_invoice?off=non_send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-success" onClick="return confirm(\'Are you sure?\')"> ON </a>' : '<a href="model/medel_invoice?on=send&uid='.$uid.'&pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" class="btn btn-danger" onClick="return confirm(\'Are you sure?\')"> OFF </a>';
	
	$qt.='
	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td><a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >'.$dq['no_invoice'].'</a></td>
		<td style="text-align:center"><a href="invoice_view?pr='.$dq['id_perusahaan'].'&keg='.$dq['id_kegiatan'].'&quote='.$dq['id_quote'].'&inv='.$dq['id_invoice'].'" >'.$dkg['nama_kegiatan'].' <br> <i><span style="font-size:10px;">'.$dkg['deskripsi'].'</span></i> </a></td>
		<td style="text-align:center;">'.$cek.'</td>
		<td class="hidden-phone" style="text-align:center;">'.$check.'</td>
		<td align="center" class="'.$pm1.'">
			'.$check_pdf.'
		</td>
	</tr>
	';
}
$vcom="SELECT * FROM
".$xoopsDB->prefix("crm_perusahaan")." AS p 
WHERE p.id_perusahaan =".$nexti."";
$rcom= $xoopsDB->query($vcom);
$dcom = $xoopsDB->fetchArray($rcom);
//PErusahaan
$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p order by p.nama ASC";
$rph= $xoopsDB->query($qph);
/////////////////////////////////////////////// HTML ///////////////////////////////////////////////////

// ==================================================== MAIN PAGE ==================================================
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
        </div>	 
        <div class="row-fluid">
             <!-- BEGIN BLANK PAGE PORTLET-->
             <div class="widget purple">
                 <div class="widget-title">
                     <h4><i class="icon-edit"></i> Invoice for company '.$dcom['nama'].' </h4>
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
                            <div class="span12">
                                <a class="btn btn-success" href="invoice_create?pr='.$pr.'"><i class="icon-plus icon-white"></i> Add New Draf Invoice </a>
                            </div>
                        <div class="space15"></div>
                    </div>
                    </div>
                    </div>
                    
                    
                </div>
             </div>
         </div>		
	</div>
</div>
';


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
.pm { display:none; }
	.pm1 { display:block; }
</style>