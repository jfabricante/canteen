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
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'a.employee_no',
				'c.role_id',
			);

		$query = $this->db->select($fields)
				->from('ipc_central.employee_masterfile_tab AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.id = b.employee_id')
				->join('users_role_tbl AS c', 'a.id = c.user_id', 'INNER')
				->where('c.role_id = 2')
				->where('c.is_active', 1)
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
				->where('a.is_void = 0')
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
		$fields = array(
				'a.id',
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'c.role_id'
			);

		$query = $this->db->select($fields)
				->from('ipc_central.employee_masterfile_tab AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.id = b.employee_id', 'INNER')
				->join('users_role_tbl AS c', 'a.id = c.user_id', 'LEFT')
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
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'd.user_type'
			);

		$data = $this->ipc_central->select($fields)
				->from('employee_masterfile_tab AS a')
				->join('personal_information_tab AS b', 'a.id = b.employee_id', 'INNER')
				->join('canteenv2.users_role_tbl AS c', 'a.id = c.user_id', 'LEFT')
				->join('canteenv2.roles_tbl as d', 'c.role_id = d.id', 'LEFT')
				->where('a.status_id <= 4')
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
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'c.meal_allowance',
				'c.load_by',
				'c.last_meal_credit',
				'c.last_meal_credit_date',
				'b.birthdate'
			);

		$clause = array(
				'a.employee_no' => $params['employee_no']
			);

		$query = $this->ipc_central->select($fields)
				->from('employee_masterfile_tab AS a')
				->join('personal_information_tab AS b', 'a.id = b.employee_id', 'INNER')
				->join('canteenv2.users_meal_allowance_tbl AS c', 'a.id = c.user_id', 'INNER')
				->where($clause)
				->where('c.is_lock', 0)
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

			$emp_no = $this->db->select('emt.employee_no')
					->from('ipc_central.employee_masterfile_tab as emt')
					->join('ipc_central.personal_information_tab as pit', 'emt.id = pit.employee_id', 'INNER')
					->where('emt.id', $params['employee']['id'])
					->get()->row_array()['employee_no'];

			// Update the value of the other table
			$this->db->update('canteen.employees', $config, array('emp_id' => $emp_no));
		}
	}

	public function returnDeductedAllowance($params)
	{
		$user = $this->db->get_where('users_meal_allowance_tbl', array('user_id' => $params['user_id']))->row_array();
		
		$total_allowance = $params['credit_used'] + $user['meal_allowance'];

		$config = array('meal_allowance' => $total_allowance);

		$this->db->update('users_meal_allowance_tbl', $config, array('user_id' => $user['user_id']));
	}

	public function fetch_balances($type = 'object')
	{
		$fields = array(
				'a.id',
				'a.user_id',
				'a.meal_allowance',
				'emt.employee_no AS emp_no',
				"CONCAT(pit.first_name,' ', pit.last_name) AS fullname",
			);

		$query = $this->db->select($fields)
				->from('users_meal_allowance_tbl AS a')
				->join('ipc_central.employee_masterfile_tab AS emt', 'a.user_id = emt.id', 'INNER')
				->join('ipc_central.personal_information_tab AS pit', 'pit.employee_id = emt.id', 'INNER')
				->order_by('emp_no')
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
				'b.employee_no',
				"CONCAT(c.first_name,' ', c.last_name) AS fullname"
			);

		$query = $this->db->select($fields)
				->from('users_meal_allowance_tbl AS a')
				->join('ipc_central.employee_masterfile_tab AS b', 'a.user_id = b.id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'b.id = c.employee_id', 'INNER')
				->where('b.employee_no', $this->session->userdata('employee_no'))
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
				"CONCAT(c.first_name,' ', c.last_name) AS employee",
				"CONCAT(e.first_name,' ', e.last_name) AS cashier"
			);

		$emp_no = isset($params['employee_no']) ? $params['employee_no'] : $this->session->userdata('employee_no');

		$clause = array('f.employee_no' => $emp_no);

		if (isset($params['employee_no']))
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('ipc_central.personal_information_tab AS c', 'a.user_id = c.employee_id', 'INNER')
					->join('ipc_central.personal_information_tab AS e', 'a.cashier_id = e.employee_id', 'INNER')
					->join('ipc_central.employee_masterfile_tab AS f', 'c.employee_id = f.id', 'INNER')
					->where($clause)
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->where('a.is_void = 0')
					->get();
		}
		else if ($this->session->userdata('user_type') == 'employee')
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('ipc_central.personal_information_tab AS c', 'a.user_id = c.employee_id', 'INNER')
					->join('ipc_central.personal_information_tab AS e', 'a.cashier_id = e.employee_id', 'INNER')
					->join('ipc_central.employee_masterfile_tab AS f', 'c.employee_id = f.id', 'INNER')
					->where($clause)
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->where('a.is_void = 0')
					->get();
		}
		else
		{
			$query = $this->db->select($fields)
					->from('transaction_tbl AS a')
					->join('transaction_item_tbl AS b', 'a.id = b.trans_id', 'INNER')
					->join('items_tbl AS d', 'd.id = b.item_id', 'INNER')
					->join('ipc_central.personal_information_tab AS c', 'a.user_id = c.employee_id', 'INNER')
					->join('ipc_central.personal_information_tab AS e', 'a.cashier_id = e.employee_id', 'INNER')
					->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
					->where('a.user_id > 0')
					->where('a.is_void = 0')
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
				->where('a.user_id', $params['user_id'])
				->where("DATE(a.payroll_date) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
				->get();

		return $query->result_array();
	}

	
	public function fetchBalances()
	{
		$query = $this->intellexion->get_where('employees', array('last_meal_credit >' => 0));

		return $query->result_array();
	}
	
	public function transferBalances($params)
	{
		$this->db->truncate('users_meal_allowance_tbl');
		$this->db->insert_batch('users_meal_allowance_tbl', $params);
	}

	public function mealHistoryBatch($params)
	{
		$this->db->truncate('users_meal_history_tbl');
		$this->db->insert_batch('users_meal_history_tbl', $params);
	}

	public function checkCalculatedBalance($params)
	{
		$str = sprintf("SELECT employees.`user_id`, employees.`employee_no`, employees.`name`, umpt.`meal_allowance` AS old_balance, umalt.`last_meal_credit`,(CASE WHEN sub.`total_purchases` IS NULL THEN 0 ELSE sub.`total_purchases` END) AS total_purchases, ROUND(umalt.`meal_allowance`,2) AS current_meal_allowance, ROUND(umpt.`meal_allowance` + umalt.`last_meal_credit` - (CASE WHEN sub.`total_purchases` IS NULL THEN 0 ELSE sub.`total_purchases` END), 2) AS calculated_balance
			FROM users_meal_allowance_tbl AS umalt
			LEFT JOIN users_meal_processing_tbl AS umpt ON umalt.`user_id` = umpt.`user_id`
			LEFT JOIN (SELECT       
				(CASE  WHEN SUM(total_purchase) IS NULL  THEN 0
				ELSE SUM(total_purchase)
				END)
				AS total_purchases,
			       tt.`user_id` 
			    FROM
			      transaction_tbl AS tt 
			    WHERE tt.`datetime` >= '%s' 
			      AND tt.`is_void` = 0 
			    GROUP BY tt.`user_id`) AS sub 
			ON sub.`user_id` = umpt.`user_id`
			INNER JOIN employees ON umalt.`user_id` = employees.`user_id`
			WHERE employees.`status_id` <= 4 AND employees.`status_id` > 0 AND umalt.`is_lock` = 0;", $params);

		$query = $this->db->query($str);

		return $query->result_array();
	}

	public function getRecentMealUploadDate()
	{
		$query = $this->db->query('SELECT last_meal_credit_date FROM users_meal_allowance_tbl ORDER BY last_meal_credit_date DESC LIMIT 1;')->row();

		return $query->last_meal_credit_date;
	}

}