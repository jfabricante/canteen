<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('category_model', 'category');
	}

	public function categories()
	{
		$data = array(
				'title'      => 'List of Categories',
				'content'    => 'categories/categories_view',
				'categories' => $this->category->browse(array('type' => 'object'))
			);

		$this->load->view('include/template', $data);
	}
}