<!-- Items block -->
<section class="content users">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-9">
			<!-- Box danger -->
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Employee no.</th>
								<th>Fullname</th>
								<th>User type</th>
								<th>Edit</th>
							</tr>
						</thead>

						<tbody>
							<?php $count = 1; ?>
							<?php foreach($users as $user): ?>
								<tr>
									<td><?php echo $count ?></td>
									<td><?php echo $user->employee_no; ?></td>
									<td><?php echo $user->employee_no; ?></td>
									<td><?php echo ucwords(strtolower($user->fullname)); ?></td>
									<td><?php echo isset($user->user_type) ? ucfirst($user->user_type) : ''; ?></td>
									<td>
										<a href="<?php echo base_url('index.php/user/form/' . $user->id); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
											<i class="fa fa-pencil" aria-hidden="true"></i>
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