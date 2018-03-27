<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$ci_Description = 'Kukua B V | BULK Weather SMS, Custom Weather Forecast, Personalised Weather Forecast, API';

error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html class="gt-ie8 gt-ie9 not-ie pxajs">
<head>
    <meta charset="utf-8">
    <title>Kukua B V | BBULK Weather SMS, Custom Weather Forecast, Personalised Weather Forecast, API</title>
    <meta name="description" content="BULK Weather SMS, Custom Weather Forecast, Personalised Weather Forecast, API">
    <meta name="author" content="Acellam Guy>
        <meta name=" robots
    " content="nofollow" />

    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/home/images/kukuagp.png">

    <!-- Open Sans font from Google CDN -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&amp;subset=latin"
          rel="stylesheet" type="text/css">

    <!-- Pixel Admin's stylesheets -->
    <?php echo link_tag('assets/backend/css/bootstrap.min.css'); ?>
    <?php echo link_tag('assets/backend/css/pixel-admin.min.css'); ?>
    <?php echo link_tag('assets/backend/css/widgets.min.css'); ?>
    <?php echo link_tag('assets/backend/css/pages.min.css'); ?>
    <?php echo link_tag('assets/backend/css/rtl.min.css'); ?>
    <?php echo link_tag('assets/backend/css/themes.min.css'); ?>
    <?php echo link_tag('assets/backend/tag-it/css/tagit.ui-zendesk.css'); ?>
    <?php echo link_tag('assets/backend/tag-it/css/jquery.tagit.css'); ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>/assets/backend/js/jquery.min.js"></script>
</head>
<body class="theme-default main-menu-animated">
<!-- begin preloader -->
<div class="preloader">
    <div class="preloader-content-wrapper">
        <div class="preloader-content">
            <h1>Kukua B V</h1>
            <i class="fa fa-cog fa-5x fa-spin"></i><br/>
            <span>Loading...</span>
        </div>
    </div>
</div>
<!-- end preloader -->
<script>var init = [];</script>


<div id="main-wrapper">

    <!-- 2. $MAIN_NAVIGATION ===========================================================================

            Main navigation
    -->
    <div id="main-navbar" class="navbar navbar-inverse" role="navigation">
        <!-- Main menu toggle -->
        <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span
                    class="hide-menu-text">HIDE MENU</span></button>

        <div class="navbar-inner">
            <!-- Main navbar header -->
            <div class="navbar-header">

                <!-- Logo -->

                <a href="<?=
                site_url([
                    "controller" => "home",
                    "method" => "index"
                ])
                ?>" class="navbar-brand">
                    <img src="<?= base_url() ?>assets/home/images/Logo-Kukua.png" alt="logo" class="logo"
                         width="110px"/>
                </a>

                <!-- Main navbar toggle -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

            </div> <!-- / .navbar-header -->

            <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
                <div>

                    <div class="right clearfix">
                        <ul class="nav navbar-nav pull-right right-navbar-nav">
                            <?php if ($this->session->userdata('dept') == 'admin'): ?>
                                <li class="nav-icon-btn nav-icon-btn-success">
                                    <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>_messages/sent_messages">
                                                <span class="label">
                                                    <?php
                                                    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(['status' => 1, 'date' => date('Y-m-d')])->get()->result();
                                                    echo number_format($rst[0]->counts);
                                                    ?>
                                                </span>
                                        <i class="nav-icon fa fa-envelope"></i>
                                        <span class="small-screen-text">Income messages</span>
                                    </a>
                                </li>
                                <li class="nav-icon-btn nav-icon-btn-danger">
                                    <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>_messages/scheduled_messages">
                                                <span class="label">
                                                    <?php
                                                    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(array('status' => 0, 'date' => date('Y-m-d')))->get()->result();
                                                    echo number_format($rst[0]->counts);
                                                    ?>
                                                </span>
                                        <i class="nav-icon fa fa-clock-o"></i>
                                        <span class="small-screen-text">Income messages</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="nav-icon-btn nav-icon-btn-success">
                                    <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>_messages/sent_messages">
                                                <span class="label">
                                                    <?php
                                                    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(['status' => 1, 'date' => date('Y-m-d'), 'sender' => $this->session->userdata('id')])->get()->result();
                                                    echo number_format($rst[0]->counts);
                                                    ?>
                                                </span>
                                        <i class="nav-icon fa fa-envelope"></i>
                                        <span class="small-screen-text">Income messages</span>
                                    </a>
                                </li>
                                <li class="nav-icon-btn nav-icon-btn-danger">
                                    <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>_messages/scheduled_messages">
                                                <span class="label">
                                                    <?php
                                                    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(array('status' => 0, 'date' => date('Y-m-d'), 'sender' => $this->session->userdata('id')))->get()->result();
                                                    echo number_format($rst[0]->counts);
                                                    ?>
                                                </span>
                                        <i class="nav-icon fa fa-clock-o"></i>
                                        <span class="small-screen-text">Income messages</span>
                                    </a>
                                </li>

                            <?php endif; ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                    <img src="<?php echo !empty($grav_url) ? $grav_url : ""; ?>" alt="">
                                    <span><?php echo strtoupper($this->session->userdata('fullname')) ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>/my_settings"><i
                                                    class="dropdown-icon fa fa-cog"></i>&nbsp;&nbsp;Settings</a></li>
                                    <li class="divider"></li>

                                    <li>
                                        <?php echo anchor($this->session->userdata('dept') . '_messages/logout', '<i class=" fa fa-power-off"></i> Log out', array('tabindex' => -1, 'onclick' => "return confirm('You are about to logout')")) ?>

                                    </li>
                                </ul>
                            </li>
                        </ul> <!-- / .navbar-nav -->
                    </div> <!-- / .right -->
                </div>
            </div> <!-- / #main-navbar-collapse -->
        </div> <!-- / .navbar-inner -->
    </div> <!-- / #main-navbar -->
    <!-- /2. $END_MAIN_NAVIGATION -->
    <div id="main-menu" role="navigation">
        <div id="main-menu-inner">
            <div class="menu-content top" id="menu-content-demo">
                <!-- Menu custom content demo
                         CSS:        styles/pixel-admin-less/demo.less or styles/pixel-admin-scss/_demo.scss
                         Javascript: html/assets/demo/demo.js
                -->
                <div>
                    <div class="text-bg"><span class="text-slim">Hi,</span> <span
                                class="text-semibold"><?php echo strtoupper($this->session->userdata('fullname')) ?></span>
                    </div>

                    <img src="<?php echo !empty($grav_url) ? $grav_url : ""; ?>" alt="">
                    <div class="btn-group">
                        <a href="<?= base_url() ?><?= $this->session->userdata('dept') ?>/my_settings"
                           class="btn btn-xs btn-primary btn-outline dark"><i class="fa fa-cog"></i></a>
                        <?php echo anchor($this->session->userdata('dept') . '_messages/logout', '<i class=" fa fa-power-off"></i>', array('class' => 'btn btn-xs btn-danger btn-outline dark', 'tabindex' => -1, 'onclick' => "return confirm('You are about to logout')")) ?>

                    </div>
                    <!--<a href="#" class="close">&times;</a>!-->
                </div>
            </div>
            <ul class="navigation">

                <li <?php echo $viewstatus == "dashboard" ? 'class="active"' : ""; ?>>

                    <?php echo anchor($this->session->userdata('dept') . '/index', '<i class="menu-icon fa fa-dashboard"></i><span class="mm-text">Dashboard</span>', ['tabindex' => "-1"]) ?>

                </li>

                <li <?php echo $viewstatus == "messages" ? 'class="active"' : ""; ?>>

                    <?php echo anchor($this->session->userdata('dept') . '_messages/messages', '<i class="menu-icon fa  fa-comments"></i><span class="mm-text">Messages</span>') ?>
                </li>


                <li <?php echo $viewstatus == "phonebook" ? 'class="active"' : ""; ?>>

                    <?php echo anchor($this->session->userdata('dept') . '_phonebook/phonebook', '<i class="menu-icon fa fa-book"></i><span class="mm-text">Phonebook </span>') ?>

                </li>


                <li <?php echo $viewstatus == "finanace" ? 'class="active"' : ""; ?> >

                    <?php echo anchor($this->session->userdata('dept') . '_finance/index', '<i class="menu-icon fa fa-money"></i><span class="mm-text">Finanace </span>') ?>

                </li>

                <?php if ($this->session->userdata('dept') == 'admin'): ?>
                    <li <?php echo $viewstatus == "administration" ? 'class="active"' : ""; ?> >

                        <?php echo anchor($this->session->userdata('dept') . '_administration/index', '<i class="menu-icon fa fa-users"></i><span class="mm-text">Administration </span>') ?>

                    </li>
                <?php endif; ?>

                <?php if ($this->session->userdata('dept') == 'admin'): ?>
                    <li <?php echo $viewstatus == "crm" ? 'class="active"' : ""; ?>>
                        <?php echo anchor($this->session->userdata('dept') . '_crm/index', '<i class="menu-icon fa fa-support"></i><span class="mm-text">CRM </span>') ?>

                    </li>
                    <li <?php echo $viewstatus == "system_settings" ? 'class="active"' : ""; ?>>
                        <?php echo anchor($this->session->userdata('dept') . '_system_settings/index', '<i class="menu-icon fa fa-cogs"></i><span class="mm-text">System Settings </span>') ?>

                    </li>
                <?php endif; ?>

                <li <?php echo $viewstatus == "reports" ? 'class="active"' : ""; ?>>
                    <?php echo anchor($this->session->userdata('dept') . '_reports', '<i class="menu-icon fa fa-file"></i><span class="mm-text">Summary Reports </span>') ?>

                </li>


                <li <?php echo $viewstatus == "sms_api" ? 'class="active"' : ""; ?>>
                    <?php echo anchor($this->session->userdata('dept') . '/sms_api', '<i class="menu-icon fa fa-code"></i><span class="mm-text">SMS API </span>') ?>

                </li>

            </ul> <!-- / .navigation -->

        </div> <!-- / #main-menu-inner -->
    </div> <!-- / #main-menu -->
    <!-- /4. $MAIN_MENU -->
    <div id="content-wrapper">
        <ul class="breadcrumb breadcrumb-page">
            <div class="breadcrumb-label text-light-gray">You are here:</div>
            <li>
                <?= anchor($this->session->userdata('dept') . '_messages/home', 'Dashboard') ?>
            </li>
            <?php
            if (!empty($breadcrumbs)) {
                foreach ($breadcrumbs as $breadcrumb) {
                    ?>
                    <li>
                        <?= anchor($breadcrumb['url'], $breadcrumb['title']) ?>
                    </li>
                <?php }
            }
            ?>
        </ul>
        <div class="page-header">

            <div class="row">
                <!-- Page header, center on small screens -->
                <h1 class="col-xs-7 col-sm-7 text-center text-left-sm">
                    <?php if (!empty($page_obj)) { ?>
                        <i class="fa fa-2x <?= $page_obj['icon'] ?> page-header-icon"></i>&nbsp;&nbsp;
                        <?= $page_obj['title'] ?>
                    <?php } ?>
                </h1>
                <h1 id="ct" class="col-xs-5 col-sm-5 text-center text-right-sm">

                </h1>
            </div>
        </div> <!-- / .page-header -->
        <?php if (!empty($flash)) { ?>
            <?php $this->load->view('Element/Flash/default', $flash); ?>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <?php //TODO Notes echo $this->Note->render();   ?>
            </div>
            <div class="col-md-12">