<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function items()
	{
		$data = array(
				'title'   => 'List of Items',
				'content' => 'items/items_view'
			);

		$this->load->view('include/template', $data);
	}

	public function item_form()
	{
		$data = array(
				'title' => 'Item form',
				'content' => 'items/item_form_view',
			);

		$this->load->view('include/template', $data);
	}
}