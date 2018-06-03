<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_award_points'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
		echo admin_form_open_multipart("award_points/add", $attrib);?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("level", "level"); ?>
						<?php
							$level = array('1' => lang('Pureplus Bonus 1'), '2' => lang('Pureplus Bonus 2'), '3' => lang('Pureplus Bonus 3'));
							echo form_dropdown('level', $level, '', 'class="form-control input-tip" required="required" id="level_point"'); ?>
                    </div>
                </div>
			</div>
			
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("name", "name_point"); ?>
                        <?php echo form_input('name', '', 'class="form-control input-tip" id="name_point" required="required"'); ?>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("point", "point_point"); ?>
                        <?php echo form_input('point', '', 'class="form-control input-tip" id="point_point" required="required"'); ?>
                    </div>
                </div>
				
                <div class="col-sm-6">
                    <div class="form-group">
						<?= lang("point_start", "point_date"); ?>
                        <?php echo form_input('point_start', '', 'class="form-control input-tip date"  id="date_cf_payment" required="required"   data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?>
					</div>
                </div>
				
                <div class="col-sm-6">
                    <div class="form-group">
						<?= lang("point_end", "point_date"); ?>
                        <?php echo form_input('point_end', '', 'class="form-control input-tip date"  id="date_cf_payment" required="required"   data-bv-notempty-message="'.lang("Enter the correct date").'"'); ?>
					</div>
                </div>
				
                <div class="col-sm-12">
				    <div class="form-group">
                        <?= lang("point_document", "document") ?>
                        <input id="document" required="required" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                        data-show-preview="false" class="form-control file" data-bv-notempty-message =<?= lang('Please attach proof of transfer.');?> />
                    </div>
                </div>

				
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_award_points', lang('save_award_points'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>

