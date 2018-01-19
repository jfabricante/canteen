<!-- Items block -->
<section class="content items">
	<!-- row -->
	<div class="row">
		<!-- col-md-6 -->
		<div class="col-md-10">
			<!-- Box danger -->
			<?php echo $this->session->flashdata('message'); ?>
			
			<div class="box box-danger">
				<!-- Content -->
				<div class="box-header with-border">
					<input type="file" name="fileUpload" id="fileUpload" class="form-control" accept=".xlsx">
				</div>
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
<script src="<?php echo base_url('resources/js/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/cpexcel.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/xlsx.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/jszip.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/xlsx.full.min.js') ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.table').DataTable();
	});

	$('#fileUpload').on('change', excelContent = filePicked)


	function filePicked(oEvent) {
		// Get The File From The Input
		var oFile = oEvent.target.files[0];

		// Create A File Reader HTML5
		var reader = new FileReader();

		var excelObject = [];

		// Ready The Event For When A File Gets Selected
		reader.onload = (e) => {
			var data = e.target.result;
			var wb = XLSX.read(data, {type: 'binary'});

			// Assume that the first sheet has its value
			var sheetName = wb.SheetNames[0]

			console.log(sheetName)

			// Assign the json values to excelObject
			excelObject.push(XLSX.utils.sheet_to_json(wb.Sheets[sheetName]))

			// Convert it to linear form
			excelObject = _.flatten(excelObject);

			console.log(excelObject);

			// $.ajax(url: 'items/ajax_excel_file', data: {excelObject}, type: 'post')


			$.post( "ajax_excel_file", { data: excelObject} );
		};
		// Tell JS To Start Reading The File.. You could delay this if desired
		reader.readAsBinaryString(oFile);
	};

	// Detroy modal
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
</script>