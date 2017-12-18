<style type="text/css">
	table.dataTable {
		font-size: 90%;
	}
</style>
<!-- Items block -->
<section class="content items">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-9">
			<!-- Box danger -->
			<?php echo $this->session->flashdata('message'); ?>
			
			<div class="box box-danger">

				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Invoice No.</th>
								<th>Status</th>
								<th>Created By</th>
								<th>Date Created</th>
								<th>Date Updated</th>
								<th>Updated By</th>
								<th>Update</th>
							</tr>
						</thead>

						<tbody>
							<?php $count = 1; ?>
							<?php foreach($entities as $entity): ?>
								<tr>
									<td><?php echo $count ?></td>
									<td><?php echo sprintf('%06d', $entity['id']); ?></td>
									<td><?php echo $entity['status']; ?></td>
									<td><?php echo ucwords(strtolower($entity['created_by'])); ?></td>
									<td><?php echo date('m/d/Y h:i A', strtotime($entity['date_created'])); ?></td>
									<td><?php echo $entity['updated_by'] ? ucwords(strtolower($entity['updated_by'])) : '' ?></td>
									<td><?php echo $entity['last_update'] ? date('m/d/Y h:i A', strtotime($entity['last_update'])) : '' ?></td>

									<td>
										<a href="<?php echo base_url('index.php/transaction/invoice_form/' . $entity['id']); ?>" data-toggle="modal" data-target=".bs-example-modal-sm">
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