<link href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css');?>" rel="stylesheet" >
<?php echo form_open('user/store');?>
<!-- <form action="<?php //echo base_url('index.php/item/store'); ?>" method="post" enctype="multipart/form-data"> -->
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
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" name="username" value="<?php echo isset($entity['employee_no']) ? $entity['employee_no'] : ''; ?>" readonly autofocus />
		</div>

		<div class="form-group">
			<label for="employee_no">Employee No.</label>
			<input type="text" class="form-control" id="employee_no" name="employee_no" value="<?php echo isset($entity['employee_no']) ? $entity['employee_no'] : ''; ?>" readonly />
		</div>

		<div class="form-group">
			<label for="fullname">Name</label>
			<input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo isset($entity['fullname']) ? $entity['fullname'] : ''; ?>" readonly/>
		</div>

		<div class="form-group">
			<label for="role_id">Role</label>
			<select name="role_id" id="role_id" class="form-control selectpicker" data-live-search="true" required>
				<option></option>
				<?php foreach($roles as $row): ?>
					<option value="<?php echo $row['id']; ?>" <?php echo isset($entity['role_id']) ? $row['id'] == $entity['role_id'] ? 'selected' : '' : ''?> ><?php echo ucfirst($row['user_type']); ?></option>
				<?php endforeach; ?>
			</select>
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