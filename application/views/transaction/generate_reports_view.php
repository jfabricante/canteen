<link rel="stylesheet" type="text/css" href="<?php echo base_url('resources/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
<!-- page content -->
<section class="content">
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-10">
			<?php echo $this->session->flashdata('message'); ?>
			<div class="box box-danger">
				<!-- box-header -->
				<div class="box-header with-border">
					<!-- form -->
					<form action="<?php echo base_url('index.php/transaction/generate_billing_report'); ?>" method="post">
						<!-- row -->
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">From</span>
										<input type="text" class="form-control datepicker" id="from" name="from" value="<?php echo isset($params['from']) ? $params['from'] :  date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<div class="input-group date">
										<span class="input-group-addon">To</span>
										<input type="text" class="form-control datepicker" id="to" name="to" value="<?php echo isset($params['to']) ? $params['to'] : date('m/d/Y') ?>" required>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-info btn-flat btn-block" name="filter_date">Filter Date</button>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-info btn-warning btn-flat btn-block" name="create_invoice">Create Invoice</button>
								</div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<!-- pdf report -->
									<button type="submit" class="btn btn-danger btn-flat" name="pdf_report">
										<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
									</button>
									<!-- ./pdf report -->

									<!-- excel report -->
									<button type="submit" class="btn btn-success btn-flat" name="excel_report">
										<i class="fa fa-file-excel-o" aria-hidden="true"></i>
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