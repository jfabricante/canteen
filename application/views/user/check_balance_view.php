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
					<form action="<?php echo base_url('index.php/user/check_calculated_balance'); ?>" method="post">
						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">Date and Time</span>
										<input type="text" class="form-control" id="date_time" name="date_time" value="<?php echo isset($params) ? $params : '' ?>" placeholder="yyyy-mm-dd HH:mm:ss" required>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<input type="submit" class="form-control btn btn-danger btn-flat" value="Validate">
								</div>
							</div>
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
		<div class="col-md-12">
			<?php if (count($entities)): ?>
				<!-- Box danger -->
				<div class="box box-danger">
					<!-- Content -->
					<div class="box-body">
						<!-- Item table -->
						<table class="table table-condensed table-striped table-bordered" id="balance_tbl">
							<thead>
								<tr>
									<th>Employee No</th>
									<th>Name</th>
									<th>Old Balance</th>
									<th>Amount Loaded</th>
									<th>Total Purchases</th>
									<th>Current Meal Allowance</th>
									<th>Calculated Balance</th>
									<th>Difference</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($entities as $entity): ?>
									<tr>
										<td><?php echo $entity['employee_no'] ?></td>
										<td><?php echo $entity['name'] ?></td>
										<td><?php echo $entity['old_balance'] ?></td>
										<td><?php echo $entity['last_meal_credit'] ?></td>
										<td><?php echo $entity['total_purchases'] ?></td>
										<td><?php echo $entity['current_meal_allowance'] ?></td>
										<td><?php echo $entity['calculated_balance'] ?></td>
										<td><?php echo $entity['calculated_balance'] - $entity['current_meal_allowance'] ?></td>
										<td><?php echo $entity['current_meal_allowance'] == $entity['calculated_balance'] ? 1 : 0 ?></td>
									</tr>
								<?php endforeach ?>
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

		$('.table').DataTable();
	});
</script>