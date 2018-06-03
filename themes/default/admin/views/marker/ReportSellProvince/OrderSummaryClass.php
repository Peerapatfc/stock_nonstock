<?php
	namespace frontend\modules\report\models;
	use Yii;
	use yii\web\Controller;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use yii\filters\AccessControl;
	class OrderSummaryClass{
		private $query;
		private $tenant;
		private $view;
		function __construct($view){
			$this->tenant = Yii::$app->user->identity->tenant_id;
			$this->db = Yii::$app->db;
			$this->view = $view;
		}
		private function listYearInOrder(){
			$sql = 'SELECT YEAR(FROM_UNIXTIME(dt_created)) as year FROM `order` where order_status_id > 4 and tenant_id = '. $this->tenant.' group by YEAR(FROM_UNIXTIME(dt_created)) ORDER BY dt_created DESC';
			$data = $this->db->createCommand($sql)->queryAll();
			$data = ArrayHelper::map($data, 'year', 'year');
			return $data;
		}
		public function summaryAll(){
			$listYear = $this->listYearInOrder();
			$month  = [];
			$total = 0;
			$totalOrder = 0;
			$where = '';
			if(Yii::$app->request->get('year')){
				$where .= ' and YEAR(FROM_UNIXTIME(`order`.dt_created)) = '. Yii::$app->request->get('year');
			}
			for($i = 1; $i <= 12 ;$i++){
				$data = $this->db->createCommand('select sum(total) as total , count(*) as amount 
					from `order` 
					where tenant_id = '. $this->tenant .' and 
						( 
							payment_confirm_status = 1 or 
							payment_confirm_status_for_sync = 1 or
							sync_status = \'complete\' or
							sync_status = \'delivered\'
						)
					 and MONTH(FROM_UNIXTIME(`order`.dt_created)) = '. $i. $where)->queryOne();
				$month[$i] = $data;
				$totalOrder += $data['amount'];
				$total += $data['total'];
			}
			return $this->view->render('summaryOrder', ['listYear' => $listYear, 'month' => $month, 'totalOrder' => $totalOrder, 'total' => $total
			]);
		}
		public function SummaryCategory(){
			$where = '';
			if(Yii::$app->request->get('minTotal') && Yii::$app->request->get('maxTotal')){
				$where .= ' and total >= '. Yii::$app->request->get('minTotal'); 
				$where .= ' and total <= '. Yii::$app->request->get('maxTotal');
			}
			if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){ 
				$fromDate =  strtotime(Yii::$app->request->get('fromDate'));
				$toDate = strtotime(Yii::$app->request->get('toDate'));
				$where .= ' and `order`.dt_created BETWEEN ' . $fromDate. ' and '. $toDate;
			}
			$arrIdCategory = [];
			$category = $this->db->createCommand('select * from category where tenant_id = '. $this->tenant .' and status = 1')->queryAll();
			foreach($category as $key => $value){
				$arrIdCategory[] = $value['id'];
			}
			$whereCategory = '';
			if(count($arrIdCategory) > 0){
				$whereCategory = ' and category.id in ('. join(',', $arrIdCategory).')';
			}
			$categorySale = $this->db->createCommand('select FORMAT(sum(order_detail.unit_price),2) as total, count(*) as amount, category.name from order_detail inner join product on product.id = order_detail.product_id inner join `order` on order.id = order_detail.order_id inner join category on category.id = product.category_id where  `order`.tenant_id = '. $this->tenant. ' and (
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1 or
					sync_status = \'complete\' or
					sync_status = \'delivered\'
				) '.  $whereCategory.  $where.' group by product.category_id')->queryAll();
			$provider = new ArrayDataProvider([
					'allModels' => $categorySale,
				]);
			return $this->view->render('summaryCategory',[ 'categorySale' => $provider]);
		}
		public  function SummaryOrderLength(){
			$arrSelectLength =[];
			$where = '';
			$length = [];
			for($i = 1 ; $i <= 20; $i++){
				$length[(($i - 1) * 250) +1 ] = $i * 250;
			}
			if(Yii::$app->request->get('minTotal') && Yii::$app->request->get('maxTotal')){
				$length = [
					Yii::$app->request->get('minTotal') => Yii::$app->request->get('maxTotal')
				];
			}
			if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){ 
				$fromDate =  strtotime(Yii::$app->request->get('fromDate'));
				$toDate = strtotime(Yii::$app->request->get('toDate'));
				$where .= ' and `order`.dt_created BETWEEN ' . $fromDate. ' and '. $toDate;
			}
			foreach($length as $key => $value){
				$data = $this->db->createCommand('select count(*) as amount, FORMAT(sum(total),2) as total from `order` where status = 1 and order_status_id > 3 and total >= '. $key .' and tenant_id = '. $this->tenant .' and total <= '. $value. $where)->queryOne();
				$arrSelectLength[number_format($key). ' - '. number_format($value)] = $data;
			}
			if(!(Yii::$app->request->get('minTotal') && Yii::$app->request->get('maxTotal'))){
				$data = $this->db->createCommand('select count(*) as amount, FORMAT(sum(total),2) as total from `order` where status = 1 and order_status_id > 3 and total > '. end($length) .' and tenant_id = '. $this->tenant . $where )->queryOne();
				$arrSelectLength['> '.number_format(end($length))] = $data;
			}
			return $this->view->render('orderLength',['data' => $arrSelectLength]);
		}
		public function summarySeller(){
			$listMonth = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
			$listYear = $this->listYearInOrder();
			$where = '';
			if(Yii::$app->request->get('month')){
				$where .= ' and MONTH(FROM_UNIXTIME(`order`.dt_created)) = '. Yii::$app->request->get('month');
			}
			if(Yii::$app->request->get('year')){
				$where .= ' and YEAR(FROM_UNIXTIME(`order`.dt_created)) = '. Yii::$app->request->get('year');
			}
			$data = $this->db->createCommand("
				select 
				  user.id as id,
				  CONCAT(profile.first_name,' ', profile.last_name) as name,
				  sum(if(order_status_id = 7, 1, 0)) as success,
				  (SELECT count(*) from `order` where admin_id = user.id and tenant_id =". $this->tenant."  and order_status_id = 3) as cancel,
				  sum(if(order_status_id > 4, total, 0)) as total
				from `order` 
				INNER JOIN user on `order`.admin_id = user.id
				INNER JOIN profile on user.id = profile.user_id
				where `order`.order_status_id > 3 and  `order`.tenant_id = ". $this->tenant. ' '.  $where .' group by admin_id')->queryAll();
			$provider = new ArrayDataProvider([
				'allModels' => $data,
			]);
			return $this->view->render('summarySeller', ['model' => $data, 'listYear' => $listYear, 'listMonth' => $listMonth]);
		}
		public function listOrderSeller(){
			$id = Yii::$app->request->get('id');
			$profile = $this->db->createCOmmand("SELECT * FROM profile where user_id = ". $id)->queryOne();
			$data = $this->db->createCommand("SELECT 
				channel_type.logo, 
				channel_type.name,
				SUM(IF(is_preorder = 0, 1, 0)) as orders,
				SUM(IF(is_preorder = 1, 1, 0)) as pre_order,
				FORMAT(sum(`order`.total),2) as total FROM `order`
			 INNER JOIN channel on `order`.channel_id = channel.id
			 INNER JOIN channel_type on channel.channel_type_id = channel_type.id
			 WHERE admin_id = ". $id ." and `order`.tenant_id = ". $this->tenant. " and admin_id = ".$id. " and order_status_id > 3 group by channel_type_id")->queryAll();
			$provider = new ArrayDataProvider([
				'allModels' => $data,
			]);
			return $this->view->render('orderListSeller', ['model' => $data, 'profile' => $profile]);
		}
		public function summaryPreorder(){
			$where = '';
			if(Yii::$app->request->get('name')){
				$name = Yii::$app->request->get('name');
				// $where .= " and (
				// 	customer.name LIKE '%". $name."%'
				// or 	customer.surname LIKE '%". $name."%'
				// or	product.sku LIKE'%". $name."%'
				// or	product.name LIKE'%". $name. "%'
				// )";
				$where .= " and (
					customer.name LIKE '%". $name."%'
				or 	customer.surname LIKE '%". $name."%'
				)";
			}
			if(Yii::$app->request->get('channel')){
				$where .= " and channel_type.id = ". Yii::$app->request->get('channel');
			}
			$channel = $this->db->createCommand("SELECT 
				channel_type.id as id, channel_type.name as name 
				FROM channel
				INNER JOIN channel_type on channel.channel_type_id = channel_type.id 
				where channel.tenant_id = ". $this->tenant." group by channel_type.name
			")->queryAll();
			$listChannel = ArrayHelper::map($channel, 'id', 'name');
			$data = $this->db->createCommand("SELECT 
				`order`.id,
				`order`.customer_address_id,
				order_status.id as staus_id,
				product.image1 as image,
				bank_account.bank_icon as bank,
				channel_type.logo,
				 `order`.order_number,CONCAT(customer.name, ' ', customer.surname) as ship_name, order_status.id as status_id , order_status.name as status, shipper.logo as carrier, payment_type.name as payment, `order`.total, `order`.dt_created  as order_date  FROM `order`
				INNER JOIN order_detail on `order`.id = order_detail.order_id
				INNER JOIN product on order_detail.product_id = product.id
				INNER JOIN channel on `order`.channel_id = channel.id 
				INNER JOIN channel_type on channel.channel_type_id = channel_type.id
				INNER JOIN payment on `order`.payment_type_id = payment.id
				INNER JOIN store_payment_type on `order`.payment_type_id = store_payment_type.id
				INNER JOIN payment_type on store_payment_type.payment_type_id = payment_type.id
				INNER JOIN shipping_price on `order`.shipping_price_id = shipping_price.id 
				INNER JOIN shipper on shipping_price.shipper_id = shipper.id
				INNER JOIN customer on `order`.customer_id = customer.id 
				INNER JOIN order_status on `order`.order_status_id = order_status.id
				INNER JOIN tenant_bank_account on payment.tenant_bank_account_id = tenant_bank_account.id
				INNER JOIN bank_account on bank_account.id = tenant_bank_account.bank_account_id
				WHERE `order`.tenant_id = ". $this->tenant. " and is_preorder = 1 ". $where." order by dt_created DESC")->queryAll();
			$provider = new ArrayDataProvider([
				'allModels' => $data,
			]);
			return $this->view->render('preOrder', ['model' => $provider, 'listChannel' => $listChannel]);
		}
		public function orderReturn(){
			$where = '';
			if(Yii::$app->request->get('name')){
				$name = Yii::$app->request->get('name');
				$where = " and (
					customer.name LIKE '%". $name."%'
				or 	customer.surname LIKE '%". $name."%'
				or	product.sku LIKE'%". $name."%'
				or	product.name LIKE'%". $name. "%'
				)";
			}
			if(Yii::$app->request->get('channel')){
				$where = " and channel_type.id = ". Yii::$app->request->get('channel');
			}
			if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){
				$start = Yii::$app->request->get('fromDate');
				$end = Yii::$app->request->get('toDate');
				$where .= ' and return_merchandise.created_at >'. $start . ' and return_merchandise.created_at < '. $end;
			}
			$data = $this->db->createCommand("SELECT 
					channel_type.logo as logo,
					`order`.id as id,
					product.image1 as image,
					`order`.order_number,
					`order`.customer_address_id,
					`order`.total as total,
					`order`.dt_created as order_date,
					shipper.logo as carrier,
					CONCAT(customer.name, ' ', customer.name) as ship_name,
					customer.telephone as phone,
					(SELECT SUM(quantity) FROM order_detail where order_id = `order`.id) as qty,
					(SELECT SUM(return_qty) FROM return_merchandise_detail where return_merchandise_detail.return_merchandise_id = return_merchandise.id) as return_qty,
					`order`.dt_created as order_create,
					return_merchandise.created_at as order_return,
					return_merchandise.reason as reason,
					return_merchandise.is_refund as refund,
					return_merchandise.order_type as order_type
				FROM  return_merchandise
				INNER JOIN `order` on return_merchandise.order_id = `order`.id
				INNER JOIN customer on `order`.customer_id = customer.id
				INNER JOIN order_detail on `order`.id = order_detail.order_id
				INNER JOIN product on order_detail.product_id = product.id
				INNER JOIN channel on `order`.channel_id = channel.id
				INNER JOIN channel_type on channel.channel_type_id = channel_type.id
				INNER JOIN shipping_price on `order`.shipping_price_id = shipping_price.id
				INNER JOIN shipper on shipping_price.shipper_id = shipper.id
				where return_merchandise.tenant_id =". $this->tenant." ". $where . "
			")->queryAll();
			$channel = $this->db->createCommand("SELECT 
				channel_type.id as id, channel_type.name as name 
				FROM channel
				INNER JOIN channel_type on channel.channel_type_id = channel_type.id 
				where channel.tenant_id = ". $this->tenant." group by channel_type.name
			")->queryAll();
			$listChannel = ArrayHelper::map($channel, 'id', 'name');
			$provider = new ArrayDataProvider([
				'allModels' => $data,
			]);
			return $this->view->render('orderReturn', ['model' => $provider, 'listChannel' => $listChannel]);
		}
		public function topProduct(){
			$where = '';
			$whereSync = '';
			$whereCategory = '';
    		if((Yii::$app->request->get('nameProduct'))){
    			$where  .= "and (product.name like '%". Yii::$app->request->get('nameProduct'). "%' or product.sku like '%". Yii::$app->request->get('nameProduct')."%') ";
    			$whereSync = "and sync_detail LIKE '%". Yii::$app->request->get('nameProudct')."%' ";
    		}
    		if((Yii::$app->request->get('category'))){
    			$where .= 'and product.category_id = '. Yii::$app->request->get('category');
    			$whereCategory = ' and category_id = '. Yii::$app->request->get('category');
    		}
    		if((Yii::$app->request->get('fromDate')) && (Yii::$app->request->get('toDate'))){
    			$from  = strtotime(Yii::$app->request->get('fromDate'));
    			$to = strtotime('+23 hour +59 minutes', strtotime(Yii::$app->request->get('toDate')));
    			$where .= ' and dt_created > '. $from . ' and dt_created < '. $to. ' ';
    			$whereSync .= ' and dt_created > '. $from . ' and dt_created < '. $to. ' ';
    		}
			$data = $this->db->createCommand("select 
					product.id as id,
					product.sku,
					product.name,
					product.price,
					product.cost,
					count(product_id) as qty
				 from `order` 
				INNER JOIN order_detail on order_detail.order_id = `order`.id
				INNER JOIN product on order_detail.product_id = product.id 
				where (
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1 or
					sync_status = 'complete' or
					sync_status = 'delivered'
				)". $where ." and `order`.tenant_id = ".  $this->tenant." and customer_address_id <> 0 group by order_detail.product_id
					order by qty DESC
			")->queryAll();
			$dataNew = [];
			foreach($data as $key => $value){
				$dataNew[$value['sku']] = $value;
			}
			$sync = $this->db->createCommand("select
					sync_detail from `order`
					where (
						payment_confirm_status = 1 or 
						payment_confirm_status_for_sync = 1 or
						sync_status = 'complete' or
						sync_status = 'delivered'
					) and `order`.tenant_id = ".  $this->tenant." ". $whereSync."  and customer_address_id = 0 
			")->queryAll();
			$skuNotFound = [];
			foreach($sync as $key => $value){
				$detailSync = json_decode($value['sync_detail'], true);
				foreach($detailSync as $index => $item){
					if(array_key_exists($item['product_sku'], $dataNew)){
						$dataNew[$item['product_sku']]['qty']++;
					}else{
						$skuNotFound[] = " sku = '". $item['product_sku'] . "'";
					}
				}
			}
			if(count($skuNotFound) > 0){
				$dataNotFound = $this->db->createCommand('select * from product WHERE tenant_id = '. $this->tenant .' and status = 1 and (' . join($skuNotFound, ' or ') . ') '. $whereCategory)->queryAll();
				foreach($dataNotFound as $key => $value){
					if(array_key_exists($value['sku'], $dataNew)){
						$dataNew[$value['sku']]['qty']++;
					}else{
						$dataNew[$value['sku']] = [
							'id' => $value['id'],
							'sku' => $value['sku'],
							'name' => $value['name'],
							'price' => $value['price'],
							'cost' => $value['cost'],
							'qty' => 1
						];
					}
				}
			}
			if(count($dataNew) > 0) $this->sksort($dataNew, 'qty');
			$listCategory = $this->getMapCategory();
			$provider = new ArrayDataProvider([
				'allModels' => $dataNew,
				'sort' => [
					'attributes' => ['qty', 'profit'],
					'defaultOrder' => [
						'qty' => SORT_DESC
					]
				],
			]);
			return $this->view->render('productProfit', ['model' => $provider, 'listCategory' => $listCategory]);
		}
		private function getMapCategory(){
    		$data = $this->db->createCommand('select * from category where tenant_id = '. $this->tenant. ' and status = 1')->queryAll();
    		return ArrayHelper::map($data, 'id', 'name');
    	}
    	public function orderByProvince(){
    		$province = $this->db->createCommand("
    			select thai_province.id, thai_province.name_th  from `order`
				   INNER JOIN zipcode on zipcode.zipcode = `order`.`sync_shipping_address_postcode`
				   INNER JOIN thai_province on zipcode.province_id = thai_province.id
				   where tenant_id = ". $this->tenant." and (
				   	`order`.`payment_confirm_status_for_sync` = 1 or 
				   	`order`.`payment_confirm_status` = 1 or
				    `order`.`sync_status` = 'complete' or 
				    `order`.`sync_status` = 'delevered'
				   ) and `order`.status = 1
				   group by thai_province.id
				   order by thai_province.name_th
    		")->queryAll();
    		$where = '';
    		if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){ 
				$fromDate =  strtotime(Yii::$app->request->get('fromDate'));
				$toDate = strtotime('+23 hour +59 minutes', strtotime(Yii::$app->request->get('toDate')));
				$where .= ' and `order`.dt_created BETWEEN ' . $fromDate. ' and '. $toDate;
			}
			if(Yii::$app->request->get('province')){
				$where = ' and thai_province.id = '. Yii::$app->request->get('province');
			}
    		$provinceSelect = ArrayHelper::map($province, 'id', 'name_th');
    		$data = $this->db->createCommand("
    		SELECT name_th, sum(orders) as orders, sum(total) as total, province_lat, province_lon
    			FROM (
				select thai_province.id, thai_province.name_th, count(*) as orders, sum(`order`.total) as total, thai_province.province_lat, thai_province.province_lon  from `order`

				   INNER JOIN zipcode on zipcode.zipcode = `order`.`sync_shipping_address_postcode`
				   INNER JOIN thai_province on zipcode.province_id = thai_province.id
				   where tenant_id = ". $this->tenant." and (
				   	`order`.`payment_confirm_status_for_sync` = 1 or 
				   	`order`.`payment_confirm_status` = 1 or
				    `order`.`sync_status` = 'complete' or 
				    `order`.`sync_status` = 'delevered'
				   ) and `order`.status = 1 ". $where ."
				   GROUP BY thai_province.id
				 	UNION ALL
					select thai_province.id, thai_province.name_th, count(*) as orders, sum(`order`.total), thai_province.province_lat, thai_province.province_lon  as total from `order`
					 INNER JOIN customer_address on customer_address.id = `order`.customer_address_id 
					 INNER JOIN zipcode on zipcode.zipcode = customer_address.zipcode
					 INNER JOIN thai_province on thai_province.id = zipcode.province_id 
					 where `order`.tenant_id = ". $this->tenant." and `order`.`payment_confirm_status` = 1 and `order`.status = 1
					  ". $where ."
					 GROUP BY thai_province.id
				 ) province GROUP BY id ORDER BY orders DESC
    		")->queryAll();
    		
    		$provider = new ArrayDataProvider([
				'allModels' => $data,
			]);
    		return $this->view->render('orderProvince', ['model' => $provider, 'selectProvince' => $provinceSelect]);
    	}
    	private function sksort(&$array, $subkey="id", $sort_ascending=false) {
		    if (count($array))
		        $temp_array[key($array)] = array_shift($array);
		    foreach($array as $key => $val){
		        $offset = 0;
		        $found = false;
		        foreach($temp_array as $tmp_key => $tmp_val)
		        {
		            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
		            {
		                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
		                                            array($key => $val),
		                                            array_slice($temp_array,$offset)
		                                          );
		                $found = true;
		            }
		            $offset++;
		        }
		        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
		    }
		    if ($sort_ascending) $array = array_reverse($temp_array);
		    else $array = $temp_array;
		}
	}
?>