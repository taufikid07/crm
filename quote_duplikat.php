<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

// ACTION =================================================================================================
$tglnow=date('Y-m-d');
$pr 	= $_GET['pr'];
$quote 	= $_GET['quote'];

/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$queryp2 = " SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p	ORDER BY p.nama ASC";
$res_pro = $xoopsDB->query($queryp2);

$qph=" SELECT * FROM ".$xoopsDB->prefix("crm_perusahaan")." AS p order by p.nama ASC";
$rph= $xoopsDB->query($qph);


echo'

<div id="main-content">
     <!-- BEGIN PAGE CONTAINER-->
     <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->   
        <div class="row-fluid">
           <div class="span12">
              <!-- BEGIN PAGE TITLE & BREADCRUMB-->
               <h3 class="page-title">
                 Duplicate Quotation
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
				   <li>
                       <a href="quote_view?pr='.$pr.'&quote='.$quote.'">View Quotation</a>
                       <span class="divider">/</span>
                   </li>
                   <li class="active">
                      Duplicate Quotation
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
                     <h4><i class="icon-edit"></i>Duplicate Quotation List </h4>
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
                                                echo'<option value="quote_duplikat_next?pr='.$pr.'&quote='.$quote.'&dup='.$dth['id_perusahaan'].'">'.$dth['nama'].'';
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