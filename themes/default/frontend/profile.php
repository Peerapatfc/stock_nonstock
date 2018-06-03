<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<title>ตัวแทนจำหน่าย</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Custom Style Sheet -->
		<link href="<?= base_url('themes/default/includes/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
        <link href="<?= base_url('themes/default/includes/css/style.css') ?>" rel="stylesheet" type="text/css"/>
        
    </head>

	
    
    <body id="page-top">
		<?php //print_r($user); ?>
		<div id="container">
			<!-- Header section-->
			<header class="header">
				<div class="row">

				<?php $privilege_id = $user->privilege_id;
				if($privilege_id== 1){
					$imgTypeUrl = base_url('themes/default/includes/images/agent_logo.png');
				}elseif($privilege_id== 2){
					$imgTypeUrl = base_url('themes/default/includes/images/member_logo.png');
				}elseif($privilege_id== 3 || $privilege_id== 4){
					$imgTypeUrl = base_url('themes/default/includes/images/VIPv3_logo.png');
				}elseif($privilege_id== 5 || $privilege_id== 6){
					$imgTypeUrl = base_url('themes/default/includes/images/VVIP_logo.png');
				}elseif($privilege_id== 7){
					$imgTypeUrl = base_url('themes/default/includes/images/grand_vip_logo.png');
				}elseif($privilege_id>= 8){
					$imgTypeUrl = base_url('themes/default/includes/images/super_platinum_logo.png');
				}else{
					$imgTypeUrl = base_url('themes/default/includes/images/agent_logo.png');
				}
				if($user->user_type == "wisdom"){
					$imgTypeUrl = base_url('themes/default/includes/images/logo_wisdom.png');
				}
				?>

					<div class="col-sm-12">
						<img class="logo" title="" alt="" src="<?= $imgTypeUrl; ?>" />
						<?php 
							if($user->default_dealer == 1){
								$logo_default_dealer = base_url('themes/default/includes/images/logo_dealer.png');
									echo '<img style="height: 32px;margin-top: 45px;" class="logo" title="" alt="" src="'.$logo_default_dealer.'" />';
							}
						?>
					</div>
				</div>
			</header>
		
			<div class="main row">
			<div class="col-sm-5">
			<div class="c-center">
			<div id="avatar">
				<img alt="" src="<?= base_url('themes/default/admin/assets/agent-card/assets/images/imgup/tmp/').$user->img; ?>" class="avatar">
			</div>
			</div>
			</div>
			<div class="no-padding col-sm-7">
				<div id="contact">
					<div class="user_user"><p class="input"><?php echo $user->name; ?></p></div>
					<div class="user_id"><p class="input"><?php echo $user->seller_id; ?></p></div>
					<div class="user_phone"><p class="input"><?php echo $user->phone ?></p></div>
					<div class="user_line"><p class="input"><?php echo $user->line; ?></p></div>
					<div class="user_facebook"><p class="input"><?php echo $user->facebook; ?></p></div>
					<div class="user_instagram"><p class="input"><?php echo $user->instragram; ?></p></div>
				</div>
			</div>
			</div>
			
        <!-- Footer Section -->
		<footer class="footer">
			<div class="row">
				<div class="col-sm-5"></div>
			
				<div class="col-sm-7 no-padding ">
					<?php 
					$dteam = $getTeam[0]->first_name." ".$getTeam[0]->last_name;
					$team = isset($user->team) ? $user->team : $dteam; ?>
					<ul class="detail-profile">
						<li><strong>TEAM :</strong> <?php echo $team; ?></li>
					</ul>
				</div>
			</div>
		</footer> 
		</div>

		
		
		
    </body>
</html>