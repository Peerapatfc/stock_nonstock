<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('attachment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("sales/popup", $attrib); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12" style="text-align:center">
					<img src="<?php echo admin_url('welcome/download/'.$pic); ?>" style="width: 450px; max-width:100%;">
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
