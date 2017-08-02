<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipc_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->db = $this->load->database('ipc_central', true);
	}

	public function fetch_personal_info($params)
	{
		$fields = array(
				'a.id',
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'c.email AS requestor_email',
				'd.ash',
				'd.sh',
				'd.adh',
				'd.dh'
			);

		$data = '';

		if (isset($params['id']) && $params['id']  > 0)
		{
			$config = array(
				'a.id' => $params['id']
			);

			$query = $this->db->select($fields)
					->from('employee_masterfile_tab AS a')
					->join('personal_information_tab AS b', 'a.id = b.employee_id', 'LEFT')
					->join('email_tab AS c', 'a.id = c.employee_id', 'LEFT')
					->join('signatories_matrix_tab AS d', 'a.section_id = d.section_id', 'LEFT')
					->where($config)
					->get();	

			$data = $query->row_array();
		}
		else if(isset($params['employee_no']) && $params['employee_no']  > 0)
		{
			$config = array(
				'a.employee_no' => $params['employee_no']
			);

			$query = $this->db->select($fields)
					->from('employee_masterfile_tab AS a')
					->join('personal_information_tab AS b', 'a.id = b.employee_id', 'LEFT')
					->join('email_tab AS c', 'a.id = c.employee_id', 'LEFT')
					->join('signatories_matrix_tab AS d', 'a.section_id = d.section_id', 'LEFT')
					->where($config)
					->get();	

			$data = $query->row_array();
		}

		return isset($data) ? $data : '';
	}

	public function fetch_department_head($emp_no)
	{
		$emp = $this->fetch_personal_info(array('employee_no' => $emp_no));

		$dept_head_id = 0;

		if ($emp['ash'] > 0 && $emp['id'] != $emp['ash']) {
			$dept_head_id = $emp['ash'];		
		}
		else if ($emp['sh'] > 0 && $emp['id'] != $emp['sh']) {
			$dept_head_id = $emp['sh'];
		}
		else if ($emp['adh'] > 0 && $emp['id'] != $emp['adh']) {
			$dept_head_id = $emp['adh'];
		}
		else if ($emp['dh'] > 0 && $emp['id'] != $emp['dh']) {
			$dept_head_id = $emp['dh'];
		}
		else {
			$dept_head_id = $emp['id'];
		}

		$fields = array(
				'a.id',
				'a.employee_no',
				"CONCAT(b.first_name,' ', b.last_name) AS fullname",
				'c.email AS supervisor_email'
			);

		$config = array(
				'a.id' => $dept_head_id
			);

		$query = $this->db->select($fields)
				->from('employee_masterfile_tab AS a')
				->join('personal_information_tab AS b', 'a.id = b.employee_id', 'LEFT')
				->join('email_tab AS c', 'a.id = c.employee_id', 'LEFT')
				->where($config)
				->get();

		return $query->row_array();
	}

	public function fetch_user_access($emp_id)
	{
		$query = $this->db->select('*')
				->from('user_access_tab')
				->where('employee_id', $emp_id)
				->where('system_id = 29')
				->get();

		return $query->row_array();
	}

}