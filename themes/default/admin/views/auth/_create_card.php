﻿
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>สร้างบัตรตัวแทน</title>
 <meta name="viewport" content="width=600">
 <link href="<?php echo base_url('li_card/assets/plugins/bootstrap/css/bootstrap.min.css?v=3')?>" rel="stylesheet">
 <link href="<?php echo base_url('li_card/css/style.css');?>" rel="stylesheet" type="text/css"/>
 <link href="<?php echo base_url('li_card/css/cms-process.css')?>" rel="stylesheet" type="text/css"/>
 <!-- use inw create copper -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <script src="<?php echo base_url('li_card/js/jquery.min.js')?>"></script>
 <script src="<?php echo base_url('li_card/js/dom.js')?>"></script>


</head>
<body>
    <style type="text/css">
  .panel-heading {
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .btn-success{
    border-color: #ffffff;
  }
  .main-page{
    padding: 0px !important;
  }
  button#black2 {
    margin-top: -1px;
  }
  .main-page {
    border: 0px solid #D0D0D0;
   
}
  /*mobie*/
  @media only screen and (max-width: 600px) {
    .c-center{
     text-align: left;
     margin-left: 10px;
   }

   #contact > div {
    margin-left: 250px;
  }
  div#contact{
    margin-top:-170px
  }
  .detail-profile li{
    font-weight: bold;
    margin-left: 250px; 
  }
  button.btn {
    font-size: 20px;
    font-weight: 400;
  }
  button#black2 {
    margin-top: -3px;
  }
}
@media only screen and (max-width: 766px) {
        .c-center{
         text-align: left;
         margin-left: 10px;
       }

       #contact > div {
        margin-left: 250px;
      }
      div#contact{
        margin-top:-170px
      }
      .detail-profile li{
        font-weight: bold;
        margin-left: 250px; 
      }
      button.btn {
        font-size: 20px;
        font-weight: 400;
      }
      button#black2 {
        margin-top: -3px;
      }
}
@media only screen and (max-width: 991px) {
    .col-sm-6 {
    width: 100%;
}
  }

a {
  text-decoration: none !important;
}

</style>


<!-- ===== use library ===== -->

<div class="clearfix"></div>


<script type="text/javascript">
 $(window).load(function() {
  $('#imgDataFix').val(0);
});
</script>


<?php

$getTime = substr(date('YmdHis', time()), 5); ?>


<div class="clearfix p40t"></div>
<div class="panel panel-default">
  <div class="col-xm-6">
    <div class="panel-heading" align="center" style="font-size: 30px">ตัวอย่างบัตรตัวแทน<!-- Example cards --></div>
  </div>
</div>
<div class="main-page" id="boy">   
 <div id="html-content-holder">

  <div id="container" style="padding-top: 0px !important; margin-top: 0px !important;">
   <!--   <?php echo $_SESSION['instragram'] ?>  -->
   <!-- Header section-->
   <header class="header">
    <div class="row">
      <div class="col-sm-12">
       <img class="logo" title="" alt="" src="<?php echo base_url('li_card/assets/images/type/agent_logo.png');?>" />
     </div>
   </div>
 </header>

 <div class="main row">
  <div class="col-sm-5">
    <div class="c-center">
      <div id="avatar">
       <img alt="" src="<?php echo base_url('assets/uploads/avatars/thumbs/'.$avatar);?>" class="avatar">
     </div>
   </div>
 </div>

 <div class="col-sm-7 no-padding">
  <div id="contact">
    <div class="user_user"><p class="input"><?php print  $username; ?></p></div>
    <div class="user_id"><p class="input"><?php print  $seller_id; ?></p></div>
    <div class="user_phone"><p class="input"><?php print  $phone; ?></p></div>
    <div class="user_line"><p class="input"><?php print  $line; ?></p></div>
    <div class="user_facebook"><p class="input"><?php print  $facebook; ?></p></div>
    <div class="user_instagram"><p class="input"><?php print  $instragram; ?></p></div>
  </div>
</div>
</div>

<!-- Footer Section -->
<footer class="footer">
  <div class="row">
    <div class="col-sm-5"></div>

    <div class="col-sm-7 no-padding">
      <ul class="detail-profile">
        <li><strong>WD</strong> : </li>
        <li><strong>DEALER</strong> : </li>
        <li><strong>TEAM</strong> : </li>
      </ul>
    </div>
  </div>
</footer>



</div> <!-- /#container -->

</div> <!-- /#html-content-holder-->
</div>  

<h3 id="create"> <!-- ซ่อน -->
  <div class="col-sm-6 col-md-offset-3">
    <button class="btn btn btn-block btn-success btn-margin-top"  style="background-color: #1CAF9A;" id="show"><i class=" fa fa-drivers-license"></i> สร้างรูปภาพบัตรตัวแทน</button>

    <button class="btn btn btn-block btn-success btn-margin-top" id="black2" style="background-color: #1CAF9A;"><i class="fa fa-undo"></i> กลับไปหน้าแรก</button>
  

</h3>
<br/>
<div class="p20t clearfix"></div> 
<div align="center">
<div class="hid" id="previewImage"></div>  <!-- /.previewImage preview here -->
</div>
<span id="hid_div">
 <div class="col-sm-6 col-md-offset-3">
   <a id="btn-Convert-Html2Image" href="javascript:;"><button class="btn btn btn-block btn-success btn-margin-top" id="btn-save-data" style="background-color: #1CAF9A;"><i class="fa fa-download"></i> ดาวโหลดภาพ</button></a>
 </div>
 <div class="col-sm-6 col-md-offset-3">
   <button class="btn btn btn-block btn-success btn-margin-top" id="black" style="background-color: #1CAF9A;">กลับไปหน้าแรก</button>
 </div>
</span>
</div>
<br/>
<div class="p20t clearfix"></div>
<!-- /.main-page --> 

<div class="p40b clearfix"></div>
<script type="text/javascript">
  var getCanvas = '';
  var getTime = '<?php print $getTime; ?>';
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
    var imgageData = getCanvas;
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");


     // console.log(newData);
     $("#btn-Convert-Html2Image").attr("download", "picture-" + getTime + ".png").attr("href", newData);
   }); 

  $("#black").on("click",function(){
    window.location.href="<?= admin_url('users/profile/'.$id); ?>"
  });
  $("#black2").on("click",function(){
    window.location.href="<?= admin_url('users/profile/'.$id); ?>"
  });

  $(document).ready(function(){

   $(".hid").hide();
   $("#hid_div").hide();

   $("#show").click(function(){
     $(".hid").show();
     $("#hid_div").show();
     $("#create").hide();
     $("#boy").hide();


   });
 });

</script>

<div id="jwProcess"></div>
<script src="<?php echo base_url('li_card/js/function.apps.js')?>"></script>
<script src="<?php echo base_url('li_card/assets/plugins/bootstrap/js/bootstrap.min.js?v=61')?>"></script>
</body>
</html>