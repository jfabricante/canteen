<section class="content upload">
	<!-- row -->
	<div class="row" id="app">
		<!-- col-md-8 -->
		<div class="col-md-8">
			<!-- box -->
			<div class="box box-danger">
				<!-- header -->
				<div class="box-header">
					<!-- form -->
					<form method="post" enctype="multipart/form-data" v-on:submit.prevent="storeResource" role="form" id="form">
						<div class="row">
							<!-- file-upload -->
							<div class="col-md-6">
								<div class="form-group">
									<label for="file-upload">Upload Spreadsheet</label>
									<input type="file" name="file-upload" v-model="fileUpload" ref="fileUpload" id="file-upload" accept=".xlsx, .xls, .csv">
								</div>
							</div>
							<!-- ./file-upload -->

							<div class="col-md-2">
								<div class="form-group">
									<label> </label>
									<input type="submit" name="update" value="Update" ref="update" class="btn btn-flat btn-danger form-control">
								</div>
							</div>
						</div>
					</form>
					<!-- ./form -->
				</div>
				<!-- ./header -->

				<!-- content -->
				<div class="box-body">
					 <!-- Item table -->
					 <table class="table table-condensed table-striped table-bordered">
					 	<thead>
					 		<tr>
					 			<th>Item Name</th>
					 			<th>Former Concessionaire</th>
					 			<th>Julindz Food Hauz</th>
					 		</tr>
					 	</thead>

					 	<tbody>
					 		<tr v-for="(item, index) in excelObject">
					 			<td>{{ item['Item Name'] }}</td>
					 			<td>{{ item['Former Concessionaire'] }}</td>
					 			<td>{{ item['Julindz Food Hauz'] }}</td>
					 		</tr>
					 	</tbody>
					 </table>
					 <!-- End of table -->
				</div>
				<!-- ./content -->
			</div>
			<!-- ./box -->
		</div>
		<!-- ./col-md-8 -->
	</div>
	<!-- ./row -->
</section>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('resources/plugins/select2/js/select2.js');?>"></script>
<script src="<?php echo base_url('resources/js/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vue/vue.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/cpexcel.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/xlsx.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/jszip.js') ?>"></script>
<script src="<?php echo base_url('resources/js/js-xlsx/xlsx.full.min.js') ?>"></script>

<script type="text/javascript">
	var appUrl = '<?php echo base_url('index.php') ?>';
	var tmUrl  = '<?php echo base_url('resources/images/') ?>';

	var app = new Vue({
		el: '#app',
		data: {
			excelObject: [],
			fileUpload: '',
		},
		watch: {
			excelObject: function() {
				console.log(this.excelObject)
			}
		},
		mounted() {
			const self = this

			$(this.$refs.fileUpload).on('change', this.filePicked)

			$(this.$refs.update).on('click', this.showModal)
		},
		methods: {
			filePicked: function(oEvent) {
				// Get The File From The Input
				var oFile = oEvent.target.files[0];

				// Create A File Reader HTML5
				var reader = new FileReader();

				// Ready The Event For When A File Gets Selected
				reader.onload = (e) => {
					var data = e.target.result;
					var wb = XLSX.read(data, {type: 'binary'});

					// Get the last sheet on the excel file
					var sheetName = wb.SheetNames[0]

					// Reset the element of excel object
					this.excelObject.splice(0, this.excelObject.length)

					// Assign the json values to excelObject
					this.excelObject.push(XLSX.utils.sheet_to_json(wb.Sheets[sheetName]))

					// Convert it to linear form
					this.excelObject = _.flatten(this.excelObject)	
				};

				// Tell JS To Start Reading The File.. You could delay this if desired
				reader.readAsBinaryString(oFile);
			},
			showModal: function() {
				$("#myModal").modal({backdrop: 'static', keyboard: false})

				$('#myModal').on('shown.bs.modal', function() {
					$(this).find('.modal-body').html('<img src="' + tmUrl + 'loading.gif" class="img-responsive"/>')
				});
			},
			storeResource: function() {
				axios({
					url: appUrl + '/item/update_upload_items',
					method: 'post',
					data: this.excelObject,
				})
				.then((response) => {
					console.log(response.data)
					$('#myModal').modal('hide')
				})
				.catch((error) => {
					$('#myModal').modal('hide')

					alert('Invalid file or data');

					console.log(error)
				});
			},
		}
	});
</script>