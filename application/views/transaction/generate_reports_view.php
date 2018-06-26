<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<style type="text/css">
	table.dataTable {
		font-size: 90%;
	}
</style>
<!-- page content -->
<section class="content">
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-10">
			<?php echo $this->session->flashdata('message'); ?>
			<div class="box box-danger">
				<!-- box-header -->
				<div class="box-header with-border">
					<!-- form -->
					<form action="<?php echo base_url('index.php/transaction/generate_billing_report'); ?>" method="post">
						<!-- row -->
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo isset($params['from']) ? $params['from'] :  date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo isset($params['to']) ? $params['to'] : date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-info btn-flat btn-block" name="filter_date">Filter Date</button>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-info btn-warning btn-flat btn-block" name="create_invoice">Create Invoice</button>
								</div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<!-- pdf report -->
									<button type="submit" class="btn btn-danger btn-flat" name="pdf_report">
										<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
									</button>
									<!-- ./pdf report -->

									<!-- excel report -->
									<button type="submit" class="btn btn-success btn-flat" name="excel_report">
										<i class="fa fa-file-excel-o" aria-hidden="true"></i>
									</button>
									<!-- ./excel report -->
								</div>
							</div>
						</div>
						<!-- ./row -->
					</form>
					<!-- ./form -->
				</div>
				<!-- /.box-header -->

				<!-- box-body -->
				<div class="box-body">
					<?php if (count($entities) > 0) : ?>
						<table class="table table-condensed table-striped table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Trans ID</th>
									<th>Employee</th>
									<th>Credit Used</th>
									<th>Cash Used</th>
									<th>Date</th>
									<th>Cashier</th>
									<th>Invoice No.</th>
									<th>Status</th>
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
								<?php $counter = 1; ?>
								<?php foreach($entities as $entity) : ?>
									<tr>
										<td><?php echo $counter ?></td>
										<td><?php echo $entity['id'] ?></td>
										<td><?php echo ucwords(strtolower($entity['employee'])); ?></td>
										<td><?php echo $entity['credit_used'] ? number_format($entity['credit_used'], 2) : ''; ?></td>
										<td><?php echo $entity['cash'] ? number_format($entity['cash'], 2) : ''; ?></td>
										<td><?php echo date('m/d/Y h:i A', strtotime($entity['datetime'])); ?></td>
										<td><?php echo ucwords(strtolower($entity['cashier'])); ?></td>
										<td><?php echo $entity['invoice_id'] ? sprintf('%06d', $entity['invoice_id']) : '' ?></td>
										<td><?php echo $entity['status'] ?></td>
									</tr>
									<?php $counter++; ?>
								<?php endforeach ?>
							</tbody>
						</table>
					<?php endif ?>
				</div>
				<!-- ./box-body -->
			</div>
		</div>
	</div>
</section>
<!-- /page content -->
<script type="text/javascript" src="<?php echo base_url('resources/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script type="text/javascript">
	$('.datepicker').datepicker();

	$('.table').DataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"footerCallback": function(row, data, start, end, display) {
			var api = this.api(), data;

			// Calculate over all result based on the result
			var grandTotal = api.column(3)
					.data()
					.reduce((a, b) => {
						// Convert to string and remove the comma
						a = a.toString().replace(/\,/g,'')
						b = b.toString().replace(/\,/g,'')

						return Number(a) + Number(b)
							
					}, 0);
		
			grandTotal = grandTotal.toLocaleString().indexOf('.') > -1 ? grandTotal.toLocaleString() :  grandTotal.toLocaleString() + '.00';

			$(api.column(3).footer()).html(grandTotal);
		}
	});

	$('#from').on('change', function() {
		$('#to').val($(this).val());
	});
</script>