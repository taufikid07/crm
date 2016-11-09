<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');



/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$pr=$_GET['pr'];
$queryp2 = " SELECT * FROM ".$xoopsDB->prefix("crm_kontak")." AS k WHERE k.id_perusahaan='".$pr."'	ORDER BY k.id_kontak ASC";
$res_pro = $xoopsDB->query($queryp2);
$knt='';
$i=0;
while($datap = $xoopsDB->fetchArray($res_pro)){
$i++;
$knt.='
	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td>'.$datap['nama_kontak'].'</td>
		<td class="hidden-phone">'.$datap['no_hp'].'</td>
		<td class="hidden-phone">'.$datap['email'].'</td>
		<td class="hidden-phone">
		<a class="fancybox btn btn-warning" href="#edit'.$datap['id_kontak'].'"><i class="icon-pencil"></i></a> 
			<div id="edit'.$datap['id_kontak'].'" style="width:500px;display: none;">
				<div class="judul_fan"><h3>Edit type</h3></div>
				<form class="form-horizontal" method="post" action="model/model_kontak?pr='.$datap['id_perusahaan'].'&kn='.$datap['id_kontak'].'">
					<div class="control-group ">
					<label for="cname" class="control-label">Contact Name</label>
					<div class="controls">
						<input name="nama_kontak" class="span5 " id="cname" minlength="2" type="text" required value="'.$datap['nama_kontak'].'"/>
					</div>
					</div>
					<div class="control-group ">
						<label for="cname" class="control-label">No Handphone</label>
						<div class="controls">
							<input name="hp" class="span2 " id="cname" type="text" required value="'.$datap['no_hp'].'"/>
						</div>
					</div>
					<div class="control-group ">
						<label for="cname" class="control-label">Email </label>
						<div class="controls">
							<input name="email" class="span3 " minlength="2" type="text" value="'.$datap['email'].'"/>
						</div>
					</div>					
					<div class="form-actions">
						<button class="btn btn-success" type="submit" name="update">Update</button>
					</div>
				</form>
			</div>
			<a class="btn btn-danger" href="model/model_kontak?delete=delete&pr='.$pr.'&kn='.$datap['id_kontak'].'"onclick="return delete_confirm();"><i class="icon-remove icon-white"></i></a>
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
                       <li>
                           <a href="perusahaan">Company</a>
                           <span class="divider">/</span>
                       </li>
					   <li class="active">
                          Contact
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
                            <h4><i class="icon-reorder"></i> Contact</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
						<!-- START DATA Contact -->
						<div class="btn-group pull-left">
							<a class="fancybox" href="#inline1">  <span class="label label-success"> <i class="icon-plus"></i> Add Contact</span> </a>
						</div>
						<div class="space15"></div>
						<div id="inline1" style="width:650px;display: none;">
						<div class="judul_fan"><center><h3>Add New Contact</h3></center></div>
						<hr>
						<form class="form-horizontal" method="POST" action="model/model_kontak?pr='.$pr.'">
							<div class="control-group ">
						<label for="cname" class="control-label">Name</label>
						<div class="controls">
							<input name="nama_kontak" class="span5 " id="cname" minlength="2" type="text" required />
						</div>
						</div>
						<div class="control-group ">
							<label for="cname" class="control-label">No Handphone</label>
							<div class="controls">
								<input name="hp" class="span5 " id="cname" minlength="2" type="text" required />
							</div>
						</div>
						<div class="control-group ">
							<label for="cemail" class="control-label">E-Mail</label>
							<div class="controls">
								<input class="span5 " id="cemail" type="email" name="email" required />
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
								<th>Client Name</th>
								<th class="hidden-phone">No. Handphone</th>
								<th class="hidden-phone">Email</th>
								<th class="hidden-phone">Action</th>
							</tr>
							</thead>
							<tbody>
								'.$knt.'
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