<?php defined('BASEPATH') OR exit('No direct script access allowed');

class award_points extends MY_Controller
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
        $this->lang->admin_load('award_points', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('auth_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
    }
	

    function index()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('award_points')));
        $meta = array('page_title' => lang('award_points'), 'bc' => $bc);
        $this->page_construct('award_points/index', $meta, $this->data);
    }


    function add()
    {
        $this->form_validation->set_rules('name', lang("award_points"), 'required|min_length[3]');
        if ($this->form_validation->run() == true) {
            $data = array(
				'level' => $this->input->post('level'),
                'name' => $this->input->post('name'),
                'points' => $this->input->post('point'),
                'start' =>  date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('point_start')))),
                'end' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('point_end')))),
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

        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("award_points");
        }

        if ($this->form_validation->run() == true && $this->auth_model->addPointrule($data)) {
            $this->session->set_flashdata('message', lang("award_points_added"));
            admin_redirect("award_points");
        } else {
            $this->data['error'] = validation_errors();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'award_points/add', $this->data);

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
		
        $this->form_validation->set_rules('name', lang("award_points"), 'required|min_length[3]');
	
        if ($this->form_validation->run() == true) {
            $data = array(
				'level' => $this->input->post('level'),
                'name' => $this->input->post('name'),
                'points' => $this->input->post('point'),
                'start' =>  date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('point_start')))),
                'end' => date('Y-m-d', strtotime(str_replace('-', '/', $this->input->post('point_end')))),
				'id' => $id,
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
			
        } elseif ($this->input->post('submit')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("award_points");
        }
		
        if ($this->form_validation->run() == true && $this->auth_model->updatePointrule($data)) {
            $this->session->set_flashdata('message', lang("award_points_updated"));
            admin_redirect("award_points");
        } else {

            $this->data['inv'] = $this->auth_model->getPointrule($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['error'] = validation_errors();
            $this->load->view($this->theme . 'award_points/edit', $this->data);

        }
    }

	
    function delete($id = NULL)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->auth_model->deletePointrule($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("award_points_deleted")));
        }
    }
	
	
    function approve()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('award_points_approve')));
        $meta = array('page_title' => lang('award_points_approve'), 'bc' => $bc);
        $this->page_construct('award_points/approve', $meta, $this->data);
    }

	
    function approvedata($id = NULL)
    {
		$this->load->library('datatables');
        $this->datatables
            ->select("award_points.id as id, award_points.reference_no, award_points.spent_points, award_points.date_insertion, users.username, award_points.approve, award_points.qty, award_points.level")
            ->from("award_points")
			->join('users', "users.id = award_points.user_id", 'left');

		$this->datatables->where('award_points.spent_points >', 0);
		$this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	
	
	public function approvePointByAdmin() {
		 if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
		$approve = $_POST['approve'];
		if (!empty($_POST['val'])) {
			foreach ($_POST['val'] as $id) {
				if($approve=="1"){
					$dlDetails = array(
						'approve' => '1'
					);
				}else if($approve=="0"){
					$dlDetails = array(
						'approve' => '0'
					);
				}
				$awardpoints = $this->auth_model->getawardpointsById($id);
				$total_points = $this->auth_model->totalpoint($awardpoints->user_id);
				if($this->auth_model->updatepoints($id, $dlDetails)){
					if($approve=="1"){
						$this->session->set_flashdata('message', lang("Approve_success"));
					}else{
						$this->auth_model->returnpoints($awardpoints, $total_points);
						$this->session->set_flashdata('message', lang("Disapprove_success"));
					}
				}
				
			}
			$uri = "award_points/approve";
			admin_redirect($uri);
		}
	}
}
