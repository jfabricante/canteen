<!-- Main Header -->
<header class="main-header">

	<!-- Logo -->
	<a href="#" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>IPC</b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><img src="<?php echo base_url('resources/images/logo_white.png');?>"></span>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">

			<!-- Navigation Menu -->
			<ul class="nav navbar-nav">
				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="<?php echo base_url('resources/images/default.png');?>" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><?php echo $this->session->userdata('fullname');?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header">
							<img src="<?php echo base_url('resources/images/default.png');?>" class="img-circle" alt="User Image">
							<p>
								<?php echo $this->session->userdata('fullname');?>
							</p>
						</li>
				
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left hidden">
								<a href="#" data-toggle="control-sidebar" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="<?php echo base_url('index.php/login/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
				<!-- End User Account Menu -->
			</ul>
			<!-- End of Navigation Menu -->
		</div>
		<!-- Navbar Right Menu -->
	</nav>
	<!-- End of Header Navbar -->
</header>
<!-- Header End -->
