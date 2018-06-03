<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>

			<?php
			if($group_id->name == "becurve" || $Owner){
			?>
			<ul class="ul-print">
			<li class="btn btn-xs btn-default no-print pull-right" style="margin-right:5px;"><a target="__blank" href="<?= admin_url('purchases/pdf_od/' . $inv->id); ?>"><i class="fa fa-print" aria-hidden="true"></i> ใบเสร็จรับเงิน (Receipt)</a></li>
			<li class="btn btn-xs btn-default no-print pull-right" style="margin-right:5px;"><a target="__blank" href="<?= admin_url('purchases/pdf/' . $inv->id); ?>"><i class="fa fa-print" aria-hidden="true"></i> ใบสั่งซื้อ (PO)</a></li>
			<?php if($inv->is_ship){ ?><li class="btn btn-xs btn-default no-print pull-right" style="margin-right:5px;"><a target="__blank" href="<?= admin_url('purchases/pdf_dv/' . $inv->id); ?>"><i class="fa fa-print" aria-hidden="true"></i> ใบส่งของ (delivery order)</a></li> <?php } ?>
			</ul>
			<?php } ?>
			<?php /*
            <button id="btn-receipt" type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" ">
                <i class="fa fa-print"></i> <?= lang('print_pdf'); ?>
            </button>
			<div style="display: none;">
				<form class="hidden" id ="receipt_pdf" target="__blank" action="<?= admin_url('purchases/pdf/' . $inv->id); ?>">
					<input type="hidden" name="form_action" value="" id="form_action"/>
					<input name="performAction" value="performAction" id="receipt-pdf-submit" class="input-xs" type="submit">
				</form>
			</div>
			*/ ?>

			
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px; visibility: hidden;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>"
                         alt="<?= $Settings->site_name; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?><br>
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?php if (!empty($inv->return_purchase_ref)) {
                            echo lang("return_ref").': '.$inv->return_purchase_ref;
                            if ($inv->return_id) {
                                echo ' <a data-target="#myModal2" data-toggle="modal" href="'.admin_url('purchases/modal_view/'.$inv->return_id).'"><i class="fa fa-external-link no-print"></i></a><br>';
                            } else {
                                echo '<br>';
                            }
                        } ?>
                        <?= lang("status"); ?>: <?= lang($inv->status); ?><br>
                        <?= lang("payment_status"); ?>: <?= lang($inv->payment_status); ?>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right order_barcodes">
                        <?= $this->sma->save_barcode($inv->reference_no, 'code128', 66, false); ?>
                        <?= $this->sma->qrcode('link', urlencode(admin_url('purchases/view/' . $inv->id)), 2); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row" style="margin-bottom:15px;">
                <div class="col-xs-6">
                    <?php echo $this->lang->line("to"); ?>:
                  <?php /*  <h2 style="margin-top:10px;"><?= $supplier->company ? $supplier->company : $supplier->name; ?></h2>
                    <?= $supplier->company ? "" : "Attn: " . $supplier->name ?>*/ ?>
				 <h2 style="margin-top:10px;"><?php echo $user_to->first_name; ?> <?php echo $user_to->last_name; ?><?php if($user_to->seller_id):?>(<?php echo $user_to->seller_id; ?>)<?php endif; ?></h2>
                    <?php
                   // echo $supplier->address . "<br />" . $supplier->city . " " . $supplier->postal_code . " " . $supplier->state . "<br />" . $supplier->country;
				   echo $user_to_address->address;

                    echo "<p>";
/*
                    if ($supplier->vat_no != "-" && $supplier->vat_no != "") {
                        echo "<br>" . lang("vat_no") . ": " . $supplier->vat_no;
                    }
                    if ($supplier->cf1 != "-" && $supplier->cf1 != "") {
                        echo "<br>" . lang("scf1") . ": " . $supplier->cf1;
                    }
                    if ($supplier->cf2 != "-" && $supplier->cf2 != "") {
                        echo "<br>" . lang("scf2") . ": " . $supplier->cf2;
                    }
                    if ($supplier->cf3 != "-" && $supplier->cf3 != "") {
                        echo "<br>" . lang("scf3") . ": " . $supplier->cf3;
                    }
                    if ($supplier->cf4 != "-" && $supplier->cf4 != "") {
                        echo "<br>" . lang("scf4") . ": " . $supplier->cf4;
                    }
                    if ($supplier->cf5 != "-" && $supplier->cf5 != "") {
                        echo "<br>" . lang("scf5") . ": " . $supplier->cf5;
                    }
                    if ($supplier->cf6 != "-" && $supplier->cf6 != "") {
                        echo "<br>" . lang("scf6") . ": " . $supplier->cf6;
                    }
*/
                    echo "</p>";
                    echo lang("tel") . ": " . $user_to_address->phone . "<br />" . lang("email") . ": " . $user_to_address->email;
                    ?>
                </div>
                <div class="col-xs-6">
                    <?php echo $this->lang->line("from"); ?>:<br/>
                   <?php /* <h2 style="margin-top:10px;"><?= $Settings->site_name; ?></h2>*/ ?>
                    <h2 style="margin-top:10px;"><?php echo $user_from_user->first_name." ".$user_from_user->last_name; ?><?php if($user_from_user->seller_id):?>(<?php echo $user_from_user->seller_id; ?>)<?php endif; ?></h2>
					
					
                  <?php /*  <?= $warehouse->name ?>*/ ?>

                    <?php
                    echo $user_from_company->address;
				echo "<p></p>";
                    echo ($user_from_company->phone ? lang("tel") . ": " . $user_from_company->phone . "<br>" : '') . ($user_from_company->email ? lang("email") . ": " . $user_from_company->email : '');
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
                        <?php
                            if ($inv->status == 'partial') {
                                echo '<th>'.lang("received").'</th>';
                            }
                        ?>
                        <th><?= lang("unit_cost"); ?></th>
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
                                <?= $row->supplier_part_no ? '<br>'.lang('supplier_part_no').': ' . $row->supplier_part_no : ''; ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                                <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>'.lang('expiry').': ' . $this->sma->hrsd($row->expiry) : ''; ?>
                            </td>
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatQuantity($row->unit_quantity).' '.$row->product_unit_code; ?></td>
                            <?php
                            if ($inv->status == 'partial') {
                                echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->sma->formatQuantity($row->quantity_received).' '.$row->product_unit_code.'</td>';
                            }
                            ?>
                            <td style="text-align:right; width:100px;"><?= $this->sma->formatMoney($row->real_unit_cost); ?></td>
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
                                    <?= $row->supplier_part_no ? '<br>'.lang('supplier_part_no').': ' . $row->supplier_part_no : ''; ?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>'.lang('expiry').': ' . $this->sma->hrsd($row->expiry) : ''; ?>
                                </td>
                                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatQuantity($row->unit_quantity).' '.$row->product_unit_code; ?></td>
                                <?php
                                if ($inv->status == 'partial') {
                                    echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->sma->formatQuantity($row->quantity_received).' '.$row->product_unit_code.'</td>';
                                }
                                ?>
                                <td style="text-align:right; width:100px;"><?= $this->sma->formatMoney($row->real_unit_cost); ?></td>
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
                    if ($inv->status == 'partial') {
                        $col++;
                    }
                    if ($Settings->product_discount && $inv->product_discount != 0) {
                        $col++;
                    }
                    if ($Settings->tax1 && $inv->product_tax > 0) {
                        $col++;
                    }
                    if ( $Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1 && $inv->product_tax > 0) {
                        $tcol = $col - 2;
                    } elseif ( $Settings->product_discount && $inv->product_discount != 0) {
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
                                echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_purchase ? ($inv->product_tax+$return_purchase->product_tax) : $inv->product_tax) . '</td>';
                            }
                            if ($Settings->product_discount && $inv->product_discount != 0) {
                                echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_purchase ? ($inv->product_discount+$return_purchase->product_discount) : $inv->product_discount) . '</td>';
                            }
                            ?>
                            <td style="text-align:right; padding-right:10px;"><?= $this->sma->formatMoney($return_purchase ? (($inv->total + $inv->product_tax)+($return_purchase->total + $return_purchase->product_tax)) : ($inv->total + $inv->product_tax)); ?></td>
                        </tr>
                    <?php } ?>
                    <?php
                    if ($return_purchase) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_total") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_purchase->grand_total) . '</td></tr>';
                    }
                    if ($inv->surcharge != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->surcharge) . '</td></tr>';
                    }
                    ?>

                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">'.($inv->order_discount_id ? '<small>('.$inv->order_discount_id.')</small> ' : '') . $this->sma->formatMoney($return_purchase ? ($inv->order_discount+$return_purchase->order_discount) : $inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    
					<?php /*<?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($return_purchase ? ($inv->order_tax+$return_purchase->order_tax) : $inv->order_tax) . '</td></tr>';
                    }
                    ?>*/ ?>
					
				
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->sma->formatMoney($return_purchase ? ($inv->grand_total+$return_purchase->grand_total) : $inv->grand_total); ?></td>
                    </tr>
					<?php /*
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->sma->formatMoney($return_purchase ? ($inv->paid+$return_purchase->paid) : $inv->paid); ?></td>
                    </tr> */ ?>
                 <?php /*   <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->sma->formatMoney(($return_purchase ? ($inv->grand_total+$return_purchase->grand_total) : $inv->grand_total) - ($return_purchase ? ($inv->paid+$return_purchase->paid) : $inv->paid)); ?></td>
                    </tr>*/ ?>
					
				<?php if ($Settings->tax2 && $inv->order_tax != 0) {
					echo '<tr style="text-align:right; font-weight:bold;"><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->grand_total - (($inv->grand_total*100)/107)) . '</td></tr>';
					  }
                    ?>
					
					<?php if ($Settings->tax2 && $inv->order_tax != 0) {
					echo '<tr style="text-align:right; font-weight:bold;"><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("subtotal_vat") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->sma->formatMoney($inv->grand_total - ($inv->grand_total - (($inv->grand_total*100)/107))) . '</td></tr>';
					  }
                    ?>


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
                        ?>
                </div>
			
				
                <div class="col-xs-12">
                    <?php
                        if ($inv->order_status_history || $inv->order_status_history != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("order_status_history"); ?>:</p>
                                <div><?= $this->sma->decode_html($inv->order_status_history); ?></div>
								<div><?php $referer = admin_url('welcome/download/'.$this->sma->decode_html($inv->attachment)); ?>
								<?= '<img src="'.$referer.'" alt="" class="" style="width: 450px; max-width:100%;">'; ?></div>
                            </div>
                        <?php
                        }
                        ?>
                </div>
					<?php if(count($additional_document)>"0"){ ?>
					<div class="col-xs-12">
						<div class="well well-sm">
						<?php 
							foreach($additional_document as $ad){
								echo "<img src=".admin_url('welcome/download/'.$ad->document)." style='width: 450px; max-width:100%;' /><br/>";
							}
						?>
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
				
				<div class="upload_slip">
				   <?php
						$attrib = array('data-toggle' => 'validator', 'role' => 'form');
						echo admin_form_open_multipart("purchases/additional_document", $attrib)
						?>
						<input type="hidden" name="purchase_id" value="<?php echo $inv->id;?>" />
					<div class="col-md-4">
						<div class="form-group">
							<?= lang("document", "document") ?>
							<input id="document"  type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
								   data-show-preview="false" class="form-control file">
						</div>
						
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<?php /*<a class='btn btn-success' href='<?= admin_url('purchases/additional_document/' . $inv->id) ?>'><?= lang('add_slip') ?></a> */ ?>
							<input type="submit" class="btn btn-success upload_slip" value="<?php echo  lang('upload_slip');?>">
						</div>
					</div>
				<?php form_close(); ?>
				</div>
            </div>
			
			
            <?php #if (!$Supplier || !$Customer) { ?>

            <?php if($Owner || $Admin  || $user_from_user->id == $this->session->userdata('user_id')) { ?>
			<?php //if($user_from_username->id == $this->session->userdata('user_id')){ ?>
                <div class="buttons">
                    <?php if ($inv->attachment) { ?>
                        <div class="btn-group">
                            <a href="<?= admin_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-success" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group"> 
                            <a href="<?= admin_url('purchases/add_payment/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-success" title="<?= lang('add_payment') ?>">
                                <i class="fa fa-dollar"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
                            </a>
                        </div>
					<div class="btn-group">
                            <a href="<?= admin_url('purchases/add_delivery/' . $inv->id) ?>" class="tip btn btn-success" title="<?= lang('add_delivery') ?>" data-toggle="modal" data-target="#myModal2">
                                <i class="fa fa-truck"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('add_delivery') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= admin_url('purchases/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-success" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= admin_url('purchases/pdf/' . $inv->id) ?>" class="tip btn btn-success" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= admin_url('purchases/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete") ?></b>"
                                data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= admin_url('purchases/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                data-html="true" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                            </a>
                        </div>
                    </div>
                </div>
			<?php //} ?>
            <?php } ?>
			
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
		$(document).on('click', '#btn-receipt', function(e) {
			e.preventDefault();
			$('#receipt-pdf-submit').click();
		});
    });
</script>
<style>
.ul-print a {
    color: #333;
}
</style>