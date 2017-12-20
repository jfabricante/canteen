<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css'); ?>">

<style type="text/css">
	.ledger .box-body .form-group label {
		margin-bottom: 0;
	}

	.ledger table.dataTable {
		font-size: 90%;
	}
</style>
<!-- Items block -->
<section class="content ledger">
	<div class="row">
		<div class="col-md-9">
			<!-- Date range -->
			<div class="box box-danger">
				<!-- box-body -->
				<div class="box-body">
					<!-- form -->
					<form action="<?php echo base_url('index.php/user/cashier_sales'); ?>" method="post">
						<!-- row -->
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="emp_id">Fullname</label>
									<select name="emp_id" id="emp_id" class="form-control selectpicker" data-live-search="true">
										<option></option>
										<?php foreach($rows as $row): ?>
											<option value="<?php echo $row['id']; ?>" <?php echo isset($params['emp_id']) && ($row['id'] == $params['emp_id']) ? 'selected' : '' ?>>
												<?php echo $row['fullname']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<br />
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo isset($params['from']) ? $params['from'] : date('m/d/Y') ?>" required />
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<br />
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo isset($params['to']) ? $params['to'] : date('m/d/Y') ?>" required />
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<br />
								<div class="form-group">
									<input type="submit" class="form-control btn btn-danger btn-flat" value="Filter Dates" />
								</div>
							</div>
						</div>
						<!-- ./row -->
					</form>
					<!-- ./form -->
				</div>
				<!-- ./box-body -->
			</div>
			<!-- ./Date range -->
		</div>

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
									<th>Trans. Date</th>
									<th>Trans. ID</th>
									<th>Item</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
							<tbody>
								<?php foreach($entities as $entity): ?>
									<tr>
										<td><?php echo date('m/d/Y', strtotime($entity['datetime'])); ?></td>
										<td><?php echo $entity['id']; ?></td>
										<td><?php echo $entity['name'] ?></td>
										<td><?php echo number_format($entity['price'], 2) ?></td>
										<td><?php echo $entity['quantity'] ?></td>
										<td><?php echo number_format($entity['total'], 2) ?></td>
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
<script type="text/javascript" src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js'); ?>"></script>
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
				var grandTotal = api.column(5)
						.data()
						.reduce((a, b) => {
							// Convert to string and remove the comma
							a = a.toString().replace(/\,/g,'')
							b = b.toString().replace(/\,/g,'')

							return Number(a) + Number(b)
								
						}, 0);
			
				grandTotal = grandTotal.toLocaleString().indexOf('.') > -1 ? grandTotal.toLocaleString() :  grandTotal.toLocaleString() + '.00';

				$(api.column(5).footer()).html(grandTotal);
			}
		});
	});
</script>