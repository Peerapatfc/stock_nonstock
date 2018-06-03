<!DOCTYPE html>
<html>
<head>
  <title>บัตรตัวแทน</title>
  <meta name="viewport" content="width=600">
  <meta name="format-detection" content="telephone=no">
  <link href="<?php echo base_url('li_card/assets/plugins/bootstrap/css/bootstrap.min.css?v=3')?>" rel="stylesheet">
  <link href="<?php echo base_url('li_card/css/style.css');?>" rel="stylesheet" type="text/css"/>
  <!--   <link href="<?php echo base_url('li_card/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css"/> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="<?php echo base_url('li_card/css/cms-process.css')?>" rel="stylesheet" type="text/css"/>
  <!-- use inw create copper -->

  <script src="<?php echo base_url('li_card/js/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('li_card/js/dom.js')?>"></script>
  <script src="<?php echo base_url('li_card/js/clipboard.min.js')?>"></script>

</head>
<body>

  <style type="text/css">
  button.btn.btn-default {
    height: 34px;
}
  .btn-success{
    border-color: #ffffff;
  }
  .btn-success:hover{
    border-color: #1CAF9A !important;
  }
  button#black2 {
    margin-top: 5px;
    /*height: 33px;*/
}
.main-page {
     border: 0px solid #D0D0D0; 
     box-shadow: 0 0 0px #D0D0D0; 
    /*width: 580px;
    max-width: 100%;
    margin: 15px auto;
    background-color: #fff;
    background-size: 720px auto;
    padding: 15px !important;
    z-index: 97;*/
}
  @media only screen and (max-width: 600px) {
    input#foo {
      height: 42px;
      font-size: 25px;
    }
    button.btn {
      font-size: 20px;
      font-weight: 400;
    }
    button#black2 {
      height: 55px;
    }
  }
  @media only screen and (max-width: 766px) {
    input#foo {
      height: 42px;
      font-size: 25px;
    }
    button.btn {
      font-size: 20px;
      font-weight: 400;
    }
    button#black2 {
      height: 55px;
    }
  }
  @media only screen and (max-width: 991px) {
    .col-sm-4 {
      width: 100%;
      margin-top: -20px;
    }
    button#black2 {
      height: 55px;
    }
    button.btn.btn-default {
    height: 41px;
}
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

<span id="boy">    
  <div class="clearfix p40t"></div>

  <div class="main-page">   
   <div id="html-content-holder">

    <div id="container" style="padding-top: 0px !important; margin-top: 0px !important;">
     <!--   <?php echo $_SESSION['instragram'] ?>  -->
     <!-- Header section-->
     <header class="header">
      <div class="row">
        <div class="col-sm-12">
         <img class="logo" title="" alt="" src="<?php echo base_url('li_card/assets/images/type/'.$user->privilege_id.'.png');?>" />
       </div>
     </div>
   </header>

   <div class="main row">
    <div class="col-sm-5">
      <div class="c-center">
        <div id="avatar">
				<?=
					$user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
					'<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
					?>
        </div>
     </div>
   </div>

   <div class="col-sm-7 no-padding">
    <div id="contact">
      <div class="user_user"><p class="input"><?php print  $user->username; ?></p></div>
      <div class="user_id"><p class="input"><?php print  $user->seller_id; ?></p></div>
      <div class="user_phone"><p class="input"><?php print  $companies[0]->phone; ?></p></div>
      <div class="user_line"><p class="input"><?php print  $companies[0]->line; ?></p></div>
      <div class="user_facebook"><p class="input"><?php print $companies[0]->facebook; ?></p></div>
      <div class="user_instagram"><p class="input"><?php print  $companies[0]->instragram; ?></p></div>
    </div>
  </div>
</div>

<!-- Footer Section -->
<footer class="footer">
  <div class="row">
    <div class="col-sm-5"></div>

    <div class="col-sm-7 no-padding">
      <ul class="detail-profile">
        <li><strong>TEAM</strong> : <?php echo $user->team; ?></li>
      </ul>
    </div>
  </div>
</footer>





</div> <!-- /#container -->

</div> <!-- /#html-content-holder-->
</div>  <!-- end -->
</span>
<div class="main-page">  


 <div class="p20t clearfix"></div> 
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


<br>


</div>
<script type="text/javascript">
setTimeout(function(){
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
  //-----------------------------------------------//
    $("#btn-Convert-Html2Image").on('click', function () {
    var imgageData = getCanvas;
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
      // console.log(newData);
      $("#btn-Convert-Html2Image").attr("download", "picture-" + getTime + ".png").attr("href", newData);
    }); 
    //--------------------------------------------//
}, 200);




  
  $("#black2").on("click",function(){
    window.location.href="<?= admin_url('users/profile/'.$user->id); ?>"
  });

</script>

<div id="jwProcess"></div>
<script src="<?php echo base_url('li_card/js/function.apps.js')?>"></script>
<script src="<?php echo base_url('li_card/assets/plugins/bootstrap/js/bootstrap.min.js?v=61')?>"></script>
</body>
</html>