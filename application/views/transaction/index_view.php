<style type="text/css">
	td > a > i {
		padding: 10px;
	}
</style>
<!-- Items block -->
<section class="content transaction">
	<!-- App -->
	<div id="app">
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
									<td>&#8369; {{ item.price }}</td>
									<td>{{ item.quantity }}</td>
									<td>&#8369;  {{ item.total }}</td>
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

			<!-- col-md-6 -->
			<div class="col-md-2">
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
											<button class="btn btn-flat btn-block" v-on:click="btnClick(3)">3</button>
										</li>
										<li class="col-md-4 list-group-item">
											<button class="btn btn-flat btn-block" v-on:click="btnClick(2)">2</button>
										</li>
										<li class="col-md-4 list-group-item">
											<button class="btn btn-flat btn-block" v-on:click="btnClick(1)">1</button>
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
			<!-- /col-md-3 -->

			<!-- Items -->
			<div class="col-md-6">
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
								<div class="tab-pane" v-for="(category_items, index) in categoryItems" v-bind:id="index" v-bind:class="{'active': index === 0}">
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
									<form class="form-horizonal" autocomplete="off" id="transaction-form" v-on:submit.prevent="performTransaction">
										<fieldset>
											<legend>Team Member Details</legend>
											<!-- Row -->
											<div class="row">
												<!-- TM details -->
												<div class="col-md-9">
													<div class="form-group">
														<label class="col-sm-4 control-label" for="employee_no">Employee No.</label>
														<div class="col-sm-8">
															<input type="text" name="employee_no" id="employee_no" class="form-control" v-validate="'max:6'" v-model="employee.no" v-on:click="manageState(employee)">
															<i v-show="errors.has('employee_no')" class="fa fa-warning text-danger"></i>
															<span v-show="errors.has('employee_no')" class="text-danger">{{ errors.first('employee_no') }}</span>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="employee_name" >Employee Name</label>
														<div class="col-sm-8">
															<input type="text" name="employee_name" id="employee_name" class="form-control" readonly v-model="employee.fullname">
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-4 control-label" for="meal_allowance">Meal Allowance</label>
														<div class="col-sm-8">
															<input type="text" name="meal_allowanace" id="meal_allowance" class="form-control" readonly v-model="employee.allowance">
														</div>
													</div>
												</div>
												<!-- /TM details -->

												<!-- TM thumbnail -->
												<div class="col-md-3">
													<img v-bind:src="imgUrl + 'no-image.png'" class="img-responsive">
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
														<label class="col-sm-4 control-label" for="total_amount">Total Amount</label>
														<div class="col-sm-8">
															<input type="text" name="total_amount" id="total_amount" class="form-control" v-bind:value="totalPurchase" readonly>
														</div>
													</div>
													<div>
														<label class="col-sm-4 control-label" for="cash">Cash</label>
														<div class="col-sm-8">
															<input type="text" name="cash" id="cash" class="form-control" v-model="cash.amount" v-on:click="enableCashField" :readonly="toggleCashfield">
														</div>
													</div>
													<div>
														<label class="col-sm-4 control-label" for="change">Change</label>
														<div class="col-sm-8">
															<input type="text" name="change" id="change" class="form-control" v-model="cash.change" readonly>
														</div>
													</div>

													<div>
														<label class="col-sm-4 control-label" for="remaining_amount">Remaining Amount</label>
														<div class="col-sm-8">
															<input type="text" name="remaining_amount" id="remaining_amount" class="form-control" v-bind:value="totalPurchase" readonly v-model="remaining_amount">
														</div>
													</div>

													<div>
														<label class="col-sm-4 control-label" for="remaining_credit">Remaining Credit</label>
														<div class="col-sm-8">
															<input type="text" name="remaining_credit" id="remaining_credit" class="form-control" v-bind:value="totalPurchase" readonly v-model="remaining_credit">
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
			<!-- End of column -->
		</div>
		<!-- End of row -->

		<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="modal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Isuzu Philippines Receipt</h4>
					</div>

					<div class="modal-body">
						<!-- Item table -->
						<p class="separator">
							<span>Isuzu Philippines Corporation <br /></span>
							<span>Transaction #1220422 <br /></span>
							<span><?php echo date('D, M d, Y h:i:A'); ?></span>
						</p>
						<p class="separator">
							<span v-show="employee.fullname.length > 0">Customer: {{ _.startCase(_.toLower(employee.fullname)) }}<br /></span>
							<span v-show="employee.allowance.length >= 1">Meal Allowance: {{ employee.allowance }}<br /></span>
							<span>Cashier: <?php echo ucwords(strtolower($this->session->userdata('fullname'))) ?></span>
						</p>
						<table class="table table-condensed">
							<tbody>
								<!-- Items header -->
								<tr>
									<td>Purchase Items</td>
								</tr>

								<!-- List of items -->
								<tr v-for="(item, index) in cart" v-bind:class="{'separator': index === (cart.length - 1)}">
									<td>{{ _.toUpper(item.name) }}</td>
									<td>{{ item.quantity + 'x' }}</td>
									<td>&#8369; {{ item.total }}</td>
								</tr>

								<!-- Purchase total -->
								<tr>
									<td colspan="2">Total: </td>
									<td>{{ "&#8369; " + totalPurchase }}</td>
								</tr>

								<!-- Remaining Credit -->
								<tr>
									<td colspan="2">Credit: </td>
									<td> {{ "&#8369; " + remaining_credit }}</td>
								</tr>

								<!-- Cash tendered -->
								<tr>
									<td colspan="2">Cash: </td>
									<td> {{ "&#8369; " + cash.amount }}</td>
								</tr>

								<!-- Customer's change -->
								<tr>
									<td colspan="2">Change: </td>
									<td> {{ "&#8369; " + cash.change }}</td>
								</tr>
							</tbody>
						</table>
						<!-- End of table -->
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

	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});

	var app = new Vue({
		el: '#app',
		data: {
			categories: [],
			categoryItems: [],
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
				state: false
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
		},
		created() {
			this.fetchCategories()
			this.fetchCategoryItems()
		},
		watch: {
			'employee.no': function() {
				this.readDetails()
			},
			'cash.amount': function() {
				this.updateValues()
			},
			totalPurchase: function () {
				this.updateValues()
			},
			cart: function() {
				this.updateTotalPurchase()
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
			}
		},
		methods: {
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
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			addItem: function(item) {
				this.newItems = {
					id: item.id,
					name: item.name,
					price: item.price,
					quantity: 1,
					total: item.price
				}

				var index = this.cartIndex(this.newItems)

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

				this.manageState(this.newItems)
				this.$refs.quantity.focus()
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
				this.$refs.quantity.focus()
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
						remaining_credit: this.remaining_credit || 0
					}
				})
				.then(function (response) {
					// your action after success
					console.log(response);

				})
				.catch(function (error) {
					// your action on error success
					console.log(error);
				});
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
					employee_no = employee_no.substring(0, this.employee.no.length - 1)

					this.$set(this.employee, 'no', employee_no)
				}
				else if (this.cash.state === true) {
					var amount = _.toString(this.cash.amount)
					amount = amount.substring(0, this.cash.amount.length - 1)

					this.$set(this.cash, 'amount', amount)
				}
			},
			showCheckoutPane: function() {
				if (this.totalPurchase > 0) {
					var $checkout_pane = $('#checkout-pane')
					var $nav_tabs = $('.nav-tabs')

					// Remove the active states on its siblings
					$checkout_pane.siblings().removeClass('active')
					$checkout_pane.addClass('active')

					// Remove nav-tab active states
					$nav_tabs.children().removeClass('active')
				}
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
							allowance: user.meal_allowance
						}

						this.updateValues()
					})
					.catch((error) => {
						console.log(error)
					})
				}
				else {
					this.employee.id = ''
					this.employee.fullname = ''
					this.employee.allowance = 0

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
				this.remaining_amount = Number(this.totalPurchase) - Number(this.employee.allowance) - Number(this.cash.amount)

				if (this.cash.amount > 0)
				{
					if (this.remaining_amount < 0)
					{
						var change = Math.abs(this.remaining_amount) > this.employee.allowance ? Math.abs(this.remaining_amount) - this.employee.allowance : Math.abs(this.remaining_amount)

						this.$set(this.cash, 'change', change)
						this.remaining_amount = 0

						if (this.cash.change > 0)
						{
							this.cash_tendered = this.cash.amount - this.cash.change
							console.log(this.cash_tendered)
						}
					}
					else
					{
						this.$set(this.cash, 'change', 0)
					}
				}
				else
				{
					if (this.remaining_amount < 0)
					{
						this.remaining_credit = Math.abs(this.remaining_amount)
						this.remaining_amount = 0
						this.$set(this.cash, 'change', 0)
					}
					else
					{
						this.remaining_credit = 0
						this.$set(this.cash, 'change', 0)
					}
				}
			},
		},
	});

	// Disable right click
	//document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
</script>