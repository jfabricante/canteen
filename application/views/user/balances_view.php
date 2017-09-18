<!-- Items block -->
<section class="content users-balance">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-8">
			<!-- Box danger -->
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>Employee no.</th>
								<th>Fullname</th>
								<th>Meal Allowance</th>
								<th>Excess Credit</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach($entities as $entity): ?>
								<tr>
									<td><?php echo $entity->emp_no; ?></td>
									<td><?php echo $entity->fullname; ?></td>
									<td><?php echo $entity->meal_allowance >= 0 ? number_format($entity->meal_allowance, 2) : '' ?></td>
									<td><?php echo $entity->meal_allowance < 0 ? number_format(abs($entity->meal_allowance), 2) : '' ?></td>
								</tr>
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