<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#BTFTable').dataTable({
            "aaSorting": [[1, "asc"], [2, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('banktransfer/getBanktransfer') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [null, null, null, null, {"bSortable": false}]
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
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('banktransfer'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= admin_url('banktransfer/add'); ?>" data-toggle="modal"
                                        data-target="#myModal"><i class="icon fa fa-plus"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive2">
                    <table id="BTFTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width: 190px;"><?php echo $this->lang->line("bank"); ?></th>
                            <th style="min-width: 190px;"><?php echo lang('account_name')?></th>
                            <th style="min-width: 170px;"><?php echo lang("account_number"); ?></th>
                            <th style="min-width: 80px;"><?php echo lang("nickname"); ?></th>
                            <th style="min-width:90px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!--<p><a href="<?php echo admin_url('banktransfer/add'); ?>" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php echo $this->lang->line("add_banktransfer"); ?></a></p>-->
            </div>
        </div>
    </div>
</div>

