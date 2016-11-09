<?php
// Tutorial                    										
// Created by KaotiK												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');



$qjoin="SELECT * FROM ".$xoopsDB->prefix("ct_kjoin")." AS j ORDER BY j.id_kjoin ASC";
$resj = $xoopsDB->query($qjoin);
$kj='';
$i=0;
while($dj = $xoopsDB->fetchArray($resj)){
	$i++;
	$uquery1=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$dj['uid']);
	$datal = $xoopsDB->fetchArray($uquery1);
	$uquery2=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=".$dj['approval']);
	$data2 = $xoopsDB->fetchArray($uquery2);
	
	$gab = date("d F Y", strtotime($dj['joindate']));
	$hc = date("d F Y", strtotime($dj['hak_cuti']));
	
	$kj.='
		<tr class="odd gradeX">
			<td>'.$i.'</td>
			<td>'.$datal['name'].'</td>
			<td>'.$dj['divisi'].'</td>
			<td>'.$dj['jabatan'].'</td>
			<td>'.$gab.'</td>
			<td>'.$hc.'</td>
			<td>'.$data2['name'].'</td>
			<td>
				<center>
				<a class="btn btn-inverse" href="detail_cuti.php?detail='.$dj['id_kjoin'].'">Detail Leave</a>
				</center>
			</td>
			<td>
			<center>';				
				// User
				$qusr = "SELECT * FROM 
						".$xoopsDB->prefix("dr_users")." AS dus INNER JOIN ".$xoopsDB->prefix("users")." AS ar ON ar.uid = dus.uid 
						ORDER BY ar.uname ASC";
				$resusr = $xoopsDB->query($qusr);
				$groupus='';
				while($qusr = $xoopsDB->fetchArray($resusr)){
					$group_selected = "SELECT * FROM ".$xoopsDB->prefix("ct_kjoin")." AS kj WHERE uid='".$dj['uid']."'";						
					$group_s = $xoopsDB->query($group_selected);
					$selected='';
					while($gs = $xoopsDB->fetchArray($group_s)){
						if($qusr['uid']==$gs['uid']){$selected='selected';}
					}
					$groupus.='<option value="'.$qusr['uid'].'" '.$selected.'>'.$qusr['uname'].'</option>';
				}
				$qpm = "SELECT * FROM ".$xoopsDB->prefix("users")." AS u
				INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.uid = u.uid
				INNER JOIN ".$xoopsDB->prefix("groups")." AS g ON g.groupid = gul.groupid
				WHERE u.uid = gul.uid AND gul.groupid = 10 ORDER BY u.uname ASC";
				$respm = $xoopsDB->query($qpm);
				$grouppm='';
				while($dpm = $xoopsDB->fetchArray($respm)){
					$group_selected = "SELECT * FROM ".$xoopsDB->prefix("ct_kjoin")." AS kj WHERE kj.approval='".$dj['approval']."'";						
					$group_s = $xoopsDB->query($group_selected);
					$selected='';
					while($gs = $xoopsDB->fetchArray($group_s)){
						if($dpm['uid']==$gs['approval']){$selected='selected';}
					}
					$grouppm.='<option value="'.$dpm['uid'].'" '.$selected.'>'.$dpm['uname'].'</option>';
				}
				
				
				$kj.='
				<a class="fancybox btn btn-warning" href="#edit'.$dj['id_kjoin'].'"><i class="icon-pencil"></i></a>
				<div id="edit'.$dj['id_kjoin'].'" style="width:500px;display: none;">
					<div class="judul_fan"><h3>Edit personnal Leave</h3></div>
					<form class="form-horizontal" method="post" action="model/kcuti_cud.php?id_kjoin='.$dj['id_kjoin'].'">
							<div class="control-group">
								<label class="control-label">Employee Name</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="guid">
										<option value=""> Choose
										'.$groupus.'
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Date of entry</label>
								<div class="controls">
									<input name="gtgl" id="dp3" type="text" value="'.$dj['joindate'].'" size="16" class="m-ctrl-medium">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Division</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="gdivisi">
										<option value="'.$dj['divisi'].'">'.$dj['divisi'].'</option>
										<option value="">==========================</option>
										<option value="Project/Production"> Project/Production</option>
										<option value="Finance"> Finance</option>
										<option value="Human Resource"> Resource</option>
										<option value="Resource"> Resource</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Position</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="gjabatan">
										<option value="'.$dj['jabatan'].'">'.$dj['jabatan'].'</option>
										<option value="">==========================</option>
										<option value="Presiden Direktur"> Presiden Direktur</option>
										<option value="Office Manager"> Office Manager</option>
										<option value="Assistant Manager"> Assistant Manager</option>
										<option value="Project Manager"> Project Manager</option>
										<option value="Project Leader"> Project Leader</option>
										<option value="Staff"> Staff</option>
										<option value="General Affair"> General Affair</option>
										<option value="Human Resource"> Human Resource</option>
										<option value="Marketing"> Marketing</option>										
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Employee Name</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="approval">
										<option value=""> Choose
										'.$grouppm.'
									</select>
								</div>
							</div>
						<div class="form-actions">
							<input type="submit" class="btn blue" value="Save" name="update">
						</div>
					</form>
				</div>
				<a class="btn btn-danger" href="model/kcuti_cud.php?delete=delete&id_kjoin='.$dj['id_kjoin'].'" onclick="return delete_confirm();"><i class="icon-remove"></i></a> 
				</center>
			</td>
		
	';
}
$query = "SELECT * FROM 
".$xoopsDB->prefix("dr_users")." AS du
INNER JOIN ".$xoopsDB->prefix("users")." AS a ON a.uid = du.uid ORDER BY a.uname ASC";
$result = $xoopsDB->query($query);
$user='';
while($qu = $xoopsDB->fetchArray($result)){
	$user.='<option value="'.$qu['uid'].'">'.$qu['uname'];
}
/*
$qgroup=" SELECT * FROM ".$xoopsDB->prefix("groups")." AS g ORDER BY g.name ASC";
$resgr= $xoopsDB->query($qgroup);
$gr='';
while($qg = $xoopsDB->fetchArray($resgr)){
	$gr.='<option value="'.$qg['groupid'].'">'.$qg['name'];
}
*/

// PROJECT MANAGER
$querypm = "SELECT * FROM
".$xoopsDB->prefix("users")." AS u
INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.uid = u.uid
INNER JOIN ".$xoopsDB->prefix("groups")." AS g ON g.groupid = gul.groupid
WHERE
u.uid = gul.uid AND
gul.groupid = 10 ORDER BY u.uname ASC";
$ress = $xoopsDB->query($querypm);
$u_pm='';
while($qus = $xoopsDB->fetchArray($ress)){
	$u_pm.='<option value="'.$qus['uid'].'">'.$qus['uname'];
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
                     Personal Leave
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
                           <a href="index.php">Personal Leave</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                          Home Page Personal Leave
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
                            <h4><i class="icon-reorder"></i> Home Personal Leave</h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                        </div>
                        <div class="widget-body">
						<!-- START DATA ACCOUNT TYPE -->
						<div class="btn-group pull-left">
							<a class="fancybox" href="#inline1">  <span class="label label-success"> <i class="icon-plus"></i> Add Personal Leave </span> </a>
						</div>
						<div class="widget-body form">
						<div id="inline1" style="width:500px;display: none;">
						<div class="judul_fan"><h3>Add Personnal Leave</h3></div>
						<form class="form-horizontal" method="post" action="model/kcuti_cud.php">
							
							<div class="control-group">
								<label class="control-label">Employee Name</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="guid">
										<option value=""> Choose
										'.$user.'
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Date of entry</label>
								<div class="controls">
									<input name="gtgl" id="dp2" type="text" value="'.date("Y-m-d").'" size="16" class="m-ctrl-medium">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Division</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="gdivisi">
										<option value=""> Choose</option>
										<option value="Project/Production"> Project/Production</option>
										<option value="Finance"> Finance</option>
										<option value="Human Resource"> Resource</option>
										<option value="Resource"> Resource</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Position</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="gjabatan">
										<option value=""> Choose</option>
										<option value="Presiden Direktur"> Presiden Direktur</option>
										<option value="Office Manager"> Office Manager</option>
										<option value="Assistant Manager"> Assistant Manager</option>
										<option value="Project Manager"> Project Manager</option>
										<option value="Project Leader"> Project Leader</option>
										<option value="Staff"> Staff</option>
										<option value="General Affair"> General Affair</option>
										<option value="Human Resource"> Human Resource</option>
										<option value="Marketing"> Marketing</option>										
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Leave Approval</label>
								<div class="controls">
									<select class="input-large m-wrap" tabindex="1" name="approval">
										<option value=""> Choose
										'.$u_pm.'
									</select>
								</div>
							</div>
							<div class="form-actions">
								<input type="submit" class="btn blue" value="Save" name="insert">
							</div>
						</form>
						</div>
                        </div>						
						
						<div class="space15"></div>
						<table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                            <tr>
                                <th>no</th>
                                <th>Employee Name</th>
								<th>Division</th>
								<th>Position</th>
								<th>Date of entry</th>
								<th>Leave entitlements</th>
								<th>Approval leave</th>
								<th>Detail</th>
                                <th style="width:15%;">Action</th>
                            </tr>
                            </thead>
                            <tbody> 
								'.$kj.'
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

<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
	function delete_confirm(){
		var a;
		a=confirm("Are you sure?");
		if(a==true) return true
		else return false
	}
</script>
