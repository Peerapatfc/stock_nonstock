<!DOCTYPE html>
<html lang="en">
<head>
<title>::: MEMBER AGENT CARD :::</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta http-equiv="content-type" content="text/html; charset=UTF8"> -->
    <?php 
/*
           * <meta name="viewport" content="width=device-width, initial-scale=1">
           */
    ?>
    <meta name="viewport" content="width=600">
<link href="http://member.99newone.com/themes/default/admin/assets/agent-card/css/bootstrap.min.css?v=1"
	rel="stylesheet">
<link href="http://member.99newone.com/themes/default/admin/assets/agent-card/css/style.css" rel="stylesheet" type="text/css" />
<link href="http://member.99newone.com/themes/default/admin/assets/agent-card/css/cms-process.css" rel="stylesheet" type="text/css" />
<!-- use inw create copper -->
<link href="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/plugins/inw-cropper/css/plugin-cropper.css"
	rel="stylesheet" type="text/css" />
<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/js/jquery.min.js"></script>
<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/js/html2canvas.js"></script>

 <script src="https://www.thesunamulet.com/agent-card/js/dom.js"></script>
 
 
<script type="text/javascript">  
      var linkUrl = 'http://member.99newone.com/themes/default/admin/assets/agent-card/';
    </script>
    <script type="text/javascript">  
      var linkUrl = 'http://member.99newone.com/themes/default/admin/assets/agent-card/';
      
    </script>
    <script>
// var params = location.href.split('?')[1].split('&');
// data = {};
// for (x in params)
//  {
// data[params[x].split('=')[0]] = params[x].split('=')[1];
//  }
//  alert(data.id);
</script>

    <style>
    @font-face {
  font-family: 'ThaiSansNeueLight';
  src: url('http://member.99newone.com/themes/default/admin/assets/agent-card/css/webfont/ThaiSansNeueLight.eot?#iefix') format('embedded-opentype'), 
 url('http://member.99newone.com/themes/default/admin/assets/agent-card/css/webfont/ThaiSansNeueLight.woff') format('woff'),
 url('http://member.99newone.com/themes/default/admin/assets/agent-card/css/webfont/ThaiSansNeueLight.ttf')  format('truetype'), 
url('http://member.99newone.com/themes/default/admin/assets/agent-card/css/webfont/ThaiSansNeueLight.svg#ThaiSansNeueLight') format('svg');
  font-weight: normal;
  font-style: normal;
}
   /* body { font-family: 'ThaiSansNeueLight' !important; }*/
    
    </style>
    

    
     <style>
      .text-block {
        width: 500px;
        font-family: serif;
		float:left;
		text-align:justify;
      }

    </style>
    
</head>
<body>
   <?php require_once("config/db-connect.php"); ?>
   <?php
// === setup image === //
$maxFileSize = 900;
$maxWidth = 3000;
$maxHeight = 2000;

// === get agent type === //
$conn = new connectMySqlDB();

if(($_POST)){
    // print_r($_POST);
//     $imgData=$_POST['imgDataView2']!=''?$_POST['imgDataView2']:
    $q_user = "update sma_users " . "set seller_id='" . $_POST['yourID'] . "'" . "
            ,avatar='" . substr($_POST['imgDataView2'], strrpos($_POST['imgDataView2'], '/') + 1) . "'"  // .$imagePath
    
        .",first_name ='".$_POST['firstName']. "'" 
         .",last_name ='".$_POST['lastName']. "'" 
    .",phone ='" . $_POST['phoneNumber'] . "'" .
    // /.",lineId =".$_POST['line']
    " where id= " . $_POST['id'];
    // echo $q_user;
    $res_u = $conn->query($q_user);
    
    
    $q_company = "update sma_companies " . "set seller_id='" . $_POST['yourID'] . "'" . ",line='" . $_POST['lineID'] . "'" .
        // .",first_name =".$_POST['first_name']
    // .",last_name =".$_POST['last_name']
    ",facebook ='" . $_POST['facebook'] . "'" . ",instragram ='" . $_POST['ig'] . "'" . " where email= '" .$_POST['email']  . "'";
    
    $res_c = $conn->query($q_company);
}




$strQuery = "SELECT typeID, nameType FROM agentType ORDER BY typeID ASC ";
$result = $conn->query($strQuery)->findAll();

// echo "id :::::::::::: ".$_GET['id'];
//get pic
// $query="select * from sma_users where id=".$_GET['id'];
 $query="select u.*,c.*,u.phone as u_phone,u.seller_id as u_sel_id,u.parent_id as parent_id
 from sma_users u 
 left join sma_companies c on u.email=c.email 
 where c.group_name='user' and u.id=".(($_GET['id']!='')?$_GET['id']:$_POST['id']);
$user = $conn->query($query)->findOne();
// echo $user->avatar;
if(($user->avatar)!=null||$user->avatar!=''){
    $imagePath="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/images/imgup/tmp/".$user->avatar;
}else{
    $imagePath = 'http://member.99newone.com/themes/default/admin/assets/agent-card/assets/images/selimg400x300.jpg';
}


$getparent = "select * from sma_users where id=".$user->parent_id;
$parent = $conn->query($getparent)->findOne();


// echo " !!!!!!!!!!!!!! ".$user->first_name."  ".$user->last_name;

// echo var_dump($user);
$conn->closeDB();
?>
   <div class="main-page">

		<div class="page-header-inw">
			<h3 class="">แบบฟอร์มกรอกข้อมูลตัวแทน</h3>
		</div>
		<div class="clearfix"></div>

		<div class="ui secondary attached segment page-height">

			<form role="form" name="dataFrm" id="dataFrm" class="myFrm" action=""
				method="post" enctype="multipart/form-data" onsubmit="return false;">
				<input type="hidden" name="id" value="<?php echo (($_GET['id']!='')?$_GET['id']:$_POST['id']);?>"/>
				<input type="hidden" name="email" value="<?php echo $user->email; ?>"/>
				<div class="form-group clearfix p15t">
					<div class="row">
						<div class="col-sm-6 p5t ">
							<label>ประเภทสมาชิก<span class="request">*</span></label> <select
								class="form-control wide" id="typeID" name="typeID">
								<option value="">--- กรุณาเลือก ---</option>
                         <?php
                        foreach ($result as $key => $reccord) {
                            ?>
                          <option
									value="<?php print $reccord->typeID; ?>"><?php print $reccord->nameType; ?></option>
                          <?php
                        } // === end foreach ===
                        ?>
                        </select>
						
							<div class="p20t clearfix">
								<label>ID<span class="request">*</span></label> <input
									name="yourID" id="yourID" class="form-control" placeholder="" value="<?php echo $user->u_sel_id;?>"
									maxlength="80" type="text">
							</div>
						</div>


						<div class="col-sm-6 p5t">

							<div class="col-sm-10 img-mode">

								<img src="<?php echo $imagePath; ?>" id="imgDataView" 
									alt="img-diaplay"/>
								<input type="hidden" name="imgDataView2" id="imgDataView2" value="<?php echo $imagePath;?>" />
								<div class="crop-upload-image">
									<div class="crop-txt-show" style="padding-top: 0px;">
										<p class="detail-txt p5t p5b" style="font-size: 11px;">
                                        ขนาดห้ามเกิน : <?php print $maxWidth; ?> X <?php print $maxHeight; ?> px.
                                      </p>
										<div class="crop-btn-style3" style="padding-left: 0px;">
											<button class="btn-addfile myBtnUploadImg" type="button"
												data-img-res="imgDataView"
												data-img-width="<?php print $maxWidth; ?>"
												data-img-height="<?php print $maxHeight; ?>">
												<i class="fa fa-cloud-upload"></i><span>อัพโหลดรูป</span>
											</button>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<!-- /.crop-upload-image -->

							</div>
							<!-- /.img-mode -->

						</div>
					</div>
				</div>

				<div class="form-group p10t clearfix">
					<div class="row">
						<div class="col-sm-6">
							<label>ชื่อ<span class="request">*</span></label>
							 <input  
								name="firstName" id="firstName" class="form-control"   value="<?php echo 
								$user->first_name;?>"
								placeholder="" maxlength="40" type="text">
								<label>นามสกุล<span class="request">*</span></label>
								 <input 
								name="lastName" id="lastName" class="form-control"   value="<?php echo 
								$user->last_name;?>"
								placeholder="" maxlength="40" type="text">
						</div>
						<div class="col-sm-6">
							<label>เบอร์โทร<span class="request">*</span></label> <input  value ="<?php echo $user->u_phone;?>"
								name="phoneNumber" id="phoneNumber" class="form-control"
								placeholder="" maxlength="85" type="text">
						</div>
					</div>
				</div>

				<div class="form-group clearfix">
					<div class="row">
						<div class="col-sm-6">
							<label>Line<span class="request">*</span></label> <input
								name="lineID" id="lineID" class="form-control" placeholder=""  value ="<?php echo $user->line;?>"
								maxlength="80" type="text">
						</div>
						<div class="col-sm-6">
							<label>Facebook<span class="request">*</span></label> <input value ="<?php echo $user->facebook;?>"
								name="facebook" id="facebook" class="form-control"
								placeholder="" maxlength="80" type="text">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label>IG</label> <input id="ig" class="form-control" name="ig"
								value ="<?php echo $user->instragram;?>" maxlength="80" type="text">
						</div>
						<div class="col-sm-6"></div>
					</div>
				</div>

				<div class="form-group clearfix">
					<div class="row">
<!--
						<div class="col-sm-6">
							<label>WD<span class="request">*</span></label> <input name="wd"  value="Example"
								id="wd" class="form-control" placeholder="" maxlength="80"
								type="text">
						</div>
						<div class="col-sm-6">
							<label>DEALER<span class="request">*</span></label> <input  value="Example"
								name="dealer" id="dealer" class="form-control" placeholder=""
								maxlength="80" type="text">
						</div>
-->
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label>TEAM<span class="request">*</span></label> <input  value="<?php echo $parent->first_name." ".$parent->last_name; ?>"
								id="team" class="form-control" name="team" value=""
								maxlength="80" type="text">
						</div>
						<div class="col-sm-6"></div>
					</div>
				</div>

				<div class="form-group p20t clearfix">
					<button class="btn btn-action" id="btn-save-data">บันทึกข้อมูล</button>
					<button class="btn btn-cancel" id="btn-clear-data">ยกเลิก</button>
					<input id="tmpFileImg" name="tmpFileImg" type="hidden" value="" />
				</div>

			</form>

		</div>

	</div>
	<!-- /.main-page -->

	<script type="text/javascript">
  // ====  save data ==== //
   function __saveData(){  
      
      $('#tmpFileImg').val( $('#imgDataView').attr('src') );
        if( $('#tmpFileImg').val() == 'assets/images/selimg400x300.jpg'  || $('#tmpFileImg').val() == '' ){
           alert('กรุณาอัพโหลดรูปภาพ');
           return false;
      }

      if( $('#typeID').val() == '' ){
        alert('กรุณาเลือกประเภทสมาชิก');
        return false;
      }
      
      if( $('#yourID').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#yourID').focus();
        return false;
      }
      
      if( $('#firstName').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#firstName').focus();
        return false;
      }

      if( $('#phoneNumber').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#phoneNumber').focus();
        return false;
      }

      if( $('#lineID').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#lineID').focus(); 
        return false;
      }

      if( $('#facebook').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#facebook').focus();
        return false;
      }

      if( $('#wd').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#wd').focus();
        return false;
      }

      if( $('#dealer').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#dealer').focus();
        return false;
      }


      if( $('#team').val() == '' ){
        alert('ค่าห้ามว่าง');
        $('#team').focus();
        return false;
      }
       
      document.forms['dataFrm'].method = 'post';
      document.forms['dataFrm'].action = '<?php echo $_SERVER['REQUEST_URI'];?>'//'index.php';
      document.forms['dataFrm'].target = '_self';
      document.forms['dataFrm'].submit(); 
   }

   $('#btn-clear-data').click(function() {
      window.open('index.php', '_self');
   });
</script>

	<!-- ===== use library ===== -->
	<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/plugins/inw-cropper/js/inwcreate-append.js"></script>
	<div class="clearfix"></div>
	<div id="myModalImg" class="modal fade" role="dialog"
		style="z-index: 99999;"></div>
	<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/plugins/inw-cropper/js/cropper.js"></script>
	<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/plugins/inw-cropper/js/inwcreate-cropimg-fn.js"></script>
	<script type="text/javascript">
 $(window).load(function() {
    $('#imgDataFix').val(0);
 });
</script>

	<!-- === if($_POST) === -->
<?php
if (! empty($_POST['typeID'])) {
    $typeID = addslashes($_POST['typeID']);
} else {
    $typeID = '';
}
if (! empty($_POST['yourID'])) {
    $yourID = addslashes($_POST['yourID']);
} else {
    $yourID = '';
}
if (! empty($_POST['firstName'])) {
    $firstName = addslashes($_POST['firstName']);
} else {
    $firstName = '';
}
if (! empty($_POST['lastName'])) {
    $lastName = addslashes($_POST['lastName']);
} else {
    $lastName = '';
}
$fullName=$firstName."  ".$lastName;
if (! empty($_POST['phoneNumber'])) {
    $phoneNumber = addslashes($_POST['phoneNumber']);
} else {
    $phoneNumber = '';
}
if (! empty($_POST['lineID'])) {
    $lineID = addslashes($_POST['lineID']);
} else {
    $lineID = '';
}
if (! empty($_POST['facebook'])) {
    $facebook = addslashes($_POST['facebook']);
} else {
    $facebook = '';
}
if (! empty($_POST['ig'])) {
    $ig = addslashes($_POST['ig']);
} else {
    $ig = '-';
}
if (! empty($_POST['wd'])) {
    $wd = addslashes($_POST['wd']);
} else {
    $wd = '';
}
if (! empty($_POST['dealer'])) {
    $dealer = addslashes($_POST['dealer']);
} else {
    $dealer = '';
}
if (! empty($_POST['team'])) {
    $team = addslashes($_POST['team']);
} else {
    $team = '';
}
if (! empty($_POST['tmpFileImg'])) {
    $tmpFileImg = addslashes($_POST['tmpFileImg']);
} else {
    $tmpFileImg = 'images/profile02.png';
}
$getTime = substr(date('YmdHis', time()), 5);

if ($_POST && $typeID != '') {
    $conn = new connectMySqlDB();
    $strQuery = "SELECT imgTypeUrl FROM agentType WHERE typeID = '$typeID' ";
    $row = $conn->query($strQuery)->findOne();
    $conn->closeDB();
    $typeAgenDisplay = $row->imgTypeUrl;
    ?> 
    
    
    <!--  <link href="http://member.99newone.com/themes/default/admin/assets/css/webfont/styles.css"> -->

    <div class="clearfix p40t"></div>
<div class="main-page">
		<div id="html-content-holder" class="text-block">

			<div id="container">
              
              <?php 
/*
           * === old design ===
           * <!-- Header section-->
           * <header class="header">
           * <div class="row">
           * <div class="col-sm-5 c-center"><img class="logo" title="" alt="" src="<?php print $typeAgenDisplay; ?>" /></div>
           * <div class="col-sm-7"></div>
           * </div>
           * </header>
           *
           * <div class="main row">
           * <div class="col-sm-5">
           * <div class="c-center">
           * <div id="avatar">
           * <img alt="" src="<?php print $tmpFileImg; ?>" class="avatar">
           * </div>
           * </div>
           * </div>
           *
           * <div class="col-sm-7">
           * <div id="contact">
           * <div class="user_id"><input type="text" value="<?php print $yourID; ?>" /></div>
           * <div class="user_phone"><input type="text" value="<?php print $phoneNumber; ?>" /></div>
           * <div class="user_line"><input type="text" value="<?php print $lineID; ?>" /></div>
           * <div class="user_facebook"><input type="text" value="<?php print $facebook; ?>" /></div>
           * <div class="user_instagram"><input type="text" value="<?php print $ig; ?>" /></div>
           * </div>
           * </div>
           * </div>
           *
           * <!-- Footer Section -->
           * <footer class="footer">
           * <div class="row">
           * <div class="col-sm-5"></div>
           *
           * <div class="col-sm-7">
           * <ul class="detail-profile">
           * <li><strong>WD</strong> : <?php print $wd; ?></li>
           * <li><strong>DEALER</strong> : <?php print $dealer; ?></li>
           * <li><strong>TEAM</strong> : <?php print $team; ?></li>
           * </ul>
           * </div>
           * </div>
           * </footer>
           */
    ?>


              <!-- Header section-->
				<header class="header">
					<div class="row">
						<div class="col-sm-12">
							<img class="logo" title="" alt=""
								src="<?php print $typeAgenDisplay; ?>" />
						</div>
					</div>
				</header>

				<div class="main row">
					<div class="col-sm-5">
						<div class="c-center">
							<div id="avatar">
								<img alt="" src="<?php print $tmpFileImg; ?>" class="avatar">
							</div>
						</div>
					</div>

					<div class="col-sm-7 no-padding">
						<div id="contact">
							<div class="user_user">
								<p class="cell value input" style="font-family: monospace;"><?php print $fullName; ?></p>
							</div>
							<div class="user_id">
								<p class="input"><?php print $yourID; ?></p>
							</div>
							<div class="user_phone">
								<p class="input"><?php print $phoneNumber; ?></p>
							</div>
							<div class="user_line">
								<p class="input"><?php print $lineID; ?></p>
							</div>
							<div class="user_facebook">
								<p class="input"><?php print $facebook; ?></p>
							</div>
							<div class="user_instagram">
								<p class="input"><?php print $ig; ?></p>
							</div>
						</div>
					</div>
				</div>

				<!-- Footer Section -->
				<footer class="footer">
					<div class="row">
						<div class="col-sm-5"></div>

						<div class="col-sm-7 no-padding">
							<ul class="detail-profile">
<!--
								<li><strong>WD</strong> : <?php print $wd; ?></li>
								<li><strong>DEALER</strong> : <?php print $dealer; ?></li>
-->								
<li><strong>TEAM</strong> : <?php print $team; ?></li>
							</ul>
						</div>
					</div>
				</footer>





			</div>
			<!-- /#container -->

		</div>
		<!-- /#html-content-holder-->
	</div>

	<div class="main-page">
		<a id="btn-Convert-Html2Image" href="javascript:;"><button
				class="btn btn-action" id="btn-save-data">ดาวโหลดภาพ และ บันทึก</button></a> <br />
				
				
					
		<div class="p20t clearfix"></div>
		<div id="previewImage" ></div>
		<!-- /.previewImage preview here -->
	</div>
	<!-- /.main-page -->

	<div class="p40b clearfix"></div>
	

   <form method="post" accept-charset="utf-8" name="form1">
            <input name="hidden_data" id='hidden_data' type="hidden"/>
            <input name="id" id='id' type="hidden" value="<?php echo $_GET['id'];?>" />
        </form>
	<script type="text/javascript">
           /*=== ref by : http://html2canvas.hertzen.com/faq/  */
//            var element = $("#html-content-holder");
//            var getCanvas = '';
         //  var getTime = '<?php //print $getTime; ?>';

//             function createImg(){
//                html2canvas(element, {
//                  onrendered: function (canvas) {
//                         $("#previewImage").append(canvas);
//                          getCanvas = canvas.toDataURL("image/png");
//                      }
                     
//                  });
//             }

var getCanvas = '';
    var getTime = '426092854';
    var node = document.getElementById('html-content-holder');
    domtoimage.toPng(node).then(function (dataUrl) {
        var img = new Image();
        img.src = dataUrl;
        getCanvas = dataUrl;
        $('#previewImage').append(img);
    }).catch(function (error) {
        console.error('oops, something went wrong!', error);
    });
             
            $("#btn-Convert-Html2Image").on('click', function () {



            	var urlUpload= 'http://member.99newone.com/themes/default/admin/assets/agent-card/upload.php';
            	 document.getElementById('hidden_data').value = getCanvas;
                 var data = new FormData(document.forms["form1"]);


                 var xhr = new XMLHttpRequest();
                 xhr.open('POST', urlUpload, true);
  
                 xhr.upload.onprogress = function(e) {
                     if (e.lengthComputable) {
                         var percentComplete = (e.loaded / e.total) * 100;
                         console.log(percentComplete + '% uploaded');
                         if(percentComplete==100){
                         alert('Succesfully uploaded');
                         }
//                          window.close();
                     }
                 };

                 xhr.addEventListener("load", transferComplete);
                 function transferComplete(evt) {
                	  console.log("The transfer is complete."+this.responseText);
                	  closeAndShowImg(this.responseText);
                	}
                 
  
                 xhr.onload = function() {
  
                 };
                 xhr.send(data);


                 var imgageData = getCanvas;
                 var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
                 $("#btn-Convert-Html2Image").attr("download", "picture-" + getTime + ".png").attr("href", newData);
           });  
          
//           createImg(); // === create img here ==== //
  <!-- </script>
  <script> -->
		//function call parent
		/* window.onunload = */
		 function closeAndShowImg(imgName) {
//             opener.somefunction(imgName); //or
            opener.document.getElementById('agent_card').src = 'http://member.99newone.com/themes/default/admin/assets/agent-card/assets/images/imgup/profile/'+ imgName;
            opener.document.getElementById('agent_warn').style.display = 'none'; 
            
			window.close();
         }

//   });
  </script>
  
  
  
<?php } // ==== End have post  ==== ?> 
<div id="jwProcess"></div>
<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/js/function.apps.js"></script>
<script src="http://member.99newone.com/themes/default/admin/assets/agent-card/assets/plugins/bootstrap/js/bootstrap.min.js?v=61"></script>


</body>
</html>