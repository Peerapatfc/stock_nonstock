<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('sale_deleted'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("sales/setdelete", $attrib); ?>
        <div class="modal-body">
            <div class="row">
			<?php #echo $owner; ?>
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= lang("staff_note", "staff_note"); ?>
                        <?php echo form_textarea('staff_note', $staff_note->staff_note, 'class="form-control input-tip" id="staff_note"'); ?>
                    </div>
                </div>
			</div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('sales_setdelete', lang('sale_deleted'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php echo  $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
	console.log("<?php echo $owner; ?>");
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
