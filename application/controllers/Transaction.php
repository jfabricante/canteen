<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/escpos-php/autoload.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class Transaction extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Set the default timezone
		date_default_timezone_set('Asia/Manila');

		$this->load->model('transaction_model', 'transaction');
		$this->load->model('category_model', 'category');
		$this->load->model('item_model', 'item');
		$this->load->model('user_model', 'user');

	}

	public function index()
	{
		$data = array(
				'title'   => 'Canteen System',
				'content' => 'transaction/index_view',
			);

		$this->load->view('include/template', $data);
	}

	public function store()
	{
		$data = json_decode(file_get_contents("php://input"), true);

		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

}


