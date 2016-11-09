<?php
// Tutorial                    										
// Created by Taufikid												
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('plugin/css_js.php');
$uid = $xoopsUser->getVar('uid');

/////////////////////////////////////////////// Action ///////////////////////////////////////////////////
$pr = $_GET['pr'];
// Untuk Perusahan
$qpr=" select * from ".$xoopsDB->prefix("crm_perusahaan")." as p 
INNER JOIN ".$xoopsDB->prefix("crm_kontak")." AS k ON k.id_perusahaan = p.id_perusahaan
where p.id_perusahaan=".$pr."";
$rp= $xoopsDB->query($qpr);
// Kontak
$u_kn='';
while($dp = $xoopsDB->fetchArray($rp)){
	$u_kn.='<option value="'.$dp['id_kontak'].'">'.$dp['nama_kontak'].'';
};


$qkegitan=" select * from ".$xoopsDB->prefix("crm_perusahaan")." as per 
INNER JOIN ".$xoopsDB->prefix("crm_kegiatan")." AS keg ON keg.id_perusahaan = per.id_perusahaan
where per.id_perusahaan=".$pr."";
$rkg= $xoopsDB->query($qkegitan);
// Kegiatan
$u_keg='';
while($dkg = $xoopsDB->fetchArray($rkg)){
	$u_keg.='<option value="'.$dkg['id_kegiatan'].'">'.$dkg['nama_kegiatan'].'';
};


// Untuk Manager
$qpm = "SELECT * FROM ".$xoopsDB->prefix("users")." AS u
INNER JOIN ".$xoopsDB->prefix("groups_users_link")." AS gul ON gul.uid = u.uid
INNER JOIN ".$xoopsDB->prefix("groups")." AS g ON g.groupid = gul.groupid
WHERE u.uid = gul.uid AND gul.groupid = 10 ORDER BY u.name ASC";
$rpm = $xoopsDB->query($qpm);

// Project Manager
$u_pm='';
while($dpm = $xoopsDB->fetchArray($rpm)){
	$u_pm.='<option value="'.$dpm['uid'].'">'.$dpm['uname'];
}

$ulist=" SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." AS u ORDER BY u.nama_unit ASC";
$rul = $xoopsDB->query($ulist);
$u_lt='';
while($dlu = $xoopsDB->fetchArray($rul)){
	$u_lt.='<option value="'.$dlu['id_listunit'].'">'.$dlu['nama_unit'];
}


$qvp=" select * from ".$xoopsDB->prefix("crm_perusahaan")." as p 
where p.id_perusahaan=".$pr."";
$rper= $xoopsDB->query($qvp);
$dper = $xoopsDB->fetchArray($rper);
$skrg=date('Y-m-d');
$month_now= date('m', strtotime($skrg));
$qoute_now= date('Y', strtotime($skrg));
$skrg2week=date('Y-m-d', strtotime(' +14 day'));

// PENCARIAN CODE QUOTATION
	$cdate = date('Y-m', strtotime($skrg));
	$mcdate = date('m', strtotime($skrg));
	$ycdate = date('Y', strtotime($skrg));
	$date_quote= date('m/Y', strtotime($skrg));
	$mdate_quote= date('m', strtotime($skrg));
	$ydate_quote= date('Y', strtotime($skrg));
	$cek_no_per = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('crm_perusahaan')." WHERE id_perusahaan='$_GET[pr]'");
	$q_noper = $xoopsDB->fetchArray($cek_no_per);
	$codep = $q_noper['kode_perusahaan'];
	
	$cek_no_quote = $xoopsDB->query("SELECT MAX(codeq) AS max_codeq FROM ".$xoopsDB->prefix('crm_quotation')." WHERE  id_perusahaan='$_GET[pr]' AND tgl_TerbitInvoice LIKE '$ycdate-%-%' AND no_quote LIKE '%/$codep/%/$ydate_quote'");
	$q_noqote = $xoopsDB->fetchArray($cek_no_quote);
	
	//$ss="SELECT MAX(codeq) AS max_codeq FROM ".$xoopsDB->prefix('crm_quotation')." WHERE  id_perusahaan='$_GET[pr]' AND tgl_TerbitInvoice LIKE '$ycdate-%-%' AND no_quote LIKE '%/$codep/%/$ydate_quote'";
	//echo $ss.'==WOOOII';
	
	if(empty($q_noqote['max_codeq'])){
		$cek_last = $xoopsDB->query("SELECT MAX(codeq) AS max_codeq FROM ".$xoopsDB->prefix('crm_quotation')." WHERE  id_perusahaan='$_GET[pr]' AND no_quote LIKE '%/$codep/%/$ydate_quote'");
		
		$r_qote = $xoopsDB->getRowsNum($cek_last);
		$q_qote = $xoopsDB->fetchArray($cek_last);		
		$qu2 = 1;
		
	}else {
	$cek_last = $xoopsDB->query("SELECT MAX(codeq) AS max_codeq FROM ".$xoopsDB->prefix('crm_quotation')." WHERE  id_perusahaan='$_GET[pr]' AND tgl_TerbitInvoice LIKE '$ycdate-%-%' AND no_quote LIKE '%/$codep/%/$ydate_quote'");
	$q_qote = $xoopsDB->fetchArray($cek_last);
	
	$max_qu2 = $q_qote['max_codeq'];
	$qu2 = ($max_qu2 + 1 ) % 100;
	//echo $qu2.'<===ADA <br>';
	}
	$quq2 = str_pad($qu2, 3, "0", STR_PAD_LEFT);
	$no_quot = $quq2.'/'.$codep.'/'.$date_quote.''; 
	// END PEMBUATAN CODE QUOTE
	$quse=$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." AS q WHERE uid=".$uid);
	$duse = $xoopsDB->fetchArray($quse);


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
					   <a href="quotation">Quotation</a>
					   <span class="divider">/</span>
				   </li>
				   <li class="active">
					  Create Quotation
				   </li>
               </ul>
               <!-- END PAGE TITLE & BREADCRUMB-->
          </div>
        </div>
        <!-- END PAGE HEADER-->
		<div class="row-fluid">
			<div class="span12">
			 <!-- BEGIN BLANK PAGE PORTLET-->
			 <div class="widget purple">
				 <div class="widget-title">
					 <h4><i class="icon-edit"></i> Create Draf Quotation </h4>
				   <span class="tools">
					   <a href="javascript:;" class="icon-chevron-down"></a>
					   <a href="javascript:;" class="icon-remove"></a>
				   </span>
				 </div>
				 <div class="widget-body">
					 <div class="portlet-body">
					 <div class="space15"></div>
						<form class="cmxform form-horizontal" id="commentForm" name="form_perusahaan" action="model/model_quote?pr='.$pr.'" method="post">
						 <div class="row-fluid">
							<div class="span2 billing-form">
							&nbsp;
							</div>
							 <div class="span8 billing-form">
								 <div class="space10"></div>
								 <input name="perusahaan" type="hidden" value="'.$pr.'">
								 <input name="codeq" type="hidden" value="'.$qu2.'">
									<div class="control-group ">
										 <label class="control-label">No Quotation</label>
										 <input name="no_quote" type="text" placeholder=" Input activity name" class="span5" value="'.$no_quot.'">
									 </div>
									 <div class="control-group ">
										 <label class="control-label">Client Name</label>	
										<select name="kontak" class="span5 " data-placeholder="Choose a Category" tabindex="1">
											<option value=""> Choose
											'.$u_kn.'	
										</select>
									 </div>
									 <div class="control-group ">
										 <label class="control-label">Use Project Name</label>								
										<select name="kegiatan" class="span5 " data-placeholder="Choose a Category" tabindex="1">
											<option value=""> Choose
											'.$u_keg.'	
										</select>
									 </div>
									 <div class="control-group ">
										 <label class="control-label">New Project Name</label>
										 <input name="xkegiatan" type="text" placeholder=" Input activity name" class="span5">
									 </div>
									 <div class="control-group ">
										 <label class="control-label">Additional Information</label>
										 <input name="deskripsi" type="text" placeholder=" Input description activity" class="span8">
									 </div>
									 <div class="control-group ">
										<label class="control-label">Project Manager</label>
										 <select name="project_manager" class="input-xlarge m-wrap" tabindex="1">
											<option value=""> Choose
											'.$u_pm.'
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
											<th class="text-center">Order By</th>
											<th class="text-center">Spasi</th>
											<th class="text-center span7">Description</th>
											<th class="text-center">Unit</th>
											<th class="text-center">Unit Price</th>
											<th class="text-center">Quantity</th>
											<th class="text-center">total Price</th>
										</tr>
									</thead>
									<tbody id="cons">
										<tr>
											<td>
												<input name="orderby[]" type="text" class="input-mini" value="100" readonly="readonly">
											</td>
											<td>
												<select name="spasi[]" class="input-small m-wrap" tabindex="1" style="font-weight:bold;">
													<option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>
													<option value="10"> &nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;</option>
													<option value="20"> &nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; </option>
													<option value="30"> &nbsp;&nbsp;&nbsp;&nbsp;>>>&nbsp;&nbsp;&nbsp; </option>
												</select>
											</td>
											<td>
												<textarea name="des[]" class="span12" rows="3"></textarea>
											</td>
											<td>
												<select name="unit[]" class="input-medium m-wrap" tabindex="1">
													<option value=""> Choose
													'.$u_lt.'
												</select> <br><br>
												<a href="quote_listunit?pr='.$pr.'" class="btn btn-primary btn-mini"><i class="icon-cogs"></i> Setting</a>
											</td>
											<td><input type="text" name="price[]" id="price_1" class="input-small changesNo"></td>
											<td><input type="text" name="quantity[]" id="quantity_1" class="input-small changesNo"></td>
											<td><input type="text" name="total[]" id="total_1" class="input-medium totalLinePrice"></td>
										</tr>
									</tbody>
								</table>
								<div class="space10"></div>
								
								<input type="button" value="Add New" id="add_new1" class="btn btn-small">
								<input type="button" value="Subtotal" id="subtot1" class="btn btn-small">
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
										 <input name="acc_num" type="text" value="130 00 09017222" class="m-ctrl-medium" readonly="readonly">
									 </div>
							 </div>
							 
							 <div class="span4 invoice-block pull-right">
								<table width="100%">
									<tr>
										<td style="width:50%; text-align:right; padding-left:30px;"><b> SUB TOTAL &nbsp;</b></td>
										<td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
										<input name="sub_total" type="number" class="input-medium" id="subTotal">
										</b></td>
									</tr>
									<tr>
										<td style="width:50%; text-align:right; padding-left:30px;"><b>TAX &nbsp; % &nbsp;</b></td>
										<td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
										<select name="tax_percent" class="input-medium m-wrap" tabindex="1" id="tax">
											<option value="0">None</option>
											<option value="10">10%</option>
										</select>
										</b></td>
									</tr>
									<tr>
										<td style="width:50%; text-align:right; padding-left:30px;"><b> TAX Amount &nbsp;</b></td>
										<td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
										<input name="tax" type="number" class="input-medium" id="taxAmount">
										</b></td>
									</tr>
									<tr>
										<td style="width:50%; text-align:right; padding-left:30px;"><b> TOTAL &nbsp;</b></td>
										<td style="width:50%; text-align:right; padding-right:10px; border:2px solid #ddd;"><b>
										<input name="total_max" type="number" class="input-medium" id="totalAftertax">
										</b></td>
									</tr>
								</table>
							 </div>
						 </div>
						 <br>
						 <div class="row-fluid">
							 <div class="span6 billing-form">
								 <h4>published</h4>
								 <div class="space10"></div>
									 <div class="control-group ">
										 <label class="control-label">Published by</label>
										 <select name="pub_invoice" class="input-xlarge m-wrap" tabindex="1">
											<option value="'.$duse['uid'].'"> '.$duse['uname'].'
											<option value="">====================================
											'.$u_pm.'
										 </select>
									 </div>
									 <div class="control-group ">
										 <label class="control-label">date publish Quotation</label>
										 <input name="date_invoice" id="dp2" type="text" value="'.$skrg.'" size="16" class="m-ctrl-medium">
										 <input name="date_kuitansi" id="dp3" type="hidden" value="'.$skrg2week.'" size="16" class="m-ctrl-medium">
										  <input name="pub_kuitansi" type="hidden" value="'.$uid.'" size="16" class="m-ctrl-medium">
									 </div>
							 </div>
							 <div class="span6 billing-form">
									 <h4>Currency format</h4>
									 <div class="space10"></div>
										 <div class="control-group ">
											 <label class="control-label">Currency format / used</label>
											 <select name="bahasa" class=" span8">
												 <option value="Rp.">Rupian ( IDR )</option>
												 <option value="JPY">Yen ( JPY )</option>
												 <option value="USD">Dollar ( USD )</option>
											 </select>
										 </div>
										 <div class="control-group ">
											 <label class="control-label">Terms and Conditions</label>
											  <textarea name="syarat" class="input-large span8" rows="3"></textarea>
										 </div>
								 </div>	
							 <div class="span12">
								<div class="row-fluid text-center">
									<button class="btn btn-success" type="submit" name="insert">Submit Quotation</button>
								 </div>
							 </div>
							 </form>
						 </div>
					 </div>
				 </div>
			 </div>
			 <!-- END BLANK PAGE PORTLET-->
			</div>
		</div>	
    </div>
    <!-- END PAGE CONTAINER-->
</div>	
';
$ulistj=" SELECT * FROM ".$xoopsDB->prefix("crm_listunit")." AS u ORDER BY u.nama_unit ASC";
$rulj = $xoopsDB->query($ulistj);
$u_ltj='';
$u_ltj.='<select name="unit[]" class="input-medium m-wrap" tabindex="1"><option value="">Choose</option>';
while($dluj = $xoopsDB->fetchArray($rulj)){
	$u_ltj.='<option value="'.$dluj['id_listunit'].'">'.$dluj['nama_unit'];
}
$u_ltj.="</select>";
/////////////////////////////////////// End New Project /////////////////////////////////////////////////////
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
   
   
  <script> 
$(document).ready(function(){
		var counter1c = 200;
		var i=100;
		$('#add_new1').click(function(){
			i=i+100;
			$('#cons').append(
				'<tr id="text_input1_'+counter1c+'">'+
				'<td><input name="orderby[]" type="text" class="input-mini" value="'+i+'" readonly="readonly"></td>'+
				'<td><select name="spasi[]" class="input-small m-wrap" tabindex="1" style="font-weight:bold;">'+
					'<option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="10"> &nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="20"> &nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; </option>'+
					'<option value="30"> &nbsp;&nbsp;&nbsp;&nbsp;>>>&nbsp;&nbsp;&nbsp; </option>'+
				'</select></td>'+
				'<td><textarea name="des[]" class="span12" rows="3"></textarea></td>'+
				'<td>'+
				<?php echo "'".$u_ltj."<br><br>'+" ?>
					'<a href="quote_listunit?pr=<?php echo $pr ?>" class="btn btn-primary btn-mini"><i class="icon-cogs"></i> Setting</a>'+
				'</td>'+
				'<td><input type="text" name="price[]" id="price_'+i+'" class="input-small changesNo"></td>'+
				'<td><input type="text" name="quantity[]" id="quantity_'+i+'" class="input-small changesNo"></td>'+
				'<td><input type="text" name="total[]" id="total_'+i+'" class="input-medium totalLinePrice"></td>'+
				'</tr>'
			);
		});	
		
		
		
		var countersub = 200;
		var j=100;
		$('#subtot1').click(function(){
			j=j+100;
			$('#cons').append(
				'<tr id="text_input1_'+countersub+'">'+
				'<td><input name="orderby[]" type="text" class="input-mini" value="'+i+'" readonly="readonly"></td>'+
				'<td><select name="spasi[]" class="input-small m-wrap" tabindex="1" style="font-weight:bold;">'+
					'<option value="0"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="10"> &nbsp;&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;</option>'+
					'<option value="20"> &nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; </option>'+
					'<option value="30"> &nbsp;&nbsp;&nbsp;&nbsp;>>>&nbsp;&nbsp;&nbsp; </option>'+
				'</select></td>'+
				'<td><textarea name="des[]" class="span12" rows="3"></textarea></td>'+
				'<td><input type="text" name="unit[]" class="input-small" readonly="readonly"></td>'+
				'<td><input type="text" name="price[]" id="price_'+i+'" class="input-small" readonly="readonly"></td>'+
				'<td><input type="text" name="quantity[]" id="quantity_'+i+'" class="input-small" readonly="readonly"></td>'+
				'<td><input type="text" name="total[]" id="total_'+i+'" class="input-medium" ></td>'+
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
   