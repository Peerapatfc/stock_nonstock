<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
        <?php if ($inv) { ?>
        localStorage.setItem('sldate', '<?= $this->sma->hrld($inv->date) ?>');
        localStorage.setItem('slcustomer', '<?= $inv->customer_id ?>');
        localStorage.setItem('slbiller', '<?= $inv->biller_id ?>');
        localStorage.setItem('slref', '<?= $inv->reference_no ?>');
        localStorage.setItem('slwarehouse', '<?= $inv->warehouse_id ?>');
        localStorage.setItem('slsale_status', '<?= $inv->sale_status ?>');
        localStorage.setItem('slpayment_status', '<?= $inv->payment_status ?>');
        localStorage.setItem('slpayment_term', '<?= $inv->payment_term ?>');
        localStorage.setItem('slnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($inv->note)); ?>');
        localStorage.setItem('slinnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($inv->staff_note)); ?>');
        localStorage.setItem('sldiscount', '<?= $inv->order_discount_id ?>');
        localStorage.setItem('sltax2', '<?= $inv->order_tax_id ?>');
        localStorage.setItem('slshipping', '<?= $inv->shipping ?>');
        localStorage.setItem('slitems', JSON.stringify(<?= $inv_items; ?>));
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
        <?php } ?>
        $(document).on('change', '#slbiller', function (e) {
            localStorage.setItem('slbiller', $(this).val());
        });
        if (slbiller = localStorage.getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
        ItemnTotals();
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= admin_url('sales/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val(),
                        customer_id: $("#slcustomer").val()
                    },
                    success: function (data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });

        $(window).bind('beforeunload', function (e) {
            localStorage.setItem('remove_slls', true);
            if (count > 1) {
                var message = "You will loss data!";
                return message;
            }
        });
        $('#reset').click(function (e) {
            $(window).unbind('beforeunload');
        });
        $('#edit_sale').click(function () {
            $(window).unbind('beforeunload');
            $('form.edit-so-form').submit();
        });
    });
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');
                echo admin_form_open_multipart("sales/edit/" . $inv->id, $attrib)
                ?>


                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4 hidden">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->sma->hrld($inv->date)), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4 hidden">
                            <div class="form-group">
                                <?= lang("reference_no", "slref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="slref" required="required"'); ?>
                            </div>
                        </div>
                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="col-md-4">
								<div class="panel panel-warning">
								<div class="panel-body" style="padding: 5px;">
                                <div class="form-group">
                                    <?= lang("biller", "slbiller"); ?>
                                    <?php
                                    $bl[""] = "";
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $inv->biller_id), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
								</div>
								</div>
                            </div>
                        <?php } else {
                            $biller_input = array(
                                'type' => 'hidden',
                                'name' => 'biller',
                                'id' => 'slbiller',
                                'value' => $this->session->userdata('biller_id'),
                            );
                            echo form_input($biller_input);
                        } ?>

                        <div class="clearfix"></div>
							<?php 
								$vi = $vendor_inventory[0]->vendor_inventory;
								$viorder_type = '';
								$customerid = '';
								if($vi == 1){
									$viorder_type = 'stock';
									$customerid = $customeridbyuser[0]->id;

									}else if($vi == 2){
										$viorder_type = 'dropship';
									}
								?>

							<?php 
								if($vi != 1){ ?>
								<div style="margin-top:5px;margin-bottom: 5px;" class="col-md-12">
									<h2><i class="fa fa-group"></i><?= lang("customer"); ?></h2>
								</div>
							<?php } ?>
							
                        <div class="col-md-12">
                            <div class="<?php if($vi == 2 || $vi == 3) echo 'panel panel-warning'; ?>">
                                <div class="panel-body" style="padding: 5px;">

									<div class="col-md-6 customer-form">
                                        <div class="form-group">
                                            <?= lang("customer", "slcustomer"); ?>
                                            <div class="input-group">
                                                <?php
                                                    echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("search") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');
                                                ?>
                                                <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                    <a href="#" id="removeReadonly">
                                                        <i class="fa fa-unlock" id="unLock"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang("warehouse", "slwarehouse"); ?>
                                                <?php
                                                $wh[''] = '';
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->name;
                                                }
                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $inv->warehouse_id), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        $warehouse_input = array(
                                            'type' => 'hidden',
                                            'name' => 'warehouse',
                                            'id' => 'slwarehouse',
                                            'value' => $this->session->userdata('warehouse_id'),
                                        );
                                        echo form_input($warehouse_input);
                                    } ?>

									<div class="col-sm-6">
										<div class="form-group">
											<?= lang("order_type", "slorder_type"); ?>
											<?php 
										
											$slorder_type = array('dropship' => lang('dropship'), 'stock' => lang('stock'));
											echo form_dropdown('order_type', $slorder_type, $inv->order_type, 'class="form-control input-tip" required="required" id="slorder_type"'); ?>
										</div>
									</div>
									
									
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12 stickend" id="sticker">
								<div style="margin-top:30px;margin-bottom: 5px;">
									<h2><i class="fa fa-cubes"></i><?= lang("order_items"); ?> *</h2>
								</div>
							<p class="code"><code><?php echo lang('suggestion'); ?></code> <?php echo lang("Add item no need to specify product name or product code, press SPACE BAR to display all products list."); ?></p>
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                                        <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <?php /*<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
                                        </div>*/ ?>
										
										<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="javascript:void(0)" id="addSpaceBar" class="tip" title="<?= lang('add_product_manually') ?>">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <div class="controls table-controls">
                                    <table id="slTable"
                                           class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                        <thead>
                                        <tr>
                                            <th class="col-md-5"><?= lang('product') . ' (' . lang('code') .' - '.lang('name') . ')'; ?></th>
                                            <?php
                                            if ($Settings->product_serial) {
                                                echo '<th class="col-md-2">' . lang("serial_no") . '</th>';
                                            }
                                            ?>
                                            <th class="col-md-2"><?= lang("net_unit_price"); ?></th>
											
											
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <?php
                                            if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount') || $inv->product_discount)) {
                                                echo '<th class="col-md-1">' . lang("discount") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->tax1) {
                                                echo '<th class="col-md-1">' . lang("product_tax") . '</th>';
                                            }
                                            ?>
											<?php
                                            if ($Settings->award_use) {
                                                echo '<th class="col-md-1">' . lang("point") . '</th>';
                                            }
											?>
                                            <th><?= lang("subtotal"); ?></th>
                                            <th style="width: 30px !important; text-align: center;"><i
                                                    class="fa fa-trash-o"
                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if ($Settings->tax2) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("order_tax", "sltax2"); ?>
                                    <?php
                                    $tr[""] = "";
                                    foreach ($tax_rates as $tax) {
                                        $tr[$tax->id] = $tax->name;
                                    }
                                    echo form_dropdown('order_tax', $tr, (isset($_POST['order_tax']) ? $_POST['order_tax'] : $Settings->default_tax_rate2), 'id="sltax2" data-placeholder="' . lang("select") . ' ' . lang("order_tax") . '" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (($Owner || $Admin || $this->session->userdata('allow_discount')) || $inv->order_discount_id) { ?>
                        <div class="col-md-4 hidden">
                            <div class="form-group">
                                <?= lang("order_discount", "sldiscount"); ?>
                                <?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount" '.(($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"')); ?>
                            </div>
                        </div>
                        <?php } ?>

						<div class="clearfix"></div>
						
						<div class="shipment col-md-12">
						<div style="margin-top:10px;margin-bottom: 5px;">
                            <h2><i class="fa fa-truck"></i><?= lang("shipment"); ?></h2>
						</div>
						<div class="well clear">
                        <div class="col-md-2">
                            <div class="form-group">
								<?= lang("shipping_title", "shipping_title"); ?>
								<?php 
									echo form_dropdown('shippingtitle', $shippingtitle, $inv->delivery_type, 'class="form-control input-tip" required="required" id="shippingtitle"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("shipping", "slshipping"); ?>
                                <?php echo form_input('shipping', '', 'class="form-control input-tip" id="slshipping"'); ?>
                            </div>
                        </div>
						</div>
						<div id="bottom-total2" class="well well-sm" style="margin-bottom: 0;">
							<table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
								<tr class="warning">
									<td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
									<td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
								</tr>
							</table>
						</div>
						</div>
						
						
						<div class="clearfix"></div>	
						<div style="margin-top:30px; margin-bottom: 5px;" class="payment_title col-md-12">
                            <h2><i class="fa fa-credit-card"></i><?= lang("payment_confirm"); ?></h2>
						</div>
						<div class="col-md-12 payment_form">
						<div class="well cleartl">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("bank_to", "slbank_to"); ?>
                                <?php 
                                echo form_dropdown('bank_to', $bank, '', 'class="form-control input-tip" required="required" id="slbank_to"'); ?>
                            </div>
                        </div>	
						
                        <div class="col-md-6">
							<div class="row">
							<div class="col-md-8">
                            <div class="form-group">
								<?= lang("transfer_date", "sldate"); ?>
                                <?php echo form_input('date_cf_payment', date("d-m-Y", strtotime($inv->date_cf_payment)), 'class="form-control input-tip date"  id="date_cf_payment" required="required" data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?>
							</div>
							</div>
							
							
							<div class="col-md-4">
                            <div class="form-group">
								<?= lang("time", "sltime"); ?>
                                <?php echo form_input('time_cf_payment', date("H:i", strtotime($inv->date_cf_payment)), 'class="form-control input-tip time2" data-timepicker="" autocomplete="off" placeholder="__:__"  id="time_cf_payment" required="required" data-bv-notempty-message="'.lang("Enter the correct time").'"'); ?>
							</div>
							</div>
							</div>
                        </div>
						
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("transfer_total", "sltotal_cf_payment"); ?>
                                <?php echo form_input('total_cf_payment', '', 'class="form-control input-tip" required="required" id="sltotal_cf_payment"'); ?>

                            </div>
                        </div>
						
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("transfer_slip", "document") ?>
                                <input id="document" type="file" data-remove-label="<?= lang('remove'); ?>" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

						<?php #print_r($inv->sale_status); ?>
                        <div class="col-sm-4 hidden">
                            <div class="form-group">
                                <?= lang("sale_status", "slsale_status"); ?>
								<?php $sst = array('wait' => lang('wait'), 'completed' => lang('completed'));
                                echo form_dropdown('sale_status', $sst, $inv->sale_status, 'class="form-control input-tip" required="required" id="slsale_status"'); ?>
                            </div>
                        </div>
						
     
                        <div class="col-sm-4 hidden">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
                                <?php echo form_input('payment_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>

                            </div>
                        </div>
						
						
 
						
						<?php #echo $inv->payment_status; ?>
                        <?php echo  form_hidden('payment_status', $inv->payment_status); ?>
						
                        <div class="clearfix"></div>

						                            </div>
                        </div>
						
                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <div class="row hidden" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("sale_note", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("staff_note", "slinnote"); ?>
                                        <?php echo form_textarea('staff_note', (isset($_POST['staff_note']) ? $_POST['staff_note'] : ""), 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('edit_sale', lang("submit"), 'id="edit_sale" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="punit" class="col-sm-4 control-label"><?= lang('product_unit') ?></label>
                        <div class="col-sm-8">
                            <div id="punits-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice" <?= ($Owner || $Admin || $GP['edit_price']) ? '' : 'readonly'; ?>>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="mserial" class="col-sm-4 control-label"><?= lang('product_serial') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mserial">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount" class="col-sm-4 control-label">
                                <?= lang('product_discount') ?>
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#gccustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });

		  $('*[data-timepicker]').attr('autocomplete','off').keydown(function(e){
			// Input Value var
			var inputValue = $(this).val();
			if(e.keyCode >= 96){val = e.keyCode - 48;}else{val = e.keyCode;}
			// Make sure keypress value is a Number
			if( (val > 47 && val < 58) || val == 8){
			  // Make sure first value is not greater than 2
			  if(inputValue.length == 0){
				if(val > 49){
				  e.preventDefault();
				  $(this).val(2);
				  over = 1;
				}else{
				  over = 0;
				}
			  }

			  // Make sure second value is not greater than 4
			  else if(inputValue.length == 1 && val != 8){
				e.preventDefault();
				//console.log(over);
				if(val > 50 && over == 1){
				  $(this).val(inputValue + '3:');
				}
				else{
				  $(this).val(inputValue + String.fromCharCode(val) + ':');
				}
			  }

			  else if(inputValue.length == 2 && val != 8){
				e.preventDefault();
				if(val > 52 ){
				  $(this).val(inputValue + ':5');
				}
				else{
				  $(this).val(inputValue + ':' + String.fromCharCode(val));
				}
			  }

			  // Make sure that third value is not greater than 5
			  else if(inputValue.length == 3 && val != 8){
				if(val > 52 ){
				  e.preventDefault();
				  $(this).val( inputValue + '5' );
				}
			  }

			  // Make sure only 5 Characters can be input
			  else if(inputValue.length > 4 && val != 8){
				e.preventDefault();
				return false;
			  }
			}
			// Prevent Alpha and Special Character inputs
			else{
			  e.preventDefault();
			  return false;
			}
		  }); // End Timepicker KeyUp function
		  
		  
		var wallet_use = '<?php echo $Settings->wallet_use; ?>';
		
		//485 shipping title
		function shippingtitle(delivery_type){
			
			if(delivery_type == null){
				delivery_type = jQuery('#shippingtitle > option:first-child').val();
			}

			var item_val = count-1;
			var condition = '<?php echo $Settings->default_shipping; ?>';
			var is_total = 0;

			if(condition == 'price'){
				is_total = total;
			}else{
				is_total =  item_val;
			}
			try {
                $.ajax({
                    type: 'get',
                    url: '<?= admin_url('sales/shipping'); ?>',
                    dataType: "json",
                    data: {
                        condition_name: condition,
						is_price: is_total,
						delivery_type: delivery_type,
                    },
                    success: function (data) {
						if(data[0].price == 'null'){
							
						}else{
							var shipping = formatDecimal(data[0].price);
							jQuery('#slshipping').val(shipping);
							var gtotal = total + shipping;
							var gtotal = ((total + invoice_tax) - order_discount) + shipping;
							
							$('#sltotal_cf_payment').val(formatMoney(gtotal));
							if(wallet_use == "1"){
								var wallet_sum = $('.wallet_sum').html();
								
								wallet_sum_float = wallet_sum.replace(/\,/g,'');
								//console.log(wallet_sum);

								var payment_method = $('.payment_method .iradio_square-blue.checked').find('input').val();
								//console.log(payment_method);
								if(wallet_sum_float < gtotal){
									$('#add_sale').attr('disabled','disabled');
								}else{
									$('#add_sale').removeAttr('disabled');
								}
								
								if(payment_method=='wallet'){
									jQuery(".payment_title,.payment_form").hide();
								}else{
									jQuery(".payment_title,.payment_form").show();
								}
							}
								$('#gtotal').text(formatMoney(gtotal));
								$('#tship').text(formatMoney(shipping));
							
						}
                    }, error: function () {
						
					}
                });
			} catch (e) {
				console.log(e.message());
			}
		}

		jQuery('#shippingtitle').on('change', function(){
			var delivery_type = this.value;
			shippingtitle(delivery_type);
		});
		
		setTimeout(function(){
			shippingtitle();

		},800);
		

		
		
		jQuery('a#addSpaceBar').click(function(){
			$.ajax({
				type: 'get',
				url: '<?= admin_url('sales/suggestions'); ?>',
				dataType: "json",
				data: {
					term: ' ',
				},
				 success: function(data){
					$(this).removeClass('ui-autocomplete-loading');
					$("#add_item").focus().val(" ");
					$("#add_item").focus().autocomplete("search");
				}
			});
		});
		
		jQuery("#shippingtitle").change(function(){
			var shipping_title = jQuery(this).val();
			if(shipping_title=="kerry"){
				jQuery(".payment_title,.payment_form").hide();
			}else{
				jQuery(".payment_title,.payment_form").show();
			}
		});
		
		
		
		if(wallet_use == "1"){
			jQuery("#payment-method").change(function(){
			
				var payment_method = jQuery(this).val();
				
				if(payment_method=="wallet"){
					jQuery(".payment_title,.payment_form").hide();
				}else{
					jQuery(".payment_title,.payment_form").show();
				}
			});

			$(document).ready(function () {
				$('#payment-method + ins').click(function(){
					var payment_parent = $(this).parent();
					var payment_val = payment_parent.find('input').val();
					var gtotal = $('#gtotal').html();
					var wallet_sum = $('.wallet_sum').html();
					
					gtotal = parseFloat(gtotal.replace(/\,/g,''));
					wallet_sum = parseFloat(wallet_sum.replace(/\,/g,''));
					//console.log(gtotal)
					//console.log(wallet_sum);
					if(payment_val=="wallet"){
						$('.wallet_info').show();
						$('.payment_title,.payment_form').hide();
						if(wallet_sum <  gtotal){
							$('#add_sale').attr('disabled','disabled');
						}else{
							$('#add_sale').removeAttr('disabled');
						}
					}else{
						$('.wallet_info').hide();
						$('.payment_title,.payment_form').show();
						$('#add_sale').removeAttr('disabled');
					}
				});
			});
		}

		//inventory stock dropship 
		var permission = '<?php echo $permissiong; ?>';
		if(permission != 1){
			var order_type = '<?php echo $viorder_type; ?>';
			setTimeout(function(){
				if(order_type == "stock"){
						$('#slcustomer').attr('value','<?php echo $customeridbyuser[0]->id; ?>');
						$('.customer-form .select2-chosen').text('<?php echo $customeridbyuser[0]->name; ?>');
						$('#s2id_slcustomer').addClass('select2-container-disabled').css({"pointer-events": "none"});
						$('.customer-form  .input-group-addon').css({"visibility" : "hidden"});
						
				}
			}, 1200);

			$('#slorder_type').on("change",function(e){
				var input_val = $('#slorder_type').val();
				if(input_val == "stock"){
					$('#slcustomer').attr('value','<?php echo $customeridbyuser[0]->id; ?>');
					$('.customer-form .select2-chosen').text('<?php echo $customeridbyuser[0]->name; ?>');
					$('#s2id_slcustomer').addClass('select2-container-disabled').css({"pointer-events": "none"});
					$('.customer-form  .input-group-addon').css({"visibility" : "hidden"});
				}else{
					$('#slcustomer').attr('value','');
					$('.customer-form .select2-chosen').text('<?php echo lang("select") . ' ' . lang("customer"); ?>');
					$('#s2id_slcustomer').removeClass('select2-container-disabled').css({"pointer-events" : "all"});
					$('.customer-form  .input-group-addon').css({"visibility" : "visible"});
				}
			});
		}

    });
</script>