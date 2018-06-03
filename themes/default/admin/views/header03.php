<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= admin_url() ?>"/>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="HandheldFriendly" content="true" />
	
    <title><?= $page_title ?> - <?= $Settings->site_name ?></title>
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <?php if ($Settings->user_rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
        </script>
    <?php } ?>
	<link href="<?php echo $assets; ?>styles/style.default.css" rel="stylesheet" type= text/css>
    <script type="text/javascript">
       $(window).load(function () {
            $("#preloader").fadeOut("slow");
        });
    </script>
</head>
   
<body lang="<?php echo $Settings->user_language; ?>">
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<div id="app_wrapper">
<section>
  <div class="leftpanel">
    <div class="logopanel">
        <h1><a class="navbar-brand-renew" href="<?= admin_url() ?>"><span class="logo"><img title="Agent Smith" alt="Agent Smith" src="<?php echo base_url().'assets/uploads/logos/Logo-SMITH1.png'; ?>" /></span></a></h1>
    </div><!-- logopanel -->
        
<div class="leftpanelinner">    
        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg"> 
		
            <div class="media userlogged">
                <img alt="" src="<?= $this->session->userdata('avatar') ? base_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : base_url('assets/images/' . $this->session->userdata('gender') . '.png'); ?>" class="media-object">
                <div class="media-body">
                    <h4><?= $this->session->userdata('username'); ?></h4>
                    <span><?= lang('welcome') ?></span></span>
                </div>
            </div>
        </div>
      
      <h5 class="sidebartitle"><?= lang('Navigation'); ?></h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
                        <li class="mm_welcome">
                            <a href="<?= admin_url() ?>">
                                <i class="fa fa-dashboard"></i>
                                <span class="text"> <?= lang('dashboard'); ?></span>
                            </a>
                        </li>

                        <?php
                        if ($Owner || $Admin) {
                            ?>
                             <li class="nav-parent mm_sales  <?= strtolower($this->router->fetch_method()) == 'sales' ? 'mm_pos' : '' ?>">
                                <a class="" href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="text"> <?= lang('sales'); ?>
                                    </span> 
                                </a>
                                <ul class="children">
                                    <li id="sales_index">
                                        <a class="submenu" href="<?= admin_url('sales'); ?>">
                                            <i class="fa fa-list"></i>
                                            <span class="text"> <?= lang('list_sales'); ?></span>
                                        </a>
                                    </li>
                                    <?php if (POS) { ?>
                                    <li id="pos_sales">
                                        <a class="submenu" href="<?= admin_url('pos/sales'); ?>">
                                            <i class="fa fa-cube"></i>
                                            <span class="text"> <?= lang('pos_sales'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li id="sales_add">
                                        <a class="submenu" href="<?= admin_url('sales/add'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_sale'); ?></span>
                                        </a>
                                    </li>
                                    <li id="sales_sale_by_csv">
                                        <a class="submenu" href="<?= admin_url('sales/sale_by_csv'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_sale_by_csv'); ?></span>
                                        </a>
                                    </li>
									
                                    <?php /*<li id="sales_deliveries">
                                        <a class="submenu" href="<?= admin_url('sales/deliveries'); ?>">
                                            <i class="fa fa-truck"></i>
                                            <span class="text"> <?= lang('deliveries'); ?></span>
                                        </a>
                                    </li>
									*/	?>
                                </ul>
                            </li>
							
							<li id="reports_payments" class="mm_reports_payments">
                                        <a href="<?= admin_url('reports/payments') ?>">
                                            <i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
                                        </a>
                                    </li>
							</li>
							
							<li class="mm_sales_deliveries">
                                        <a  href="<?= admin_url('sales/deliveries'); ?>">
                                            <i class="fa fa-truck"></i>
                                            <span class="text"> <?= lang('deliveries'); ?></span>
                                        </a>
                            </li>
							
							
							
							<li class="nav-parent mm_products">
                                <a class="" href="#">
                                    <i class="fa fa-cubes"></i>
                                    <span class="text"> <?= lang('products'); ?> </span>
                                    
                                </a>
                                <ul class="children">
                                    <li id="products_index">
                                        <a class="submenu" href="<?= admin_url('products'); ?>">
                                            <i class="fa fa-list"></i>
                                            <span class="text"> <?= lang('list_products'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_add">
                                        <a class="submenu" href="<?= admin_url('products/add'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_product'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_import_csv">
                                        <a class="submenu" href="<?= admin_url('products/import_csv'); ?>">
                                            <i class="fa fa-file-text"></i>
                                            <span class="text"> <?= lang('import_products'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_print_barcodes">
                                        <a class="submenu" href="<?= admin_url('products/print_barcodes'); ?>">
                                            <i class="fa fa-barcode"></i>
                                            <span class="text"> <?= lang('print_barcode_label'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_quantity_adjustments">
                                        <a class="submenu" href="<?= admin_url('products/quantity_adjustments'); ?>">
                                            <i class="fa fa-filter"></i>
                                            <span class="text"> <?= lang('quantity_adjustments'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_add_adjustment">
                                        <a class="submenu" href="<?= admin_url('products/add_adjustment'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_adjustment'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_stock_counts">
                                        <a class="submenu" href="<?= admin_url('products/stock_counts'); ?>">
                                            <i class="fa fa-list-ol"></i>
                                            <span class="text"> <?= lang('stock_counts'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_count_stock">
                                        <a class="submenu" href="<?= admin_url('products/count_stock'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('count_stock'); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

							
							
							
							
							
						
							
							
							
							
							
							
							<li class="nav-parent mm_reports">
                                <a class="" href="#">
                                    <i class="fa fa-bar-chart-o"></i>
                                    <span class="text"> <?= lang('reports'); ?> </span>
                                    
                                </a>
                                <ul class="children">
                                    <li id="reports_index">
                                        <a href="<?= admin_url('reports') ?>">
                                            <i class="fa fa-area-chart"></i><span class="text"> <?= lang('overview_chart'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_warehouse_stock">
                                        <a href="<?= admin_url('reports/warehouse_stock') ?>">
                                            <i class="fa fa-cubes"></i><span class="text"> <?= lang('warehouse_stock'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_best_sellers">
                                        <a href="<?= admin_url('reports/best_sellers') ?>">
                                            <i class="fa fa-line-chart"></i><span class="text"> <?= lang('best_sellers'); ?></span>
                                        </a>
                                    </li>
                                    <?php if (POS) { ?>
                                    <li id="reports_register">
                                        <a href="<?= admin_url('reports/register') ?>">
                                            <i class="fa fa-align-left"></i><span class="text"> <?= lang('register_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li id="reports_quantity_alerts">
                                        <a href="<?= admin_url('reports/quantity_alerts') ?>">
                                            <i class="fa fa-cube"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
                                        </a>
                                    </li>
                                    <?php if ($Settings->product_expiry) { ?>
                                    <li id="reports_expiry_alerts">
                                        <a href="<?= admin_url('reports/expiry_alerts') ?>">
                                            <i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li id="reports_products">
                                        <a href="<?= admin_url('reports/products') ?>">
                                            <i class="fa fa-cubes"></i><span class="text"> <?= lang('products_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_adjustments">
                                        <a href="<?= admin_url('reports/adjustments') ?>">
                                            <i class="fa fa-filter"></i><span class="text"> <?= lang('adjustments_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_categories">
                                        <a href="<?= admin_url('reports/categories') ?>">
                                            <i class="fa fa-sitemap"></i><span class="text"> <?= lang('categories_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_brands">
                                        <a href="<?= admin_url('reports/brands') ?>">
                                            <i class="fa fa-th-list"></i><span class="text"> <?= lang('brands_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_daily_sales">
                                        <a href="<?= admin_url('reports/daily_sales') ?>">
                                            <i class="fa fa-calendar"></i><span class="text"> <?= lang('daily_sales'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_monthly_sales">
                                        <a href="<?= admin_url('reports/monthly_sales') ?>">
                                            <i class="fa fa-calendar"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_sales">
                                        <a href="<?= admin_url('reports/sales') ?>">
                                            <i class="fa fa-shopping-cart"></i><span class="text"> <?= lang('sales_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_commission">
                                        <a href="<?= admin_url('reports/commission') ?>">
                                            <i class="glyphicon glyphicon-hand-right"></i></i><span class="text"><?= lang('commission_report'); ?></span>
                                        </a>
                                    </li>
									<?php /*
                                    <li id="reports_payments">
                                        <a href="<?= admin_url('reports/payments') ?>">
                                            <i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
                                        </a>
                                    </li>
									*/	?>
                                    <li id="reports_profit_loss">
                                        <a href="<?= admin_url('reports/profit_loss') ?>">
                                            <i class="fa fa-pie-chart"></i><span class="text"> <?= lang('profit_and_loss'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_daily_purchases">
                                        <a href="<?= admin_url('reports/daily_purchases') ?>">
                                            <i class="fa fa-calendar"></i><span class="text"> <?= lang('daily_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_monthly_purchases">
                                        <a href="<?= admin_url('reports/monthly_purchases') ?>">
                                            <i class="fa fa-calendar"></i><span class="text"> <?= lang('monthly_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_purchases">
                                        <a href="<?= admin_url('reports/purchases') ?>">
                                            <i class="fa fa-tag"></i><span class="text"> <?= lang('purchases_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_expenses">
                                        <a href="<?= admin_url('reports/expenses') ?>">
                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('expenses_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_customer_report">
                                        <a href="<?= admin_url('reports/customers') ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_supplier_report">
                                        <a href="<?= admin_url('reports/suppliers') ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_staff_report">
                                        <a href="<?= admin_url('reports/users') ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('staff_report'); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
							
							
							<!-- purchases -->
							
							<li class="nav-parent mm_purchases">
                                <a class="" href="#">
                                    <i class="fa fa-tags"></i>
                                    <span class="text"> <?= lang('purchases'); ?>
                                    </span> 
                                </a>
                                <ul class="children">
                                    <li id="purchases_index">
                                        <a class="submenu" href="<?= admin_url('purchases'); ?>">
                                            <i class="fa fa-list"></i>
                                            <span class="text"> <?= lang('list_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <li id="purchases_add">
                                        <a class="submenu" href="<?= admin_url('purchases/add'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_purchase'); ?></span>
                                        </a>
                                    </li>
                                    <li id="purchases_purchase_by_csv">
                                        <a class="submenu" href="<?= admin_url('purchases/purchase_by_csv'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_purchase_by_csv'); ?></span>
                                        </a>
                                    </li>
                                    <li id="purchases_expenses">
                                        <a class="submenu" href="<?= admin_url('purchases/expenses'); ?>">
                                            <i class="fa fa-dollar"></i>
                                            <span class="text"> <?= lang('list_expenses'); ?></span>
                                        </a>
                                    </li>
                                    <li id="purchases_add_expense">
                                        <a class="submenu" href="<?= admin_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_expense'); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

							
							<!-- product transfers -->
							 <li class="nav-parent mm_transfers">
                                <a class="" href="#">
                                    <i class="fa fa-refresh"></i>
                                    <span class="text"> <?= lang('transfers'); ?> </span>
                                    
                                </a>
                                <ul class="children">
                                    <li id="transfers_index">
                                        <a class="submenu" href="<?= admin_url('transfers'); ?>">
                                            <i class="fa fa-list"></i><span class="text"> <?= lang('list_transfers'); ?></span>
                                        </a>
                                    </li>
                                    <li id="transfers_add">
                                        <a class="submenu" href="<?= admin_url('transfers/add'); ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
                                        </a>
                                    </li>
                                    <li id="transfers_purchase_by_csv">
                                        <a class="submenu" href="<?= admin_url('transfers/transfer_by_csv'); ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer_by_csv'); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
							
							
							
							<!-- list_customers -->
							
							
							
							<li class="mm_list_customers mm_customers nav-parent">
                                        <a class="submenu" href="#">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('customers'); ?></span>
                                        </a>
									<ul class="children">	
									<li id="customers_index">
                                        <a class="submenu" href="<?= admin_url('customers'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
                                        </a>
                                    </li>
                                    <li id="customers_index">
                                        <a class="submenu" href="<?= admin_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer'); ?></span>
                                        </a>
                                    </li>
                                    </ul>
                            </li>
							
							
							
							<!-- list_users -->
							<?php if ($Owner) { ?>
							<li class="mm_list_users mm_auth nav-parent">
                                    <a class="submenu" href="#">
										<i class="fa fa-users"></i><span class="text"> <?= lang('account_users'); ?></span>
									</a>
									<ul class="children">	
										<li id="auth_users">
											<a class="submenu" href="<?= admin_url('users'); ?>">
												<i class="fa fa-users"></i><span class="text"> <?= lang('list_users'); ?></span>
											</a>
										</li>
										<li id="auth_create_user">
											<a class="submenu" href="<?= admin_url('users/create_user'); ?>">
												<i class="fa fa-user-plus"></i><span class="text"> <?= lang('new_user'); ?></span>
											</a>
										</li>
                                    </ul>
                            </li>
							<?php } ?>
							<!-- list_suppliers -->
							
							 <li class="mm_list_billers mm_suppliers nav-parent">
                                        <a class="submenu" href="#">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('suppliers'); ?></span>
                                        </a>
									<ul class="children">
									<li id="suppliers_index">
                                        <a class="submenu" href="<?= admin_url('suppliers'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('list_suppliers'); ?></span>
                                        </a>
                                    </li>
                                    <li id="suppliers_index">
                                        <a class="submenu" href="<?= admin_url('suppliers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier'); ?></span>
                                        </a>
                                    </li>
                                    </ul>
                             </li>
							
							
							<!-- notifications -->
							
							<li class="mm_notifications">
                                <a class="submenu" href="<?= admin_url('notifications'); ?>">
                                    <i class="fa fa-info-circle"></i><span class="text"> <?= lang('notifications'); ?></span>
                                </a>
                            </li>
							
							
                            <li class="nav-parent">
                                <a class="" href="#">
                                <i class="fa fa-users"></i>
                                <span class="text"> <?= lang('people'); ?> </span>
                                </a>

                                <ul class="children">
                                    <li id="pointreward_index">
                                        <a class="submenu" href="<?= admin_url('award_points/add'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('award_points'); ?></span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            
                            
                           
                            
                            
                            
                            
                            
                            <?php if ($Owner) { ?>
                                <li class="nav-parent mm_banktransfer mm_system_settings <?= strtolower($this->router->fetch_method()) == 'sales' ? '' : 'mm_pos' ?>">
                                    <a class="" href="#">
                                        <i class="fa fa-cog"></i><span class="text"> <?= lang('settings'); ?> </span>
                                        
                                    </a>
                                    <ul class="children">
                                        <li id="system_settings_index">
                                            <a href="<?= admin_url('system_settings') ?>">
                                                <i class="fa fa-cogs"></i><span class="text"> <?= lang('system_settings'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_shipping_matrixrate">
                                            <a href="<?= admin_url('system_settings/shipping_matrixrate') ?>">
                                                <i class="fa fa-truck" aria-hidden="true"></i><span class="text"> <?= lang('shipping_method'); ?></span>
                                            </a>
                                        </li>
										
                                        <li id="system_settings_commission">
                                            <a href="<?= admin_url('system_settings/commission') ?>">
                                                <i class="fa fa-money" aria-hidden="true"></i><span class="text"> <?= lang('commission'); ?></span>
                                            </a>
                                        </li>
										
                                        <?php if (POS) { ?>
                                        <li id="pos_settings">
                                            <a href="<?= admin_url('pos/settings') ?>">
                                                <i class="fa fa-th-large"></i><span class="text"> <?= lang('pos_settings'); ?></span>
                                            </a>
                                        </li>
                                        <li id="pos_printers">
                                            <a href="<?= admin_url('pos/printers') ?>">
                                                <i class="fa fa-print"></i><span class="text"> <?= lang('list_printers'); ?></span>
                                            </a>
                                        </li>
                                        <li id="pos_add_printer">
                                            <a href="<?= admin_url('pos/add_printer') ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_printer'); ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li id="system_settings_change_logo">
                                            <a href="<?= admin_url('system_settings/change_logo') ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-picture-o"></i><span class="text"> <?= lang('change_logo'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_currencies">
                                            <a href="<?= admin_url('system_settings/currencies') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('currencies'); ?></span>
                                            </a>
                                        </li>
                                         <li id="banktransfer_index">
                                            <a  href="<?= admin_url('banktransfer'); ?>">
                                                <i class="fa fa-bars" aria-hidden="true"></i><span class="text"> <?= lang('bank_account'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_customer_groups">
                                            <a href="<?= admin_url('system_settings/customer_groups') ?>">
                                                <i class="fa fa-users"></i><span class="text"> <?= lang('customer_groups'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_price_groups">
                                            <a href="<?= admin_url('system_settings/price_groups') ?>">
                                                <i class="fa fa-dollar"></i><span class="text"> <?= lang('price_groups'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_categories">
                                            <a href="<?= admin_url('system_settings/categories') ?>">
                                                <i class="fa fa-sitemap"></i><span class="text"> <?= lang('categories'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_expense_categories">
                                            <a href="<?= admin_url('system_settings/expense_categories') ?>">
                                                <i class="fa fa-sitemap"></i><span class="text"> <?= lang('expense_categories'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_units">
                                            <a href="<?= admin_url('system_settings/units') ?>">
                                                <i class="fa fa-cube"></i><span class="text"> <?= lang('units'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_brands">
                                            <a href="<?= admin_url('system_settings/brands') ?>">
                                                <i class="fa fa-th-list"></i><span class="text"> <?= lang('brands'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_variants">
                                            <a href="<?= admin_url('system_settings/variants') ?>">
                                                <i class="fa fa-circle"></i><span class="text"> <?= lang('variants'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_tax_rates">
                                            <a href="<?= admin_url('system_settings/tax_rates') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('tax_rates'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_warehouses">
                                            <a href="<?= admin_url('system_settings/warehouses') ?>">
                                                <i class="fa fa-cubes"></i><span class="text"> <?= lang('warehouses'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_email_templates">
                                            <a href="<?= admin_url('system_settings/email_templates') ?>">
                                                <i class="fa fa-envelope"></i><span class="text"> <?= lang('email_templates'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_user_groups">
                                            <a href="<?= admin_url('system_settings/user_groups') ?>">
                                                <i class="fa fa-key"></i><span class="text"> <?= lang('group_permissions'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_backups">
                                            <a href="<?= admin_url('system_settings/backups') ?>">
                                                <i class="fa fa-database"></i><span class="text"> <?= lang('backups'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                            

                            
                           
                            

                            
                            

                            
                            
                           
                            
                            
                            <?php if ($Owner && file_exists(APPPATH.'controllers'.DIRECTORY_SEPARATOR.'shop'.DIRECTORY_SEPARATOR.'Shop.php')) { ?>
                            <li class="nav-parent mm_shop_settings mm_api_settings">
                                <a class="" href="#">
                                    <i class="fa fa-shopping-cart"></i><span class="text"> <?= lang('front_end'); ?> </span>
                                    
                                </a>
                                <ul class="children">
                                    <li id="shop_settings_index">
                                        <a href="<?= admin_url('shop_settings') ?>">
                                            <i class="fa fa-cog"></i><span class="text"> <?= lang('shop_settings'); ?></span>
                                        </a>
                                    </li>
                                    <li id="shop_settings_slider">
                                        <a href="<?= admin_url('shop_settings/slider') ?>">
                                            <i class="fa fa-file"></i><span class="text"> <?= lang('slider_settings'); ?></span>
                                        </a>
                                    </li>
                                    <?php if ($this->Settings->apis) { ?>
                                    <li id="api_settings_index">
                                        <a href="<?= admin_url('api_settings') ?>">
                                            <i class="fa fa-key"></i><span class="text"> <?= lang('api_keys'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <li id="shop_settings_pages">
                                        <a href="<?= admin_url('shop_settings/pages') ?>">
                                            <i class="fa fa-file"></i><span class="text"> <?= lang('list_pages'); ?></span>
                                        </a>
                                    </li>
                                    <li id="shop_settings_pages">
                                        <a href="<?= admin_url('shop_settings/add_page') ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_page'); ?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php } ?>

                        <?php
                        } else { // not owner and not admin
                            ?>
                            

                            <?php if ($GP['sales-index'] || $GP['sales-add'] || $GP['sales-gift_cards']) { ?>
                            <li class="nav-parent mm_sales <?= strtolower($this->router->fetch_method()) == 'sales' ? 'mm_pos' : '' ?>">
                                <a class="" href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="text"> <?= lang('sales'); ?>
                                    </span> 
                                </a>
                                <ul class="children">
                                    <li id="sales_index">
                                        <a class="submenu" href="<?= admin_url('sales'); ?>">
                                            <i class="fa fa-list"></i><span class="text"> <?= lang('list_sales'); ?></span>
                                        </a>
                                    </li>
                                    <?php if ($GP['sales-add']) { ?>
                                    <li id="sales_add">
                                        <a class="submenu" href="<?= admin_url('sales/add'); ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_sale'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>

                            <?php if ($GP['customers-index'] || $GP['customers-add'] || $GP['suppliers-index'] || $GP['suppliers-add']) { ?>
                                <li class="nav-parent mm_customers mm_auth mm_customers mm_suppliers mm_billers">
                                    <a class="" href="#">
                                        <i class="fa fa-users"></i>
                                        <span class="text"> <?= lang('customer'); ?> </span>
                                        
                                    </a>
                                    <ul class="children">
                                        <?php if ($GP['customers-index']) { ?>
                                        <li id="customers_index">
                                            <a class="submenu" href="<?= admin_url('customers'); ?>">
                                                <i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
                                            </a>
                                        </li>
                                        <?php }
                                        if ($GP['customers-add']) { ?>
                                        <li id="customers_index">
                                            <a class="submenu" href="<?= admin_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer'); ?></span>
                                            </a>
                                        </li>
                                        <?php }
                                        if ($GP['suppliers-index']) { ?>
                                        <li id="suppliers_index">
                                            <a class="submenu" href="<?= admin_url('suppliers'); ?>">
                                                <i class="fa fa-users"></i><span class="text"> <?= lang('list_suppliers'); ?></span>
                                            </a>
                                        </li>
                                        <?php }
                                        if ($GP['suppliers-add']) { ?>
                                        <li id="suppliers_index">
                                            <a class="submenu" href="<?= admin_url('suppliers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier'); ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                             <?php } ?>
                            
                            
                            <?php if ($GP['transfers-index'] || $GP['transfers-add']) { ?>
                                <li class="nav-parent mm_transfers">
                                    <a class="" href="#">
                                        <i class="fa fa-refresh"></i>
                                        <span class="text"> <?= lang('transfers'); ?> </span>
                                        
                                    </a>
                                    <ul class="children">
                                        <li id="transfers_index">
                                            <a class="submenu" href="<?= admin_url('transfers'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_transfers'); ?></span>
                                            </a>
                                        </li>
                                        <?php if ($GP['transfers-add']) { ?>
                                        <li id="transfers_add">
                                            <a class="submenu" href="<?= admin_url('transfers/add'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                            
                            <?php if ($GP['products-index'] || $GP['products-add'] || $GP['products-barcode'] || $GP['products-adjustments'] || $GP['products-stock_count']) { ?>
                            <li class="nav-parent mm_products">
                                <a class="" href="#">
                                    <i class="fa fa-cubes"></i>
                                    <span class="text"> <?= lang('products'); ?>
                                    </span> 
                                </a>
                                <ul class="children">
                                    <li id="products_index">
                                        <a class="submenu" href="<?= admin_url('products'); ?>">
                                            <i class="fa fa-list"></i><span class="text"> <?= lang('list_products'); ?></span>
                                        </a>
                                    </li>
                                    <?php if ($GP['products-add']) { ?>
                                    <li id="products_add">
                                        <a class="submenu" href="<?= admin_url('products/add'); ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_product'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($GP['products-barcode']) { ?>
                                    <li id="products_sheet">
                                        <a class="submenu" href="<?= admin_url('products/print_barcodes'); ?>">
                                            <i class="fa fa-barcode"></i><span class="text"> <?= lang('print_barcode_label'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($GP['products-adjustments']) { ?>
                                    <li id="products_quantity_adjustments">
                                        <a class="submenu" href="<?= admin_url('products/quantity_adjustments'); ?>">
                                            <i class="fa fa-filter"></i><span class="text"> <?= lang('quantity_adjustments'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_add_adjustment">
                                        <a class="submenu" href="<?= admin_url('products/add_adjustment'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_adjustment'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($GP['products-stock_count']) { ?>
                                    <li id="products_stock_counts">
                                        <a class="submenu" href="<?= admin_url('products/stock_counts'); ?>">
                                            <i class="fa fa-list-ol"></i>
                                            <span class="text"> <?= lang('stock_counts'); ?></span>
                                        </a>
                                    </li>
                                    <li id="products_count_stock">
                                        <a class="submenu" href="<?= admin_url('products/count_stock'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('count_stock'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            
							
							
                             <li>
                                        <a href="<?= admin_url('reports/daily_sales') ?>">
                                            <i class="fa fa-calendar"></i><span class="text"> <?= lang('daily_sales'); ?></span>
                                        </a>
                            </li>
							
							
							
							<li id="reports_monthly_sales">
                                        <a href="<?= admin_url('reports/monthly_sales') ?>">
                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
                                        </a>
                            </li>

                            <?php if ($GP['purchases-index'] || $GP['purchases-add'] || $GP['purchases-expenses']) { ?>
                            <li class="nav-parent mm_purchases">
                                <a class="" href="#">
                                    <i class="fa fa-tags"></i>
                                    <span class="text"> <?= lang('purchases'); ?>
                                    </span> 
                                </a>
                                <ul class="children">
                                    <li id="purchases_index">
                                        <a class="submenu" href="<?= admin_url('purchases'); ?>">
                                            <i class="fa fa-list"></i><span class="text"> <?= lang('list_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <?php if ($GP['purchases-add']) { ?>
                                    <li id="purchases_add">
                                        <a class="submenu" href="<?= admin_url('purchases/add'); ?>">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_purchase'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($GP['purchases-expenses']) { ?>
                                    <li id="purchases_expenses">
                                        <a class="submenu" href="<?= admin_url('purchases/expenses'); ?>">
                                            <i class="fa fa-dollar"></i><span class="text"> <?= lang('list_expenses'); ?></span>
                                        </a>
                                    </li>
                                    <li id="purchases_add_expense">
                                        <a class="submenu" href="<?= admin_url('purchases/add_expense'); ?>"
                                            data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_expense'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>

                            

                            

                            <?php if ($GP['reports-quantity_alerts'] || $GP['reports-expiry_alerts'] || $GP['reports-products'] || $GP['reports-monthly_sales'] || $GP['reports-sales'] || $GP['reports-payments'] || $GP['reports-purchases'] || $GP['reports-customers'] || $GP['reports-suppliers'] || $GP['reports-expenses']) { ?>
                            <li class="nav-parent mm_reports">
                                <a class="" href="#">
                                    <i class="fa fa-bar-chart-o"></i>
                                    <span class="text"> <?= lang('reports'); ?> </span>
                                    
                                </a>
                                <ul class="children">
                                    <?php if ($GP['reports-quantity_alerts']) { ?>
                                    <li id="reports_quantity_alerts">
                                        <a href="<?= admin_url('reports/quantity_alerts') ?>">
                                            <i class="fa fa-cube"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-expiry_alerts']) { ?>
                                    <?php if ($Settings->product_expiry) { ?>
                                    <li id="reports_expiry_alerts">
                                        <a href="<?= admin_url('reports/expiry_alerts') ?>">
                                            <i class="fa fa-cube"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php }
                                    if ($GP['reports-products']) { ?>
                                    <li id="reports_products">
                                        <a href="<?= admin_url('reports/products') ?>">
                                            <i class="fa fa-cubes"></i><span class="text"> <?= lang('products_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_adjustments">
                                        <a href="<?= admin_url('reports/adjustments') ?>">
                                            <i class="fa fa-filter"></i><span class="text"> <?= lang('adjustments_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_categories">
                                        <a href="<?= admin_url('reports/categories') ?>">
                                            <i class="fa fa-sitemap"></i><span class="text"> <?= lang('categories_report'); ?></span>
                                        </a>
                                    </li>
                                    <li id="reports_brands">
                                        <a href="<?= admin_url('reports/brands') ?>">
                                            <i class="fa fa-th-list"></i><span class="text"> <?= lang('brands_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-daily_sales']) { ?>
                                    <li id="reports_daily_sales">
                                        <a href="<?= admin_url('reports/daily_sales') ?>">
                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_sales'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-monthly_sales']) { ?>
                                    <li id="reports_monthly_sales">
                                        <a href="<?= admin_url('reports/monthly_sales') ?>">
                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-sales']) { ?>
                                    <li id="reports_sales">
                                        <a href="<?= admin_url('reports/sales') ?>">
                                            <i class="fa fa-shopping-cart"></i><span class="text"> <?= lang('sales_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-payments']) { ?>
                                    <li id="reports_payments">
                                        <a href="<?= admin_url('reports/payments') ?>">
                                            <i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-daily_purchases']) { ?>
                                    <li id="reports_daily_purchases">
                                        <a href="<?= admin_url('reports/daily_purchases') ?>">
                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-monthly_purchases']) { ?>
                                    <li id="reports_monthly_purchases">
                                        <a href="<?= admin_url('reports/monthly_purchases') ?>">
                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_purchases'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-purchases']) { ?>
                                    <li id="reports_purchases">
                                        <a href="<?= admin_url('reports/purchases') ?>">
                                            <i class="fa fa-tag"></i><span class="text"> <?= lang('purchases_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-expenses']) { ?>
                                    <li id="reports_expenses">
                                        <a href="<?= admin_url('reports/expenses') ?>">
                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('expenses_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-customers']) { ?>
                                    <li id="reports_customer_report">
                                        <a href="<?= admin_url('reports/customers') ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php }
                                    if ($GP['reports-suppliers']) { ?>
                                    <li id="reports_supplier_report">
                                        <a href="<?= admin_url('reports/suppliers') ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        <?php } ?>
      </ul>
    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->
  

  <div class="mainpanel">
    <div class="headerbar">
      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <form id="idsalesearch" class="searchform searchform-hidden" action="<?= admin_url('sales'); ?>" method="get">
        <input id ="salesearch" type="text" class="form-control" name="q" placeholder= "<?php echo lang('search_here...'); ?>" />
		<input id="submit" type="submit" value="Submit">
	  </form>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
              <button class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown">
                <img class="flag" src="<?= base_url('assets/images/' . $Settings->user_language . '.png'); ?>" alt="">
              </button>
              <div class="dropdown-menu dropdown-menu-head pull-right">
                <h5 class="title"><?= lang('select_language') ?></h5>
                <ul class="dropdown-list user-list">
				<?php $scanned_lang_dir = array_map(function ($path) {
                    return basename($path);
                }, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
                foreach ($scanned_lang_dir as $entry) { ?>	
                <li class="language">
                    <div class="thumb"><a href="<?= admin_url('welcome/language/' . $entry); ?>"><img src="<?= base_url('assets/images/'.$entry.'.png'); ?>" class="language-img"></a></div>
                    <div class="desc">
                      <h5><a href="<?= admin_url('welcome/language/' . $entry); ?>">
                       &nbsp;&nbsp;<?= ucwords($entry); ?></a></h5>
                    </div>
                  </li>
				<?php } ?>
                </ul>
              </div>
            </div>
          </li>

          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img alt="" src="<?= $this->session->userdata('avatar') ? base_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : base_url('assets/images/' . $this->session->userdata('gender') . '.png'); ?>" class="mini_avatar img-rounded">
                <?= lang('welcome') ?> <?= $this->session->userdata('username'); ?></span>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="<?= admin_url('users/profile/' . $this->session->userdata('user_id')); ?>"><i class="fa fa-user"></i> <?= lang('profile'); ?></a></li>
                <li><a href="<?= admin_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fa fa-key"></i> <?= lang('change_password'); ?></a></li>
                <li><a href="<?= admin_url('logout'); ?>"><i class="fa fa-sign-out"></i> <?= lang('logout'); ?></a></li>
              </ul>
            </div>
          </li>
        </ul>
      </div><!-- header-right -->
    </div><!-- headerbar -->

	
    <div class="pageheader">
      <h2><em class="icon"></em><?php echo $bc[sizeof($bc) - 1]['page']; ?></h2>
      <div class="breadcrumb-wrapper">
		<span class="label"><?=lang('You are here:'); ?></span>
        <ol class="breadcrumb">
            <?php
				$ch_noti = 0;
                foreach ($bc as $b) {
                    if ($b['link'] === '#') {
						$ch_noti += 1;
                        echo '<li class="active">' . $b['page'] . '</li>';
                    } else {
						$ch_noti += 1;
                        echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
                    }
                }
            ?>
        </ol>
      </div>
    </div>
	
    <div class="contentpanel">
    <div class="" id="container">
        <div id="main-con">
        <div class="lt"><div>
			<div class="content-content">
            <div id="content" class="panel-body panel-body-nopadding">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($message) { ?>
                            <div class="alert alert-success">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <?= $message; ?>
                            </div>
                        <?php } ?>
                        <?php if ($error) { ?>
                            <div class="alert alert-danger">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <?= $error; ?>
                            </div>
                        <?php } ?>
                        <?php if ($warning) { ?>
                            <div class="alert alert-warning">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <?= $warning; ?>
                            </div>
                        <?php } ?>
                        <?php
                        if ($info && $ch_noti <= 1) {
                            foreach ($info as $n) {
                                if (!$this->session->userdata('hidden' . $n->id)) {
                                    ?>
                                    <div class="alert alert-info">
                                        <a href="#" id="<?= $n->id ?>" class="close hideComment external"
                                           data-dismiss="alert">&times;</a>
                                        <?= $n->comment; ?>
                                    </div>
                                <?php }
                            }
                        } ?>
                        <div class="alerts-con"></div>