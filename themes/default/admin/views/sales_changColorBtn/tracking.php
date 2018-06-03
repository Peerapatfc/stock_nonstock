<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('tracking'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("sales/tracking", $attrib); ?>
        <div class="modal-body">
            <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("order", "tracking_order"); ?>
                                <?php echo form_input('tracking_order', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('tracking_order') . '" id="tracking_order"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("tracking number", "tracking_number"); ?>
                                <?php echo form_input('tracking_number', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('tracking_number') . '" id="tracking_number"'); ?>

                            </div>
                        </div>

			</div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php echo  $modal_js ?>