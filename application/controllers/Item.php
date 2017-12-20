<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		date_default_timezone_set('Asia/Manila');

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

	public function ajax_item_list()
	{
		echo json_encode($this->item->browse());
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

	public function notice()
	{
		$data['id'] = $this->uri->segment(3);

		$this->load->view('item/delete_view', $data);
	}

	public function delete()
	{
		$this->item->delete();

		$this->session->set_flashdata('message', '<div class="alert alert-success">Item has been deleted!</div>');

		redirect('item/list_');
	}

	public function store()
	{
		$id   = $this->input->post('id');

		$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
		// $pattern = '/([0-9])|(\"|\}|\{|!|@|£|\$|%|\^|\&|\(\)|\?|\|||\[|\]|-|\=|\+|\§|\±)/';

		if (preg_match($pattern, $this->input->post('name')))
		{
		    $this->session->set_flashdata('message', '<div class="alert alert-warning">Item name contains special characters.</div>');
		}
		else
		{
			$data = $this->_handle_upload();

			$item_id = $this->item->store($data);

			$config = array(
					'item_id'     => $item_id, 
					'category_id' => $this->input->post('category_id') ? $this->input->post('category_id') : 0
				);

			$this->item->store_item_category($config);

			if ($id > 0)
			{
				$this->session->set_flashdata('message', '<div class="alert alert-success">Item has been updated!</div>');
			}
			else
			{
				$this->session->set_flashdata('message', '<div class="alert alert-success">Item has been added!</div>');
			}
		}

		redirect('/item/list_');
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

	public function ajax_store_featured()
	{
		$data = json_decode(file_get_contents("php://input"), true);

		$data['datetime'] = date('Y-m-d H:i:s');

		$this->item->store_featured($data);
	}

	public function ajax_delete_featured()
	{
		$data = json_decode(file_get_contents("php://input"), true);

		$this->item->delete_featured($data);
	}

	public function ajax_browse_featured_items()
	{
		echo json_encode($this->item->browseFeaturedItems());
	}
}