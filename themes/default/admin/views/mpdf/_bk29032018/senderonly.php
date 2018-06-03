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
    <body>
    <?php
        if(isset($order) && is_array($order)) {
            foreach($order as $j => $thislist) {
            ?>
            <div style="width: 70mm; float: left;  height: 3in; padding-top: 15%; padding-left: 5mm; padding-right: 5mm;">
                <div style="font-weight: bold; font-size: 22px; line-height: 22px;">ชื่อที่อยู่ผู้รับ</div>
                <div style="font-size: 18px; line-height: 22px;"><?= $customer[$j]['customerName'] ?> <?php if(isset($customer[$j]['telephone']) && $customer[$j]['telephone'] != '') { ?>
                    <span style="font-size: 18px; font-weight: normal;"><b>โทร.</b> <?= $customer[$j]['telephone'] ?></span>
                <?php } ?></div>
                <div style="font-size: 18px; line-height: 20px;"><?= $customer[$j]['customerAddress'] ?></div>
                <div style="width: 100%; text-align:left; padding-top: 5%; ">
                    <?php 
                    if(isset($header[$j]['product'])) {
                            foreach($header[$j]['product'] as $i => $product) {
                                echo "- " . $product['sku'] . " (" . $product['quantity'] . ")";
                                if($i+1 != count($header[$j]['product'])) {
                                    echo "<br/> ";
                                }
                            }   
                        }
                    ?>
                </div>
            </div>
            <div style="width: 20mm; position: relative; height: 3in;">
                <table rotate="-90" border="0" cellpadding="5" cellspacing="0" width="100%" style="position: absolute;">
                    <tr>
                        <td align="center" >
                            <barcode code="<?= $header[$j]['setting']['orderno'] ?>" type="C128A" size="1" height="1"/>
                            <div style="font-size: 16px;"><b>
                            <?= $header[$j]['setting']['orderno'] ?></b></div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
            }
        }    
    ?>
    </body>
</html>