<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$v = "";
	if ($this->input->post('province')) {
		$v .= "&province=" . $this->input->post('province');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
?>

<script>
    $(document).ready(function () {
        oTable = $('#PROVData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('reports/getOrderProvinceReport/?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                //nRow.id = aData[9]; 
                return nRow;
            },
            "aoColumns": [
			null,
			null,
			{"mRender": currencyFormat}
			],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
            }
        }).fnSetFilteringDelay().dtFilter([
        ], "footer");
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>


<div class="box">
    <div class="box-header">
<style>
.pageheader .icon::before {
   content:"\e062";
   font-family: 'Glyphicons Halflings';
}
</style>
        <h2 class="blue"><i class="glyphicon glyphicon-map-marker"></i><?= lang('order_province'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date')." ".$this->input->post('province');
            }
            ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
		
		<?php /*
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>">
                        <i class="icon fa fa-file-excel-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                        <i class="icon fa fa-file-picture-o"></i>
                    </a>
                </li>
            </ul>
        </div>
		*/ ?>
    </div>
    <div class="box-content">
        <div class="row">


            <div class="col-lg-12">
                <p class="introtext"><?= lang('customize_report'); ?></p>
                <div id="form">
                    <?php echo admin_form_open("reports/orderProvince"); ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="province"><?= lang("province"); ?></label>
                                <?php
									$pv[""] = lang('select').' '.lang('province');
									foreach ($province as $prov) {
										$pv[$prov->name_in_thai] = $prov->name_in_thai;
									}
									echo form_dropdown('province', $pv, (isset($_POST['province']) ? $_POST['province'] : ""), 'class="form-control" id="province" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("province") . '"');
								?>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

				<div style="position:relative;">
				  <div  style="width:100px;margin-left:125px;top:10px;position:absolute;z-index: 999; background-color:#FFFFFF;padding: 5px;">
					<div class="col-lg-12 col-xs-12 col-md-12">
					  <div class="form-group row text-center" >
						  <lable class="control-label"><b>Option</b></lable>
					   </div>
					   <div class="form-group row">
						  <table align="center">
							<tr>
							  <td  style="font-size:10px;text-align:right">price : &nbsp;</td>
							  <td>
								<input type="checkbox" id="price">   
							  </td>
							</tr>
							<tr>
							   <td style="font-size:10px;text-align:right">show all : &nbsp;</td>
							   <td>
									<input type="checkbox" id="showAll">
							   </td>
							</tr>
						  </table>
					   </div>
					 </div>
				  </div>
				  <div class="col-lg-12 col-md-12 col-xs-12" style="height:600px" id="map"></div>
				</div>
			<div class="clearfix"></div>	
				
				
				
                <div class="table-responsive2">
                    <table id="PROVData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
                            <th style=""><?= lang("name"); ?></th>
                            <th style=""><?= lang("orders"); ?></th>
                            <th style=""><?= lang("total"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th><?= lang("name"); ?></th>
                            <th><?= lang("orders"); ?></th>
                            <th><?= lang("total"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=admin_url('reports/getOrderProvinceReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=admin_url('reports/getOrderProvinceReport/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>

<?php $jsonLat = json_encode($dataLat); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAK3RgqSLy1toc4lkh2JVFQ5ipuRB106vU"></script>
<script type="text/javascript">    
	//console.log($.parseJSON('<?php echo $jsonLat ?>'));
    //$(document).ready(function () {
		  var map; 
          var i; 
          var jsonLat = $.parseJSON('<?php echo $jsonLat ?>');
          var arrMarker = new Array();
          var isShowAll = true;
          var isPrice = false;
          function initMap(map, title, lat, lng){ 
            var marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lng),        
				map: map, 
				title : (title)  
            }); 
            var infowindow = new google.maps.InfoWindow(); 
            infowindow.setContent(title);         
            infowindow.open(map, marker); 
            marker.addListener('click', function() {
              infowindow.open(marker.get('map'), marker);
            });
            arrMarker.push(marker);
          } 
          function Map() { 
            map = new google.maps.Map(document.getElementById('map'), { 
                center: {lat: 12.847860, lng: 100.604274},
                zoom: 6
              }); 
            genMark(true);
          } 
		  
          function genMark(limit, showPrice){
            clearMark();
            count = 1;
            //for(var i in jsonLat){
			for (var i = 0; i < jsonLat.length; i++ ) {
				console.log(jsonLat);
              if(limit == true && count == 10) break;
                if(showPrice){
                  initMap(map, jsonLat[i].count_order + ' / ' + jsonLat[i].grand_total + ' ฿', jsonLat[i].province_lat, jsonLat[i].province_lng);
                }else{
                  initMap(map, jsonLat[i].count_order , jsonLat[i].province_lat, jsonLat[i].province_lng);
                }
              count++;
            }
          }
		  
          function clearMark(){
			for (var i = 0; i < arrMarker.length; i++ ) {
                arrMarker[i].setMap(null);
            }arrMarker = [];
          }

          $(document).ready(function(){
              Map();
			  $("#price").on("ifChanged", function(){
                 if($(this).prop('checked')){ 
                  isPrice = true;
                  genMark(isShowAll, isPrice);
                }else{ 
                  isPrice = false;
                  genMark(isShowAll, isPrice);
                }
              });
				$("#showAll").on("ifChanged", function(){
                if($(this).prop('checked')){ 
                  isShowAll = false;
                  genMark(isShowAll, isPrice);
                }else{ 
                  isShowAll = true;
                  genMark(isShowAll, isPrice);
                }
              });
          });
		  //google.maps.event.addDomListener(window, 'load', initMap);
    //});
</script>
<style>
#PROVData_wrapper .row:first-child {
    visibility: hidden;
}
#PROVData_wrapper th {
    width: calc(100%/3) !important;
}
</style>