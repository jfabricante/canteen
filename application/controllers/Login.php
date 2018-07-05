<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$helpers = array('form');

		$this->load->helper($helpers);

		$this->load->model('user_model', 'user');
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

			$this->session->set_userdata($user_data);

			if ($user_data['user_type'] == 'administrator')
			{
				redirect('/item/list_');
			}
			else if ($user_data['user_type'] == 'cashier')
			{
				redirect('/transaction/index');
			}
			else
			{
				redirect('user/purchased_items');
			}
			
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

		redirect('login/index');
	}

	public function update_users_meal_allowance_tbl()
	{
		$new_users = $this->user->getDifference();

		$users_meal_allowance = array();

		$users_meal_history = array();

		if (count($new_users))
		{
			foreach ($new_users as $user)
			{
				$users_meal_allowance[] = array(
					'user_id'               => $user['id'],
					'meal_allowance'        => 0,
					'datetime'              => date("Y-m-d H:i:s"),
					'last_meal_credit'      => 0,
					'last_meal_credit_date' => date('Y-m-d H:i:s'),
				);

				$users_meal_history[] = array(
					'user_id'      => $user['id'],
					'payroll_date' => date('Y-m-d H:i:s'),
					'adj_code'     => 'New Entry',
					'reference'    => 'Auto',
					'adj_amount'   => 0,
					'last_update'  => date('Y-m-d H:i:s')
				);
			}

			$this->user->updateUsersMealAllowance($users_meal_allowance);

			$this->user->appendMealHistory($users_meal_history);

			echo 'Updated';
		}
		else
		{
			echo 'No new TM';
		}
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
		return is_array($this->user->exist()) ? $this->user->exist() : false;
	}
}
