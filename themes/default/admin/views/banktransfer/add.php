<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_banktransfer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("banktransfer/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("bank", "btfbank"); ?>
                        <?php echo form_input('bank', '', 'class="form-control input-tip" id="btfbank" required="required"'); ?>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("account_name", "btfaccount_name"); ?>
                        <?php echo form_input('account_name', '', 'class="form-control input-tip" id="btfaccount_name" required="required"'); ?>
                    </div>
                </div>
				
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("account_number", "btfaccount_number"); ?>
                        <?php echo form_input('account_number', '', 'class="form-control input-tip" id="btfaccount_number" required="required"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("nickname", "btfnickname"); ?>
                        <?php echo form_input('nickname', '', 'class="form-control input-tip" id="btfnickname" required="required"'); ?>
                    </div>
                </div>

            </div>




        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_banktransfer', lang('save_banktransfer'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>

