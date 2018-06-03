<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<?php if ($Owner) { ?><?= admin_form_open('sales/delivery_actions', 'id="action-form"') ?><?php } ?>

<?php
function action($x){
	    $detail_link = anchor('admin/sales/view_delivery/'.$x, '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/'.$x, '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/'.$x, '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/'.$x, '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
		$delete_link = '<a href="'. admin_url('sales/deliveriesdelete/'.$x) .'" data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="'.lang('Delete Sale').'"><i class="fa fa-trash-o"></i> '.lang('Delete Sale').'</a>';

        $action = '<div class="text-center"><div class="btn-group text-left">'
			. '<button type="button" class="btn btn-default btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
			. lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';
		return $action;
}
?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-truck"></i><?= lang('deliveries'); ?></h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12">
                        <ul id="dbTab" class="nav nav-tabs">
                            <li class=""><a href="#ready"><?= lang('ready') ?></a></li>
                            <li class=""><a href="#packing"><?= lang('packing') ?></a></li>
                            <li class=""><a href="#delivering"><?= lang('delivering') ?></a></li>
                            <li class=""><a href="#delivered"><?= lang('delivered') ?></a></li>
                        </ul>

                        <div class="tab-content">
                        <?php if ($Owner || $Admin) { ?>
                            <div id="ready" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="sales-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("customer"); ?></th>
													<th><?= $this->lang->line("address"); ?></th>
													<th><?= $this->lang->line("delivery"); ?></th>
													<th><?= $this->lang->line("sale_no"); ?></th>
													<th><?= $this->lang->line("status"); ?></th>
													<th><?= $this->lang->line("actions"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($ready)) {
													//echo sizeof($ready);
                                                    $r = 1;
                                                    foreach ($ready as $order) {
														$c =  isset($order->is_delete) ? "danger" : "";
                                                        echo '<tr id="' . $order->id . '" class="'. $c .' delivery_link">
															<td class="">' . $r . '</td>
															<td class="">' . $this->sma->hrld($order->date) . '</td>
															<td class="">' . $order->customer . '</td>
															<td class="">' . $order->address . '</td>
															<td class="">' . $order->do_reference_no . '</td>
															<td class="">' . $order->sale_reference_no . '</td>
															<td class=""><div class="text-center"><span class="label label-danger">'.$order->status.'</span></div></td>
															<td>'. action($order->id) .'</td>
														</tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="7"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php } if ($Owner || $Admin) { ?>
                            <div id="packing" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="packing-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("customer"); ?></th>
													<th><?= $this->lang->line("address"); ?></th>
													<th><?= $this->lang->line("delivery"); ?></th>
													<th><?= $this->lang->line("sale_no"); ?></th>
													<th><?= $this->lang->line("status"); ?></th>
													<th><?= $this->lang->line("actions"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($packing)) {
                                                    $r = 1;
                                                    foreach ($packing as $order) {
														$c =  isset($order->is_delete) ? "danger" : "";
                                                        echo '<tr id="' . $order->id . '" class="'. $c .' delivery_link">
															<td class="">' . $r . '</td>
															<td class="">' . $this->sma->hrld($order->date) . '</td>
															<td class="">' . $order->customer . '</td>
															<td class="">' . $order->address . '</td>
															<td class="">' . $order->do_reference_no . '</td>
															<td class="">' . $order->sale_reference_no . '</td>
															<td class=""><div class="text-center"><span class="label label-warning">'.$order->status.'</span></div></td>
															<td>'. action($order->id) .'</td>
														</tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php } if ($Owner || $Admin) { ?>

                            <div id="delivering" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="delivering-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("customer"); ?></th>
													<th><?= $this->lang->line("address"); ?></th>
													<th><?= $this->lang->line("delivery"); ?></th>
													<th><?= $this->lang->line("sale_no"); ?></th>
													<th><?= $this->lang->line("status"); ?></th>
													<th><?= $this->lang->line("actions"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($delivering)) {
                                                    $r = 1;
                                                    foreach ($delivering as $order) {
														$c =  isset($order->is_delete) ? "danger" : "";
                                                        echo '<tr id="' . $order->id . '" class="'. $c .' delivery_link">
															<td class="">' . $r . '</td>
															<td class="">' . $this->sma->hrld($order->date) . '</td>
															<td class="">' . $order->customer . '</td>
															<td class="">' . $order->address . '</td>
															<td class="">' . $order->do_reference_no . '</td>
															<td class="">' . $order->sale_reference_no . '</td>
															<td class=""><div class="text-center"><span class="label label-primary">'.$order->status.'</span></div></td>
															<td>'. action($order->id) .'</td>
														</tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php } if ($Owner || $Admin) { ?>

                            <div id="delivered" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="delivered-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("customer"); ?></th>
													<th><?= $this->lang->line("address"); ?></th>
													<th><?= $this->lang->line("delivery"); ?></th>
													<th><?= $this->lang->line("sale_no"); ?></th>
													<th><?= $this->lang->line("status"); ?></th>
													<th><?= $this->lang->line("actions"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($delivered)) {
                                                    $r = 1;
                                                    foreach ($delivered as $order) {
														$c =  isset($order->is_delete) ? "danger" : "";
                                                        echo '<tr id="' . $order->id . '" class="'. $c .' delivery_link">
															<td class="">' . $r . '</td>
															<td class="">' . $this->sma->hrld($order->date) . '</td>
															<td class="">' . $order->customer . '</td>
															<td class="">' . $order->address . '</td>
															<td class="">' . $order->do_reference_no . '</td>
															<td class="">' . $order->sale_reference_no . '</td>
															<td class=""><div class="text-center"><span class="label label-success">'.$order->status.'</span></div></td>
															<td>'. action($order->id) .'</td>
														</tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="7"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php }?>
                        </div>

                    </div>

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
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                $('#form_action').val($(this).attr('data-action'));
                //$('#action-form').submit();
                $('#action-form-submit').click();
            });
        });
    </script>
<?php } ?>