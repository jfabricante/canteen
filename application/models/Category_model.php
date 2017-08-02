<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function browse($params)
	{
		if ($params['type'] == 'object')
		{
			return $this->db->get('category_tbl')->result();	
		}
		else
		{
			return $this->db->get('category_tbl')->result_array();
		}
	}
}
