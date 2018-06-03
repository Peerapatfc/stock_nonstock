<!DOCTYPE html>
<html>
<head>
  <title>บัตรตัวแทน</title>
  <meta name="viewport" content="width=600">
  <meta name="format-detection" content="telephone=no">
  <link href="<?php echo base_url('li_card/assets/plugins/bootstrap/css/bootstrap.min.css?v=3')?>" rel="stylesheet">
  <link href="<?php echo base_url('li_card/css/style.css');?>" rel="stylesheet" type="text/css"/>
  
  <link href="<?php echo base_url('li_card/css/cms-process.css')?>" rel="stylesheet" type="text/css"/>
  <!-- use inw create copper -->

  <script src="<?php echo base_url('li_card/js/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('li_card/js/dom.js')?>"></script>
  <script src="<?php echo base_url('li_card/js/clipboard.min.js')?>"></script>

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
  .btn-success:hover{
    border-color: #1CAF9A !important;
  }
  .main-page{
    padding: 0px !important;
  }
  button#black2 {
    margin-top: 5px;
  }
  .main-page {
    border: 0px solid #D0D0D0;

  }
  .panel {
    margin-top: -40px;
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
        margin-bottom: 5px;
  }
  button#black2 {
    margin-top: 5px;
  }
  .panel {
    margin-top: -40px;
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
      margin-bottom: 5px;
}
button#black2 {
  margin-top: 5px;
}
.panel {
  margin-top: -40px;
}
input#foo {
    height: 42px;
    font-size: 25px;
}
}
@media only screen and (max-width: 991px) {
  .col-sm-6 {
    width: 100%;
  }
  button.btn {
    font-size: 20px;
    font-weight: 400;
        margin-bottom: 5px;
  }
  .panel {
    margin-top: -40px;
  }
}

a {
  text-decoration: none !important;
}

</style>


<?php
$getTime = substr(date('YmdHis', time()), 5); ?>

<span id="boy">    
  <div class="clearfix p40t"></div>

  <div class="main-page">   
   <div id="html-content-holder">

    <div id="container" style="padding-top: 0px !important; margin-top: 0px !important;">
     <!--   <?php echo $_SESSION['instragram'] ?>  -->
     <!-- Header section-->
     <header class="header">
      <div class="row">
         <div class="col-xs-8 col-sm-8 col-lg-8">
       <img class="logo" title="" alt="" src="<?php echo base_url('li_card/assets/images/type/agent_logo.png');?>" />
     </div>
     </div>
   </header>

   <div class="main row">
    <div class="col-xs-6 col-sm-6 col-lg-6">
      <div class="c-center">
        <div id="avatar">
         <img alt="" src="<?php echo base_url('assets/uploads/avatars/thumbs/'.$user->avatar);?>" class="avatar">
         </div>
       </div>
     </div>

     <div class="col-xs-6 col-sm-6 col-lg-6 contact">
      <img class="" title="" alt="" src="<?php echo base_url('li_card/images/bg_con.png');?>" />
    </div>
    



<!--    <div class="col-sm-7 no-padding">
    <div id="contact">
      <div class="user_user"><p class="input"><?php print  $user->username; ?></p></div>
      <div class="user_id"><p class="input"><?php print  $user->seller_id; ?></p></div>
      <div class="user_phone"><p class="input"><?php print  $companies[0]->phone; ?></p></div>
      <div class="user_line"><p class="input"><?php print  $companies[0]->line; ?></p></div>
      <div class="user_facebook"><p class="input"><?php print $companies[0]->facebook; ?></p></div>
      <div class="user_instagram"><p class="input"><?php print  $companies[0]->instragram; ?></p></div>
    </div>
  </div> -->
</div>

<!-- Footer Section -->
<footer class="footer">
    <div class="col-xs-5 col-sm-5 col-lg-5 no-padding"></div>
    <div class="col-xs-7 col-sm-7 col-lg-7">
      <div class="user_user"><!-- <p class="input"> -->NAME : <?php print  $user->first_name ?><!-- </p> --></div>
        <div class="user_id"><!-- <p class="input"> -->CODE : <?php print  $user->seller_id; ?><!-- </p> --></div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
      <div class="col-xs-3 col-sm-3 col-lg-3 user_phone"><p><?php print  $companies[0]->phone; ?></p></div>
      <div class="col-xs-3 col-sm-3 col-lg-3 user_line"><p><?php print  $companies[0]->line; ?></p></div>
      <div class="col-xs-3 col-sm-3 col-lg-3 user_facebook"><p><?php print  $companies[0]->facebook; ?></p></div>
      <div class="col-xs-3 col-sm-3 col-lg-3 user_instagram"><p><?php print  $companies[0]->instragram; ?></p></div>
    </div>
  
</footer>





</div> <!-- /#container -->

</div> <!-- /#html-content-holder-->
</div>  <!-- end -->
</span>

<br><br>
<div class="main-page">  


 <!-- <div class="p20t clearfix"></div>  -->
 <div class="hid" id="previewImage"></div>  <!-- /.previewImage preview here -->
 <!-- </div> -->  <!-- /.main-page --> 
</div>

<div align="center">
  <?php
    // echo  $_SESSION['id_user_'];
  if($user->qrcode_image !=""){?>
  <br><p style=" font-size: 26px;">QR Code สมัครตัวแทน</p><br>
  <img src="<?php echo base_url('assets/qr/'.$user->qrcode_image); ?>" alt="QRCode Image">

  <br>
  <div class="col-md-4 col-md-offset-4">
    <div class="input-group">

     <input  id="foo" type="text" value="<?php echo $data_link ;?>" class="form-control">
     <span class="input-group-btn">

      <button class="btn btn-default" data-clipboard-action="copy" data-clipboard-target="#foo"> 

        <!--   <img style="width: 100%" src="<?php echo base_url(); ?>assets/icon/clippy.svg.png" alt="Copy to clipboard"> -->
        <i class="fa fa-clipboard "> คัดลอกลิ้งค์</i>
      </button>
    </span>
  </div>
</div>


<script>
  var clipboard = new ClipboardJS('.btn');

  clipboard.on('success', function(e) {
    console.log(e);
  });

  clipboard.on('error', function(e) {
    console.log(e);
  });
</script>


<br>
<!--  <p>QR Code  <?php print  $user->username; ?></p> -->
<br>
<div class="col-sm-4 col-md-offset-4" >
  <?php if($this->session->userdata('user_id')!=""){ ?>
  <button class="btn btn btn-block btn-success btn-margin-top" id="black2" style="background-color: #1CAF9A;">กลับไปหน้าแรก</button>
  <?php } ?>
</div>
<br>
<?php } ?>



<br><br>


</div>
<script type="text/javascript">

  var getCanvas = '';
  var getTime = '<?php print $getTime; ?>';
  var node = document.getElementById('html-content-holder');
  domtoimage.toPng(node).then(function (dataUrl) {
    var img = new Image();
    img.src = dataUrl;
    getCanvas = dataUrl;
    $('#previewImage').append(img);
    $("#boy").hide();
  }).catch(function (error) {
    console.error('oops, something went wrong!', error);
  });

  $("#btn-Convert-Html2Image").on('click', function () {
    var imgageData = getCanvas;
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
      // console.log(newData);
      $("#btn-Convert-Html2Image").attr("download", "picture-" + getTime + ".png").attr("href", newData);
    }); 

  
  $("#black2").on("click",function(){
    window.location.href="<?= admin_url('users/profile/'.$user->id); ?>"
  });

</script>

<div id="jwProcess"></div>
<script src="<?php echo base_url('li_card/js/function.apps.js')?>"></script>
<script src="<?php echo base_url('li_card/assets/plugins/bootstrap/js/bootstrap.min.js?v=61')?>"></script>
</body>
</html>