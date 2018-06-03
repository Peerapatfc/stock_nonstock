<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#APRTable').dataTable({
            "aaSorting": [[2, "asc"], [4, "desc"]],
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
			null,
			{"bSortable": false},
			],
			
			    'fnRowCallback': function (nRow, aData, iDisplayIndex) {
					
				//console.log(aData);
                var oSettings = oTable.fnSettings();
				var admin_url = '<?php echo admin_url('welcome/download/'); ?>';
				$('td:eq(0)', nRow).html('<div style="text-align:center"><img src="'+ admin_url+aData[0]+'" style="width: auto; max-height:45px;"></div>');
				
				//วันเวลา
				var date = aData[4];
				if('<?php echo date('Y-m-d'); ?>' == date){ date = "<?php echo lang('today'); ?>";}
				$('td:eq(4)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date + '</span>');
				
				var date = aData[3];
				if('<?php echo date('Y-m-d'); ?>' == date){ date = "<?php echo lang('today'); ?>";}
				$('td:eq(3)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date + '</span>');
				
				if(aData[6] == '1'){
					$('td:eq(5)', nRow).html('<div style="text-align:center"><?=lang('Pureplus Bonus 1'); ?></div>');
				}
				if(aData[6] == '2'){
					$('td:eq(5)', nRow).html('<div style="text-align:center"><?=lang('Pureplus Bonus 2'); ?></div>');
				}
				if(aData[6] == '3'){
					$('td:eq(5)', nRow).html('<div style="text-align:center"><?=lang('Pureplus Bonus 3'); ?></div>');
				}
				
				return nRow;
            },
			

        });
    });
</script>

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
							<th style="width: 170px;"><?php echo lang('level')?></th>
                            <th style="width:90px;"><?php echo $this->lang->line("exchange"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
	
				
                <!--<p><a href="<?php echo admin_url('award_points/add'); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php echo $this->lang->line("add_award_points"); ?></a></p>-->
            </div>
        </div>
    </div>
</div>

