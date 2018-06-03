<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $assets ?>styles/register.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script src="<?= base_url() ?>assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
        .btn {
            margin-top: 10px;
        }

        span#recaptcha_privacy {
            display: none;
        }

        .recaptchatable #recaptcha_response_field {
            height: 30px;
            border-right: 1px solid #CCA940 !important;
        }

        .form-group {
            margin-bottom: 5px;
        }
        #result {
		   position: absolute;
		   width: 100%;
		   cursor: pointer;
		   overflow-y: auto;
		   max-height: 400px;
		   box-sizing: border-box;
		   z-index: 1001;
		}
		  .link-class:hover{
		   background-color:#f1f1f1;
		}
    </style>

</head>

<body class="register">
	<div class="box bblue">
			<div class="container-reg">
				<div class="row">
					<div id="content" class="col-sm-12 full">
						<div class="header">
							<?= $this->lang->line('user_register_form') ?>
						</div>
						<div class="row">
							<div class="login-box">
								<?php if ($this->mmode) { ?>
								<div class="alert alert-warning">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= lang('site_is_offline') ?>
								</div>
								<? }
								if ($message) { ?>
								<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= $message; ?>
								</div>
								<?php } ?>
								<?php
								if ($error) { ?>
								<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= $error; ?>
								</div>
								<?php } ?>
								<?php //echo admin_form_open("auth/register", 'class="login"'); ?>

								
								<!-- create user start -->
								<div id="content-c" class="col-lg-12">
								<div class="form-reg">
									<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
									echo admin_form_open("auth/register", $attrib);
									?>
									<!-- 	-->
									<div class="row">
										<div class="col-md-12">
											<div class="col-content">

												<div class="form-group">
												<?php echo lang('advisor', 'advisor'); ?>
													<div class="controls">
													<?php echo form_input('advisor', '', 'class="form-control" id="searchadvisor" required="required"'); ?>
													</div>
												</div>
											
												<div class="form-group">
												<?php echo lang('first_name', 'first_name'); ?>
													<div class="controls">
													<?php echo form_input('first_name', '', 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
													</div>
												</div>

												<div class="form-group">
												<?php echo lang('last_name', 'last_name'); ?>
													<div class="controls">
													<?php echo form_input('last_name', '', 'class="form-control" id="last_name" required="required"'); ?>
													</div>
												</div>

												<div class="form-group">
												<?= lang('gender', 'gender'); ?>
												<?php
												$ge[''] = array('male' => lang('male'), 'female' => lang('female'));
												echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
												?>
												</div>

												<div class="form-group">
												<?php echo lang('phone', 'phone'); ?>
													<div class="controls">
													<?php echo form_input('phone', '', 'class="form-control" id="phone" required="required"'); ?>
													</div>
												</div>

												<div class="form-group">
												<?php echo lang('email', 'email'); ?>
													<div class="controls">
														<input type="email" id="email" name="email"
															class="form-control" required="required" />
															<?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
													</div>
												</div>
												
		
												
												<div class="form-group">
												<?= lang("line", "line"); ?>
													<div class="controls">
													<?php echo form_input('line', '', 'class="form-control" id="line" '); ?>
													</div>
												</div>

												<div class="form-group">
												<?= lang("facebook", "facebook"); ?>
												<?php echo form_input('facebook', '', 'class="form-control" id="facebook" '); ?>
												</div>

												<div class="form-group">
												<?= lang("Instragram", "Instragram"); ?>
												<?php echo form_input('instragram', '', 'class="form-control" id="instragram" '); ?>
												</div>
												
												
												<div  class="form-group">
													<div style="margin-bottom: 5px;" class="">
													<?= lang("address", "address"); ?>
													<?php echo form_textarea('address', '', ' class="form-control" id="address" required="required"'); ?>
													</div>
													
													<div id="demo2" class="demo " style="display: none;" autocomplete="off">
													<div class="col-md-60">
													<div style="margin-bottom: 5px;" class="form-group03">
													<?= lang("district", "district"); ?>
													<?php echo form_input('district', '', 'autocomplete="off" class="form-control" placeholder="ตำบล" id="district" '); ?>
													</div>
													</div>
													<div style="margin-bottom: 5px;" class="col-md-60">
													<div class="form-group03">
													<?= lang("amphoe", "amphoe"); ?>
													<?php echo form_input('amphoe', '', ' autocomplete="off" class="form-control" placeholder="อำเภอ"  id="amphoe" '); ?>
													</div>
													</div>
													<div class="col-md-60">
													<div style="margin-bottom: 5px;" class="form-group03">
													<?= lang("province", "province"); ?>
													<?php echo form_input('province', '', 'autocomplete="off" class="form-control" placeholder="จังหวัด" id="province" '); ?>
													</div>
													</div>
													<div class="col-md-60">
													<div style="margin-bottom: 5px;" class="form-group03">
													<?= lang("zipcode", "zipcode"); ?>
													<?php echo form_input('zipcode', '', 'autocomplete="off" class="form-control" placeholder="รหัสไปรษณีย์" id="zipcode" '); ?>
													</div>
													</div>
													
													</div>
												</div>

												<div class="form-group">
												<?php echo lang('username', 'username'); ?>
													<div class="controls">
														<input type="text" id="username" name="username"
															class="form-control" required="required"
															pattern=".{4,20}" />
													</div>
												</div>
												<div class="form-group">
												<?php echo lang('password', 'password'); ?>
													<div class="controls">
													<?php echo form_password('password', '', 'class="form-control tip" id="password" required="required" pattern="(?=.*).{4,}" data-bv-regexp-message="'.lang('pasword_hint1').'"'); ?>
													</div>
												</div>

												<div class="form-group">
												<?php echo lang('confirm_password', 'confirm_password'); ?>
													<div class="controls">
													<?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="required" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
													</div>
												</div>
											</div>
											
											
											<div style="display:none;" class="col-md-5 col-md-offset-1">
												<div class="clearfix"></div>
												<div  class="noob">
													<div class="form-group">
													<?= lang("warehouse", "warehouse"); ?>
													<?php
													$wh[''] = lang('select').' '.lang('warehouse');
													foreach ($warehouses as $warehouse) {
														$wh[$warehouse->id] = $warehouse->name;
													}
													echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="warehouse" class="form-control select" style="width:200px;" ');
													?>
													</div>

													<div class="form-group">
													<?= lang("view_right", "view_right"); ?>
													<?php
													$vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
													echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : 1), 'id="view_right" class="form-control select" style="width:200px;"');
													?>
													</div>
													<div class="form-group">
													<?= lang("edit_right", "edit_right"); ?>
													<?php
													$opts = array(1 => lang('yes'), 0 => lang('no'));
													echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : 0), 'id="edit_right" class="form-control select" style="width:200px;"');
													?>
													</div>
													<div class="form-group">
													<?= lang("allow_discount", "allow_discount"); ?>
													<?= form_dropdown('allow_discount', $opts, (isset($_POST['allow_discount']) ? $_POST['allow_discount'] : 0), 'id="allow_discount" class="form-control select" style="width:200px;"'); ?>
													</div>

													<div class="form-group">
														<label class="control-label" for="price_group"><?php echo $this->lang->line("price_group"); ?>
														</label>
														<?php
														$pgs[''] = lang('select').' '.lang('price_group');
														foreach ($price_groups as $price_group) {
															$pgs[$price_group->id] = $price_group->name;
														}
														echo form_dropdown('price_group', $pgs, '', 'class="form-control select" id="price_group" style="width:200px;"');
														?>
													</div>
												</div>

												<div class="form-group">
													<div class="row_c">
														<div class="noob">
															<div class="panel panel-warning">
																<div class="panel-body" style="padding: 5px;">
																	<div class="col-md-12">
																		<div class="col-md-12">
																			<div class="form-group">
																				<div class="control-group">
																				<?php
																				$countbrand = sizeof($allbrand);
																				$sst = array();
																				$brands = array();
																				$brand_key = array();
																				for($i=0; $i<=$countbrand; $i++){
																					foreach($allbrand[$i] as $key => $value){
																						$sst[$key] = $value;
																					}
																					$brand_key[] = $sst['id'];
																					$brands  = $brands + array($sst['id'] => $sst['name']);
																				}
																				$brand_attr = explode(",",$user->brand_targets);
																				#print_r($brand_key);
																				if (empty($user->brand_targets)) {
																					$brand_val = $brand_key;
																				}else{
																					$brand_val = $brand_attr;
																				}
																				?>
																					<div class="controls">
																					<?= lang("select_brand", "select_brand"); ?>
																					<?= form_multiselect('brand_targets[]',$brands, $brand_val, 'id="brand-select" style="width:200px;"');?>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="clearfix"></div>
															<div class="col-md-12 hidden">
																<label><?= lang("agent"); ?> </label>
																<div class="form-group">
																	<div class="panel panel-default">
																		<div class="panel-body">
																			<div class="row">
																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="1"
																						name="agent" id="Non_stock" checked="checked"> <label
																						for="full" class="padding05"> <?= lang('Non_stock'); ?>
																					</label>
																				</div>
																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="2"
																						name="agent" id="Stock_Item"> <label for="partial"
																						class="padding05"> <?= lang('Stock_Item'); ?> </label>
																				</div>

																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="3"
																						name="agent" id="Stock_Model"> <label
																						for="partial" class="padding05"> <?= lang('Stock_Model_(VIP)'); ?>
																					</label>
																				</div>

																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="col-md-12">
																<label><?= lang("vendor_inventory"); ?> </label>
																<?php $vendor_inventory = $vendor_inventory->vendor_inventory; ?>

																<div class="form-group">
																	<div class="panel panel-default">
																		<div class="panel-body">
																			<div class="row">
																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="1"
																						name="vendor_inventory" id="stock_only"> <label
																						for="full" class="padding05"> <?= lang('stock_only'); ?>
																					</label>
																				</div>
																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="2"
																						name="vendor_inventory" id="dropship"> <label
																						for="partial" class="padding05"> <?= lang('dropship'); ?>
																					</label>
																				</div>

																				<div class="col-xs-12 col-sm-12">
																					<input type="radio" class="checkbox type" value="3"
																						name="vendor_inventory" id="stock_dropship"
																						checked="checked"> <label for="partial"
																						class="padding05"> <?= lang('stock_dropship'); ?>
																					</label>
																				</div>

																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="col-md-12">
															<?= lang('type_commission', 'type_commission'); ?>
																<div class="form-group">

																	<div class="panel panel-default">
																		<div class="panel-body">
																			<div class="row">
																				<div class="col-xs-12 col-sm-12">

																				<?php $sst = array('bronze' => lang('bronze'), 'silver' => lang('silver'), 'gold' => lang('gold'),'platinum' => lang('platinum'),'diamond' => lang('diamond'),'vip' => lang('vip'));
																				echo form_dropdown('type_commission', $sst, '', 'class="form-control input-tip" required="required" id="type"');
																				?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>
											</div>
											
											<div class="col-content">
												<div class="form-group">
													<div style="display:none;" class="col-md-8">
														<label class="checkbox" for="notify"> <input type="checkbox" name="notify" value="1" id="notify" checked="checked" /> <?= lang('notify_user_by_email') ?></label>
													</div>
													
													<div class="clearfix"></div>
													<?php echo form_submit('add_user', lang('signin_to_agent'), 'style="background-color:#1caf9a; color:#fff;height: 45px;width: 100%;" class="btn  btn-margin-top"'); ?>
								
												</div>
											</div>
										</div>
									</div>
									<?php echo form_close(); ?>
									<!-- create user end -->
									<div class="clearfix" style="height: 10px;"></div>
		
										<table class="or">
											<tbody><tr>
												<td><div class="pslogin-border"></div></td>
												<td class="pslogin-bordertext w25"><?php echo lang('or'); ?></td>
												<td><div class="pslogin-border"></div></td>
											</tr>
										</tbody></table>
	
									<a href="login" class="col-xs-12 back_to_login"><?php echo lang('login'); ?></a>
									<!--<a class="pull-right" href="page-register.html">Sign Up!</a>-->
								</div>
								<div class="clearfix"></div>
								<p class="legal">© 2017 All Rights Reserved. Proudly created with <a title="smith ระบบตัวแทน" href="https://www.atcreative.co.th/smith/" target="__blank"><img style="width: 16px;" src="<?= $assets ?>images/copyright_while.png"></a></p>
							</div>
						</div>
					</div>
				</div>
			</div>


			<script src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
			<script src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
			<script src="<?= $assets ?>js/bootstrap.min.js"></script>
			<script>
				$(document).ready(function () {
					$('.reload-captcha').click(function (event) {
						event.preventDefault();
						$.ajax({
							url: '<?=base_url();?>auth/reload_captcha',
							success: function (data) {
								$('.captcha-image').html(data);
							}
						});
					});
				});
			</script>
	</div>
	</div>
</body>
</html>


<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('.no').slideUp();
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
		
		$( "input#more" ).change(function() {
			if($(this).prop( "checked" )){
				$('#more-con').show();
			}else{
				$('#more-con').hide();
			}
		});
    });
</script>

<script src="<?= $assets ?>js/jquery.Thailand.js/jquery.Thailand.js/dependencies/zip.js/zip.js"></script>
<script src="<?= $assets ?>js/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script src="<?= $assets ?>js/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<script src="<?= $assets ?>js/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
<script type="text/javascript">
    $.Thailand({
        $district: $('#demo2 [name="district"]'),
        $amphoe: $('#demo2 [name="amphoe"]'),
        $province: $('#demo2 [name="province"]'),
        $zipcode: $('#demo2 [name="zipcode"]'),

        onDataFill: function(data){
            //console.info('Data Filled', data);
        },

        onLoad: function(){
            //console.info('Autocomplete is ready!');
            $('#loader, .demo').toggle();
        }
    });

    $(document).ready(function(){
		$.ajaxSetup({ cache: false });
			$('#searchadvisor').keyup(function(){
				$('#result').html('');
				$('#state').val('');
				var searchField = $('#searchadvisor').val();
				var expression = new RegExp(searchField, "i");
				$.getJSON('data.json', function(data) {
					$.each(data, function(key, value){
					    if (value.name.search(expression) != -1 || value.location.search(expression) != -1)
					    {
					     $('#result').append('<li class="list-group-item link-class"><img src="'+value.image+'" height="40" width="40" class="img-thumbnail" /> '+value.name+' | <span class="text-muted">'+value.location+'</span></li>');
					    }
					});   
				});
			});
		$('#result').on('click', 'li', function() {
			var click_text = $(this).text().split('|');
			$('#searchadvisor').val($.trim(click_text[0]));
			$("#result").html('');
		});
	});

</script>