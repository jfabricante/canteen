<form action="<?php echo base_url('index.php/category/store'); ?>" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title"><?php echo $title; ?></h4>
	</div>

	<div class="modal-body">
		<div class="form-group hidden">
			<input type="number" class="form-control" id="id" name="id" value="<?php echo isset($entity->id) ? $entity->id : ''; ?>">
		</div>

		<div class="form-group">
			<label for="name">Category Name</label>
			<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($entity->name) ? $entity->name : ''; ?>" required>
		</div>
	</div>
	
	<div class="modal-footer">
		<div class="form-group">
			<button type="button" class="btn btn-flat btn-info pull-left" data-dismiss="modal">Close</button>
			<input type="submit" value="Submit" class="btn btn-flat btn-danger">
		</div>
	</div>
	
</form><!-- End Form -->
<script type="text/javascript">
	$(document).ready(function() {
		const appUrl = "<?php echo base_url('index.php'); ?>";

		const $name = $('#name');

		$name.on('keyup', function() {
			let $self = $(this);

			if(/^[a-zA-Z0-9- ]*$/.test($self.val()) == false)
			{
			    alert('Category name contains illegal character(s).');
			}
		});
	});
</script>
					