<!-- Items block -->
<section class="content transaction">
	<!-- App -->
	<div id="app">
		<div class="row">
			<div class="col-md-1 page-thumbnail">
				<img v-if="employee.hasThumbnail" v-bind:src="tmUrl + employee.no + '.JPG'" class="img-responsive">
				<img v-else :src="imgUrl + 'no-image.png'" class="img-responsive">
			</div>

			<div class="col-md-3">
				<h4>Fullname: {{ employee.fullname }}</h4>
			</div>

			<div class="col-md-2">
				<h4>Meal Allowance: {{ employee.allowance >= 0 ? employee.allowance : 0}}</h4>
			</div>

			<div class="col-md-2">
				<h4>Remaining Credit: {{ remaining_credit <= -1 ? Math.abs(remaining_credit) : 0}}</h4>
			</div>
		</div>
		<!-- row -->
		<div class="row">
			<!-- col-md-6 -->
			<div class="col-md-4">
				<!-- Box danger -->
				<?php echo $this->session->flashdata('message');  ?>
				
				<div class="box box-danger cart-items">
					<!-- Content -->
					<div class="box-header with-border">
						<h3 class="box-title">Items</h3>
						<button class="btn btn-flat btn-danger pull-right" v-on:click="clearItems">Clear Items</button>
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
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, index) in cart">
									<td>{{ _.toUpper(item.name) }}</td>
									<td>{{ item.price }}</td>
									<td>{{ item.quantity }}</td>
									<td>{{ item.total }}</td>
									<td>
										<a href="#" v-on:click="editItem(index)">
											<i class="fa fa-pencil fa-lg" aria-hidden="true"></i>	
										</a>
									</td>
									<td>
										<a href="#" v-on:click="deleteItem(index)">
											<i class="fa fa-trash fa-lg" aria-hidden="true"></i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
						<!-- End of table -->
					</div>

					<div class="box-footer text-left">
						<div class="row">
							<div class="total-label">
								<div class="col-md-5 col-md-offset-3">
									<strong>Total Purchase: </strong>
								</div>
								<div class="col-md-3">
									<strong>&#8369;  {{ totalPurchase }}</strong>
								</div>
							</div>
						</div>
					</div>
					<!-- End of content -->
				</div>
				<!-- End of danger -->
			</div>
			<!-- End of col-md-4 -->

			<!-- col-md-8 -->
			<div class="col-md-8">
				<!-- row -->
				<div class="row">
					<!-- col-md-4 -->
					<div class="col-md-4">
						<!-- box-danger -->
						<div class="box box-danger cart-form">
							<div class="box-body">
								<!-- Row -->
								<div class="row">
									<!-- col-md-12 -->
									<div class="col-md-12">
										<!-- Form -->
										<form id="itemForm" class="form-horizonal" v-on:submit.prevent="updateItem" method="post" autocomplete="off">
											<div class="form-group hidden">
												<input type="number" class="form-control" id="id" name="id" v-model="newItems.id">
											</div>

											<div class="form-group">
												<div class="row">
													<label for="item" class="col-sm-4 control-label">Item</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" id="item" name="item" v-model="_.toUpper(newItems.name)" v-bind:class="{'input': true, 'is-danger': errors.has('item') }" readonly v-validate="'required'">
													</div>

													<div class="col-sm-12">
														<i v-show="errors.has('item')" class="fa fa-warning text-danger"></i>
														<span v-show="errors.has('item')" class="text-danger">{{ errors.first('item') }}</span>
													</div>
												</div>
											</div>

											<div class="form-group hidden">
												<label for="price">Price</label>
												<input type="text" class="form-control" id="price" name="price" v-model="newItems.price">
											</div>

											<div class="form-group">
												<div class="row">
													<label for="quantity" class="col-sm-4 control-label">Quantity</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" id="quantity" ref="quantity" name="quantity" v-model="newItems.quantity" v-validate="'required|min_value:1|max:6'" v-bind:class="{'input': true, 'is-danger': errors.has('quantity') }">
													</div>
												</div>

												<div class="col-sm-12">
													<i v-show="errors.has('quantity')" class="fa fa-warning text-danger"></i>
													<span v-show="errors.has('quantity')" class="text-danger">{{ errors.first('quantity') }}</span>
												</div>

											</div>

											<div class="form-group hidden">
												<label for="total">Total Amount</label>
												<input type="text" class="form-control" id="total" name="total" v-model="newItems.total" v-bind:value="Number(newItems.quantity) * newItems.price">
											</div>
										
											<div class="form-group pull-right">
												<input type="submit" value="Update" class="btn btn-flat btn-danger">
											</div>
										</form>
										<!-- End Form -->		
									</div>
									<!-- /col-md-12 -->

									<!-- col-md-12 -->
									<div class="col-md-12">
										<!-- Calculator -->
										<div class="calculator text-center">
											<ul class="list-group">
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(7)">7</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(8)">8</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(9)">9</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(4)">4</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(5)">5</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(6)">6</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(1)">1</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(2)">2</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(3)">3</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick(0)">0</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="btnClick('.')">.</button>
												</li>
												<li class="col-md-4 list-group-item">
													<button class="btn btn-flat btn-block" v-on:click="removeChar"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
												</li>
											</ul>
										</div>
										<!-- /Calculator -->
									</div>
									<!-- /col-md-12 -->

									<!-- col-md-12 -->
									<div class="col-md-12">
										<div class="checkout text-center">
											<button class="btn btn-flat btn-danger btn-block" v-on:click="showCheckoutPane">
												<i class="fa fa-shopping-cart fa-4x" aria-hidden="true"></i>
											</button>
										</div>
									</div>
									<!-- /col-md-12 -->

								</div>
								<!-- /Row -->
							</div>
							<!-- /box-body -->
						</div>
						<!-- /box-danger -->
					</div>
					<!-- /col-md-4 -->

					<!-- col-md-8 -->
					<div class="col-md-8">
						<!-- Danger box -->
						<div class="box box-danger">
							
							<div class="box-header">
								<div class="box-header with-border">
									<h3 class="box-title">Categories</h3>
								</div>
							</div>

							<!-- Box body -->
							<div class="box-body">
								<!-- Custom tabs -->
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li v-for="(category, index) in categories" class="" v-bind:class="{'active': index === 0}">
											<a v-bind:href="'#' + index" data-toggle="tab" aria-expanded="false">{{ category.name }}</a>
										</li>
									</ul>

									<div class="tab-content">
										<!-- Tab pane -->
										<div class="tab-pane" v-for="(category_items, index) in featuredItems" v-bind:id="index" v-bind:class="{'active': index === 0}">
											<!-- Row -->
											<div class="row">
												<ul class="list-group">
													<li  v-for="item in category_items" class="col-md-2 list-group-item" v-on:click="addItem(item)">
														<img v-bind:src="imgUrl + item.thumbnail" v-if="item.thumbnail !== null" class="img-responsive">
														<img v-bind:src="imgUrl + 'no-image.png'" v-else class="img-responsive">
														<p class="text-center">{{ _.toUpper(item.name) }}</p>
														<p class="text-center">&#8369; {{ item.price }}</p>
													</li>
												</ul>
											</div>
											<!-- End of row -->
										</div>
										<!-- Tab pane -->

										<!-- Checkout pane -->
										<div class="tab-pane" id="checkout-pane">
											<!-- form -->
											<form class="form-horizonal" autocomplete="off" id="transaction-form" v-on:submit.prevent="performTransaction">
												<fieldset>
													<legend>Team Member Details</legend>
													<!-- Row -->
													<div class="row">
														<!-- TM details -->
														<div class="col-md-9">
															<div class="form-group">
																<label class="col-sm-5 control-label" for="employee_no">Employee No.</label>
																<div class="col-sm-7">
																	<input type="password" name="employee_no" id="employee_no" class="form-control" v-validate="'max:6'" v-model="employee.no" v-on:click="manageState(employee)">
																	<i v-show="errors.has('employee_no')" class="fa fa-warning text-danger"></i>
																	<span v-show="errors.has('employee_no')" class="text-danger">{{ errors.first('employee_no') }}</span>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-5 control-label" for="employee_name" >Employee Name</label>
																<div class="col-sm-7">
																	<input type="text" name="employee_name" id="employee_name" class="form-control" readonly v-model="employee.fullname">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-5 control-label" for="meal_allowance">Meal Allowance</label>
																<div class="col-sm-7">
																	<input type="text" name="meal_allowanace" id="meal_allowance" class="form-control" readonly v-model="employee.allowance">
																</div>
															</div>
														</div>
														<!-- /TM details -->

														<!-- TM thumbnail -->
														<div class="col-md-3">
															<img v-if="employee.hasThumbnail" v-bind:src="tmUrl + employee.no + '.JPG'" class="img-responsive">
															<img v-else :src="imgUrl + 'no-image.png'" class="img-responsive">
														</div>
														<!-- /thumbnail -->
													</div>
													<!-- /Row -->
												</fieldset>

												<fieldset class="form-group">
													<legend>Transaction Details</legend>
													<!-- row -->
													<div class="row">
														<div class="col-md-10">
															<div>
																<label class="col-sm-5 control-label" for="total_amount">Total Amount</label>
																<div class="col-sm-7">
																	<input type="text" name="total_amount" id="total_amount" class="form-control" v-model="totalPurchase" readonly>
																</div>
															</div>
															<div>
																<label class="col-sm-5 control-label" for="cash">Cash</label>
																<div class="col-sm-7">
																	<input type="text" name="cash" id="cash" class="form-control" v-model="cash.amount" v-on:click="enableCashField" :readonly="toggleCashfield">
																</div>
															</div>
															<div>
																<label class="col-sm-5 control-label" for="change">Change</label>
																<div class="col-sm-7">
																	<input type="text" name="change" id="change" class="form-control" v-model="cash.change" readonly>
																</div>
															</div>

															<div>
																<label class="col-sm-5 control-label" for="remaining_amount">Remaining Amount</label>
																<div class="col-sm-7">
																	<input type="text" name="remaining_amount" id="remaining_amount" class="form-control" readonly v-model="remaining_amount">
																</div>
															</div>

															<div>
																<label class="col-sm-5 control-label" for="remaining_credit">Remaining Credit</label>
																<div class="col-sm-7">
																	<input type="text" name="remaining_credit" id="remaining_credit" class="form-control" readonly v-model="remaining_credit">
																</div>
															</div>

															<div>
																<div class="col-sm-12">
																	<button class="btn btn-flat btn-danger pull-right">Enter</button>
																</div>
															</div>
														</div>
													</div>
													<!-- /row -->
												</fieldset>
											</form>
											<!-- /.form -->
										</div>
										<!-- /Checkout pane -->
									</div>
									<!-- /.tab-content -->
								</div>
								<!-- End of custom tabs -->
							</div>
							<!-- End of box body -->
						</div>
						<!-- Danger box -->
					</div>
					<!-- ./col-md-8 -->
				</div>
				<!-- ./row -->
			</div>
			<!-- ./col-md-8 -->
			
		</div>
		<!-- End of row -->

		<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal" ref="modal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<!-- modal-body -->
					<div class="modal-body">
						<!-- Item table -->
						<p class="separator">
							<span>Isuzu Philippines Corporation <br /></span>
							<span>Transaction#: {{ last_transaction_id }} <br /></span>
							<span><?php echo date('D, M d, Y h:i A'); ?></span>
						</p>
						<p class="separator">
							<span v-show="employee.fullname.length > 0">Customer: {{ _.startCase(_.toLower(employee.fullname)) }}<br /></span>
							<span v-show="employee.allowance.length >= 1">Meal Allowance: {{ employee.allowance }}<br /></span>
							<span>Cashier: <?php echo ucwords(strtolower($this->session->userdata('fullname'))) ?><br /></span>
							<span v-show="remaining_credit < 0">You have credit balance of {{ Math.abs(remaining_credit) }} pesos to be deducted on next meal allowance credit</span>
						</p>
						<table class="table table-condensed">
							<tbody>
								<!-- Items header -->
								<tr>
									<td>Purchased Items</td>
								</tr>

								<!-- List of items -->
								<tr v-for="(item, index) in cart" v-bind:class="{'separator': index === (cart.length - 1)}">
									<td>{{ item.name }}</td>
									<td>{{ item.quantity + 'x' }}</td>
									<td>{{ item.total }}</td>
								</tr>

								<!-- Purchase total -->
								<tr>
									<td colspan="2">Total: </td>
									<td>{{ totalPurchase }}</td>
								</tr>

								<!-- Remaining Credit -->
								<tr>
									<td colspan="2">Credit: </td>
									<td>{{ remaining_credit }}</td>
								</tr>

								<!-- Cash tendered -->
								<tr>
									<td colspan="2">Cash: </td>
									<td>{{ cash.amount }}</td>
								</tr>

								<!-- Customer's change -->
								<tr>
									<td colspan="2">Change: </td>
									<td>{{ cash.change }}</td>
								</tr>
							</tbody>
						</table>
						<!-- End of table -->
					</div>
					<!-- ./modal-body -->

					<div class="modal-footer">
						<button class="btn btn-info btn-flat pull-left" data-dismiss="modal">Close</button>
						<button class="btn btn-danger btn-flat" @click="printTransaction">Print</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End of App -->
</section>

<script src="<?php echo base_url('resources/js/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vue/vue.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vue-barcode-scanner/vue-barcode-scanner.js') ?>"></script>
<script src="<?php echo base_url('resources/js/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vee-validate/vee-validate.min.js') ?>"></script>
<script type="text/javascript">
	Vue.use(VeeValidate);
	Vue.use(VueBarcodeScanner);

	var appUrl = '<?php echo base_url('index.php') ?>';
	var imgUrl = '<?php echo base_url('resources/thumbnail/') ?>';
	var tmUrl  = '<?php echo base_url('resources/images/') ?>';

	var app = new Vue({
		el: '#app',
		data: {
			categories: [],
			categoryItems: [],
			featuredItems: [],
			cart: [],
			newItems: {
				id: '',
				name: '',
				price: '',
				quantity: '',
				total: '',
				barcode:'',
				state: false,
			},
			employee: {
				id: '',
				no: '',
				fullname: '',
				allowance: 0,
				state: false,
				hasThumbnail: false
			},
			cash: {
				amount: 0,
				change: 0,
				state: false
			},
			totalPurchase: 0,
			itemIndex: undefined,
			remaining_amount: 0,
			remaining_credit: 0,
			cash_tendered: 0,
			predicted_total: 0,
			credit_used: 0,
			last_transaction_id: 0,
			to_print: false
		},
		created() {
			this.fetchCategories()
			this.fetchCategoryItems()
			this.fetchFeaturedItems()

			// Add barcode scan listener and pass the callback function
			this.$barcodeScanner.init(this.onBarcodeScanned)
		},
		destroyed() {
			// Remove listener when component is destroyed
			this.$barcodeScanner.destroy()
		},
		mounted() {
			$(this.$refs.modal).on("hidden.bs.modal", this.initialState)
		},
		watch: {
			'employee.no': function() {
				this.readDetails()

				this.$set(this.employee, 'hasThumbnail', this.imageExists(tmUrl + this.employee_no + '.JPG') ? true : false)
			},
			'cash.amount': function() {
				this.updateValues()
			},
			totalPurchase: function() {
				this.updateValues()
			},
			cart: function() {
				this.updateTotalPurchase()
			},
			remaining_amount: function() {
				this.updateValues()
			},
		},
		computed: {
			toggleCashfield: function() {
				if (this.employee.allowance > 0)
				{
					if (this.remaining_amount > 0)
						return false

					return true
				}

				return false
			},
		},
		methods: {
			// Create callback function to receive barcode when the scanner is already done
			onBarcodeScanned (barcode) {
				if (barcode.length > 6)
				{
					this.addItem(this.searchItem(barcode))
				}
				else
				{
					this.$set(this.employee, 'no', barcode)
				}
			},
			searchItem: function(bc) {
				var items = _.flattenDeep(this.categoryItems)

				for(var item of items)
				{
					if (item.barcode == bc)
					{
						return item
					}
				}

				return false				
			},
			fetchCategories: function() {
				axios.get(appUrl + '/category/ajax_category_list')
				.then((response) => {
					this.categories = response.data
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			fetchCategoryItems: function() {
				axios.get(appUrl + '/category/ajax_category_items')
				.then((response) => {
					this.categoryItems = response.data
					console.log(this.categoryItems)
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			fetchFeaturedItems: function() {
				axios.get(appUrl + '/category/ajax_featured_items')
				.then((response) => {
					this.featuredItems = response.data
					console.log(this.featuredItems)
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			addItem: function(item) {
				if (item == false)
				{
					return
				}

				this.newItems = {
					id: item.id,
					name: item.name,
					price: item.price,
					quantity: 1,
					total: item.price,
					barcode: item.barcode
				}

				//console.log(this.newItems)
				console.log(this.last_transaction_id)

				var index = this.cartIndex(this.newItems)

				this.predicted_total = Number(this.totalPurchase) + Number(this.newItems.total) - Number(this.employee.allowance) - Number(this.cash.amount)

				// Meal allowance basis
				if (this.hasUser() && this.predicted_total <= 200)
				{
					if (index !== undefined) {
						this.newItems = {
							id: this.cart[index].id,
							name: this.cart[index].name,
							price: this.cart[index].price,
							quantity: ++this.cart[index].quantity,
							total: this.cart[index].quantity * this.cart[index].price
						}

						this.cart.splice(index, 1, this.newItems)
						this.itemIndex = index
					}
					else {
						this.cart.push(this.newItems)
						this.itemIndex = this.cartIndex(this.newItems)
					}
				}
				else if (!this.hasUser())
				{
					// Cash basis
					if (index !== undefined) {
						this.newItems = {
							id: this.cart[index].id,
							name: this.cart[index].name,
							price: this.cart[index].price,
							quantity: ++this.cart[index].quantity,
							total: this.cart[index].quantity * this.cart[index].price
						}

						this.cart.splice(index, 1, this.newItems)
						this.itemIndex = index
					}
					else {
						this.cart.push(this.newItems)
						this.itemIndex = this.cartIndex(this.newItems)
					}
				}
				else
				{
					let confirmation = confirm(`You will have to pay additional amount of ${this.predicted_total - 200} to proceed on this transaction.`)

					if (confirmation)
					{
						if (index !== undefined) {
							this.newItems = {
								id: this.cart[index].id,
								name: this.cart[index].name,
								price: this.cart[index].price,
								quantity: ++this.cart[index].quantity,
								total: this.cart[index].quantity * this.cart[index].price
							}

							this.cart.splice(index, 1, this.newItems)
							this.itemIndex = index
						}
						else {
							this.cart.push(this.newItems)
							this.itemIndex = this.cartIndex(this.newItems)
						}
					}
				}

				this.manageState(this.newItems)
			},
			cartIndex: function(item) {
				var i = undefined

				for(var [index, value] of this.cart.entries())
				{
					if (value.id === item.id)
					{
						i = index
						break
					}
				}

				return i
			},
			clearItems: function() {
				this.cart.splice(0, this.cart.length)
			},
			editItem: function(index) {
				this.itemIndex = index

				this.newItems = {
						id: this.cart[index].id,
						name: this.cart[index].name,
						price: this.cart[index].price,
						quantity: this.cart[index].quantity,
						total: this.cart[index].total
					}

				this.manageState(this.newItems)
			},
			updateItem: function() {
				this.$validator.validateAll()
				.then((result) => {
					if (result)
					{
						this.newItems.total = Number(this.newItems.price) * this.newItems.quantity

						this.cart.splice(this.itemIndex, 1, this.newItems)

						this.newItems = {
								id: '',
								name: '',
								price: '',
								quantity: '',
								total: ''
							}
					}

				});
			},
			deleteItem: function(index) {
				this.cart.splice(index, 1)
			},
			updateTotalPurchase: function() {
				this.totalPurchase = _.chain(this.cart).map((prop) => { return Number(prop.total) }).sum()
			},
			performTransaction: function() {
				console.log(this.remaining_amount)
				if ((this.hasUser() && this.remaining_credit >= -200 && this.cart.length > 0 && this.remaining_amount < 0) || (this.remaining_amount <= 0 && this.cart.length > 0))
				{
					axios({
						url: appUrl + '/transaction/store',
						method: 'post',
						data: {
							cart: this.cart,
							employee: this.employee.id ? this.employee : 0,
							totalPurchase: this.totalPurchase || 0,
							cash: this.cash.amount || 0,
							change: this.cash.change || 0,
							remaining_amount: this.remaining_amount || 0,
							remaining_credit: this.remaining_credit || 0,
							credit_used: this.credit_used || 0,
							to_print: this.to_print || false,
							trans_id: this.last_transaction_id || 0
						}
					})
					.then((response) => {
						// your action after success
						console.log(response.data)
						this.last_transaction_id = response.data
						$('#modal').modal('show')
					})
					.catch(function (error) {
						// your action on error success
						console.log(error);
					});
				}
				else if (this.hasUser() && this.remaining_credit < 200 && this.cart.length > 0)
				{
					alert('Cannot do the transaction credit limit has been exceded!');
				}				
				else if (this.cart.length == 0)
				{
					alert('There is no purchased item!');
				}
				else if (!this.hasUser() && this.remaining_amount > 0)
				{
					alert('Not enough cash to perform the transaction!');
				}
				else
				{
					alert('Not enough cash to perform the transaction!');
				}
			},
			printTransaction: function()
			{
				this.to_print = true

				this.performTransaction()
			},
			btnClick: function(value) {
				// Check if quantity is editable
				if (this.newItems.state === true) {
					var concat = _.toString(this.newItems.quantity) + _.toString(value)

					this.$set(this.newItems, 'quantity', concat)
				}
				else if (this.employee.state === true) {
					var concat = _.toString(this.employee.no) + _.toString(value)

					this.$set(this.employee, 'no', concat)
				}
				else if (this.cash.state === true) {
					var concat = _.toString(this.cash.amount) + _.toString(value)

					this.$set(this.cash, 'amount', concat)
				}
			},
			removeChar: function() {
				// Check if quantity is editable
				if (this.newItems.state === true) {
					var quantity = _.toString(this.newItems.quantity)
					quantity = quantity.substring(0, this.newItems.quantity.length - 1)

					this.$set(this.newItems, 'quantity', quantity)
				}
				else if (this.employee.state === true) {
					var employee_no = _.toString(this.employee.no)

					if (this.employee.no != undefined)
					{
						employee_no = employee_no.substring(0, this.employee.no.length - 1)

						console.log(employee_no)

						this.$set(this.employee, 'no', employee_no)
					}
					
				}
				else if (this.cash.state === true) {
					var amount = _.toString(this.cash.amount)
					amount = amount.substring(0, this.cash.amount.length - 1)

					this.$set(this.cash, 'amount', amount)
				}
			},
			showCheckoutPane: function() {
				var $checkout_pane = $('#checkout-pane')
				var $nav_tabs = $('.nav-tabs')

				// Remove the active states on its siblings
				$checkout_pane.siblings().removeClass('active')
				$checkout_pane.addClass('active')

				// Remove nav-tab active states
				$nav_tabs.children().removeClass('active')
			},
			readDetails: function() {
				if (this.employee.no.length == 6) {
					axios.get(appUrl + '/user/entity', {
						params: {
							employee_no: this.employee.no
						}
					})
					.then((response) => {
						var user = response.data

						console.log(user)

						this.employee = {
							id: user.id,
							no: user.emp_no,
							fullname: user.fullname,
							allowance: user.meal_allowance, 
							hasThumbnail: this.imageExists(tmUrl + user.emp_no + '.JPG') ? true : false
						}

						this.updateValues()
						this.manageState(this.employee)
					})
					.catch((error) => {
						console.log(error)
						alert('Employee No. does not exist!')
						this.employee.id = ''
						this.employee.fullname = ''
						this.employee.allowance = 0
						this.remaining_credit = 0
						this.manageState(this.employee)
					})
				}
				else {
					this.employee.id = ''
					this.employee.fullname = ''
					this.employee.allowance = 0
					this.remaining_credit = 0

					this.updateValues()
				}
			},
			enableCashField: function() {
				if (this.cash.amount == 0) {
					this.$set(this.cash, 'amount', '')
				}

				this.manageState(this.cash)
			},
			manageState: function(state) {
				this.newItems.state = false
				this.employee.state = false
				this.cash.state = false

				this.$set(state, 'state', true)
			},
			updateValues: function() {
				// Calculate remaining amount
				if (this.hasUser() && this.employee.allowance < 0)
				{
					this.remaining_amount = Number(this.totalPurchase) - Number(this.cash.amount)
				}
				else
				{		
					this.remaining_amount = Number(this.totalPurchase) - Number(this.employee.allowance) - Number(this.cash.amount)
				}

				// Cash and no user
				if (this.cash.amount > 0 && !this.hasUser())
				{
					if (this.remaining_amount < 0)
					{
						this.$set(this.cash, 'change', Math.abs(this.remaining_amount))
						this.remaining_amount = 0

						if (this.cash.change > 0)
						{
							this.cash_tendered = this.cash.amount - this.cash.change
							console.log("Cash tendered: " + this.cash_tendered)
						}
					}
					else
					{
						this.$set(this.cash, 'change', 0)
					}
				}
				else if (this.cash.amount == 0 && this.hasUser())
				{
					if (this.remaining_amount < 0)
					{
						this.remaining_credit = Math.abs(this.remaining_amount)
						this.remaining_amount = 0
						this.credit_used      = this.totalPurchase
					}
					else if (this.remaining_amount > 0)
					{
						// Calculate remaining limit
						var remaining_limit = 200 + Number(this.employee.allowance)
						
						// Calculate initial value
						var calculated_amount = Number(this.totalPurchase) - Number(remaining_limit)

						this.remaining_credit = this.employee.allowance > 0 ? Number(this.remaining_amount * -1) : Number(this.remaining_amount * -1) + Number(this.employee.allowance)
						this.remaining_amount = Number(calculated_amount) > 0 ? calculated_amount : 0
						this.credit_used      = this.totalPurchase
					}
					else
					{
						this.remaining_credit = 0
						this.credit_used      = 0
					}

					this.$set(this.cash, 'change', 0)
				}
				else if (this.cash.amount > 0 && this.hasUser())
				{
					if (this.employee.allowance >= -200)
					{
						// Calculate remaining limit
						var remaining_limit = 200 + Number(this.employee.allowance)
						
						// Calculate initial value
						var calculated_amount = Number(this.totalPurchase) - Number(remaining_limit)

						this.remaining_amount = Number(calculated_amount) > 0 ? calculated_amount : 0

						if (calculated_amount > 0)
						{
							// Apply cash amount
							calculated_amount = Number(calculated_amount) - Number(this.cash.amount)

							// Set amount to be filled up
							this.remaining_amount = Number(calculated_amount) > 0 ? calculated_amount : 0

							this.remaining_credit = Number(this.employee.allowance) + Number(-1 * remaining_limit)

							this.credit_used = remaining_limit

							if (calculated_amount <= 0)
							{
								this.$set(this.cash, 'change', Math.abs(calculated_amount))
							}
						}
					}
				}
			},
			hasUser: function() {
				if (this.employee.fullname.length > 0 && this.employee.no.length > 0)
				{
					return true
				}

				return false
			},
			initialState: function() {
				this.clearItems()

				this.newItems = {
					id: '',
					name: '',
					price: '',
					quantity: '',
					total: '',
					barcode:'',
					state: false,
				}

				this.employee = {
					id: '',
					no: '',
					fullname: '',
					allowance: 0,
					state: false,
					hasThumbnail: false
				}

				this.cash = {
					amount: 0,
					change: 0,
					state: false
				}

				this.totalPurchase    = 0
				this.itemIndex        = undefined
				this.remaining_amount = 0
				this.remaining_credit = 0
				this.cash_tendered    = 0
				this.predicted_total  = 0
				this.credit_used      = 0
				this.to_print         = false

				this.showValues()
			},
			showValues: function()
			{
				console.log("Cart: " + this.cart)
				console.log("items: " + this.newItems)
				console.log("Employee: " + this.employee)
				console.log("Cash: " + this.cash)
				console.log("Total Purchase: " + this.totalPurchase)
				console.log("Item Index: " + this.itemIndex)
				console.log("Remaining Amount: " + this.remaining_amount)
				console.log("Remaining Credit: " + this.remaining_credit)
				console.log("Cash Tendered: " + this.cash_tendered)
				console.log("Predicted Total: " + this.predicted_total)
				console.log("Credit Used: " + this.credit_used)
				console.log("Allowed printing: " + this.to_print)
			},
			imageExists: function(image_url)
			{
				var http = new XMLHttpRequest();

				http.open('HEAD', image_url, false);
				http.send();

				return http.status === 200;
			}
		},
	});

	// JQuery script
	document.oncontextmenu = document.body.oncontextmenu = function() {return false;}

</script>