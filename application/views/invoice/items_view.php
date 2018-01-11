<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/select/css/bootstrap-select.min.css'); ?>">

<style type="text/css">
	.search-invoice {
		margin-top: 25px;
	}

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
				<!-- Content -->
				<div class="box-header with-border">
					<form action="<?php echo base_url('index.php/transaction/invoice_item'); ?>" method="post">
						<!-- row -->
						<div class="row">
							<!-- col-md-4 -->
							<div class="col-md-3">
								<div class="form-group">
									<label for="invoice_no">Invoice No.</label>
									<select name="invoice_no" id="invoice_no" class="form-control selectpicker" data-live-search="true" required>
										<option></option>
										<?php foreach($rows as $row): ?>
											<option value="<?php echo $row['id']; ?>" <?php echo isset($params['invoice_no']) && ($row['id'] == $params['invoice_no']) ? 'selected' : '' ?>>
												<?php echo sprintf('%06d', $row['id']); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<!-- ./col-md-4 -->

							<!-- col-md-2 -->
							<div class="col-md-2">
								<div class="form-group">
									<button class="btn btn-flat btn-danger search-invoice btn-block" name="search" type="submit">Search</button>
								</div>
							</div>
							<!-- ./col-md-2 -->

							<!-- col-md-2 -->
							<div class="col-md-2">
								<div class="form-group">
									<button class="btn btn-flat btn-info search-invoice btn-block" name="invoice_report" type="submit">Create Report</button>
								</div>
							</div>
							<!-- ./col-md-2 -->

						</div>
						<!-- ./row -->
					</form>
				</div>

				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Trans. ID</th>
								<th>Credit Used</th>
								<th>Cash Used</th>
								<th>Fullname</th>
								<th>Trans. Date</th>
								<th>Status</th>
							</tr>
						</thead>

						<tbody>
							<?php if (count($entities)): ?>
								<?php $count = 1; ?>
								<?php foreach($entities as $entity): ?>
									<tr>
										<td><?php echo $count ?></td>
										<td><?php echo $entity['trans_id'] ?></td>
										<td><?php echo $entity['credit_used'] ?></td>
										<td><?php echo $entity['cash'] ?></td>
										<td><?php echo ucwords(strtolower($entity['fullname'])) ?></td>
										<td><?php echo date('m/d/Y h:i A', strtotime($entity['trans_date'])) ?></td>
										<td><?php echo $entity['status'] ?></td>
									</tr>
									<?php $count++; ?>
								<?php endforeach; ?>
							<?php endif ?>
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
<script type="text/javascript" src="<?php echo base_url('resources/plugins/select/js/bootstrap-select.min.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.table').DataTable();
	});

	// Detroy modal
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
</script>