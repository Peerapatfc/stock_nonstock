<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#POData').dataTable({
            "aaSorting": [[1, "desc"], [2, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=admin_url('purchases/getPurchases' . ($warehouse_id ? '/' . $warehouse_id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
				
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [
			{"bSortable": false,
			"mRender": checkbox},
			
			null,
			null,
			null,			
			{"mRender": row_status},
			{"mRender": currencyFormat}, 
			{"mRender": currencyFormat}, 
			{"mRender": currencyFormat}, 
			{"mRender": pay_status}, 
			{"mRender": fld},
			{"bSortable": false,"mRender": attachment},

			{"bSortable": false}]
			
			,'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				
				console.log(aData);
                var oSettings = oTable.fnSettings();
				
				
                nRow.id = aData[0];
                nRow.className = "purchase_link";

				//วันเวลา
				var date = aData[9].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(9)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');
				
				
				var current_user_id= "<?php echo $this->companies_model->getCompanyByID($this->session->userdata('biller_id'))->id; ?>";
				var group_user = "<?php echo $this->site->getUserGroup($this->session->userdata('user_id'))->name;?>";
				//console.log(current_user);
				//order
				console.log('aData[11] ='+aData[11]);
				console.log('current_user_id ='+current_user_id);
				if(aData[11]==current_user_id){
					var data_order = '<div class="text-center"><span style="background-color:#FF7600;" class="row_status order-status label"><i class="fa fa-minus-square" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}else{
					var data_order = '<div class="text-center"><span style="background-color:#6f0f70;" class="row_status order-status label"><i class="fa fa-plus-square" aria-hidden="true"></i> '+aData[1]+'</span></div>';
				}
				$('td:eq(1)', nRow).html(data_order);
				
				//Popup slip
				var uri = '<?php echo admin_url("purchases/popup/") ?>' + nRow.id;
				if(aData[10] != null){
					$('td:eq(10)', nRow).addClass('popup-slip');
					$('td:eq(10)', nRow).html('<div class="text-center"><a href='+ uri +' data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="<?php echo lang("attachment"); ?>"><i class="fa fa-file"></i></a></div>');
				}else{
					$('td:eq(10)', nRow).html('<div class="text-center text-muted"><i class="fa fa-file"></i></div>');
				}
				if(aData[11]==current_user_id){
					var data_actions = aData[12];
				}else{
					var data_actions = "<div class='parent_sale'>"+aData[12]+"</div>";
				}
				$('td:eq(11)', nRow).html(data_actions);
				
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    total += parseFloat(aaData[aiDisplay[i]][5]);
                    paid += parseFloat(aaData[aiDisplay[i]][6]);
                    balance += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = currencyFormat(total);
                nCells[6].innerHTML = currencyFormat(paid);
                nCells[7].innerHTML = currencyFormat(balance);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('ref_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('purchase_status');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
			{column_number: 9, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
        ], "footer");

        <?php if ($this->session->userdata('remove_pols')) {?>
        if (localStorage.getItem('poitems')) {
            localStorage.removeItem('poitems');
        }
        if (localStorage.getItem('podiscount')) {
            localStorage.removeItem('podiscount');
        }
        if (localStorage.getItem('potax2')) {
            localStorage.removeItem('potax2');
        }
        if (localStorage.getItem('poshipping')) {
            localStorage.removeItem('poshipping');
        }
        if (localStorage.getItem('poref')) {
            localStorage.removeItem('poref');
        }
        if (localStorage.getItem('powarehouse')) {
            localStorage.removeItem('powarehouse');
        }
        if (localStorage.getItem('ponote')) {
            localStorage.removeItem('ponote');
        }
        if (localStorage.getItem('posupplier')) {
            localStorage.removeItem('posupplier');
        }
        if (localStorage.getItem('pocurrency')) {
            localStorage.removeItem('pocurrency');
        }
        if (localStorage.getItem('poextras')) {
            localStorage.removeItem('poextras');
        }
        if (localStorage.getItem('podate')) {
            localStorage.removeItem('podate');
        }
        if (localStorage.getItem('postatus')) {
            localStorage.removeItem('postatus');
        }
        if (localStorage.getItem('popayment_term')) {
            localStorage.removeItem('popayment_term');
        }
        <?php $this->sma->unset_data('remove_pols');}
        ?>

		var add_purchases = '<a style="margin-right:10px;" class="btn btn-danger" href="<?=admin_url('purchases/add')?>"> <i class="fa fa-plus-circle"></i> <?=lang('add_purchase')?></a>';
		$("#POData_length label").append(add_purchases);

		var print_packing = $(".print-packing").html();
		$("#POData_length").append(print_packing);
		





		function parseDateValue(rawDate) {
			var dateArray= rawDate.split("-");
			var parsedDate= dateArray[0] + dateArray[1] + dateArray[2];
			return parsedDate;
		}
		/*var searchbydate = "<div id='baseDateControl'><div class='dateControlBlock'>Between<?php echo form_input('dateStart', $date, 'class="form-control input-tip date-custom"  id="dateStart" required="required"   data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?>and<?php echo form_input('dateEnd', $date, 'class="form-control input-tip date-custom"  id="dateEnd" required="required"   data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?></div></div>";*/
		var search_by_date = '<div id="baseDateControl"><div class="dateControlBlock"> From <input type="text" name="dateStart" value=""  class="form-control input-tip date-custom"  id="dateStart" required="required"   data-bv-notempty-message="Enter the correct date" /> To <input type="text" name="dateEnd" value=""  class="form-control input-tip date-custom"  id="dateEnd" required="required"   data-bv-notempty-message="Enter the correct date" /></div></div>';
	

		//foreach ($warehouses as $warehouse) {
								//	$namebywarehouse = $this->site->getUserByWarehouse($warehouse->id)->first_name." ".$this->site->getUserByWarehouse($warehouse->id)->last_name;
								//	if(!empty($namebywarehouse)):
                            	      //  echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . admin_url('purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $namebywarehouse . '</a></li>';
										
										//echo form_dropdown('bank_to', $bank, '', 'class="form-control input-tip" required="required" id="slbank_to"');
								
                                   // $wh[''] = lang('select').' '.lang('warehouse');
                                   // foreach ($warehouses as $warehouse) {
                                     //   $wh[$warehouse->id] = $warehouse->name;
                                  //  }
                               //   form_dropdown('warehouse', $wh, $warehouse_id, 'id="warehouse" class="form-control select" disabled="disabled" style="width:100%;" ');
							
                                  
									
                            	   // }	
	
	
	//var dropdownSelectOrderStatus = "<select id='order-status-list'><option value=''><?php echo lang('select_order_status'); ?></option><option value=''><?php echo lang('all'); ?></option><option value='wait'><?php echo lang('wait'); ?></option><option value='completed'><?php echo lang('completed'); ?></option></select>";
       
	//	$(".dataTables_filter").prepend(search_by_date);
		//$(".dataTables_filter").prepend(search_by_warehouse);
		
		
		var dateControls= $("#baseDateControl").children("div").clone();
		//$("#POData_filter").prepend(dateControls);
	
		//$('#POData').dataTable().moment('YYYY MMMM D HH:mm');

		$("#dateStart").keyup ( function() { 
			oTable.fnFilter($(this).val(),9,true,false).fnDraw(); 
		});
		$("#dateStart").change( function() { 
			oTable.fnFilter($(this).val(),9,true,false).fnDraw(); 
		});
		$("#dateEnd").keyup ( function() { 
			oTable.fnFilter($(this).val(),9,true,false).fnDraw(); 
		});
		$("#dateEnd").change( function() { 
			oTable.fnFilter($(this).val(),9,true,false).fnDraw(); 
		});
		
		
	

		

		
			
		

			/* Initialise datatables */
		
			 
			/* Add event listeners to the two range filtering inputs */

			
			

		
		
    });

</script>

<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
   content:"\f03a";
}
</style>
        <h2 class="blue"><i
                class="fa-fw fa fa-list"></i><?=lang('purchases') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')';?>
        </h2>

        <div class="box-icon">
		
            <ul class="btn-tasks">
			<?php if($this->session->userdata('user_id')==$this->Settings->default_admin_user || $Owner){ ?>
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
			<?php } ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=admin_url('purchases/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('add_purchase')?>
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
                            <a href="#" class="bpo" title="<b><?=lang("delete_purchases")?></b>"
                                data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                                data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?=lang('delete_purchases')?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if (count($warehouses)>0) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building tip" data-placement="left" title="<?=lang("select_agent")?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=admin_url('purchases')?>"><i class="fa fa-user"></i> <?=lang('all_agent')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
									if($warehouse->id):
										$namebywarehouse = $this->site->getUserByWarehouse($warehouse->id)->first_name." ".$this->site->getUserByWarehouse($warehouse->id)->last_name;
										if(!empty(trim($namebywarehouse))):
												echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . admin_url('purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $namebywarehouse . " (" .$this->site->getUserByWarehouse($warehouse->id)->seller_id .")" . '</a></li>';
										endif;
									endif;
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
	
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">
                    <?php echo admin_form_open("purchases/purchases_setting"); ?>
                    <div class="row">
					
					<div class="well clear">
						<div style="margin-top:0px;margin-bottom: 5px;">
                            <h2 style="margin-top:0px;"><i class="fa fa-print" aria-hidden="true"></i>ใบเสร็จรับเงิน (Receipt)</h2>
						</div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="prefix"><?= lang("prefix"); ?></label>
                                <?php echo form_input('odprefix', $purchases_setting->odprefix, 'class="form-control tip" id="odprefix"'); ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="digits"><?= lang("digits"); ?></label>
                                <?php echo form_input('oddigits', $purchases_setting->oddigits, 'class="form-control tip" id="oddigits"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <?= lang("number_current", "number_current"); ?>
                                <?php echo form_input('odcurrent', sprintf("%0".$purchases_setting->oddigits."d", $purchases_setting->odcurrent), 'class="form-control " id="odcurrent"'); ?>
                            </div>
                        </div>
					</div>
						
					<div class="well clear">
						<div style="margin-top:0px;margin-bottom: 5px;">
                            <h2 style="margin-top:0px;"><i class="fa fa-print" aria-hidden="true"></i>ใบสั่งซื้อ (PO)</h2>
						</div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="prefix"><?= lang("prefix"); ?></label>
                                <?php echo form_input('poprefix', $purchases_setting->poprefix, 'class="form-control tip" id="poprefix"'); ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="digits"><?= lang("digits"); ?></label>
                                <?php echo form_input('podigits', $purchases_setting->podigits, 'class="form-control tip" id="podigits"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <?= lang("number_current", "number_current"); ?>
                                <?php echo form_input('pocurrent', sprintf("%0".$purchases_setting->podigits."d", $purchases_setting->pocurrent), 'class="form-control " id="pocurrent"'); ?>
                            </div>
                        </div>
					</div>
					
					<div class="well clear">
						<div style="margin-top:0px;margin-bottom: 5px;">
                            <h2 style="margin-top:0px;"><i class="fa fa-print" aria-hidden="true"></i>ใบส่งของ (delivery order)</h2>
						</div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="prefix"><?= lang("prefix"); ?></label>
                                <?php echo form_input('spprefix', $purchases_setting->spprefix, 'class="form-control tip" id="spprefix"'); ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="digits"><?= lang("digits"); ?></label>
                                <?php echo form_input('spdigits', $purchases_setting->spdigits, 'class="form-control tip" id="spdigits"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <?= lang("number_current", "number_current"); ?>
                                <?php echo form_input('spcurrent', sprintf("%0".$purchases_setting->spdigits."d", $purchases_setting->spcurrent), 'class="form-control " id="spcurrent"'); ?>
                            </div>
                        </div>
					</div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
				</div>
				
				 
				

				<?php if ($Owner || $GP['bulk_actions']) {
						echo admin_form_open('purchases/purchase_actions', 'id="action-form" name="purchase_form"');
					}
				?>

                <div class="table-responsive2">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>

                            <th style="min-width:80px;"><?= lang("ref_no"); ?></th>
                            <th style="min-width:180px;"><?= lang("account_sell"); ?></th>
							<th style="min-width:180px;"><?= lang("account_buy"); ?></th>
                            <th style="min-width:80px;"><?= lang("purchase_status"); ?></th>
                            <th style="min-width:80px;"><?= lang("grand_total"); ?></th>
                            <th style="min-width:80px;"><?= lang("paid"); ?></th>
                            <th style="min-width:80px;"><?= lang("balance"); ?></th>
                            <th style="min-width:80px;"><?= lang("payment_status"); ?></th>
							<th style="min-width:220px;"><?= lang("date"); ?></th>
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
							
                            <th style="width:100px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="11" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
							<th><?= lang("account_user"); ?></th>
                            <th></th>

							<th></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th><?= lang("paid"); ?></th>
                            <th></th>
                            <th></th>
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
                            <th style="width:100px; text-align: center;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
				<?php if ($Owner || $GP['bulk_actions']) {?>
					<div style="display: none;">
						<input type="hidden" name="form_action" value="" id="form_action"/>
						<?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
					</div>
					<?=form_close()?>
				<?php } ?>
            </div>
        </div>
    </div>

	<?php /* <div class="print-packing" style="display:none;">
	<?php if ($Owner || $GP['bulk_actions']) {?>
	<table class="btn-print">
		<tr>
			<td>
				<a style="max-width:200px" href="#" class="tip btn btn-danger" id="acprint_packing" data-action="acprint_packing" data-original-title="<?php echo lang('print & packing'); ?>" data-action-url="<?php echo admin_url('purchases/print_shipping_label');?>">
					<i class="fa fa-print" aria-hidden="true"></i> 
					<span><?= lang('print & packing') ?></span>
				</a>
			</td>
		<?php /*	<td>&nbsp;</td>
			<td>
				<?php
					$print_pdf = array('Addressee' => lang('Addressee_(Sticker)'), 'Company' => lang('Submitted_by_Company_(A4)'));
					echo form_dropdown('print_pdf', $print_pdf, '', 'class="form-control input-tip select" required="required" id="print_pdf"'); ?>
			</td>
		</tr>
	</table>
	<?php  } ?>
	</div> */ ?>
</div>



<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
// print shipping label
    $(document).on('click', "#acprint_packing", function(e){
        e.preventDefault();
		var print_pdf = $('#print_pdf').val();
		if(print_pdf == "Addressee"){
			var form_action = "acprint_packing";
		}else{
			var form_action = "owner_receiver";
		}
		//var action = "<?php echo admin_url('purchases/print_shipping_label');?>";
		var action = $(this).attr('data-action-url');
        $('#form_action').val(form_action);
		$("form[name=purchase_form]").attr("action",action);
		$("form[name=purchase_form]").attr("target","purchase_form");
		var viewportwidth = document.documentElement.clientWidth;
		window.open("","purchase_form","width=500,height="+(viewportwidth-300)+",top=0,left=1000,toolbar=0");
		$('#action-form-submit').click();
		setTimeout(function(){
			$('#POData').dataTable().fnDraw();
		},500);
    });
	
		$('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		
		
		$('.date-custom').datetimepicker({format: 'yyyy-mm-dd', fontAwesome: true, language: 'sma', todayBtn: 1, autoclose: 1, minView: 2 });
		


});
	/*	setTimeout(
			function(){
				$('.parent_sale .delivery-link').add();
			},
		100);*/
		
		$(document).on('click', "button.btn", function(e){
			$('.parent_sale .delivery-link').remove();
			$('.parent_sale .payment-link').remove();
		});
</script>



<style>
#POData_length > label {
    float: left;
    margin: 0 !important;
}


.dataTables_filter, .dataTables_length {
    clear: both;
    display: inline-block;
    width: 100%;
}
	.btn-print {
		float: none;
		display: inline-block;
	}
@media only screen 
	and (max-width : 1399px) {
	.btn-print {
		margin: 5px 0;
	}
}


@media only screen 
and (max-width : 1024px) {

}


</style>