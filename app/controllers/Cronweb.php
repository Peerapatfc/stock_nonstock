<?php
class CronWeb extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		
		$this->load->admin_model('sales_model','object_sales_model');
		$this->load->admin_model('products_model','object_products_model');
		$this->load->admin_model('companies_model','object_companies_model');
		$this->load->admin_model('settings_model','object_settings_model');
		


		$this->digital_upload_path = 'files/';
		$this->upload_path = 'assets/uploads/';
		$this->thumbs_path = 'assets/uploads/thumbs/';
		$this->image_types = 'gif|jpg|jpeg|png|tif';
		$this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
		$this->allowed_file_size = '1024';
		$this->data['logo'] = true;
		
	
		
	}
	
	public function copy_img($img,$img_url){
		$img_r = substr($img,mb_strrpos($img,"/"),strlen($img));
		$path = substr($img,0,mb_strrpos($img,"/"));
		
		$upload_path = $this->upload_path.'product'.$path;
		$digital_upload_path = $this->digital_upload_path.'product'.$path;
		$thumbs_path = $this->thumbs_path.'product'.$path;
		
		if (!file_exists($upload_path)){
			mkdir($upload_path, 0777, true);
		}
		if (!file_exists($digital_upload_path)){
			mkdir($digital_upload_path, 0777, true);
		}
			if (!file_exists($thumbs_path)){
			mkdir($thumbs_path, 0777, true);
		}
		copy($img_url, $upload_path."".$img_r);
		copy($img_url, $digital_upload_path."".$img_r);
		copy($img_url, $thumbs_path."".$img_r);
	}
	
	public function getSlip($value){
		$return = null;
		if(mb_strpos($value,"payment confirmation")>"-1"){
			$slip = $value;
			/* Get slip image */
			$pattern = '/<img[^>]+src="([^>"]+)/';
			$subject = $slip;
			$result = preg_match( $pattern, $subject , $matches , PREG_OFFSET_CAPTURE );
			$matched =$matches[1];
			$first_img = $matched[0];
			$file_name = date("YmdHis")."-".(mb_substr($first_img,(mb_strrpos($first_img,"/")+1),strlen($first_img)));
			copy($first_img, $this->upload_path."".$file_name);
			copy($first_img, $this->digital_upload_path."".$file_name);
			$return = $file_name;
		}
		return $return;
	}
	
/*	public function replace_double_quote($value){
		$pattern = "/\"/";
		$replace = "";
		$line = preg_replace($pattern,$replace,$line);
		$lineArray = explode(",",$line);  
	}*/
	
	public function index()
	{
		$setting = $this->object_settings_model->getSettings();
		$sync_web = $setting->web_sync_url;

		$json = file_get_contents($sync_web.'/api/get_smith_data.php');
		$array = json_decode($json);
		
		
		//echo count($array->product_data);
	
		$items = array();
		$date = date('Y-m-d H:i:s');

		foreach($array->product_data as $data){
			$img = NULL;
			$img_url = NULL;
			if($data->product_image!="no_selection"){
				$img = $data->product_image;
				$img_url = $data->product_image_url;
				
				$img_real = "product".$data->product_image;
				$img_url_real = "product".$data->product_image_url;
			}
				$datas = array(
					'code' => ($data->sku),
					'barcode_symbology' => 'code128',
					'name' => ($data->product_name),
					'type' => 'standard',
					'category_id' => '1',
					'cost' => $data->price,
					'price' => $data->price,
					'details' => ($data->short_description),
					'product_details' => ($data->description),
					'quantity' => $data->qty,
					'image' => $img_real,
					'unit' => '1',
					'slug' => $data->slug,
					'weight' => $data->weight,
				);
				
				$items = NULL;
				$warehouse_qty = NULL;
				$product_attributes = NULL;
				$photos = NULL;

				$this->copy_img($img,$img_url);
				$this->object_products_model->addProduct($datas, '', '', '', '');
			
		//echo $data->sku."<br>";
		
		}

		foreach($array->customer_data as $data){
			$address = trim(($data->street)." ".($data->city)." ".($data->region)." ".($data->postcode));
			$datas = array(
				'name' => $data->firstname." ".$data->lastname,
				'email' => $data->email, #$this->input->post('email')
				'group_id' => '3',
				'group_name' => 'customer',
				'customer_group_id' => '1',
				'customer_group_name' => 'General',
				'price_group_id' => NULL,
				'price_group_name' => NULL,
				'company' => $data->firstname." ".$data->lastname,
				'address' => $address,
				'vat_no' => $data->vat_id,
				'city' => $data->city,
				'state' => $data->region,
				'postal_code' => $data->postcode,
				'country' => $data->country_id,
				'phone' => $data->telephone,
			);
			
			if(!$this->object_companies_model->getCompanyByEmailAndAddress($data->email,$address)){
				$this->object_companies_model->addCompany($datas);	
			}
		}

		foreach($array->order_data as $data){
							
			
			$address_order = trim(($data->street)." ".($data->city)." ".($data->region)." ".($data->postcode));
			$company = $this->object_companies_model->getCompanyByEmailAndAddress($data->email,$address_order);
			$biller = $this->object_companies_model->getCompanyByID($setting->default_biller_sync);
			$sale_id = $this->site->getReference('so');
			
			
			$datas = array(
				'date' => $data->created_at,
				'reference_no' => $sale_id,
				'customer_id' => $company->id,
				'customer' => $company->name,
				'biller_id' => $biller->id,
				'biller' => $biller->company,
				'warehouse_id' => "1",
				'note' => "",
				'staff_note' => "",
				'total' => $data->grandtotal,
				'product_discount' => "",
				'order_discount_id' => "",
				'order_discount' => "",
				'total_discount' => "",
				'product_tax' => "",
				'order_tax_id' => "",
				'order_tax' => "",
				'total_tax' => "",
				'shipping' => "",
				'grand_total' => $data->grandtotal,
				'total_items' => $data->total_items,
				'sale_status' => "completed",
				'payment_status' => "paid",
				'payment_term' => "",
				'due_date' => "",
				'paid' => $data->grandtotal,
				'created_by' => $biller->id,
				'hash' => hash('sha256', microtime() . mt_rand()),
				'attachment' => $this->getSlip($data->order_status_history),
				'order_status_history' => $data->order_status_history,
				'bank_from' => "",
				'bank_to' => "",
				'date_cf_payment' => "",
				'total_cf_payment' => "",
				'web_order_id' => $data->increment_id,
				'cms_sync_web' => $data->cms_sync_web
			);
				$items = array();
				foreach($data->sku_order as $data_sku){
					$product = $this->object_products_model->getProductByCode($data_sku->sku);
					$items[] = array(
						'sale_id' => $sale_id,
						'product_id' => $product->id,
						'product_code' => $product->code,
						'product_name' => $product->name,
						'net_unit_price' => $data_sku->base_price,
						'quantity' => $data_sku->qty_ordered,
						'subtotal' => $data_sku->base_row_total,
						'unit_quantity' => $data_sku->qty_ordered

					);
				}
			if(!$this->object_sales_model->getSaleByWebOrderID($data->increment_id)){
				
				$payment = array(
					'date' => $date,
					'sale_id' => $sale_id,
					'reference_no' => $this->site->getReference('pay'),
					'amount' => $data->grandtotal,
					'paid_by' => 'cash',
					'created_by' => $company->id,
					'type' => 'received'
				);
				
				$this->object_sales_model->addSale($datas, $items, $payment);

			}
		}
	}
	

 
  public function greet($name)
  {
  // echo "Hello, $name" . PHP_EOL;
  }
}

?>