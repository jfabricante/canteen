<form action="<?php echo base_url('index.php/item/delete'); ?>" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span></button>
	</div>

	<div class="modal-body">
		<div class="form-group hidden">
			<input type="number" class="form-control" id="id" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
		</div>
		<p class="text-center">Do you want to delete this item?</p>
	</div>

	<div class="modal-footer">
		<div class="form-group">
			<button type="button" class="btn btn-flat btn-info pull-left" data-dismiss="modal">Close</button>
			<input type="submit" value="Yes" class="btn btn-flat btn-danger">
		</div>
	</div>
	
</form><!-- End Form -->
	
				