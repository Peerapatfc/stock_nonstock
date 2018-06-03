<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<title>ตัวแทนจำหน่าย</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Custom Style Sheet -->
		<link href=" <?= base_url('themes/default/includes/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
		<link href=" <?= base_url('themes/default/includes/css/bootstrap-override.css') ?>" rel="stylesheet" type="text/css"/>
        <link href=" <?= base_url('themes/default/includes/css/style.css') ?>" rel="stylesheet" type="text/css"/>
        
    </head>

    
    <body id="page-top">
		<div id="container">
		<h1>ตัวแทนจำหน่าย</h1>
        <!-- Header section-->
        <?php #@$this->load->view('website/includes/header') ?>
			<div class="row">
			<div class="col-sm-5"><div id="avatar"><img alt="" src="<?php echo base_url('assets/uploads/avatars/thumbs/').$user->avatar; ?>" class="avatar"></div></div>
			<div class="col-sm-7">
				<?php /*<p class="logo"><span class="logo"><img title="Agent Smith" alt="Agent Smith" src="<?php echo base_url().'assets/uploads/logos/Logo-SMITH1.png'; ?>" /></span></p>*/ ?>
				<ul class="detail-profile">
					<li><strong>ชื่อตัวแทน</strong>: <?php echo $user->name; ?></li>
					<li class="sellerid"><strong>รหัสตัวแทน</strong>: <?php echo $user->seller_id; ?></li>
					<li><strong>เบอร์โทร </strong>: <?php echo $user->phone; ?></li>
					<li><strong>Line</strong>: <?php echo $user->line; ?></li>
					<li><strong>Facebook</strong>: <?php echo $user->facebook; ?></li>
					<li><strong>Instragram</strong>: <?php echo $user->instragram; ?></li>
				</ul>
				<?php #print_r($user); ?>
			</div>
			</div>
        <!-- Footer Section -->
        <?php #@$this->load->view('website/includes/footer') ?>
		</div>
    </body>
</html>