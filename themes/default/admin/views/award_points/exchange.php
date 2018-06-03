<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('exchange_points'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
		echo admin_form_open_multipart("auth/exchangepoints", $attrib);?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="row">
			
			<?php //print_r($inv);
				//print_r($point_old);
			?>
				<div class="col-sm-6">
					<img style="max-width:100%" src="<?php echo admin_url('welcome/download/').$inv->attachment; ?>" />
				</div>
                <div class="col-sm-6">
				<h3><?php echo lang('name')." : ".$inv->name; ?></h3>
                    <div class="form-group">
                        <?= lang("qty", "point_qty"); ?>
						<select name="qty" class="form-control" title="QTY">
						<?php
							$qty = intval($point_old/$inv->points);
							for( $i= 1 ; $i <= $qty ; $i++ )
							{
								echo '<option value="' . $i . '" >' . $i . '</option>';
							}
						?>
						</select>
                    </div>
                </div>
            </div>
			<?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_award_points', lang('save_award_points'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;

	$(document).ready(function () {
		$('#point_qty').change(function () {
			update_amounts();
		}).keydown();
	});

	function update_amounts() {
		var sum = 0.0;
		var qty = $('#point_qty').val();
		var points_use = parseInt('<?php echo $inv->points; ?>');
		var point_total = parseInt('<?php echo $point_old; ?>');
		var max_qty = parseInt(point_total/points_use);

		if(qty > max_qty){
			alert('test');
		}
	}
</script>

