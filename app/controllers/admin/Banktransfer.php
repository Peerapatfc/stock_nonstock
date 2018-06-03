<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Banktransfer extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
     /*   if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/
        $this->lang->admin_load('banktransfer', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('cmt_model');

    }

    function index()
    {
     /*   if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/

        //$this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('banktransfer')));
        $meta = array('page_title' => lang('banktransfer'), 'bc' => $bc);
        $this->page_construct('banktransfer/index', $meta, $this->data);
    }

	
    function getBanktransfer()
    {
		if ($this->Owner || $this->Admin) {
			$this->load->library('datatables');
			$this->datatables
				->select("id, bank, account_name, account_number, nickname")
				->from("banktransfer")
				->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('banktransfer/edit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_banktransfer") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_banktransfer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('banktransfer/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
	
	}else{
			$this->load->library('datatables');
			$this->datatables
				->select("id, bank, account_name, account_number, nickname")
				->from("banktransfer")
				->where('user_id', $this->session->userdata('user_id'))
				->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('banktransfer/edit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_banktransfer") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_banktransfer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('banktransfer/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
	
	}

	   $this->datatables->unset_column('id');
        echo $this->datatables->generate();
    }

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
			 'user_id' => $this->session->userdata('user_id'),
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
     /*   if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/

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
			 'user_id' => $this->session->userdata('user_id'),
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
  /*      if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/

        if ($this->cmt_model->deleteBank($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("banktransfer_deleted")));
        }
    }

}
