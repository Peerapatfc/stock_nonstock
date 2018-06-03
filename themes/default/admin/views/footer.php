<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->

</div></div></div></td></tr></table></div></div></div></div></div>

<div class="clearfix endbody"></div>
  <footer>© 2017 All Rights Reserved. Proudly created with <a title="smith ระบบตัวแทน" href="https://www.atcreative.co.th/smith/" target="__blank"><?php echo '<img style="width: 16px;" src="' . base_url('assets/images/copyright_gray.png'). '" />'; ?></a></footer>

</section>


<div class="clearfix"></div>

<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>


<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('url' => base_url(), 'base_url' => admin_url(), 'assets' => $assets, 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', returned: '<?=lang('returned');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>', download: '<?=lang('download');?>'};
</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>


<script src="<?= $assets ?>js/templatejs/js/jquery-ui-1.10.3.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/modernizr.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/jquery.sparkline.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/toggles.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/retina.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/jquery.cookies.js"></script>
<script src="<?= $assets ?>js/templatejs/js/flot/jquery.flot.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/flot/jquery.flot.resize.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/flot/jquery.flot.spline.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/morris.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/raphael-2.1.0.min.js"></script>
<script src="<?= $assets ?>js/templatejs/js/custom.js"></script>
<!--
<script src="<?= $assets ?>js/templatejs/js/dashboard.js"></script>
-->


<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>

<!--<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script> -->

<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
<?= ($m == 'purchases' && ($v == 'add' || $v == 'edit' || $v == 'purchase_by_csv')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/transfers.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/quotes.js"></script>' : ''; ?>
<?= ($m == 'products' && ($v == 'add_adjustment' || $v == 'edit_adjustment')) ? '<script type="text/javascript" src="' . $assets . 'js/adjustments.js"></script>' : ''; ?>

<?php 
		if($m.'_'.$v == 'sales_deliveries'){
			echo '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>';
		}
?>

<script type="text/javascript" charset="UTF-8">var oTable = '', r_u_sure = "<?=lang('r_u_sure')?>";
    <?=$s2_file_date?>
    $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
    $(window).load(function () {

		if('<?=$m?>_<?=$v?>' == 'sales_deliveries' || '<?=$m?>_<?=$v?>' == 'reports_payments' || '<?=$m?>_<?=$v?>' == 'system_settings_warehouses'){
			$('.mm_<?=$m?>_<?=$v?>').addClass('active');
		}else{
			$('.mm_<?=$m?>').addClass('active');
			$('.mm_<?=$m?>').find("ul").first().slideToggle();
			$('#<?=$m?>_<?=$v?>').addClass('active');
			$('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
		}
    });
</script>
</body>
</html>