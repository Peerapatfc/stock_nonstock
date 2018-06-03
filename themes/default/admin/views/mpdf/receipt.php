<!DOCTYPE html>
<html>
    <head>
        <style>
            @page {
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 1cm;
                margin-right: 1cm;
            }
        </style>
    </head>
    <body id="print">
        <div style="width: 100%; text-align: right; font-size: 24px;height:24px;"></div>
        <div style="margin-bottom: 10px;">
            <div style="width: 100%;">
                <div style="width: 60%; float: left;">
                    <div class="col-lg-6">
                        <div class="row">
                        <?php if($header['company']['logo'] != '') { ?>
                            <img src="<?= $header['company']['logo'] ?>" style="height: 100%; max-height: 50px;" />
                        <?php } ?>
                        </div>
                        <div class="row" style="font-size: 22px;">
                            <strong><?= $header['company']['name'] ?></strong>
                        </div>
                        <div class="row" style="font-size: 18px; line-height: 22px;">
                            <?= $header['company']['address'] ?>
                        </div>
                        <div class="row" style="font-size: 18px; line-height: 22px;">
                            <b>โทรศัพท์</b> <?= $header['company']['telephone'] ?>
                        </div>
						
                        <div class="row" style="font-size: 18px; line-height: 22px;">
                            <b>เลขประจำตัวผู้เสียภาษี</b> <?= $header['company']['taxno'] ?>
                        </div>
                    </div>
                </div>
				
                <div style="width: 200px;padding-top: 20px; float: right;">
                    <div style="text-align: center; margin-bottom: 15px;">
                        <span style="font-size: 22px;">เลขที่ใบสั่งซื้อ: <strong><?= $header['setting']['orderno'] ?></strong></span><br/>
                        <barcode code="<?= $header['setting']['codeToBarCode'] ?>" type="C128A" size="1" />
                    </div>
					
                    <div style="font-size: 1.5em; line-height: 20px; text-align: center;">
                        <strong>ใบเสร็จรับเงิน</strong>
                        <p>แผ่นที่ 1/1</p>
                    </div>
                </div>
            </div>
            <div style="width: 100%;">
                <div style="width: 70%; float: left; padding: 4px; border: 1px solid black; border-radius: 1em; height: auto;">
                    <table style="width: 100%; font-size: 18px;">
                        <tr>
                            <td>
                                <strong>รหัสลูกค้า</strong>
                            </td>
                            <td style="text-align: left;">
                                <?= $customer['customerCode'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>นามลูกค้า</strong>
                            </td>
                            <td>
                                <?= $customer['customerName'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>ที่อยู่</strong>
                            </td>
                            <td>
                                <?= $customer['customerAddress'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>เลขประจำตัวผู้เสียภาษี</strong>
                            </td>
                            <td>
                                <?= $customer['customerTax'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="">
                    <div style="text-align: center; border: 1px solid black; border-radius: 0.7em; margin-left: 20px; margin-bottom: 10px;">
                        <div style="font-size: 18px; line-height: 20px; padding: 5px 5px 3px 5px; border-bottom: 1px solid black;">
                                วันที่
                        </div>
                        <div class="container-row" style="font-size: 18px; line-height: 20px; padding: 5px 7px;">
                            <?= $header['setting']['orderdate'] ?>
                        </div>
                    </div>
                    <div style="text-align: center; border: 1px solid black; border-radius: 0.7em; margin-left: 20px; ">
                        <div style="font-size: 18px; line-height: 20px; padding: 5px 5px 3px 5px; border-bottom: 1px solid black;">
                                เลขที่ใบเสร็จรับเงิน
                        </div>
                        <div class="container-row" style="font-size: 18px; line-height: 20px; padding: 5px 7px;">
                            <?= $header['setting']['invoiceno'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%;">
            <table style="width: 100%; font-size: 18px; border: 0.5px solid black; border-collapse: collapse;" border="1" cellpadding="5">
                <tr>
                    <th align="center" style="width: 55px;">ลำดับที่<br/>No.</th>
                    <th align="center">รายการ<br/>Description</th>
                    <th align="center" style="width: 140px;">รหัส<br/>(SKU)</th>
                    <th align="center">จำนวน<br/>Quantity</th>
                    <th align="center" style="width: 100px;">ราคาต่อหน่วย<br/>Price</th>
                    <th align="center" style="width: 70px;">ยอดรวม<br/>Total</th>
                </tr>
                <?php
                    if(isset($order) && $order != null) {
                        foreach ($order as $i => $list) {
                ?>
				
                    <tr class="tbList" style="border: 0; padding-top: 10px;">
                        <td valign="top" align="center"><?= $i+1 ?></td>
                        <td valign="top" align="left" style="padding-left: 5px;"><?= $list['description'] ?></td>
                        <td valign="top" align="center"><?= $list['orderCode'] ?></td>
                        <td valign="top" align="center"><?= $list['quantity'] ?></td>
                        <td valign="top" align="center"><?= $list['price'] ?></td>
                        <td valign="top" align="right"><?= $list['total'] ?></td>
                    </tr>
                <?php
                        }
                    }
                ?>
				
                <tr class="tbSummary">
					<td colspan="3" rowspan="6"></td>
                    <td class="summary" colspan="2">ยอดรวม</td>
                    <td align="right" alt="net_total"><?= $summary['total'] ?></td>
                </tr>
				
                <?php if($summary['vat'] > 0) { ?>
                <?php /*<tr class="tbSummary">
                    <?php if(isset($summary['vat_type']) && $summary['vat_type'] == "include") { ?>
                        <td class="summary" colspan="2">จำนวนเงินก่อนภาษี (บาท)</td>
                    <?php } else { ?>
                        <td class="summary" colspan="2">จำนวนเงินก่อนภาษี (บาท)</td>
                    <?php } ?>
                    <td align="right"><?= $summary['total'] ?></td>
                </tr>
				*/ ?>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ภาษีมูลค่าเพิ่ม 7% (Vat 7%)</td>
                    <td align="right"><?= $summary['vat'] ?></td>
                </tr>
                <?php } ?>
		 <?php if($summary['discount'] > 0) { ?>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ส่วนลด</td>
                    <td align="right"><?= $summary['discount'] ?></td>
                </tr>
                <?php } ?>
				
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ค่าขนส่ง</td>
                    <td align="right"><?= $summary['shipping'] ?></td>
                </tr>
				
				<?php /*
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ค่าบริการ COD (Service Fee)</td>
                    <td align="right"><?= ($summary['cod']==null)?"0.00":$summary['cod'] ?></td>
                </tr>
				*/ ?>

				

                <tr class="tbSummary">
                    <td class="summary" colspan="2">ยอดรวมสุทธิ</td>
                    <td align="right" alt="grand_total"><?= $summary['grand_total'] ?></td>
                </tr>
            </table>
        </div>
        <?php /*<div style="width: 100%; padding: 20px; ">
            <div style="width: 50%; float: left; text-align: right; font-size: 24px; padding-right: 10px; line-height: 32px;">
                รูปแบบการชำระเงิน ชำระเงินผ่านช่องทาง
            </div>
            <div style="font-size: 24px; line-height: 32px;">
                <div style=""><img style="margin-right: 5px;" src="<?php echo base_url().'assets/images/box.png'; ?>" width="18px" />ผ่านช่องทางธนาคาร ........................</div>
            </div>
        </div>
		*/ ?> 
		<br/>
        <div style="width: 100%;">
            <div style="width: 50%; float: left; text-align: center;">
                <div style="margin-right: 10px;  float: right; border: 1px solid black; border-radius: 1em; padding: 5px; width: 240px; font-size: 18px;">
                    <strong>ผู้รับเงิน</strong>
                    <br />
                    .......................................................................
                    <br />
                    (......................................................................)
                    <br />
                    วันที่ ......... / ......... / .........
                </div>
            </div>
            <div style="text-align: center;  width: 50%; font-size: 18px;">
                <div style="margin-left: 10px; float: left; border: 1px solid black; border-radius: 1em; padding: 5px; width: 240px;  font-size: 18px;">
                    <strong>ผู้อนุมัติ</strong>
                    <br />
                    .......................................................................
                    <br />
                    (......................................................................)
                    <br />
                    วันที่ ......... / ......... / .........
                </div>
            </div>
        </div>
    </body>
</html>