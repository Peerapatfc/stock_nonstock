<?php
	$to = 'admin@atcreative.co.th'; // <– replace with your address here
	$subject = 'Test Mail';
	$message = 'Hello! This is a simple test email message.';
	$from = 'admin@atcreative.com';
	$headers = 'From:' . $from;
	mail($to,$subject,$message,$headers);
	echo 'Mail Sent.';
?>