<!-- Items block -->
<section class="content menu">
	<!-- App -->
	<div id="app">
		<!-- row -->
		<div class="row">
			<!-- col-md-6 -->
			<div class="col-md-6">
				<!-- Box danger -->
				<?php echo $this->session->flashdata('message');  ?>
				
				<div class="box box-danger menu-items">
					<!-- Content -->
					<div class="box-header with-border">
						<h3 class="box-title">Menu for the day</h3>
					</div>

					<div class="box-body">
						<ul class="list-group">
							<draggable class="dragArea" :list="featured_items" :options="{group:'product'}" @change="changeState" style="min-height: 200px;" :add="onAdd">
								<li v-for="(item, index) in featured_items" :key="index" class="col-md-2 list-group-item">
									<i class="fa fa-times fa-lg item-remove" aria-hidden="true" v-on:click="removeFeaturedItem(item, index)"></i>
									<img v-bind:src="imgUrl + item.thumbnail" v-if="item.thumbnail !== null" class="img-responsive">
									<img v-bind:src="imgUrl + 'no-image.png'" v-else class="img-responsive">
									<p class="text-center">{{ _.toUpper(item.name) }}</p>
									<p class="text-center">&#8369; {{ item.price }}</p>
								</li>
							</draggable>
						</ul>
					</div>
					<!-- End of content -->
				</div>
				<!-- End of danger -->
			</div>
			<!-- End of col-md-6 -->

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
											<draggable class="dragArea" :list="category_items" :options="{group:{name:'product', pull:'clone', put:false}}" style="min-height: 200px;">
												<li v-for="(item, index) in category_items" :key="index" class="col-md-2 list-group-item">
													<img v-bind:src="imgUrl + item.thumbnail" v-if="item.thumbnail !== null" class="img-responsive">
													<img v-bind:src="imgUrl + 'no-image.png'" v-else class="img-responsive">
													<p class="text-center">{{ _.toUpper(item.name) }}</p>
													<p class="text-center">&#8369; {{ item.price }}</p>
												</li>
											</draggable>
										</ul>
									</div>
									<!-- End of row -->
								
								</div>
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

<script src="<?php echo base_url('resources/js/axios/axios.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vue/vue.min.js') ?>"></script>
<script src="<?php echo base_url('resources/js/lodash/lodash.js') ?>"></script>
<script src="<?php echo base_url('resources/js/sortable/sortable.js') ?>"></script>
<script src="<?php echo base_url('resources/js/vuedraggable/vuedraggable.js') ?>"></script>
<script type="text/javascript">
	var appUrl = '<?php echo base_url('index.php') ?>';
	var imgUrl = '<?php echo base_url('resources/thumbnail/') ?>';
	var tmUrl  = '<?php echo base_url('resources/images/') ?>';

	var app = new Vue({
		el: '#app',
		data: {
			categories: [],
			featured_items: [],
			categoryItems: [],
		
			itemIndex: undefined,
		},
		created() {
			this.fetchCategories()
			this.fetchCategoryItems()
		},
		methods: {
			changeState: function(evt) {
				this.addItem(evt)
			},
			addItem: function(event) {
				if (event.added == undefined)
				{
					return
				}

				var item_id = event.added.element.id

				axios({
					url: appUrl + '/item/ajax_store_featured',
					method: 'post',
					data: {
						item_id: item_id
					}
				})
				.then(function (response) {
					// your action after success
					//console.log(response);
				})
				.catch(function (error) {
					// your action on error success
					console.log(error);
				});

				this.featured_items = _.uniqBy(this.featured_items, 'id')
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
				})
				.catch(function (err) {
					console.log(err.message);
				});
			},
			fetchItems: function() {
				axios.get(appUrl + '/item/ajax_item_list')
				.then((response) => {
					this.items = response.data
					console.log(this.items)
				})
				.catch(function (err) {
					console.log(err.message);
				});
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

	// Disable right click
	//document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
</script>
