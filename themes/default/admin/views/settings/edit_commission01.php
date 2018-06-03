<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_commission'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/edit_commission/", $attrib); ?>
		
		
		
        <div class="modal-body">
		
            <p><?= lang('enter_info'); ?></p>
            <div class="form-group">
                <?= lang('condition_from_value', 'condition_from_value'); ?>
                <?= form_input('condition_from_value', $details->condition_from_value, 'class="form-control tip" id="code" required="required"'); ?>
            </div>
            <div class="form-group">
                <?= lang('condition_to_value', 'condition_to_value'); ?>
                <?= form_input('condition_to_value', $details->condition_to_value, 'class="form-control tip" id="name" required="required"'); ?>
            </div>
			
            <div class="form-group">
                <?= lang('price', 'price'); ?>
                <?= form_input('price', $details->price, 'class="form-control tip" id="name" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('type', 'type'); ?>
                <?= form_input('type', $details->type, 'class="form-control tip" id="name" required="required"'); ?>
            </div>

			<?php echo form_hidden('id', $id); ?>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_commission', lang('save_commission'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#base_unit').change(function(e) {
            var bu = $(this).val();
            if(bu > 0)
                $('#measuring').slideDown();
            else
                $('#measuring').slideUp();
        });
        var obu = <?= !empty($unit->base_unit) ? $unit->base_unit : 0; ?>;
        if(obu > 0)
            $('#measuring').slideDown();
        else
            $('#measuring').slideUp();
    });
</script>
