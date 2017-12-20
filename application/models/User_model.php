<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->intellexion = $this->load->database('intellexion', true);

		$this->ipc_central = $this->load->database('ipc_central', true);
	}

	public function exist()
	{
		$config = array_map('trim', $this->input->post());

		$fields = array(
				'a.id',
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'e.user_type'
			);	

		$query = $this->ipc_central->select($fields)
				->from('employee_masterfile_tab AS a')
				->join('personal_information_tab AS b', 'a.id = b.employee_id', 'INNER')
				->join('password_tab AS c', 'a.id = c.employee_id', 'INNER')
				->join('canteenv2.users_role_tbl AS d', 'a.id = d.user_id', 'INNER')
				->join('canteenv2.roles_tbl AS e', 'd.role_id = e.id', 'INNER')
				->where('a.employee_no', $config['username'])
				->where('c.password', $config['password'])
				->get();

		if ($query->num_rows())
		{
			return $query->row_array();
		}

		return false;
	}

	public function cashiers()
	{
		$fields = array(
				'a.id',
				'a.username',
				'a.fullname',
				'a.emp_no',
				'b.role_id',
			);

		$query = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->where('b.role_id = 2')
				->get();

		return $query->result_array();
	}

	public function cashierSales($params)
	{
		$fields = array(
				'a.id',
				'a.datetime',
				'c.name',
				'b.quantity',
				'b.price',
				'b.total'
			);

		$query = $this->db->select($fields)
				->from('transaction_tbl AS a')
				->join('transaction_item_tbl AS b', 'a.id = b.trans_id')
				->join('items_tbl AS c', 'c.id = b.item_id')
				->where('cashier_id', $params['user_id'])
				->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
				->get();

		return $query->result_array();
	}

	public function store_batch(array $data)
	{
		$this->db->insert_batch('users_tbl', $data);

		return $this;
	}

	public function assign_batch_role(array $ids)
	{
		foreach ($ids as $id)
		{
			$config = array(
					'user_id' => $id,
					'role_id' => 3
				);

			$exist = $this->db->select('*')
					->from('users_role_tbl')
					->where($config)
					->get();

			if (!$exist->num_rows())
			{
				$this->db->insert('users_role_tbl', $config);
			}	
		}

		return $this;
	}

	public function store($params)
	{
		if ($params['id'] == 0)
		{
			unset($params['id']);

			$this->db->insert('users_tbl', $params);

			return $this->db->insert_id();
		}
		else
		{
			$this->db->update('users_tbl', $params, array('id' => $params['id']));

			return $params['id'];
		}

		return 0;
	}

	public function readDetails($id)
	{
		$fields = array('a.*', 'b.role_id');

		$query = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->where('a.id', $id)
				->get();

		return $query->row_array();
	}

	public function store_role($params)
	{
		if ($params['id'] == 0)
		{
			unset($params['id']);

			$this->db->insert('users_role_tbl', $params);
		}
		else
		{
			$this->db->update('users_role_tbl', $params, array('id' => $params['id']));
		}
	}

	public function findRole($params)
	{
		$query = $this->db->select('id')
				->from('users_role_tbl')
				->where('user_id', $params)
				->get();

		return array_filter($query->row_array()) ? $query->row_array() : 0;
	}

	public function fetch($type = 'object')
	{
		$fields = array(
				'a.id',
				'a.username',
				'a.fullname',
				'a.emp_no',
				'a.datetime',
				'b.id AS users_role_id',
				'c.user_type'
			);

		$data = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->join('roles_tbl AS c', 'b.role_id = c.id', 'INNER')
				->order_by('a.fullname')
				->get();

		if ($type == 'object')
		{
			return $data->result();
		}

		return $data->result_array();
	}

	public function read(array $params)
	{
		$fields = array(
				'a.id',
				'a.emp_no',
				'a.fullname',
				'b.meal_allowance',
				'b.load_by',
				'b.last_meal_credit',
				'b.last_meal_credit_date'
			);

		$clause = array(
				'a.emp_no' => $params['emp_no']
			);

		$query = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_meal_allowance_tbl AS b', 'a.id = b.user_id', 'INNER')
				->where($clause)
				->get();

		return $query->row();
	}

	public function fetch_roles($type = 'object')
	{
		if ($type == 'object')
		{
			return $this->db->get('roles_tbl')->result();
		}
		
		return $this->db->get('roles_tbl')->result_array();
	}

	public function assign_role(array $params = array())
	{
		if ($params['user_id'] > 0)
		{
			$this->db->insert('users_role_tbl', $params);
		}

		return 0;
	}

	public function users_count()
	{
		return $this->db->get('users_tbl')->num_rows();
	}

	public function users_role_count()
	{
		return $this->db->get('users_role_tbl')->num_rows();
	}

	public function truncate_tbl()
	{
		$this->db->truncate('users_tbl');
		$this->db->truncate('users_role_tbl');
	}

	public function update_allowance($params)
	{
		if (is_array($params['employee']))
		{
			$config = array(
					'meal_allowance' => $params['remaining_credit']
				);

			$this->db->update('users_meal_allowance_tbl', $config, array('user_id' => $params['employee']['id']));
		}
	}

	public function fetch_balances($type = 'object')
	{
		$fields = array(
				'a.id',
				'a.meal_allowance',
				'b.emp_no',
				'b.fullname',
			);

		$query = $this->db->select($fields)
				->from('users_meal_allowance_tbl AS a')
				->join('users_tbl AS b', 'a.user_id = b.id', 'INNER')
				->order_by('b.emp_no')
				->get();

		if ($type == 'array')
		{
			return $query->result_array();
		}

		return $query->result();
	}

	public function read_balance($type = 'object')
	{
		$fields = array(
				'a.id',
				'a.meal_allowance',
				'b.emp_no',
				'b.fullname',
			);

		$query = $this->db->select($fields)
				->from('users_meal_allowance_tbl AS a')
				->join('users_tbl AS b', 'a.user_id = b.id', 'INNER')
				->where('emp_no', $this->session->userdata('emp_no'))
				->get();

		if ($type == 'array')
		{
			return $query->row_array();
		}

		return $query->row();
	}

	public function fetchPurchasedItems($params)
	{
		$fields = array(
				'a.id',
				'a.datetime',
				'd.name',
				'b.quantity',
				'b.price',
				'b.total',
				'c.fullname AS employee',
				'e.fullname AS cashier'
			);

		$emp_no = isset($params['emp_no']) ? $params['emp_no'] : $this->session->userdata('emp_no');

		$clause = array('c.emp_no' => $emp_no);

		if (isset($params['emp_no']))
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('users_tbl AS c', 'c.id = a.user_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('users_tbl AS e', 'e.id = a.cashier_id', 'INNER')
					->where($clause)
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->get();
		}
		else if ($this->session->userdata('user_type') == 'employee')
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('users_tbl AS c', 'c.id = a.user_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('users_tbl AS e', 'e.id = a.cashier_id', 'INNER')
					->where($clause)
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->get();
		}
		else
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('users_tbl AS c', 'c.id = a.user_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('users_tbl AS e', 'e.id = a.cashier_id', 'INNER')
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->get();
		}

		return $query->result_array();
	}

	// array params 
	public function mealHistory($params)
	{
		$fields = array(
				'a.id',
				'a.payroll_date',
				'a.adj_code',
				'a.reference',
				'a.adj_amount',
				'a.type',
				'a.load_by',
			);

		$query = $this->db->select($fields)
				->from('users_meal_history_tbl AS a')
				->join('users_tbl AS b', 'a.user_id = b.id', 'INNER')
				->where('a.user_id', $params['user_id'])
				->where("DATE(a.payroll_date) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
				->get();

		return $query->result_array();
	}

}