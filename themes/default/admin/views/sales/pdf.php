<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->lang->line('sale') . ' ' . $inv->reference_no; ?></title>
    <link href="<?= $assets ?>styles/pdf/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $assets ?>styles/pdf/pdf.css" rel="stylesheet">
</head>

<body>
<div id="wrap">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) {
                $path = base_url() . 'assets/uploads/logos/Logo-SMITH1-print.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= $base64; ?>" alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php }
            ?>
            <div class="clearfix"></div>
            <div class="padding10">

                <div class="col-xs-5">
                    <?php echo $this->lang->line("to"); ?>:
                    <?= $customer->company ? $customer->company : $customer->name; ?>
                    <?= $customer->company ? '' : 'Attn: ' . $customer->name; ?><br/>
                    <?php
                        #echo $customer->address . '<br />' . $customer->city . ' ' . $customer->postal_code . ' ' . $customer->state . '<br />' . $customer->country;
                        echo $customer->address;
                        echo '<p>';
                        if ($customer->vat_no != "-" && $customer->vat_no != "") {
                            echo "<br>" . lang("vat_no") . ": " . $customer->vat_no;
                        }
                        
                        echo '</p>';
                        echo lang('tel') . ': ' . $customer->phone . '<br />' . lang('email') . ': ' . $customer->email;
                    ?>
                </div>

                <div class="col-xs-5">

                    <?php echo $this->lang->line("from"); ?>:
                    <?= $user_byemail['name']; ?><br/>
                    <?php
                        echo $user_byemail['address'];
                        echo '<p>';
                        if ($user_byemail['vat_no'] != "-" && $user_byemail['vat_no'] != "") {
                            echo "<br>" . lang("vat_no") . ": " . $user_byemail['vat_no'];
                        }
                        echo '</p>';
                        echo lang('tel') . ': ' . $user_byemail['phone'] . '<br />' . lang('email') . ': ' . $user_byemail['email'];
                    ?>
                    <div class="clearfix"></div>
                </div>

            </div>
            <div class="clearfix"></div>
            <div class="padding10">
                <div class="col-xs-5">
                    <span class="bold"><?= $Settings->site_name; ?></span><br>
                    <?= $warehouse->name ?>

                    <?php
                        echo $warehouse->address . '<br>';
                        echo ($warehouse->phone ? lang('tel') . ': ' . $warehouse->phone . '<br>' : '') . ($warehouse->email ? lang('email') . ': ' . $warehouse->email : '');
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5">
                    <div class="bold">
                        <?= lang('date'); ?>: <?= $this->sma->hrld($inv->date); ?><br>
                        <?= lang('ref'); ?>: <?= $inv->reference_no; ?><br>
                        <?php if (!empty($inv->return_sale_ref)) {
                            echo lang("return_ref").': '.$inv->return_sale_ref.'<br>';
                        } ?>
                        <div class="order_barcodes barcode">
                            <?= $this->sma->save_barcode($inv->reference_no, 'code128', 66, false); ?>
                            <?php /*echo $this->sma->qrcode('link', urlencode(admin_url('sales/view/' . $inv->id)), 2);*/ ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>
            <?php
                $col = 4;
                if ( $Settings->product_discount && $inv->product_discount != 0) {
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
            <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <td><?= lang('no'); ?></td>
                        <td><?= lang('description'); ?></td>
                        <td><?= lang('quantity'); ?></td>
                        <td><?= lang('unit_price'); ?></td>
                        <?php
                            if ($Settings->tax1 && $inv->product_tax > 0) {
                                echo '<td>' . lang('tax') . '</td>';
                            }
                            if ( $Settings->product_discount && $inv->product_discount != 0) {
                                echo '<td>' . lang('discount') . '</td>';
                            }
                        ?>
                        <td><?= lang('subtotal'); ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $r = 1;
                        foreach ($rows as $row):
                            ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= $row->product_code.' - '.$row->product_name . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                                </td>
                                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatQuantity($row->unit_quantity).' '.$row->product_unit_code; ?></td>
                                <td style="text-align:right; width:90px;"><?= $this->sma->formatMoney($row->real_unit_price); ?></td>
                                <?php
                                    if ($Settings->tax1 && $inv->product_tax > 0) {
                                        echo '<td style="width: 90px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>(' . $row->tax_code . ')</small> ' : '') . $this->sma->formatMoney($row->item_tax) . '</td>';
                                    }
                                    if ( $Settings->product_discount && $inv->product_discount != 0) {
                                        echo '<td style="width: 90px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->sma->formatMoney($row->item_discount) . '</td>';
                                    }
                                ?>
                                <td style="vertical-align:middle; text-align:right; width:110px;"><?= $this->sma->formatMoney($row->subtotal); ?></td>
                            </tr>
                            <?php
                            $r++;
                        endforeach;
                        if ($return_rows) {
                            echo '<tr class="warning"><td colspan="'.($col+1).'" class="no-border"><strong>'.lang('returned_items').'</strong></td></tr>';
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
                                    <td style="text-align:right; width:90px;"><?= $this->sma->formatMoney($row->real_unit_price); ?></td>
                                    <?php
                                    if ($Settings->tax1 && $inv->product_tax > 0) {
                                        echo '<td style="text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->sma->formatMoney($row->item_tax) . '</td>';
                                    }
                                    if ($Settings->product_discount && $inv->product_discount != 0) {
                                        echo '<td style="text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->sma->formatMoney($row->item_discount) . '</td>';
                                    }
                                    ?>
                                    <td style="text-align:right; width:110px;"><?= $this->sma->formatMoney($row->subtotal); ?></td>
                                </tr>
                                <?php
                                $r++;
                            endforeach;
                        }
                    ?>
                    </tbody>
                    <tfoot>
                    
                    <?php if ($inv->grand_total != $inv->total) {
                        ?>
                        <tr>
                            <td colspan="<?= $tcol; ?>" style="text-align:right;"><?= lang('total'); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <?php
                                if ($Settings->tax1 && $inv->product_tax > 0) {
                                    echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_sale ? ($inv->product_tax+$return_sale->product_tax) : $inv->product_tax) . '</td>';
                                }
                                if ( $Settings->product_discount && $inv->product_discount != 0) {
                                    echo '<td style="text-align:right;">' . $this->sma->formatMoney($return_sale ? ($inv->product_discount+$return_sale->product_discount) : $inv->product_discount) . '</td>';
                                }
                            ?>
                            <td style="text-align:right;"><?= $this->sma->formatMoney($return_sale ? (($inv->total + $inv->product_tax)+($return_sale->total + $return_sale->product_tax)) : ($inv->total + $inv->product_tax)); ?></td>
                        </tr>
                    <?php }
                    ?>
                    <?php
                    if ($return_sale) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("return_total") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($return_sale->grand_total) . '</td></tr>';
                    }
                    if ($inv->surcharge != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($inv->surcharge) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang('order_discount') . ' (' . $default_currency->code . ')</td><td style="text-align:right;">'.($inv->order_discount_id ? '<small>('.$inv->order_discount_id.')</small> ' : '') . $this->sma->formatMoney($return_sale ? ($inv->order_discount+$return_sale->order_discount) : $inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang('order_tax') . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($return_sale ? ($inv->order_tax+$return_sale->order_tax) : $inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang('shipping') . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right;  "><?= lang('total_amount'); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right;  "><?= $this->sma->formatMoney($return_sale ? ($inv->grand_total+$return_sale->grand_total) : $inv->grand_total); ?></td>
                    </tr>

                    <tr>
                        <td colspan="<?= $col; ?>" style="text-align:right;  "><?= lang('paid'); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right;  "><?= $this->sma->formatMoney($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid); ?></td>
                    </tr>
                    <tr>
                        <td colspan="<?= $col; ?>" style="text-align:right;  "><?= lang('balance'); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right;  "><?= $this->sma->formatMoney(($return_sale ? ($inv->grand_total+$return_sale->grand_total) : $inv->grand_total) - ($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid)); ?></td>
                    </tr>

                    </tfoot>
                </table>
            </div>
            </div>
            <div class="clearfix"></div>

                <div class="col-xs-12">
                    <?php if ($inv->note || $inv->note != '') { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang('note'); ?>:</p>

                            <div><?= $this->sma->decode_html($inv->note); ?></div>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 pull-left">
                    <p style="height: 80px;"><?= lang('seller'); ?>
                        : <?= $user_byemail['name']; ?> </p>
                    <hr>
                    <p><?= lang('stamp_sign'); ?></p>
                </div>
                <div class="col-xs-4 pull-right">
                    <p style="height: 80px;"><?= lang('customer'); ?>
                        : <?= $customer->company ? $customer->company : $customer->name; ?> </p>
                    <hr>
                    <p><?= lang('stamp_sign'); ?></p>
                    <?php if ($customer->award_points != 0 && $Settings->each_spent > 0) { ?>
                        <div class="well well-sm">
                            <?=
                            '<p>'.lang('this_sale').': '.floor(($inv->grand_total/$Settings->each_spent)*$Settings->ca_point)
                            .'<br>'.
                            lang('total').' '.lang('award_points').': '. $customer->award_points . '</p>';?>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>

        </div>
    </div>
</div>
</body>
</html>