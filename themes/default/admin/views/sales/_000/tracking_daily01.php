<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog" id="tracking_daily">

    <div class="modal-content">

		<div class="tracking_daily">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('tracking_daily'); ?></h4>
        </div>
        <?php #$attrib = array('data-toggle' => 'validator', 'role' => 'form');
		#echo $id;
        //echo admin_form_open("sales/tracking_daily", $attrib); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
					<div class="form-group">
						<?= lang("tracking_day", "tracking_day"); ?>
						<?php echo form_input('tracking_day', $date, 'class="form-control input-tip date"  id="tracking_day" required="required"   data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?>
					</div>
							<?php
								$txt = '';
								$txt = $date.'</strong><br/>';
								$i = 0;
								foreach($datatrack as $key => $value):
								$i++;
									$txt = $txt.$value.'<br/>';
								endforeach;  
							?>
							
					<div class="form-group">
						<?= lang("tracking_data", "tracking_data"); ?>
						<?php echo form_textarea('tracking_data', $txt, 'id="tracking_data"'); ?>
					</div>
					<button type="button" class=" button btn btn-danger btn-lg"><?php echo lang('select_all'); ?></button>

                </div>
			</div>
            <?php #echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer col-center">

		</div>
		</div>
    </div>

    <?php //echo form_close(); ?>
</div>

<?php echo  $modal_js ?>
<style type="text/css">

</style>


<script type="text/javascript">
	$('button.button').on('click', function() {
		 $('.redactor_editor').focus();
			document.execCommand('selectAll', false, null);
			document.execCommand("copy");
			document.getSelection().removeAllRanges();
	});
	
	$('#tracking_day').on('change', function() {

		var date = $(this).val();
		console.log(date);
			$.ajax({
				type: 'get',
				url: '<?= admin_url('sales/tracking_date'); ?>',
				dataType: "json",
				data: {
					term: date,
				}, success: function(data){
					var content = '';
					var i =0; k = 1;
					var txt = '';
					content += "<strong>"+date+"</strong><br/>";
					for(i = 0; i < data.length; i++) { 
						txt = k++ +'. ' + data[i]['customer'] + ' ' +data[i]['tracking'];
						content += txt+"<br/>";
					}
					$('.redactor_editor').html('');
					$(content).appendTo(".redactor_editor");
				}
			});
	});
	
</script>