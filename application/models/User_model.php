<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
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
				'a.password',
				'a.fullname',
				'a.email',
				'a.emp_id',
				'a.emp_no',
				'a.supervisor_email',
				'b.role_id',
				'c.user_type'
			);

		$query = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->join('role_tbl AS c', 'b.role_id = c.id', 'INNER')
				->where($config)
				->get();

		if ($query->num_rows())
		{
			return $query->row_array();	
		}

		return false;
	}

	public function store(array $params = array())
	{
		if (count($params) > 0)
		{
			$this->db->insert('users_tbl', $params);
			return $this->db->insert_id();
		}

		return 0;
	}

	public function fetch()
	{
		$fields = array(
				'a.username',
				'a.fullname',
				'a.email',
				'a.emp_id',
				'a.emp_no',
				'a.supervisor_email',
				'c.user_type'
			);

		$data = $this->db->select($fields)
				->from('users_tbl AS a')
				->join('users_role_tbl AS b', 'a.id = b.user_id', 'INNER')
				->join('role_tbl AS c', 'b.role_id = c.id', 'INNER')
				->get();

		return $data->result();
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