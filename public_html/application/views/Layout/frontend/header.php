<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
	
<head>
		<meta charset="utf-8">
		<title>Kukua B V | BULK Weather SMS, Custom Weather Forecast, Personalised Weather Forecast, API</title>
		<meta name="description" content="BULK Weather SMS, Custom Weather Forecast, Personalised Weather Forecast, API">
		<meta name="author" content="Spectrum Team">

		<!-- Mobile Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Favicon -->
		<link rel="shortcut icon" href="<?=base_url()?>assets/home/images/kukuagp.png">

		<!-- Web Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>

		<!-- Bootstrap core CSS -->
                <?php echo link_tag('assets/home/bootstrap/css/bootstrap.css');?>

		<!-- Font Awesome CSS -->
                <?php echo link_tag('assets/home/fonts/font-awesome/css/font-awesome.css');?>

		<!-- Fontello CSS -->
                <?php echo link_tag('assets/home/fonts/fontello/css/fontello.css');?>

		<!-- Plugins -->
                <?php echo link_tag('assets/home/plugins/rs-plugin/css/settings.css');?>
                <?php echo link_tag('assets/home/plugins/rs-plugin/css/extralayers.css');?>
                <?php echo link_tag('assets/home/plugins/magnific-popup/magnific-popup.css');?>
                <?php echo link_tag('assets/home/css/animations.css');?>
                <?php echo link_tag('assets/home/plugins/owl-carousel/owl.carousel.css');?>

		<!-- Spectrum core CSS file -->
                <?php echo link_tag('assets/home/css/style.css');?>

		<!-- Style Switcher Styles (Remove these two lines) -->
		<link href="#" data-style="styles" rel="stylesheet">
                <?php echo link_tag('assets/home/style-switcher/style-switcher.css');?>

		<!-- Custom css -->
                <?php echo link_tag('assets/home/css/custom.css');?>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
                
		<script type="text/javascript" src="<?php echo base_url(); ?>/assets/home/plugins/jquery.min.js"></script>
                <script src='https://www.google.com/recaptcha/api.js'></script>
	</head>

	<!-- body classes: 
			"boxed": boxed layout mode e.g. <body class="boxed">
			"pattern-1 ... pattern-9": background patterns for boxed layout mode e.g. <body class="boxed pattern-1"> 
	-->
	<body class="front no-trans">
		<!-- scrollToTop -->
		<!-- ================ -->
		<div class="scrollToTop"><i class="icon-up-open-big"></i></div>

		<!-- page wrapper start -->
		<!-- ================ -->
		<div class="page-wrapper">

			<!-- header-top start (Add "dark" class to .header-top in order to enable dark header-top e.g <div class="header-top dark">) -->
			<!-- ================ -->
			<div class="header-top">
				<div class="container">
					<div class="row">
						<div class="col-xs-2 col-sm-6">

							<!-- header-top-first start -->
							<!-- ================ -->
							<div class="header-top-first clearfix">
								<ul class="social-links clearfix hidden-xs">
									<li class="twitter"><a target="_blank" href="https://twitter.com/kukuaweather"><i class="fa fa-twitter"></i></a></li>
									<li class="facebook"><a target="_blank" href="https://web.facebook.com/kukuaweather/"><i class="fa fa-facebook"></i></a></li>
                                                                        <li class="linkedin"><a target="_blank" href="https://www.linkedin.com/company/kukua-b-v-/"><i class="fa fa-linkedin"></i></a></li>
                                                                        <li class="facebook"><a href="#">+31302271617</a></li>

								</ul>
								<div class="social-links hidden-lg hidden-md hidden-sm">
									<div class="btn-group dropdown">
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-share-alt"></i></button>
										<ul class="dropdown-menu dropdown-animation">
											<li class="twitter"><a target="_blank" href="https://twitter.com/spectrumUG"><i class="fa fa-twitter"></i></a></li>
											<li class="facebook"><a target="_blank" href="https://web.facebook.com/spectrumconnect/"><i class="fa fa-facebook"></i></a></li>
                                                                                        <li class="facebook"><a target="_blank" href="https://ug.linkedin.com/in/spectrum-connect-a2167a117"><i class="fa fa-linkedin"></i></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- header-top-first end -->

						</div>
						<div class="col-xs-10 col-sm-6">

							<!-- header-top-second start -->
							<!-- ================ -->
							<div id="header-top-second"  class="clearfix">

								<!-- header top dropdowns start -->
								<!-- ================ -->
								<div class="header-top-dropdown">
									<!--<div class="btn-group dropdown">
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search"></i> Search</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
											<li>
												<form role="search" class="search-box">
													<div class="form-group has-feedback">
														<input type="text" class="form-control" placeholder="Search">
														<i class="fa fa-search form-control-feedback"></i>
													</div>
												</form>
											</li>
										</ul>
									</div>!-->
									<div class="btn-group dropdown">
                                                                            
										<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Login</button>
										<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
                                                                                    
                                                                                    
                                                                                    <li>
                                                                                            <?php echo form_open('home/login',array('class'=>'login-form','id'=>'login-form'));?>
                                                                                                    <div class="form-group has-feedback">
                                                                                                            <label class="control-label">Username</label>
                                                                                                            <input type="text" class="form-control" name="email" placeholder="Email" required="required">
                                                                                                            <i class="fa fa-user form-control-feedback"></i>
                                                                                                    </div>
                                                                                                    <div class="form-group has-feedback">
                                                                                                            <label class="control-label">Password</label>
                                                                                                            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                                                                                                            <i class="fa fa-lock form-control-feedback"></i>
                                                                                                    </div>
                                                                                                    <button type="submit" class="btn btn-group btn-dark btn-sm">Log In</button>
                                                                                                    <span>or</span>
                                                                                                    <?php echo anchor('home/create_account','Sign Up',array('class'=>'btn btn-group btn-default btn-sm'));?>

                                                                                                    <ul>
                                                                                                            <li> <?php echo anchor('home/forgot_password','Forgotten password?');?></li>
                                                                                                    </ul>
                                                                                                    <!--<div class="divider"></div>
                                                                                                    <span class="text-center">Login with</span>
                                                                                                    <ul class="social-links clearfix">
                                                                                                            <li class="facebook"><a target="_blank" href="assets/home/http://www.facebook.com/"><i class="fa fa-facebook"></i></a></li>
                                                                                                            <li class="twitter"><a target="_blank" href="assets/home/http://www.twitter.com/"><i class="fa fa-twitter"></i></a></li>
                                                                                                            <li class="googleplus"><a target="_blank" href="assets/home/http://plus.google.com/"><i class="fa fa-google-plus"></i></a></li>
                                                                                                    </ul>!-->
                                                                                            <?php echo form_close();?>
                                                                                    </li>
										</ul>
									</div>
								

								</div>
								<!--  header top dropdowns end -->

							</div>
							<!-- header-top-second end -->

						</div>
					</div>
				</div>
			</div>
			<!-- header-top end -->

			<!-- header start classes:
				fixed: fixed navigation mode (sticky menu) e.g. <header class="header fixed clearfix">
				 dark: dark header version e.g. <header class="header dark clearfix">
			================ -->
			<header class="header fixed clearfix">
				<div class="container">
					<div class="row">
						<div class="col-md-3">

							<!-- header-left start -->
							<!-- ================ -->
							<div class="header-left clearfix">

								<!-- logo -->
								<div class="logo">
									<a href="<?=base_url()?>"><img id="logo" src="<?=base_url()?>assets/home/images/Logo-Kukua.png" alt="Kukua"></a>
								</div>

								<!-- name-and-slogan -->
								<div class="site-slogan">
                                    To close Africa’s weather information gap
								</div>

							</div>
							<!-- header-left end -->

						</div>
						<div class="col-md-9">

							<!-- header-right start -->
							<!-- ================ -->
							<div class="header-right clearfix">

								<!-- main-navigation start -->
								<!-- ================ -->
								<div class="main-navigation animated">

									<!-- navbar start -->
									<!-- ================ -->
									<nav class="navbar navbar-default" role="navigation">
										<div class="container-fluid">

											<!-- Toggle get grouped for better mobile display -->
											<div class="navbar-header">
												<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
													<span class="sr-only">Toggle navigation</span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
												</button>
											</div>

											<!-- Collect the nav links, forms, and other content for toggling -->
											<div class="collapse navbar-collapse" id="navbar-collapse-1">
												<ul class="nav navbar-nav navbar-right">

												</ul>
											</div>

										</div>
									</nav>
									<!-- navbar end -->

								</div>
								<!-- main-navigation end -->

							</div>
							<!-- header-right end -->

						</div>
					</div>
				</div>
			</header>
			<!-- header end -->
