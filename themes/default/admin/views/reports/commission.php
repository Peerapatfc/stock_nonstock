<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

$v = "";
if ($this->input->post('user')) {
    $v .= "&user=" . $this->input->post('user');
}

if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}

?>

<script>
    $(document).ready(function () {
        oTable = $('#SlRData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('reports/getCommissionReport/?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				
                nRow.id = aData[9]; 
                nRow.className = (aData[5] > 0) ? "invoice_link2" : "invoice_link2 warning";
                return nRow;
            },
            "aoColumns": [{"mRender": fld}, null, null, null, {"bSearchable": false,"mRender": pqFormat}, {"mRender": currencyFormat}, 
			//{"mRender": currencyFormat}, 
			//{"mRender": currencyFormat}, 
			{"mRender": row_status}
			//{"bVisible": false}
			],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][5]);
                   // paid += parseFloat(aaData[aiDisplay[i]][6]);
                    balance += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = currencyFormat(parseFloat(gtotal));
                //nCells[6].innerHTML = currencyFormat(parseFloat(paid));
                //nCells[7].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            // {column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            // {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            // {column_number: 2, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
            // {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            // {column_number: 8, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>


<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
   content:"\f0a4";
}
</style>
        <h2 class="blue"><i class="glyphicon glyphicon-hand-right"></i><?= lang('commission_report'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?>
        </h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
			</ul>
		</div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('customize_report'); ?></p>
                <div id="form">
                    <?php echo admin_form_open("reports/commission"); ?>
                    <div class="row">
                        <div class="col-sm-3">
							<div class="row">
							<div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<?= lang("end_date", "end_date"); ?>
									<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
								</div>
							</div>
							</div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("staff"); ?></label>
                                <?php
                                $us[""] = lang('select').' '.lang('user');
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

                <div class="table-responsive2">
                    <table id="SlRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
                            <th style="min-width:120px;"><?= lang("date"); ?></th>
                            <th style="min-width:150px;"><?= lang("reference_no"); ?></th>
                            <th style="min-width:150px;"><?= lang("biller"); ?></th>
                            <th style="min-width:150px;"><?= lang("customer"); ?></th>
                            <th style="min-width:240px;"><?= lang("product_qty"); ?></th>
                            <th style="min-width:120px;"><?= lang("grand_total"); ?></th>
                            <th style="min-width:120px;"><?= lang("payment_status"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?= lang("product_qty"); ?></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=admin_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=admin_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>