<!-- Items block -->
<section class="content users-purchased">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-8">
			<!-- Box danger -->
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-body">
					<!-- Item table -->
					<table class="table table-condensed table-striped table-bordered" id="items">
						<thead>
							<tr>
								<th>Trans. ID</th>
								<th>Item</th>
								<th>Quantity</th>
								<th>Unit Price</th>
								<th>Total</th>
								<th>Cashier</th>
								<th>Trans. Date</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="4" style="text-align:right">Total:</th>
								<th colspan="2"></th>
							</tr>
						</tfoot>
						<tbody>
							<?php foreach($entities as $entity): ?>
								<tr>
									<td><?php echo $entity['id']; ?></td>
									<td><?php echo $entity['name']; ?></td>
									<td><?php echo $entity['quantity'] >= 0 ? $entity['quantity'] : '' ?></td>
									<td><?php echo $entity['price'] >= 0 ? number_format($entity['price'], 2) : '' ?></td>
									<td><?php echo $entity['total'] >= 0 ? number_format($entity['total'], 2) : '' ?></td>
									<td><?php echo ucwords(strtolower($entity['cashier'])) ?></td>
									<td><?php echo date('M d, Y h:i A', strtotime($entity['datetime'])) ?></td>
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
		$('.table').DataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"footerCallback": function(row, data, start, end, display) {
				var api = this.api(), data;

				// Calculate over all result based on the result
				total = api.column(4)
						.data()
						.reduce((a, b) => {
							return Number(a) + Number(b)
								
						}, 0);

				totalPerPage = api.column(4, {page: 'current'})
						.data()
						.reduce((a, b) => {
							return Number(a) + Number(b)
								
						}, 0);

				$(api.column(4).footer()).html(totalPerPage.toFixed(2));
			}
		});
	});
</script>