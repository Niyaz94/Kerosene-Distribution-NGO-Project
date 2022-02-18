<?php
	function headerJS($page){
		
		$link='';
		if($page=="languages"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				

				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}else if($page=="translation"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				


				
				
				

				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/touchspin.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/maxlength.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/formatter.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/pages/components_popups.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}elseif($page=="admin"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				
				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<!-- file need for bootstrap_multiselect -->			
				<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>	
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
				<!-- file need for switch -->		
				<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/switch.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/duallistbox.min.js"></script>
				
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>

			';
		}else if($page=="systemlog"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			
			';
		}else if($page=="profile"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
			';
		}else if($page=="setting"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="camp"){
			$link.='

			 	<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				


				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>

			';
		}elseif($page=="product"){
			$link='

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				

				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<!-- file need for bootstrap_multiselect -->			
				<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>	
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
				
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}elseif($page=="family"){
			$link='

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				

				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>

				<script type="text/javascript" src="../API/barcode/dist/JsBarcode.all.min.js"></script>


			';
		}elseif($page=="round"){
			$link='

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				

				
				
				

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}elseif($page=="donation"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/barcode/dist/JsBarcode.all.min.js"></script>
				<script type="text/javascript" src="../API/barcodeReader/jquery.scannerdetection.js"></script>
			';
		}elseif($page=="donation2"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/barcode/dist/JsBarcode.all.min.js"></script>
				<script type="text/javascript" src="../API/barcodeReader/jquery.scannerdetection.js"></script>
			';
		}elseif($page=="donationview"){
			$link='

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				
				
				
				
				
				
				
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
			';
		}elseif($page=="dashboard"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/visualization/echarts/echarts.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/charts/echarts/timeline_option.js"></script>
			';
		}else if($page=="distribution"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>

				<script type="text/javascript" src="../API/barcode/dist/JsBarcode.all.min.js"></script>
			';
		}else if($page=="contractor"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}else if($page=="voucher"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}else if($page=="notreceived"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}else if($page=="rounddetail"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}else if($page=="roundtotal"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/ripple.min.js"></script>
			';
		}
		$link.='
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/noty.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/jgrowl.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/sweet_alert.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/uniform.min.js"></script>

			<script type="text/javascript" src="../API/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
		
		
			<script type="text/javascript" src="_general/frontend/alert.js"></script>
			<script type="text/javascript" src="_general/frontend/messege.js"></script>
			<script type="text/javascript" src="_general/frontend/loader.js"></script>
			<script type="text/javascript" src="_general/frontend/buttons.js"></script>
			<script type="text/javascript" src="_general/frontend/tooltip.js"></script>
			<script type="text/javascript" src="_general/frontend/dataTable.js"></script>
			<script type="text/javascript" src="_general/frontend/generalValidation.js"></script>
			<script type="text/javascript" src="_general/frontend/generalFunctions.js"></script>
		';

		return $link;
	}
?>


