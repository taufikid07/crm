
<style type="text/css" title="currentStyle">
	@import "media/css/demo_page.css";
	@import "media/css/demo_table_jui.css";
	@import "media/examples_support/themes/smoothness/jquery-ui-1.8.4.custom.css";
</style>
<link rel="stylesheet" href="plugin/style.css" type="text/css" />

<link rel="stylesheet" href="plugin/datepicker/datepicker.css" type="text/css" />
<link rel="stylesheet" href="plugin/datepicker/bootstrap-combined.css" type="text/css" />
<script type="text/javascript" src="plugin/datepicker/jquery.js"></script>
<script type="text/javascript" src="plugin/datepicker/datepicker.js"></script>
<script type="text/javascript" src="plugin/datepicker/bootstrap.min.js"></script>

<script type="text/javascript" src="plugin/datepicker/eye.js"></script>
<script type="text/javascript" src="plugin/datepicker/layout.js?ver=1.0.2"></script>

<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		oTable = $("#example").dataTable({
			"sPaginationType": "full_numbers"
		});
	} );
</script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="plugin/source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="plugin/source/jquery.fancybox.css?v=2.1.5" media="screen" />

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

