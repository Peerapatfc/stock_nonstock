<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
    content: "\f0c0";
}
</style>
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_user'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('create_user'); ?></p>

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open("auth/create_user", $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
						
                            <div class="form-group1">
                                <?php echo lang('seller_id', 'seller_id'); ?>
                                <div class="controls">
                                    <?php echo form_input('seller_id', '', 'class="form-control" id="seller_id"'); ?>
                                </div>
                            </div>
							


						
                            <div class="form-group1">
                                <?php echo lang('first_name', 'first_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', '', 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group1">
                                <?php echo lang('last_name', 'last_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', '', 'class="form-control" id="last_name" required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group1">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>

                            <div class="form-group1">
                                <?php echo lang('company', 'company'); ?>
                                <div class="controls">
                                    <?php echo form_input('company', '', 'class="form-control" id="company" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group1">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', '', 'class="form-control" id="phone" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group1">
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <input type="email" id="email" name="email" class="form-control"
                                           required="required"/>
                                    <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
                                </div>
                            </div>
							
							

							<div class="form-group1">
								<?= lang("line", "line"); ?>
								<div class="controls">
								<?php echo form_input('line', '', 'class="form-control" id="line" '); ?>
								</div>
							</div>

							<div class="form-group1">
								<?= lang("facebook", "facebook"); ?>
								<?php echo form_input('facebook', '', 'class="form-control" id="facebook" '); ?>
							</div>

							<div class="form-group1">
								<?= lang("Instragram", "Instragram"); ?>
								<?php echo form_input('instragram', '', 'class="form-control" id="instragram" '); ?>
							</div>

							<div class="form-group1">
								<?= lang("address", "address"); ?>
								<?php echo form_textarea('address', '', ' class="form-control" id="address" required="required"'); ?>
								<span  class="help-block"><?php echo lang('กรุณาใส่รหัสไปรษณีไว้อันดับสุดท้าย'); ?></span>
							</div>
							
							
							<div id="demo2">
							<div class="form-group1">
								<?= lang("district", "district"); ?>
								<?php echo form_input('district', '',  'class="form-control" id="district" placeholder="ตำบล"'); ?>
							</div>
							
							<div class="form-group1">							
								<?= lang("amphoe", "amphoe"); ?>
								<?php echo form_input('amphoe', '',  'class="form-control" id="amphoe" placeholder="อำเภอ"'); ?>
							</div>
								
							<div class="form-group1">
								<?= lang("province", "province"); ?>
								<?php echo form_input('province', '',  'class="form-control" id="province" placeholder="จังหวัด"'); ?>
							</div>
								
							<div class="form-group1">
								<?= lang("zipcode", "zipcode"); ?>
								<?php echo form_input('zipcode', '',  'class="form-control" id="zipcode" placeholder="รหัสไปรษณีย์"'); ?>
							</div>
							</div>
							
                            <div class="form-group1">
                                <?php echo lang('username', 'username'); ?>
                                <div class="controls">
                                    <input type="text" id="username" name="username" class="form-control"
                                           required="required" pattern=".{4,20}"/>
                                </div>
                            </div>
                            <div class="form-group1">
                                <?php echo lang('password', 'password'); ?>
                                <div class="controls">
                                    <?php echo form_password('password', '', 'class="form-control tip" id="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="'.lang('pasword_hint').'"'); ?>
                                    <span  class="help-block"><?= lang('pasword_hint') ?></span>
                                </div>
                            </div>

                            <div class="form-group1">
                                <?php echo lang('confirm_password', 'confirm_password'); ?>
                                <div class="controls">
                                    <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="required" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="form-group1">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                            <div class="form-group1">
                                <?= lang("group", "group"); ?>
                                <?php
                                foreach ($groups as $group) {
                                    if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                        $gp[$group['id']] = $group['name'];
                                    }
                                }
                                echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                            <div class="clearfix"></div>
                            <div class="no">
                                <div class="form-group1">
                                    <?= lang("biller", "biller"); ?>
                                    <?php
                                    $bl[""] = lang('select').' '.lang('biller');
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company.' - '.$biller->name : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ''), 'id="biller" class="form-control select" disabled="disabled" style="width:100%;"');
                                    ?>
                                </div>

								<div class="form-group1" style="display:none">
									 <?= lang("wisdom", "wisdom"); ?>
									<div class="controls">
									<?php
										$wd[""] = lang('select').' '.lang('wisdom');
										foreach ($wisdoms as $wisdom) {
											$sellerid = $wisdom->seller_id ? $wisdom->seller_id . " - " : "";
											$wd[$wisdom->id] = $sellerid.$wisdom->first_name." ".$wisdom->last_name;
										}
										echo form_dropdown('wisdom', $wd, (isset($_POST['wisdom']) ? $_POST['wisdom'] : $user->wisdom_id), 'id="wisdom" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("wisdom") . '" class="form-control select" disabled="disabled" style="width:100%;"');
									?>
									</div>
								</div>
							
                                <div class="form-group1">
                                    <?= lang("warehouse", "warehouse"); ?>
                                    <?php
                                    $wh[''] = lang('select').' '.lang('warehouse');
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->name;
                                    }
                                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="warehouse" class="form-control select" disabled="disabled" style="width:100%;" ');
                                    ?>
                                </div>
								
								 <div class="form-group1">
									<?= lang("parent_user", "parent_user"); ?>
									<?php
									$prd[''] = lang('select').' '.lang('parent_user');
									foreach ($parent_ids as $parent_id) {
										$prd[$parent_id->id] = $parent_id->first_name." ".$parent_id->last_name;
									}
									echo form_dropdown('parent_id', $prd, (isset($_POST['parent_id']) ? $_POST['parent_id'] : $user->parent_id), 'id="parent_id" class="form-control select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("parent_id") . '" style="width:100%;" ');
									?>
								</div>

                                <div class="form-group1">
                                    <?= lang("view_right", "view_right"); ?>
                                    <?php
                                    $vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
                                    echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : 1), 'id="view_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group1">
                                    <?= lang("edit_right", "edit_right"); ?>
                                    <?php
                                    $opts = array(1 => lang('yes'), 0 => lang('no'));
                                    echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : 0), 'id="edit_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group1">
                                    <?= lang("allow_discount", "allow_discount"); ?>
                                    <?= form_dropdown('allow_discount', $opts, (isset($_POST['allow_discount']) ? $_POST['allow_discount'] : 0), 'id="allow_discount" class="form-control select" style="width:100%;"'); ?>
                                </div>

								<div class="form-group1">
									<label class="control-label" for="price_group"><?php echo $this->lang->line("price_group"); ?></label>
									<?php
										$pgs[''] = lang('select').' '.lang('price_group');
										foreach ($price_groups as $price_group) {
											$pgs[$price_group->id] = $price_group->name;
										}
										echo form_dropdown('price_group', $pgs, '', 'class="form-control select" id="price_group" style="width:100%;"');
									?>
								</div>
                            </div>

							<div class="form-group1">
							<div class="row_c">
								<div class="no">
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
													<?= form_multiselect('brand_targets[]',$brands, $brand_val, 'id="brand-select"');?>
												</div>
											</div>
										</div>
										</div>
										</div>
										</div>
									</div>
									
									
									
									
									<div class="clearfix"></div>
									<div class="col-md-12 hidden">
										<label><?= lang("agent"); ?></label>
										<div class="form-group1">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="1" name="agent" id="Non_stock" checked="checked">
															<label for="full" class="padding05">
																<?= lang('Non_stock'); ?>
															</label>
														</div>
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="2" name="agent" id="Stock_Item">
															<label for="partial" class="padding05">
																<?= lang('Stock_Item'); ?>
															</label>
														</div>

														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="3" name="agent" id="Stock_Model" >
															<label for="partial" class="padding05">
																<?= lang('Stock_Model_(VIP)'); ?>
															</label>
														</div>	
														
													</div>
												</div>
											</div>
										</div>
									</div>	
									
									<div class="col-md-12">
										<label><?= lang("vendor_inventory"); ?></label>
										<?php $vendor_inventory = $vendor_inventory->vendor_inventory; ?>
										
										<div class="form-group">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="1" name="vendor_inventory" id="stock_only" >
															<label for="full" class="padding05">
																<?= lang('stock_only'); ?>
															</label>
														</div>
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="2" name="vendor_inventory" id="dropship">
															<label for="partial" class="padding05">
																<?= lang('dropship'); ?>
															</label>
														</div>

														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="3" name="vendor_inventory" id="stock_dropship" checked="checked">
															<label for="partial" class="padding05">
																<?= lang('stock_dropship'); ?>
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
											</div></div></div>
										</div>
									</div>
									</div>
									
								</div>
							</div>
							
                            <div class="row_c">
                                <div class="col-md-8">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/>
                                        <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
							</div>
                        </div>
                    </div>
                </div>

                <p><?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-success btn-margin-top"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
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

    </script>