<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->admin_load('agent', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('agent_model');
    }

    function index($phone = NULL)
    {
		$this->data['company'] = $this->agent_model->getUserByCompany($phone);
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('agent')));
        $meta = array('page_title' => lang('agent'), 'bc' => $bc);
        $this->page_construct('agent/index', $meta, $this->data);
    }
}
