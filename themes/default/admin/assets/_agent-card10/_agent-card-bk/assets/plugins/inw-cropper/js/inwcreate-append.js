  /*  ====== setup and fonfig model crop image  ====== 
    Deverlop by inwcreate.com @2017
  */ 
     
 function __addPopUpUpload(){
      $("#myModalImg").html(
             '<div class="modal-dialog">'
                 +'<div class="modal-content"> '
                   + '<div class="modal-header">'
                     + '<button type="button" class="close" data-dismiss="modal">&times;</button>'
                     + '<h4 class="modal-title"><i class="fa fa-cloud-upload"></i> UPLOAD IMAGES SYSTEM V.16.0.2</h4>'
                   + '</div>'

                   +'<div class="clearfix modal-body">'
                       +'<div class="w100p">'
                         +'<div class="upload-div">'
                          + '<div class="crop-btn-action po">' 
                               +'<form class="avatar-form" id="avatarForm" action="img-crop-v16.0.2.php" enctype="multipart/form-data" method="post">'
                                     +'<div class="crop-btn-style1">'
                                        +'<p><i class="fa fa-info-circle"></i> Max Image Dimension <span class="model-info-txt"></span> Pixels.</p>'
                                     +'</div>'
                                     +'<div class="crop-btn-style3">'
                                        +'<button type="button" class="btn-close" id="btn-close-window" data-dismiss="modal"><i class="fa fa-remove"></i><span>Close</span></button>'
                                     +'</div>'
                                     +'<div class="crop-btn-style3">'
                                        +'<button type="button" class="btn-clear" id="btn-clear"><i class="fa fa-refresh"></i><span>Reset</span></button>'
                                     +'</div>'
                                     +'<div class="crop-btn-style3">'
                                        +'<button type="button" class="btn buton-disable btn-info" id="saveCropImg" disabled="disabled"><i class="fa fa-picture-o"></i><span>Confirm upload</span></button>'
                                     +'</div>'
                                     +'<div class="crop-btn-style2">'
                                        +'<button class="btn-addfile" id="btn-addfile" type="button"><i class="fa fa-plus"></i><span>Add</span></button>'
                                     +'</div>'
                                    +'<div class="clearfix"></div>'
                                    +'<div class="zone-img-crop clearfix">'
                                       +'<div class="col-md-8">'
                                          +'<div class="crop-img">'
                                              +'<img src="http://topagent.smith.in.th/themes/default/admin/assets/agent-card/assets/images/icon-add-image.png" id="imgViewTmp" >'
                                          +'</div>'
                                       +'</div>'
                                       +'<div class="col-md-4 add-border">'
                                          +'<div class="previewImg">'
                                              +'<img src="http://topagent.smith.in.th/themes/default/admin/assets/agent-card/assets/images/none-img.png" id="imgViewTmp" >'
                                          +'</div>'
                                          +'<div class="img-wait-process clearfix">' // <!-- process load here -->
                                          +'</div>'
                                       +'</div>'
                                     +'<div class="clearfix"></div>'
                                   +'</div>' // <!-- /.zone-img-crop -->
                                   +'<div class="data-view">'
                                      +'<div class="col-md-8">'
                                         +'<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">'
                                           +'<p>Width : (pixels)</p>'
                                           +'<input type="text" name="dataWidth" id="dataWidth" readonly disabled />'
                                         +'</div>'
                                         +'<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">'
                                           +'<p>Height : (pixels)</p>'
                                           +'<input type="text" name="dataHeight" id="dataHeight" readonly disabled />'
                                         +'</div>'
                                           + '<input type="hidden" id="imgDataFix" name="imgDataFix" value="1" readonly>'
										   +'<input type="hidden" id="imgMaxWidth" name="imgMaxWidth" value="" readonly>'
                                           +'<input type="hidden" id="imgMaxHeight" name="imgMaxHeight" value="" readonly>'
                                           +'<input type="hidden" id="imgDataViewReturn" name="imgDataViewReturn" value="" readonly>'
                                           +'<input type="hidden" class="avatar-data" name="avatar_data" readonly>'
                                           +'<input type="hidden" class="avatar-src" name="avatar_src" readonly>'
                                           +'<input class="avatar-input" id="inputImage_1" name="avatar_file" type="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">'
                                         +'<div class="errMsgInfo">' // <!-- msg error here -->
                                         +'</div>'
                                     +'</div>'
                                     +'<div class="col-md-4">&nbsp;</div>'
                                  +'</div>' // <!-- /.data-view -->

                             +'</form>'

                           +'<div class="clear"></div>'
                        +'</div>'  // <!-- /.crop-btn-action  -->


                         +'</div>' // <!-- /.upload-div -->
                       +'</div>' // <!-- /.w100p -->
                   +'</div>' // <!-- /.modal-body -->
                   +'<div class="clearfix modal-footer">'
                     +'<p>© All Rights Reserved - jQuery image cropping plugin deverlop by inwcreate.com</p>'
                   +'</div>' // <!-- /.modal-footer -->

                 +'</div>' // <!-- /.modal-content -->
             + '</div>'); // <!-- /.modal-dialog -->
     }