<!-- Items block -->
<section class="content categories">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-5">
			<!-- Box danger -->
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-header with-border">
					<a href="<?php echo base_url('index.php/item/item_form') ?>">
						<button class="btn btn-flat btn-success pull-right">Add Item <i class="fa fw fa-plus" aria-hidden="true"></i></button>
					</a>
				</div>

				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Date Time</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>

						<tbody>
							<?php $count = 1; ?>
							<?php foreach ($categories as $category): ?>
								<tr>
									<td><?php echo $count; ?></td>
									<td><?php echo $category->name; ?></td>
									<td><?php echo $category->datetime; ?></td>
									<td>
										<a href="#">
											<i class="fa fa-pencil" aria-hidden="true"></i>
										</a>
									</td>
									<td>
										<a href="#">
											<i class="fa fa-trash" aria-hidden="true"></i>
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
<script type="text/javascript">
	$(document).ready(function() {
		$('.table').DataTable();
	});
</script>