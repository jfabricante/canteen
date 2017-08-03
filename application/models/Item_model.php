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

	/*public function store()
	{
		$id       = $this->input->post('id') ? $this->input->post('id') : 0;
		$name     = ucfirst(strtolower(trim($this->input->post('name'))));
		$datetime = date('Y-m-d H:i:s');

		$config = array(
				'name'     => $name,
				'datetime' => $datetime
			);

		if ($id > 0)
		{
			$this->db->update('category_tbl', $config, array('id' => $id));
		}
		else
		{
			$this->db->insert('category_tbl', $config);
		}

		return $this;
	}

	public function delete()
	{
		$id = $this->input->post('id');

		$this->db->delete('category_tbl', array('id' => $id));

		return $this;
	}*/
}
