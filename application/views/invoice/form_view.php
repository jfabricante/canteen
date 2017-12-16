<link href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css');?>" rel="stylesheet" >
<?php echo form_open('transaction/invoice_update');?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title"><?php echo $title; ?></h4>
	</div>

	<div class="modal-body">
		<div class="form-group hidden">
			<input type="text" class="form-control" id="id" name="id" value="<?php echo isset($entity['id']) ? $entity['id'] : 0; ?>" />
		</div>

		<div class="form-group">
			<label for="invoice_no">Invoice No.</label>
			<input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo isset($entity['id']) ? sprintf('%06d', $entity['id']) : ''; ?>" required  readonly />
		</div>

		<div class="form-group">
			<label for="status">Status</label>
			<input type="text" class="form-control" id="status" name="status" value="<?php echo isset($entity['status']) ? $entity['status'] : ''; ?>" required autofocus />
		</div>

	</div>
	
	<div class="modal-footer">
		<div class="form-group">
			<button type="button" class="btn btn-flat btn-info pull-left" data-dismiss="modal">Close</button>
			<input type="submit" value="Submit" class="btn btn-flat btn-danger">
		</div>
	</div>
	
</form><!-- End Form -->
<!-- End of table -->
<script src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.selectpicker').selectpicker({});
	});
</script>