<?php
	$hostname = 'localhost';
	$username = 'member99n_smith';
	$password = '3WvDuIyy0o';
	$database = 'member99n_smith';
	
	mysql_connect($hostname,$username,$password) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());
	

	$data_user = array();
	$sql2 = "SELECT * FROM `sma_users` u WHERE u.`active` = 0";
	$result2 = mysql_query($sql2);
	while($row2 = mysql_fetch_assoc($result2)):
		$data_user[] = $row2;
	endwhile;

	foreach($data_user as $user_val){
		//$user_val['id'] = 1047;
		//echo"<pre>"; print_r($user_val['id']); echo"</pre>";
		$sql_update = "UPDATE `sma_users` u SET u.`active` = 1, u.`group_id` = 13 WHERE u.`id` = '".$user_val['id']."'";
		echo $sql_update."<br>";
		mysql_query($sql_update);
	}
	
?>