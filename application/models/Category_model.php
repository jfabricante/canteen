<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->database();
	}

	public function browse(array $params = array('type' => 'object'))
	{
		if ($params['type'] == 'object')
		{
			return $this->db->get('category_tbl')->result();	
		}

		return $this->db->get('category_tbl')->result_array();
	}

	public function featured_categories()
	{
		$query = $this->db->distinct('a.id')
				->select('a.*')
				->from('category_tbl AS a')
				->join('item_category_tbl AS b', 'a.id = b.category_id', 'INNER')
				->join('featured_items_tbl AS c', 'b.item_id = c.item_id', 'INNER')
				->order_by('a.id')
				->get();

		return $query->result_array();
	}

	public function fetch_category_items()
	{
		$categories = $this->browse();

		$config = array();

		$fields = array(
				'a.category_id',
				'b.id',
				'b.name',
				'b.price',
				'b.thumbnail',
				'b.barcode'
			);

		foreach($categories as $category)
		{
			$query = $this->db->select($fields)
					->from('item_category_tbl AS a')
					->join('items_tbl AS b', 'a.item_id = b.id', 'INNER')
					->where('a.category_id', $category->id)
					->get();

			if ($query->num_rows())
			{
				array_push($config, $query->result_array());
			}
		}

		return $config;
	}

	public function fetch_featured_items()
	{
		$categories = $this->browse();

		$config = array();

		$fields = array(
				'b.id',
				'b.name',
				'b.price',
				'b.thumbnail',
				'b.barcode',
				'c.category_id'
			);

		foreach ($categories as $category)
		{
			$query = $this->db->select($fields)
				->from('featured_items_tbl AS a')
				->join('items_tbl AS b', 'a.item_id = b.id', 'INNER')
				->join('item_category_tbl AS c', 'b.id = c.item_id', 'INNER')
				->where('c.category_id', $category->id)
				->get();

			if ($query->num_rows())
			{
				array_push($config, $query->result_array());
			}
		}

		return $config;
	}

	public function read(array $params = array('type' => 'object'))
	{
		if ($params['id'] > 0)
		{
			$query = $this->db->get_where('category_tbl', array('id' => $params['id']));

			if ($params['type'] == 'object')
			{
				return $query->row();
			}
			
			return $query->row_array();
		}
	}

	public function store()
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
			if ($this->hasDuplicateCategory($config))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-warning">Category name is already exist!</div>');

				redirect($this->agent->referrer());
			}
			else
			{
				$this->db->insert('category_tbl', $config);
			}
		}

		return $this;
	}

	public function hasDuplicateCategory($params)
	{
		$query = $this->db->where('name', $params['name'])->get('category_tbl');

		return $query->num_rows();
	}

	public function delete()
	{
		$id = $this->input->post('id');

		$this->db->delete('category_tbl', array('id' => $id));

		return $this;
	}
}
