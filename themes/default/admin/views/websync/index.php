<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php	


?>
<script type="text/javascript">
    $(document).ready(function () {
        oTable = $('#BTFTable').dataTable({
            "aaSorting": [[1, "asc"], [2, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('websync/getWebsync') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
				//console.log(aoData);
            },
            "aoColumns": [
			null, 
			null, 
			null, 
			null, 
			null,
			null,
			null,
			null
			]
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-refresh"></i><?= lang('Web Sync'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
             <?php /*   <li class="dropdown"><a href="<?= admin_url('banktransfer/add'); ?>" data-toggle="modal"
                                        data-target="#myModal"><i class="icon fa fa-plus"></i></a></li>*/ ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="BTFTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line("0"); ?></th>
                            <th style="width: 170px;"><?php echo $this->lang->line("1"); ?></th>
                            <th style="width: 170px;"><?php echo $this->lang->line("2"); ?></th>
                            <th style="width: 170px;"><?php echo $this->lang->line("3"); ?></th>
                            <th style="width:90px;"><?php echo $this->lang->line("4"); ?></th>
							<th style="width:90px;"><?php echo $this->lang->line("5"); ?></th>
							<th style="width:90px;"><?php echo $this->lang->line("6"); ?></th>
							<th style="width:90px;"><?php echo $this->lang->line("7"); ?></th>
							<th style="width:90px;"><?php echo $this->lang->line("8"); ?></th>
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
