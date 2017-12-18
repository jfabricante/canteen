<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<style type="text/css">
	table.dataTable {
		font-size: 90%;
	}
</style>
<!-- Items block -->
<section class="content users-purchased">
	<div class="row">
		<div class="col-md-6">
			<!-- Date range -->
			<div class="box box-danger">
				<!-- box-body -->
				<div class="box-body">
					<!-- form -->
					<form action="<?php echo base_url('index.php/user/purchased_items'); ?>" method="post">
						<di class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo isset($dates['from']) ? $dates['from'] : date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo isset($dates['to']) ? $dates['to'] : date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<input type="submit" class="form-control btn btn-danger btn-flat" value="Filter Dates">
								</div>
							</div>
						</di>
						
					</form>
					<!-- ./form -->
				</div>
				<!-- ./box-body -->
			</div>
			<!-- ./Date range -->
		</div>

		<!-- Meal allowance -->
		<div class="col-md-3">
			<div class="box box-danger">
				<div class="box-body text-center">
					<h5 class="lead"><strong><?php echo $balance->meal_allowance >= 0 ? "Remaining balance: " . number_format($balance->meal_allowance, 2) : "Excess credit: " . number_format(abs($balance->meal_allowance), 2) ?></strong></h5>
				</div>
			</div>
		</div>
		<!-- ./Meal allowance -->
	</div>

	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-9">
			<?php if (count($entities)): ?>
				<!-- Box danger -->
				<div class="box box-danger">
					<!-- Content -->
					<div class="box-body">
						<!-- Item table -->
						<table class="table table-condensed table-striped table-bordered" id="items">
							<thead>
								<tr>
									<th>Trans. ID</th>
									<th>Item</th>
									<th>Quantity</th>
									<th>Unit Price</th>
									<th>Total</th>
									<?php if($this->session->userdata('user_type') == 'administrator' ): ?>
										<th>Employee</th>
									<?php endif; ?>
									<th>Cashier</th>
									<th>Trans. Date</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th colspan="4" class="text-right">Total this page:</th>
									<th></th>
									<th class="text-right">Total Purchased: </th>
									<th></th>
								</tr>
							</tfoot>
							<tbody>
								<?php foreach($entities as $entity): ?>
									<tr>
										<td><?php echo $entity['id']; ?></td>
										<td><?php echo $entity['name']; ?></td>
										<td><?php echo $entity['quantity'] >= 0 ? $entity['quantity'] : '' ?></td>
										<td><?php echo $entity['price'] >= 0 ? number_format($entity['price'], 2) : '' ?></td>
										<td><?php echo $entity['total'] >= 0 ? number_format($entity['total'], 2) : '' ?></td>
										<?php if($this->session->userdata('user_type') == 'administrator' ): ?>
											<td><?php echo ucwords(strtolower($entity['employee'])) ?></td>
										<?php endif; ?>
										<td><?php echo ucwords(strtolower($entity['cashier'])) ?></td>
										<td><?php echo date('M d, Y h:i A', strtotime($entity['datetime'])) ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<!-- End of table -->
					</div>
					<!-- End of content -->
				</div>
				<!-- End of danger -->
			<?php endif; ?>
		</div>
		<!-- End of col-md-6 -->
	</div>
	<!-- End of row -->
</section>
<script type="text/javascript" src="<?php echo base_url('resources/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.datepicker').datepicker();

		$('#from').on('change', function() {
			$('#to').val($(this).val());
		});

		$('.table').DataTable({
			// Define disabled column
			"columnDefs": [{
				"searchable": false, "targets": [1, 2, 3, 4]
			}],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"footerCallback": function(row, data, start, end, display) {
				var api = this.api(), data;

				// Calculate over all result based on the result
				total = api.column(4)
						.data()
						.reduce((a, b) => {
							// Convert to string and remove the comma
							a = a.toString().replace(/\,/g,'')
							b = b.toString().replace(/\,/g,'')

							return Number(a) + Number(b)
								
						}, 0);

				// Calculate result per page
				totalPerPage = api.column(4, {page: 'current'})
						.data()
						.reduce((a, b) => {
							// Convert to string and remove the comma
							a = a.toString().replace(/\,/g,'')
							b = b.toString().replace(/\,/g,'')

							return Number(a) + Number(b)
								
						}, 0);

				totalPerPage = totalPerPage.toLocaleString().indexOf('.') > -1 ? totalPerPage.toLocaleString() : totalPerPage.toLocaleString() + '.00';
				total = total.toLocaleString().indexOf('.') > -1 ? total.toLocaleString() :  total.toLocaleString() + '.00';

				$(api.column(4).footer()).html(totalPerPage);
				$(api.column(6).footer()).html(total);
			}
		});
	});
</script>