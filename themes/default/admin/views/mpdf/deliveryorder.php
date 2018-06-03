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
    <body>
        <div style="width: 100%; text-align: right; margin-bottom: 5px;">
            <strong style="font-size: 1.4em;">ใบส่งของ/ใบกำกับภาษี</strong>
        </div>
        <div style="width: 100%; margin-bottom: 5px;">
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
                        <b>โทรศัพท์</b> <?= $header['company']['telephone'] ?> <?= ($header['company']['fax']!=null)?', ' . $header['company']['fax']:null ?>
                    </div>
                    <div class="row" style="font-size: 18px; line-height: 22px;">
                        <b>เลขประจำตัวผู้เสียภาษี</b> <?= $header['company']['taxno'] ?>
                    </div>
                </div>
            </div>
            <div style="width: 190px; padding-top: 20px; float: right;">
                <div style="">
                    <div style="text-align: center; border: 2px solid black; border-radius: 0.7em; margin-left: 20px; margin-bottom: 10px;">
                        <div style="font-size: 18px; line-height: 20px; padding: 5px 5px 3px 5px; border-bottom: 1px solid black;">
                                วันที่
                        </div>
                        <div class="container-row" style="font-size: 18px; line-height: 20px; padding: 5px 7px;">
                            <?= $header['setting']['orderdate'] ?>
                        </div>
                    </div>
                    <div style="text-align: center; border: 2px solid black; border-radius: 0.7em; margin-left: 20px; ">
                        <div style="font-size: 18px; line-height: 20px; padding: 5px 5px 3px 5px; border-bottom: 1px solid black;">
                                เลขที่ใบส่งของ
                        </div>
                        <div class="container-row" style="font-size: 18px; line-height: 20px; padding: 5px 7px;">
                            <?= $header['deliverycode']['deliveryorder'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%; margin-bottom: 10px;">
            <div style="width: 400px; float: left; padding: 4px; border: 1px solid black; border-radius: 1em; ">
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
            <div style="width: 250px; float: right; padding: 4px; border: 1px solid black; border-radius: 1em;">
                <table style="width: 100%; font-size: 18px;">
                    <tr>
                        <td>
                            <strong>ช่องทางการชำระเงิน</strong>
                        </td>
                        <td style="text-align: left;">
                            <?= $header['paymentchannel']['order'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>วิธีการชำระเงิน</strong>
                        </td>
                        <td>
                            <?= $header['paymentchannel']['payment'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>วิธีการจัดส่ง</strong>
                        </td>
                        <td>
                            <?= $header['paymentchannel']['shipping'] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="width: 100%; margin-bottom: 10px;">
            <table border="1" cellpadding="5" style="width: 100%; font-size: 18px; border: 1px solid black; border-radius: 1em; overflow:hidden; border-collapse: collapse;">
                <tr >
                    <td align="center"><strong>เลขที่ใบสั่งซื้อ<br/>Purchase Order No.</strong></td>
                    <td align="center"><strong>ใบส่งของ/ใบแจ้งหนี้<br/>Delivery Order No.</strong></td>
                    <td align="center"><strong>พนักงานขาย<br/>Sale Man</strong></td>
                </tr>
                <tr>
                    <td width="33%" align="center">
                        <barcode code="<?= $header['deliverycode']['purchaseorder'] ?>" type="C128A" size="0.7"><br/>
                        <strong><?= $header['deliverycode']['purchaseorder'] ?></strong>
                    </td>
                    <td width="33%" align="center">
                        <barcode code="<?= $header['deliverycode']['deliveryorder'] ?>" type="C128A" size="0.7"><br/>
                        <strong><?= $header['deliverycode']['deliveryorder'] ?></strong>
                    </td>
                    <td width="33%" align="center">
                        <strong><?= $header['deliverycode']['saleman'] ?></strong>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 100%; margin-bottom: 10px;">
            <table style="width: 100%; font-size: 18px; border: 0.5px solid black; border-collapse: collapse;" border="1" cellpadding="5">
                <tr>
                    <th align="center" style="width: 55px;">ลำดับที่<br/>No.</th>
                    <th align="center">รายการ<br/>Description</th>
                    <th align="center" style="width: 80px;">รหัส<br/>(SKU)</th>
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
           
                        <td valign="top" align="center"><?= $list['total'] ?></td>
                    </tr>
                <?php
                        }
                    }
                ?>
                <tr class="tbSummary">
                    <td colspan="3" rowspan="5" valign="top">หมายเหตุ:<br/>Remark:</td>
                    <td class="summary" colspan="2">ยอดรวมสุทธิ (Net Total)</td>
                    <td align="center"><?= $summary['net_total'] ?></td>
                </tr>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ภาษีมูลค่าเพิ่ม 7% (Vat 7%)</td>
                    <td align="center"><?= $summary['vat'] ?></td>
                </tr>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ค่าขนส่ง (Shipping)</td>
                    <td align="center"><?= $summary['shipping'] ?></td>
                </tr>

                <tr class="tbSummary">
                    <td class="summary" colspan="2">ยอดรวมก่อนภาษีมูลค่าเพิ่ม</td>
                    <td align="center"><?= $summary['grand_total'] ?></td>
                </tr>
            </table>
        </div>
        <div style="width: 100%;">
            <div style="margin: 0 auto; width: 60%;">
                <div style="float: left; width: 45%; text-align: center; border: 1px solid black; border-radius: 1em; padding: 5px;font-size: 18px;">
                    <strong>ผู้รับของ</strong>
                    <br />
                    ......................................................
                    <br />
                    (....................................................)
                    <br />
                    วันที่ ......... / ......... / .........
                </div>

                <div style="float: left; width: 45%; text-align: center; border: 1px solid black; border-radius: 1em; padding: 5px; font-size: 18px;  margin-left: 9px;">
                    <strong>ผู้อนุมัติ</strong>
                    <br />
                    ......................................................
                    <br />
                    (....................................................)
                    <br />
                    วันที่ ......... / ......... / .........
                </div>
            </div>
        </div>
    </body>
</html>