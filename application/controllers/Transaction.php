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

		$trans_id = $this->transaction->store($data);

		$data['trans_id'] = $trans_id;

		$this->transaction->store_items($data, $trans_id);

		$this->user->update_allowance($data);

		//$this->_generate_receipt($data);
	}

	protected function _generate_receipt($params)
	{
		try {
			$connector = new WindowsPrintConnector('smb://BARCODE08/EPSON TM-T82II Receipt');
			$printer = new Printer($connector);

			$printer->initialize();

			$copies = array("***Customer Copy***", "***Cashier Copy***");

			foreach ($copies as $row) 
			{
				$printer->text("Isuzu Philippines Corporation\n");
				$printer->text("Transaction#: " . $params['trans_id'] . "\n");
				$printer->text(date('D, M d, Y h:i A') . "\n");
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				$printer->text("Customer: " . ucwords(strtolower($params['employee']['fullname'])) . "\n");
				$printer->text("Meal Allowance: " . $params['employee']['allowance'] . "\n");
				$printer->text("Cashier: " . ucwords(strtolower($this->session->userdata('fullname'))) . "\n");
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				$printer->text("Purchased Items\n");

				foreach ($params['cart'] as $item) 
				{
					$printer->text(str_pad($item['name'], 32));
					$printer->text(str_pad($item['quantity'] . 'x' , 6));
					$printer->text(str_pad($item['total'], 10) . "\n");
				}
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				$printer->text(str_pad("Total:", 38) . str_pad($params['totalPurchase'], 10) . "\n");
				//$printer->text(str_pad("Credit:", 38) . str_pad($params['remaining_credit'], 10) . "\n");
				$printer->text(str_pad("Credit:", 38) . str_pad($params['credit_used'], 10) . "\n");
				$printer->text(str_pad("Cash:", 38) . str_pad($params['cash'], 10) . "\n");
				$printer->text(str_pad("Change:", 38) . str_pad($params['change'], 10) . "\n");
				$printer->feed(2);
				$printer->text(str_pad('', 48, '_') . "\n");
				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("SIGNATURE\n");
				$printer->text($row . "\n");
				$printer->feed();
				$printer->setJustification();
				$printer->cut();
			}
			
			$printer->close();


		} catch(Exception $e) {
		    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
		}
	}

}


