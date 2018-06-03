<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
		<?php $stt = array('ready' => lang('ready'), 'packing' => lang('packing'), 'delivering' => lang('delivering'), 'delivered' => lang('delivered')); ?>
        var dss = <?= json_encode($stt); ?>;
		function ds(x) {
            if (x == 'delivered') {
                return '<div class="text-center"><span class="label label-success">'+(dss[x] ? dss[x] : x)+'</span></div>';
            } else if (x == 'delivering') {
                return '<div class="text-center"><span class="label label-primary">'+(dss[x] ? dss[x] : x)+'</span></div>';
            } else if (x == 'packing') {
                return '<div class="text-center"><span class="label label-warning">'+(dss[x] ? dss[x] : x)+'</span></div>';
            } else if (x == 'ready') {
                return '<div class="text-center"><span class="label label-danger">'+(dss[x] ? dss[x] : x)+'</span></div>';
            }
            return x;
            return (x != null) ? (dss[x] ? dss[x] : x) : x;
        }
		
		<?php foreach($stt as $key => $val): ?>
		var ckstatus = '<?php echo $key ?>';
		if(ckstatus == "delivering" || ckstatus == "delivered"){
        oTable = $('#<?php echo $key; ?>').dataTable({
            "aaSorting": [[8, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('sales/fn'.$key) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "delivery_link";
				var ck = '<?php echo $Owner; ?>';
				if(ck != 1){ nRow.className = "disable";}
				if (aData[3] != null)
				{
					$('td:eq(3)', nRow).html( '<div class="text-center">'+ aData[3] +'</div>' );
				}
				if (aData[5] != null)
				{
					$('td:eq(5)', nRow).html( '<div class="text-center"><span class="label label-danger">'+ aData[5] +'</span></div>' );
				}
				
				if(aData[6] != null){
					var text = "";
					if(aData[6] == "ems"){
						text = '<?php echo lang("ems"); ?>';
					}else if(aData[6] == "kerry"){
						text = '<?php echo lang("kerry"); ?>';
					}else{
						text =aData[6];
					}
					
					$('td:eq(6)', nRow).html( '<div class="text-center"><span">'+ text +'</span></div>' );
				}
				
	
				
				//order_type
				console.log(aData);
				if(aData[7] == "dropship"){
					var data_col2 = '<div class="text-center"><span style="background-color:#FF7600;" class="row_status order-status label"><i class="fa fa-paper-plane" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}else{
					var data_col2 = '<div class="text-center"><span style="background-color:#123EAB;" class="row_status order-status label"><i class="fa fa-cubes" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}
				$('td:eq(1)', nRow).html(data_col2);
				
				
				//วันเวลา
				var date = aData[9].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(8)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				
                return nRow;
            },
            //"aoColumns": [{"bSortable": false,"mRender": checkbox},  null, null, null, {"mRender": ds}, {"bSortable": false}]
            "aoColumns": [
				{"bSortable": false,"mRender": checkbox},
				null,
				null, //customer
				null, //phone
				null, //address
				null,
				null,
				{"bVisible": false},
				{"mRender": ds},
				null, //date
				{"bSortable": false},
			]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('sale_reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('address');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('tracking');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('shipping_title');?>]", filter_type: "text", data: []},
			{column_number: 9, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
        ], "footer");	
		}else{
        oTable = $('#<?php echo $key; ?>').dataTable({
            "aaSorting": [[7, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('sales/fn'.$key) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "delivery_link";
				var ck = '<?php echo $Owner; ?>';

				if(ck != 1){ nRow.className = "disable";}
				if (aData[3] != null)
				{
					$('td:eq(3)', nRow).html( '<div class="text-center">'+ aData[3] +'</div>' );
				}
				
				//order_type
				if(aData[6] == "dropship"){
					var data_col2 = '<div class="text-center"><span style="background-color:#FF7600;" class="row_status order-status label"><i class="fa fa-paper-plane" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}else{
					var data_col2 = '<div class="text-center"><span style="background-color:#123EAB;" class="row_status order-status label"><i class="fa fa-cubes" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}
				$('td:eq(1)', nRow).html(data_col2);
				
				if(aData[5] != null){
					var text = "";
					if(aData[5] == "ems"){
						text = '<?php echo lang("ems"); ?>';
					}else if(aData[5] == "kerry"){
						text = '<?php echo lang("kerry"); ?>';
					}else{
						text =aData[5];
					}
					
					
					$('td:eq(5)', nRow).html( '<div class="text-center"><span">'+ text +'</span></div>' );
				}
				
				
				//วันเวลา
				var date = aData[8].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(7)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				
                return nRow;
            },
            "aoColumns": [
				{"bSortable": false,"mRender": checkbox},
				null,
				null,
				null,
				null,
				null,
				{"bVisible": false},
				{"mRender": ds},
				null, //date
				{"bSortable": false},
			]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('sale_reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('address');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('shipping_title');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
        ], "footer");
		}
		<?php endforeach; ?>

		// when close modal tracking will refresh page
		$("#myModal").on("hidden.bs.modal", function(){
			window.location.href='<?php echo site_url('/admin/sales/deliveries'); ?>';
		});
    });
</script>

<?php if ($Owner) { ?><?= admin_form_open('sales/delivery_actions', 'id="action-form" name="delivery_form"') ?><?php } ?>
<div class="box">
    <div class="box-header">
	<style>
	.pageheader .icon::before {
		content: "\f0d1";
	}
	</style>
        <h2 class="blue"><i class="fa-fw fa fa-truck"></i><?= lang('deliveries'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo" title="<b><?= $this->lang->line("delete_deliveries") ?></b>" 
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" 
                                data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?= lang('delete_deliveries') ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
					<?php 
					$countready = empty($ready) ? 0 : sizeof($ready);
					$countpacking = empty($packing) ? 0 : sizeof($packing);
					$countdelivering = empty($delivering) ? 0 : sizeof($delivering);
					$countdelivered = empty($delivered) ? 0 : sizeof($delivered);
					?>
				<ul id="dbTab" class="nav nav-tabs tab_delivering">
					<?php /*foreach($stt as $key => $val): ?>
						<li class=""><a href="#tb_<?php echo $key; ?>"><?= $val ?><span><?php echo sizeof($ready); ?></span></a></li>
					<?php endforeach;*/ ?>
                        <li class=""><a href="#tb_ready"><i class="fa fa-truck" aria-hidden="true"></i> <?= lang('ready') ?><span style="margin-left:3px;background-color: #d9534f;" class="badge badge-warning"><?php echo $countready; ?></span></a></li>
                        <li class=""><a href="#tb_packing"><i class="fa fa-dropbox" aria-hidden="true"></i>
<?= lang('packing') ?><span style="margin-left:3px;background-color: #f0ad4e;" class="badge"><?php echo $countpacking; ?></span></a></li>
                        <li class=""><a href="#tb_delivering"><i class="fa fa-paper-plane" aria-hidden="true"></i>
<?= lang('delivering') ?><span style="margin-left:3px;background-color: #428bca;" class="badge"><?php echo $countdelivering; ?></span></a></li>
                        <li class=""><a href="#tb_delivered"><i class="fa fa-hand-peace-o" aria-hidden="true"></i>
<?= lang('delivered') ?><span style="margin-left:3px;background-color: #5cb85c;" class="badge"><?php echo $countdelivered; ?></span></a></li>
                </ul>
				   
				<div id="page-delivered" class="tab-content">
				<?php foreach($stt as $key => $val): ?>
                <div id="<?php echo 'tb_'.$key; ?>" class="tab-pane fade in">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive2" id="tb_deliveries">
								<table id="<?php echo $key ?>" class="table table-bordered table-hover table-striped table-condensed">
									<thead>
									<tr>
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkftdeliveries" datack="<?php echo $key ?>" type="checkbox" name="check"/>
										</th>
										<th class="sale_reference_no"><?= lang("sale_reference_no"); ?></th>
										<th style="min-width: 167px;"><?= lang("customer"); ?></th>
										<th style="min-width: 101px;"><?= lang("phone"); ?></th>
										<th style="min-width: 490px;"><?= lang("address"); ?></th>
										<?php if($key == "delivering" || $key == "delivered"){?>
											<th><?= lang("tracking"); ?></th>
										<?php } ?>
										<th style="min-width: 101px;"><?=lang('shipping_title');?></th>
										<th ></th>
										<th class="thstatus"><?= lang("status"); ?></th>
										<th class="thdate"><?= lang("date"); ?></th>
										<th style="width:100px; text-align:center;"><?= lang("actions"); ?></th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td colspan="7" class="dataTables_empty"><?= lang("loading_data"); ?></td>
									</tr>
									</tbody>
									<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkftdeliveries" datack="<?php echo $key ?>" type="checkbox" name="check"/>
										</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<?php if($key == "delivering" || $key == "delivered"){?>
											<th></th>
										<?php } ?>
										<th></th>
										<th></th>
										<th>[<?=lang('status');?>]</th>
										<th></th>
										<th style="width:100px; text-align:center;"><?= lang("actions"); ?></th>
									</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					
					<div style="display:none" class="buttons" data-btn = "<?php echo $key; ?>">
						<div class="btn-group btn-group-justified">
							<div class="btn-group">
							<?php if($key == 'ready'): ?>
								<a style="max-width:200px" href="#" class="tip btn btn-danger" id="acprint_packing" data-action="acprint_packing" data-original-title="<?php echo lang('print & packing'); ?>" data-action-url="<?php echo site_url('admin/sales/print_shipping_label');?>">
								<i class="fa fa-print" aria-hidden="true"></i> 
								<span><?= lang('print & packing') ?></span>
								</a>
							<?php elseif($key == 'packing'): ?>
								<a style="max-width:200px" class="tip btn btn-danger" href='<?php echo admin_url("sales/tracking/") ?>' data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="<?php echo lang("tracking"); ?>"> 
									<i class="fa fa-truck" aria-hidden="true"></i>
									<span><?php echo lang('add_tracking'); ?></span>
								</a>
							<?php elseif($key == 'delivering'): ?>
								<table>
									<tr>
										<td>
											<a style="max-width:200px" href="#" class="tip btn btn-danger" id="accomplete" data-action="complete" data-original-title="<?php echo lang('complete'); ?>">
												<i class="fa fa-check" aria-hidden="true"></i>
												<span><?php echo lang('complete'); ?></span>
											</a>
										</td>
										<td>&nbsp;</td>
										<td>
											<a style="max-width:200px" href="#" class="tip btn btn-danger" id="acsendsmstracking" data-action="send_sms_tracking" data-original-title="<?php echo lang('send_sms'); ?>">
												<i class="fa fa-comments" aria-hidden="true"></i>
												<span><?php echo lang('send_sms'); ?></span>
											</a>
										</td>
									</tr>
								</table>
							<?php elseif($key == 'delivered'): ?>
								<a style="max-width:200px" href="#" class="tip btn btn-danger" id="acsendsmstracking2" data-action="send_sms_tracking" data-original-title="<?php echo lang('send_sms'); ?>">
									<i class="fa fa-comments" aria-hidden="true"></i>
									<span><?php echo lang('send_sms'); ?></span>
								</a>
							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
				</div>

            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('perform_action', 'perform_action', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            // print shipping label
            $(document).on('click', "#acprint_packing", function(e){
            	e.preventDefault();
            	var action = $(this).attr('data-action-url');
            	$('#form_action').val($(this).attr('data-action'));
			    $("form[name=delivery_form]").attr("action",action);
			    $("form[name=delivery_form]").attr("target","delivery_form");
			    var viewportwidth = document.documentElement.clientWidth;
			    window.open("","delivery_form","width=500,height="+(viewportwidth-300)+",top=0,left=1000,toolbar=0");
			    $('#action-form-submit').click();
			    window.location.href='<?php echo site_url('admin/sales/deliveries'); ?>';
            });
            // $(document).on('click', '#delete,#acprint_packing,#acpacking,#accomplete', function(e) {
            $(document).on('click', '#delete,#acpacking,#accomplete,#acsendsmstracking,#acsendsmstracking2', function(e) {
                e.preventDefault();
                $('#form_action').val($(this).attr('data-action'));
                //$('#action-form').submit();
                $('#action-form-submit').click();
            });
        });
    </script>
<?php } else {
	echo '<style>.box-header .box-icon, #tb_deliveries tbody td:last-child,#tb_deliveries  th:last-child{display:none}</style>';
}