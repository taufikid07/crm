<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================

$queryp2 = " SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p
INNER JOIN ".$xoopsDB->prefix("crm_alamat")." AS a ON a.id_perusahaan = p.id_perusahaan WHERE a.status='1' ORDER BY p.id_perusahaan DESC";
$res_pro = $xoopsDB->query($queryp2);
$pro='';
$i=0;
while($datap = $xoopsDB->fetchArray($res_pro)){
	$stat='';
	if ($datap['status'] == 1) {
		$stat='Fujicon';
	} elseif ($datap['status'] == 2) {
		$stat='Client';
	} else {
		$stat='Vendor';
	}
	$i++;
	$status='';
	$group_selected = "SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS np WHERE status='".$datap['status']."'";	
	$group_s = $xoopsDB->query($group_selected);
	$selected='';
	while($gs = $xoopsDB->fetchArray($group_s)){
		if($gs['status']==1){$selected01='selected'; $q='Fujicon';}
		if($gs['status']==2){$selected02='selected'; $q='Client';}
		if($gs['status']==3){$selected03='selected'; $q='Vendor';}
	}
$pro.='

	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td class="hidden-phone">'.$datap['kode_perusahaan'].'</td>
		<td>'.$datap['nama'].'</td>
		<td class="hidden-phone">'.$datap['alamat'].'</td>
		<td class="hidden-phone">'.$datap['kontak_perusahaan'].'</td>
		<td class="hidden-phone">'.$datap['fax'].'</td>
		<td class="hidden-phone">'.$stat.'</td>
		<td>
			<center>
			<a href="kontak?pr='.$datap['id_perusahaan'].'"><span class="label label-inverse"> <i class="icon-arrow-left"></i>Contact </span></a>
			<a href="address?pr='.$datap['id_perusahaan'].'"><span class="label label-info"> <i class="icon-arrow-left"></i> Address </span></a>
			<a class="fancybox btn btn-warning" href="#edit'.$datap['id_perusahaan'].'"><i class="icon-pencil"></i></a> 
			<a class="btn btn-danger" href="model/model_perusahaan?delete=delete&pr='.$datap['id_perusahaan'].'"onclick="return delete_confirm();"><i class="icon-remove icon-white"></i></a>
			</center>
			<div id="edit'.$datap['id_perusahaan'].'" style="width:500px;display: none;">
				<div class="judul_fan"><h3>Edit type</h3></div>
				<form class="form-horizontal" method="post" action="model/model_perusahaan?pr='.$datap['id_perusahaan'].'">
					<div class="control-group ">
					<label for="cname" class="control-label">Company Name</label>
					<div class="controls">
						<input name="nama_perusahaan" class="span5 " id="cname" minlength="2" type="text" required value="'.$datap['nama'].'"/>
					</div>
					</div>
					<div class="control-group ">
						<label for="cname" class="control-label">Company Code</label>
						<div class="controls">
							<input name="kode_perusahaan" class="span2 " id="cname" maxlength="6" type="text" required value="'.$datap['kode_perusahaan'].'"/>
						</div>
					</div>
					<div class="control-group ">
						<label for="cname" class="control-label">Phone </label>
						<div class="controls">
							<input name="kontak_perusahaan" class="span3 " minlength="2" type="text" value="'.$datap['phone'].'"/>
						</div>
					</div>
					<div class="control-group ">
						<label for="cname" class="control-label">Fax </label>
						<div class="controls">
							<input name="fax" class="span3 " minlength="2" type="text" value="'.$datap['fax'].'"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">payment status</label>
						<div class="controls">
							<select name="status_p" class="input-large m-wrap" tabindex="1">
								'.$status.'
								<option value="1" '.$selected01.'> Fujicon</option>
								<option value="2" '.$selected02.'> Client</option>
								<option value="3" '.$selected03.'> Vendor</option>
							</select>
						</div>
					</div>
					
					<div class="form-actions">
						<button class="btn btn-success" type="submit" name="update">Update</button>
					</div>
				</form>
			</div>
		</td>
	</tr>';
	
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
                          Company
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
              </div>
            </div>
            <!-- END PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN CHART PORTLET-->
                    <div class="widget ">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i> Company</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
						<!-- START DATA COMPANY -->
						<div class="btn-group pull-left">
							<a class="fancybox" href="#inline1">  <span class="label label-success"> <i class="icon-plus"></i> Add Company</span> </a>
						</div>
						<div class="space15"></div>
						<div id="inline1" style="width:650px;display: none;">
						<div class="judul_fan"><center><h3>Add New Company</h3></center></div>
						<hr>
						<form class="form-horizontal" method="post" action="model/model_perusahaan">
							<div class="control-group ">
							<label for="cname" class="control-label">Company Name</label>
							<div class="controls">
								<input name="nama_perusahaan" class="span5 " id="cname" minlength="2" type="text" required />
							</div>
							</div>
							<div class="control-group ">
								<label for="cname" class="control-label">Company Code</label>
								<div class="controls">
									<input name="kode_perusahaan" class="span2 " id="cname" maxlength="6" type="text" required />
								</div>
							</div>
							<div class="control-group ">
								<label for="cname" class="control-label">Phone </label>
								<div class="controls">
									<input name="kontak_perusahaan" class="span3 " minlength="2" type="text"/>
								</div>
							</div>
							<div class="control-group ">
								<label for="cname" class="control-label">Fax </label>
								<div class="controls">
									<input name="fax" class="span3 " minlength="2" type="text"/>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">payment status</label>
								<div class="controls">
									<select name="status_p" class="input-large m-wrap" tabindex="1">
										<option value="1">Fujicon</option>
										<option value="2">Client</option>
										<option value="3">Vendor</option>
									</select>
								</div>
							</div>
							
							<div class="form-actions">
								<button class="btn btn-success" type="submit" name="insert">Save</button>
							</div>
							
						</form>
						</div>
						<div class="space15"></div>
						<table class="table table-striped table-bordered" id="sample_1">
							<thead>
							<tr>
								<th class="hidden-phone">No</th>
								<th class="hidden-phone">Code</th>
								<th style="width:20%">Company Name</th>
								<th class="hidden-phone">Address</th>
								<th class="hidden-phone" style="width:10%">Phone</th>
								<th class="hidden-phone">Fax</th>
								<th class="hidden-phone">Status</th>
								<th style="width:20%">Action</th>
							</tr>
							</thead>
							<tbody>
								'.$pro.'
							</tbody>
						</table>
						
						<!-- END DATA ACCOUNT TYPE-->	
                        </div>
                    </div>
                    <!-- END CHART PORTLET-->
                </div>
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