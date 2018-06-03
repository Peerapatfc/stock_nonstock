<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	// print_r($totalpoint);
	// $earner = $totalpoint['points_current'];
	// $spent =  $totalpoint['spent_points'];
	$balance_points = $totalpoint;
	
	/* Wallet */
	//$walletpoint = $walletpoint['wallet_sum'];
	$walletpoint = ($walletpoint['wallet_sum']!='')? number_format($walletpoint['wallet_sum'],2,".",",") : 0;
?>

<?php //แลกใช้แต้มสะสม ?>
<script>
    $(document).ready(function () {
        oTable = $('#APRTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('auth/getAwardpointsruleByUser') ?>',
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
            'sAjaxSource': '<?= admin_url('auth/getAwardpoints/'.$id) ?>',
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
				console.log(aData);
				
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
                    gtotal += parseFloat(aaData[aiDisplay[i]][2]);
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
            'sAjaxSource': '<?= admin_url('auth/spentpoints/'.$id) ?>',
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
				//approve
					if(aData[5] == 1){
						nRow.className = "id_approve success";
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?= lang("approve"); ?></span></div>');
					}else if(aData[5] == 0){
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-danger"><i class="fa fa-close" aria-hidden="true"></i> <?= lang("disapprove"); ?></span></div>');
					}else{
						$('td:eq(4)', nRow).html('<div style="text-align:center"><span class="label label-warning"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <?= lang("pendding"); ?></span></div>');
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

<script>

    $(document).ready(function () {
        oTable1 = $('#wallet_summary').dataTable({
            "aaSorting": [[3, "desc"],[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('auth/getWallet/'.$id) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				nRow.id = aData[0];

				
			/* Slip */
			if(aData[3]!=""){
				var uri = '<?php echo admin_url("auth/popup/") ?>' + nRow.id;
				//uri_pic = '<?php echo base_url();?>files/'+aData[3];
				$('td:eq(3)', nRow).html('<div class="text-center"><a href='+ uri +' data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="<?php echo lang("attachment"); ?>"><i class="fa fa-file"></i></a></div>');
			}else{
				$('td:eq(3)', nRow).html('<div class="text-center text-muted"><i class="fa fa-file"></i></div>');
			}
				var date = aData[1].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(0)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');
				
				var approve = aData[6];
				if(approve=="1"){
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('approve');?></span></div>');
				}else{
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-danger"><i class="fa fa-close" aria-hidden="true"></i> <?php echo lang('disapprove');?></span></div>');
				}
				
				var transfer = aData[5];
				if(transfer=="withdraw"){
					$('td:eq(4)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-danger"><?php echo lang('withdraw');?></span></div>');
				}else if(transfer=="deposit"){
					$('td:eq(4)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-success"><?php echo lang('deposit');?></span></div>');
				}
				
				var slip = aData[4];
				//console.log(slip);
				if(slip==null){
					$('td:eq(3)', nRow).html('');
				}
			
			
            },"aoColumns": [
				{"bVisible": false}, 
				null,
				null,
				{"mRender": currencyFormat},
				null,
				null,
				null,
			]
			
        });
    });
	
	/*$(document).ready(function () {
		var add_sale = '<a class="btn btn-danger" href="<?=admin_url('auth/approvewallet')?>"> <i class="fa fa-plus-circle"></i> <?=lang('approve_wallet')?></a>';
		$("#wallet_summary_length > label").append(add_sale);
	});*/

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
				<?php //if (!$Owner) { 
					echo '<p><i class="fa fa-gift"></i> '.$balance_points.' '.lang('points').'</p><br/>';
				//}
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
			<?php if($Settings->wallet_use=="1"): ?><li class=""><a href="#wallet" class="tab-grey"><?= lang('wallet') ?></a></li><?php endif; ?>
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
                                            <div class="form-group1">
                                                <?php echo lang('first_name', 'first_name'); ?>
                                                <div class="controls">
                                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group1">
                                                <?php echo lang('last_name', 'last_name'); ?>

                                                <div class="controls">
                                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
                                                </div>
                                            </div>
                                            <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                <div class="form-group1">
                                                    <?php echo lang('company', 'company'); ?>
                                                    <div class="controls">
                                                        <?php echo form_input('company', $user->company, 'class="form-control" id="company" required="required"'); ?>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                echo form_hidden('company', $user->company);
                                            } ?>
                                            <div class="form-group1">

                                                <?php echo lang('phone', 'phone'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="phone" class="form-control" id="phone"
                                                           required="required" value="<?= $user->phone ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group1">
                                                <?= lang('gender', 'gender'); ?>
                                                <div class="controls">  <?php
                                                    $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                                    echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
                                                    ?>
                                                </div>
                                            </div>
											
											<div class="form-group1">
												<?= lang("line", "line"); ?>
												<?php echo form_input('line', $companies[0]->line, 'class="form-control" id="line" '); ?>
											</div>
							

											<div class="form-group1">
												<?= lang("facebook", "facebook"); ?>
												<?php echo form_input('facebook', $companies[0]->facebook, 'class="form-control" id="facebook" '); ?>
											</div>

											<div class="form-group1">
												<?= lang("Instragram", "Instragram"); ?>
												<?php echo form_input('instragram', $companies[0]->instragram, 'class="form-control" id="instragram" '); ?>
											</div>
											
											<div class="form-group1">
												<?= lang("address", "address"); ?>
												<?php echo form_textarea('address', $companies[0]->address, ' class="form-control" id="address"'); ?>
											</div>

											<div class="hidden">
												<?= lang("user_id", "user_id"); ?>
												<?php echo form_input('user_id', $companies[0]->id, 'class="form-control" id="id" '); ?>
											</div>

                                            <?php if (($Owner || $Admin) && $id != $this->session->userdata('user_id')) { ?>
											
                                            <div class="form-group1 hidden">
                                                <?= lang('award_points', 'award_points'); ?>
                                                <?= form_input('award_points', set_value('award_points', $user->award_points), 'class="form-control tip" id="award_points"  required="required"'); ?>
                                            </div>
                                            <?php } ?>

                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="form-group1">
                                                    <?php echo lang('username', 'username'); ?>
                                                    <input type="text" name="username" class="form-control"
                                                           id="username" value="<?= $user->username ?>"
                                                           required="required"/>
                                                </div>
                                                <div class="form-group1">
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
                                                                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company.' - '.$biller->name : $biller->name;
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

									<div class="col-md-12">
									<?= lang('type_commission', 'type_commission'); ?>
										<div class="form-group">
										
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="row">
														<div class="col-xs-12 col-sm-12">
										
											<?php $sst = array('bronze' => lang('bronze'), 'silver' => lang('silver'), 'gold' => lang('gold'),'platinum' => lang('platinum'),'diamond' => lang('diamond'),'conqueror' => lang('conqueror'));
												echo form_dropdown('type_commission', $sst, $user->type_commission, 'class="form-control input-tip" required="required" id="type"');
											?>
											</div></div></div>
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
                                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-success btn-margin-top"'); ?></p>
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
                                            <a href="<?= admin_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>" class="btn btn-danger btn-xs po remove-btn"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
											   data-return-id="<?php echo $id;?>"
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
			
			<?php if($Settings->wallet_use=="1"): ?>
			<!-- Wallet -->
			 <div id="wallet" class="tab-pane fade">
				<!-- Wallet summary table -->
				<div class="box">
				  <div class="box-header">
					<h2 class="blue"><i class="fa fa-exchange" aria-hidden="true"></i>
					  <?= lang('Wallet_balance'); ?>
					</h2>
					
				  </div>
				  
				  
				  
				  
				  
				  <div class="box-content">
					<div class="row">
					  <div class="col-lg-12">
						<h4><?= lang('Wallet_balance'); ?> : <?php echo $walletpoint;?></h4>
					  </div>
					</div>
				  </div>
				</div>
				
				
				<!-- Wallet Update Form -->
				<div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="glyphicon glyphicon-credit-card" aria-hidden="true"></i> <?= lang('wallet_update'); ?></h2>
                    </div>
					
					<div class="box-content">
						 <?php echo admin_form_open_multipart("auth/update_wallet", 'id="wallet-update"'); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-5">
									<div class="form-group">
										<label for="wallet_type"><?php echo lang('wallet_type'); ?></label><br/>
										<?php $wt[''] = array('deposit' => lang('deposit')); ?>
										<?php echo form_dropdown('wallet_type', $wt, (isset($_POST['wallet_type']) ? $_POST['wallet_type'] : ""), 'class="form-control" id="wallet_type" required="required"');?>
									</div>
									<div class="form-group">
										<label for="wallet_amount"><?php echo lang('wallet_amount'); ?></label><br/>
										<?php echo form_input('wallet_amount', '', 'class="form-control" id="wallet_amount" required="required"'); ?>
									</div>
									<div class="form-group">
                                        <?= lang("upload_slip", "upload_slip"); ?>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="upload_slip" id="upload_slip" 
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
									<?php echo form_input($user_id); ?>
									<p><?php echo form_submit('update_wallet', lang('update_wallet'), 'class="btn btn-success"'); ?></p>
								</div>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
                </div>
				
				<!-- -->
				<div class="box">
				  <div class="box-header">
					<h2 class="blue"><i class="fa fa-exchange" aria-hidden="true"></i>
					  <?= lang('Wallet_Summary'); ?>
					</h2>
				  </div>
				  <div class="box-content">
					<div class="row">
					  <div class="col-lg-12">
						<table id="wallet_summary" class="table table-bordered table-hover table-striped table-condensed">
							<thead>
								<tr>
									<th></th>
									<th><?= lang("date"); ?></th>
									<th><?= lang("use_sale_order"); ?></th>
									<th><?= lang("amount"); ?></th>
									<th><?= lang("slip"); ?></th>
									<th><?= lang("wallet_type"); ?></th>
									<th><?= lang("status"); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="7" class="dataTables_empty"><?= lang("loading_data"); ?></td>
								</tr>
							</tbody>
							<tfoot class="dtFilter">
								<?php /*<tr class="active">
									<th><input class="checkbox checkft" type="checkbox" name="check"/></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>*/ ?>
							</tfoot>
							</table>
					  </div>
					</div>
				  </div>
				</div>

				
			 </div>
			<?php endif; ?>
			
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