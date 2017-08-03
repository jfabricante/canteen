<!-- Items block -->
<section class="content users">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-8">
			<!-- Box danger -->
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-header with-border">
					<a href="<?php echo base_url('index.php/item/form') ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
						<button class="btn btn-flat btn-success pull-right">Add Item <i class="fa fw fa-plus" aria-hidden="true"></i></button>
					</a>
				</div>

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
								<th>Date Time</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>

						<tbody>
							<?php $count = 1; ?>
							<?php foreach($users as $user): ?>
								<tr>
									<td><?php echo $count ?></td>
									<td><?php echo $user->username; ?></td>
									<td><?php echo $user->emp_no; ?></td>
									<td><?php echo $user->fullname; ?></td>
									<td><?php echo ucfirst($user->user_type); ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($user->datetime)); ?></td>
									<td>
										<a href="<?php echo base_url('index.php/user/form/' . $user->id); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
											<i class="fa fa-pencil" aria-hidden="true"></i>
										</a>
									</td>
									<td>
										<a href="<?php echo base_url('index.php/user/notice/' . $user->id); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
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