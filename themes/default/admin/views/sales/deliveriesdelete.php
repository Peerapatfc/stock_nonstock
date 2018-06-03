<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('deleted_deliveries'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("sales/deliveriesdelete", $attrib); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("note", "note"); ?>
                        <?php echo form_textarea('note', $note->note, 'class="form-control input-tip" id="staff_note" required="required"'); ?>
                    </div>
                </div>
			</div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('sales_deliveriesdelete', lang('deleted_deliveries'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
