<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('user_model', 'user');
		$this->load->model('ipc_model', 'ipc');
	}

	public function store_batch()
	{
		$data = $this->ipc->fetch_all(array('type' => 'array'));

		$ids = array_column($data, 'id');

		$this->user->store_batch($data)->assign_batch_role($ids);
	}

	public function list_()
	{
		$data = array(
				'title'   => 'List of Users',
				'content' => 'user/list_view',
				'users'   => $this->user->fetch()
			);

		$this->load->view('include/template', $data);
	}

	public function count()
	{
		echo '<pre>';
		print_r($this->user->users_count());
		echo '<br />';
		print_r($this->user->users_role_count());
		echo '</pre>';
	}

	public function truncate()
	{
		$this->user->truncate_tbl();
	}

	public function entity()
	{
		$config =  array(
				'emp_no' => $this->input->get('employee_no')
			);

		echo $this->user->read($config) ? json_encode($this->user->read($config)) : '';
	}

	public function balances()
	{
		$data = array(
				'title'    => 'Balances',
				'content'  => 'user/balances_view',
				'entities' => $this->user->fetch_balances()
			);

		$this->load->view('include/template', $data);
	}

	public function purchased_items()
	{
		// Change to standard format
		$config = array(
				'from' => date('Y-m-d' ,strtotime($this->input->post('from'))),
				'to'   => date('Y-m-d' ,strtotime($this->input->post('to'))),
			);

		$entities = $this->user->fetchPurchasedItems($config);

		$data = array(
				'title'         => 'Purchased Items',
				'content'       => 'user/purchased_view',
				'entities'      => $entities,
			);

		$this->load->view('include/template', $data);
	}
}
