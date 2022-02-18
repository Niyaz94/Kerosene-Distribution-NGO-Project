<?php 
	$fileName=__FILE__;
	include_once "header.php";
?>

<div class="panel panel-flat" id="testLoader">
	<table id="systemLogDatatable" class="table datatable-column-search-inputs">
		<thead>
			<tr>
				<th>ID</th>
                <th class="multi_lang" >Action User</th>
				<th class="multi_lang" >Date</th>
                <th class="multi_lang" width="20%">Page</th>
				<th class="multi_lang">Table</th>
				<th class="multi_lang">Table Row ID</th>
				<th class="multi_lang" >Action Type</th>
                <th class="multi_lang" >Note</th>
				<th class="multi_lang" width="15%">Actions</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td></td>
                <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
                <td></td>
                <td></td>
			</tr>
		</tfoot>
	</table>
</div>

<div id="show_detail_modal" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-full">
		<div class="modal-content">
			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="icon-cross"></i></button>
					<h4 class="modal-title multi_lang"> Show </h4>
			</div>
			<div class="modal-body" id="show_detail_modal_body">
				<div style="overflow:auto;">
				<table id="showData" class="table table-striped table-bordered table-hover table-condensed" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="multi_lang" width="25%">Column Name</th>
                            <th class="multi_lang" width="25%">Old Data</th>
                            <th class="multi_lang" width="25%">New Data</th>
                            <th class="multi_lang" width="25%">Date</th>
                        </tr>
                    </thead>
                    <tbody>	
					</tbody>
                </table>
				</div>
			<div>
			<div class="modal-footer"><?php
				echo button2("editSystemlogFormButton","button","Save","icon-floppy-disk","btn btn-labeled btn-labeled-left heading-btn btn-primary");
				echo button2("cancelCustomerFormButton","button","Close","icon-cross","btn btn-warning",'data-dismiss="modal"')
			?></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="controllers/systemlog.js?random=<?php echo uniqid(); ?>"></script>