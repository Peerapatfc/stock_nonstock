<!DOCTYPE html>
<html>
    <head>
        <style>
            @page {
                margin-top: 0.1cm;
                margin-bottom: 0.1cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }
        </style>
    </head>
    <body>
    <?php
        $break = 0;
        if(isset($order) && is_array($order)) {
            foreach($order as $j => $thislist) {
            ?>
            <div style="width: 100%; padding: 10px; ">
                <div style="width: 100%; padding: 10px 10px 0 10px;">
                    <div style="width: 60%; float: left;">
                        <div style="padding: 5px;"><img src="<?= $header[$j]['company']['logo'] ?>" height="50" /></div>
                        <div style="font-weight: bold; font-size: 22px; line-height: 26px;">ชื่อที่อยู่ผู้ฝากส่ง</div>
                        <div style="font-size: 22px; line-height: 24px;"><?= $header[$j]['company']['name'] ?></div>
                        <div style="font-size: 22px; line-height: 24px;"><?= $header[$j]['company']['address'] ?></div>
                    </div>
                    <div style="text-align: right;">
                        <?php if(isset($summary[$j]['cod'])) { ?>
                            <h1>COD/<?= $summary[$j]['cod'] ?>฿</h1>
                        <?php } ?>
                    </div>
                </div>
                <div style="width: 100%;">
                    <div style="width: 40%; padding: 10px; float: left;">
                        <?php if(isset($header[$j]['company']['telephone']) && $header[$j]['company']['telephone'] != '') { ?>
                        <div style="font-size: 22px; line-height: 24px;"><b>โทร.</b><?= $header[$j]['company']['telephone'] ?></div>
                        <?php } ?>
                        <div style="width: 60%; text-align: center; margin-top: 10%;">
                            <div style="font-size: 20px;">Order No.</div>
                            <div><barcode code="<?= $header[$j]['setting']['orderno'] ?>" type="C128A" size="0.8" height="1"/></div>
                            <div style="font-size: 20px;"><?= $header[$j]['setting']['orderno'] ?></div>
                        </div>
                    </div>
                    <div style="padding: 0 10px;">
                        <div style="font-weight: bold; font-size: 24px; line-height: 30px;">ชื่อที่อยู่ผู้รับ</div>
                        <div style="font-size: 22px; line-height: 24px;"><?= $customer[$j]['customerName'] ?> <?php if(isset($customer[$j]['telephone']) && $customer[$j]['telephone'] != '') { ?>
                            <span style="font-size: 22px; font-weight: normal;"><b>โทร.</b> <?= $customer[$j]['telephone'] ?></span>
                        <?php } ?></div>
                        <div style="font-size: 22px; line-height: 24px;"><?= $customer[$j]['customerAddress'] ?></div>
                        <div style="text-align: center; margin-top: 10px;">
                            <?php
                                $postcode = str_replace(' ', '', $customer[$j]['postcode']);
                                $split = str_split(($postcode!=null && strlen($postcode) > 0 && preg_match('/^[0-9]+$/i',$postcode))?$postcode:'     ');
                                if($split && is_array($split)) {
                                    ?>
                                    <table width="50%" border="1" cellpadding="5" style="font-family: tahoma;border-collapse: collapse; line-height: 24px; font-size: 22px; ">
                                        <tr>
                                    <?php
                                    foreach ($split as $number) {
                                        ?>
                                        <td valign="middle" align="center" height="36px"><strong><?= $number ?></strong></td>
                                        <?php
                                    }
                                    ?>
                                        </tr>
                                    </table>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div style="border-top: none; border-left: none; border-right: none; border-style: dashed; margin: 0;"></div>
            <?php
                if((($j+1)%3)==0) {
                    if($j+1 != count($order)) {
                    ?>
                    <div style="display: block;page-break-before: always;"></div>
                    <?php
                    }
                }
            }
        }    
    ?>
    </body>
</html>