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
					<!-- row -->
					<div class="row">
						<!-- form -->
						<form action="<?php echo base_url('index.php/transaction/revoke'); ?>" method="post">
							<!-- col-md-3 -->
							<div class="col-md-3">
								<div class="form-group">
									<label for="trans_id">Transaction ID</label>
									<input type="text" name="trans_id" class="form-control" value="<?php echo isset($params['trans_id']) ? $params['trans_id'] : '' ?>">
								</div>
							</div>
							<!-- ./col-md-3 -->

							<!-- col-md-2 -->
							<div class="col-md-2">
								<div class="form-group">
									<button class="btn btn-flat btn-danger search-invoice btn-block" type="submit">Search</button>
								</div>
							</div>
							<!-- ./col-md-2 -->
						</form>
						<!-- ./form -->

						<!-- col-md-3 -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="credit_used">Credit Used</label>
								<input type="text" name="credit_used" disabled class="form-control" value="<?php echo isset($items[0]['credit_used']) ? $items[0]['credit_used'] : '' ?>">
							</div>
						</div>
						<!-- ./col-md-3 -->

						<!-- col-md-3 -->
						<div class="col-md-2">
							<div class="form-group">
								<label for="cash">Cash Used</label>
								<input type="text" name="cash_used" disabled class="form-control" value="<?php echo isset($items[0]['cash']) ? $items[0]['cash'] : '' ?>">
							</div>
						</div>
						<!-- ./col-md-3 -->

						<!-- form -->
						<form action="<?php echo base_url('index.php/transaction/handle_revoke'); ?>" method="post">
							<!-- col-md-2 -->
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="trans_id" class="form-control hidden" required value="<?php echo isset($params['trans_id']) ? $params['trans_id'] : '' ?>">
									<button class="btn btn-flat btn-danger search-invoice btn-block" type="submit">Perform Revoke</button>
								</div>
							</div>
							<!-- ./col-md-2 -->
						</form>
						<!-- ./form -->
					</div>
					<!-- ./row -->
				</div>

				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered">
						<thead>
							<tr>
								<th>Item</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>

						<tbody>
							<?php if (is_array($items)): ?>
								<?php $count = 1; ?>
								<?php foreach($items as $entity): ?>
									<tr>
										<td><?php echo $entity['name'] ?></td>
										<td><?php echo $entity['price'] ?></td>
										<td><?php echo $entity['quantity'] ?></td>
										<td><?php echo $entity['total'] ?></td>
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