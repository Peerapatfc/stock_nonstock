<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		
        <!-- Custom Style Sheet -->
        <link href="<?= base_url('assets_web/css/style.css') ?>" rel="stylesheet" type="text/css"/>
    </head>

    
    <body id="page-top">
	
        <!-- Header section-->
        <?php @$this->load->view('website/includes/header') ?>

        <!-- Content section-->
		<?php print_r($_GET); ?>
		<?php print_r($id); ?>
		
        <!-- Footer Section -->
        <?php @$this->load->view('website/includes/footer') ?>
    </body>
</html>