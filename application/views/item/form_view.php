<link href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css');?>" rel="stylesheet" >
<?php echo form_open_multipart('item/store');?>
<!-- <form action="<?php //echo base_url('index.php/item/store'); ?>" method="post" enctype="multipart/form-data"> -->
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
		<h4 class="modal-title"><?php echo $title; ?></h4>
	</div>

	<div class="modal-body">
		<div class="form-group hidden">
			<input type="text" class="form-control" id="id" name="id" value="<?php echo isset($entity->id) ? $entity->id : 0; ?>" />
			<input type="text" class="form-control" id="item_category_id" name="item_category_id" value="<?php echo isset($entity->item_category_id) ? $entity->item_category_id : 0; ?>" />
		</div>

		<div class="form-group">
			<label for="name">Item name</label>
			<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($entity->name) ? $entity->name : ''; ?>" required autofocus />
		</div>

		<div class="form-group">
			<label for="price">Price</label>
			<input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo isset($entity->price) ? $entity->price : ''; ?>" required />
		</div>

		<div class="form-group">
			<label for="thumbnail">Thumbnail</label>
			<input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" value="<?php echo isset($entity->thumbnail) ? $entity->thumbnail : ''; ?>" />
		</div>

		<div class="form-group">
			<label for="barcode">Barcode</label>
			<input type="text" class="form-control" id="barcode" name="barcode" value="<?php echo isset($entity->barcode) ? $entity->barcode : ''; ?>" />
		</div>

		<div class="form-group">
			<label for="category_id">Category</label>
			<select name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true" required>
				<option></option>
				<?php foreach($categories as $category): ?>
					<option value="<?php echo $category->id; ?>" <?php echo isset($entity->category_id) ? $category->id == $entity->category_id ? 'selected' : '' : ''; ?> ><?php echo $category->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="modal-footer">
		<div class="form-group">
			<button type="button" class="btn btn-flat btn-info pull-left" data-dismiss="modal">Close</button>
			<input type="submit" value="Submit" class="btn btn-flat btn-danger" id="btn-submit">
		</div>
	</div>
	
</form><!-- End Form -->
<!-- End of table -->
<script src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		const appUrl = "<?php echo base_url('index.php'); ?>";

		$('.selectpicker').selectpicker({});

		const $name    = $('#name');
		const $barcode = $('#barcode');

		$name.on('keyup', function() {
			let $self = $(this);

			if(/^[a-zA-Z0-9- ]*$/.test($self.val()) == false)
			{
			    alert('Item name contains illegal character(s).');
			}
		});

		$barcode.on('keyup', function() {
			let $self = $(this);

			$.post(appUrl + '/item/ajax_has_duplicate', {barcode: $self.val()})
			.done(function(data){
				let result = JSON.parse(data);

				if (result.length > 0)
				{
					alert('The barcode of this item is already exist ' + result.join(', '));	
				}
			});

		});

	});
</script>