<script>

    $(document).ready(function () {
        oTable1 = $('#wallet_summary').dataTable(
		{
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
			
            'sAjaxSource': '<?= admin_url('auth/getWalletForApprove') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
				
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
				nRow.id = aData[0];
			
				
			/* Slip */
			if(aData[2]!=""){
				var uri = '<?php echo admin_url("auth/popup/") ?>' + nRow.id;
				//uri_pic = '<?php echo base_url();?>files/'+aData[3];
				$('td:eq(3)', nRow).html('<div class="text-center"><a href='+ uri +' data-toggle="modal" data-target="#myModal" class="tip" title="" data-original-title="<?php echo lang("attachment"); ?>"><i class="fa fa-file"></i></a></div>');
			}else{
				$('td:eq(3)', nRow).html('<div class="text-center text-muted"><i class="fa fa-file"></i></div>');
			}
				var date = aData[1].split(" ");
				if('<?php echo date('Y-m-d'); ?>' == date[0]){ date[0] = "<?php echo lang('today'); ?>";}
				$('td:eq(1)', nRow).html('<span class="cdate"><i class="fa fa-calendar" aria-hidden="true"></i> '+ date[0] + '</span><span class="ctime"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + date[1] + '</span>');

				var approve = aData[5];
				if(approve=="1"){
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-success"><i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('approve');?></span></div>');
				}else{
					$('td:eq(5)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-danger"><i class="fa fa-close" aria-hidden="true"></i> <?php echo lang('disapprove');?></span></div>');
				}
				
				var transfer = aData[4];
				if(transfer=="deposit"){
					$('td:eq(4)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-success"><?php echo lang('deposit');?></span></div>');
				}else if(transfer=="withdraw"){
					$('td:eq(4)', nRow).html('<div class="text-center"><span class="row_status payment_status label label-danger"><?php echo lang('withdraw');?></span></div>');
				}
				
				var slip = aData[3];
			
				if(slip==null){
					$('td:eq(3)', nRow).html('');
				}
			
			
			
            },"aoColumns": [
				{"bSortable": false,"mRender": checkbox,"bVisible": true},
				null,
				{"mRender": currencyFormat},
				null,
				null,
				null,
			]
			
        });
		
		var uri = '<?= admin_url('auth/approveWalletByAdmin'); ?>';
		var wallet_approve = '<a class="btn btn-success bt-approve" href="javascript:void(0)"><i class="fa fa-check"></i> <?php echo lang('approve');?></a>';
		var wallet_disapprove = '&nbsp;<a class="btn btn-danger bt-approve" href="javascript:void(0)"><i class="fa fa-close"></i> <?php echo lang('disapprove');?></a>';
		//var wallet_approve = '<input type="submit" value="Approve"/>';
		$("#wallet_summary_length label").append(wallet_approve);
		$("#wallet_summary_length label").append(wallet_disapprove);
		
		
		var dropdownSelectOrderType = "<select id='wallet-approve'><option value=''><?php echo lang('select_approve_wallet_type'); ?></option><option value=''><?php echo lang('all'); ?></option><option value='0' ><?php echo lang('disapprove'); ?></option><option value='1'><?php echo lang('approve'); ?></option></select>";
        
		$(".dataTables_filter").append(dropdownSelectOrderType);
		
	
        $("#wallet-approve").change(function(){
            oTable1.fnFilter($(this).val(),5).fnDraw();
        });
    });
	
	

	
	

</script>
<?php echo admin_form_open_multipart("auth/approveWalletByAdmin", 'id="wallet-update"'); ?>
<div class="box">
    <div class="box-header">
	<style>
	.pageheader .icon::before {
		content: "\f0c9";
	}
	</style>
	 
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('transfer_wallet'); ?></h2>


    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('transfer_wallet'); ?></p>

                <div class="table-responsive">
                    <table id="wallet_summary" class="table table-bordered table-hover table-striped table-condensed">
							<thead>
								<tr>
									<th><input class="checkbox checkft" type="checkbox" name="check"/></th>
									<th><?= lang("date"); ?></th>
									<th><?= lang("amount"); ?></th>
									<th><?= lang("slip"); ?></th>
									<th><?= lang("wallet_type"); ?></th>
									<th><?= lang("status"); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="7" class="dataTables_empty"><?= lang("loading_data"); ?></td>
								</tr>
							</tbody>
							</table>
                </div>
	
				
             
            </div>
        </div>
    </div>
	<input type="hidden" value="1" name="approve" id="chk_approve" />
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).on('click', '.bt-approve', function(e) {
		e.preventDefault();
		//$('.bt-approve').on('click',function(){
		//$("#wallet-update").submit();
		if($(this).hasClass('btn-danger')){
			$('#chk_approve').attr('value',0);
		}else{
			$('#chk_approve').attr('value',1);
		}
		//console.log($('#chk_approve').attr('value'));
		$('#wallet-update').submit();
	});

		
</script>