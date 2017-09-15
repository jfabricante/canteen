<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Canteen System Billing Reports</title>

	<!-- Bootstrap core CSS -->
	<link href="<?php echo base_url('resources/templates/bootstrap-3.3.7/css/bootstrap.min.css');?>" rel="stylesheet" >
	<!-- Admin LTE core CSS -->
	<link href="<?php echo base_url('resources/templates/AdminLTE-2.3.5/dist/css/AdminLTE.min.css');?>" rel="stylesheet" >

	<style type="text/css">
		table.table.table-condensed.table-striped.table-bordered th,
		table.table.table-condensed.table-striped.table-bordered td {
			font-size: 11px;
		}

		.total {
			margin: 20px 0 40px;
		}

		.signature-item {
			height: 60px;
			width: 250px;
			border-bottom: .5px solid black;
			margin-right: 100px;
		}

	</style>
</head>
<body>
	<!-- box -->
	<div class="box box-danger">
		<!-- box-header -->
		<div class="box-header with-border">
			<h4 class="box-title"><?php echo isset($title) ? $title : '' ?></h4>
		</div>
		<!-- /box-header -->

		<!-- box-body -->
		<div class="box-body">
			<!-- table -->
			<table class="table table-condensed table-striped table-bordered">
				<thead>
					<tr>
						<th>Trans ID</th>
						<th>Employee</th>
						<th>Credit Used</th>
						<th>Cash Used</th>
						<th>Date</th>
						<th>Cashier</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($entities as $entity): ?>
						<tr>
							<td><?php echo $entity['id'] ?></td>
							<td><?php echo $entity['employee']; ?></td>
							<td><?php echo $entity['credit_used']; ?></td>
							<td><?php echo $entity['cash']; ?></td>
							<td><?php echo date('m/d/Y h:i A', strtotime($entity['datetime'])); ?></td>
							<td><?php echo $entity['cashier']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<!-- /table -->

			<div class="total">
				<h4>Total Bill: <span class="total-bill"><?php echo $total_bill ?></span></h4>
			</div>

			<div class="signature">
				<table>
					<tr>
						<td>
							<div class="signature-item">
								<p>Prepared by:</p>
							</div>
						</td>

						<td>
							<div class="signature-item">
								<p>Checked by:</p>
							</div>
						</td>
					</tr>
				</table>
			</div>

		</div>
		<!-- /box-body -->
	</div>
	<!-- /box -->
</body>
</html>