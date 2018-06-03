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
            <div class="alert alert-success tracking-success" style="display: none;"></div>
            <div class="alert alert-danger tracking-fail" style="display: none;"></div>
            <div class="row">
                <div class="col-sm-6">
                    <strong><?= lang('sale_reference_no') ?></strong>
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_input('tracking_order', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('tracking_order') . '" id="tracking_order"'); ?>    
                            <div class="input-group-btn">
                                <button class="btn btn-danger" onclick="return false;"><span class="fa fa-barcode"></span></button>
                            </div>
                        </div>                        
                    </div>
                    <div class="alert alert-success" id="tracking-order-detail-box" style="display: none;">
                        <h2>
                            <p><strong><?= lang('sale_reference_no') ?> : </strong> <span id="tracking-order-no"></span></p>
                            <p><strong><?= lang('ship_to') ?> : </strong> <span id="tracking-ship-to"></span></p>
                        </h2>
                    </div>
                </div>
                <div class="col-sm-6" style="border-left:1px solid #eee;">
                    <strong><?= lang('add_tracking') ?></strong>
                    <div class="form-group">
                        <div class="input-group">
                            <?php echo form_input('tracking_number', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('tracking_number') . '" id="tracking_number"'); ?>
                            <div class="input-group-btn">
                                <button class="btn btn-danger" onclick="return false;"><span class="fa fa-truck"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success" id="tracking-detail-box" style="display: none;">
                        <h2 style="padding:7px 0 7px;font-size:2em;" class="text-center">
                            <i class="fa fa-check-square-o"></i> <span id="tracking-number-text"></span>
                        </h2>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#tracking_order").keypress(function(e){
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {

                // var order_no = $("#tracking_order").val();
                // if(order_no != ''){
                //     search_order_no(order_no);    
                // }else{
                //     $(".tracking-fail").text("Please fill order before search!");
                //     $(".tracking-fail").show();
                //     setTimeout(function(){
                //         $(".tracking-fail").hide();
                //     },2000);
                // }
                $("#tracking-order-detail-box").hide();
                $("#tracking-detail-box").hide();

                $("#tracking_order").trigger("blur");

                // $("#tracking_number").focus();
                e.preventDefault();
                return false;
            }
        });

        $("#tracking_order").blur(function(e){
            var order_no = $("#tracking_order").val();
            if(order_no != ''){
                search_order_no(order_no);
            }else{
                $(".tracking-fail").text("Please fill order before search!");
                $(".tracking-fail").show();
                setTimeout(function(){
                    $(".tracking-fail").hide();
                },2000);
            }

            e.preventDefault();
            return false;
        });

        $("#tracking_number").keypress(function(e){
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {

                $("#tracking_number").trigger("blur");

                e.preventDefault();
                return false;
            }
        });

        $("#tracking_number").blur(function(e){
            add_tracking();
            // reset input
            $("#tracking_order").focus();
            $("#tracking_order").val("");
            $("#tracking_number").val("");

            e.preventDefault();
            return false;
        });

        function search_order_no(order_no) {
            $.ajax({
                type: "GET",
                url: "<?php echo site_url('/admin/sales/search_order_no'); ?>",
                data: {order_no: order_no},
                dataType: "json",
                success: function(data){
                    if(data.message == 'pass'){
                        console.log(data);
                        $(".tracking-success").text(data.description);
                        $(".tracking-success").show();

                        $("#tracking_number").focus();

                        $("#tracking-order-no").text(data.order_no);
                        $("#tracking-ship-to").text(data.ship_to);
                        $("#tracking-order-detail-box").show();

                        setTimeout(function(){
                            $(".tracking-success").hide();
                        },2000);
                    }else if(data.message == 'fail'){
                        $(".tracking-fail").text(data.description);
                        $(".tracking-fail").show();

                        $("#tracking_order").val("");
                        $("#tracking_order").focus();

                        setTimeout(function(){
                            $(".tracking-fail").hide();
                        },2000);
                    }
                }
            });
        }

        function add_tracking() {
            var order_no = $("#tracking_order").val();
            var tracking_no = $("#tracking_number").val();

            $.ajax({
                type: "GET",
                url: "<?php echo site_url('/admin/sales/add_tracking'); ?>",
                data: { order_no: order_no, tracking_no: tracking_no },
                dataType: "json",
                success: function(data){
                    if(data.message == 'pass'){
                        // show message
                        $(".tracking-success").text(data.description);
                        $(".tracking-success").show(); 

                        $("#tracking-number-text").text(tracking_no);
                        $("#tracking-detail-box").show();

                        setTimeout(function(){
                            $(".tracking-success").hide();
                        },2000);
                    }else if(data.message == 'fail'){
                        $(".tracking-fail").text(data.description);
                        $(".tracking-fail").show();

                        setTimeout(function(){
                            $(".tracking-fail").hide();
                        },2000);
                    }
                    // reset input
                    $("#tracking_order").focus();
                    $("#tracking_order").val("");
                    $("#tracking_number").val("");
                }
            });
        }
    });
</script>
<?php echo  $modal_js ?>