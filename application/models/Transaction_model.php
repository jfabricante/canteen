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

	public function store_items($params, $tid)
	{
		if ($tid > 0)
		{
			foreach ($params['cart'] as $item) 
			{
				$config = array(
						'item_id'  => $item['id'],
						'trans_id' => $tid,
						'price'    => $item['price'],
						'quantity' => $item['quantity'],
						'total'    => $item['total']
					);
				
				$this->db->insert('transaction_item_tbl', $config);
			}
		} 
	}

	public function billing_report($params)
	{
		$fields = array(
				'a.id',
				"CONCAT(b.first_name,' ', b.last_name) AS employee",
				'a.credit_used',
				'a.cash',
				'a.datetime',
				"CONCAT(c.first_name,' ', c.last_name) AS cashier",
				'a.invoice_id',
				'd.status'
			);

		$query = $this->db->select($fields)
				->from('transaction_tbl AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.user_id = b.employee_id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'a.cashier_id = c.employee_id', 'INNER')
				->join('invoice_tbl AS d', 'a.invoice_id = d.id', 'LEFT')
				->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
				->where('a.credit_used > 0')
				->get();


		return $query->result_array();
	}

	public function getInvoiceItems($params)
	{
		$fields = array(
				'a.id',
				"CONCAT(b.first_name,' ', b.last_name) AS employee",
				'a.credit_used',
				'a.cash',
				'a.datetime',
				"CONCAT(c.first_name,' ', c.last_name) AS cashier",
				'a.invoice_id',
				'd.status',
				'd.date_covered'
			);

		$query = $this->db->select($fields)
				->from('transaction_tbl AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.user_id = b.employee_id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'a.cashier_id = c.employee_id', 'INNER')
				->join('invoice_tbl AS d', 'a.invoice_id = d.id', 'LEFT')
				->where('d.id', $params['invoice_no'])
				->where('a.credit_used > 0')
				->get();


		return $query->result_array();
	}


	public function subjectForInvoice($params)
	{
		$fields = array(
				'a.id',
				"CONCAT(b.first_name,' ', b.last_name) AS employee",
				'a.credit_used',
				'a.cash',
				'a.datetime',
				"CONCAT(c.first_name,' ', c.last_name) AS cashier",
				'a.invoice_id'
			);

		$query = $this->db->select($fields)
				->from('transaction_tbl  AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.user_id = b.employee_id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'a.cashier_id = c.employee_id', 'INNER')
				->where("DATE(a.datetime) BETWEEN '" . $params['from'] . "' AND '" . $params['to'] . "'")
				->where('invoice_id IS NULL')
				->where('a.credit_used > 0')
				->get();

		return $query->result_array();
	}

	public function createInvoice()
	{
		$config = array(
				'user_id'      => $this->session->userdata('id'),
				'date_created' => date('Y-m-d H:i:s'),
				'date_covered' => date('M d, Y', strtotime($this->input->post('from'))) . ' to ' . date('M d, Y', strtotime($this->input->post('to')))
			);

		$this->db->insert('invoice_tbl', $config);

		return $this->db->insert_id();
	}
 
	public function invoiceList()
	{
		$fields = array(
				'a.*',
				"CONCAT(b.first_name,' ', b.last_name) AS created_by",
				"CONCAT(c.first_name,' ', c.last_name) AS updated_by"
			);
		$query = $this->db->select($fields)
				->from('invoice_tbl AS a')
				->join('ipc_central.personal_information_tab AS b', 'a.user_id = b.employee_id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'a.last_user = c.employee_id', 'LEFT')
				->get();

		return $query->result_array();
	}

	public function invoiceEntity($params)
	{
		$query = $this->db->get_where('invoice_tbl', array('id' => $params));

		return $query->row_array();
	}

	public function updateInvoiceStatus($params)
	{
		$this->db->update('invoice_tbl', $params, array('id' => $params['id']));
	}

	public function updateTransInvoice($params)
	{
		$this->db->update('transaction_tbl', $params, array('id' => $params['id']));
	}

	public function fetchInvoiceItems($params)
	{
		$fields = array(
				'a.id',
				'a.status',
				'b.id AS trans_id',
				'b.credit_used',
				'b.cash',
				'b.datetime AS trans_date',
				"CONCAT(c.first_name,' ', c.last_name) AS fullname",
			);

		$query = $this->db->select($fields)
				->from('invoice_tbl AS a')
				->join('transaction_tbl AS b', 'a.id = b.invoice_id', 'INNER')
				->join('ipc_central.personal_information_tab AS c', 'b.user_id = c.employee_id', 'INNER')
				->where('b.credit_used > 0')
				->where('a.id', $params)
				->get();

		return $query->result_array();
	}
}