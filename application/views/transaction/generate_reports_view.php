<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<!-- page content -->
<section class="content">
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-6">
			<?php echo $this->session->flashdata('message'); ?>
			<div class="box box-danger">
				<!-- box-header -->
				<div class="box-header"></div>
				<!-- /.box-header -->

				<!-- box-body -->
				<div class="box-body">
					<!-- form -->
					<form action="<?php echo base_url('index.php/transaction/handle_billing_report'); ?>" method="post">
						<!-- row -->
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<!-- pdf report -->
									<button type="submit" class="btn btn-danger btn-flat" name="pdf_report">
										<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
									</button>
									<!-- ./pdf report -->

									<!-- excel report -->
									<button type="submit" class="btn btn-success btn-flat" name="excel_report">
										<i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i>
									</button>
									<!-- ./excel report -->
								</div>
							</div>
						</div>
						<!-- ./row -->
					</form>
					<!-- ./form -->
				</div>
				<!-- ./box-body -->
			</div>
		</div>
	</div>
</section>
<!-- /page content -->
<script type="text/javascript" src="<?php echo base_url('resources/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script type="text/javascript">
	$('.datepicker').datepicker();

	$('#from').on('change', function() {
		$('#to').val($(this).val());
	});
</script>