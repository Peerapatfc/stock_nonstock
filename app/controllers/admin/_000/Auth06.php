<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->admin_load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('sales_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '30720';
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->admin_model('auth_model');
		$this->load->admin_model('companies_model');
		$this->load->admin_model('sales_model');
        $this->load->library('ion_auth');
    }

    function index()
    {

        if (!$this->loggedIn) {
            admin_redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function users()
    {
        if ( ! $this->loggedIn) {
            admin_redirect('login');
        }
        if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin/welcome');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/index', $meta, $this->data);
    }

    function getUsers()
    {
        if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
        }
		
		//IF(type = 'P', IFNULL(amount,0), IFNULL(amount,0) * -1) as amount
        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('users').".id as id, first_name, last_name, email, company, sum(points_current)- sum(spent_points) as points, " . $this->db->dbprefix('groups') . ".name, active")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->join('award_points', 'award_points.user_id=users.id', 'left')
            ->group_by('users.id')
			->where('award_points.approve', NULL)->or_where('award_points.approve', '1')
            ->where('company_id', NULL)
            ->edit_column('active', '$1__$2', 'active, id')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('auth/profile/$1') . "' class='tip' title='" . lang("edit_user") . "'><i class=\"fa fa-edit\"></i></a></div>", "id");

			
// SELECT * FROM `sma_users`
// LEFT JOIN `sma_award_points`
// ON `sma_award_points`.`user_id` = `sma_users`.`id`

// WHERE `sma_award_points`.`approve`  IS NULL || `sma_award_points`.`approve` = '1'
	
			
        if (!$this->Owner) {
            $this->datatables->unset_column('id');
        }
        echo $this->datatables->generate();
    }

    function getUserLogins($id = NULL)
    {
        if (!$this->ion_auth->in_group(array('owner', 'admin'))) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            admin_redirect('welcome');
        }
        $this->load->library('datatables');
        $this->datatables
            ->select("login, ip_address, time")
            ->from("user_logins")
            ->where('user_id', $id);

        echo $this->datatables->generate();
    }

    function delete_avatar($id = NULL, $avatar = NULL)
    {

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            unlink('assets/uploads/avatars/' . $avatar);
            unlink('assets/uploads/avatars/thumbs/' . $avatar);
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('avatar');
            }
            $this->db->update('users', array('avatar' => NULL), array('id' => $id));
            $this->session->set_flashdata('message', lang("avatar_deleted"));
           // die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function profile($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'admin');
        }
		
		$allbrand = $this->auth_model->getAllBrands();
		$agent = $this->auth_model->getAgent($id);
		
		
        if (!$id || empty($id)) {
            admin_redirect('auth');
        }
        $this->data['title'] = lang('profile');
        $user = $this->ion_auth->user($id)->row();
		$totalpoint = $this->auth_model->totalpoint($id);
		$walletpoint = $this->auth_model->walletpoint($user->id);
		
		
        $groups = $this->ion_auth->groups()->result_array();
		
		$this->data['walletpoint'] = $walletpoint;
		
        $this->data['totalpoint'] = $totalpoint;
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['warehouses'] = $this->site->getAllWarehouses();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'class' => 'form-control',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

		$this->data['companies'] = $this->sales_model->customeridbyuser($id);

		$this->data['allbrand'] = $allbrand;
		$this->data['agent'] = $agent[0]->agent;
		$this->data['vendor_inventory'] = $agent[0];
        $this->data['id'] = $id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('profile')));
        $meta = array('page_title' => lang('profile'), 'bc' => $bc);
        $this->page_construct('auth/profile', $meta, $this->data);
    }

    public function captcha_check($cap)
    {
        $expiration = time() - 300; // 5 minutes limit
        $this->db->delete('captcha', array('captcha_time <' => $expiration));

        $this->db->select('COUNT(*) AS count')
            ->where('word', $cap)
            ->where('ip_address', $this->input->ip_address())
            ->where('captcha_time >', $expiration);

        if ($this->db->count_all_results('captcha')) {
            return true;
        } else {
            $this->form_validation->set_message('captcha_check', lang('captcha_wrong'));
            return FALSE;
        }
    }


    function login($m = NULL)
    {
        if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            admin_redirect('welcome');
        }
        $this->data['title'] = lang('login');

        if ($this->Settings->captcha) {
            $this->form_validation->set_rules('captcha', lang('captcha'), 'required|callback_captcha_check');
        }

        if ($this->form_validation->run() == true) {

            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                if ($this->Settings->mmode) {
                    if (!$this->ion_auth->in_group('owner')) {
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        admin_redirect('auth/logout');
                    }
                }
                if ($this->ion_auth->in_group('customer') || $this->ion_auth->in_group('supplier')) {
                    if(file_exists(APPPATH.'controllers'.DIRECTORY_SEPARATOR.'shop'.DIRECTORY_SEPARATOR.'Shop.php')) {
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect(base_url());
                    } else {
                        admin_redirect('auth/logout/1');
                    }
                }
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $referrer = ($this->session->userdata('requested_page') && $this->session->userdata('requested_page') != 'admin') ? $this->session->userdata('requested_page') : 'welcome';
                admin_redirect($referrer);
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                admin_redirect('login');
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = $this->session->flashdata('message');
            if ($this->Settings->captcha) {
                $this->load->helper('captcha');
                $vals = array(
                    'img_path' => './assets/captcha/',
                    'img_url' => base_url('assets/captcha/'),
                    'img_width' => 150,
                    'img_height' => 34,
                    'word_length' => 5,
                    'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
                );
                $cap = create_captcha($vals);
                $capdata = array(
                    'captcha_time' => $cap['time'],
                    'ip_address' => $this->input->ip_address(),
                    'word' => $cap['word']
                );

                $query = $this->db->insert_string('captcha', $capdata);
                $this->db->query($query);
                $this->data['image'] = $cap['image'];
                $this->data['captcha'] = array('name' => 'captcha',
                    'id' => 'captcha',
                    'type' => 'text',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => lang('type_captcha')
                );
            }

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('email'),
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => lang('password'),
            );
            $this->data['allow_reg'] = $this->Settings->allow_reg;
            if ($m == 'db') {
                $this->data['message'] = lang('db_restored');
            } elseif ($m) {
                $this->data['error'] = lang('we_are_sorry_as_this_sction_is_still_under_development.');
            }

            $this->load->view($this->theme . 'auth/login', $this->data);
        }
    }

    function reload_captcha()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './assets/captcha/',
            'img_url' => base_url('assets/captcha/'),
            'img_width' => 150,
            'img_height' => 34,
            'word_length' => 5,
            'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
        );
        $cap = create_captcha($vals);
        $capdata = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('captcha', $capdata);
        $this->db->query($query);
        //$this->data['image'] = $cap['image'];

        echo $cap['image'];
    }

    function logout($m = NULL)
    {

        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());

        admin_redirect('login/' . $m);
    }

    function change_password()
    {
        if (!$this->ion_auth->logged_in()) {
            admin_redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect('auth/profile/' . $user->id . '/#cpassword');
        } else {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                admin_redirect('auth/profile/' . $user->id . '/#cpassword');
            }
        }
    }

    function forgot_password()
    {
        $this->form_validation->set_rules('forgot_email', lang('email_address'), 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);
            admin_redirect("login#forgot_password");
        } else {

            $identity = $this->ion_auth->where('email', strtolower($this->input->post('forgot_email')))->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                admin_redirect("login#forgot_password");
            }

            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if ($forgotten) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                admin_redirect("login#forgot_password");
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                admin_redirect("login#forgot_password");
            }
        }
    }

    public function reset_password($code = NULL)
    {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            $this->form_validation->set_rules('new', lang('password'), 'required|min_length[8]|max_length[25]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', lang('confirm_password'), 'required');

            if ($this->form_validation->run() == false) {

                $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['title'] = lang('reset_password');
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                    'data-bv-regexp-message' => lang('pasword_hint'),
                    'placeholder' => lang('new_password')
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'class' => 'form-control',
                    'data-bv-identical' => 'true',
                    'data-bv-identical-field' => 'new',
                    'data-bv-identical-message' => lang('pw_not_same'),
                    'placeholder' => lang('confirm_password')
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;
                $this->data['identity_label'] = $user->email;
                //render
                $this->load->view($this->theme . 'auth/reset_password', $this->data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error(lang('error_csrf'));

                } else {
                    // finally change the password
                    $identity = $user->email;

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        //$this->logout();
                        admin_redirect('login');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        admin_redirect('auth/reset_password/' . $code);
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            admin_redirect("login#forgot_password");
        }
    }

    function activate($id, $code = false)
    {

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->Owner) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            if ($this->Owner) {
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                admin_redirect("auth/login");
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            admin_redirect("forgot_password");
        }
    }

    function deactivate($id = NULL)
    {
        $this->sma->checkPermissions('users', TRUE);
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string)$id : (int)$id;
        $this->form_validation->set_rules('confirm', lang("confirm"), 'required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->post('deactivate')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['user'] = $this->ion_auth->user($id)->row();
                $this->data['modal_js'] = $this->site->modal_js();
                $this->load->view($this->theme . 'auth/deactivate_user', $this->data);
            }
        } else {

            if ($this->input->post('confirm') == 'yes') {
                if ($id != $this->input->post('id')) {
                    show_error(lang('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->Owner) {
                    $this->ion_auth->deactivate($id);
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                }
            }

            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function create_user()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
		$allbrand = $this->auth_model->getAllBrands();
        $this->data['title'] = "Create User";
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('group', lang("group"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = $this->input->post('notify');

			$brand_targets = $this->input->post('brand_targets');
			$brand_val = "";
			foreach($brand_targets as $brand){
				$brand_val .= $brand.',';
			}
			$brand_val = substr($brand_val,0,-1);

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '3',
                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),
                'view_right' => $this->input->post('view_right'),
                'edit_right' => $this->input->post('edit_right'),
                'allow_discount' => $this->input->post('allow_discount'),
				'brand_targets' => $brand_val,
				'agent' => $this->input->post('agent'),
				'vendor_inventory' => $this->input->post('vendor_inventory'),
				'type_commission' => $this->input->post('type_commission'),
            );
			
			
            $data = array(
				'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
                'email' => $email,
                'group_id' => NULL,
                'group_name' => 'user',
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
				'user_id' => 'Administrator',
				'facebook' => $this->input->post('facebook'),
				'instragram' => $this->input->post('instragram'),
				'line' => $this->input->post('line'),
            );

            $active = $this->input->post('status');
        }
		

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
			$this->companies_model->addCompany($data);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            admin_redirect("auth/users");

        } else {
			$this->data['allbrand'] = $allbrand;
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('auth/create_user', $meta, $this->data);
        }
    }

    function edit_user($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang("edit_user");

        if (!$this->loggedIn || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $user = $this->ion_auth->user($id)->row();

        if ($user->username != $this->input->post('username')) {
            $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        }
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        }
		$brand_targets = $this->input->post('brand_targets');
		$brand_val = "";
		foreach($brand_targets as $brand){
			$brand_val .= $brand.',';
		}
		$brand_val = substr($brand_val,0,-1);
        if ($this->form_validation->run() === TRUE) {

            if ($this->Owner) {
                if ($id == $this->session->userdata('user_id')) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                        'gender' => $this->input->post('gender'),
                    );
					$customer_data = array(
						'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
						'group_id' => NULL,
						'company' => $this->input->post('company'),
						'address' => $this->input->post('address'),
						'phone' => $this->input->post('phone'),
						'facebook' => $this->input->post('facebook'),
						'instragram' => $this->input->post('instragram'),	

						'line' => $this->input->post('line'),
					);
                } elseif ($this->ion_auth->in_group('customer', $id) || $this->ion_auth->in_group('supplier', $id)) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                        'gender' => $this->input->post('gender'),
                    );
					
					$customer_data = array(
						'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
						'group_id' => NULL,
						'company' => $this->input->post('company'),
						'address' => $this->input->post('address'),
						'phone' => $this->input->post('phone'),
						'facebook' => $this->input->post('facebook'),
						'instragram' => $this->input->post('instragram'),

						'line' => $this->input->post('line'),
					);
                } else {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'username' => $this->input->post('username'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        'gender' => $this->input->post('gender'),
                        'active' => $this->input->post('status'),
                        'group_id' => $this->input->post('group'),
                        'biller_id' => $this->input->post('biller') ? $this->input->post('biller') : NULL,
                        'warehouse_id' => $this->input->post('warehouse') ? $this->input->post('warehouse') : NULL,
                        'award_points' => $this->input->post('award_points'),
                        'view_right' => $this->input->post('view_right'),
                        'edit_right' => $this->input->post('edit_right'),
                        'allow_discount' => $this->input->post('allow_discount'),
						'brand_targets' => $brand_val,
						'agent' => $this->input->post('agent'),
						'edit_user' => $this->input->post('edit_user'),
						'type_commission' => $this->input->post('type_commission'),
						'vendor_inventory' => $this->input->post('vendor_inventory'),
                    );
					$customer_data = array(
						'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
						'group_id' => NULL,
						'company' => $this->input->post('company'),
						'address' => $this->input->post('address'),
						'phone' => $this->input->post('phone'),
						'facebook' => $this->input->post('facebook'),
						'instragram' => $this->input->post('instragram'),

						'line' => $this->input->post('line'),
						'email' => $this->input->post('email'),
					);
                }

            } elseif ($this->Admin) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                    'gender' => $this->input->post('gender'),
                    'active' => $this->input->post('status'),
                    'award_points' => $this->input->post('award_points'),
                );
				
				$customer_data = array(
					'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
					'group_id' => NULL,
					'company' => $this->input->post('company'),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
					'facebook' => $this->input->post('facebook'),
					'instragram' => $this->input->post('instragram'),			
					
					'line' => $this->input->post('line'),
				);
				
            } else {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                    'gender' => $this->input->post('gender'),
                );
				$customer_data = array(
					'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),
					'group_id' => NULL,
					'company' => $this->input->post('company'),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
					'facebook' => $this->input->post('facebook'),
					'instragram' => $this->input->post('instragram'),
					
					'line' => $this->input->post('line'),
				);
            }

            if ($this->Owner) {
                if ($this->input->post('password')) {
                    if (DEMO) {
                        $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $this->form_validation->set_rules('password', lang('edit_user_validation_password_label'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', lang('edit_user_validation_password_confirm_label'), 'required');

                    $data['password'] = $this->input->post('password');
                }
            }

			$user_id = $this->input->post('user_id');
             // $this->sma->print_arrays($data);
			 // exit(0);
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data) && $this->companies_model->updateCompany($user_id, $customer_data)) {
            $this->session->set_flashdata('message', lang('user_updated'));
            admin_redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html = $this->load->view('header', $this->viewdata, $render);
        $view_html .= $this->load->view($view, $this->viewdata, $render);
        $view_html = $this->load->view('footer', $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

    /**
     * @param null $id
     */
    function update_avatar($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        //validate form input
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');

        if ($this->form_validation->run() == true) {

            if ($_FILES['avatar']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/avatars/' . $photo;
                $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $user = $this->ion_auth->user($id)->row();
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatar($id, $photo)) {
            unlink('assets/uploads/avatars/' . $user->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $user->avatar);
			if($id == $this->session->userdata('user_id')){
				$this->session->set_userdata('avatar', $photo);
			}
            $this->session->set_flashdata('message', lang("avatar_updated"));
            admin_redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("auth/profile/" . $id);
        }
    }

    function register()
    {
        $this->data['title'] = "Register";
        if (!$this->allow_reg) {
            $this->session->set_flashdata('error', lang('registration_is_disabled'));
            admin_redirect("login");
        }

        $this->form_validation->set_message('is_unique', lang('account_exists'));
        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
        $this->form_validation->set_rules('email', lang('email_address'), 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('usernam', lang('usernam'), 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');
        if ($this->Settings->captcha) {
            $this->form_validation->set_rules('captcha', lang('captcha'), 'required|callback_captcha_check');
        }

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            admin_redirect("login");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();

            $this->load->helper('captcha');
            $vals = array(
                'img_path' => './assets/captcha/',
                'img_url' => admin_url() . 'assets/captcha/',
                'img_width' => 150,
                'img_height' => 34,
            );
            $cap = create_captcha($vals);
            $capdata = array(
                'captcha_time' => $cap['time'],
                'ip_address' => $this->input->ip_address(),
                'word' => $cap['word']
            );

            $query = $this->db->insert_string('captcha', $capdata);
            $this->db->query($query);
            $this->data['image'] = $cap['image'];
            $this->data['captcha'] = array('name' => 'captcha',
                'id' => 'captcha',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('type_captcha')
            );

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->load->view('auth/register', $this->data);
        }
    }

    function user_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        if ($id != $this->session->userdata('user_id')) {
							$getEmail = $this->auth_model->getEmail($id);
                            $this->auth_model->delete_user($id,$getEmail->email);
							//****************
                        }
                    }
                    $this->session->set_flashdata('message', lang("users_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('first_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('last_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('group'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $user = $this->site->getUser($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->first_name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->last_name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->company);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->group);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'users_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    return create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_user_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function delete($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if ($this->input->get('id')) { $id = $this->input->get('id'); }

        if ( ! $this->Owner || $id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin/welcome');
        }

        if ($this->auth_model->delete_user($id)) {
            //echo lang("user_deleted");
            $this->session->set_flashdata('message', 'user_deleted');
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	/* Awardpoints */
    function getAwardpoints($id = NULL)
    {
		$this->load->library('datatables');
        $this->datatables
            ->select("award_points.id as id, award_points.reference_no, award_points.points_current, award_points.date_insertion, users.username, award_points.approve, award_points.points_rule_id")
            ->from("award_points")
			->join('users', "users.id = award_points.user_id", 'left');
			
		$user_id = !is_null($id) ? $id : $this->session->userdata('user_id');
		$this->datatables->where('award_points.status', '1');
		$this->datatables->where('award_points.user_id', $user_id);
		$this->datatables->where('award_points.spent_points <=', 0);

        echo $this->datatables->generate();
    }
	
	
    function spentpoints($id = NULL)
    {
		if ($this->Owner || $this->Admin) {
			$action = '<div class="text-center"><a href="'. admin_url('auth/approvepoints/$1') .'" class="btn btn-success">'. lang("approve") .'</a></div>';
        }else{
			#$action = '<div class="text-center"><a href="javascript:void(0)" class="btn btn-default">'. lang("approve") .'</a></div>';
		}
		
		$this->load->library('datatables');
        $this->datatables
            ->select("
				{$this->db->dbprefix('award_points')}.id as id,
				{$this->db->dbprefix('award_points')}.reference_no,
				{$this->db->dbprefix('award_points')}.spent_points,
				{$this->db->dbprefix('award_points')}.date_insertion,
				{$this->db->dbprefix('users')}.username,
				{$this->db->dbprefix('award_points')}.approve,
				
			")
            ->from("award_points")
			->join('users', "{$this->db->dbprefix('award_points')}.user_id = {$this->db->dbprefix('users')}.id", 'left');
			
			$user_id = !is_null($id) ? $id : $this->session->userdata('user_id');
			if ($this->Owner || $this->Admin) {
				$this->datatables->where('award_points.points_rule_id !=', null);
			}
			//else{
				$this->datatables->where('award_points.user_id', $user_id);
			//}
			$this->datatables->where('award_points.spent_points >', 0);
			
		$this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	
    function getAwardpointsrule()
    {
		$dateCur = date("Y-m-d");
		if ($this->Owner || $this->Admin) {
			#$action = '<div class="text-center"><a href="#" class="btn btn-success">'. lang("edit") .'</a></div>';
			$action = "<div class=\"text-center\"><a href='" . admin_url('award_points/edit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_award_points") . "'><i class=\"fa fa-edit\"></i></a> <a href='" . admin_url('award_points/delete/$1') . "' class='tip po' title='<b>" . $this->lang->line("delete_award_points") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('award_points/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>";
	
        }else{
			$action = '<div class="text-center"><a href="'. admin_url('auth/exchangepoints/$1') .'" class="btn btn-success">'. lang("exchange") .'</a></div>';
		}
		$this->load->library('datatables');
        $this->datatables
            ->select("
				award_points_rule.attachment,
				award_points_rule.name,
				award_points_rule.points,
				award_points_rule.start,
				award_points_rule.end,
				award_points_rule.id as id,
			")
            ->from("award_points_rule");
           // ->where('award_points_rule.start <=', $dateCur)
			//->where('award_points_rule.end >=', $dateCur);
			//$this->datatables->unset_column('id');

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
		
    function getAwardpointsruleByUser()
    {
		$dateCur = date("Y-m-d");
		if ($this->Owner || $this->Admin) {
			#$action = '<div class="text-center"><a href="#" class="btn btn-success">'. lang("edit") .'</a></div>';
			$action = "<div class=\"text-center\"><a href='" . admin_url('award_points/edit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_award_points") . "'><i class=\"fa fa-edit\"></i></a> <a href='" . admin_url('award_points/delete/$1') . "' class='tip po' title='<b>" . $this->lang->line("delete_award_points") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('award_points/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>";
	
        }else{
			$action = '<div class="text-center"><a href="'. admin_url('auth/exchangepoints/$1') .'" class="btn btn-success">'. lang("exchange") .'</a></div>';
		}
		$this->load->library('datatables');
        $this->datatables
            ->select("
				award_points_rule.attachment,
				award_points_rule.name,
				award_points_rule.points,
				award_points_rule.start,
				award_points_rule.end,
				award_points_rule.id as id,
			")
            ->from("award_points_rule")
            ->where('award_points_rule.start <=', $dateCur)
			->where('award_points_rule.end >=', $dateCur);
			//$this->datatables->unset_column('id');

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
		
		
    function exchangepoints($id = NULL)
    {
		$date = date('Y-m-d H:i:s');
		$awardpointsrule = $this->auth_model->getawardpointsrule($id);
		$reference_no = $awardpointsrule->name;
		$point = $awardpointsrule->points;
        $data = array(
			'reference_no' => $reference_no,
            'user_id' => $this->session->userdata('user_id'),
            'points_current' => 0,
			'points_rule_id' => $id,
			'date_insertion' => $date,
			'spent_points' => $point,
			'status' => 1,
        );
		$total_points = $this->auth_model->totalpoint($this->session->userdata('user_id'));
		if($this->auth_model->exchangepoints($data, $total_points)){
			$this->session->set_flashdata('message', lang("exchange_points_successfully"));
			$uri = "users/profile/".$this->session->userdata('user_id');
			admin_redirect($uri);
		}
	}


	/* end point */
	
	

    function id_card()
    {
        $data = array(
			//'id' => $this->session->userdata('user_id'),
			'id' => $this->input->post('user_id'),
        );

            if ($_FILES['id_card']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
				
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('id_card')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $data['id_card'] = $photo;
            }
			
		if($this->auth_model->adddocument($data)){
			$this->session->set_flashdata('message', lang("add_document_successfully"));
			$uri = "users/profile/".$this->input->post('user_id');
			admin_redirect($uri);
		}
	}
	
	
    function account_book()
    {
        $data = array(
			'id' => $this->input->post('user_id'),
        );

            if ($_FILES['account_book']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
				
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('account_book')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $data['account_book'] = $photo;
            }
			
		if($this->auth_model->adddocument($data)){
			$this->session->set_flashdata('message', lang("add_document_successfully"));
			$uri = "users/profile/".$this->input->post('user_id');
			admin_redirect($uri);
		}
	}


    function usergeneral()
    {
        $data = array(
			'line' => $this->input->post('line'),
			'facebook' => $this->input->post('facebook'),
            'address' => $this->input->post('address'),
			'id'  => $this->input->post('id'),
		);

		if($this->auth_model->adddocument($data)){
			$this->session->set_flashdata('message', lang("add_user_general_success"));
			$uri = "users/profile/".$this->session->userdata('user_id');
			admin_redirect($uri);
		}
    }
	
	 public function update_wallet()
    {
		$wallet_type =  $this->input->post('wallet_type');
		$wallet_amount = "";
		if(strtolower($wallet_type)=="deposit"){
			$wallet_amount = number_format($this->input->post('wallet_amount'),4,'.','');
		}else if(strtolower($wallet_type)=="withdraw"){
			$wallet_amount = number_format((-1)*(abs($this->input->post('wallet_amount'))),4,'.','');
		}
		   $data = array(
			'wallet_type' 	=> $wallet_type,
			'wallet_amount' 	=> $wallet_amount,
			'user_id' 		=> $this->input->post('user_id'),
        );
		
		if ($_FILES['upload_slip']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
				
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('upload_slip')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $data['upload_slip'] = $photo;
		}
		
		
		if($this->auth_model->update_wallet($data)){
			$this->session->set_flashdata('message', lang("approve_points_successfully"));
			$uri = "users/profile/".$data['user_id'];
			admin_redirect($uri);
		}
	}
	
	function approvewallet($id = NULL)
    {
        $data = array(
			'approve' 	=> "1",
			'id' 		=> $id ,
        );
		if($this->auth_model->approveWallet($data)){
			$this->session->set_flashdata('message', lang("approve_wallets_successfully"));
		}
	}
	
	 function getWallet($user_id)
    {

	  $this->load->library('datatables');
        $this->datatables
            ->select('id,date_wallet,sale_id,amount,upload_slip,type,approve')
            ->from("wallets")
            ->where('user_id', $user_id);

        echo $this->datatables->generate();

    }
	function getWalletForApprove()
    {
		//$v = $_GET['v'];
	  $this->load->library('datatables');
		$this->datatables
            ->select('wallets.id,wallets.date_wallet,wallets.amount,wallets.upload_slip,wallets.type,wallets.approve')
            ->from("wallets");
         //  ->where('wallets.approve', 0);
		

        echo $this->datatables->generate();

    }
	
	 function popup($id = NULL)
    {

		$upload_slip = $this->auth_model->getWalletSlip($id);
		$this->data['id'] = $id;
		$this->data['pic']['attachment'] = $upload_slip->upload_slip;
		$this->data['modal_js'] = $this->site->modal_js();
        $this->data['error'] = validation_errors();
        $this->load->view($this->theme . 'sales/popup', $this->data);
	}
	
	public function approveWalletByAdmin() {
		 if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
		$approve = $_POST['approve'];
		if (!empty($_POST['val'])) {
			$id_arr = array();
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
				array_push($id_arr,$id);
				$this->auth_model->approveWalletByAdmin($id, $dlDetails);
				if($approve=="1"){
					$this->session->set_flashdata('message', lang("Approve_success"));
				}else{
					$this->session->set_flashdata('message', lang("Disapprove_success"));
				}
				
			}
			$uri = "wallets";
			admin_redirect($uri);
			//print_r($id_arr);
		}
	}
	
}

