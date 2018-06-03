<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_shipping'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open_multipart("system_settings/add_shipping", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang("image", "image") ?>
                <input id="image" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile" data-show-upload="false" data-show-preview="false" class="form-control file">
            </div>
			
			
			<div class="form-group">
				<?= lang("condition", "condition"); ?>
				<?php 
				$condition_name = array('price' => lang('ship_for_price'), 'item' => lang('ship_for_item'), 'weight' => lang('ship_for_weight'));
				echo form_dropdown('condition_name', $condition_name, $shipping->price, 'class="form-control input-tip" required="required" id="condition_name"'); ?>
			</div>

            <div class="form-group">
                <?= lang('condition_from_value', 'condition_from_value'); ?>
                <?= form_input('condition_from_value', $shipping->condition_from_value, 'class="form-control tip" id="name" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('condition_to_value', 'condition_to_value'); ?>
                <?= form_input('condition_to_value', $shipping->condition_to_value, 'class="form-control tip" id="name" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('price', 'price'); ?>
                <?= form_input('price', $shipping->price, 'class="form-control tip" id="name" required="required"'); ?>
            </div>
			
            <div class="form-group">
                <?= lang('delivery_type', 'delivery_type'); ?>
				<?php 
				$delivery_type = array('ems' => lang('ems'), 'kerry' => lang('kerry'), 'pickup' => lang('PickUp'));
				echo form_dropdown('delivery_type', $delivery_type, $shipping->delivery_type, 'class="form-control input-tip" required="required" id="delivery_type"'); ?>
            </div>
			
            <div class="form-group">
                <?= lang('ordering', 'ordering'); ?>
                <?= form_input('ordering', $shipping->ordering, 'class="form-control tip" id="code" required="required"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_shipping', lang('add_shipping'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
