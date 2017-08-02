<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$helpers = array('form');

		$this->load->helper($helpers);
	}

	public function index()
	{
		$this->load->view('login_view');
	}

	public function authenticate()
	{
		$user_data = $this->_user_exist();

		if ($this->_validate_input() && is_array($user_data))
		{
			$user_data['fullname'] = ucwords(strtolower($user_data['fullname']));
			$fullname              = explode(',', $user_data['fullname']);
			$user_data['fullname'] = ($fullname[0]);

			$this->session->set_userdata($user_data);

			if($user_data['user_type'] == 'admin')
			{
				redirect(base_url('index.php/admin/rooms'));
			}
			
			redirect(base_url('index.php/requestor/rooms'));
		}

		$data['message'] = '<span class="col-sm-12 alert alert-warning">You have no rights to access this system.</span>';

		$this->load->view('login_view', $data);

	}

	public function dashboard()
	{
		$data = array(
			'title' => 'Dashboard',
			'content' => 'dashboard_view',
		);

		$this->load->view('include/template', $data);
	}

	public function logout()
	{
		$this->session->sess_destroy();

		redirect('index.php/login/index');
	}

	protected function _validate_input()
	{
		$this->load->library('form_validation');

		$config = array(
		        array(
		                'field' => 'username',
		                'label' => 'Username',
		                'rules' => 'required|trim',
		                'errors' => array(
		                	'required' => 'You must provide a %s.',
		                ),
		        ),
		        array(
		                'field' => 'password',
		                'label' => 'Password',
		                'rules' => 'required|trim',
		                'errors' => array(
		                        'required' => 'You must provide a %s.',
		                ),
		        ),
			);

		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == false)
		{
			return false;
		}

		return true;
	}

	protected function _user_exist()
	{
		$this->load->model('user_model', 'user');

		return is_array($this->user->exist()) ? $this->user->exist() : false;
	}
}
