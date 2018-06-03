<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
	<style>
	.pageheader .icon::before {
		content: "\f0c0";
	}
	</style>
        <h2 class="blue"><i class="fa fa-users" aria-hidden="true"></i><?= lang('agent'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<?php print_r($company); ?>
            </div>
        </div>
    </div>
</div>

