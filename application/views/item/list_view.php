<!-- Items block -->
<section class="content items">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-10">
			<!-- Box danger -->
			<?php echo $this->session->flashdata('message'); ?>
			
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-header with-border">
					<a href="<?php echo base_url('index.php/item/form') ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
						<button class="btn btn-flat btn-success pull-right">Add Item <i class="fa fw fa-plus" aria-hidden="true"></i></button>
					</a>
				</div>

				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>Thumbnail</th>
								<th>Name</th>
								<th>Price</th>
								<th>Category</th>
								<th>Barcode</th>
								<th>Date Time</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>

						<tbody>
							<?php $count = 1; ?>
							<?php foreach($items as $item): ?>
								<tr>
									<td class="text-center">
										<img class="img-responsive item-thumbnail" src="<?php echo $item->thumbnail ? base_url('/resources/thumbnail/' . $item->thumbnail) : base_url('/resources/thumbnail/no-image.png'); ?>" style="width: 100px">
									</td>
									<td><?php echo strtoupper($item->name); ?></td>
									<td><?php echo $item->price; ?></td>
									<td><?php echo $item->category; ?></td>
									<td><?php echo $item->barcode != 'null' ? $item->barcode : ''; ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($item->datetime)); ?></td>
									<td>
										<a href="<?php echo base_url('index.php/item/form/' . $item->id); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
											<i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
										</a>
									</td>
									<td>
										<a href="<?php echo base_url('index.php/item/notice/' . $item->id); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
											<i class="fa fa-trash fa-lg" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
								<?php $count++; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
					<!-- End of table -->
				</div>
				<!-- End of content -->
			</div>
			<!-- End of danger -->
		</div>
		<!-- End of col-md-6 -->
	</div>
	<!-- End of row -->
</section>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.table').DataTable();
	});

	// Detroy modal
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
</script>