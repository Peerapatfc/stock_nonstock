<?php
	namespace frontend\modules\report\controllers;
	use Yii;
	use yii\web\Controller;
	use yii\data\ArrayDataProvider;
	use frontend\modules\report\models\OrderSummaryClass;
	use frontend\modules\report\models\OrderSummaryByMonth;
	use frontend\modules\report\models\OrderListFilter;
	use yii\helpers\ArrayHelper;
	use yii\filters\AccessControl;
	use common\models\Order;



	class SellController extends Controller{
		private $connection;
    	private $tenant;

    	public function behaviors(){
	        return [
	            'access' => [
	                'class' => AccessControl::className(),
	                'rules' => [
	                    [
	                        'allow' => true,
	                        'roles' => ['@'],
	                    ],
	                ]
	            ],
	        ];
	    }
	    public function __construct($id, $module, $config = []){
	      parent::__construct($id, $module, $config);
	      $this->connection = Yii::$app->db;
	      $this->connection->createCommand("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';")->execute();
	    }
    	private function getMapCategory(){
    		$data = $this->connection->createCommand('select * from category where tenant_id = '. $this->tenant. ' and status = 1')->queryAll();
    		return ArrayHelper::map($data, 'id', 'name');
    	}

//		start Product Profit
    	private function selectBestProductSell(){

    		// $data = new OrderSummaryClass($this);
    		// $data->topProduct();
    		// exit();
    		$where = '';
    		if((Yii::$app->request->get('nameProduct'))){
    			$where  .= "and (product.name like '%". Yii::$app->request->get('nameProduct'). "%' or product.sku like '%". Yii::$app->request->get('nameProduct')."%') ";
    		}
    		if((Yii::$app->request->get('category'))){
    			$where .= 'and product.category_id = '. Yii::$app->request->get('category');
    		}
    		if((Yii::$app->request->get('fromDate')) && (Yii::$app->request->get('toDate'))){
    			$from  = strtotime(Yii::$app->request->get('fromDate'));
    			$to = strtotime('+23 hour +59 minutes', strtotime(Yii::$app->request->get('toDate')));
    			$where .= ' and dt_created > '. $from . ' and dt_created < '. $to. ' ';
    		}
    		$sqlSelectTop = 'select product.sku as sku, product.name as name, round(product.price) as price, round(product.cost) as cost, sum(order_detail.quantity) as qty from product INNER JOIN order_detail ON order_detail.product_id = product.id INNER JOIN `order` ON order_detail.order_id = `order`.id where product.tenant_id = '. $this->tenant.' '. $where.' and order.status = 1 and product.product_type_id = 2 and product.status = 1 group by order_detail.product_id order by qty DESC';
    		$data = $this->connection->createCommand($sqlSelectTop)->queryAll();
    		return $data;
    	}

		private function bestSellProduct($tenant){
			$data = new OrderSummaryClass($this);
			return $data->topProduct();
			
		}

//		end Product Profit
//		start product month 
		private function lastOrderInMonth($year){
			$sql = 'SELECT MAX(MONTH(FROM_UNIXTIME(dt_created))) as month FROM `order` where tenant_id = '. $this->tenant.' and YEAR(FROM_UNIXTIME(dt_created)) = '. $year;
			$data = $this->connection->createCommand($sql)->queryOne();
			return $data['month'];
		}

		private function listYearInOrder(){
			$sql = 'SELECT YEAR(FROM_UNIXTIME(dt_created)) as year FROM `order` where 
				(
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1 or
					sync_status = \'complete\' or
					sync_status = \'delivered\'
				)
			and tenant_id = '. $this->tenant.' group by YEAR(FROM_UNIXTIME(dt_created)) ORDER BY dt_created DESC';
			$data = $this->connection->createCommand($sql)->queryAll();
			$data = ArrayHelper::map($data, 'year', 'year');
			return $data;
		}

		private function selectProduct($month, $year){
			$month = [
		 		'', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG'
		 		, 'SEP', 'OCT', 'NOV', 'DEC'
		 	];

		 	$whereProduct = '';
    		if((Yii::$app->request->get('nameProduct'))){
    			$whereProduct  .= " and name like '%". Yii::$app->request->get('nameProduct'). "%' ";
    		}
    		if((Yii::$app->request->get('category'))){
    			$whereProduct .= ' and category_id = '. Yii::$app->request->get('category');
    		}


			$sql = 'SELECT id, name, sku FROM product WHERE status = 1  ' . $whereProduct .' AND tenant_id = '. $this->tenant . ' ORDER BY sku ';
			$data = $this->connection->createCommand($sql)->queryAll();
			if($data != null) {
				foreach($data as $key => $value){
					$max = 0;
					for($i = 1 ; $i <= 12; $i++){
						
						$sql = 'SELECT SUM(order_detail.quantity) AS quantity FROM order_detail INNER JOIN `order` ON `order`.id = order_detail.order_id where `order`.status = 1 and MONTH(FROM_UNIXTIME(`order`.dt_created)) =  '. $i .' and YEAR(FROM_UNIXTIME(`order`.dt_created)) =  '. $year .' and product_id = '. $value['id'];
						$qty = $this->connection->createCommand($sql)->queryOne();
						if($qty['quantity'] < 1) $data[$key][$month[$i]] = 0;
						else {
							$max += $qty['quantity'];
							$data[$key][$month[$i]] = $qty['quantity'];
						}
					}
					$data[$key]['total'] = $max;
				}
			}
			return $data;
		}

		private function productByMonth($tenant){
			$this->tenant = $tenant;
			$this->connection = Yii::$app->db;
			if((Yii::$app->request->get('year')))  $year = Yii::$app->request->get('year');
    		else $year = date('Y');
			$month = $this->lastOrderInMonth($year);
			$list = $this->selectProduct($month, $year);
			$listCategory = $this->getMapCategory();
			$listYear = $this->listYearInOrder();
			$provider = new ArrayDataProvider([
				'allModels' => $list,
			]);
			return $this->render('orderProductByMonth',[
				'model' => $provider,
				'month' => $month,
				'year' => $year,
				'listCategory' => $listCategory,
				'listYear' => $listYear,
			]);
		} 
//		end product month

//		start order status
		public function selectOrderStatus($tenant){
			$this->tenant = $tenant;
			$this->connection = Yii::$app->db;
			$orderStatus = $this->connection->createCommand('select id, name from order_status where id not in (2,5,7,8) ')->queryAll();
			
			foreach($orderStatus as  $key => $value){
				$sumOrder = $this->connection->createCommand('select count(id)  as total from `order` where status = 1 and tenant_id = '. $this->tenant.' and order_status_id = '. $value['id'])->queryOne();
				$sumQuantity = $this->connection->createCommand('select count(*) as total from order_detail inner join `order` on `order`.id = order_detail.order_id where `order`.status = 1 and `order`.tenant_id = '. $this->tenant.' and order_status_id = '. $value['id'])->queryOne();
				$sumPrice = $this->connection->createCommand('select sum(total) as total from `order` where status = 1 and tenant_id = '. $this->tenant. ' and order_status_id = '. $value['id'])->queryOne();
				$orderStatus[$key]['total'] = $sumOrder['total'];
				$orderStatus[$key]['quantity'] = $sumQuantity['total'];
				if($sumPrice['total'] < 1) $orderStatus[$key]['price'] = 0;
				else $orderStatus[$key]['price'] = $sumPrice['total'];
			}
		
			return $this->render('orderStatus',['model' => $orderStatus]);
		}
//		end order status
//		start Top Bank
		private function changeMonthInKey($data){
			$dataMonth = [];
			foreach($data as $key => $value){
				$dataMonth[$value['month']] = $value;
			}
			return $dataMonth;
		}

		public function payBank($tenant){
			$this->tenant = $tenant;
			$this->connection = Yii::$app->db;

			$bank = $this->connection->createCommand('select id, bank_name_en, bank_icon, bank_name_th from bank_account')->queryAll();
			foreach($bank as $key => $value){
				$listBank = $this->connection->createCommand('select * from tenant_bank_account where tenant_id = '. $this->tenant. ' and status = 1 and bank_account_id = '. $value['id'])->queryAll();
				foreach($listBank as $index => $item){
					$selectMonth = $this->connection->createCommand('SELECT FORMAT(sum(amount),2) as total, MONTH(FROM_UNIXTIME(payment_date)) as month FROM `payment` where tenant_id = '. $this->tenant.' and tenant_bank_account_id =  '. $item['id'] .'  group by MONTH(FROM_UNIXTIME(payment_date))')->queryAll();
					$listBank[$index]['month'] = $this->changeMonthInKey($selectMonth);
				}
				
				if(count($listBank) > 0) {
					$bank[$key]['list'] = $listBank;
				// 	foreach($listBank as $index => $item){
				// 		$totalPayMent = $this->connection->createCommand('SELECT sum(amount) AS total FROM payment WHERE tenant_bank_account_id = '. $item['id'])->queryOne();
				// 		$listBank[$index]['total'] = $totalPayMent['total'];
				}else unset($bank[$key]);
			}
			$provider = new ArrayDataProvider([
				'allModels' => $bank,
			]);
			return $this->render('payBank', ['model' => $bank]);
		}
//		end Top Bank

//		start top Order
		private function topOrder($tenant){
			$this->tenant = $tenant;
			$this->connection = Yii::$app->db;
			$where = '';
			if(Yii::$app->request->get('minTotal') && Yii::$app->request->get('maxTotal')){
				$where .= ' and total >= '. Yii::$app->request->get('minTotal'); 
				$where .= ' and total <= '. Yii::$app->request->get('maxTotal');
			}

			if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){ 
				$fromDate =  strtotime(Yii::$app->request->get('fromDate'));
				$toDate = strtotime('+23 hour +59 minutes', strtotime(Yii::$app->request->get('toDate')));
				$where .= ' and `order`.dt_created BETWEEN ' . $fromDate. ' and '. $toDate;
			}
			$selectTopOrder = $this->connection->createCommand("SELECT CONCAT(customer.name) as name, order_number, channel.name as channel, total as total FROM `order` INNER JOIN customer ON customer.id = `order`.customer_id INNER JOIN channel on channel.id = `order`.channel_id WHERE  `order`.tenant_id = ". $this->tenant. " and (
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1 or
					sync_status = 'complete' or
					sync_status = 'delivered'
				) and `order`.status = 1 ". $where." order by total DESC")->queryAll();
			$summaryOrder = $this->connection->createCommand('SELECT count(*) as amount, FORMAT(sum(total), 2) as total FROM `order` INNER JOIN customer ON customer.id = `order`.customer_id INNER JOIN channel on channel.id = `order`.channel_id WHERE  `order`.tenant_id = '. $this->tenant. ' and (
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1 or
					sync_status = \'complete\' or
					sync_status = \'delivered\'
				)  and `order`.status = 1 '. $where.' order by total DESC')->queryOne();

			
			$provider = new ArrayDataProvider([
				'allModels' => $selectTopOrder,
			]);
			return $this->render(
				'topOrder', ['model' => $provider, 'sumOrder' => $summaryOrder['total'],
				'amountOrder' => $summaryOrder['amount']]
			);
		}
//		end top Order
//		start shipment
		private function selectShipment($tenant){
			$this->tenant = $tenant;
			$this->connection = Yii::$app->db;
			return $this->render('shipment');
		}
//		end shipment
		public function actionShipment(){
			 return $this->selectShipment(Yii::$app->user->identity->tenant_id);
		}

		public function actionOrderStatus(){
			// return $this->selectOrderStatus(Yii::$app->user->identity->tenant_id);
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$statusColumns = [Yii::t('app', 'NotPaid'), Yii::t('app', 'Cancel'), Yii::t('app', 'Paid'), Yii::t('app', 'Confirmed'), Yii::t('app', 'Complete')];

			$channelList = \common\models\Channel::find()
	            ->where(['tenant_id'=>$tenant_id, 'status'=>1])
	            ->orderBy(['sort'=>SORT_ASC,'name'=>SORT_ASC])
	            ->all();

			$sync_channel = [];
	        // find only available sync channel
	        foreach($channelList as $channel){
	            if($channel->channelType->ch_type == 1){
	                $sync_channel[$channel->channel_type_id] = 1;
	            }
	        }

	        $lz_status_arr = Yii::$app->params['lazada_status'][6];
	        $lz_status = '';
	        if(count($lz_status_arr) > 1){
	            $lz_status = "('".implode("','", $lz_status_arr)."')";
	        }else{
	            $lz_status = "('".$lz_status_arr."')";
	        }

	        $es_status_arr = Yii::$app->params['11street_status'][6];
	        $es_status = '';
	        if(count($es_status_arr) > 1){
	            $es_status = "('".implode("','", $es_status_arr)."')";
	        }else{
	            $es_status = "('".$es_status_arr."')";
	        }

	        $date_from = date('Y-m-d');
	        $date_to = date('Y-m-d');

	        if($get = Yii::$app->request->get()){
	        	$date_from = $get['from'];
	        	$date_to = $get['to'];
	        }

			$queryNotpaid = Order::find()
			->joinWith(['channel','channel.channelType'], true, 'INNER JOIN')
            ->where(['order.tenant_id'=>$tenant_id, 'order.status' => Order::ACTIVE, 'order.cancel_date'=>0])
            ->andWhere([
                'or',
                isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][1], 'channel.channel_type_id'=>10] : '',
                isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][1], 'channel.channel_type_id'=>11] : '',
                isset($sync_channel[2]) ? ['order.sync_status'=>Yii::$app->params['lazada_status'][1], 'channel.channel_type_id'=>2] : '',
                isset($sync_channel[8]) ? ['order.sync_status'=>Yii::$app->params['11street_status'][1], 'channel.channel_type_id'=>8] : '',
                isset($sync_channel[9]) ? ['order.sync_status'=>Yii::$app->params['shopee_status'][1], 'channel.channel_type_id'=>9] : '',
                ['order.order_status_id'=>1, 'order.cod_approve'=>0],
            ]);

            if(Yii::$app->user->can('orderAssigned')){
	            $queryNotpaid->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryNotpaid->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryNotpaid->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

            $notpaidCount = $queryNotpaid->count();
            $notpaidTotal = $queryNotpaid->sum('total');

            $queryPaid = Order::find()
            ->joinWith(['channel', 'channel.channelType'], true, 'INNER JOIN')
            ->where(['order.tenant_id'=>$tenant_id, 'order.status' => Order::ACTIVE, 'order.cancel_date'=>0])
            ->andWhere([
                'or',
                ['order.order_status_id'=>4, 'order.payment_confirm_status' => 0],
                isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][4], 'payment_confirm_status_for_sync'=>0, 'channel.channel_type_id'=>10] : '',
                isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][4], 'order.payment_confirm_status_for_sync'=>0, 'channel.channel_type_id'=>11] : '',
                isset($sync_channel[2]) ? ['order.sync_status'=>Yii::$app->params['lazada_status'][4], 'order.payment_confirm_status_for_sync'=>0, 'channel.channel_type_id'=>2] : '',
                isset($sync_channel[8]) ? ['order.sync_status'=>Yii::$app->params['11street_status'][4], 'order.payment_confirm_status_for_sync'=>0, 'channel.channel_type_id'=>8] : '',
                isset($sync_channel[9]) ? ['order.sync_status'=>Yii::$app->params['shopee_status'][4], 'order.payment_confirm_status_for_sync'=>0, 'channel.channel_type_id'=>9] : '',
            ]);

            if(Yii::$app->user->can('orderAssigned')){
	            $queryPaid->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryPaid->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryPaid->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

            $paidCount = $queryPaid->count();
            $paidTotal = $queryPaid->sum('total');

            $queryConfirm = Order::find()
            ->joinWith(['channel', 'channel.channelType'], true, 'INNER JOIN')
            ->where(['order.tenant_id'=>$tenant_id, 'order.status' => Order::ACTIVE, 'order.cancel_date'=>0])
            ->andWhere([
                'or',
                ['order.order_status_id'=>4, 'order.payment_confirm_status'=>1],
                isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>10] : '',
                isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>11] : '',
                isset($sync_channel[2]) ? ['order.sync_status'=>Yii::$app->params['lazada_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>2] : '',
                isset($sync_channel[8]) ? ['order.sync_status'=>Yii::$app->params['11street_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'order.sync_tracking'=>null, 'channel.channel_type_id'=>8] : '',
                isset($sync_channel[9]) ? ['order.sync_status'=>Yii::$app->params['shopee_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>9] : '',
            ]);

            if(Yii::$app->user->can('orderAssigned')){
	            $queryConfirm->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryConfirm->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryConfirm->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

            $confirmCount = $queryConfirm->count();
            $confirmTotal = $queryConfirm->sum('total');

            $queryPack = Order::find()
	            ->joinWith(['channel', 'channel.channelType'], true, 'INNER JOIN')->joinWith('customer')
	            ->where(['order.tenant_id'=>$tenant_id, 'order.status' => Order::ACTIVE, 'order.cancel_date'=>0])
	            ->andWhere([
	                'or',
	                ['order.order_status_id'=>4, 'order.payment_confirm_status'=>1],
	                isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>10] : '',
	                isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>11] : '',
	                isset($sync_channel[2]) ? ['order.sync_status'=>Yii::$app->params['lazada_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>2] : '',
	                isset($sync_channel[8]) ? ['order.sync_status'=>Yii::$app->params['11street_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'order.sync_tracking'=>null, 'channel.channel_type_id'=>8] : '',
	                isset($sync_channel[9]) ? ['order.sync_status'=>Yii::$app->params['shopee_status'][4], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>9] : '',
	                ['order.cod_approve'=>1, 'order.order_status_id'=>1, 'cancel_date'=>0],
	            ]);
            
	        if(Yii::$app->user->can('orderAssigned')){
	            $queryPack->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryPack->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryPack->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

	        $packCount = $queryPack->count();
            $packTotal = $queryPack->sum('total');

            $queryShip = Order::find()
            	->joinWith(['channel', 'channel.channelType'], true, 'INNER JOIN')->joinWith('customer')
                ->where(['order.tenant_id'=>$tenant_id, 'order.status' => Order::ACTIVE, 'mark_as_complete'=>0, 'cancel_date'=>0])
                ->andWhere([
                    'or',
                    'order.order_status_id = 6',
                    isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][6], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>10] : '',
                    isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][6], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>11] : '',
                    isset($sync_channel[2]) ? new \yii\db\Expression('`order`.`sync_status` IN '.$lz_status.' AND `order`.`payment_confirm_status_for_sync` = 1 AND `order`.`sync_tracking` IS NOT NULL AND channel.channel_type_id = 2') : '',
                    isset($sync_channel[8]) ? new \yii\db\Expression('`order`.`sync_status` IN '.$es_status.' AND `order`.`payment_confirm_status_for_sync` = 1 AND `order`.`sync_tracking` IS NOT NULL AND channel.channel_type_id = 8') : '',
                    isset($sync_channel[9]) ? ['order.sync_status'=>Yii::$app->params['shopee_status'][6], 'order.payment_confirm_status_for_sync'=>1, 'channel.channel_type_id'=>9] : '',
                ]);

            if(Yii::$app->user->can('orderAssigned')){
	            $queryShip->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryShip->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryShip->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

	        $shipCount = $queryShip->count();
            $shipTotal = $queryShip->sum('total');

            $queryComplete = Order::find()
            	->joinWith(['channel', 'channel.channelType'], true, 'INNER JOIN')
	            ->where([
	                'order.tenant_id'=>$tenant_id, 
	                'order.status' => Order::ACTIVE,
	                'order.mark_as_complete'=>1,
	                'order.cancel_date'=>0,
	            ])
	            ->andWhere([
	                'or',
	                ['order.order_status_id'=>7],
	                isset($sync_channel[10]) ? ['order.sync_status'=>Yii::$app->params['magento_status'][7]] : '',
	                isset($sync_channel[11]) ? ['order.sync_status'=>Yii::$app->params['woo_status'][7]] : '',
	                isset($sync_channel[2]) ? ['order.sync_status'=>Yii::$app->params['lazada_status'][7]] : '',
	                isset($sync_channel[8]) ? ['order.sync_status'=>Yii::$app->params['11street_status'][7]] : '',
	                isset($sync_channel[9]) ? ['CAST(order.sync_status AS BINARY)'=>Yii::$app->params['shopee_status'][7]] : '',
	            ]);

	        if(Yii::$app->user->can('orderAssigned')){
	            $queryComplete->andWhere(['order.admin_id'=>$user_id]);
	        }

	        if(Yii::$app->user->can('period45Days')){
	            $queryComplete->andWhere(['between', 'DATE(FROM_UNIXTIME(order.dt_created))', new \yii\db\Expression('DATE_SUB(DATE(NOW()), INTERVAL 45 DAY)'), new \yii\db\Expression('DATE(NOW())')]);
	        }

	        if(!empty($date_from) && !empty($date_to)){
	            $queryComplete->andWhere([
	                    'between',
	                    (new \yii\db\Expression('DATE(FROM_UNIXTIME(order.dt_created))')),
	                    $date_from,
	                    $date_to
	                ]);
	        }

	        $completeCount = $queryComplete->count();
            $completeTotal = $queryComplete->sum('total');

            $summary = [
            	'NotPaid'=> [$notpaidCount, $notpaidTotal],
            	'Paid' => [$paidCount, $paidTotal],
            	'Confirm' => [$confirmCount, $confirmTotal],
            	'Pack' => [$packCount, $packTotal],
            	'Ship' => [$shipCount, $shipTotal],
            	'Complete' => [$completeCount, $completeTotal],
            ];
            return $this->render('orderStatus', [
            	'statusColumns' => $statusColumns, 
            	'summary'=>$summary,
            	'from' => $date_from,
            	'to' => $date_to,
           	]);
		}

		public function actionProductProfit(){
			return $this->bestSellProduct(Yii::$app->user->identity->tenant_id);
		}

		public function actionProductMonth(){
			return $this->productByMonth(Yii::$app->user->identity->tenant_id);
		}

		public function actionPayBank(){
			return $this->payBank(Yii::$app->user->identity->tenant_id);
		}

		public function actionTopOrder(){
			$tenant_id = Yii::$app->user->identity->tenant_id;

			$min = 0;
			$max = 0;
			$from = date('Y-m-d');
			$to = date('Y-m-d');
			$andTotalRange = '';
			$andDateRange = '';
			$params = [];
			$params[':tenant_id'] = $tenant_id;

			$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";	
			$params[':from'] = $from;
			$params[':to'] = $to;

			if($get = Yii::$app->request->get()){
				$min = $get['min'];
				$max = $get['max'];
				$from = $get['from'];
				$to = $get['to'];

				if(!empty($min) && !empty($max)){
					$andTotalRange = ' AND total BETWEEN :min AND :max ';
					$params[':min'] = $min;
					$params[':max'] = $max;
				}
				if(!empty($from) && !empty($to)){
					$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";	
					$params[':from'] = $from;
					$params[':to'] = $to;
				}
			}

			$sql = "
				SELECT 
					TRIM(CONCAT_WS('', customer.name, `order`.bill_name)) AS name, 
					`order`.order_number,
					(channel.name) AS channel,
					total   
				FROM `order` 
				LEFT JOIN customer ON customer.id = `order`.customer_id 
				INNER JOIN channel ON channel.id = `order`.channel_id 
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andTotalRange 
				$andDateRange 
			";

			$sqlCount = "
				SELECT COUNT(*)  
				FROM `order` 
				LEFT JOIN customer ON customer.id = `order`.customer_id 
				INNER JOIN channel ON channel.id = `order`.channel_id 
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andTotalRange 
				$andDateRange 
			"; 

			$count = Yii::$app->db->createCommand($sqlCount, $params)->queryScalar();
			
			$dataProvider = new \yii\data\SqlDataProvider([
				'sql' => $sql,
				'params' => $params,
				'totalCount' => $count,
				'sort' => [
					'defaultOrder' => ['total'=>SORT_DESC],
			        'attributes' => [
			            'name',
			            'order_number',
			            'total',
			        ],
			    ],
			    'pagination' => [
			        'pageSize' => 20,
			    ],
			]);

			return $this->render('topOrder', [
				'dataProvider'=>$dataProvider,
				'min' => $min,
				'max' => $max,
				'from' => $from,
				'to' => $to,
			]);
		}

		public function actionOrderChannel(){
			$this->connection = Yii::$app->db;
			$tenant = Yii::$app->user->identity->tenant_id;
			$where = '';
			if(Yii::$app->request->get('fromDate') && Yii::$app->request->get('toDate')){
				$fromDate =  strtotime(Yii::$app->request->get('fromDate'));
				$toDate = strtotime('+23 hour +59 minutes', strtotime(Yii::$app->request->get('toDate')));
				// print_r(date('d-m-Y', $toDate));
				$where .= ' and `order`.dt_created BETWEEN ' . $fromDate. ' and '. $toDate;
			}

			$summaryChannel = $this->connection->createCommand("SELECT FORMAT(sum(`order`.total),2) as total, count(*) as amount, channel.name as channelName, channel_type.logo , channel_type.name as channel FROM channel INNER JOIN `order` on `order`.channel_id = channel.id  INNER JOIN channel_type on channel.channel_type_id = channel_type.id where `order`.tenant_id = ". $tenant ."  and (
					cod_approve = 1 or
					payment_confirm_status = 1 or 
					payment_confirm_status_for_sync = 1
				) ". $where ."  group by `order`.channel_id order by amount DESC ")->queryAll();
			
			$provider = new ArrayDataProvider([
				'allModels' => $summaryChannel,
			]);
			return $this->render('orderChannel', ['model' => $provider]);
		}

		public function actionSaleInMonth(){
			$this->tenant = Yii::$app->user->identity->tenant_id;
			$where = '';
			if(Yii::$app->request->get('month')){
				$where .= ' and MONTH(FROM_UNIXTIME(`order`.dt_created)) = '. Yii::$app->request->get('month');
			}
			if(Yii::$app->request->get('year')){
				$where .= ' and YEAR(FROM_UNIXTIME(`order`.dt_created)) ='. Yii::$app->request->get('year');
			}
			$listYear = $this->listYearInOrder();
			$listMonth = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
			$this->connection = Yii::$app->db;
			$day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			$saleInDay =[];
			$saleInHour =[];
			foreach($day as $key => $value){
				$dataDay  = $this->connection->createCommand("select count(*) as amount, sum(total) as total from `order` where dayname(FROM_UNIXTIME(dt_created)) =  '". $value. "' and tenant_id = ". $this->tenant." and 
					(
						payment_confirm_status = 1 or 
						payment_confirm_status_for_sync = 1 or
						sync_status = 'complete' or
						sync_status = 'delivered'
					)
				 and status = 1 ". $where)->queryOne();
				$saleInDay[$value]['amount'] = number_format($dataDay['amount']);
				$saleInDay[$value]['total'] = round($dataDay['total'],2);
			}
			for($i = 0 ;$i <= 23; $i++){
				$dataHour =  $this->connection->createCommand("select count(*) amount,  sum(total) as total from `order` where hour(FROM_UNIXTIME(dt_created)) <=  '". $i. "' and hour(FROM_UNIXTIME(dt_created)) >  '". ($i - 1). "' and tenant_id = ". $this->tenant." and (
						payment_confirm_status = 1 or 
						payment_confirm_status_for_sync = 1 or
						sync_status = 'complete' or
						sync_status = 'delivered'
					) and status = 1 ". $where)->queryOne();
				$saleInHour[$i]['amount'] = $dataHour['amount'];
				$saleInHour[$i]['total'] = round($dataHour['total'],2);
			}
			return $this->render('saleInMonth',['saleInDay' => $saleInDay, 'saleInHour' => $saleInHour, 'listYear' => $listYear, 'listMonth' => $listMonth]);
		}

		public function actionSummaryAll($startback=null, $endback=null){
			// $data = new OrderSummaryClass($this);
			// return $data->summaryAll();
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$assigned = '';
			if(Yii::$app->user->can('reportAssigned')){
				$assigned = ' AND `order`.admin_id = '.$user_id;
			}

			$reports = [];
			$start = Yii::$app->request->post('start');
			$end = Yii::$app->request->post('end');
			if(empty($start) && empty($end)){
				if(!empty($startback) && !empty($endback)){
					$start = $startback;
					$end = $endback;
				}else{
					$start = date('Y-m-1');
					$end = date('Y-m-d');
				}
			}
			// $sql = "
			// 	SELECT MONTH(FROM_UNIXTIME(payment_confirm_date)) AS for_month, SUM(`order`.total) AS total_price, COUNT(`order`.id) AS orders, SUM(IF(product_bundle.quantity>0,0,order_detail.quantity)) AS total_qty, SUM(product_bundle.quantity) AS total_qty_bundle   
			// 	FROM `order` 
			// 	INNER JOIN order_detail ON order_detail.order_id = `order`.id 
			// 	INNER JOIN product ON product.sku = order_detail.tenant_product_sku 
			// 	LEFT JOIN product_bundle ON product_bundle.bundle_product_id = product.id 
			// 	WHERE 
			// 	(
			// 		(`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '') OR 
			// 	    (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3)
			// 	)
			// 	AND `order`.status = 1 
			// 	AND `order`.tenant_id = :tenant_id 
			// 	AND DATE(FROM_UNIXTIME(`order`.payment_confirm_date)) BETWEEN :start AND :end 
			// 	GROUP BY for_month;
			// ";
			$sql = "
				SELECT 
					YEAR(FROM_UNIXTIME(`order`.dt_created)) AS for_year, 
					MONTH(FROM_UNIXTIME(`order`.dt_created)) AS for_month, 
					COUNT(`order`.id) AS orders, 
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_not_confirm,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS not_confirm_qty,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS not_confirm_qty_bundle,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN total
							ELSE 0
						END
					) AS total_confirm,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
						END
					) AS confirm_qty,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN product_bundle.quantity
						END
					) AS confirm_qty_bundle,
					-- COUNT(
					-- 	CASE 
					-- 		WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.id 
					-- 		ELSE NULL 
					-- 	END
					-- ) AS cancel_orders,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_cancel,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS cancel_qty,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS cancel_qty_bundle
				FROM `order` 
				INNER JOIN (SELECT order_id, tenant_product_sku, quantity FROM order_detail GROUP BY order_id) AS tb_order_detail ON tb_order_detail.order_id = `order`.id 
				LEFT JOIN (SELECT id,sku FROM product GROUP BY sku) AS tb_product ON tb_product.sku = tb_order_detail.tenant_product_sku 
				LEFT JOIN product_bundle ON product_bundle.bundle_product_id = tb_product.id 
				WHERE `order`.status = 1 
				AND `order`.tenant_id = :tenant_id 
				AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :start AND :end 
				$assigned 
				GROUP BY for_year, for_month;
			";
			$order_at_months = Yii::$app->db->createCommand($sql,[':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end])->queryAll();

			return $this->render('summaryAll',['order_at_months'=>$order_at_months, 'start'=>$start, 'end'=>$end]);
		}

		public function actionSummaryAllDetail($start, $end, $month)
		{
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$assigned = '';
			if(Yii::$app->user->can('reportAssigned')){
				$assigned = ' AND `order`.admin_id = '.$user_id;
			}

			$reports = [];
			// $sql = "
			// 	SELECT DAY(FROM_UNIXTIME(`order`.payment_confirm_date)) AS for_day, MONTH(FROM_UNIXTIME(`order`.payment_confirm_date)) AS for_month, SUM(`order`.total) AS total_price, COUNT(`order`.id) AS orders, SUM(IF(product_bundle.quantity>0,0,order_detail.quantity)) AS total_qty, SUM(product_bundle.quantity) AS total_qty_bundle   
			// 	FROM `order` 
			// 	INNER JOIN order_detail ON order_detail.order_id = `order`.id 
			// 	INNER JOIN product ON product.sku = order_detail.tenant_product_sku 
			// 	LEFT JOIN product_bundle ON product_bundle.bundle_product_id = product.id 
			// 	WHERE 
			// 	(
			// 		(`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '') OR 
			// 		(`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3)
			// 	)
			// 	AND `order`.status = 1 
			// 	AND `order`.tenant_id = :tenant_id  
			// 	AND DATE(FROM_UNIXTIME(`order`.payment_confirm_date)) BETWEEN :start AND :end 
			// 	GROUP BY for_month, for_day 
			// 	HAVING for_month = :month;
			// ";
			$sql = "
				SELECT 
					DAY(FROM_UNIXTIME(`order`.dt_created)) AS for_day,
					MONTH(FROM_UNIXTIME(`order`.dt_created)) AS for_month, 
					COUNT(`order`.id) AS orders, 
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_not_confirm,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS not_confirm_qty,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS not_confirm_qty_bundle,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN total
							ELSE 0
						END
					) AS total_confirm,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
						END
					) AS confirm_qty,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN product_bundle.quantity
						END
					) AS confirm_qty_bundle,
					-- COUNT(
					-- 	CASE 
					-- 		WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.id 
					-- 		ELSE NULL 
					-- 	END
					-- ) AS cancel_orders,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_cancel,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS cancel_qty,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS cancel_qty_bundle 
				FROM `order` 
				INNER JOIN (SELECT order_id, tenant_product_sku, quantity FROM order_detail GROUP BY order_id) AS tb_order_detail ON tb_order_detail.order_id = `order`.id 
				LEFT JOIN (SELECT id,sku FROM product GROUP BY sku) AS tb_product ON tb_product.sku = tb_order_detail.tenant_product_sku 
				LEFT JOIN product_bundle ON product_bundle.bundle_product_id = tb_product.id 
				WHERE `order`.status = 1 
				AND `order`.tenant_id = :tenant_id 
				AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :start AND :end  
				$assigned 
				GROUP BY for_month, for_day  
				HAVING for_month = :month;
			";

			$order_at_days = Yii::$app->db->createCommand($sql,[':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end, ':month'=>$month])->queryAll();

			return $this->render('summaryAllDetail',['order_at_days'=>$order_at_days, 'start'=>$start, 'end'=>$end]);
		}

		public function actionCancelOrder(){
			$tenant_id = Yii::$app->user->identity->tenant_id;
			
			$andDateRange = '';
			$params = [];
			$params[':tenant_id'] = $tenant_id;
			$from = date('Y-m-d');
			$to = date('Y-m-d');
			
			$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";
			$params[':from'] = $from;
			$params[':to'] = $to;

			if($get = Yii::$app->request->get()){ 
				$from = Yii::$app->request->get('from');
				$to = Yii::$app->request->get('to');
				$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";
				$params[':from'] = $from;
				$params[':to'] = $to;
			}

			$sql = "
				SELECT 
					TRIM(CONCAT_WS('', `order`.bill_name, customer.name)) AS name, 
					order_number,
					(channel.name) AS channel,
					total,
					TRIM(CONCAT_WS('', `order`.sync_payment_method, store_payment_type.title_payment_custom)) AS type_payment,
					`order`.note
				FROM `order` 
				LEFT JOIN customer ON customer.id = `order`.customer_id 
				INNER JOIN channel ON channel.id = `order`.channel_id 
				LEFT JOIN store_payment_type ON store_payment_type.id = `order`.payment_type_id 
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andDateRange 
			";

			$sqlCount = "
				SELECT 
					COUNT(*) 
				FROM `order` 
				LEFT JOIN customer ON customer.id = `order`.customer_id 
				INNER JOIN channel ON channel.id = `order`.channel_id 
				LEFT JOIN store_payment_type ON store_payment_type.id = `order`.payment_type_id 
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andDateRange 
			";

			$count = Yii::$app->db->createCommand($sqlCount, $params)->queryScalar();
			
			$dataProvider = new \yii\data\SqlDataProvider([
				'sql' => $sql,
				'params' => $params,
				'totalCount' => $count,
				'sort' => [
					'defaultOrder' => ['dt_created'=>SORT_DESC],
			        'attributes' => [
			            'name',
			            'order_number',
			            'total',
			            'dt_created'
			        ],
			    ],
			    'pagination' => [
			        'pageSize' => 20,
			    ],
			]);

			return $this->render('cancelOrder', [
				'dataProvider' => $dataProvider,
				'from' => $from,
				'to' => $to,
			]);

			// $selectCancelOrder = $this->connection->createCommand("SELECT CONCAT(customer.name, ' ', customer.surname) as name, order_number, channel.name as channel,  FORMAT(total,2) as total, store_payment_type.title_payment_custom as type_payment, `order`.note FROM `order` INNER JOIN customer ON customer.id = `order`.customer_id INNER JOIN channel on channel.id = `order`.channel_id INNER JOIN store_payment_type on `order`.payment_type_id = store_payment_type.id WHERE  `order`.tenant_id = ". $this->tenant. " and order_status_id = 3 ". $where."  order by dt_created DESC")->queryAll();
			// $provider = new ArrayDataProvider([
			// 	'allModels' => $selectCancelOrder,
			// ]);
			// return $this->render('cancelOrder', ['model' => $provider]);
		}

		public function actionListOrderFilter(){
			$data = new OrderListFilter($this, Yii::$app->request->get());
			return $data->generateListOrder();
		}

		public function actionOrderLength(){
			$data = new OrderSummaryClass($this);
			return $data->summaryOrderLength();
		}

		public function actionSummaryPaymentType(){
			$data = new OrderSummaryByMonth($this);
			return  $data->orderPaymentTypeByMonth();
		}

		public function actionSummaryCategory(){
			// $data = new OrderSummaryClass($this);
			// return $data->SummaryCategory();
			$tenant_id = Yii::$app->user->identity->tenant_id;

			$from = date('Y-m-d');
			$to = date('Y-m-d');
			$andDateRange = '';
			$params = [];
			$params[':tenant_id'] = $tenant_id;

			$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";	
			$params[':from'] = $from;
			$params[':to'] = $to;

			if($get = Yii::$app->request->get()){
				$from = Yii::$app->request->get('from');
				$to = Yii::$app->request->get('to');
				$andDateRange = " AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :from AND :to ";	
				$params[':from'] = $from;
				$params[':to'] = $to;
			}

			$sql = "
				SELECT 
					product.category_id,
					category.tenant_id,
					(category.name) AS name,
					(
						SUM(
		                    CASE 
		                        WHEN (product_bundle.bundle_product_id IS NULL AND (
		                                (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR 
		                                (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '') OR 
		                                (`order`.cod_approve = 1 AND `order`.order_status_id != 3)
		                            ))
		                            THEN order_detail.quantity
		                        ELSE 0
		                    END
		                ) 
		                + 
		                SUM(
		                    CASE 
		                        WHEN (product_bundle.bundle_product_id IS NOT NULL AND (
		                                (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR 
		                                (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '') OR 
		                                (`order`.cod_approve = 1 AND `order`.order_status_id != 3)
		                            ))
		                            THEN (product_bundle.quantity * order_detail.quantity)
		                        ELSE 0
		                    END
		                )
	                ) AS qty,
					SUM(`order`.total) as total 
				FROM order_detail 
				INNER JOIN `order` ON `order`.id = order_detail.order_id 
				INNER JOIN product ON product.sku = order_detail.tenant_product_sku AND product.tenant_id = :tenant_id 
				LEFT JOIN product_bundle ON product.id = product_bundle.bundle_product_id 
				INNER JOIN category ON category.id = product.category_id AND category.status = 1
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andDateRange 
				GROUP BY product.category_id 
			";

			$sqlCount = "
				SELECT COUNT(*) FROM (SELECT 
					COUNT(*)  
				FROM order_detail 
				INNER JOIN `order` ON `order`.id = order_detail.order_id 
				INNER JOIN product ON product.sku = order_detail.tenant_product_sku AND product.tenant_id = :tenant_id 
				LEFT JOIN product_bundle ON product.id = product_bundle.bundle_product_id 
				INNER JOIN category ON category.id = product.category_id AND category.status = 1
				WHERE `order`.tenant_id = :tenant_id AND `order`.status = 1 
				$andDateRange 
				GROUP BY product.category_id ) AS custom_table
			";

			$count = Yii::$app->db->createCommand($sqlCount, $params)->queryScalar();

			$dataProvider = new \yii\data\SqlDataProvider([
				'sql' => $sql,
				'params' => $params,
				'totalCount' => $count,
				'sort' => [
					'defaultOrder' => ['name'=>SORT_ASC],
			        'attributes' => [
			            'name',
			            'qty',
			            'total',
			        ],
			    ],
			    'pagination' => [
			        'pageSize' => 20,
			    ],
			]);

			return $this->render('summaryCategory', [
				'dataProvider'=>$dataProvider,
				'from' => $from,
				'to' => $to
			]);
		}

		public function actionSummarySeller($startback=null, $endback=null, $gsale=null){
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$assigned = '';
			if(Yii::$app->user->can('reportAssigned')){
				$assigned = ' AND `order`.admin_id = '.$user_id;
			}

			$reports = [];
			$start = Yii::$app->request->post('start');
			$end = Yii::$app->request->post('end');
			$sale = Yii::$app->request->post('sale');
			if(!empty($gsale) && empty($sale)){
				$sale = $gsale;
			}
			// $saleCondition = 'GROUP BY sale_id';
			if(empty($start) && empty($end)){
				if(!empty($startback) && !empty($endback)){
					$start = $startback;
					$end = $endback;
				}else{
					$start = date('Y-m-1');
					$end = date('Y-m-d');
				}
			}
			
			if(!empty($sale)){
				$addCondition = 'AND `order`.admin_id = :sale_id';
				$params = [':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end, ':sale_id'=>$sale];
			}else{
				$addCondition = 'GROUP BY sale_id';
				$params = [':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end];	
			}

			$sql = "
				SELECT 
					(`order`.admin_id) AS sale_id,
					IF(profile.first_name != '' AND profile.last_name != '', CONCAT(profile.first_name,' ',profile.last_name), profile.name) AS sale_name,
					COUNT(`order`.id) AS orders, 
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_not_confirm,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS not_confirm_qty,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS not_confirm_qty_bundle,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3 AND cancel_date = 0) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN total
							ELSE 0
						END
					) AS total_confirm,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
						END
					) AS confirm_qty,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN product_bundle.quantity
						END
					) AS confirm_qty_bundle,
					-- COUNT(
					-- 	CASE 
					-- 		WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.id 
					-- 		ELSE NULL 
					-- 	END
					-- ) AS cancel_orders,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_cancel,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS cancel_qty,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS cancel_qty_bundle
				FROM `order` 
				INNER JOIN (SELECT order_id, tenant_product_sku, quantity FROM order_detail GROUP BY order_id) AS tb_order_detail ON tb_order_detail.order_id = `order`.id 
				LEFT JOIN (SELECT id,sku FROM product GROUP BY sku) AS tb_product ON tb_product.sku = tb_order_detail.tenant_product_sku 
				LEFT JOIN product_bundle ON product_bundle.bundle_product_id = tb_product.id 
				INNER JOIN user ON user.id = `order`.admin_id 
				INNER JOIN profile ON profile.user_id = user.id 
				WHERE `order`.status = 1 
				AND `order`.tenant_id = :tenant_id 
				AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :start AND :end 
				$assigned 
				$addCondition 
			";

			$orders = Yii::$app->db->createCommand($sql, $params)->queryAll();

			$saleListCondition = ['tenant_id'=>$tenant_id];
			if(Yii::$app->user->can('reportAssigned')){
				$saleListCondition['id'] = $user_id;
			}
			$sales = \common\models\User::find()->where($saleListCondition)->all();
			$saleList = [];
			if(!empty($sales)){
				$saleList = ArrayHelper::map($sales, 'id', function($model){
					if(!empty($model->profile->first_name) && !empty($model->profile->last_name)){
						return $model->profile->first_name.' '.$model->profile->last_name;
					}else{
						if(!empty($model->profile->name)){
							return $model->profile->name;
						}else{
							return '';
						}
					}
				});
			}

			return $this->render('summaryStaffSale',['orders'=>$orders, 'start'=>$start, 'end'=>$end, 'sale'=>$sale, 'saleList'=>$saleList]);

			// return $this->render('summarySaleStaff', ['start'=>$start, 'end'=>$end]);
			// $data = new OrderSummaryClass($this);
			// return $data->summarySeller();
		}

		public function actionSummaryStaffSaleDetail($start=null,$end=null,$sale=null)
		{
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$assigned = '';
			if(Yii::$app->user->can('reportAssigned')){
				$assigned = ' AND `order`.admin_id = '.$user_id;
			}

			$sql = "
				SELECT 
					(`order`.admin_id) AS sale_id,
					YEAR(FROM_UNIXTIME(`order`.dt_created)) AS for_year,
					MONTH(FROM_UNIXTIME(`order`.dt_created)) AS for_month,
					IF(profile.first_name != '' AND profile.last_name != '', CONCAT(profile.first_name,' ',profile.last_name), profile.name) AS sale_name,
					COUNT(`order`.id) AS orders, 
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_not_confirm,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS not_confirm_qty,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS not_confirm_qty_bundle,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN total
							ELSE 0
						END
					) AS total_confirm,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
						END
					) AS confirm_qty,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN product_bundle.quantity
						END
					) AS confirm_qty_bundle,
					-- COUNT(
					-- 	CASE 
					-- 		WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.id 
					-- 		ELSE NULL 
					-- 	END
					-- ) AS cancel_orders,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_cancel,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS cancel_qty,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS cancel_qty_bundle
				FROM `order` 
				INNER JOIN (SELECT order_id, tenant_product_sku, quantity FROM order_detail GROUP BY order_id) AS tb_order_detail ON tb_order_detail.order_id = `order`.id 
				LEFT JOIN (SELECT id,sku FROM product GROUP BY sku) AS tb_product ON tb_product.sku = tb_order_detail.tenant_product_sku 
				LEFT JOIN product_bundle ON product_bundle.bundle_product_id = tb_product.id 
				INNER JOIN user ON user.id = `order`.admin_id 
				INNER JOIN profile ON profile.user_id = user.id 
				WHERE `order`.status = 1 
				AND `order`.tenant_id = :tenant_id 
				AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :start AND :end 
				AND `order`.admin_id = :sale_id 
				$assigned 
				GROUP BY for_year, for_month
			";

			$orders = Yii::$app->db->createCommand($sql, [':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end, ':sale_id'=>$sale])->queryAll();

			return $this->render('summaryStaffSaleDetail',['orders'=>$orders, 'start'=>$start, 'end'=>$end, 'sale'=>$sale]);
		}

		public function actionSummaryStaffSaleDayDetail($start=null,$end=null,$sale=null,$month=null)
		{
			$tenant_id = Yii::$app->user->identity->tenant_id;
			$user_id = Yii::$app->user->identity->id;

			$assigned = '';
			if(Yii::$app->user->can('reportAssigned')){
				$assigned = ' AND `order`.admin_id = '.$user_id;
			}

			$sql = "
				SELECT 
					(`order`.admin_id) AS sale_id,
					DAY(FROM_UNIXTIME(`order`.dt_created)) AS for_day,
					MONTH(FROM_UNIXTIME(`order`.dt_created)) AS for_month,
					IF(profile.first_name != '' AND profile.last_name != '', CONCAT(profile.first_name,' ',profile.last_name), profile.name) AS sale_name,
					COUNT(`order`.id) AS orders, 
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_not_confirm,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS not_confirm_qty,
					SUM(
						CASE 
							WHEN (order_status_id != 3 AND cancel_date = 0 AND payment_confirm_status = 0) OR (sync_status != '' AND cancel_date = 0 AND payment_confirm_status_for_sync = 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS not_confirm_qty_bundle,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN total
							ELSE 0
						END
					) AS total_confirm,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
						END
					) AS confirm_qty,
					SUM(
						CASE 
							WHEN (`order`.payment_confirm_status = 1 AND `order`.order_status_id != 3) OR (`order`.payment_confirm_status_for_sync = 1 AND `order`.sync_status != '' AND cancel_date = 0) THEN product_bundle.quantity
						END
					) AS confirm_qty_bundle,
					-- COUNT(
					-- 	CASE 
					-- 		WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.id 
					-- 		ELSE NULL 
					-- 	END
					-- ) AS cancel_orders,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN `order`.total 
							ELSE 0 
						END
					) AS total_cancel,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN IF(product_bundle.quantity>0,0,tb_order_detail.quantity)
							ELSE 0
						END
					) AS cancel_qty,
					SUM(
						CASE 
							WHEN (order_status_id = 3 AND cancel_date > 0) OR (sync_status != '' AND cancel_date > 0) THEN product_bundle.quantity
							ELSE 0
						END
					) AS cancel_qty_bundle
				FROM `order` 
				INNER JOIN (SELECT order_id, tenant_product_sku, quantity FROM order_detail GROUP BY order_id) AS tb_order_detail ON tb_order_detail.order_id = `order`.id 
				LEFT JOIN (SELECT id,sku FROM product GROUP BY sku) AS tb_product ON tb_product.sku = tb_order_detail.tenant_product_sku 
				LEFT JOIN product_bundle ON product_bundle.bundle_product_id = tb_product.id 
				INNER JOIN user ON user.id = `order`.admin_id 
				INNER JOIN profile ON profile.user_id = user.id 
				WHERE `order`.status = 1 
				AND `order`.tenant_id = :tenant_id 
				AND DATE(FROM_UNIXTIME(`order`.dt_created)) BETWEEN :start AND :end 
				AND `order`.admin_id = :sale_id 
				$assigned 
				GROUP BY for_month, for_day 
				HAVING for_month = :month; 
			";

			$orders = Yii::$app->db->createCommand($sql, [':tenant_id'=>$tenant_id, ':start'=>$start, ':end'=>$end, ':sale_id'=>$sale, ':month'=>$month])->queryAll();

			return $this->render('summaryStaffSaleDayDetail',['orders'=>$orders, 'start'=>$start, 'end'=>$end, 'sale'=>$sale]);
		}

		public function actionSummaryPreorder(){
			$data = new OrderSummaryClass($this);
			return $data->summaryPreOrder();
		}

		public function actionListOrderSeller(){
			$data = new OrderSummaryClass($this);
			return $data ->listOrderSeller();
		}

		public function actionReturnOrder(){
			$data = new OrderSummaryClass($this);
			return $data->orderReturn();
		}

		public function actionOrderProvince(){
			$data = new OrderSummaryClass($this);
			return $data->orderByProvince();
		}
	}
?>