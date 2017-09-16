<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	// Return user credentials
	public function exist()
	{
		$config = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			);

		$fields = array(
				'a.id',
				'a.username',
				'a.fullname',
				'a.emp_no',
				'b.role_id',
				'c.user_type'
			);

		$query = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->join('roles_tbl AS c', 'b.role_id = c.id', 'INNER')
				->where($config)
				->get();

		if ($query->num_rows())
		{
			return $query->row_array();	
		}

		return false;
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

	public function store()
	{
		if (count($params) > 0)
		{
			$this->db->insert('users_tbl', $params);
			return $this->db->insert_id();
		}

		return 0;
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
				'b.meal_allowance'
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
}