<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#BrandTable').dataTable({
            "aaSorting": [[1, "asc"], [3, "asc"], [7, "asc"]],
            "aLengthMenu": [[20, 40, 60, 100, -1], [20, 40, 60, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": 20,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('system_settings/getShipping') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				console.log(aData);
				$('td:eq(1)', nRow).html('<input class="form-control text-center rordering" tabindex="" name="" value="'+aData[1]+'" data-id="'+aData[0]+'"  id="rordering'+aData[0]+'" type="text">');
				return nRow;
			},
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, {"bSortable": false, "mRender": img_hl}, null, null,null, null, null, {"bSortable": false}]
        });
		
		
		function focus_rordering(){
			$(".rordering").change(function(){
				var id = $(this).attr('data-id');
				var count = $(this).val();
				try {
					$.ajax({
						type: 'get',
						url: '<?= admin_url('system_settings/updateshipping'); ?>',
						dataType: "json",
						data: {
							id: id,
							count: count,
						},
						success: function (data) {
							$('#BrandTable').dataTable().fnDraw();
							location.reload();
						}
					});
				} catch (e) {
					console.log(e.message());
				}

			});
		}
		setInterval(function(){ 
			focus_rordering();
		}, 200);
    });
</script>
<?= admin_form_open('system_settings/shipping_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
    content: "\f0d1";
}
</style>
        <h2 class="blue"><i class="fa fa-truck" aria-hidden="true"></i><?= lang('shipping'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?php echo admin_url('system_settings/add_shipping'); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('add_shipping') ?>
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
                <div class="table-responsive2">
                    <table id="BrandTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkth" type="checkbox" name="check"/>
                                </th>
                                <th style="min-width:100px;"><?= lang("ordering"); ?></th>
                                <th style="min-width:85px; width: 40px; text-align: center;">
                                    <?= lang("image"); ?>
                                </th>
                                <th style="min-width:100px;"><?= lang("condition"); ?></th>
                                <th style="min-width:100px;"><?= lang("condition_from_value"); ?></th>
                                <th style="min-width:100px;"><?= lang("condition_to_value"); ?></th>
                                <th style="min-width:120px;"><?= lang("price"); ?></th>
                                <th style="min-width:100px;"><?= lang("delivery_type"); ?></th>
                                <th style="width:100px;"><?= lang("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="dataTables_empty">
                                    <?= lang('loading_data_from_server') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    <input type="hidden" name="form_action" value="" id="form_action"/>
    <?= form_submit('submit', 'submit', 'id="action-form-submit"') ?>
</div>
<?= form_close() ?>
<script language="javascript">
    $(document).ready(function () {
        $('#delete').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

        $('#update').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });
    });

</script>

