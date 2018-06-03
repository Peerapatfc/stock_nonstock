<?php
	$hostname = 'localhost';
	$username = 'member99n_smith';
	$password = '3WvDuIyy0o';
	$database = 'member99n_smith';
	
	mysql_connect($hostname,$username,$password) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());
	
	/* Buy */
	$data_buy = array();
	$sql2 = "SELECT d.id,d.username,d.warehouse_id,b.product_id,SUM(b.unit_quantity) as sum_unit FROM sma_purchases a,sma_purchase_items b,sma_users d WHERE a.warehouse_id = d.warehouse_id AND a.id = b.purchase_id AND a.payment_status != 'pending' GROUP BY a.`warehouse_id` ORDER BY d.`id` ASC";
	$result2 = mysql_query($sql2);
	while($row2 = mysql_fetch_assoc($result2)):
		//echo"<pre>"; print_r($row2); echo"</pre>";
		$data_buy[] = $row2;
	endwhile;
	
	
	/* Sell */
	$data_sell = array();
	$sql = "SELECT d.id,d.username,d.warehouse_id,b.product_id,SUM(b.unit_quantity) as sum_unit FROM sma_purchases a,sma_purchase_items b,sma_companies c,sma_users d WHERE a.supplier_id = c.id AND c.id = d.biller_id  AND a.id = b.purchase_id AND a.payment_status != 'pending' GROUP BY a.supplier_id ORDER BY d.`id` ASC";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)):
		//echo"<pre>"; print_r($row); echo"</pre>";
		$data_sell[] = $row;
	endwhile;
	

	
	//echo"<pre>"; print_r($data_buy); echo"</pre>";
	$data = array();
	$data_s = array();
	$i=0;
	
	foreach($data_buy as $db){
		$data[$db['id']]['id'] = $db['id'];
		$data[$db['id']]['username'] = $db['username'];
		$data[$db['id']]['warehouse_id'] = $db['warehouse_id'];
		$data[$db['id']]['sum_purchase'] = $db['sum_unit'];
		//echo"<pre>"; print_r($db); echo"</pre>";
	}
	
	foreach($data_sell as $ds){
		$data_s[$ds['id']]['id'] = $ds['id'];
		$data_s[$ds['id']]['username'] = $ds['username'];
		$data_s[$ds['id']]['warehouse_id'] = $ds['warehouse_id'];
		$data_s[$ds['id']]['sum_purchase'] = $ds['sum_unit'];
		//echo"<pre>"; print_r($db); echo"</pre>";
	}
	
	$i=0;
	$data_sum = array();
	foreach($data as $dby){
		$data_sum[$i]['id'] = $dby['id'];
		$data_sum[$i]['username'] = $dby['username'];
		$data_sum[$i]['warehouse_id'] = $dby['warehouse_id'];
		$data_sum[$i]['sum_purchase'] = $dby['sum_purchase'] - $data_s[$dby['id']]['sum_purchase'];
		$i++;
	}
	

	
//	echo"<pre>"; print_r($data_buy); echo"</pre>";

	//echo"<pre>"; print_r($data_s); echo"</pre>";
	//echo"<pre>"; print_r($data_sum); echo"</pre>";
//	print_r($data);
	foreach($data_sum as $dsu){
		$sql_update = "UPDATE sma_warehouses_products SET quantity = '".$dsu['sum_purchase']."' where warehouse_id = '".$dsu['warehouse_id']."'";
		echo $sql_update."<br>";
		mysql_query($sql_update);
	}

?>