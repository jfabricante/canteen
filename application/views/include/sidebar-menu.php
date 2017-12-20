<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo base_url('resources/images/default.png');?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>	<?php echo $this->session->userdata('fullname'); ?></p>
			</div>
		</div>

		<!-- Seach form -->
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
					<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</form>

		<!-- Sidebar Menu -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			
			<?php $menu = $this->uri->uri_string(); ?>

			<li class="<?php echo $menu == 'item/list_' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/item/list_') ?>"><i class="fa fa-dot-circle-o"></i><span>Items</span></a></li>

			<li class="<?php echo $menu == 'category/list_' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/category/list_') ?>"><i class="fa fa-tags"></i><span>Categories</span></a></li>

			<li class="<?php echo $menu == 'category/set_menu' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/category/set_menu') ?>"><i class="fa fa-bars"></i><span>Set Menu</span></a></li>

			<li class="<?php echo $menu == 'user/list_' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/user/list_') ?>"><i class="fa fa-user"></i><span>Users</span></a></li>

			<li class="<?php echo $menu == 'transaction/index' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/transaction/index') ?>"><i class="fa fa-shopping-cart"></i><span>POS</span></a></li>

			<li class="<?php echo $menu == 'transaction/generate_billing_report' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/transaction/generate_billing_report') ?>"><i class="fa fa-bar-chart"></i><span>Billing Report</span></a></li>

			<li class="<?php echo $menu == 'user/balances' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/user/balances') ?>"><i class="fa fa-credit-card"></i><span>Balances</span></a></li>

			<li class="<?php echo $menu == 'user/purchased_items' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/user/purchased_items') ?>"><i class="fa fa-database"></i><span>Sales Order</span></a></li>

			<li class="<?php echo $menu == 'user/ledger' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/user/ledger') ?>"><i class="fa fa-book"></i><span>Ledger</span></a></li>
			
			<li class="<?php echo $menu == 'user/cashier_sales' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/user/cashier_sales') ?>"><i class="fa fa-files-o"></i><span>Cashier Sales</span></a></li>

			<li class="<?php echo $menu == 'transaction/invoice_list' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/transaction/invoice_list') ?>"><i class="fa fa-envelope"></i><span>Invoices</span></a></li>

			<li class="<?php echo $menu == 'transaction/invoice_item' ? 'active' : ''; ?>"><a href="<?php echo base_url('index.php/transaction/invoice_item') ?>"><i class="fa fa-circle"></i><span>Invoice Item</span></a></li>


		</ul><!-- /.sidebar-menu -->

	</section>
	<!-- /.sidebar -->
</aside>

