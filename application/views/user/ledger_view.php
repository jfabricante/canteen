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
					<form action="<?php echo base_url('index.php/user/ledger'); ?>" method="post">
						<div class="row">
							
							<?php if (in_array($this->session->userdata('user_type'), array('administrator'))): ?>
								<div class="col-md-4">
									<div class="form-group">
										<label for="employee_no">Fullname</label>
										<select name="employee_no" id="employee_no" class="form-control selectpicker" data-live-search="true" required>
											<option></option>
											<?php foreach($rows as $row): ?>
												<option value="<?php echo $row['employee_no']; ?>" <?php echo isset($params['employee_no']) && ($row['employee_no'] == $params['employee_no']) ? 'selected' : '' ?>>
													<?php echo $row['fullname']; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							<?php else: ?>
								<div class="col-md-4 hidden">
									<div class="form-group">
										<label for="employee_no">Fullname</label>
										<select name="employee_no" id="employee_no" class="form-control selectpicker" data-live-search="true" required>
											<option></option>
											<?php foreach($rows as $row): ?>
												<option value="<?php echo $row['employee_no']; ?>" <?php echo ($row['employee_no'] == $this->session->userdata('employee_no')) ? 'selected' : '' ?>>
													<?php echo $row['fullname']; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							<?php endif ?>

							<div class="<?php echo $this->session->userdata('user_type') == 'employee' ? 'col-md-3' : 'col-md-2' ?>">
								<br />
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo isset($params['from']) ? $params['from'] : date('m/d/Y') ?>" required />
									</div>
								</div>
							</div>

							<div class="<?php echo $this->session->userdata('user_type') == 'employee' ? 'col-md-3' : 'col-md-2' ?>">
								<br />
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo isset($params['to']) ? $params['to'] : date('m/d/Y') ?>" required />
									</div>
								</div>
							</div>

							<div class="<?php echo $this->session->userdata('user_type') == 'employee' ? 'col-md-3' : 'col-md-2' ?>">
								<br />
								<div class="form-group">
									<input type="submit" class="form-control btn btn-danger btn-flat" name="filter_dates" value="Filter Dates" />
								</div>
							</div>

							<?php if(in_array($this->session->userdata('user_type'), array('administrator'))): ?>
								<div class="col-md-2">
									<br />
									<div class="form-group">
										<input type="submit" class="form-control btn btn-success btn-flat" name="excel_report" value="Create Report" />
									</div>
								</div>
							<?php endif ?>
						</div>
						
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
									<th>Debit</th>
									<th>Credit</th>
									<th>Remarks</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
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
										<td><?php echo $entity['trans_date']; ?></td>
										<td><?php echo $entity['trans_id']; ?></td>
										<td><?php echo $entity['debit'] ?></td>
										<td><?php echo $entity['credit'] ?></td>
										<td><?php echo $entity['remarks'] ?></td>
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
				var debitTotal = api.column(2)
						.data()
						.reduce((a, b) => {
							// Convert to string and remove the comma
							a = a.toString().replace(/\,/g,'')
							b = b.toString().replace(/\,/g,'')

							return Number(a) + Number(b)
								
						}, 0);


				// Calculate over all result based on the result
				var creditTotal = api.column(3)
						.data()
						.reduce((a, b) => {
							// Convert to string and remove the comma
							a = a.toString().replace(/\,/g,'')
							b = b.toString().replace(/\,/g,'')

							return Number(a) + Number(b)
								
						}, 0);

				debitTotal = debitTotal.toLocaleString().indexOf('.') > -1 ? debitTotal.toLocaleString() : debitTotal.toLocaleString() + '.00';
				creditTotal = creditTotal.toLocaleString().indexOf('.') > -1 ? creditTotal.toLocaleString() :  creditTotal.toLocaleString() + '.00';

				$(api.column(2).footer()).html(debitTotal);
				$(api.column(3).footer()).html(creditTotal);
			}
		});
	});
</script>