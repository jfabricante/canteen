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
											<i class="fa fa-pencil" aria-hidden="true"></i>	
										</a>
									</td>
									<td>
										<a href="#" v-on:click="deleteItem(index)">
											<i class="fa fa-trash" aria-hidden="true"></i>
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
								<div class="col-md-2 col-md-offset-6">
									<strong>Total: </strong>
								</div>
								<div class="col-md-3">
									<strong>&#8369;  {{ grandTotal }}</strong>
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
											<label for="item" class="col-sm-3 control-label">Item</label>
											<div class="col-sm-9">
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
											<label for="quantity" class="col-sm-3 control-label">Quantity</label>
											<div class="col-sm-9">
												<input type="text" class="form-control" id="quantity" ref="quantity" name="quantity" v-model="newItems.quantity" v-validate="'required|min_value:1|max:6'" v-bind:class="{'input': true, 'is-danger': errors.has('quantity') }">
											</div>
										</div>

										<div class="col-sm-12">
											<i v-show="errors.has('quantity')" class="fa fa-warning text-danger"></i>
											<span v-show="errors.has('quantity')" class="text-danger">{{ errors.first('quantity') }}</span>
										</div>

									</div>

									<div class="form-group hidden">
										<label for="total">Total</label>
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
									<button class="btn btn-flat btn-danger btn-block" v-on:click="testPush">
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

								<!-- Checkout pane -->
								<div class="tab-pane" id="checkout-pane">
									This is a test
								</div>
								<!-- /Checkout pane -->
								<!-- Tab pane -->
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
	</div>
	<!-- End of App -->
</section>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>
<script src="<?php echo base_url('resources/js/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vue/vue.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vee-validate/vee-validate.min.js') ?>"></script>
<script type="text/javascript">
	Vue.use(VeeValidate);

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
			newItems: [{
				id: '',
				name: '',
				price: '',
				quantity: '',
				total: '',
			}],
			grandTotal: 0,
			itemIndex: undefined,
			quantityState: false,
		},
		created() {
			this.fetchCategories()
			this.fetchCategoryItems()
		},
		methods: {
			fetchCategories: function()
			{
				axios.get(appUrl + '/category/ajax_category_list')
				.then((response) => {
					this.categories = response.data
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			fetchCategoryItems: function()
			{
				axios.get(appUrl + '/category/ajax_category_items')
				.then((response) => {
					this.categoryItems = response.data
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			addItem: function(item) 
			{
				this.newItems = {
					id: item.id,
					name: item.name,
					price: item.price,
					quantity: 1,
					total: item.price
				}

				var index = this.cartIndex(this.newItems)

				if (index !== undefined)
				{
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
				else
				{
					this.cart.push(this.newItems)
					this.itemIndex = this.cartIndex(this.newItems)
				}

				this.quantityState = true
				this.$refs.quantity.focus()
				this.updateGrandtotal()
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
			clearItems: function()
			{
				this.cart.splice(0, this.cart.length)

				this.grandTotal = 0
			},
			editItem: function(index)
			{
				this.itemIndex = index

				this.newItems = {
						id: this.cart[index].id,
						name: this.cart[index].name,
						price: this.cart[index].price,
						quantity: this.cart[index].quantity,
						total: this.cart[index].total
					}

				this.quantityState = true
				this.$refs.quantity.focus()
			},
			updateItem: function()
			{
				this.$validator.validateAll()
				.then((result) => {
					if (result)
					{
						this.newItems.total = Number(this.newItems.price) * this.newItems.quantity

						this.cart.splice(this.itemIndex, 1, this.newItems)

						this.updateGrandtotal()

						this.newItems = {
								id: '',
								name: '',
								price: '',
								quantity: '',
								total: ''
							}
					}

				});
				this.quantityState = false
			},
			deleteItem: function(index)
			{
				this.cart.splice(index, 1)

				this.updateGrandtotal()
			},
			updateGrandtotal: function() 
			{
				this.grandTotal = _.chain(this.cart).map((prop) => { return Number(prop.total) }).sum()
			},
			btnClick: function(value)
			{
				// Check if quantity is editable
				if (this.quantityState === true)
				{
					var concat = _.toString(this.newItems.quantity) + _.toString(value)

					this.newItems.quantity = concat
				}
			},
			removeChar: function()
			{
				// Check if quantity is editable
				if (this.quantityState === true)
				{
					var quantity = _.toString(this.newItems.quantity)
					quantity = quantity.substring(0, this.newItems.quantity.length - 1)

					this.$set(this.newItems,  'quantity', quantity)
				}
			},
		},
	});

</script>