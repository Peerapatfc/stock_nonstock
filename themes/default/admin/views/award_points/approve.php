<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php #ประวัติการแลกแต้มสะสม ?>
<script>
    $(document).ready(function () {
        oTable2 = $('#SPEntpoints').dataTable({
            "aaSorting": [[3, "desc"],[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('award_points/approvedata') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },"aoColumns": [
				{"bSortable": false,"mRender": checkbox},
				null,
				{"mRender": currencyFormat},
				null,
				null,
				//{"bVisible": false},
				null,
				null,
				null,
				//{"bSortable": false},
				
				
			],'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable2.fnSettings();
                nRow.id = aData[0];
				console.log(aData);
				
				//วันเวลา
				var date = aData[3].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(3)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				//approve
				if(aData[5] == 1){
					nRow.className = "id_approve success";
					$('td:eq(5)', nRow).html('<div style="text-align:center"><span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?= lang("approve"); ?></span></div>');
				}else if(aData[5] == 0){
					$('td:eq(5)', nRow).html('<div style="text-align:center"><span class="label label-danger"><i class="fa fa-close" aria-hidden="true"></i> <?= lang("disapprove"); ?></span></div>');
				}else{
					$('td:eq(5)', nRow).html('<div style="text-align:center"><span class="label label-warning"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <?= lang("pendding"); ?></span></div>');
				}

				
				//level
				if(aData[7] == '1'){
					$('td:eq(7)', nRow).html('<div style="text-align:center"><?=lang('Silver'); ?></div>');
				}
				if(aData[7] == '2'){
					$('td:eq(7)', nRow).html('<div style="text-align:center"><?=lang('Gold'); ?></div>');
				}
				if(aData[7] == '3'){
					$('td:eq(7)', nRow).html('<div style="text-align:center"><?=lang('VIP_Access'); ?></div>');
				}
				
				return nRow;
            }
			

        });
		

		var approve = '<a class="btn btn-success bt-approve" href="javascript:void(0)"><i class="fa fa-check"></i> <?php echo lang('approve');?></a>';
		var disapprove = '&nbsp;<a class="btn btn-danger bt-approve" href="javascript:void(0)"><i class="fa fa-close"></i> <?php echo lang('disapprove');?></a>';
		$("#SPEntpoints_length label").append(approve);
		$("#SPEntpoints_length label").append(disapprove);
		
    });
</script>

<?php echo admin_form_open_multipart("award_points/approvePointByAdmin", 'id="point-update"'); ?>
<div class="box">
    <div class="box-header">
	<style>
	.pageheader .icon::before {
		content: "\f0c9";
	}
	</style>
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('award_points'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= admin_url('award_points/add'); ?>" data-toggle="modal"
                                        data-target="#myModal"><i class="icon fa fa-plus"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
					<table id="SPEntpoints" class="table table-bordered table-hover table-striped table-condensed">
						<thead>
						<tr>
							<th></th>
							<th><?= lang("list_spent"); ?></th>
							<th><?= lang("spent_points"); ?></th>
							<th><?= lang("date"); ?></th>
							<th><?= lang("user_name"); ?></th>
							<th><?= lang("status"); ?></th>
							<th><?= lang("qty"); ?></th>
							<th><?= lang("level"); ?></th>
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
    </div>
</div>

<input type="hidden" value="1" name="approve" id="chk_approve" />
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).on('click', '.bt-approve', function(e) {
		e.preventDefault();
		if($(this).hasClass('btn-danger')){
			$('#chk_approve').attr('value',0);
		}else{
			$('#chk_approve').attr('value',1);
		}
		$('#point-update').submit();
	});
</script>