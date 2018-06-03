<?php 


defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "/third_party/pdf/lib/mpdf/mpdf.php";
require_once APPPATH . "/third_party/thsms/thsms.php";

class Sales extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->admin_load('sales', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('sales_model');
		$this->load->admin_model('purchases_model');
		$this->load->admin_model('auth_model');
		$this->load->admin_model('reports_model');
		$this->load->admin_model('settings_model','object_settings_model');
		
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '30720';
        $this->data['logo'] = true;
    }

    public function index($warehouse_id = null)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		$_wisdom = "7";
		$this->data['wisdom'] = $this->sales_model->getUserByGroup($_wisdom);
		$this->data['sale_setting'] = $this->sales_model->getSaleSetting();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('sales/index', $meta, $this->data);
		
		
    }

    public function getSales($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');
		$setting = $this->object_settings_model->getSettings();
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        $detail_link = anchor('admin/sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $duplicate_link = anchor('admin/sales/add?sale_id=$1', '<i class="fa fa-plus-circle"></i> ' . lang('duplicate_sale'));
        $payments_link = anchor('admin/sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('admin/sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $packagink_link = anchor('admin/sales/packaging/$1', '<i class="fa fa-archive"></i> ' . lang('packaging'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('admin/sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
		$email_link = anchor('admin/sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $pdf_link = anchor('admin/sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $return_link = anchor('admin/sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
		
        $delete_link = "<a href='" . admin_url('sales/setdelete/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("delete_sale") . "'><i class=\"fa fa-trash-o\"></i>" . lang('delete_sale') ."</a>";
        // $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
		// . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete/$1') . "' data-toggle='modal' data-target='#myModal' class='tip'>"
        // . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        // . lang('delete_sale') . "</a>";

		//$add_delivery_soko = anchor('admin/sales/add_delivery_soko/$1', '<i class="fa fa-truck"></i> ' . lang('Add_Delivery_Soko'), 'data-toggle="modal" data-target="#myModal"');
		
		if ($this->Owner || $this->Admin) {
			$action = '<div class="text-center"><div class="btn-group text-left">'
			. '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
			. lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $payments_link . '</li>
				<li>' . $add_payment_link . '</li>
				<li>' . $add_delivery_link . '</li>


				<li>' . $detail_link . '</li>
				<li>' . $packagink_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $duplicate_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $email_link . '</li>
				<li>' . $return_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
			</div></div>';
		}else{
			$action = '<div class="text-center"><div class="btn-group text-left">'
			. '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
			. lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $payments_link . '</li>
				<li>' . $add_payment_link . '</li>
				<li>' . $add_delivery_link . '</li>
				<li>' . $detail_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
			</div></div>';
		}
		
        $this->load->library('datatables');
        if ($warehouse_id) {
			if($setting->default_admin_user = $this->session->userdata('user_id')){
				$child_store_arr = $this->site->getUserByParent($this->session->userdata('user_id'));
				$child_store = '"'.$warehouse_id.'",';
				foreach($child_store_arr as $cs){
					$child_store .= '"'.$cs->warehouse_id.'",';
				}
					$child_store = substr($child_store,0,(strlen($child_store)-1));
				 $this->datatables
					->select("{$this->db->dbprefix('sales')}.id as id , {$this->db->dbprefix('sales')}.cms_sync_web as cms_sync_web, reference_no, CONCAT(first_name,' ',last_name) AS   create_user, companies.name, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status,deliveries.tracking, {$this->db->dbprefix('sales')}.delivery_type, {$this->db->dbprefix('sales')}.attachment,DATE_FORMAT({$this->db->dbprefix('sales')}.date, '%Y-%m-%d %T') as date, {$this->db->dbprefix('sales')}.is_deliveries,{$this->db->dbprefix('sales')}.is_delete ,  companies.line, companies.facebook, {$this->db->dbprefix('sales')}.order_type")
					->from('sales')
					->join('deliveries', 'deliveries.sale_id = sales.id', 'left')
					->join('companies', 'companies.id = sales.customer_id', 'left')
                    ->join('users', 'users.biller_id = sales.biller_id', 'left')
                    ->where('sales.warehouse_id IN ('.$child_store.')');
                    //->where('sales.warehouse_id', $warehouse_id);
					//->where('sales.created_by', $this->session->userdata('user_id'))
					//->where('sales.warehouse_id IN ('.$child_store.')');
			}else{
				$this->datatables
					->select("{$this->db->dbprefix('sales')}.id as id , {$this->db->dbprefix('sales')}.cms_sync_web as cms_sync_web, reference_no, CONCAT(first_name,' ',last_name) AS create_user, companies.name, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status,deliveries.tracking, {$this->db->dbprefix('sales')}.delivery_type, {$this->db->dbprefix('sales')}.attachment,DATE_FORMAT({$this->db->dbprefix('sales')}.date, '%Y-%m-%d %T') as date, {$this->db->dbprefix('sales')}.is_deliveries,{$this->db->dbprefix('sales')}.is_delete ,  companies.line, companies.facebook, {$this->db->dbprefix('sales')}.order_type")
					->from('sales')
					->join('deliveries', 'deliveries.sale_id = sales.id', 'left')
					->join('companies', 'companies.id = sales.customer_id', 'left')
                    ->join('users', 'users.biller_id = sales.biller_id', 'left')
					->where('sales.warehouse_id', $warehouse_id)
					->or_where('sales.created_by', $this->session->userdata('user_id'));
			}
        } else {
            $this->datatables
                ->select("{$this->db->dbprefix('sales')}.id as id , {$this->db->dbprefix('sales')}.cms_sync_web as cms_sync_web, reference_no, CONCAT(first_name,' ',last_name) AS create_user, companies.name, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status,deliveries.tracking, {$this->db->dbprefix('sales')}.delivery_type, {$this->db->dbprefix('sales')}.attachment,DATE_FORMAT({$this->db->dbprefix('sales')}.date, '%Y-%m-%d %T') as date, {$this->db->dbprefix('sales')}.is_deliveries,{$this->db->dbprefix('sales')}.is_delete ,  companies.line, companies.facebook, {$this->db->dbprefix('sales')}.order_type")
                ->from('sales')
				->join('deliveries', 'deliveries.sale_id = sales.id', 'left')
                ->join('users', 'users.biller_id = sales.biller_id', 'left')
				->join('companies', 'companies.id = sales.customer_id', 'left');
        }

        if ($this->input->get('shop') == 'yes') {
            $this->datatables->where('shop', 1);
        } elseif ($this->input->get('shop') == 'no') {
            $this->datatables->where('shop !=', 1);
        }
		

        if ($this->input->get('delivery') == 'no') {
            $this->datatables->join('deliveries', 'deliveries.sale_id=sales.id', 'left')
            ->where('sales.sale_status', 'completed')->where('sales.payment_status', 'paid')
            ->where("({$this->db->dbprefix('deliveries')}.status != 'delivered' OR {$this->db->dbprefix('deliveries')}.status IS NULL)", NULL);
        }
        if ($this->input->get('attachment') == 'yes') {
            $this->datatables->where('sales.payment_status !=', 'paid')->where('sales.attachment !=', NULL);
        }
        $this->datatables->where('pos !=', 1); // ->where('sale_status !=', 'returned');
		//$this->session->userdata('view_right') = null;
		
		
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
           $this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('sales.customer_id', $this->session->userdata('user_id'));
        }

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }


    function popup($id = NULL)
    {
		$chk = $this->sales_model->chkPermissions($this->session->userdata('group_id'));
        $sale = $this->sales_model->getInvoiceByID($id);
		
		if ($this->Owner || $this->Admin || $chk['sales-payments'] == "1") {
			if ($sale->payment_status == 'paid' && $sale->grand_total == $sale->paid) {
				$this->data['show'] = 0;
			}else{
				$this->add_payment($id);
				$this->data['show'] = 1;
			};
		}
		
		#$this->data['userdata'] = $chk['sales-payments'];
		$this->data['id'] = $id;
        $this->data['pic'] = $this->sales_model->getattachmentByID($id);
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['error'] = validation_errors();
        $this->load->view($this->theme . 'sales/popup', $this->data);
	}
	
	
    function tracking_daily()
    {
		$date = date('Y-m-d');
		$this->data['date'] = $date;
		$created_by = "";
        if (!$this->Owner) {
            $created_by = $this->session->userdata('user_id');
        }
		$this->data['datatrack'] = $this->sales_model->trackingDay($date, $created_by);
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['error'] = validation_errors();
        $this->load->view($this->theme . 'sales/tracking_daily', $this->data);
	}
	
    function tracking_date()
    {
		$term = $this->input->get('term', true);
		$created_by = "";
        if (!$this->Owner) {
            $created_by = $this->session->userdata('user_id');
        }
		$data = $this->sales_model->trackingDate($term, $created_by);
        $this->sma->send_json($data);
    }
	
    function setdelete($id = NULL)
    {

        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->form_validation->set_rules('staff_note', lang("sales"), 'required|min_length[2]');
	
        if ($this->form_validation->run() == true) {
            $data = array(
				'id' => $this->input->post('id'),
                'staff_note' => $this->sma->clear_tags($this->input->post('staff_note')),
				'is_delete' => '1',
            );
        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("sales");
        }

        if ($this->form_validation->run() == true && $this->sales_model->Setdelete($id, $data)) {
			if ($this->Owner == "1") {
				$this->delete($id);
			}else{
				$this->session->set_flashdata('message', lang("notify_deleted_successfully"));
				admin_redirect("sales");
			}
        } else {
			$this->data['owner'] = $this->Owner;
			$this->data['staff_note'] = $this->sales_model->getSaleByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['error'] = validation_errors();
            $this->load->view($this->theme . 'sales/setdelete', $this->data);
        }
    }
	
	public function deliveriesdelete($id = NULL){
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
        }

        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
		
        $this->form_validation->set_rules('note', lang("sales"), 'required|min_length[2]');
	
        if ($this->form_validation->run() == true) {
            $data = array(
				'id' => $this->input->post('id'),
                'note' => $this->sma->clear_tags($this->input->post('note')),
				'is_delete' => '1',
            );
			if ($this->Owner == "1") {
				$this->sales_model->deleteDelivery($id);
			}
        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
             admin_redirect("sales");
        }
        if ($this->form_validation->run() == true && $this->sales_model->Setdeliveriesdelete($id, $data)) {
            $this->session->set_flashdata('message', lang("deleted_deliveries"));
             admin_redirect("sales/deliveries");
        } else {
			$this->data['note'] = $this->sales_model->getDeliveriesByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['error'] = validation_errors();
            $this->load->view($this->theme . 'sales/deliveriesdelete', $this->data);
        }
	}
	
    public function modal_view($id = null)
    {
        $this->sma->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by, true);
        }
		$created_by = $this->site->getUser($inv->created_by);
		$this->data['deliveries'] = $this->sales_model->getDeliveriesBySaleID($id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $created_by;
		$this->data['user_byemail'] = $this->sales_model->getUserByEmail($created_by->email);
		
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_sale'] = $inv->return_id ? $this->sales_model->getInvoiceByID($inv->return_id) : NULL;
        $this->data['return_rows'] = $inv->return_id ? $this->sales_model->getAllInvoiceItems($inv->return_id) : NULL;
		// [reference_no] 
		$this->data['point'] = $this->sales_model->getPointByReference($inv->reference_no);
		$this->data['totalpoint'] = $this->auth_model->totalpoint($inv->created_by);
		
        $this->load->view($this->theme . 'sales/modal_view', $this->data);
    }

    public function view($id = null)
    {
        $this->sma->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
		$bank = $this->sales_model->banktranfer();
		$bank_default = $this->sales_model->bankthailand();
		
        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by);
        }
		
		$created_by = $this->site->getUser($inv->created_by);
		$this->data['deliveries'] = $this->sales_model->getDeliveriesBySaleID($id);
        $this->data['barcode'] = "<img src='" . admin_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $created_by;
		
		$this->data['user_byemail'] = $this->sales_model->getUserByEmail($created_by->email);
		
		
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['bank'] = $bank;
		$this->data['bank_default'] = $bank_default;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_sale'] = $inv->return_id ? $this->sales_model->getInvoiceByID($inv->return_id) : NULL;
        $this->data['return_rows'] = $inv->return_id ? $this->sales_model->getAllInvoiceItems($inv->return_id) : NULL;
        $this->data['paypal'] = $this->sales_model->getPaypalSettings();
        $this->data['skrill'] = $this->sales_model->getSkrillSettings();
		$this->data['point'] = $this->sales_model->getPointByReference($inv->reference_no);
		$this->data['totalpoint'] = $this->auth_model->totalpoint($inv->created_by);
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_sales_details'), 'bc' => $bc);
        $this->page_construct('sales/view', $meta, $this->data);
    }

	
	
    public function sale_setting()
    {
		$prefix = $this->input->post('prefix');
		$digits = $this->input->post('digits');
		$start = $this->input->post('start');
		$max_count = $this->input->post('max_count');
		$current = $this->input->post('current');
		$data = [
			'prefix' 	=> $prefix,
			'digits'	=> $digits,
			'start'		=> $start,
			'max_count'	=> $max_count,
			'current'	=> $current,
		];
		
		if ($this->sales_model->updateSaleSetting($data)) {
            $this->session->set_flashdata('message', lang("sale_setting_update_success"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        }
	}
    public function pdf($id = null, $view = null, $save_bufffer = null)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);

		$bank_to = $inv->bank_to;
		$bank = $this->sales_model->getBanktranfer($bank_to);

		
		$sale_setting = $this->sales_model->getSaleSetting();
		$SalesReceipt = $this->sales_model->getSalesReceipt($id);

		if(isset($SalesReceipt->invoice_no)){
			$invoice_no = $SalesReceipt->invoice_no;
		}else{
			$sequence_no = sprintf("%0".$sale_setting->digits."d", $sale_setting->current);

			$invoice_no = $sale_setting->prefix.''.date("Ym", strtotime($inv->date)).$sequence_no;

			
			if ($this->sales_model->chkSalesReceipt($invoice_no)) {
				$this->session->set_flashdata('error', sprintf(lang("เลขที่ใบเสร็จรับเงิน %s ได้ถูกออกไปแล้ว รบกวนเปลี่ยนค่าใน เลขที่ใบเสร็จรับเงินปัจจุบัน"), $invoice_no));
				redirect($_SERVER["HTTP_REFERER"]);
			}

			
			$current_no = [
				'current' => $sale_setting->current + 1,
			];

			$sales_receipt = [
				'reference_no'	=> $inv->reference_no,
				'sale_id'		=> $inv->id,
				'invoice_no'	=> $invoice_no,
			];
			$this->sales_model->addSalesReceipt($sales_receipt);
			$this->sales_model->updateSaleSetting($current_no);
		}
		

        $order= [''];
        $header= [''];
        $customer= [''];
		$order_data = [''];
		$summary= [''];
		$bank_data= [''];

		$setting 		= $this->object_settings_model->getSettings();
		$order_no 		= !empty($inv->reference_no) ? $inv->reference_no : '';
		$_customer 		= $this->sales_model->getUserById($inv->customer_id);
		$company_name 	= $setting->company_name;
		$company_addr	= $setting->billing_addr;
		$phone			= $setting->billing_phone;
		$key			= $id;
		
        #foreach($query->result() as $key=>$row){
            $order[$key] = $inv;
            $header['company'] = [
                'logo' => base_url().'assets/images/logo.png',
                'name' => $company_name,
                'address' => $company_addr,
                'telephone' => $phone,
				'taxno' => $setting->taxno,
            ];
			
            $header['setting'] = array(
                'codeToBarCode' => "<img src='" . admin_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />",
				'orderdate' 	=> date("d-m-Y", strtotime($inv->date)),
				'orderno' 		=> $order_no,
				'invoiceno'		=> $invoice_no,
            );

            $customer = [
                'customerName' => $_customer['name'],
                'customerAddress' => $_customer['address'],
                'telephone' => $_customer['phone'],
				'customerTax' => $_customer['vat_no'],
				'customerCode' => $_customer['id'],
            ];

			$i = 0;
			$sale_items = $this->sales_model->getAllInvoiceItems($id);
            foreach($sale_items as  $sale_item){
                $order_data[$i++] = [
					'orderCode' => $sale_item->product_code,
					'description' => $sale_item->details ? $sale_item->details : $sale_item->product_name,
					'quantity' => intval($sale_item->quantity),
					'price' => number_format($sale_item->unit_price,2),
					'discount' => number_format($sale_item->discount ? $sale_item->discount : 0,2),
					'total' => number_format($sale_item->subtotal,2),
				];
            }
			//for becurve เท่านั้น
			$grand_total = $inv->total + $inv->shipping;
			$summary = [
				//'cod'		=>	"",
				'total'				=>	 number_format($inv->total,2),
				'vat'				=>	 number_format($inv->total*7/100, 2),
				'shipping'			=>	 number_format($inv->shipping, 2),
				'discount'			=>	 number_format($inv->total_discount, 2),
				'vat_type'			=>	"include",
				'grand_total'		=>	 number_format($grand_total, 2),
				
				
			];
			$bank_data = [
				 'bank' => $bank->bank,
				 'account_name' => $bank->account_name,
				 'account_number'=> $bank->account_number,
				 'nickname' => $bank->nickname,
			];
        #}
        $data = [
            'order' 	=> $order_data,
            'header' 	=> $header,
            'customer' 	=> $customer,
			'summary' 	=> $summary,
			'bank'		=> $bank_data,
        ];
		
		$html = $this->load->view($this->theme.'mpdf/receipt', $data, TRUE);
		$mpdf = new mPDF('utf-8', 'A4', '0', 'thsarabun');
		$mpdf->SetTitle('receipt pdf');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }


    public function combine_pdf($sales_id)
    {
        $this->sma->checkPermissions('pdf');

        foreach ($sales_id as $id) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->sales_model->getInvoiceByID($id);
            if (!$this->session->userdata('view_right')) {
                $this->sma->view_rights($inv->created_by);
            }
			$created_by = $this->site->getUser($inv->created_by);
			$this->data['user_byemail'] = $this->sales_model->getUserByEmail($created_by->email);
            $this->data['barcode'] = "<img src='" . admin_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
            $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
            $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
            $this->data['user'] = $this->site->getUser($inv->created_by);
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['inv'] = $inv;
            $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
            $this->data['return_sale'] = $inv->return_id ? $this->sales_model->getInvoiceByID($inv->return_id) : NULL;
            $this->data['return_rows'] = $inv->return_id ? $this->sales_model->getAllInvoiceItems($inv->return_id) : NULL;
            $html_data = $this->load->view($this->theme . 'sales/pdf', $this->data, true);
            if (! $this->Settings->barcode_img) {
                $html_data = preg_replace("'\<\?xml(.*)\?\>'", '', $html_data);
            }

            $html[] = array(
                'content' => $html_data,
                'footer' => $this->data['biller']->invoice_footer,
            );
        }

        $name = "sales" . ".pdf";
	
        $this->sma->generate_pdf($html, $name);

    }

    public function email($id = null)
    {
        $this->sma->checkPermissions(false, true);
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->form_validation->set_rules('to', lang("to") . " " . lang("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', lang("cc"), 'trim|valid_emails');
        $this->form_validation->set_rules('bcc', lang("bcc"), 'trim|valid_emails');
        $this->form_validation->set_rules('note', lang("message"), 'trim');

        if ($this->form_validation->run() == true) {
            if (!$this->session->userdata('view_right')) {
                $this->sma->view_rights($inv->created_by);
            }
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = null;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = null;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $biller = $this->site->getCompanyByID($inv->biller_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $biller->logo . '" alt="' . ($biller->company != '-' ? $biller->company : $biller->name) . '"/>',
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            $paypal = $this->sales_model->getPaypalSettings();
            $skrill = $this->sales_model->getSkrillSettings();
            $btn_code = '<div id="payment_buttons" class="text-center margin010">';
            if ($paypal->active == "1" && $inv->grand_total != "0.00") {
                if (trim(strtolower($customer->country)) == $biller->country) {
                    $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_my / 100);
                } else {
                    $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_other / 100);
                }
                $btn_code .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=' . $paypal->account_email . '&item_name=' . $inv->reference_no . '&item_number=' . $inv->id . '&image_url=' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '&amount=' . (($inv->grand_total - $inv->paid) + $paypal_fee) . '&no_shipping=1&no_note=1&currency_code=' . $this->default_currency->code . '&bn=FC-BuyNow&rm=2&return=' . admin_url('sales/view/' . $inv->id) . '&cancel_return=' . admin_url('sales/view/' . $inv->id) . '&notify_url=' . admin_url('payments/paypalipn') . '&custom=' . $inv->reference_no . '__' . ($inv->grand_total - $inv->paid) . '__' . $paypal_fee . '"><img src="' . base_url('assets/images/btn-paypal.png') . '" alt="Pay by PayPal"></a> ';

            }
            if ($skrill->active == "1" && $inv->grand_total != "0.00") {
                if (trim(strtolower($customer->country)) == $biller->country) {
                    $skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_my / 100);
                } else {
                    $skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_other / 100);
                }
                $btn_code .= ' <a href="https://www.moneybookers.com/app/payment.pl?method=get&pay_to_email=' . $skrill->account_email . '&language=EN&merchant_fields=item_name,item_number&item_name=' . $inv->reference_no . '&item_number=' . $inv->id . '&logo_url=' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '&amount=' . (($inv->grand_total - $inv->paid) + $skrill_fee) . '&return_url=' . admin_url('sales/view/' . $inv->id) . '&cancel_url=' . admin_url('sales/view/' . $inv->id) . '&detail1_description=' . $inv->reference_no . '&detail1_text=Payment for the sale invoice ' . $inv->reference_no . ': ' . $inv->grand_total . '(+ fee: ' . $skrill_fee . ') = ' . $this->sma->formatMoney($inv->grand_total + $skrill_fee) . '&currency=' . $this->default_currency->code . '&status_url=' . admin_url('payments/skrillipn') . '"><img src="' . base_url('assets/images/btn-skrill.png') . '" alt="Pay by Skrill"></a>';
            }

            $btn_code .= '<div class="clearfix"></div>
    </div>';
            $message = $message . $btn_code;

            $attachment = $this->pdf($id, null, 'S');
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->sma->send_email($to, $subject, $message, null, null, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->session->set_flashdata('message', lang("email_sent"));
            admin_redirect("sales");
        } else {

            if (file_exists('./themes/' . $this->Settings->theme . '/admin/views/email_templates/sale.html')) {
                $sale_temp = file_get_contents('themes/' . $this->Settings->theme . '/admin/views/email_templates/sale.html');
            } else {
                $sale_temp = file_get_contents('./themes/default/admin/views/email_templates/sale.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('invoice') . ' (' . $inv->reference_no . ') ' . lang('from') . ' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $sale_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/email', $this->data);
			
        }
    }

    /* ------------------------------------------------------------------ */

    public function add($quote_id = null)
    {
        $this->sma->checkPermissions();
        $sale_id = $this->input->get('sale_id') ? $this->input->get('sale_id') : NULL;
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
		$bank = $this->sales_model->getbanktransfer($this->session->userdata('user_id'));
		$salebrand = $this->sales_model->getSaleBrand();
		$vendor_inventory = $this->auth_model->getAgent($this->session->userdata('user_id'));
		
		$bank_default = $this->sales_model->bankthailand();
        if ($this->form_validation->run() == true) {
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');
            if ($this->Owner || $this->Admin) {
                #$date = $this->sma->fld(trim($this->input->post('date')));
				$date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$order_type = $this->input->post('order_type');

			$delivery_type = $this->input->post('shippingtitle');
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $payment_term = $this->input->post('payment_term');
			$bank_from = $this->input->post('bank_from');
			$bank_to = $this->input->post('bank_to');
			$date_cf_payment = $this->input->post('date_cf_payment');
			$time_cf_payment = $this->input->post('time_cf_payment');
			$total_cf_payment = $this->input->post('total_cf_payment');
			$award_points = $this->input->post('award_points');
			$wallet_use = $this->input->post('payment_method');

            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);

            $biller = $biller_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));
            $quote_id = $this->input->post('quote_id') ? $this->input->post('quote_id') : null;

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $digital = FALSE;
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : null;
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_unit_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = $_POST['product_base_quantity'][$r];

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : null;
                    // $unit_price = $real_unit_price;
                    $pr_discount = 0;
                    if ($item_type == 'digital') {
                        $digital = TRUE;
                    }

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = $this->sma->formatDecimal(((($this->sma->formatDecimal($unit_price)) * (Float) ($pds[0])) / 100), 4);
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount);
                        }
                    }

                    $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                        $tax_details = $this->site->getTaxRateByID($item_tax_rate);
                        $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_price);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if (!empty($product_details) && $product_details->tax_method != 1) {
                            $item_net_price = $unit_price - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_unit_quantity), 4);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
                    $unit = $this->site->getUnitByID($item_unit);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $unit ? $unit->id : NULL,
                        'product_unit_code' => $unit ? $unit->code : NULL,
                        'unit_quantity' => $item_unit_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                    );
					
					//$this->sales_model->updateQuantitySale($item_option, $warehouse_id, $item_quantity, $item_id);
                    $total += $this->sma->formatDecimal(($item_net_price * $item_unit_quantity), 4);
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal(((($total + $product_tax) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');

                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    } elseif ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal(((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }
			foreach($bank as $key => $value){
				if($key == $bank_to){
					$value_bank_to = $value;
				}
			}
			foreach($bank_default as $key => $value){
				if($key == $bank_from){
					$value_bank_from = $value;
				}
			}

			if($delivery_type == "kerry"):
				$order_status_history = "เก็บเงินปลายทาง Kerry(COD)";
			else:
				$order_status_history = 
				lang('ชำระเงินโดย : ') . $customer . "\n\r<br/>" .
				lang('โอนเข้าบัญชี : ') . $value_bank_to . "\n\r<br/>" .
				lang('วัน :  ') .  date('d/m/Y', strtotime($date_cf_payment)). lang('  เวลา : ') .  $time_cf_payment. "\n\r<br/>" .
				lang('ยอดโอน : ') . $total_cf_payment . "\n\r<br/>";	
			endif;

            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4); 
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount), 4);
			$create_user = $this->site->getUser($this->session->userdata('user_id'));

			
			$addr = $customer_details->address;
			preg_match("/\s+\d{5}?\b/", $addr, $matches);
			if(strlen(trim($matches[0])) <= 0){
				strip_tags($addr);
				preg_match("/\b\d{5}\b(?!.*\b\d{5}\b)/", $addr, $matches);
			}
			$zipcode = $matches[0];

			
			/* fix to child_user */
			$user_data_id = $this->session->userdata('user_id');
			if($this->input->post('sell_child_name') != ""){
				$sell_cihld_name = $this->input->post('sell_child_name');
				$child_user = $this->site->getUser($sell_cihld_name);
				$warehouse_id = $child_user->warehouse_id;
				$biller_id = $child_user->biller_id;
				$biller_details = $this->site->getCompanyByID($biller_id);
				$biller = $biller_details->name;
				$create_user = $this->site->getUser($child_user->id);
				$user_data_id = $child_user->id;
			}
		
			
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => 0,
                'created_by' => $user_data_id,
                'hash' => hash('sha256', microtime() . mt_rand()),

				'order_type' => $order_type,
				'order_status_history' => $order_status_history,
                //'bank_from' => $bank_from,
				'bank_to' => $bank_to,

				'date_cf_payment' => date('Y-m-d H:i:s', strtotime($date_cf_payment." ".$time_cf_payment)), // 2017-11-08 12:15:04
				'total_cf_payment' => $total_cf_payment,
				'delivery_type' => $delivery_type,
				'create_user' => $create_user->first_name . ' ' . $create_user->last_name,
				'zipcode' => trim($zipcode),
				'wisdom_id' => $create_user->parent_id,
            );

            if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'deposit') {
                    if ( ! $this->site->check_customer_deposit($customer_id, $this->input->post('amount-paid'))) {
                        $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;
                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->sma->formatDecimal($amount_paying),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('gift_card_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received',
                        'gc_balance' => $gc_balance,
                    );
                } else {
                    $payment = array(
                        'date' => $date,
                        'reference_no' => $this->input->post('payment_reference_no'),
                        'amount' => $this->sma->formatDecimal($this->input->post('amount-paid')),
                        'paid_by' => $this->input->post('paid_by'),
                        'cheque_no' => $this->input->post('cheque_no'),
                        'cc_no' => $this->input->post('pcc_no'),
                        'cc_holder' => $this->input->post('pcc_holder'),
                        'cc_month' => $this->input->post('pcc_month'),
                        'cc_year' => $this->input->post('pcc_year'),
                        'cc_type' => $this->input->post('pcc_type'),
                        'created_by' => $this->session->userdata('user_id'),
                        'note' => $this->input->post('payment_note'),
                        'type' => 'received',
                    );
                }
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
            // $this->sma->print_arrays($data, $products, $payment); updateQuantitySale
        }

		$limitsale = $this->sales_model->getLimitSale();
		$limitsale_total = $limitsale->total_reference;
		$setting = $this->object_settings_model->getSettings();
		

		#max_limit
		if($limitsale_total >= $setting->limitsale){
			$this->session->set_flashdata('error', sprintf(lang("your_order_has_maximum_limits_%s"), $setting->limitsale));
			redirect($_SERVER["HTTP_REFERER"]);
		} else {
			
			
			if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment)) {
				$this->session->set_userdata('remove_slls', 1);

					if ($quote_id) {
						$this->db->update('quotes', array('status' => 'completed'), array('id' => $quote_id));
					}
					
					// $point_data = array(
						// 'reference_no' => $reference,
						// 'user_id' => $this->session->userdata('user_id'),
						// 'points_current' => $award_points,
						// 'date_insertion' => $date,
						// );
					
					// if ($this->Settings->award_use) {
						// $this->sales_model->addPoint($point_data);
					// }
					
					$wallet_data = array(
						'user_id' => $this->session->userdata('user_id'),
						'sale_id' => $reference,
						'wallet_amount' => $grand_total,
						'wallet_type' => 'withdraw',
						'upload_slip' => ''
					);
					$this->sales_model->use_wallet($wallet_data);
					// $chk = 0;
					// for ($r = 0; $r < $i; $r++) {
						// $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : null;
						// $item_quantity = $_POST['product_base_quantity'][$r];
						// $item_id = $_POST['product_id'][$r];
						// $chk += $item_quantity;
						// $this->sales_model->updateQuantitySale($item_option, $warehouse_id, $item_quantity, $item_id);
					// }

					
					$this->session->set_flashdata('message', lang("sale_added"));
					admin_redirect("sales");

			} else {
				if ($quote_id || $sale_id) {
					if ($quote_id) {
						$this->data['quote'] = $this->sales_model->getQuoteByID($quote_id);
						$items = $this->sales_model->getAllQuoteItems($quote_id);
					} elseif ($sale_id) {
						$this->data['quote'] = $this->sales_model->getInvoiceByID($sale_id);
						$items = $this->sales_model->getAllInvoiceItems($sale_id);
					}
					krsort($items);
					$c = rand(100000, 9999999);
					foreach ($items as $item) {
						$row = $this->site->getProductByID($item->product_id);
						if (!$row) {
							$row = json_decode('{}');
							$row->tax_method = 0;
						} else {
							unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
						}
						$row->quantity = 0;
						
						$pis = $this->site->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
						if ($pis) {
							foreach ($pis as $pi) {
								$row->quantity += $pi->quantity_balance;
							}
						}
						
						$row->id = $item->product_id;
						$row->code = $item->product_code;
						$row->name = $item->product_name;
						$row->type = $item->product_type;
						$row->qty = $item->quantity;
						$row->base_quantity = $item->quantity;
						$row->base_unit = $row->unit ? $row->unit : $item->product_unit_id;
						$row->base_unit_price = $row->price ? $row->price : $item->unit_price;
						$row->unit = $item->product_unit_id;
						$row->qty = $item->unit_quantity;
						$row->discount = $item->discount ? $item->discount : '0';
						$row->price = $this->sma->formatDecimal($item->net_unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity));
						$row->unit_price = $row->tax_method ? $item->unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity) + $this->sma->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
						$row->real_unit_price = $item->real_unit_price;
						$row->tax_rate = $item->tax_rate_id;
						$row->serial = '';
						$row->option = $item->option_id;
						$options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
						if ($options) {
							$option_quantity = 0;
							foreach ($options as $option) {
								$pis = $this->site->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
								if ($pis) {
									foreach ($pis as $pi) {
										$option_quantity += $pi->quantity_balance;
									}
								}
								if ($option->quantity > $option_quantity) {
									$option->quantity = $option_quantity;
								}
							}
						}
						$combo_items = false;
						if ($row->type == 'combo') {
							$combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
						}
						$units = $this->site->getUnitsByBUID($row->base_unit);
						$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
						$ri = $this->Settings->item_addition ? $row->id : $c;
					   
						$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 
								'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);
						$c++;
					}
					$this->data['quote_items'] = json_encode($pr);
				}
		}

			$this->data['shippingtitle'] = $this->sales_model->shippingtitle($this->Settings->default_shipping);
			//$this->data['shippingtitle'] = $this->Settings->default_shipping;

			$this->data['setting'] = $this->object_settings_model->getSettings();
			$this->data['customeridbyuser'] = $this->sales_model->customeridbyuser();
			$this->data['vendor_inventory'] = $vendor_inventory;
			$this->data['bank'] = $bank;
			$this->data['salebrand'] = $salebrand;
			$this->data['bank_default'] = $bank_default;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id ? $quote_id : $sale_id;
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$current_user = $this->site->getUserByWarehouse($this->session->userdata('warehouse_id'));
		  $this->data['current_user_id'] = $current_user;
		  
		  $this->data['userbyparent'] = $this->site->getUserByParent($current_user->id);
            //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
            $this->data['payment_ref'] = ''; //$this->site->getReference('pay');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale')));
            $meta = array('page_title' => lang('add_sale'), 'bc' => $bc);
            $this->page_construct('sales/add', $meta, $this->data);

        }


    }

    /* ------------------------------------------------------------------------ */

    public function edit($id = null)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->sales_model->getInvoiceByID($id);
        if ($inv->sale_status == 'returned' || $inv->return_id || $inv->return_sale_ref) {
            $this->session->set_flashdata('error', lang('sale_x_action'));
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
    /*    if (!$this->session->userdata('edit_right')) {
            $this->sma->view_rights($inv->created_by);
        }*/
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
		
		$bank = $this->sales_model->banktranfer();
		$bank_default = $this->sales_model->bankthailand();
        if ($this->form_validation->run() == true) {

            $reference = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = $inv->date;
            }
			$order_type = $this->input->post('order_type');
			
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            #$payment_status = $inv->payment_status;
			
			$delivery_type = $this->input->post('shippingtitle');
            $payment_term = $this->input->post('payment_term');
			$bank_from = $this->input->post('bank_from');
			$bank_to = $this->input->post('bank_to');
			$date_cf_payment = $this->input->post('date_cf_payment');
			$time_cf_payment = $this->input->post('time_cf_payment');
			$total_cf_payment = $this->input->post('total_cf_payment');


            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = !empty($customer_details->company) && $customer_details->company != '-'  ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = !empty($biller_details->company) && $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : null;
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_unit_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = $_POST['product_base_quantity'][$r];

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : null;
                    // $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = $this->sma->formatDecimal(((($this->sma->formatDecimal($unit_price)) * (Float) ($pds[0])) / 100), 4);
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount);
                        }
                    }

                    $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                        $tax_details = $this->site->getTaxRateByID($item_tax_rate);
                        $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_price);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if (!empty($product_details) && $product_details->tax_method != 1) {
                            $item_net_price = $unit_price - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_unit_quantity), 4);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
                    $unit = $this->site->getUnitByID($item_unit);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $unit ? $unit->id : NULL,
                        'product_unit_code' => $unit ? $unit->code : NULL,
                        'unit_quantity' => $item_unit_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                    );

                    $total += $this->sma->formatDecimal(($item_net_price * $item_unit_quantity), 4);
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal(((($total + $product_tax) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal(((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }

			foreach($bank as $key => $value){
				if($key == $bank_to){
					$value_bank_to = $value;
				}
			}
			foreach($bank_default as $key => $value){
				if($key == $bank_from){
					$value_bank_from = $value;
				}
			}

			if($delivery_type == "kerry"):
				$order_status_history = "เก็บเงินปลายทาง Kerry(COD)";
			else:
				$order_status_history = 
				lang('ชำระเงินโดย : ') . $customer . "\n\r<br/>" .
				lang('โอนเข้าบัญชี : ') . $value_bank_to . "\n\r<br/>" .
				lang('วัน :  ') .  date('d/m/Y', strtotime($date_cf_payment)). lang('  เวลา : ') .  $time_cf_payment. "\n\r<br/>" .
				lang('ยอดโอน : ') . $total_cf_payment . "\n\r<br/>";	
			endif;
			
            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount), 4);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
				'order_type' => $order_type,
				'order_status_history' => $order_status_history,
                //'bank_from' => $bank_from,
				'bank_to' => $bank_to,
				'date_cf_payment' => $date_cf_payment." ".$time_cf_payment,
				'total_cf_payment' => $total_cf_payment,
				//'shippingtitle' => $this->input->post('shippingtitle'),
				'delivery_type' => $delivery_type,
            );

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->sma->print_arrays($data, $products);
			
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateSale($id, $data, $products)) {
            #$this->session->set_userdata('remove_slls', 1);
            #$this->session->set_flashdata('message', lang("sale_updated"));
            admin_redirect($inv->pos ? 'pos/sales' : 'sales');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
            if ($this->Settings->disable_editing) {
                if ($this->data['inv']->date <= date('Y-m-d', strtotime('-'.$this->Settings->disable_editing.' days'))) {
                    $this->session->set_flashdata('error', sprintf(lang("sale_x_edited_older_than_x_days"), $this->Settings->disable_editing));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }
            $inv_items = $this->sales_model->getAllInvoiceItems($id);
            krsort($inv_items);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
                }
                $pis = $this->site->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->base_quantity = $item->quantity;
                $row->base_unit = !empty($row->unit) ? $row->unit : $item->product_unit_id;
                $row->base_unit_price = !empty($row->price) ? $row->price : $item->unit_price;
                $row->unit = $item->product_unit_id;
                $row->qty = $item->unit_quantity;
                $row->quantity += $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->sma->formatDecimal($item->net_unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity) + $this->sma->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
                $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);

                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->site->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        $option_quantity += $item->quantity;
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity = $combo_item->qty * $item->quantity;
                    }
                }
                $units = !empty($row->base_unit) ? $this->site->getUnitsByBUID($row->base_unit) : NULL;
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $ri = $this->Settings->item_addition ? $row->id : $c;
            //    $product_wholesales = $this->products_model->getProductWholesales($item->product_id);  
				
                $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 
                    'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);
                $c++;
            }
			$this->data['shippingtitle'] = $this->sales_model->shippingtitle($this->Settings->default_shipping);
			$this->data['bank'] = $bank;
			$this->data['bank_default'] = $bank_default;
			
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('biller') : null;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $this->site->getAllWarehouses();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('edit_sale')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('sales/edit', $meta, $this->data);
        }
    }

    /* ------------------------------- */

    public function return_sale($id = null)
    {
        $this->sma->checkPermissions('return_sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $sale = $this->sales_model->getInvoiceByID($id);
        if ($sale->return_id) {
            $this->session->set_flashdata('error', lang("sale_already_returned"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('return_surcharge', lang("return_surcharge"), 'required');

        if ($this->form_validation->run() == true) {

            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('re');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->sma->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $sale_item_id = $_POST['sale_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' && $_POST['product_option'][$r] != 'null' ? $_POST['product_option'][$r] : null;
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_unit_quantity = (0-$_POST['quantity'][$r]);
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : null;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : null;
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = (0-$_POST['product_base_quantity'][$r]);

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : null;
                    // $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = $this->sma->formatDecimal(((($this->sma->formatDecimal($unit_price)) * (Float) ($pds[0])) / 100), 4);
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount, 4);
                        }
                    }

                    $unit_price = $this->sma->formatDecimal(($unit_price - $pr_discount), 4);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_unit_quantity, 4);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {

                        $tax_details = $this->site->getTaxRateByID($item_tax_rate);
                        $ctax = $this->site->calculateTax($product_details, $tax_details, $unit_price);
                        $item_tax = $ctax['amount'];
                        $tax = $ctax['tax'];
                        if ($product_details->tax_method != 1) {
                            $item_net_price = $unit_price - $item_tax;
                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_unit_quantity), 4);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = $this->sma->formatDecimal((($item_net_price * $item_unit_quantity) + $pr_item_tax), 4);
                    $unit = $item_unit ? $this->site->getUnitByID($item_unit) : FALSE;

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit ? $unit->code : NULL,
                        'unit_quantity' => $item_unit_quantity,
                        'warehouse_id' => $sale->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $item_tax_rate,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->sma->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                        'sale_item_id' => $sale_item_id,
                    );

                    $si_return[] = array(
                        'id' => $sale_item_id,
                        'sale_id' => $id,
                        'product_id' => $item_id,
                        'option_id' => $item_option,
                        'quantity' => (0-$item_quantity),
                        'warehouse_id' => $sale->warehouse_id,
                        );

                    $total += $this->sma->formatDecimal(($item_net_price * $item_unit_quantity), 4);
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal(((($total + $product_tax) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id, 4);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal(((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $this->sma->formatDecimal($product_tax + $order_tax, 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($return_surcharge) - $order_discount), 4);
            $data = array('date' => $date,
                'sale_id' => $id,
                'reference_no' => $sale->reference_no,
                'customer_id' => $sale->customer_id,
                'customer' => $sale->customer,
                'biller_id' => $sale->biller_id,
                'biller' => $sale->biller,
                'warehouse_id' => $sale->warehouse_id,
                'note' => $note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'surcharge' => $this->sma->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id'),
                'return_sale_ref' => $reference,
                'sale_status' => 'returned',
                'pos' => $sale->pos,
                'payment_status' => $sale->payment_status == 'paid' ? 'due' : 'pending',
            );
            if ($this->input->post('amount-paid') && $this->input->post('amount-paid') > 0) {
                $pay_ref = $this->input->post('payment_reference_no') ? $this->input->post('payment_reference_no') : $this->site->getReference('pay');
                $payment = array(
                    'date' => $date,
                    'reference_no' => $pay_ref,
                    'amount' => (0-$this->input->post('amount-paid')),
                    'paid_by' => $this->input->post('paid_by'),
                    'cheque_no' => $this->input->post('cheque_no'),
                    'cc_no' => $this->input->post('pcc_no'),
                    'cc_holder' => $this->input->post('pcc_holder'),
                    'cc_month' => $this->input->post('pcc_month'),
                    'cc_year' => $this->input->post('pcc_year'),
                    'cc_type' => $this->input->post('pcc_type'),
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'returned',
                );
                $data['payment_status'] = $grand_total == $this->input->post('amount-paid') ? 'paid' : 'partial';
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            // $this->sma->print_arrays($data, $products, $si_return, $payment);
        }

        if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment, $si_return)) {
            $this->session->set_flashdata('message', lang("return_sale_added"));
            admin_redirect($sale->pos ? "pos/sales" : "sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $sale;
            if ($this->data['inv']->sale_status != 'completed') {
                $this->session->set_flashdata('error', lang("sale_status_x_competed"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("sale_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $inv_items = $this->sales_model->getAllInvoiceItems($id);
            krsort($inv_items);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
                }
                $pis = $this->site->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->sale_item_id = $item->id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->base_quantity = $item->quantity;
                $row->base_unit = $row->unit ? $row->unit : $item->product_unit_id;
                $row->base_unit_price = $row->price ? $row->price : $item->unit_price;
                $row->unit = $item->product_unit_id;
                $row->qty = $item->quantity;
                $row->oqty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->sma->formatDecimal($item->net_unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity) + $this->sma->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
                $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id, true);
                $units = $this->site->getUnitsByBUID($row->base_unit);
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $ri = $this->Settings->item_addition ? $row->id : $c;

                $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'units' => $units, 'tax_rate' => $tax_rate, 'options' => $options);
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['payment_ref'] = '';
            $this->data['reference'] = ''; // $this->site->getReference('re');
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('return_sale')));
            $meta = array('page_title' => lang('return_sale'), 'bc' => $bc);
            $this->page_construct('sales/return_sale', $meta, $this->data);
        }
    }

    /* ------------------------------- */

    public function delete($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->sales_model->getInvoiceByID($id);
        if ($inv->sale_status == 'returned') {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("sale_x_action")));
        }

        if ($this->sales_model->deleteSale($id)) {
            if ($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => lang("sale_deleted")));
            }
            $this->session->set_flashdata('message', lang('sale_deleted'));
            admin_redirect('welcome');
        }
    }
	

    public function delete_return($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteReturn($id)) {
            if ($this->input->is_ajax_request()) {
                $this->sma->send_json(array('error' => 0, 'msg' => lang("return_sale_deleted")));
            }
            $this->session->set_flashdata('message', lang('return_sale_deleted'));
            admin_redirect('welcome');
        }
    }

    public function sale_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSale($id);
                    }
                    $this->session->set_flashdata('message', lang("sales_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {
                    $html = $this->combine_pdf($_POST['val']);
				} elseif ($this->input->post('form_action') == 'ship_soko') {
                    $html = $this->ship_soko($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('create_user'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getInvoiceByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->create_user);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($sale->paid));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($sale->payment_status));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
					ob_end_clean();
                    return create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* ------------------------------- */

    public function deliveries()
    {
        $this->sma->checkPermissions();

		$this->data['ready'] = $this->sales_model->getDeliveriesReady();
		$this->data['packing'] = $this->sales_model->getDeliveriesPacking();
		$this->data['delivering'] = $this->sales_model->getDeliveriesDelivering();
		$this->data['delivered'] = $this->sales_model->getDeliveriesDelivered();

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('deliveries')));
        $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        $this->page_construct('sales/deliveries', $meta, $this->data);
    }

    public function fnready(){
        $this->sma->checkPermissions('deliveries');
        $detail_link = anchor('admin/sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_delivery/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');


        $this->datatables
            ->select("
			deliveries.id  as id,
			deliveries.sale_reference_no,
			deliveries.customer,
			deliveries.phone as phone,
			deliveries.address,
			deliveries.shipping_type as delivery_type,
			sales.order_type,
			status,
			deliveries.date
			")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
			->join('sales', 'sales.id = deliveries.sale_id', 'left')
			->join('companies', 'companies.id = sales.customer_id', 'left')
			->where('deliveries.status', 'ready')
            ->group_by('deliveries.id');
			
			
			
		#if ($this->Owner || $this->Admin) {
		if (!$this->Owner) {
            $this->datatables->where('deliveries.created_by', $this->session->userdata('user_id'));
		}
		
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
	}
	
	
    public function fnpacking(){
        $this->sma->checkPermissions('deliveries');
        $detail_link = anchor('admin/sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_delivery/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')
        $this->datatables
            ->select("
			deliveries.id  as id,
			deliveries.sale_reference_no,
			deliveries.customer,
			deliveries.phone as phone,
			deliveries.address,
			deliveries.shipping_type as delivery_type,
			sales.order_type,
			status,
			deliveries.date
			")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
			->join('sales', 'sales.id = deliveries.sale_id', 'left')
			->join('companies', 'companies.id = sales.customer_id', 'left')
			->where('status', 'packing')
            ->group_by('deliveries.id');
		if (!$this->Owner) {
            $this->datatables->where('deliveries.created_by', $this->session->userdata('user_id'));
		}
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
	}
	
	
	
	public function fndelivering(){
        $this->sma->checkPermissions('deliveries');

        $detail_link = anchor('admin/sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_delivery/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')
        $this->datatables
            ->select("
			deliveries.id  as id,
			deliveries.sale_reference_no,
			deliveries.customer,
			deliveries.phone as phone,
			deliveries.address,
			deliveries.tracking,
			deliveries.shipping_type as delivery_type,
			sales.order_type,
			status,
			deliveries.date
			")
			
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
			->join('sales', 'sales.id = deliveries.sale_id', 'left')
			->join('companies', 'companies.id = sales.customer_id', 'left')
			->where('status', 'delivering')
            ->group_by('deliveries.id');
		if (!$this->Owner) {
            $this->datatables->where('deliveries.created_by', $this->session->userdata('user_id'));
		}
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
	}
	
	
	public function fndelivered(){
        $this->sma->checkPermissions('deliveries');

        $detail_link = anchor('admin/sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_delivery/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')
        $this->datatables
            ->select("
			deliveries.id  as id,
			deliveries.sale_reference_no,
			deliveries.customer,
			deliveries.phone as phone,
			deliveries.address,
			deliveries.tracking,
			deliveries.shipping_type as delivery_type,
			sales.order_type,
			status,
			deliveries.date
			")

			->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
			->join('sales', 'sales.id = deliveries.sale_id', 'left')
			->join('companies', 'companies.id = sales.customer_id', 'left')
			->where('status', 'delivered')
            ->group_by('deliveries.id');
		if (!$this->Owner) {
            $this->datatables->where('deliveries.created_by', $this->session->userdata('user_id'));
		}
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
	}
	
	
	
    public function getDeliveries()
    {
        $this->sma->checkPermissions('deliveries');

        $detail_link = anchor('admin/sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('admin/sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('admin/sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('admin/sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_delivery/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn  btn-xs btn-success dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $detail_link . '</li>
				<li>' . $edit_link . '</li>
				<li>' . $pdf_link . '</li>
				<li>' . $delete_link . '</li>
			</ul>
		</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')
        $this->datatables
			->select("deliveries.id as id, sale_reference_no, customer, address, status")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
            ->group_by('deliveries.id');
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
    }

    public function pdf_delivery($id = null, $view = null, $save_bufffer = null)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);

        $name = lang("delivery") . "_" . str_replace('/', '_', $deli->do_reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf_delivery', $this->data, true);
        if (! $this->Settings->barcode_img) {
            $html = preg_replace("'\<\?xml(.*)\?\>'", '', $html);
        }
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf_delivery', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sma->generate_pdf($html, $name);
        }
    }

	public function pdf_delivery_all($id_arr = null, $view = null, $save_bufffer = null){
		 $html = array();
		 foreach($id_arr as $id){
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$deli = $this->sales_model->getDeliveryByID($id);

			$this->data['delivery'] = $deli;
			$sale = $this->sales_model->getInvoiceByID($deli->sale_id);
			$this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
			$this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
			$this->data['user'] = $this->site->getUser($deli->created_by);
			$name = lang("delivery") . "_" . str_replace('/', '_', $deli->do_reference_no) . ".pdf";
			array_push($html,$this->load->view($this->theme . 'sales/pdf_delivery', $this->data, true));

		}
		$this->sma->generate_pdf($html, $name);
	}

	
    public function view_delivery($id = null)
    {
        $this->sma->checkPermissions('deliveries');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
	   $setting = $this->object_settings_model->getSettings();

	   
        // if (!$sale) {
            // $this->session->set_flashdata('error', lang('sale_not_found'));
            // $this->sma->md();
        // }
		
		
        $this->data['delivery'] = $deli;
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);
        $this->data['page_title'] = lang("delivery_order");
		
	   if(strpos($deli->sale_reference_no,$setting->purchase_prefix)>"-1"){
		   $this->data['rows'] = $this->purchases_model->getPurchaseItemsById($deli->sale_id);
	   }else{
		   $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
	   }

        $this->load->view($this->theme . 'sales/view_delivery', $this->data);
    }

	
    public function add_delivery($id = null)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $sale = $this->sales_model->getInvoiceByID($id);
        if ($sale->sale_status != 'completed') {
            $this->session->set_flashdata('error', lang('status_is_x_completed'));
            $this->sma->md();
        }

        if ($delivery = $this->sales_model->getDeliveryBySaleID($id)) {
            $this->edit_delivery($delivery->id);
        } else {

            $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
            $this->form_validation->set_rules('customer', lang("customer"), 'required');
            $this->form_validation->set_rules('address', lang("address"), 'required');

            if ($this->form_validation->run() == true) {
                if ($this->Owner || $this->Admin) {
                    $date = $this->sma->fld(trim($this->input->post('date')));
                } else {
                    $date = date('Y-m-d H:i:s');
                }
				#date_default_timezone_set("Asia/Bangkok");
				$customer = $this->sales_model->getUserById($sale->customer_id);
				
                $dlDetails = array(
                    'date' => $date,
                    'sale_id' => $this->input->post('sale_id'),
                    'do_reference_no' => $this->input->post('do_reference_no') ? $this->input->post('do_reference_no') : $this->site->getReference('do'),
                    'sale_reference_no' => $this->input->post('sale_reference_no'),
                    'customer' => $this->input->post('customer'),
                    'address' => $this->input->post('address'),
                    'status' => $this->input->post('status'),
                    'delivered_by' => $this->input->post('delivered_by'),
                    'received_by' => $this->input->post('received_by'),
                    'note' => $this->sma->clear_tags($this->input->post('note')),
                    'created_by' => $this->session->userdata('user_id'),
					'updated_at' => date("Y-m-d h:i:sa"),
					
					'phone' => $customer['phone'],		
					'shipping_type' => $sale->delivery_type,
                    );
                if ($_FILES['document']['size'] > 0) {
                    $this->load->library('upload');
                    $config['upload_path'] = $this->digital_upload_path;
                    $config['allowed_types'] = $this->digital_file_types;
                    $config['max_size'] = $this->allowed_file_size;
                    $config['overwrite'] = false;
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('document')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $photo = $this->upload->file_name;
                    $data['attachment'] = $photo;
                }
            } elseif ($this->input->post('add_delivery')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            }
			$data_deliveries = array(
					'is_deliveries' => 1,
                    );
            if ($this->form_validation->run() == true && $this->sales_model->addDelivery($dlDetails)) {
				$this->sales_model->is_deliveries($id,$data_deliveries);
                $this->session->set_flashdata('message', lang("delivery_added"));
                admin_redirect("sales/deliveries");
                #redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                $this->data['customer'] = $this->site->getCompanyByID($sale->customer_id);
                $this->data['address'] = $this->site->getAddressByID($sale->address_id);
                $this->data['inv'] = $sale;
                $this->data['do_reference_no'] = ''; 
				
				//$this->site->getReference('do');
                $this->data['modal_js'] = $this->site->modal_js();
                $this->load->view($this->theme . 'sales/add_delivery', $this->data);
            }
        }
    }

    public function add_delivery_soko($id = null)
    {

        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $sale = $this->sales_model->getInvoiceByID($id);
        if ($sale->sale_status != 'completed') {
            $this->session->set_flashdata('error', lang('status_is_x_completed'));
            $this->sma->md();
        }

        // if ($delivery = $this->sales_model->getDeliveryBySaleID($id)) {
            // $this->edit_delivery($delivery->id);
        // } else {

            $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
            $this->form_validation->set_rules('customer', lang("customer"), 'required');
            $this->form_validation->set_rules('address', lang("address"), 'required');

            if ($this->form_validation->run() == true) {
                if ($this->Owner || $this->Admin) {
                    $date = $this->sma->fld(trim($this->input->post('date')));
                } else {
                    $date = date('Y-m-d H:i:s');
                }
				#date_default_timezone_set("Asia/Bangkok");
                $dlDetails = array(
                    'date' => $date,
                    'sale_id' => $this->input->post('sale_id'),
                    'do_reference_no' => $this->input->post('do_reference_no') ? $this->input->post('do_reference_no') : $this->site->getReference('do'),
                    'sale_reference_no' => $this->input->post('sale_reference_no'),
                    'customer' => $this->input->post('customer'),
                    'address' => $this->input->post('address'),
                    'status' => $this->input->post('status'),
                    'delivered_by' => $this->input->post('delivered_by'),
                    'received_by' => $this->input->post('received_by'),
                    'note' => $this->sma->clear_tags($this->input->post('note')),
                    'created_by' => $this->session->userdata('user_id'),
					'updated_at' => date("Y-m-d h:i:sa"),
                    );
                if ($_FILES['document']['size'] > 0) {
                    $this->load->library('upload');
                    $config['upload_path'] = $this->digital_upload_path;
                    $config['allowed_types'] = $this->digital_file_types;
                    $config['max_size'] = $this->allowed_file_size;
                    $config['overwrite'] = false;
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('document')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $photo = $this->upload->file_name;
                    $data['attachment'] = $photo;
                }
            } elseif ($this->input->post('add_delivery')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            }
			$data_deliveries = array(
					'is_deliveries' => 1,
                    );
            if ($this->form_validation->run() == true && $this->sales_model->addDelivery($dlDetails)) {
				$this->sales_model->is_deliveries($id,$data_deliveries);
                $this->session->set_flashdata('message', lang("delivery_added"));
                admin_redirect("sales/deliveries");
            } else {
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                $this->data['customer'] = $this->site->getCompanyByID($sale->customer_id);
                $this->data['address'] = $this->site->getAddressByID($sale->address_id);
                $this->data['inv'] = $sale;
                $this->data['do_reference_no'] = ''; //$this->site->getReference('do');
                $this->data['modal_js'] = $this->site->modal_js();

                $this->load->view($this->theme . 'sales/add_delivery_soko', $this->data);
            }
        //}
	}
	
    public function ship_soko($sales_id)
    {
        $this->sma->checkPermissions(false, true);
		
		print_r($sales_id);
		exit(0);
        foreach ($sales_id as $id) {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->sales_model->getInvoiceByID($id);

			

        }
    }
	
    public function edit_delivery($id = null)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
		
		$sale_id = $this->input->post('sale_id');
        $this->form_validation->set_rules('do_reference_no', lang("do_reference_no"), 'required');
        $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');

        if ($this->form_validation->run() == true) {
			$sale = $this->sales_model->getInvoiceByID($sale_id);
			$customer = $this->sales_model->getUserById($sale->customer_id);
			date_default_timezone_set("Asia/Bangkok");
            $dlDetails = array(
                'sale_id' => $sale_id,
                'do_reference_no' => $this->input->post('do_reference_no'),
                'sale_reference_no' => $this->input->post('sale_reference_no'),
                'customer' => $this->input->post('customer'),
                'address' => $this->input->post('address'),
                'status' => $this->input->post('status'),
                'delivered_by' => $this->input->post('delivered_by'),
                'received_by' => $this->input->post('received_by'),
                'note' => $this->sma->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id'),
				'updated_at' => date("Y-m-d h:i:sa"), #2017-07-31 17:12:00
				
					'phone' => $customer['phone'],		
					'shipping_type' => $sale->delivery_type,
            );

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
                $dlDetails['date'] = $date;
            }
        } elseif ($this->input->post('edit_delivery')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
		
		$data_deliveries = array('is_deliveries' => 1);
        if ($this->form_validation->run() == true && $this->sales_model->updateDelivery($id, $dlDetails)) {
			$this->sales_model->is_deliveries($sale_id,$data_deliveries);
            $this->session->set_flashdata('message', lang("delivery_updated"));
            admin_redirect("sales/deliveries");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['delivery'] = $this->sales_model->getDeliveryByID($id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/edit_delivery', $this->data);
        }
    }

    public function delete_delivery($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteDelivery($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("delivery_deleted")));
        }
    }


    function tracking($id_arr = NULL)
    {
		
        $this->data['inv'] = $id_arr;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['error'] = validation_errors();
        $this->load->view($this->theme . 'sales/tracking', $this->data);
	}
	
	function sync_back($id){
		$delivery_id = $this->sales_model->getDeliveriesByID($id);
		$sale_id = $this->sales_model->getSaleByID($delivery_id->sale_id);
		$setting = $this->object_settings_model->getSettings();
		$sync_web = $setting->web_sync_url;
		$api_username = $setting->api_username;
		$api_password = $setting->api_password;
		
		
		
		/* Call Api */
		if($sale_id->cms_sync_web=="magento"){
			$proxy = new SoapClient($sync_web.'/api/v2_soap/?wsdl');
			$session = $proxy->login($api_username, $api_password);
			$orderincrement_id = $proxy->salesOrderShipmentCreate($session, $sale_id->web_order_id);
			$result = $proxy->salesOrderShipmentAddTrack($session, $orderincrement_id, 'custom', 'EMS', $delivery_id->tracking);
		}
	}
	
    public function delivery_actions()
    {
        // if (!$this->Owner && !$this->GP['bulk_actions']) {
            // $this->session->set_flashdata('warning', lang('access_denied'));
            // redirect($_SERVER["HTTP_REFERER"]);
        // }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

		
        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
				/* action packing */
     //            if ($this->input->post('form_action') == 'acprint_packing') {
					// $id_arr = array();
     //                foreach ($_POST['val'] as $id) {
					// 	$dlDetails = array(
					// 		'status' => 'packing',
					// 		'updated_at' => date("Y-m-d h:i:sa"),
					// 	);
					// 	array_push($id_arr,$id);
					// 	$this->sales_model->updateDelivery($id, $dlDetails);
					// 	$this->session->set_flashdata('message', lang("print add packing success"));
     //                }
					
					// $this->pdf_delivery_all($id_arr,'<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));

					
					// $this->session->set_flashdata('message', lang("delivery_updated"));
     //                redirect($_SERVER["HTTP_REFERER"]);
     //            }


                if ($this->input->post('form_action') == 'send_sms_tracking') {
                    if (!empty($_POST['val'])) {
                        // get setting
                        $settings = $this->db->get('sma_settings');
                        $setting = $settings->row(0);

                        if(!empty($setting->sms_template)){
                            // order number, tracking number
                            foreach($_POST['val'] as $id){
                                // $this->db->where('id', $id);
                                // $query = $this->db->get('sma_deliveries');
                                $sql = '
                                    SELECT 
                                        (sma_deliveries.sale_reference_no) AS order_number,
                                        (sma_deliveries.tracking) AS tracking_number,
                                        (sma_companies.phone) AS phone 
                                    FROM sma_deliveries 
                                    INNER JOIN sma_sales ON sma_sales.id = sma_deliveries.sale_id 
                                    INNER JOIN sma_companies ON sma_companies.id = sma_sales.customer_id 
                                    WHERE sma_deliveries.id = ? 
                                ';
                                $query = $this->db->query($sql, [$id]);
                                $rs = $query->row(0);
                                if(!empty($setting->sms_template)){
                                    if(!empty($rs->order_number) && !empty($rs->tracking_number)){
                                        $replace = ['{order_number}'=>$rs->order_number, '{tracking_number}'=>$rs->tracking_number];
                                        $from = "020000000";
                                        $to = $rs->phone;
                                        $message = strtr($setting->sms_template, $replace);    
                                        $sms = new thsms();
                                        $sms_response = $sms->send($from, $to, $message);
                                        if($sms_response[0] === false){
                                            $this->session->set_flashdata('error', "fail to send sms !");
                                            redirect($_SERVER["HTTP_REFERER"]);
                                        }
                                    }    
                                }
                            }
                            $this->session->set_flashdata('message', lang("send_sms_complete"));  
                        }else{
                            $this->session->set_flashdata('error', lang("please_set_sms_template_before_send"));
                        }
                        // var_dump($_POST['val']);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
				
				/* action accomplete */
                if ($this->input->post('form_action') == 'complete') {
                    foreach ($_POST['val'] as $id) {
						$dlDetails = array(
							'status' => 'delivered',
							'updated_at' => date("Y-m-d h:i:sa"),
						);
						$this->sales_model->updateDelivery($id, $dlDetails);

						$this->sync_back($id);

						
					
						
						
                    }
					$this->session->set_flashdata('message', lang("delivery_updated"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'delete') {
                    $this->sma->checkPermissions('delete_delivery');
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteDelivery($id);
                    }
                    $this->session->set_flashdata('message', lang("deliveries_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('deliveries'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('do_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('sale_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $delivery = $this->sales_model->getDeliveryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($delivery->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $delivery->do_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $delivery->sale_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $delivery->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $delivery->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($delivery->status));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

                    $filename = 'deliveries_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    return create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_delivery_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* Shipping Label by Hope */
    
    public function print_shipping_label()
    {
        // if (!$this->Owner && !$this->GP['bulk_actions']) {
            // $this->session->set_flashdata('warning', lang('access_denied'));
            // redirect($_SERVER["HTTP_REFERER"]);
        // }

        if (!empty($_POST['val'])) {
            /* action packing */
            if ($this->input->post('form_action') == 'acprint_packing') {
                $id_arr = array();
                foreach ($_POST['val'] as $id) {
                    $dlDetails = array(
                        'status' => 'packing',
                        'updated_at' => date("Y-m-d h:i:sa"),
                    );
                    array_push($id_arr,$id);
                    $this->sales_model->updateDelivery($id, $dlDetails);
                    $this->session->set_flashdata('message', lang("print add packing success"));
                }
				/* check if purchase order or sale order */
				$delivery_detail = $this->sales_model->getDeliveryByID($id);
				$purchase = $this->purchases_model->getPurchaseByRef($delivery_detail->sale_reference_no);
				
				
			//	if(!empty($purchase->reference_no)){
              /*  $sql = "
                    SELECT 
                        (sma_deliveries.customer) AS customer_name, 
                        (sma_deliveries.address) AS customer_address, 
                        (sma_users.phone) AS customer_phone, 
                        (sma_deliveries.sale_reference_no) AS order_no,
                        (sma_deliveries.sale_id) AS sale_id    
                    FROM sma_deliveries 
                    INNER JOIN sma_purchases ON sma_purchases.id = sma_deliveries.sale_id 
                    INNER JOIN sma_users ON sma_users.warehouse_id = sma_purchases.warehouse_id 
                    WHERE sma_deliveries.id IN ? 
                ";*/
				
			//	}else{
			/*		 $sql = "
                    SELECT 
                        (sma_deliveries.customer) AS customer_name, 
                        (sma_deliveries.address) AS customer_address, 
                        (sma_companies.phone) AS customer_phone, 
                        (sma_deliveries.sale_reference_no) AS order_no,
                        (sma_deliveries.sale_id) AS sale_id    
                    FROM sma_deliveries 
                    INNER JOIN sma_sales ON sma_sales.id = sma_deliveries.sale_id 
                    INNER JOIN sma_companies ON sma_companies.id = sma_sales.customer_id 
                    WHERE sma_deliveries.id IN ? 
                ";*/
				$sql = "
				SELECT 
                        (sma_deliveries.customer) AS customer_name, 
                        (sma_deliveries.address) AS customer_address, 
                        (sma_deliveries.phone) AS customer_phone, 
                        (sma_deliveries.sale_reference_no) AS order_no,
                        (sma_deliveries.sale_id) AS sale_id    
                    FROM sma_deliveries 
					LEFT JOIN sma_purchases ON sma_purchases.id = sma_deliveries.sale_id 
					LEFT JOIN sma_sales ON sma_sales.id = sma_deliveries.sale_id 
					LEFT JOIN sma_users ON sma_users.warehouse_id = sma_purchases.warehouse_id 
					LEFT JOIN sma_companies ON sma_companies.id = sma_sales.customer_id 
                    WHERE sma_deliveries.id IN ?";
				//}
                $query = $this->db->query($sql, [$id_arr]);
                
                $order = [];
                $header = [];
                $customer = [];

                foreach($query->result() as $key=>$row){
                    $order[$key] = $row;
                    // $header[$key]['company'] = [
                    //     'logo' => 'data:image/gif;base64,R0lGODlhPQBEAPeoAJosM//AwO/AwHVYZ/z595kzAP/s7P+goOXMv8+fhw/v739/f+8PD98fH/8mJl+fn/9ZWb8/PzWlwv///6wWGbImAPgTEMImIN9gUFCEm/gDALULDN8PAD6atYdCTX9gUNKlj8wZAKUsAOzZz+UMAOsJAP/Z2ccMDA8PD/95eX5NWvsJCOVNQPtfX/8zM8+QePLl38MGBr8JCP+zs9myn/8GBqwpAP/GxgwJCPny78lzYLgjAJ8vAP9fX/+MjMUcAN8zM/9wcM8ZGcATEL+QePdZWf/29uc/P9cmJu9MTDImIN+/r7+/vz8/P8VNQGNugV8AAF9fX8swMNgTAFlDOICAgPNSUnNWSMQ5MBAQEJE3QPIGAM9AQMqGcG9vb6MhJsEdGM8vLx8fH98AANIWAMuQeL8fABkTEPPQ0OM5OSYdGFl5jo+Pj/+pqcsTE78wMFNGQLYmID4dGPvd3UBAQJmTkP+8vH9QUK+vr8ZWSHpzcJMmILdwcLOGcHRQUHxwcK9PT9DQ0O/v70w5MLypoG8wKOuwsP/g4P/Q0IcwKEswKMl8aJ9fX2xjdOtGRs/Pz+Dg4GImIP8gIH0sKEAwKKmTiKZ8aB/f39Wsl+LFt8dgUE9PT5x5aHBwcP+AgP+WltdgYMyZfyywz78AAAAAAAD///8AAP9mZv///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAKgALAAAAAA9AEQAAAj/AFEJHEiwoMGDCBMqXMiwocAbBww4nEhxoYkUpzJGrMixogkfGUNqlNixJEIDB0SqHGmyJSojM1bKZOmyop0gM3Oe2liTISKMOoPy7GnwY9CjIYcSRYm0aVKSLmE6nfq05QycVLPuhDrxBlCtYJUqNAq2bNWEBj6ZXRuyxZyDRtqwnXvkhACDV+euTeJm1Ki7A73qNWtFiF+/gA95Gly2CJLDhwEHMOUAAuOpLYDEgBxZ4GRTlC1fDnpkM+fOqD6DDj1aZpITp0dtGCDhr+fVuCu3zlg49ijaokTZTo27uG7Gjn2P+hI8+PDPERoUB318bWbfAJ5sUNFcuGRTYUqV/3ogfXp1rWlMc6awJjiAAd2fm4ogXjz56aypOoIde4OE5u/F9x199dlXnnGiHZWEYbGpsAEA3QXYnHwEFliKAgswgJ8LPeiUXGwedCAKABACCN+EA1pYIIYaFlcDhytd51sGAJbo3onOpajiihlO92KHGaUXGwWjUBChjSPiWJuOO/LYIm4v1tXfE6J4gCSJEZ7YgRYUNrkji9P55sF/ogxw5ZkSqIDaZBV6aSGYq/lGZplndkckZ98xoICbTcIJGQAZcNmdmUc210hs35nCyJ58fgmIKX5RQGOZowxaZwYA+JaoKQwswGijBV4C6SiTUmpphMspJx9unX4KaimjDv9aaXOEBteBqmuuxgEHoLX6Kqx+yXqqBANsgCtit4FWQAEkrNbpq7HSOmtwag5w57GrmlJBASEU18ADjUYb3ADTinIttsgSB1oJFfA63bduimuqKB1keqwUhoCSK374wbujvOSu4QG6UvxBRydcpKsav++Ca6G8A6Pr1x2kVMyHwsVxUALDq/krnrhPSOzXG1lUTIoffqGR7Goi2MAxbv6O2kEG56I7CSlRsEFKFVyovDJoIRTg7sugNRDGqCJzJgcKE0ywc0ELm6KBCCJo8DIPFeCWNGcyqNFE06ToAfV0HBRgxsvLThHn1oddQMrXj5DyAQgjEHSAJMWZwS3HPxT/QMbabI/iBCliMLEJKX2EEkomBAUCxRi42VDADxyTYDVogV+wSChqmKxEKCDAYFDFj4OmwbY7bDGdBhtrnTQYOigeChUmc1K3QTnAUfEgGFgAWt88hKA6aCRIXhxnQ1yg3BCayK44EWdkUQcBByEQChFXfCB776aQsG0BIlQgQgE8qO26X1h8cEUep8ngRBnOy74E9QgRgEAC8SvOfQkh7FDBDmS43PmGoIiKUUEGkMEC/PJHgxw0xH74yx/3XnaYRJgMB8obxQW6kL9QYEJ0FIFgByfIL7/IQAlvQwEpnAC7DtLNJCKUoO/w45c44GwCXiAFB/OXAATQryUxdN4LfFiwgjCNYg+kYMIEFkCKDs6PKAIJouyGWMS1FSKJOMRB/BoIxYJIUXFUxNwoIkEKPAgCBZSQHQ1A2EWDfDEUVLyADj5AChSIQW6gu10bE/JG2VnCZGfo4R4d0sdQoBAHhPjhIB94v/wRoRKQWGRHgrhGSQJxCS+0pCZbEhAAOw==',
                    //     'name' => 'Smith company',
                    //     'address' => '123 small roads',
                    //     'telephone' => '1669',
                    // ];
                    $header[$key]['setting'] = array(
                        'orderno' => !empty($row->order_no) ? $row->order_no : '',
                    );

                    // query sale items
                    $this->db->where('sale_id', $row->sale_id);
                    $sale_items = $this->db->get('sma_sale_items');
                    foreach($sale_items->result() as $sale_item){
                        $header[$key]['product'][] = ['sku'=>$sale_item->product_code, 'quantity'=>intval($sale_item->quantity)];
                    }

                    $customer[$key] = [
                        'customerName' => $row->customer_name,
                        'customerAddress' => $row->customer_address,
                        'telephone' => $row->customer_phone,
                    ];
                }

                $data = [
                    'order' => $order,
                    'header' => $header,
                    'customer' => $customer,
                ];

                $html = $this->load->view($this->theme.'mpdf/senderonly', $data, TRUE);

                // $this->load->view($this->theme . 'sales/tracking', $this->data);
                // die;
                
                // $this->pdf_delivery_all($id_arr,'<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
                // echo 'Shipping Label';
                $mpdf = new mPDF('utf-8', [101.6,76.2], '0', 'thsarabun');
                $mpdf->WriteHTML($html);
                $mpdf->Output();
            }
        }
    }
    /* End Shipping Label by Hope */

    /* Search tracking by Hope */
    public function search_order_no()
    {
        $order_no = $this->input->get('order_no');
        if(!empty($order_no)){
            $this->db->where('sale_reference_no', $order_no);
            $rs = $this->db->get('sma_deliveries');
            $response = [];
            if(!empty($rs->result())){
                $data = $rs->row(0);
                $order_no = $data->sale_reference_no;
                $ship_to = $data->customer;
                $response = ['message'=>'pass', 'description'=>lang("Order has found !"), 'order_no'=>$order_no, 'ship_to'=>$ship_to];
            }else{
                $response = ['message'=>'fail', 'description'=>lang("Order not found !")];
            }
            echo json_encode($response);
        }
    }

    /* End Add tracking */

    /* Add tracking by Hope */

    public function add_tracking()
    {
        $order_no = $this->input->get('order_no');
        $tracking_no = $this->input->get('tracking_no');
        if(!empty($order_no) && !empty($tracking_no)){
            $this->db->set('tracking', $tracking_no);
            $this->db->set('status', 'delivering');
            $this->db->where('sale_reference_no', $order_no);
            $rs = $this->db->update('sma_deliveries');
            $response = [];
            if($rs){
                $response = ['message'=>'pass', 'description'=>lang("Add Tracking Complete !")];
            }else{
                $response = ['message'=>'fail', 'description'=>lang("Add tracking Fail !")];
            }
            echo json_encode($response);
        }
    }

    /* End Add tracking */

    /* Send Sms for tracking by Hope */

    public function send_sms()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }


        /*
        $sms = new thsms();
        $rs = $thsms->send($from, $to, $msg);       
        if($rs[0]){
            // send complete
        }else{
            // send fail
        }
        */
    }

    /* End Sms for tracking by Hope */

    /* Shipping Label by Wang Copy Hope */
    
    public function owner_receiver()
    {
        // if (!$this->Owner && !$this->GP['bulk_actions']) {
            // $this->session->set_flashdata('warning', lang('access_denied'));
            // redirect($_SERVER["HTTP_REFERER"]);
        // }

        if (!empty($_POST['val'])) {
            /* action packing */
            if ($this->input->post('form_action') == 'owner_receiver' || $this->input->post('form_action') == 'sales_receiver') {
                $id_arr = array();
                foreach ($_POST['val'] as $id) {
                    $dlDetails = array(
                        'status' => 'packing',
                        'updated_at' => date("Y-m-d h:i:sa"),
                    );
                    array_push($id_arr,$id);
                    $this->sales_model->updateDelivery($id, $dlDetails);
                    $this->session->set_flashdata('message', lang("print label for packing success"));
                }
            /*    $sql = "
                    SELECT 
                        (sma_deliveries.customer) AS customer_name, 
                        (sma_deliveries.address) AS customer_address, 
                        (sma_companies.phone) AS customer_phone, 
                        (sma_deliveries.sale_reference_no) AS order_no,
                        (sma_deliveries.sale_id) AS sale_id    
                    FROM sma_deliveries 
                    INNER JOIN sma_sales ON sma_sales.id = sma_deliveries.sale_id 
                    INNER JOIN sma_companies ON sma_companies.id = sma_sales.customer_id 
                    WHERE sma_deliveries.id IN ? 
                ";*/
				
				
				$sql = "
				SELECT 
                        (sma_deliveries.customer) AS customer_name, 
                        (sma_deliveries.address) AS customer_address, 
                        (sma_users.phone) AS customer_phone, 
                        (sma_companies.phone) AS customer_phone, 
                        (sma_deliveries.sale_reference_no) AS order_no,
                        (sma_deliveries.sale_id) AS sale_id    
                    FROM sma_deliveries 
					LEFT JOIN sma_purchases ON sma_purchases.id = sma_deliveries.sale_id 
					LEFT JOIN sma_sales ON sma_sales.id = sma_deliveries.sale_id 
					LEFT JOIN sma_users ON sma_users.warehouse_id = sma_purchases.warehouse_id 
					LEFT JOIN sma_companies ON sma_companies.id = sma_sales.customer_id 
                    WHERE sma_deliveries.id IN ?";
				
                $query = $this->db->query($sql, [$id_arr]);
                
                $order = [];
                $header = [];
                $customer = [];

                foreach($query->result() as $key => $row){
                    $order[$key] = $row;
                    $header[$key]['setting'] = array(
                        'orderno' => !empty($row->order_no) ? $row->order_no : '',
                    );

                    // query sale items
                    $this->db->where('sale_id', $row->sale_id);
                    $sale_items = $this->db->get('sma_sale_items');
                    foreach($sale_items->result() as $sale_item){
                        $header[$key]['product'][] = ['sku'=>$sale_item->product_code, 'quantity'=>intval($sale_item->quantity)];
                    }
					$order_no 	= !empty($row->order_no) ? $row->order_no : '';
					$user_id 	= $this->session->userdata('user_id');
					$userdata 	= $this->auth_model->getAgent($user_id);
					$UserByEmail = $this->sales_model->getUserByEmail($userdata[0]->email);

					$name		= $userdata[0]->first_name.' '.$userdata[0]->last_name;
					$address	= $UserByEmail['address'];
					$phone		= $userdata[0]->phone;
					
					if($this->input->post('form_action') == 'sales_receiver'){
						$saledata = $this->sales_model->getBillerByOrderId($order_no);
						$biller = $this->data['biller'] = $this->site->getCompanyByID($saledata['biller_id']);
						$name = $biller->name;
					}

                    $header[$key]['company'] = array(
                        'name' 			=> $name,
						'address'		=> str_ireplace('</p>','',str_ireplace('<p>','',$address)),
						'telephone'		=> $phone,
                    );
					
                    $customer[$key] = [
                        'customerName' => $row->customer_name,
                        'customerAddress' => str_ireplace('</p>','',str_ireplace('<p>','',$row->customer_address)),
                        'telephone' => $row->customer_phone,
                    ];
					
					$summary[$key] = [
                        'cod' => '',
                    ];
                }

                $data = [
                    'order'		=> $order,
                    'header'	=> $header,
                    'customer'	=> $customer,
					'summary'	=> $summary,
                ];
//A4 	210 x 297 มม. 	8.27 x 11.69 นิ้ว 
                $html = $this->load->view($this->theme.'mpdf/shippinglabel2', $data, TRUE);
				$mpdf = new mPDF('utf-8', [210, 85], '0', 'thsarabun');
                //$mpdf = new mPDF('utf-8', [204,94], '0', 'thsarabun');
                $mpdf->WriteHTML($html);
                $mpdf->Output();
            }

        }else{
			$data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('deliveries')));
			$meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
			$this->page_construct('sales/deliveries', $meta, $this->data);
		}
    }
    /* End Shipping Label by Wang Copy Hope */
	
	
    /* payment list */

    public function payment_list()
    {
        // $this->sma->checkPermissions();

        // $this->load->view($this->theme . 'sales/payment_list');
        // $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('deliveries')));
        // $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        // $this->page_construct('sales/payment_list');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('sales/payment_list', $meta, $this->data);
    }

    /* end payment list*/

    /* ajax get payment list */

    public function get_payment_list()
    {
        // $this->sma->checkPermissions();

        // $this->load->library('datatables');

        // $this->datatables->select("id, reference_no, customer_id")->from('sales');
        // echo $this->databases->generate();
        // $this->datatables
        //     ->select("id,ordering, image, condition_name,  condition_from_value, condition_to_value, price, delivery_type")
        //     ->from("shipping_matrixrate");

        // echo $this->datatables->generate();

 //        $data = array(
 // array('Name'=>'parvez', 'Empid'=>11, 'Salary'=>101),
 // array('Name'=>'alam', 'Empid'=>1, 'Salary'=>102),
 // array('Name'=>'phpflow', 'Empid'=>21, 'Salary'=>103) );
             
 
 // $results = array(
 // "sEcho" => 1,
 //        "iTotalRecords" => count($data),
 //        "iTotalDisplayRecords" => count($data),
 //          "aaData"=>$data);
/*while($row = $result->fetch_array(MYSQLI_ASSOC)){
  $results["data"][] = $row ;
}*/
 
// echo json_encode($results);
        $query = $this->db->query("SELECT id, biller, bank_to, date_cf_payment, total_cf_payment, attachment FROM sma_sales");
        $count = 0;
        $dataRow = [];
        foreach($query->result() as $data){
            $dataRow[] = [
                'id' => $data->id,
                'biller' => $data->biller,
                'bank_to' => $data->bank_to,
                'date_cf_payment' => $data->date_cf_payment,
                'total_cf_payment' => $data->total_cf_payment,
                'attachment' => $data->attachment
            ];
            $count++;
        }
        $response = [
            'sEcho'=> 1,
            'iTotalRecords' => $count,
            'iTotalDisplayRecords' => $count,
            'aaData' => $dataRow
        ];

        echo json_encode($response);
    }

    /* end ajax get payment list */

    /* -------------------------------------------------------------------------------- */

    public function payments($id = null)
    {
        //$this->sma->checkPermissions(false, true);
        $this->data['payments'] = $this->sales_model->getInvoicePayments($id);
        $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
        $this->load->view($this->theme . 'sales/payments', $this->data);
    }

    public function payment_note($id = null)
    {
        $this->sma->checkPermissions('payments', true);
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = lang("payment_note");

        $this->load->view($this->theme . 'sales/payment_note', $this->data);
    }

    public function email_payment($id = null)
    {
        $this->sma->checkPermissions('payments', true);
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $customer = $this->site->getCompanyByID($inv->customer_id);
        if ( ! $customer->email) {
            $this->sma->send_json(array('msg' => lang("update_customer_email")));
        }
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['customer'] =$customer;
        $this->data['page_title'] = lang("payment_note");
        $html = $this->load->view($this->theme . 'sales/payment_note', $this->data, TRUE);

        $html = str_replace(array('<i class="fa fa-2x">&times;</i>', 'modal-', '<p>&nbsp;</p>', '<p style="border-bottom: 1px solid #666;">&nbsp;</p>', '<p>'.lang("stamp_sign").'</p>'), '', $html);
        $html = preg_replace("/<img[^>]+\>/i", '', $html);
        // $html = '<div style="border:1px solid #DDD; padding:10px; margin:10px 0;">'.$html.'</div>';

        $this->load->library('parser');
        $parse_data = array(
            'stylesheet' => '<link href="'.$this->data['assets'].'styles/helpers/bootstrap.min.css" rel="stylesheet"/>',
            'name' => $customer->company && $customer->company != '-' ? $customer->company :  $customer->name,
            'email' => $customer->email,
            'heading' => lang('payment_note').'<hr>',
            'msg' => $html,
            'site_link' => base_url(),
            'site_name' => $this->Settings->site_name,
            'logo' => '<img src="' . base_url('assets/uploads/logos/' . $this->Settings->logo) . '" alt="' . $this->Settings->site_name . '"/>'
        );
        $msg = file_get_contents('./themes/' . $this->Settings->theme . '/admin/views/email_templates/email_con.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = lang('payment_note') . ' - ' . $this->Settings->site_name;

        if ($this->sma->send_email($customer->email, $subject, $message)) {
            $this->sma->send_json(array('msg' => lang("email_sent")));
        } else {
            $this->sma->send_json(array('msg' => lang("email_failed")));
        }
    }

	
	/* ADD PAYMENT*/
    public function add_payment($id = null)
    {
        $this->sma->checkPermissions('payments', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		
        $sale = $this->sales_model->getInvoiceByID($id);
        if ($sale->payment_status == 'paid' && $sale->grand_total == $sale->paid) {
            $this->session->set_flashdata('error', lang("sale_already_paid"));
            $this->sma->md();
        }

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->input->post('paid_by') == 'deposit') {
                $sale = $this->sales_model->getInvoiceByID($this->input->post('sale_id'));
                $customer_id = $sale->customer_id;
                if ( ! $this->site->check_customer_deposit($customer_id, $this->input->post('amount-paid'))) {
                    $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $customer_id = null;
            }
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('pay'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
                'type' => 'received',
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

			// $getSaleByID = $this->sales_model->getSaleByID($id);
			// $award_points = $this->auth_model->totalpoint($getSaleByID->created_by);
			// $total_points = $this->auth_model->totalpoint($id);
			// $point_data = array(
				// 'reference_no' => $getSaleByID->reference_no,
				// 'created_by' => $getSaleByID->created_by,
				// 'customer_id' => $getSaleByID->customer_id,
				// 'total_points' => $total_points,
				// );
			
			// if ($this->Settings->award_use) {
				// $this->sales_model->update_status_points($point_data);
			// }
            //$this->sma->print_arrays($payment);
        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment, $customer_id)) {
			$this->sales_model->updatepaymentstatus($id); //custom change status order
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            if ($sale->sale_status == 'returned' && $sale->paid == $sale->grand_total) {
                $this->session->set_flashdata('warning', lang('payment_was_returned'));
                $this->sma->md();
            }
            $this->data['inv'] = $sale;
            $this->data['payment_ref'] = ''; //$this->site->getReference('pay');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/add_payment', $this->data);
        }
    }

    public function edit_payment($id = null)
    {
        $this->sma->checkPermissions('edit', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $payment = $this->sales_model->getPaymentByID($id);
        if ($payment->paid_by == 'ppp' || $payment->paid_by == 'stripe' || $payment->paid_by == 'paypal' || $payment->paid_by == 'skrill') {
            $this->session->set_flashdata('error', lang('x_edit_payment'));
            $this->sma->md();
        }
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->input->post('paid_by') == 'deposit') {
                $sale = $this->sales_model->getInvoiceByID($this->input->post('sale_id'));
                $customer_id = $sale->customer_id;
                $amount = $this->input->post('amount-paid')-$payment->amount;
                if ( ! $this->site->check_customer_deposit($customer_id, $amount)) {
                    $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $customer_id = null;
            }
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = $payment->date;
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->updatePayment($id, $payment, $customer_id)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            admin_redirect("sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['payment'] = $payment;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/edit_payment', $this->data);
        }
    }

    public function delete_payment($id = null)
    {
        $this->sma->checkPermissions('delete');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* --------------------------------------------------------------------------------------------- */

    public function suggestions()
    {
        $term = $this->input->get('term', true);
        $warehouse_id = $this->input->get('warehouse_id', true);
        $customer_id = $this->input->get('customer_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . admin_url('welcome') . "'; }, 10);</script>");
        }

        $analyzed = $this->sma->analyze_term($term);
        $sr = $analyzed['term'];
        $option_id = $analyzed['option_id'];

        $warehouse = $this->site->getWarehouseByID($warehouse_id);
        $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        if ($this->Owner || $this->Admin) {
			$salebrand = "";
        } else {
			$salebrand = $this->sales_model->getSaleBrand();
        }
		
        $rows = $this->sales_model->getProductNames($sr, $warehouse_id, $salebrand['brand_targets']);

        if ($rows) {
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($rows as $row) {
                unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
                $option = false;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
                if ($options) {
                    $opt = $option_id && $r == 0 ? $this->sales_model->getProductOptionByID($option_id) : $options[0];
                    if (!$option_id || $r > 0) {
                        $option_id = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                    $option_id = FALSE;
                }
                $row->option = $option_id;
                $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
                if ($row->promotion) {
                    $row->price = $row->promo_price;
                } elseif ($customer->price_group_id) {
                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $customer->price_group_id)) {
                        $row->price = $pr_group_price->price;
                    }
                } elseif ($warehouse->price_group_id) {
                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $warehouse->price_group_id)) {
                        $row->price = $pr_group_price->price;
                    }
                }
				
                $row->price = $row->price + (($row->price * $customer_group->percent) / 100);
                $row->real_unit_price = $row->price;
                $row->base_quantity = 1;
                $row->base_unit = $row->unit;
                $row->base_unit_price = $row->price;
                $row->unit = $row->sale_unit ? $row->sale_unit : $row->unit;
                $row->comment = '';

				$setting = $this->object_settings_model->getSettings();
				$award_use = $setting->award_use;
				
				if($award_use == 1): //price
					$row->award_points = floor(($row->real_unit_price / $setting->each_sale) * $setting->sa_point);
					$award_use = "Price";
				else: //fig
					$row->award_points = $row->award_points ? $row->award_points : 0;
					$award_use = "ConfigProduct";
				endif;
				
                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                }

                $units = $this->site->getUnitsByBUID($row->base_unit);
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'category' => $row->category_id,
                    'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, 'award_use' => $award_use);
                $r++;
            }
            $this->sma->send_json($pr);
        } else {
            $this->sma->send_json(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    /* ------------------------------------ Gift Cards ---------------------------------- */

    public function gift_cards()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('gift_cards')));
        $meta = array('page_title' => lang('gift_cards'), 'bc' => $bc);
        $this->page_construct('sales/gift_cards', $meta, $this->data);
    }

    public function getGiftCards()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('gift_cards') . ".id as id, card_no, value, balance, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as created_by, customer, expiry", false)
            ->join('users', 'users.id=gift_cards.created_by', 'left')
            ->from("gift_cards")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('sales/view_gift_card/$1') . "' class='tip' title='" . lang("view_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> <a href='" . admin_url('sales/topup_gift_card/$1') . "' class='tip' title='" . lang("topup_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-dollar\"></i></a> <a href='" . admin_url('sales/edit_gift_card/$1') . "' class='tip' title='" . lang("edit_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_gift_card") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('sales/delete_gift_card/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    public function view_gift_card($id = null)
    {
        $this->data['page_title'] = lang('gift_card');
        $gift_card = $this->site->getGiftCardByID($id);
        $this->data['gift_card'] = $this->site->getGiftCardByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($gift_card->customer_id);
        $this->data['topups'] = $this->sales_model->getAllGCTopups($id);
        $this->load->view($this->theme . 'sales/view_gift_card', $this->data);
    }

    public function topup_gift_card($card_id)
    {
        $this->sma->checkPermissions('add_gift_card', true);
        $card = $this->site->getGiftCardByID($card_id);
        $this->form_validation->set_rules('amount', lang("amount"), 'trim|integer|required');

        if ($this->form_validation->run() == true) {
            $data = array('card_id' => $card_id,
                'amount' => $this->input->post('amount'),
                'date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_id'),
            );
            $card_data['balance'] = ($this->input->post('amount')+$card->balance);
            // $card_data['value'] = ($this->input->post('amount')+$card->value);
            if ($this->input->post('expiry')) {
                $card_data['expiry'] = $this->sma->fld(trim($this->input->post('expiry')));
            }
        } elseif ($this->input->post('topup')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->topupGiftCard($data, $card_data)) {
            $this->session->set_flashdata('message', lang("topup_added"));
            admin_redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['card'] = $card;
            $this->data['page_title'] = lang("topup_gift_card");
            $this->load->view($this->theme . 'sales/topup_gift_card', $this->data);
        }
    }

    public function validate_gift_card($no)
    {
        //$this->sma->checkPermissions();
        if ($gc = $this->site->getGiftCardByNO($no)) {
            if ($gc->expiry) {
                if ($gc->expiry >= date('Y-m-d')) {
                    $this->sma->send_json($gc);
                } else {
                    $this->sma->send_json(false);
                }
            } else {
                $this->sma->send_json($gc);
            }
        } else {
            $this->sma->send_json(false);
        }
    }

    public function add_gift_card()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|is_unique[gift_cards.card_no]|required');
        $this->form_validation->set_rules('value', lang("value"), 'required');

        if ($this->form_validation->run() == true) {
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : null;
            $customer = $customer_details ? $customer_details->company : null;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : null,
                'customer' => $customer,
                'balance' => $this->input->post('value'),
                'expiry' => $this->input->post('expiry') ? $this->sma->fsd($this->input->post('expiry')) : null,
                'created_by' => $this->session->userdata('user_id'),
            );
            $sa_data = array();
            $ca_data = array();
            if ($this->input->post('staff_points')) {
                $sa_points = $this->input->post('sa_points');
                $user = $this->site->getUser($this->input->post('user'));
                if ($user->award_points < $sa_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    admin_redirect("sales/gift_cards");
                }
                $sa_data = array('user' => $user->id, 'points' => ($user->award_points - $sa_points));
            } elseif ($customer_details && $this->input->post('use_points')) {
                $ca_points = $this->input->post('ca_points');
                if ($customer_details->award_points < $ca_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    admin_redirect("sales/gift_cards");
                }
                $ca_data = array('customer' => $this->input->post('customer'), 'points' => ($customer_details->award_points - $ca_points));
            }
            // $this->sma->print_arrays($data, $ca_data, $sa_data);
        } elseif ($this->input->post('add_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->addGiftCard($data, $ca_data, $sa_data)) {
            $this->session->set_flashdata('message', lang("gift_card_added"));
            admin_redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['users'] = $this->sales_model->getStaff();
            $this->data['page_title'] = lang("new_gift_card");
            $this->load->view($this->theme . 'sales/add_gift_card', $this->data);
        }
    }

    public function edit_gift_card($id = null)
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|required');
        $gc_details = $this->site->getGiftCardByID($id);
        if ($this->input->post('card_no') != $gc_details->card_no) {
            $this->form_validation->set_rules('card_no', lang("card_no"), 'is_unique[gift_cards.card_no]');
        }
        $this->form_validation->set_rules('value', lang("value"), 'required');
        //$this->form_validation->set_rules('customer', lang("customer"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $gift_card = $this->site->getGiftCardByID($id);
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : null;
            $customer = $customer_details ? $customer_details->company : null;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : null,
                'customer' => $customer,
                'balance' => ($this->input->post('value') - $gift_card->value) + $gift_card->balance,
                'expiry' => $this->input->post('expiry') ? $this->sma->fsd($this->input->post('expiry')) : null,
            );
        } elseif ($this->input->post('edit_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateGiftCard($id, $data)) {
            $this->session->set_flashdata('message', lang("gift_card_updated"));
            admin_redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['gift_card'] = $this->site->getGiftCardByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/edit_gift_card', $this->data);
        }
    }

    public function sell_gift_card()
    {
        $this->sma->checkPermissions('gift_cards', true);
        $error = null;
        $gcData = $this->input->get('gcdata');
        if (empty($gcData[0])) {
            $error = lang("value") . " " . lang("is_required");
        }
        if (empty($gcData[1])) {
            $error = lang("card_no") . " " . lang("is_required");
        }

        $customer_details = (!empty($gcData[2])) ? $this->site->getCompanyByID($gcData[2]) : null;
        $customer = $customer_details ? $customer_details->company : null;
        $data = array('card_no' => $gcData[0],
            'value' => $gcData[1],
            'customer_id' => (!empty($gcData[2])) ? $gcData[2] : null,
            'customer' => $customer,
            'balance' => $gcData[1],
            'expiry' => (!empty($gcData[3])) ? $this->sma->fsd($gcData[3]) : null,
            'created_by' => $this->session->userdata('user_id'),
        );

        if (!$error) {
            if ($this->sales_model->addGiftCard($data)) {
                $this->sma->send_json(array('result' => 'success', 'message' => lang("gift_card_added")));
            }
        } else {
            $this->sma->send_json(array('result' => 'failed', 'message' => $error));
        }

    }

    public function delete_gift_card($id = null)
    {
        $this->sma->checkPermissions();

        if ($this->sales_model->deleteGiftCard($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("gift_card_deleted")));
        }
    }

    public function gift_card_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $this->sma->checkPermissions('delete_gift_card');
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteGiftCard($id);
                    }
                    $this->session->set_flashdata('message', lang("gift_cards_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('gift_cards'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('card_no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('value'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('customer'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->site->getGiftCardByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->card_no);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->value);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->customer);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'gift_cards_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    return create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_gift_card_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function get_award_points($id = null)
    {
        $this->sma->checkPermissions('index');

        $row = $this->site->getUser($id);
        $this->sma->send_json(array('sa_points' => $row->award_points));
    }

    /* -------------------------------------------------------------------------------------- */

    public function sale_by_csv()
    {
        $this->sma->checkPermissions('csv');
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');

        if ($this->form_validation->run() == true) {

            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so');
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $payment_term = $this->input->post('payment_term');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : null;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company != '-'  ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->sma->clear_tags($this->input->post('note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    admin_redirect("sales/sale_by_csv");
                }

                $csv = $this->upload->file_name;
                $data['attachment'] = $csv;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'net_unit_price', 'quantity', 'variant', 'item_tax_rate', 'discount', 'serial');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) {

                    if (isset($csv_pr['code']) && isset($csv_pr['net_unit_price']) && isset($csv_pr['quantity'])) {

                        if ($product_details = $this->sales_model->getProductByCode($csv_pr['code'])) {

                            if ($csv_pr['variant']) {
                                $item_option = $this->sales_model->getProductVariantByName($csv_pr['variant'], $product_details->id);
                                if (!$item_option) {
                                    $this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $product_details->name . " - " . $csv_pr['variant'] . " ). " . lang("line_no") . " " . $rw);
                                    redirect($_SERVER["HTTP_REFERER"]);
                                }
                            } else {
                                $item_option = json_decode('{}');
                                $item_option->id = null;
                            }

                            $item_id = $product_details->id;
                            $item_type = $product_details->type;
                            $item_code = $product_details->code;
                            $item_name = $product_details->name;
                            $item_net_price = $this->sma->formatDecimal($csv_pr['net_unit_price']);
                            $item_quantity = $csv_pr['quantity'];
                            $item_tax_rate = $csv_pr['item_tax_rate'];
                            $item_discount = $csv_pr['discount'];
                            $item_serial = $csv_pr['serial'];

                            if (isset($item_code) && isset($item_net_price) && isset($item_quantity)) {
                                $product_details = $this->sales_model->getProductByCode($item_code);

                                if (isset($item_discount)) {
                                    $discount = $item_discount;
                                    $dpos = strpos($discount, $percentage);
                                    if ($dpos !== false) {
                                        $pds = explode("%", $discount);
                                        $pr_discount = $this->sma->formatDecimal(((($this->sma->formatDecimal($item_net_price)) * (Float) ($pds[0])) / 100), 4);
                                    } else {
                                        $pr_discount = $this->sma->formatDecimal($discount);
                                    }
                                } else {
                                    $pr_discount = 0;
                                }
                                $item_net_price = $this->sma->formatDecimal(($item_net_price - $pr_discount), 4);
                                $pr_item_discount = $this->sma->formatDecimal(($pr_discount * $item_quantity), 4);
                                $product_discount += $pr_item_discount;

                                if (isset($item_tax_rate) && $item_tax_rate != 0) {

                                    if ($tax_details = $this->sales_model->getTaxRateByName($item_tax_rate)) {
                                        $pr_tax = $tax_details->id;
                                        if ($tax_details->type == 1) {

                                            $item_tax = $this->sma->formatDecimal((($item_net_price) * $tax_details->rate) / 100, 4);
                                            $tax = $tax_details->rate . "%";

                                        } elseif ($tax_details->type == 2) {
                                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                                            $tax = $tax_details->rate;
                                        }
                                        $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_quantity), 4);
                                    } else {
                                        $this->session->set_flashdata('error', lang("tax_not_found") . " ( " . $item_tax_rate . " ). " . lang("line_no") . " " . $rw);
                                        redirect($_SERVER["HTTP_REFERER"]);
                                    }

                                } elseif ($product_details->tax_rate) {

                                    $pr_tax = $product_details->tax_rate;
                                    $tax_details = $this->site->getTaxRateByID($pr_tax);
                                    if ($tax_details->type == 1) {

                                        $item_tax = $this->sma->formatDecimal((($item_net_price) * $tax_details->rate) / 100, 4);
                                        $tax = $tax_details->rate . "%";

                                    } elseif ($tax_details->type == 2) {

                                        $item_tax = $this->sma->formatDecimal($tax_details->rate);
                                        $tax = $tax_details->rate;

                                    }
                                    $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_quantity), 4);

                                } else {
                                    $item_tax = 0;
                                    $pr_tax = 0;
                                    $pr_item_tax = 0;
                                    $tax = "";
                                }
                                $product_tax += $pr_item_tax;
                                $subtotal = $this->sma->formatDecimal((($item_net_price * $item_quantity) + $pr_item_tax), 4);
                                $unit = $this->site->getUnitByID($product_details->unit);

                                $products[] = array(
                                    'product_id' => $product_details->id,
                                    'product_code' => $item_code,
                                    'product_name' => $item_name,
                                    'product_type' => $item_type,
                                    'option_id' => $item_option->id,
                                    'net_unit_price' => $item_net_price,
                                    'quantity' => $item_quantity,
                                    'product_unit_id' => $product_details->unit,
                                    'product_unit_code' => $unit->code,
                                    'unit_quantity' => $item_quantity,
                                    'warehouse_id' => $warehouse_id,
                                    'item_tax' => $pr_item_tax,
                                    'tax_rate_id' => $pr_tax,
                                    'tax' => $tax,
                                    'discount' => $item_discount,
                                    'item_discount' => $pr_item_discount,
                                    'subtotal' => $subtotal,
                                    'serial_no' => $item_serial,
                                    'unit_price' => $this->sma->formatDecimal(($item_net_price + $item_tax), 4),
                                    'real_unit_price' => $this->sma->formatDecimal(($item_net_price + $item_tax + $pr_discount), 4),
                                );

                                $total += $this->sma->formatDecimal(($item_net_price * $item_quantity), 4);
                            }

                        } else {
                            $this->session->set_flashdata('error', lang("pr_not_found") . " ( " . $csv_pr['code'] . " ). " . lang("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $rw++;
                    }

                }
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal(((($total + $product_tax) * (Float) ($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $this->sma->formatDecimal(($order_discount + $product_discount), 4);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal(((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = null;
            }

            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total = $this->sma->formatDecimal(($total + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount), 4);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => 0,
                'created_by' => $this->session->userdata('user_id'),
            );

            if ($payment_status == 'paid') {

                $payment = array(
                    'date' => $date,
                    'reference_no' => $this->site->getReference('pay'),
                    'amount' => $grand_total,
                    'paid_by' => 'cash',
                    'cheque_no' => '',
                    'cc_no' => '',
                    'cc_holder' => '',
                    'cc_month' => '',
                    'cc_year' => '',
                    'cc_type' => '',
                    'created_by' => $this->session->userdata('user_id'),
                    'note' => lang('auto_added_for_sale_by_csv') . ' (' . lang('sale_reference_no') . ' ' . $reference . ')',
                    'type' => 'received',
                );

            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->sma->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == true && $this->sales_model->addSale($data, $products, $payment)) {
            $this->session->set_userdata('remove_slls', 1);
            $this->session->set_flashdata('message', lang("sale_added"));
            admin_redirect("sales");
        } else {

            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['slnumber'] = $this->site->getReference('so');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale_by_csv')));
            $meta = array('page_title' => lang('add_sale_by_csv'), 'bc' => $bc);
            $this->page_construct('sales/sale_by_csv', $meta, $this->data);

        }
    }

    public function update_status($id)
    {

        $this->form_validation->set_rules('status', lang("sale_status"), 'required');

        if ($this->form_validation->run() == true) {
            $status = $this->input->post('status');
            $note = $this->sma->clear_tags($this->input->post('note'));
        } elseif ($this->input->post('update')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'sales');
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateStatus($id, $status, $note)) {
            $this->session->set_flashdata('message', lang('status_updated'));
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'sales');
        } else {

            $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
            $this->data['returned'] = FALSE;
            if ($this->data['inv']->sale_status == 'returned' || $this->data['inv']->return_id) {
                $this->data['returned'] = TRUE;
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'sales/update_status', $this->data);

        }
    }

    public function packaging($id)
    {

            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['returned'] = FALSE;
            if ($sale->sale_status == 'returned' || $sale->return_id) {
                $this->data['returned'] = TRUE;
            }
            $this->data['warehouse'] = $this->site->getWarehouseByID($sale->warehouse_id);
            $items = $this->sales_model->getAllInvoiceItems($sale->id);
            foreach ($items as $item) {
                $packaging[] = array(
                    'name' => $item->product_code.' - '.$item->product_name,
                    'quantity' => $item->quantity.' '.$item->product_unit_code,
                    'rack' => $this->sales_model->getItemRack($item->product_id, $sale->warehouse_id),
                    );
            }
            $this->data['packaging'] = $packaging;
            $this->data['sale'] = $sale;

            $this->load->view($this->theme.'sales/packaging', $this->data);

    }
	
    public function ff_search0()
    {
        $term = $this->input->get('term', true);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . admin_url('welcome') . "'; }, 10);</script>");
        }
        $analyzed = $this->sma->analyze_term($term);
        $sr = $analyzed['term'];
        $option_id = $analyzed['option_id'];
        $rows = $this->sales_model->getSearch($sr);
        if ($rows) {
            foreach ($rows as $row) {
				$reference_no = $row->reference_no." (".$row->customer." )";
                $pr[] = array('id' => $row->id, 'label' => $reference_no);
                $r++;
            }
            $this->sma->send_json($pr);
        } else {
            $this->sma->send_json(array(array('id' => 0, 'label' => lang('no_found'), 'value' => $term)));
        }
    }
	
	
    function shipping()
    {
		$condition_name = $this->input->get('condition_name', true);
		//$condition_name = $condition_name == 'price' ? $condition_name : 'item';
		$price = $this->input->get('is_price', true);
		$delivery_type = $this->input->get('delivery_type', true);
		$data = $this->sales_model->getshipping($condition_name, $price, $delivery_type);
		if($data == '999'){
			$data = array(array('price' => 0));
		}
        $this->sma->send_json($data);
    }
}
