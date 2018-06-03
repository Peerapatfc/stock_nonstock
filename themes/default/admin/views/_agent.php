<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog" id="slip-attachment">
    <div class="modal-content">
		<div class="slip-attachment">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('attachment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
		#echo $id;
        echo admin_form_open("agent", $attrib); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12" style="text-align:center">
					<img src="<?php echo admin_url('welcome/download/'.$pic['attachment']); ?>" style="width: 450px; max-width:100%;">
                </div>
			</div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer col-center">
			<?php if($show > 0): ?>
            <a href="javascript:void(0)" class="btn btn-danger" id="view_add_payment"><i class="fa fa-money"></i> <?php echo lang("confirm payment"); ?></a>
			<?php endif; ?>
		</div>
		</div>

    </div>
    <?php echo form_close(); ?>
</div>

<?php echo  $modal_js ?>
<style type="text/css">
	#add_payment{display:none;}
</style>
<script type="text/javascript">
	jQuery("#view_add_payment").on("click",function(){
		jQuery('#add_payment').show();
		jQuery('#slip-attachment').hide();
	});
</script>