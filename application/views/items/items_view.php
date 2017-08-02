<!-- Items block -->
<section class="content items">
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
								<th>Price</th>
								<th>Category</th>
								<th>Date Time</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>1</td>
								<td>Coke</td>
								<td>10.00</td>
								<td>Drinks</td>
								<td>08/2/2017</td>
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