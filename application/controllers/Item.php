<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('form');

		$this->load->model('item_model', 'item');
		$this->load->model('category_model', 'category');
	}

	public function list_()
	{
		$data = array(
				'title'   => 'List of Items',
				'content' => 'item/list_view',
				'items'   => $this->item->browse()
			);

		$this->load->view('include/template', $data);
	}

	public function form()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$data = array(
				'title'      => $id ? 'Update details' : 'Item form',
				'content'    => 'item/form_view',
				'entity'     => $id ? $this->item->read(array('id' => $id, 'type' => 'object')) : '',
				'categories' => $this->category->browse()
			);

		$this->load->view('item/form_view', $data);
	}

	public function store()
	{
		$id = $this->input->post('id');
		var_dump($this->input->post());
		/*$data = $this->_handle_upload();

		if (is_array($data))
		{

		}*/
	}

	protected function _handle_upload()
	{
		$config = array(
				'upload_path'   => './resources/thumbnail',
				'allowed_types' => 'gif|jpg|png',
				'max_size'      => 100
			);

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('thumbnail'))
		{
			$error = array('error' => $this->upload->display_errors());

			return $this->upload->display_errors();
		}

		return $this->upload->data();
	}
}