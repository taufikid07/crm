<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');
/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$pr 	= $_GET['pr'];
$quote  = $_GET['quote'];
$i=0;
$qt='';
$qquot="SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." AS p ORDER BY p.id_listunit DESC";
$rq= $xoopsDB->query($qquot);
while ($dq = $xoopsDB->fetchArray($rq)){
$i++;
	$qt.='
	<tr class="odd gradeX">
		<td class="hidden-phone">'.$i.'</td>
		<td>'.$dq['nama_unit'].'</td>
		<td align="center">
			<a class="fancybox btn btn-warning" href="#edit'.$dq['id_listunit'].'"><i class="icon-pencil"></i></a> 
			<div id="edit'.$dq['id_listunit'].'" style="width:400px;display: none;">
				<div class="judul_fan"><h3>Edit type</h3></div>
				<form class="form-horizontal" method="post" action="model/model_unit?pr='.$pr.'&quote='.$quote.'&unt='.$dq['id_listunit'].'">
					<div class="control-group ">
					<label for="cname" class="control-label">Company Name</label>
					<div class="controls">
						<input name="nama_unit" class="span3 " id="cname" minlength="2" type="text" required value="'.$dq['nama_unit'].'"/>
					</div>
					</div>
					<div class="form-actions">
						<button class="btn btn-success" type="submit" name="update">Update</button>
					</div>
				</form>
			</div>
			<a href="model/model_unit?del=unit_delete&pr='.$pr.'&quote='.$quote.'&unt='.$dq['id_listunit'].'" onClick="return confirm(\'Are you sure?\')" class="btn btn-danger"><i class="icon-trash"></i></a>
		</td>
	</tr>
	';
}
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
						   <a href="quotation">Quotation</a>
						   <span class="divider">/</span>
					   </li>
					   <li class="active">
						  Quotation List Unit
					   </li>
				   </ul>
				   <!-- END PAGE TITLE & BREADCRUMB-->
			  </div>
			</div>
            <div class="row-fluid">
                 <!-- BEGIN BLANK PAGE PORTLET-->
                 <div class="widget purple">
                     <div class="widget-title">
                         <h4><i class="icon-edit"></i> Unit List </h4>
                       <div class="actions sm-btn-position">
                            <a href="quote_create?pr='.$pr.'" class="btn btn-primary btn-small"> Back <i class="icon-arrow-right"></i> </a>
                        </div>
                     </div>
                     <div class="widget-body">
						 <div class="space15"></div>
						 <div class="btn-group pull-left">
							<a class="fancybox" href="#inline1">  <span class="label label-success"> <i class="icon-plus"></i> Add Unit</span> </a>
						 </div>
						 <div class="space15"></div>
                        <!-- BEGIN FORM-->    
                         <table class="table table-striped table-bordered dataTable" id="sample_1">
                            <thead>
                            <tr>
                                <th class="hidden-phone">No</th>
                                <th width="80%">Nama Unit</th>
                                <th align="center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                '.$qt.'
                            </tbody>	
                            </tbody>
                        </table>
						<!-- END FORM-->
                    </div>
                 </div>
             </div>		
			<div id="inline1" style="width:400px;display: none;">
				<div class="judul_fan"><center><h3>Add New Unit</h3></center></div>
				<hr>
				<form class="form-horizontal" method="POST" action="model/model_unit?pr='.$pr.'&quote='.$quote.'">
					<div class="control-group ">
					<label for="cname" class="control-label">Unit Name</label>
					<div class="controls">
						<input name="nama_unit" class="span3" id="cname" minlength="2" type="text" required />
					</div>
					</div>
					<div class="form-actions">
						<button class="btn btn-success" type="submit" name="insert">Save</button>
					</div>
				</form>
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
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.3"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.fancybox.css?v=2.1.2" media="screen" />
<style>
.pm {
		display:none;
	}
	.pm1 {
		display:block;
	}
</style>