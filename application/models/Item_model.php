<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function browse(array $params = array('type' => 'object'))
	{
		$fields = array(
				'a.id',
				'a.name',
				'ROUND(a.price, 2) AS price',
				'a.thumbnail',
				'a.datetime',
				'a.barcode',
				'b.category_id',
				'c.name AS category'
			);

		$query = $this->db->select($fields)
				->from('items_tbl AS a')
				->join('item_category_tbl AS b', 'a.id = b.item_id', 'INNER')
				->join('category_tbl AS c', 'b.category_id = c.id', 'INNER')
				->get();

		if ($params['type'] == 'object')
		{
			return $query->result();	
		}

		return $query->result_array();
	}

	public function read(array $params = array('type' => 'object'))
	{
		if ($params['id'] > 0)
		{
			$fields = array(
				'a.id',
				'a.name',
				'ROUND(a.price, 2) AS price',
				'a.thumbnail',
				'a.datetime',
				'a.barcode',
				'b.id AS item_category_id',
				'b.category_id',
				'c.name AS category'
			);

			$query = $this->db->select($fields)
					->from('items_tbl AS a')
					->join('item_category_tbl AS b', 'a.id = b.item_id', 'INNER')
					->join('category_tbl AS c', 'b.category_id = c.id', 'INNER')
					->where('a.id', $params['id'])
					->get();

			if ($params['type'] == 'object')
			{
				return $query->row();	
			}

			return $query->row_array();
		}
	}

	public function store($params)
	{
		$id          = $this->input->post('id');
		$name        = ucfirst(strtolower(trim($this->input->post('name'))));
		$price       = $this->input->post('price');
		$barcode     = $this->input->post('barcode');
		$category_id = $this->input->post('category_id');
		$datetime    = date('Y-m-d H:i:s');

		$config = array();

		if (is_array($params))
		{
			$config = array(
					'name'      => $name,
					'price'     => $price,
					'thumbnail' => $params['file_name'],
					'datetime'  => $datetime,
					'barcode'   => $barcode
				);
		}
		else
		{
			$config = array(
					'name'      => $name,
					'price'     => $price,
					'datetime'  => $datetime,
					'barcode'   => $barcode
				);
		}

		if ($id > 0)
		{
			$this->db->update('items_tbl', $config, array('id' => $id));
		}
		else
		{
			$this->db->insert('items_tbl', $config);

			$id = $this->db->insert_id();
		}

		$config = array(
					'item_id'     => $id, 
					'category_id' => $category_id
				);

		$this->_store_item_category($config);

		$config = array(
				'item_id'  => $id,
				'price'    => $price,
				'datetime' => $datetime,
				'admin_id' => $this->session->userdata('id')
			);

		$this->_store_item_price_modified($config);
		
	}

	protected function _store_item_category($params)
	{
		$id = $this->input->post('item_category_id');

		if ($id > 0)
		{
			$this->db->update('item_category_tbl', $params, array('id' => $id));
		}
		else 
		{
			$this->db->insert('item_category_tbl', $params);
		}
	}

	protected function _store_item_price_modified($params)
	{
		$this->db->insert('item_price_modified_tbl', $params);
	}

	public function delete()
	{
		$id = $this->input->post('id');

		$this->db->delete('items_tbl', array('id' => $id));

		return $this;
	}

	public function store_featured($params)
	{
		if (!$this->_checkFeaturedExist($params))
		{
			$this->db->insert('featured_items_tbl', $params);
		}
	}

	protected function _checkFeaturedExist($params)
	{
		$query = $this->db->where('item_id', $params['item_id'])
				->get('featured_items_tbl');

		if ($query->num_rows() > 0)
		{
			return true;
		}

		return false;
	}

	public function delete_featured($params)
	{
		$this->db->delete('featured_items_tbl', array('item_id' => $params['item_id']));

		return $this;
	}

	public function browseFeaturedItems()
	{
		$fields = array(
				'b.id',
				'b.name',
				'b.price',
				'b.thumbnail',
				'b.barcode',
				'c.category_id'
			);

		$query = $this->db->select($fields)
				->from('featured_items_tbl AS a')
				->join('items_tbl AS b', 'a.item_id = b.id', 'INNER')
				->join('item_category_tbl AS c', 'b.id = c.item_id', 'INNER')
				->get();

		return $query->result_array();
	}
}
