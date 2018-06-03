<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Websync extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->admin_load('websync', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('cmt_model');

    }

    function index()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('WebSync')));
        $meta = array('page_title' => lang('Web Sync'), 'bc' => $bc);
        $this->page_construct('websync/index', $meta, $this->data);
    }
	
	function getShippingAddress($value,$client,$session){
		
	//	$session = $client->login('admin_api', 'atcscms1234!');
	//	$data_api = $client->salesOrderList($session);
	//$client = new SoapClient('http://chevitdd.in.th/demo4/logancee/api/soap/?wsdl');
	//$session = $client->login('admin_api', 'atcscms1234!');
	//$result = $client->call($session, 'customer_address.info', '10');
	//$data_api->salesOrderList($session)
		
		//print_r($result);
	//	echo $result;
	
	
		$data_api = $client->multiCall($session, 
		array(
			 array('sales_order.info',$value)
		));
		foreach($data_api as $data){
				//foreach($data as $dt){
				//	print_r($data['shipping_address']);
					$address = $data['shipping_address']['street']." ".$data['shipping_address']['city']." ".$data['shipping_address']['region']." ".$data['shipping_address']['postcode'];
					$return = strip_tags($address);
			//	}
		}
		return $return;
	}
	
    function getWebsync()
    {
	
	/*

		$client = new SoapClient('http://chevitdd.in.th/demo4/logancee/api/soap/?wsdl');
		$session = $client->login('admin_api', 'atcscms1234!');

		$data_api = $client->multiCall($session, 
		array(
			 array('order.list')
		));
		$data_array = array();
		$i = 0;
		
		foreach($data_api as $data){
			if($data['status']=="processing"){
				$data_array[$i][] = $data['increment_id'];
				$data_array[$i][] = $data['shipping_firstname']." ".$data['shipping_lastname'];
				$data_array[$i][] = $data['shipping_address_id'];
				$data_array[$i][] = $data['total_qty_ordered'];
				$data_array[$i][] = $data['shipping_amount'];
				$data_array[$i][] = $data['subtotal'];
				$data_array[$i][] = $data['grand_total'];
				$data_array[$i][] = $data['status'];
				
				$i++;
			}

		}
			$output = array(
				"sEcho" =>1,
				"iTotalRecords" => 8,
				"iTotalDisplayRecords" => 8,
				"aaData" => array()
			);
			
			for($i=0;$i<count($data_array);$i++){
				$output['aaData'][$i] = $data_array[$i];
			}
			
			echo json_encode($output);*/
			
			
		$client = new SoapClient('http://chevitdd.in.th/demo4/logancee/api/soap/?wsdl');
		$session = $client->login('admin_api', 'atcscms1234!');
		$data_api = $client->multiCall($session, 
		array(
			 array('sales_order.list')
		));
		
		$data_array = array();
		
		foreach($data_api as $data){
			$t = 0;
				foreach($data as $dt){
				if($dt['status']=="processing"){
					$data_array[$t][] = $dt['increment_id'];
					$data_array[$t][] = $dt['shipping_firstname']." ".$dt['shipping_lastname'];
					$data_array[$t][] = $this->getShippingAddress($dt['increment_id'],$client,$session);
					$data_array[$t][] = $dt['total_qty_ordered'];
					$data_array[$t][] = $dt['shipping_amount'];
					$data_array[$t][] = $dt['subtotal'];
					$data_array[$t][] = $dt['grand_total'];
					$data_array[$t][] = $dt['status'];
					$t = $t+1;
				}
			//	print_r($data);
			//	echo $i;
				
				
			}
		}
		
		//print_r($data_array);
		
		$output = array(
				"sEcho" =>1,
				"iTotalRecords" => 8,
				"iTotalDisplayRecords" => 8,
				"aaData" => array()
			);
			
			for($i=0;$i<count($data_array);$i++){
				$output['aaData'][$i] = $data_array[$i];
			}
			
			echo json_encode($output);
	}
	
	

/*
    function add()
    {
        $this->form_validation->set_rules('bank', lang("banktransfer"), 'required|min_length[3]');
		$bank_default = $this->cmt_model->bankthailand();
        if ($this->form_validation->run() == true) {
            $data = array(
                'bank' => $this->input->post('bank'),
                'account_name' => $this->input->post('account_name'),
                'account_number' => $this->input->post('account_number'),
                'nickname' => $this->input->post('nickname'),
            );
			
        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("banktransfer");
        }

        if ($this->form_validation->run() == true && $this->cmt_model->addBanktransfer($data)) {
            $this->session->set_flashdata('message', lang("banktransfer_added"));
            admin_redirect("banktransfer");
        } else {

            $this->data['bank'] = array('name' => 'bank',
                'id' => 'bank',
                'type' => 'input',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('bank'),
            );
			$this->data['bank_default'] = $bank_default;
            $this->data['error'] = validation_errors();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'banktransfer/add', $this->data);

        }
    }

    function edit($id = NULL)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
		
        $this->form_validation->set_rules('bank', lang("banktransfer"), 'required|min_length[3]');
	
        if ($this->form_validation->run() == true) {
            $data = array(
                'bank' => $this->input->post('bank'),
                'account_name' => $this->input->post('account_name'),
                'account_number' => $this->input->post('account_number'),
                'nickname' => $this->input->post('nickname'),
            );
        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("banktransfer");
        }
        if ($this->form_validation->run() == true && $this->cmt_model->updateBanktranfer($id, $data)) {

            $this->session->set_flashdata('message', lang("banktransfer_updated"));
            admin_redirect("banktransfer");

        } else {
            $comment = $this->cmt_model->getBanktranferByID($id);
            $this->data['bank'] = array(
				'name' => 'bank',
                'id' => 'bank',
                'type' => 'input',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('bank', $comment->comment),
            );
			
			$this->data['bank_default'] = $bank_default;
            $this->data['banktransfer'] = $comment;
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['error'] = validation_errors();
            $this->load->view($this->theme . 'banktransfer/edit', $this->data);

        }
    }

	
    function delete($id = NULL)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->cmt_model->deleteBank($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("banktransfer_deleted")));
        }
    }*/

}
