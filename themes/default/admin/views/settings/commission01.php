<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?= admin_form_open('system_settings/commission_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
    content: "\f0d6";
}
</style>
        <h2 class="blue"><i class="fa fa-money" aria-hidden="true"></i><?= lang('commission'); ?></h2>

    </div>
    <div class="box-content">
	
	
                                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                echo admin_form_open('#' . $user->id, $attrib);
                                ?>
        <div class="row">
            <div class="col-lg-3">
                    <div class="form-group">
                        <?= lang("condition_from", "condition_from"); ?>
                        <?php echo form_input('condition_from_value', '', 'class="form-control" id="" '); ?>
                    </div>
            </div>
			
            <div class="col-lg-3">
                    <div class="form-group">
                        <?= lang("condition_to", "condition_to"); ?>
                        <?php echo form_input('condition_to_value', '', 'class="form-control" id="" '); ?>
                    </div>
            </div>
			
            <div class="col-lg-3">
                    <div class="form-group">
                        <?= lang("price", "price"); ?>
                        <?php echo form_input('price', '', 'class="form-control" id="" '); ?>
                    </div>
            </div>
            <div class="col-lg-3">
                    <div class="form-group">
                        <?= lang("type", "type"); ?>
                        <?php echo form_input('type', '', 'class="form-control" id="" '); ?>
                    </div>
            </div>
			
        </div>
		
                                            <?php #echo form_hidden('id', $id); ?>
                                            <?php #echo form_hidden($csrf); ?>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo form_submit('save', lang('save'), 'class="btn btn-success"'); ?></p>
                                <?php echo form_close(); ?>
		
    </div>
</div>

<div style="display: none;">
    <input type="hidden" name="form_action" value="" id="form_action"/>
    <?= form_submit('submit', 'submit', 'id="action-form-submit"') ?>
</div>
<?= form_close() ?>