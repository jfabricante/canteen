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

	public function fetch_roles()
	{
		return $this->db->get('role_tbl')->result();
	}

	public function assign_role(array $params = array())
	{
		if ($params['user_id'] > 0)
		{
			$this->db->insert('users_role_tbl', $params);
		}

		return 0;
	}
}