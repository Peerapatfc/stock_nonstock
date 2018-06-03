<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	// print_r($totalpoint);
	// $earner = $totalpoint['points_current'];
	// $spent =  $totalpoint['spent_points'];
	//$balance_points = $totalpoint;
	//echo $totalpoint."<br/>";
	// print_r($user->type_commission);
	//print_r($silver_point[0]->spent);



$silver_point_old = isset($silver_point_old) ? $silver_point_old : 0;
$gold_point_old = isset($gold_point_old) ? $gold_point_old : 0;
$vip_point_old = isset($vip_point_old) ? $vip_point_old : 0;

$spoint = isset($silver_point) ? $silver_point : 0;
$gpoint = isset($gold_point) ? $gold_point : 0;
$vpoint = isset($vip_point) ? $vip_point : 0;


	//print_r($totalpoint);
	//$totalpoint = $totalpoint[0]->points_current;


$silver_point = $totalpoint-$silver_point_old-$spoint;
$gold_point = $totalpoint-$gold_point_old-$gpoint;
$vip_point = $totalpoint-$vip_point_old-$vpoint;

/* Wallet */
	//$walletpoint = $walletpoint['wallet_sum'];
$walletpoint = ($walletpoint['wallet_sum']!='')? number_format($walletpoint['wallet_sum'],2,".",",") : 0;
?>

<style type="text/css" >
	.dataTables_filter, .dataTables_length {
    text-align: left;
}
</style>
<script>
	$(document).ready(function () {
		//vip
		<?php $attr = ($user->type_commission == 'vip') ? array('1' => 'Silver', '2' => 'Gold', '3' => 'VIP_Access') : array('1' => 'Silver', '2' => 'Gold'); ?>
		
		<?php foreach($attr as $key => $val): ?>
		oTable = $('#<?php echo $val; ?>').dataTable({
			"aaSorting": [[2, "asc"], [3, "asc"]],
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
			"iDisplayLength": <?= $Settings->rows_per_page ?>,
			'bProcessing': true, 'bServerSide': true,
			'sAjaxSource': '<?= admin_url('auth/getAwardpointsruleByUser/'.$key) ?>',
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

				var point = parseFloat(aData[2]);
				var ck = 0;
				ck = '<?php echo $Owner; ?>';
				
				
				if('<?php echo $val; ?>' == 'Silver'){
					limit_point = '<?php echo $silver_point; ?>';
				}else if('<?php echo $val; ?>' == 'Gold'){
					limit_point = '<?php echo $gold_point; ?>';
				}else{
					limit_point = '<?php echo $vip_point; ?>';
				}
				limit_point = parseFloat(limit_point);

				if(!ck && limit_point <= point){
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
		<?php endforeach; ?>
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
				//console.log(aData);
				
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
			{"bVisible": false},
			{"bVisible": false},
			{"bVisible": false},
				//{"bSortable": false},
				]
			});
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
			null,
			null,
			{"bVisible": false},
				//{"bSortable": false},
				],'fnRowCallback': function (nRow, aData, iDisplayIndex) {
					var oSettings = oTable2.fnSettings();
					nRow.id = aData[0];
					console.log(aData[6]);

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
					$('td:eq(3)', nRow).html('<div style="text-align:center"><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?= lang("approve"); ?></span></div>');
				}else if(aData[5] == 0){
					$('td:eq(3)', nRow).html('<div style="text-align:center"><span class="label label-danger"><i class="fa fa-close" aria-hidden="true"></i> <?= lang("disapprove"); ?></span></div>');
				}else{
					$('td:eq(3)', nRow).html('<div style="text-align:center"><span class="label label-warning"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <?= lang("pendding"); ?></span></div>');
				}

				//level
				if(aData[6] == '1'){
					$('td:eq(4)', nRow).html('<div style="text-align:center"><?=lang('Silver'); ?></div>');
				}
				if(aData[6] == '2'){
					$('td:eq(4)', nRow).html('<div style="text-align:center"><?=lang('Gold'); ?></div>');
				}
				if(aData[6] == '3'){
					$('td:eq(4)', nRow).html('<div style="text-align:center"><?=lang('VIP_Access'); ?></div>');
				}

				return nRow;
			}
		});
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
<style type="text/css">
/*	div.col-xs-4 i{
		color: #fff;
		font-size: 30px;
    border: 1px solid #fff;
    padding: 8px 8px;
    border-radius: 50px;
    opacity: 0.7;
    width: 51px;
    height: 51px;
    text-align: center;
    line-height: 32px;
    margin-left: 400px;
    opacity: 1;
    background-color: #1d2939;
	}*/
	div.col-xs-12 i.fa{
		margin-top: -15px;
		background-color: #691818;
	}
	.riw {
    margin: -30px;
    /*background-color: #fff;*/
}
.panel-dark .panel-heading {
    background-color: #691818;
}
</style>


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
								<h4><i class="fa fa-link" aria-hidden="true"></i> คลิกลิ้งเพื่อดูบัตรตัวแทน : <a id="" href="<?php echo admin_url('auth/link_card/'.$user->id);?>"><?php echo $user->first_name.' '.$user->last_name;?></a></h4>
			<!-- <h4><i class="fa fa-user" aria-hidden="true"></i> <?php echo $user->first_name.' '.$user->last_name;?></h4> -->
			<!-- <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p> -->
			<!-- <?php if (!$Owner) {
				 echo '<p><i class="glyphicon glyphicon-screenshot"></i> '.$user->type_commission.'</p><br/>';
			}
				?> -->
			</div>
		</div>
		</div>

							<!-- <div class="col-xs-4">
								<i class="fa fa-trophy fa-lg" aria-hidden="true" ></i>
							</div> -->
		<div class="col-sm-6 col-md-10">
			<div class="panel panel-dark panel-stat">
				<div class="panel-heading">
					<div class="stat">
						<div class="row">
							<div class="riw">
								<div class="col-xs-12" align="center">
									<i class="fa fa-trophy fa-lg" aria-hidden="true" ></i>
								</div>
							</div>
							<div class="col-xs-12" align="center">
							<?php if ($Owner) { ?>
								<small class="stat-label"><?php echo lang("points"); ?></small>
								<h1><?php echo  lang('total_point : ').$totalpoint; ?> </h1>
								<?php }else{ ?><?php echo lang("points"); ?>
								<small class="stat-label"><?php echo lang("point_balance"); ?></small>
								<h1><?php echo number_format($totalpoint); ?></h1>


								
								<?php } ?>

							</div>
						</div>
						<div class="mb15"></div>
						<div class="row">
							<div class="col-xs-4">
							<!-- 	<small class="stat-label"><?php echo lang("Shipments yesterday"); ?></small>
								<h4><?php echo number_format($yesterday); ?></h4> -->
							</div>
							<div class="col-xs-4">
								<!-- <small class="stat-label"><?php echo lang("Shipments this month"); ?></small>
								<h4><?php echo number_format($month); ?></h4> -->
							</div>
							<div class="col-xs-12" align="center">
								<small class="stat-label"><?php echo lang("Level_: "); ?><?php echo $_SESSION['pv']; ?></small>
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-sm-10">
			<ul id="myTab" class="nav nav-tabs">
				<?php 

				if($this->Settings->award_use){ ?>
				<li class=""><a href="#awardpoints" class="tab-grey"><?= lang('award_points') ?></a></li>
				<?php  } ?>


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
									<?php //แลกใช้แต้มสะสม ?>
									<ul id="dbTab" class="nav nav-tabs tab_delivering">
										<?php foreach($attr as $key => $value): ?>
											<li class=""><a href="#<?php echo $value.'_'.$key; ?>"><?= lang($value); ?></a></li>
										<?php endforeach; ?>
									</ul>
									<div id="page-award" class="tab-content">
										<?php foreach($attr as $key => $value): ?>
											<div id="<?php echo $value.'_'.$key; ?>" class="tab-pane fade in">
												<?php
												$c_point = 0;
												$cal_total = 0;
												if($value == 'Silver'){
												$c_point = $silver_point; //แต้มคงเหลือ
												$t_point = $spoint; //แต้มที่ใช้
												$cal_total = $totalpoint-$silver_point_old;
											}elseif($value == 'Gold'){
												$c_point = $gold_point;
												$t_point = $gpoint;
												$cal_total = $totalpoint-$gold_point_old;
											}else{
												$c_point = $vip_point;
												$t_point = $vpoint;
												$cal_total = $totalpoint-$vip_point_old;
											}

											#echo '<div style="font-size: 14px;padding: 15px 0;" class="row"><div  class="glyphicon glyphicon-gift text-primary col-md-4"> '.lang('Total : ').$cal_total.'</div><div class="text-danger col-md-4 glyphicon glyphicon-transfer"> '.lang('Used_To_Day : ').$t_point.'</div><div class="col-md-4 text-success glyphicon glyphicon-saved"> '.lang('Balance : ').$c_point.'</div></div>';
											?>

											<p class="introtext"><?= lang('list_results'); ?></p>
											<div class="table-responsive">
												<table id="<?php echo $value; ?>" cellpadding="0" cellspacing="0" border="0"
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
									<?php endforeach; ?>
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
					
					<!-- div style="display:none" class="total_point"><strong style="line-height: 42px;" ><?php echo  lang('total_point : ').$totalpoint; ?> <?php echo lang("points"); ?></strong></div> -->
					<div class="box-content panel-body">
						<div class="row r-header">
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
								</table>
							</div>
						</div>
					</div>
					<script>
						setTimeout(function(){
							$("#AWArdpoints_wrapper .row:first-child .text-right").append($(".total_point").html());
						}, 800);
					</script>
				</div>

				<?php #ประวัติการแลกแต้มสะสม ?>
				<?php /*<div class="box">
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
												<th><?= lang("status"); ?></th>
												<th></th>
												<th><?= lang("level"); ?></th>
												<th><?= lang("qty"); ?></th>
												<th></th>
											</tr>
											</thead>
											<tbody>
											<tr>
												<td colspan="8" class="dataTables_empty"><?= lang("loading_data"); ?></td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div> */ ?>
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