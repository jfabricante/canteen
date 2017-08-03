<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

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

}