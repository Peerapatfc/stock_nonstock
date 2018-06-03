<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button id="btn-receipt" type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" ">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
			<div style="display: none;">
				<form class="hidden" id ="receipt_pdf" action="<?= admin_url('sales/pdf/' . $inv->id); ?>">
					<input name="performAction" value="performAction" id="receipt-pdf-submit" class="input-xs" type="submit">
				</form>
			</div>



            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <?php echo lang("order_detail")." ".($inv->reference_no); ?>
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-md-5">
                    <p class="bold view-information-order">
                        <em class="text-title"><?= lang("date"); ?></em>: <?= $this->sma->hrld($inv->date); ?><br>
                        <em class="text-title"><?= lang("ref"); ?></em>: <?= $inv->reference_no; ?><br>
                        <?php if (!empty($inv->return_sale_ref)) {
                            echo '<em class="text-title">'.lang("return_ref").'</em>: '.$inv->return_sale_ref;
                            if ($inv->return_id) {
                                echo ' <a data-target="#myModal2" data-toggle="modal" href="'.admin_url('sales/modal_view/'.$inv->return_id).'"><i class="fa fa-external-link no-print"></i></a><br>';
                            } else {
                                echo '<br>';
                            }
                        } ?>
                        <em class="text-title"><?= lang("sale_status"); ?></em>: <?= lang($inv->sale_status); ?><br>
                        <em class="text-title"><?= lang("payment_status"); ?></em>: <?= lang($inv->payment_status); ?>
						<?php if($inv->delivery_type): ?>
						<br><em class="text-title"><?= lang("delivery_type"); ?></em>: <?= lang($inv->delivery_type); ?>
						<?php endif; ?>
						
						<?php if ($inv->order_type || $inv->order_type != "") { ?>
							<?php 
								if($inv->order_type == "dropship"):
									$_icon = '<span  style="border-radius: 3px;color:#fff; padding: 3px 8px;font-size: 100%;background-color:#FF7600;"><i class="fa fa-paper-plane" aria-hidden="true"></i> '.lang($inv->order_type).'</span>';
								else:
									$_icon = '<span  style="border-radius: 3px;color:#fff; padding: 3px 8px;font-size: 100%;background-color:#123EAB;"><i class="fa fa-cubes" aria-hidden="true"></i> '.lang($inv->order_type).'</span>';
								endif;
							?>
							<br><em class="text-title"><?= lang("order_type"); ?></em>: <?php echo $_icon; ?>
						<?php } ?>

						<?php if($inv->is_deliveries){ ?>
							<br><em class="text-title"><?= lang("tracking"); ?></em>: <?php echo  !is_null($deliveries->tracking) ? '<span class="panel-success"><i class="fa fa-truck" aria-hidden="true"></i> '.$deliveries->tracking.'</span>' : '<span class="panel-danger"><i class="fa fa-truck" aria-hidden="true"></i> '.lang("Pending_tracking").'</span>'; ?>
						<?php }else{ ?>
							<br><em class="text-title"><?= lang("tracking"); ?></em>: <?php echo  !is_null($deliveries->tracking) ? '<span class="panel-success"><i class="fa fa-truck" aria-hidden="true"></i> '.$deliveries->tracking.'</span>' : '<span class="panel-danger"><i class="fa fa-truck" aria-hidden="true"></i> '.lang("Pending_order").'</span>'; ?>
						<?php } ?>
                    </p>
                    </div>
                    <div class="col-md-7 text-right order_barcodes">
                        <?= $this->sma->save_barcode($inv->reference_no, 'code128', 66, false); ?>
                        <?= $this->sma->qrcode('link', urlencode(admin_url('sales/view/' . $inv->id)), 2); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row" style="margin-bottom:15px;">

                <div class="col-xs-6">
                    <?php echo $this->lang->line("customer"); ?>:<br/>
                    <h2 style="margin-top:10px;"><?= $customer->name ? $customer->name : $customer->company; ?></h2>
                    <?= $customer->name ? "" : "Attn: " . $customer->company ?>

                    <?php
                    #echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;
 echo $customer->address . "<br>" . $customer->country;
                    echo "<p>";

                    if ($customer->vat_no != "-" && $customer->vat_no != "") {
                        echo "<br>" . lang("vat_no") . ": " . $customer->vat_no;
                    }
                    if ($customer->cf1 != "-" && $customer->cf1 != "") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                    if ($customer->cf2 != "-" && $customer->cf2 != "") {
                        echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                    }
                    if ($customer->cf3 != "-" && $customer->cf3 != "") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if ($customer->cf4 != "-" && $customer->cf4 != "") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if ($customer->cf5 != "-" && $customer->cf5 != "") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if ($customer->cf6 != "-" && $customer->cf6 != "") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }

                    echo "</p>";
                    echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;
                    ?>
                </div>

                <div class="col-xs-6">
                    <?php echo $this->lang->line("created_by"); ?>:
                    <h2 style="margin-top:10px;"><h2 class=""><?= $created_by->first_name.' '.$created_by->last_name; ?></h2></h2>
                    <?php // $user_byemail->company ? "" : "Attn: " . $user_byemail->first_name; ?>

                    <?php
                    
echo $user_byemail->address . "<br>" . $user_byemail->city . " " . $user_byemail->postal_code . " " . $user_byemail->state . "<br>" . $user_byemail->country;
                    echo "<p>";

                    if ($biller->vat_no != "-" && $biller->vat_no != "") {
                        echo "<br>" . lang("vat_no") . ": " . $biller->vat_no;
                    }
                    if ($biller->cf1 != "-" && $biller->cf1 != "") {
                        echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
                    }
                    if ($biller->cf2 != "-" && $biller->cf2 != "") {
                        echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
                    }
                    if ($biller->cf3 != "-" && $biller->cf3 != "") {
                        echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
                    }
                    if ($biller->cf4 != "-" && $biller->cf4 != "") {
                        echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                    }
                    if ($biller->cf5 != "-" && $biller->cf5 != "") {
                        echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                    }
                    if ($biller->cf6 != "-" && $biller->cf6 != "") {
                        echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                    }

                    echo "</p>";
                    echo lang("tel") . ": " . $created_by->phone . "<br>" . lang("email") . ": " . $created_by->email;
                    ?>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table">

                    <thead>

                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?></th>
                        <th><?= lang("quantity"); ?></th>
                        <th><?= lang("unit_price"); ?></th>
                        <?php
                        if ($Settings->tax1 && $inv->product_tax > 0) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        if ($Settings->product_discount && $inv->product_discount != 0) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        ?>
                        <th><?= lang("subtotal"); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    $tax_summary = array();
                    foreach ($rows as $row):
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;">
                                <?= $row->product_code.' - '.$row->product_name . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                
                            </td>
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatQuantity($row->unit_quantity).' '.$row->product_unit_code; ?></td>
                            <td style="text-align:right; width:100px;"><?= $this->sma->formatMoney($row->real_unit_price); ?></td>
                            <?php
                            if ($Settings->tax1 && $inv->product_tax > 0) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->sma->formatMoney($row->item_tax) . '</td>';
                            }
                            if ($Settings->product_discount && $inv->product_discount != 0) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->sma->formatMoney($row->item_discount) . '</td>';
                            }
                            ?>
                            <td style="text-align:right; width:120px;"><?= $this->sma->formatMoney($row->subtotal); ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    if ($return_rows) {
                        echo '<tr class="warning"><td colspan="100%" class="no-border"><strong>'.lang('returned_items').'</strong></td></tr>';
                        foreach ($return_rows as $row):
                        ?>
                            <tr class="warning">
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= $row->product_code.' - '.$row->product_name . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                                </td>
                                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatQuantity($row->quantity).' '.$row->product_unit_code; ?></td>
                                <td style="text-align:right; width:100px;"><?= $this->sma->formatMoney($row->real_unit_price); ?></td>
                                <?php
                                if ($Settings->tax1 && $inv->product_tax > 0) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->sma->formatMoney($row->item_tax) . '</td>';
                                }
                                if ($Settings->product_discount && $inv->product_discount != 0) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->sma->formatMoney($row->item_discount) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; width:120px;"><?= $this->sma->formatMoney($row->subtotal); ?></td>
                            </tr>
                            <?php
                            $r++;
                        endforeach;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                    $col = 4;
                    if ($Settings->product_discount && $inv->product_discount != 0) {
                        $col++;
                    }
                    if ($Settings->tax1 && $inv->product_tax > 0) {
                        $col++;
                    }
                    if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1 && $inv->product_tax > 0) {
                        $tcol = $col - 2;
                    } elseif ($Settings->product_discount && $inv->product_discount != 0) {
                        $tcol = $col - 1;
                    } elseif ($Settings->tax1 && $inv->product_tax > 0) {
                        $tcol = $col - 1;
                    } else {
                        $tcol = $col;
                    }
                    ?>
                    <?php if ($inv->grand_total != $inv->total) { ?>
                        <tr>
                            <td colspan="<?= $tcol; ?>"
                                style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <?php
                            if ($Settings->tax1 && $inv->product_tax > 0) {
                                echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_sale ? ($inv->product_tax+$return_sale->product_tax) : $inv->product_tax) . '</td>';
                            }
                            if ($Settings->product_discount && $inv->product_discount != 0) {
                                echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_sale ? ($inv->product_discount+$return_sale->product_discount) : $inv->product_discount) . '</td>';
                            }
                            ?>
                            <td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($return_sale ? (($inv->total + $inv->product_tax)+($return_sale->total + $return_sale->product_tax)) : ($inv->total + $inv->product_tax)); ?></td>
                        </tr>
                    <?php } ?>
                    <?php
                    if ($return_sale) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_total") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_sale->grand_total) . '</td></tr>';
                    }
                    if ($inv->surcharge != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->surcharge) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">'.($inv->order_discount_id ? '<small>('.$inv->order_discount_id.')</small> ' : '') . $this->sma->formatMoney($return_sale ? ($inv->order_discount+$return_sale->order_discount) : $inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_sale ? ($inv->order_tax+$return_sale->order_tax) : $inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->sma->formatMoney($return_sale ? ($inv->grand_total+$return_sale->grand_total) : $inv->grand_total); ?></td>
                    </tr>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->sma->formatMoney($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid); ?></td>
                    </tr>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->sma->formatMoney(($return_sale ? ($inv->grand_total+$return_sale->grand_total) : $inv->grand_total) - ($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid)); ?></td>
                    </tr>

                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->sma->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
						
                        if ($inv->order_status_history || $inv->order_status_history != "") { ?>
                            <div class="well well-sm">
								<p class="bold"><?= lang("Payment Confirm"); ?>:</p>
								<p> <?= $this->sma->decode_html($inv->order_status_history); ?></p>
								<?php $referer = admin_url('welcome/download/'.$this->sma->decode_html($inv->attachment)); ?>
								<div><?= '<img src="'.$referer.'" alt="" class="" style="width: 450px; max-width:100%;">'; ?></div>
                            </div>
                        <?php
                        }
						
						
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>
                                <div><?= $this->sma->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                </div>

                <?php if ($Settings->award_use > 0) { ?>
                <div class="col-xs-5 pull-left">
                    <div class="well well-sm">
                        <?=
                        '<p>'.lang('this_sale').': '.$point->points_current
                        .'<br>'.
                        lang('your_award_points').': '. $totalpoint . '</p>';?>
                    </div>
                </div>
                <?php } ?>

                <div class="col-xs-5 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                            <?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?>
                        </p>
                        <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->sma->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
			
			
			
			<?php if ($Owner || $GP['bulk_actions']){ ?>
            <?php if (!$Supplier || !$Customer) { ?>
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <a href="<?= admin_url('sales/add_payment/' . $inv->id) ?>" class="tip btn btn-success" title="<?= lang('add_payment') ?>" data-toggle="modal" data-target="#myModal2">
                                <i class="fa fa-dollar"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= admin_url('sales/add_delivery/' . $inv->id) ?>" class="tip btn btn-success" title="<?= lang('add_delivery') ?>" data-toggle="modal" data-target="#myModal2">
                                <i class="fa fa-truck"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('add_delivery') ?></span>
                            </a>
                        </div>
                        <?php if ($inv->attachment) { ?>
                            <div class="btn-group">
                                <a href="<?= admin_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-success" title="<?= lang('attachment') ?>">
                                    <i class="fa fa-chain"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="btn-group">
                            <a href="<?= admin_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-success" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= admin_url('sales/pdf/' . $inv->id) ?>" class="tip btn btn-success" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <?php if ( ! $inv->sale_id) { ?>
                        <div class="btn-group">
                            <a href="<?= admin_url('sales/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= admin_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                data-html="true" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                            </a>
                        </div>
                        <?php } ?>
						<?php } ?>
						
						
						
						
						
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
		
	$(document).on('click', '#btn-receipt', function(e) {
		e.preventDefault();
		//$('#receipt_pdf').val($(this).attr('data-action'));
        $('#receipt-pdf-submit').click();
    });
		
		
    });
</script>
