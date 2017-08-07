<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('transaction_model', 'transaction');
		$this->load->model('category_model', 'category');
		$this->load->model('item_model', 'item');

	}

	public function index()
	{
		$data = array(
				'title'   => 'Cateen System',
				'content' => 'transaction/index_view',
			);

		$this->load->view('include/template', $data);
	}

	

}


