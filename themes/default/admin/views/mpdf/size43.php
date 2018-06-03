<!DOCTYPE html>
<html>
    <head>
        <style>
            @page {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body style="margin: 0px; padding: 0px;">
    <?php
        $break = 0;
        if(isset($order) && is_array($order)) {
            foreach($order as $j => $thislist) {
            ?>
            <table autosize="0" border="0" cellpadding="0" width="100%" style="max-width: 3in; border-collapse: collapse; margin: 0px; overflow:hidden;">
                <tr>
                    <td valign="top" style="padding: 10px 10px 5px 10px;">
                        <div style="height: 50px !important;"><img src="<?php echo $header[$j]['company']['logo'] ?>" height="50" /></div>
                        <div style="font-weight: bold; font-size: 22px; line-height: 24px;">ชื่อที่อยู่ผู้ฝากส่ง</div>
                        <div style="font-size: 18px; line-height: 24px;"><?php echo $header[$j]['company']['name'] ?> <?php echo (isset($header[$j]['company']['telephone']))?"โทร. " . $header[$j]['company']['telephone']:null ?></div>
                        <div style="font-size: 18px; line-height: 24px;"><?php echo $header[$j]['company']['address'] ?></div>
                    </td>
                <tr>
                    <td valign="top" style="padding: 10px 10px 5px 30px;">
                        <div style="font-weight: bold; font-size: 22px; line-height: 22px;">ชื่อที่อยู่ผู้รับ</div>
                        <div style="font-size: 18px; line-height: 22px;"><?php echo $customer[$j]['customerName'] ?> <?php if(isset($customer[$j]['telephone']) && $customer[$j]['telephone'] != '') { ?>
                            <span style="font-size: 18px; font-weight: normal;"><b>โทร.</b> <?php echo $customer[$j]['telephone'] ?></span>
                        <?php } ?></div>
                        <div style="font-size: 18px; line-height: 20px;"><?php echo $customer[$j]['customerAddress'] ?></div>
                    </td>
                </tr>
            </table>
            <div style="width: 100%; padding-top: 10px; padding-bottom: 10px; text-align:center;">
                <barcode code="<?php echo $header[$j]['setting']['orderno'] ?>" type="C128A" size="1" height="1"/>
                <div style="font-size: 16px;"><b>
                <?php echo $header[$j]['setting']['orderno'] ?></b></div>
            </div>
            <div style="width: 100%; text-align:left; padding-left: 20px;">
                <?php 
                if(isset($header[$j]['product'])) {
                        foreach($header[$j]['product'] as $i => $product) {
                            echo $product['sku'] . " (" . $product['quantity'] . ")";
                            if($i+1 != count($header[$j]['product'])) {
                                echo "<br/> ";
                            }
                        }   
                    }
                ?>
            </div>
            <?php
            if($j != count($order) - 1) {
                ?>
                <div style="display: block;page-break-before: always;"></div>
                <?php
            }
            }
        }    
    ?>
    </body>
</html>