<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->_redirectUnauthorized();

		date_default_timezone_set('Asia/Manila');

		$this->load->model('category_model', 'category');
	}

	public function list_()
	{
		$data = array(
				'title'      => 'List of Categories',
				'content'    => 'category/list_view',
				'categories' => $this->category->browse()
			);

		$this->load->view('include/template', $data);
	}

	public function form()
	{
		$id = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

		$config = array(
				'id'   => $id,
				'type' => 'object'
			);

		$data = array(
				'title'   => $id ? 'Update Details' : 'Add Category',
				'entity'  => $id ? $this->category->read($config) : ''
			);

		$this->load->view('category/form_view', $data);
	}

	public function store()
	{
		$id = $this->input->post('id') ? $this->input->post('id') : 0;

		$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
		// $pattern = '/([0-9])|(\"|\}|\{|!|@|£|\$|%|\^|\&|\(\)|\?|\|||\[|\]|-|\=|\+|\§|\±)/';

		if (preg_match($pattern, $this->input->post('name')))
		{
		    $this->session->set_flashdata('message', '<div class="alert alert-warning">Category name contains special characters.</div>');
		}
		else
		{
			$this->category->store();

			if ($id > 0)
			{
				$this->session->set_flashdata('message', '<div class="alert alert-success">Category has been updated!</div>');
			}
			else
			{
				$this->session->set_flashdata('message', '<div class="alert alert-success">Category has been added!</div>');
			}
		}

		redirect('/category/list_');
	}

	public function notice()
	{
		$data['id'] = $this->uri->segment(3);

		$this->load->view('category/delete_view', $data);
	}

	public function delete()
	{
		$this->category->delete();

		$this->session->set_flashdata('message', '<div class="alert alert-success">Category has been deleted!</div>');

		redirect('category/list_');
	}

	public function set_menu()
	{
		$data = array(
				'title'   => 'Set Menu',
				'content' => 'category/set_menu_view',
			);

		$this->load->view('include/template', $data);
	}

	public function ajax_category_list()
	{
		echo json_encode($this->category->browse());
	}

	public function ajax_category_items()
	{
		echo json_encode($this->category->fetch_category_items());
	}

	public function ajax_featured_items()
	{
		echo json_encode($this->category->fetch_featured_items());
	}

	protected function _redirectUnauthorized()
	{
		if (count($this->session->userdata()) < 3)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-warning">Login first!</div>');
			redirect(base_url());
		}
	}
}