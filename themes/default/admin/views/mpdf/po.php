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
                <div style="width: 100%; text-align: center;">
                    <div class="col-lg-12">
                        
                        <div class="row" style="font-size: 22px;">
							<strong><?= $header['company']['name'] ?></strong>  
                        </div>
                        <div class="row" style="font-size: 18px; line-height: 22px;">
							<?= $header['company']['address'] ?>
                        </div>						
                        <div class="row" style="font-size: 18px; line-height: 22px;">
                            <b>เลขที่ประจำตัวผู้เสียภาษี  <span><?= $header['company']['taxno'] ?></span></b> 
                        </div>
                    </div>
                </div>               
            </div>
             
        </div>
        <div style="width: 100%;">
		<div class="row" style="font-size: 22px;text-align: center;">
			ใบสั่งซื้อ   
        </div>
		<div class="row" style="font-size: 16px;text-align: right;">
			เลขที่ <?= $header['setting']['invoiceno'] ?>
        </div>
		<div class="row" style="font-size: 16px;text-align: right;">
			วันที่ <?= $header['setting']['orderdate'] ?> 
        </div>
            <table style="width: 100%; font-size: 18px; border: 0.5px solid black; border-collapse: collapse;" border="1" cellpadding="5">
                <tr>
                    <th align="center" style="width: 55px;">ลำดับที่</th>
                    <th align="center">สินค้า</th>
                    <th align="center">จำนวน</th>
                    <th align="center" style="width: 100px;">หน่วยละ</th>
                    <th align="center" style="width: 70px;">จำนวนเงิน</th>
                </tr>
				
                <?php
                    if(isset($order) && $order != null) {
                        foreach ($order as $i => $list) {
                ?>
				
                    <tr class="tbList" style="border: 0; padding-top: 10px;">
                        <td valign="top" align="center"><?= $i+1 ?></td>
                        <td valign="top" align="left" style="padding-left: 5px;"><?= $list['orderCode']." - ".$list['description'] ?></td>
                        <td valign="top" align="center"><?= $list['quantity'] ?></td>
                        <td valign="top" align="center"><?= $list['price'] ?></td>
                        <td valign="top" align="right"><?= $list['total'] ?></td>
                    </tr>
                <?php
                        }
                    }
                ?>
				
                <tr class="tbSummary">
					<td colspan="2" rowspan="5"></td>
                    <td class="summary" colspan="2">ยอดรวม</td>
                    <td align="right" alt="net_total"><?= $summary['net_total'] ?></td>
                </tr>
				
			  <?php if($summary['discount'] > 0) { ?>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ส่วนลด</td>
                    <td align="right"><?= $summary['discount'] ?></td>
                </tr>
                <?php } ?>
				
		  <?php if($summary['shipping'] > 0) { ?>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ค่าขนส่ง</td>
                    <td align="right"><?= $summary['shipping'] ?></td>
                </tr>
                <?php } ?>

                <?php if($summary['vat'] > 0) { ?>
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ภาษีมูลค่าเพิ่ม 7% (Vat 7%)</td>
                    <td align="right"><?= $summary['vat'] ?></td>
                </tr>
                <?php } ?>
				
                <tr class="tbSummary">
                    <td class="summary" colspan="2">ยอดรวมสุทธิ</td>
                    <td align="right" alt="grand_total"><?= $summary['grand_total'] ?></td>
                </tr>
            </table>
        </div>
        
		<br/>
        <div style="width: 100%;">
            <div style="width: 50%; float: left; text-align: center;">
                <div style="margin-right: 10px;  float: right; padding: 5px; width: 240px; font-size: 18px;">
                   
                    <br />
                    .......................................................................
                    <br />
                    (......................................................................)
                    <br />
                   <strong>ผู้สั่งซื้อ</strong>
                </div>
            </div>
            <div style="text-align: center;  width: 50%; font-size: 18px;">
                <div style="margin-left: 10px; float: left;  padding: 5px; width: 240px;  font-size: 18px;">
                    
                    <br />
                    .......................................................................
                    <br />
                    (......................................................................)
                    <br />
                   <strong>ผู้อนุมัติ</strong>
                </div>
            </div>
        </div>
    </body>
</html>