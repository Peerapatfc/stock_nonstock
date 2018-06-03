<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_banktransfer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("banktransfer/edit", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>
            <div class="row">
			<?php #echo print_r($banktransfer); ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("bank", "btfbank"); ?>
                        <?php echo form_input('bank', $banktransfer->bank, 'class="form-control input-tip" id="btfbank" required="required"'); ?>
                    </div>
                </div>
			
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("account_name", "btfaccount_name"); ?>
                        <?php echo form_input('account_name', $banktransfer->account_name, 'class="form-control input-tip" id="btfaccount_name" required="required"'); ?>
                    </div>
                </div>
				
                <div class="col-sm-6"> 
                    <div class="form-group">
                        <?= lang("account_number", "btfaccount_number"); ?>
                        <?php echo form_input('account_number', $banktransfer->account_number, 'class="form-control input-tip" id="btfaccount_number" required="required"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("nickname", "btfnickname"); ?>
                        <?php echo form_input('nickname', $banktransfer->nickname, 'class="form-control input-tip" id="btfnickname" required="required"'); ?>
                    </div>
                </div>
			
            </div>

            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_banktransfer', lang('edit_banktransfer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>