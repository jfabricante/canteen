<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function store($params)
	{

		if (count($params['cart']) > 0)
		{
			$config = array(
					'user_id'        => $params['employee'] ? $params['employee']['id'] : 0,
					'credit_used'    => $params['credit_used'],
					'cash'           => $params['cash'],
					'total_purchase' => $params['totalPurchase'],
					'change'         => $params['change'],
					'cashier_id'     => $this->session->userdata('id'),
					'datetime'       => date('Y-m-d H:i:s')
				);

			$this->db->insert('transaction_tbl', $config);
			
			return $this->db->insert_id();
		}

		return 0;
	}

}