  /*  ====== setup and fonfig model crop image  ====== 
    Deverlop by inwcreate.com @2017
  */ 
	  // ==== run this apps ==== // 
	   __addPopUpUpload();

	  $('#imgViewTmp').click(function(event) {
	    $('#btn-addfile').trigger('click'); 
	  });
  
	  function __destroyModel(){
		var $btnAdd = $('#btn-addfile'),
		  $imgFile = $('#imgFileUpload');
		 
		$('#avatarForm').get(0).reset();
		$imgFile.cropper('destroy');
		$imgFile.remove();
		$('.errMsgInfo p').remove();
		$('#imgViewTmp').css({'display':'inline-block'});  
		$('#saveCropImg').prop( "disabled", true); 
		$btnAdd.prop( "disabled", false);
		$btnAdd.removeClass('buton-disable');
	  }
  
	  $(function () {
		 // =====  reset model =====
		 $(document).on("hidden.bs.modal", "#myModalImg", function () {
		   __destroyModel(); 
		 });
	  });
   
	  $(".myBtnUploadImg").click(function(){
		  var thisImgRes = $(this).attr('data-img-res'),
			  thisWidth = $(this).attr('data-img-width'),
			  thisheight = $(this).attr('data-img-height');    
		  
		  $('#imgDataViewReturn').val(thisImgRes);
		  $('#imgMaxWidth').val( parseInt(thisWidth) );
		  $('#imgMaxHeight').val( parseInt(thisheight) );
		  $('.model-info-txt').html(thisWidth + "x" + thisheight);
		  $("#myModalImg").modal();
	  });
	  
	  $('#btn-addfile').on('click', function(e){  
		 $('#inputImage').trigger('click');
	  });
	  
	  $('#btn-clear').on('click', function(e){  
		 __destroyModel();
	  });
  
     $('#btn-addfile').on('click', function(e){  
		var figimgMaxWidth = $('#imgMaxWidth').val();
	    var figimgMaxHeight = $('#imgMaxHeight').val();
		__myFnCrop(figimgMaxWidth, figimgMaxHeight);
     });

     $('#saveCropImg').click(function(event) {    
        uploadImgToServer();
     });
      
      function uploadImgToServer(){
          
          var imgDataFix = $('#imgDataFix').val();
		  var url = $('#avatarForm').attr('action');
		  var urlUpload = linkUrl + url;
          var data = new FormData($('#avatarForm')[0]);  
          var imgDataViewReturn = $('#imgDataViewReturn').val();          
          var imgMaxWidth = $('#imgMaxWidth').val();
          var imgMaxHeight = $('#imgMaxHeight').val();
          var chkWidth = $('#dataWidth').val();
          var chkHeight = $('#dataHeight').val();
          
          /* ==== check site befor upload ==== */
		  $('.errMsgInfo p').remove();
		  if(imgDataFix == "1"){  // === fix site image
			  if(  parseInt(chkWidth) < imgMaxWidth || parseInt(chkHeight) < imgMaxHeight ){
				$('.errMsgInfo').html('<p>!!! Upload Image File Error : image size fail !!!<br><span>Your image size : '+ chkWidth +'x' + chkHeight +' pixels.</span></p>');
			   return false;
			  }
		  }

           $('.img-wait-process').html('<img src="http://127.0.0.1/new-agent-card/themes/default/admin/assets/agent-card/assets/images/ajax-loader.gif">');  
		   
           setTimeout(function(){  
              $.ajax(urlUpload, {
                type: 'post', 
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,

                beforeSend: function () {
                  // load data first  
                },
                success: function (dataRes) {
                    console.log(dataRes);
                    $('#'+imgDataViewReturn).attr('src', ''+ dataRes.result);
                    $('.img-wait-process img').remove();
                    $('.errMsgInfo p').remove();
                    $('#myModalImg').modal('hide');
                    __destroyModel();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                  console.log(textStatus)
                },
                complete: function () { 
                  console.log('upload file complete naja.')
                }
              });  
                
           }, 2000);
		   
      }  // end function uploadImgToServer
	  
	  
	  /*  ====== function crop ===== */
	   function __myFnCrop(paramW, paramH){  
			  $(".crop-img").append('<img id="imgFileUpload" src="">');
			  var console = window.console || { log: function () {} };
			  var URL = window.URL || window.webkitURL;
			  var $image = $('#imgFileUpload');
			  /*  ===  for view value ===
			  var $dataX = $('#dataX');
			  var $dataY = $('#dataY');
			  */
			  var $dataHeight = $('#dataHeight');
			  var $dataWidth = $('#dataWidth');	 
			  var figimgMaxWidth = parseInt(paramW);
			  var figimgMaxHeight = parseInt(paramH);
			    
			  /*
			  var $dataRotate = $('#dataRotate');
			  var $dataScaleX = $('#dataScaleX');
			  var $dataScaleY = $('#dataScaleY');
			  */
			 
			  var $previews = $('.previewImg');
			  var croppable = false;
			  var $dataX = $("#dataX"),
                  $dataY = $("#dataY"),
                  $dataHeight = $("#dataHeight"),
                  $dataWidth = $("#dataWidth");
			  
			  var options = {     			 
					autoCropArea: 0.5,
					preview: '.previewImg',
					data: {
					  width: figimgMaxWidth,
					  height: figimgMaxHeight
					},		
					ready: function (e) {
					  var $clone = $(this).clone().removeClass('cropper-hidden');
					  croppable = true;
					  $clone.css({
						display: 'block',
						width: '100%',
						minWidth: 0,
						minHeight: 0,
						maxWidth: 'none',
						maxHeight: 'none'
					  });
					  $previews.css({
						width: '100%',
						overflow: 'hidden'
					  }).html($clone);
					},
							
					crop: function (e) {
					  /* put value ===  for view value ===
					  $dataX.val(Math.round(e.x));
					  $dataY.val(Math.round(e.y));
					  */
					  $dataHeight.val(Math.round(e.height));
					  $dataWidth.val(Math.round(e.width));
					  /*
					  $dataRotate.val(e.rotate);
					  $dataScaleX.val(e.scaleX);
					  $dataScaleY.val(e.scaleY); 
					  */
					   var imageData = $(this).cropper('getImageData');
                       var previewAspectRatio = e.width / e.height;
					   var json = [
						'{"x":' + e.x,
						'"y":' + e.y,
						'"height":' + e.height,
						'"width":' + e.width,
						'"rotate":' + e.rotate + '}'
					   ].join();

					   $('.avatar-data').val(json);  // add to value data
					}					
				  }
				 
			  var originalImageURL = $image.attr('src');
			  var uploadedImageType = 'image/jpeg';
			  var uploadedImageURL;


			  // Tooltip
			  $('[data-toggle="tooltip"]').tooltip();


			  // Cropper
			  $image.on({
				ready: function (e) {
				   console.log(e.type);
				},
				cropstart: function (e) {
				   console.log(e.type, e.action);
				},
				cropmove: function (e) {
				   console.log(e.type, e.action);
				},
				cropend: function (e) {
				   console.log(e.type, e.action);
				},
				crop: function (e) {
				   console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
				},
				zoom: function (e) {
				   console.log(e.type, e.ratio);
				}
			  }).cropper(options);


			  // Buttons
			  if (!$.isFunction(document.createElement('canvas').getContext)) {
				$('button[data-method="getCroppedCanvas"]').prop('disabled', true);
			  }

			  if (typeof document.createElement('cropper').style.transition === 'undefined') {
				$('button[data-method="rotate"]').prop('disabled', true);
				$('button[data-method="scale"]').prop('disabled', true);
			  }


			  // Options
			  $('.docs-toggles').on('change', 'input', function () {
				var $this = $(this);
				var name = $this.attr('name');
				var type = $this.prop('type');
				var cropBoxData;
				var canvasData;

				if (!$image.data('cropper')) {
				  return;
				}

				if (type === 'checkbox') {
				  options[name] = $this.prop('checked');
				  cropBoxData = $image.cropper('getCropBoxData');
				  canvasData = $image.cropper('getCanvasData');

				  options.ready = function () {
					$image.cropper('setCropBoxData', cropBoxData);
					$image.cropper('setCanvasData', canvasData);
				  };
				} else if (type === 'radio') {
				  options[name] = $this.val();
				}

				$image.cropper('destroy').cropper(options);
			  });


			  // Methods
			  $('.docs-buttons').on('click', '[data-method]', function () {
				var $this = $(this);
				var data = $this.data();
				var $target;
				var result;

				if ($this.prop('disabled') || $this.hasClass('disabled')) {
				  return;
				}

				if ($image.data('cropper') && data.method) {
				  data = $.extend({}, data); // Clone a new one

				  if (typeof data.target !== 'undefined') {
					$target = $(data.target);

					if (typeof data.option === 'undefined') {
					  try {
						data.option = JSON.parse($target.val());
					  } catch (e) {
						console.log(e.message);
					  }
					}
				  }

				  switch (data.method) {
					case 'rotate':
					  $image.cropper('clear');
					  break;

					case 'getCroppedCanvas':
					  if (uploadedImageType === 'image/jpeg') {
						if (!data.option) {
						  data.option = {};
						}

						data.option.fillColor = '#fff';
					  }

					  break;
				  }

				  result = $image.cropper(data.method, data.option, data.secondOption);

				  switch (data.method) {
					case 'rotate':
					  $image.cropper('crop');
					  break;

					case 'scaleX':
					case 'scaleY':
					  $(this).data('option', -data.option);
					  break;

					case 'getCroppedCanvas':
					  if (result) {
						// Bootstrap's Modal
						$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

						if (!$download.hasClass('disabled')) {
						  $download.attr('href', result.toDataURL(uploadedImageType));
						}
					  }

					  break;

					case 'destroy':
					  if (uploadedImageURL) {
						URL.revokeObjectURL(uploadedImageURL);
						uploadedImageURL = '';
						$image.attr('src', originalImageURL);
					  }

					  break;
				  }

				  if ($.isPlainObject(result) && $target) {
					try {
					  $target.val(JSON.stringify(result));
					} catch (e) {
					  console.log(e.message);
					}
				  }

				}
			  });


			  // Keyboard
			  $(document.body).on('keydown', function (e) {

				if (!$image.data('cropper') || this.scrollTop > 300) {
				  return;
				}

				switch (e.which) {
				  case 37:
					e.preventDefault();
					$image.cropper('move', -1, 0);
					break;

				  case 38:
					e.preventDefault();
					$image.cropper('move', 0, -1);
					break;

				  case 39:
					e.preventDefault();
					$image.cropper('move', 1, 0);
					break;

				  case 40:
					e.preventDefault();
					$image.cropper('move', 0, 1);
					break;
				}

			  });


			  // Import image
			  var $inputImage = $('#inputImage');
			  if (URL) {
				 $inputImage.change(function () {
				 $('#imgViewTmp').css({'display':'none'});
				  var files = this.files;
				  var file;
				  
				  if(files){ // disable button add file
				     $('#btn-addfile').addClass('buton-disable');
					 $('#btn-addfile').prop( "disabled", true);
					 
					 $('#saveCropImg').prop( "disabled", false);
    				 $('#saveCropImg').removeClass('buton-disable');
				   }
				  else{ 
				    $('#btn-addfile').removeClass('buton-disable');
				  }

				  if (!$image.data('cropper')) {
					return;
				  }

				  if (files && files.length) {
					file = files[0];

					if (/^image\/\w+$/.test(file.type)) {
					  uploadedImageType = file.type;
					  
					  if (uploadedImageURL) {
						// for replace image file
						 URL.revokeObjectURL(uploadedImageURL);
					  }
						
					   // for new image file
					   uploadedImageURL = URL.createObjectURL(file);
					   $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
					  // $inputImage.val('');
					} else {
					  window.alert('Please choose an image file.');
					}
				  }
				});
			  } else {
				$inputImage.prop('disabled', true).parent().addClass('disabled');
			 }
		  
	  }	  // end function
	  
	  
	   // if !hasClass myBtnUploadImg will remove element  
	    $(function () {
		   if( $('button').hasClass("myBtnUploadImg") ){
			console.log('see plugin'); 
			
		   }
		   else{
			console.log('not see plugin');   
			$('#myModalImg').empty();  
		   }
	   });
	  // ====== end check ======= 
