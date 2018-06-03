<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#SLData').dataTable({
            "aaSorting": [[12, "desc"], [2, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=admin_url('sales/getSales' . ($warehouse_id ? '/' . $warehouse_id : '') .'?v=1'. ($this->input->get('shop') ? '&shop='.$this->input->get('shop') : ''). ($this->input->get('attachment') ? '&attachment='.$this->input->get('attachment') : ''). ($this->input->get('delivery') ? '&delivery='.$this->input->get('delivery') : '')); ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                console.log(aData);
                var oSettings = oTable.fnSettings();
                //$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                nRow.id = aData[0];
                nRow.setAttribute('data-return-id', aData[9]);
                nRow.className = "invoice_link re"+aData[9];

				/* start wow */
				console.log(aData);
				var ck = 0;
				ck = '<?php echo $Owner; ?>';

				
				//chanel
				icon = "";
				if(aData[1] == "magento"){
					icon = '<?php echo base_url().'assets/images/icon/icon_magento.png' ?>';
				}else if(aData[15] != null && aData[16] != null){
					icon = '<?php echo base_url().'assets/images/icon/icon_line.png' ?>';
				}else if(aData[15] != null){
					icon = '<?php echo base_url().'assets/images/icon/icon_line.png' ?>';
				}else if(aData[16] != null){
					icon = '<?php echo base_url().'assets/images/icon/icon_facebook.png' ?>';
				}
				else{
					icon = '<?php echo base_url().'assets/images/icon/icon_smith.png' ?>';
				}
				$('td:eq(1)', nRow).html('<div class="text-center text-muted"><img width="26" height="26"  src='+ icon +' /></div>');
				
				
				//สถานะคำสั่งซื้อ
				if (aData[5] == 'wait')
				{
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status order-status label label-warning"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo lang('wait'); ?></span></div>' );
				}
				if(aData[5] == "completed"){
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status order-status label label-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php echo lang('completed'); ?></span></div>');
				}
				
				
				//สถานะชำระงิน
				if(aData[9] == "pending"){
					$('td:eq(5)', nRow).addClass('asset-locked disable');
					$('td:eq(9)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-warning"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo lang('pending'); ?></span></div>');
				}
				if(aData[9] == "paid"){
					$('td:eq(9)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-success "><i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php echo lang('paid'); ?></span></div>');
				}
				if(aData[9] == "partial"){
					$('td:eq(9)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-info"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo lang('partial'); ?></span></div>');
				}
				
				
				//สถานะจัดส่ง
				if(aData[10] != null){
					$('td:eq(10)', nRow).html( '<div class="text-center"><span class="row_delivering label label-success">'+ aData[10] +'</span></div>' );
				}else{
					if (aData[13] == '1')
					{
						$('td:eq(10)', nRow).html( '<div class="text-center"><span class="row_delivery text-success"><i class="fa fa-truck" aria-hidden="true"></i></span></div>' );
					}else{
						$('td:eq(10)', nRow).html( '<div class="text-center"><span class="row_delivery text-danger"><i class="fa fa-truck" aria-hidden="true"></i></span></div>' );
					}
				}
				
				
				//Popup slip
				var uri = '<?php echo admin_url("sales/popup/") ?>' + nRow.id;
				if(aData[11] != null){
					$('td:eq(11)', nRow).addClass('popup-slip');
					$('td:eq(11)', nRow).html('<div class="text-center"><a href='+ uri +' data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="<?php echo lang("attachment"); ?>"><i class="fa fa-file"></i></a></div>');
				}else{
					$('td:eq(11)', nRow).html('<div class="text-center text-muted"><i class="fa fa-file"></i></div>');
				}


				//วันเวลา
				var date = aData[12].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(12)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				
				//is_delete
				if(aData[14] > 0){ nRow.className = "invoice_link repending id_delete danger";}
				
				
				//order_type
				if(aData[17] == "dropship"){
					var data_col2 = '<div class="text-center"><span style="background-color:#FF7600;" class="row_status order-status label"><i class="fa fa-paper-plane" aria-hidden="true"></i> '+aData[2]+'</span></div>';
				}else{
					var data_col2 = '<div class="text-center"><span style="background-color:#123EAB;" class="row_status order-status label"><i class="fa fa-cubes" aria-hidden="true"></i> '+aData[2]+'</span></div>';
				}
				$('td:eq(2)', nRow).html(data_col2);

				
				//console.log(aData[2]);
				
				
				$('td:eq(13)', nRow).addClass('custom-position');
				
                return nRow;
            },
			

            "aoColumns": [
				{"bSortable": false,"mRender": checkbox}, // checkbox 
				
				null, // ช่องทาง 
				null,// เลขที่อ้างอิง 
				null,// ขายโดย 
				null, //ลูกค้า
				{"mRender": row_status}, // สถานะสั่งซื้อ 
				{"mRender": currencyFormat},// ราคารวม 
				{"mRender": currencyFormat},// ยอดชำระ 
				{"mRender": currencyFormat},// ขาด/เกิน 
				{"mRender": pay_status},// สถานะชำระเงิน 
				null, //สถานะจัดส่ง
				{"bSortable": false,"mRender": attachment},  //slip
				{"mRender": fld}, // Date 
				{"bVisible": false}, //is_deliveries hidden
				{"bVisible": false}, //is_delete hidden
				{"bVisible": false}, //line hidden
				{"bVisible": false}, //facebook hidden
				{"bVisible": false}, //order_type hidden
				{"bSortable": false},
			], 
			

			
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                    paid += parseFloat(aaData[aiDisplay[i]][7]);
                    balance += parseFloat(aaData[aiDisplay[i]][8]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[6].innerHTML = currencyFormat(parseFloat(gtotal));
                nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                nCells[8].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 12, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('sale_status');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
        ], "footer");

		var keyword = "<?php echo isset($_GET["q"]) ? $_GET["q"] : '' ?>";
		if(keyword){
			console.log(keyword);
			oTable.fnFilter(keyword);
			jQuery('#salesearch').val(keyword);
		}

        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }

        <?php if ($this->session->userdata('remove_slls')) {?>
        if (localStorage.getItem('slitems')) {
            localStorage.removeItem('slitems');
        }
        if (localStorage.getItem('sldiscount')) {
            localStorage.removeItem('sldiscount');
        }
        if (localStorage.getItem('sltax2')) {
            localStorage.removeItem('sltax2');
        }
        if (localStorage.getItem('slref')) {
            localStorage.removeItem('slref');
        }
        if (localStorage.getItem('slshipping')) {
            localStorage.removeItem('slshipping');
        }
        if (localStorage.getItem('slwarehouse')) {
            localStorage.removeItem('slwarehouse');
        }
        if (localStorage.getItem('slnote')) {
            localStorage.removeItem('slnote');
        }
        if (localStorage.getItem('slinnote')) {
            localStorage.removeItem('slinnote');
        }
        if (localStorage.getItem('slcustomer')) {
            localStorage.removeItem('slcustomer');
        }
        if (localStorage.getItem('slbiller')) {
            localStorage.removeItem('slbiller');
        }
        if (localStorage.getItem('slcurrency')) {
            localStorage.removeItem('slcurrency');
        }
        if (localStorage.getItem('sldate')) {
            localStorage.removeItem('sldate');
        }
        if (localStorage.getItem('slsale_status')) {
            localStorage.removeItem('slsale_status');
        }
        if (localStorage.getItem('slpayment_status')) {
            localStorage.removeItem('slpayment_status');
        }
        if (localStorage.getItem('paid_by')) {
            localStorage.removeItem('paid_by');
        }
        if (localStorage.getItem('amount_1')) {
            localStorage.removeItem('amount_1');
        }
        if (localStorage.getItem('paid_by_1')) {
            localStorage.removeItem('paid_by_1');
        }
        if (localStorage.getItem('pcc_holder_1')) {
            localStorage.removeItem('pcc_holder_1');
        }
        if (localStorage.getItem('pcc_type_1')) {
            localStorage.removeItem('pcc_type_1');
        }
        if (localStorage.getItem('pcc_month_1')) {
            localStorage.removeItem('pcc_month_1');
        }
        if (localStorage.getItem('pcc_year_1')) {
            localStorage.removeItem('pcc_year_1');
        }
        if (localStorage.getItem('pcc_no_1')) {
            localStorage.removeItem('pcc_no_1');
        }
        if (localStorage.getItem('cheque_no_1')) {
            localStorage.removeItem('cheque_no_1');
        }
        if (localStorage.getItem('slpayment_term')) {
            localStorage.removeItem('slpayment_term');
        }
        <?php $this->sma->unset_data('remove_slls');}
        ?>

        $(document).on('click', '.sledit', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
        $(document).on('click', '.slduplicate', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
        var dropdownSelectOrderType = "<select id='order-type-list'><option value=''><?php echo lang('select_order_type'); ?></option><option value=''><?php echo lang('all'); ?></option><option value='dropship'><?php echo lang('dropship'); ?></option><option value='stock'><?php echo lang('stock'); ?></option></select>";
        
        $(".dataTables_filter").append(dropdownSelectOrderType);
        $("#order-type-list").change(function(){
            oTable.fnFilter($(this).val(),17).fnDraw();
        });

        var dropdownSelectOrderStatus = "<select id='order-status-list'><option value=''><?php echo lang('select_order_status'); ?></option><option value=''><?php echo lang('all'); ?></option><option value='wait'><?php echo lang('wait'); ?></option><option value='completed'><?php echo lang('completed'); ?></option></select>";
        $(".dataTables_filter").append(dropdownSelectOrderStatus);
        $("#order-status-list").change(function(){
            oTable.fnFilter($(this).val(),5).fnDraw();
        });
    });

</script>
<style>
.pageheader .icon::before {
    content: "\f03a";
}
</style>
<?php if ($Owner || $GP['bulk_actions']) {
	    echo admin_form_open('sales/sale_actions', 'id="action-form"');
	}
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-list"></i><?=lang('sales') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')';?>
		</h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=admin_url('sales/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo" title="<b><?=lang("delete_sales")?></b>" data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>" data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?=lang('delete_sales')?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=admin_url('sales')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li><a href="' . admin_url('sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
                <?php if (SHOP) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-list-alt tip" data-placement="left" title="<?=lang("sales")?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li<?= $this->input->get('shop') == 'yes' ? ' class="active"' : ''; ?>><a href="<?=admin_url('sales?shop=yes')?>"><i class="fa fa-shopping-cart"></i> <?=lang('shop_sales')?></a></li>
                        <li<?= $this->input->get('shop') == 'no' ? ' class="active"' : ''; ?>><a href="<?=admin_url('sales?shop=no')?>"><i class="fa fa-heart"></i> <?=lang('staff_sales')?></a></li>
                        <li<?= !$this->input->get('shop') ? ' class="active"' : ''; ?>><a href="<?=admin_url('sales')?>"><i class="fa fa-list-alt"></i> <?=lang('all_sales')?></a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?=lang('list_results');?></p>

                <div class="table-responsive2">
                    <table id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
							<th style="min-width:80px;"><?= lang("chanel"); ?></th>
                            <th class="threference_no"><?= lang("reference_no"); ?></th>
                            <th class="thbiller"><?= lang("biller"); ?></th>
                            <th class="thcustomer"><?= lang("customer"); ?></th>
                            <th class="thsale_status"><?= lang("sale_status"); ?></th>
                            <th class="thgrand_total"><?= lang("grand_total"); ?></th>
                            <th class="thpaid_total"><?= lang("paid_total"); ?></th>
                            <th class="thbalance"><?= lang("balance"); ?></th>
                            <th class="thpayment_status"><?= lang("payment_status"); ?></th>
							<th class="thshipping_status"><?= lang("shipping_status"); ?></th>
                            <th class="thslip" style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
							<th class="thdate"><?= lang("date"); ?></th>
							<th></th>
							<th></th>
							<th>line</th>
							<th>facebook</th>
							<th>order_type</th>
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="12" class="dataTables_empty"><?= lang("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
							<th><?= lang("chanel"); ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th><?= lang("paid"); ?></th>
                            <th><?= lang("balance"); ?></th>
                            <th></th>
							<th class="thshipping_status"><?= lang("shipping_status"); ?></th>
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
							<th></th>
							<th></th>
							<th></th>
							<th>line</th>
							<th>facebook</th>
							<th>order_type</th>
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="add_sale" style="display:none">
    <a class="btn btn-danger" href="<?=admin_url('sales/add')?>">
        <i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
    </a>
</div>


<?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }else{
	//echo '<style>.box-header .box-icon, #SLData tbody td:last-child .dropdown-menu > li:not(:last-child) {display:none}</style>';
}
?>

<script type="text/javascript">
	setTimeout(function(){
		var add_sale = jQuery(".add_sale").html();
		jQuery("#SLData_length label").append(add_sale);
	},500);
</script>