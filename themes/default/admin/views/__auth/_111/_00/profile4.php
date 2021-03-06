<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$earner = $totalpoint['points_current'];
	$spent =  $totalpoint['spent_points'];
	$balance_points = $earner - $spent;
?>
<script>
    $(document).ready(function () {
        oTable = $('#APRTable').dataTable({
            "aaSorting": [[3, "asc"], [1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('auth/getAwardpointsrule') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
			
            "aoColumns": [
			//{"bSortable": false,"mRender": img_hl},
			null,
			null,
			null,
			null,
			null,
			{"bVisible": false},
			{"bSortable": false},
			],

			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
				var admin_url = '<?php echo admin_url('welcome/download/'); ?>';
				$('td:eq(0)', nRow).html('<div style="text-align:center"><img src="'+ admin_url+aData[0]+'" style="width: auto; max-height:45px;"></div>');
				
				
				var point = parseInt(aData[2]);
				var ck = 0;
				ck = '<?php echo $Owner; ?>';
				balance_points = '<?php echo $balance_points; ?>';
				//console.log(point);
				//console.log(balance_points);
				
				if(!ck && balance_points <= point){
					$('td:eq(5)', nRow).html('<div class="text-center"><span style="pointer-events: none; background: #ccc; border-color: #999;" class="btn"><?php echo $this->lang->line("exchange"); ?></span></div>');
				}
				
				
				//วันเวลา
				var date = aData[4];
				if('<?php echo date('Y-m-d'); ?>' == date){ date = "<?php echo lang('today'); ?>";}
				$('td:eq(4)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date + '</span>');
				
				var date = aData[3];
				if('<?php echo date('Y-m-d'); ?>' == date){ date = "<?php echo lang('today'); ?>";}
				$('td:eq(3)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date + '</span>');
				return nRow;
            },
			
			
			
        });
    });
</script>


<?php #รายการสะสมแต้ม ?>
<script>
    $(document).ready(function () {
        oTable1 = $('#AWArdpoints').dataTable({
            "aaSorting": [[3, "desc"],[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('auth/getAwardpoints') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable1.fnSettings();
                nRow.id = aData[0];

				//วันเวลา
				var date = aData[3].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(2)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');
                
				return nRow;
            },"aoColumns": [
				{"bSortable": false,"mRender": checkbox,"bVisible": false},
				null,
				{"mRender": currencyFormat},
				null,
				null,
				{"bVisible": false},
				{"bVisible": false},
				//{"bSortable": false},
			],"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0 ,gspent = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][3]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[1].innerHTML = currencyFormat(parseFloat(gtotal));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('sale_reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('points_current');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('user_name');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>

<?php #ประวัติการแลกแต้มสะสม ?>
<script>
    $(document).ready(function () {
        oTable2 = $('#SPEntpoints').dataTable({
            "aaSorting": [[3, "desc"],[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('auth/spentpoints') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },"aoColumns": [
				{"bSortable": false,"mRender": checkbox,"bVisible": false},
				
				null,
				{"mRender": currencyFormat},
				null,
				null,
				{"bVisible": false},
				{"bSortable": false},

			],'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable2.fnSettings();
                nRow.id = aData[0];
				//console.log(aData);
				
				//วันเวลา
				var date = aData[3].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(2)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				//approve
				var ck = 0;
				ck = '<?php echo $Owner; ?>';
				if(!ck){
					if(aData[5] > 0){
						nRow.className = "id_approve success";
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?= lang("approve"); ?></span></div>');
					}else{
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-warning"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <?= lang("pendding_approve"); ?></span></div>');
					}
				}else{
					if(aData[5] > 0){
						nRow.className = "id_approve success";
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?= lang("approve"); ?></span></div>');
					}
				}
				return nRow;
            }
			
			,"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0 ,gspent = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][2]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[1].innerHTML = currencyFormat(parseFloat(gtotal));
            }
			

        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('list_spent');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('spent_points');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('user_name');?>]", filter_type: "text", data: []},
        ], "footer");
    });
	
</script>



<div class="row">
    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <h4><i class="fa fa-user" aria-hidden="true"></i> <?php echo $user->first_name.' '.$user->last_name;?></h4>
                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
				<?php if (!$Owner) { 
					echo '<p><i class="fa fa-gift"></i> '.$balance_points.' '.lang('points').'</p><br/>';
				}
			?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <ul id="myTab" class="nav nav-tabs">
			<li class=""><a href="#awardpoints" class="tab-grey"><?= lang('award_points') ?></a></li>
            <li class=""><a href="#edit" class="tab-grey"><?= lang('edit') ?></a></li>
            <li class=""><a href="#cpassword" class="tab-grey"><?= lang('change_password') ?></a></li>
            <li class=""><a href="#avatar" class="tab-grey"><?= lang('avatar') ?></a></li>
            <li class=""><a href="#document" class="tab-grey"><?= lang('document') ?></a></li>
            <li class=""><a href="#usergeneral" class="tab-grey"><?= lang('user_general') ?></a></li>
        </ul>

        <div class="tab-content">
            <div id="awardpoints" class="tab-pane fade in">
                <div class="box">
					<?php //แลกแต้มสะสม   ?>
                    <div class="box-header">
                        <h2 class="blue"><i class="fa fa-exchange" aria-hidden="true"></i><?= lang('exchange_points'); ?></h2>
                    </div>
							<div class="box-content">
								<div class="row">
									<div class="col-lg-12">
										<p class="introtext"><?= lang('list_results'); ?></p>
										<div class="table-responsive">
											<table id="APRTable" cellpadding="0" cellspacing="0" border="0"
												   class="table table-bordered table-hover table-striped">
												<thead>
												<tr>
													<th style="width: 170px;"><?php echo lang('Images')?></th>
													<th style="width: 170px;"><?php echo lang('List_gift_reward')?></th>
													<th style="width: 170px;"><?php echo lang("spent_points"); ?></th>
													<th style="width: 170px;"><?php echo lang("start_promotions"); ?></th>
													<th style="width: 170px;"><?php echo lang("end_promotions"); ?></th>
													<th style="width: 170px;"><?php echo lang('ID')?></th>
													<th style="width:90px;"><?php echo $this->lang->line("exchange"); ?></th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
				</div>

				<?php #รายการสะสมแต้ม ?>
				<div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa fa-gift" aria-hidden="true"></i><?= lang('your_point'); ?></h2>
                    </div>
							<div class="box-content panel-body">
								<div class="row">
									<div class="col-lg-12">
										<p class="introtext"><?= lang('list_results'); ?></p>

										<table id="AWArdpoints" class="table table-bordered table-hover table-striped table-condensed">
											<thead>
											<tr>
												<th><?= lang("no"); ?></th>
												<th><?= lang("sale_reference_no"); ?></th>
												<th><?= lang("points_current"); ?></th>
												<th><?= lang("date"); ?></th>
												<th><?= lang("user_name"); ?></th>
												<th></th>
												<th></th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td colspan="7" class="dataTables_empty"><?= lang("loading_data"); ?></td>
											</tr>
											</tbody>
											<tfoot class="dtFilter">
											<tr class="active">
												<th><?= lang("no"); ?></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
											</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
				</div>

				<?php #ประวัติการแลกแต้มสะสม ?>
				<div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa fa-history" aria-hidden="true"></i><?= lang('history_points'); ?></h2>
                    </div>
							<div class="box-content panel-body">
								<div class="row">
									<div class="col-lg-12">
										<p class="introtext"><?= lang('list_results'); ?></p>

										<table id="SPEntpoints" class="table table-bordered table-hover table-striped table-condensed">
											<thead>
											<tr>
												<th></th>
												<th><?= lang("list_spent"); ?></th>
												<th><?= lang("spent_points"); ?></th>
												<th><?= lang("date"); ?></th>
												<th><?= lang("user_name"); ?></th>
												<th></th>
												<th><?= lang("status"); ?></th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td colspan="7" class="dataTables_empty"><?= lang("loading_data"); ?></td>
											</tr>
											</tbody>
											<tfoot class="dtFilter">
											<tr class="active">
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th>[<?= lang("status"); ?>]</th>

											</tr>
											</tfoot>
										</table>
										
									</div>
								</div>
							</div>
							
							
				</div>

			</div>
		
		
		
		
		
		
		
		
		
            <div id="edit" class="tab-pane fade in">

                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?= lang('edit_profile'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">

                                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                echo admin_form_open('auth/edit_user/' . $user->id, $attrib);
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('first_name', 'first_name'); ?>
                                                <div class="controls">
                                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('last_name', 'last_name'); ?>

                                                <div class="controls">
                                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
                                                </div>
                                            </div>
                                            <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('company', 'company'); ?>
                                                    <div class="controls">
                                                        <?php echo form_input('company', $user->company, 'class="form-control" id="company" required="required"'); ?>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                echo form_hidden('company', $user->company);
                                            } ?>
                                            <div class="form-group">

                                                <?php echo lang('phone', 'phone'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="phone" class="form-control" id="phone"
                                                           required="required" value="<?= $user->phone ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?= lang('gender', 'gender'); ?>
                                                <div class="controls">  <?php
                                                    $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                                    echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if (($Owner || $Admin) && $id != $this->session->userdata('user_id')) { ?>
                                            <div class="form-group">
                                                <?= lang('award_points', 'award_points'); ?>
                                                <?= form_input('award_points', set_value('award_points', $user->award_points), 'class="form-control tip" id="award_points"  required="required"'); ?>
                                            </div>
                                            <?php } ?>

                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('username', 'username'); ?>
                                                    <input type="text" name="username" class="form-control"
                                                           id="username" value="<?= $user->username ?>"
                                                           required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('email', 'email'); ?>

                                                    <input type="email" name="email" class="form-control" id="email"
                                                           value="<?= $user->email ?>" required="required"/>
                                                </div>
                                                <div class="row">
                                                    <div class="panel panel-warning">
                                                        <div
                                                            class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                                                        <div class="panel-body" style="padding: 5px;">
                                                            <div class="col-md-12">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <?php echo lang('password', 'password'); ?>
                                                                        <?php echo form_input($password); ?>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <?php echo lang('confirm_password', 'password_confirm'); ?>
                                                                        <?php echo form_input($password_confirm); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>

                                        </div>
                                        <div class="col-md-6 col-md-offset-1">
                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>

                                                    <div class="row">
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading"><?= lang('user_options') ?></div>
                                                            <div class="panel-body" style="padding: 5px;">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <?= lang('status', 'status'); ?>
                                                                            <?php
                                                                            $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                                                            echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" required="required" class="form-control input-tip select" style="width:100%;"');
                                                                            ?>
                                                                        </div>
                                                                        <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                                        <div class="form-group">
                                                                            <?= lang("group", "group"); ?>
                                                                            <?php
                                                                            $gp[""] = "";
                                                                            foreach ($groups as $group) {
                                                                                if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                                                                    $gp[$group['id']] = $group['name'];
                                                                                }
                                                                            }
                                                                            echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $user->group_id), 'id="group" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                                                            ?>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <div class="no">
                                                                            <div class="form-group">
                                                                                <?= lang("biller", "biller"); ?>
                                                                                <?php
                                                                                $bl[""] = lang('select').' '.lang('biller');
                                                                                foreach ($billers as $biller) {
                                                                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                                                                }
                                                                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $user->biller_id), 'id="biller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("biller") . '" class="form-control select" style="width:100%;"');
                                                                                ?>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <?= lang("warehouse", "warehouse"); ?>
                                                                                <?php
                                                                                $wh[''] = lang('select').' '.lang('warehouse');
                                                                                foreach ($warehouses as $warehouse) {
                                                                                    $wh[$warehouse->id] = $warehouse->name;
                                                                                }
                                                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $user->warehouse_id), 'id="warehouse" class="form-control select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" style="width:100%;" ');
                                                                                ?>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <?= lang("view_right", "view_right"); ?>
                                                                                <?php
                                                                                $vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
                                                                                echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : $user->view_right), 'id="view_right" class="form-control select" style="width:100%;"');
                                                                                ?>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <?= lang("edit_right", "edit_right"); ?>
                                                                                <?php
                                                                                $opts = array(1 => lang('yes'), 0 => lang('no'));
                                                                                echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : $user->edit_right), 'id="edit_right" class="form-control select" style="width:100%;"');
                                                                                ?>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <?= lang("allow_discount", "allow_discount"); ?>
                                                                                <?= form_dropdown('allow_discount', $opts, (isset($_POST['allow_discount']) ? $_POST['allow_discount'] : $user->allow_discount), 'id="allow_discount" class="form-control select" style="width:100%;"'); ?>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
														<div class="no">
														<div class="panel panel-warning">
                                                            <div class="panel-heading"><?= lang('sale_brand') ?></div>
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
									
									
			
									<div class="col-md-12">
										<label><?= lang("agent"); ?> *</label>
										<div class="form-group">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="1" name="agent" id="Non_stock" <?= $agent=='1' ? 'checked="checked"' : ''; ?>>
															<label for="full" class="padding05">
																<?= lang('Non_stock'); ?>
															</label>
														</div>
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="2" name="agent" id="Stock_Item" <?= $agent=='2' ? 'checked="checked"' : ''; ?>>
															<label for="partial" class="padding05">
																<?= lang('Stock_Item'); ?>
															</label>
														</div>
														
														
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="3" name="agent" id="Stock_Model" <?= $agent=='3' ? 'checked="checked"' : ''; ?>>
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
										<label><?= lang("vendor_inventory"); ?> *</label>
										<?php $vendor_inventory = $vendor_inventory->vendor_inventory; ?>
										
										<div class="form-group">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="1" name="vendor_inventory" id="stock_only" <?= $vendor_inventory=='1' ? 'checked="checked"' : ''; ?>>
															<label for="full" class="padding05">
																<?= lang('stock_only'); ?>
															</label>
														</div>
														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="2" name="vendor_inventory" id="dropship" <?= $vendor_inventory=='2' ? 'checked="checked"' : ''; ?>>
															<label for="partial" class="padding05">
																<?= lang('dropship'); ?>
															</label>
														</div>

														<div class="col-xs-12 col-sm-12">
															<input type="radio" class="checkbox type" value="3" name="vendor_inventory" id="stock_dropship" <?= $vendor_inventory=='3' ? 'checked="checked"' : ''; ?>>
															<label for="partial" class="padding05">
																<?= lang('stock_dropship'); ?>
															</label>
														</div>	
														
													</div>
												</div>
											</div>
										</div>
									</div>	
		
		
									<?php #echo CI_VERSION; ?>
														</div>
                                                    </div>
                                            <?php } ?>
                                            <?php echo form_hidden('id', $id); ?>
                                            <?php echo form_hidden($csrf); ?>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-success"'); ?></p>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo admin_form_open("auth/change_password", 'id="change-password-form"'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required"'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="'.lang('pasword_hint').'"'); ?>
                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-success"'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

			
			
            <div id="avatar" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-5">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $user->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= admin_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo admin_form_open_multipart("auth/update_avatar"); ?>
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-success"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			
            <div id="usergeneral" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa fa-group"></i><?= lang('user_general'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                



        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'usergeneral-auth-form');
        echo admin_form_open_multipart("auth/usergeneral", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="row">
                <div class="">
                    <div class="form-group company hidden">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" id="company" '); ?>
                    </div>

					

					
					<div class="col-md-12">
                    <div class="form-group">
                        <?= lang("line", "line"); ?>
                        <?php echo form_input('line', '', 'class="form-control" id="line" '); ?>
                    </div>
					</div>	
					
					<div class="col-md-12">
                    <div class="form-group">
                        <?= lang("facebook", "facebook"); ?>
                        <?php echo form_input('facebook', '', 'class="form-control" id="facebook" '); ?>
                    </div>
					</div>
					

					<div class="col-md-12">
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_textarea('address', '', ' class="form-control" id="address" required="required"'); ?>
                    </div>
					</div>
					
					<div class="hidden">
                        <?= lang("id", "id"); ?>
						<?php echo form_input('id', $id, 'class="form-control" id="id" '); ?>
           
                    </div>

					
                </div>
				
            </div>


        </div>

        <?php echo form_submit('add_usergeneral', lang('add'), 'class="btn btn-success"'); ?>
		<?php echo form_close(); ?>
		
		


                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			
            <div id="document" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="glyphicon glyphicon-credit-card" aria-hidden="true"></i> <?= lang('id_card'); ?></h2>
                    </div>

						<div class="row" style="padding:10px;">
							<div class="col-sm-5">
								<div style="max-width:100%; width:650px;">
									<?php
									$card_id = admin_url('welcome/download/').$user->id_card;
									echo $user->id_card ? '<img alt="" src="' . $card_id . '" style="max-width:100%;">' :
										'<img alt="" src="' . base_url() . 'assets/images/icon/noimage.png">';
									?>
								</div><br/>
								<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
								echo admin_form_open_multipart("auth/id_card", $attrib); ?>

                                            <div class="form-group">
												<input id="id_card" required="required" type="file" data-browse-label="<?= lang('browse'); ?>" name="id_card" data-show-upload="false"
												data-show-preview="false" class="form-control file" data-bv-notempty-message =<?= lang('Please attach proof of transfer.');?> />
											</div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('save', lang('save'), 'class="btn btn-success"'); ?></p>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                </div>
				
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa fa-book" aria-hidden="true"></i> <?= lang('account_book'); ?></h2>
                    </div>

						<div class="row" style="padding:10px;">
							<div class="col-sm-5">
								<div style="max-width:100%; width:650px;">
									<?php
									$card_id = admin_url('welcome/download/').$user->account_book;
									echo $user->account_book ? '<img alt="" src="' . $card_id . '" style="max-width:100%;">' :
										'<img alt="" src="' . base_url() . 'assets/images/icon/noimage.png">';
									?>
								</div><br/>
								<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
								echo admin_form_open_multipart("auth/account_book", $attrib); ?>

                                            <div class="form-group">
												<input id="account_book" required="required" type="file" data-browse-label="<?= lang('browse'); ?>" name="account_book" data-show-upload="false"
												data-show-preview="false" class="form-control file" data-bv-notempty-message =<?= lang('Please attach proof of transfer.');?> />
											</div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('save', lang('save'), 'class="btn btn-success"'); ?></p>


                                <?php echo form_close(); ?>
                            </div>
                        </div>
                </div>
            </div>
			
			
			
        </div>
    </div>
	

    <script>
        $(document).ready(function () {
            $('#change-password-form').bootstrapValidator({
                message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]'
            });
        });
    </script>
    <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {
            $('#group').change(function (event) {
                var group = $(this).val();
                if (group == 1 || group == 2) {
                    $('.no').slideUp();
                } else {
                    $('.no').slideDown();
                }
            });
            var group = <?=$user->group_id?>;
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
    </script>
<?php } ?>



<?php if ($Owner) { ?>
    <script type="text/javascript" charset="utf-8">
		setTimeout(function(){
			var uri = '<?= admin_url('award_points'); ?>';
			var name = '<?php echo lang('add_award_points'); ?>';
			var award_points = '<a class="btn btn-danger" href="'+uri+'"><i class="fa fa-plus-circle"></i> '+name+'</a>';
			jQuery("#APRTable_length label").append(award_points);
		},500);
	</script>
	<?php
	//echo "<style>#award_points_wrapper .dtFilter {display: none;}</style>";
}
?>
<style>
.dataTables_filter {
    display: none;
}
</style>
</div>