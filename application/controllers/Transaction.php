<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/escpos-php/autoload.php';
require_once APPPATH . '/third_party/dompdf/autoload.inc.php';
require_once APPPATH . '/third_party/PHPExcel/Classes/PHPExcel.php';

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Dompdf\Dompdf;


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
				'title'   => '',
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
				$printer->text(str_pad("Remaining balance:", 38) . str_pad($params['remaining_credit'], 10) . "\n");
				$printer->text(str_pad("Cash:", 38) . str_pad($params['cash'] ? $params['cash'] : '', 10) . "\n");
				$printer->text(str_pad("Change:", 38) . str_pad($params['change'] ? $params['change'] : '', 10) . "\n");
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

	public function generate_billing_report()
	{
		$data = array(
				'title'   => 'Filter Dates to Generate Billing Report',
				'content' => 'transaction/generate_reports_view'
			);

		$this->load->view('include/template', $data);
	}

	public function filter_billing_report()
	{
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		// Change date format
		$from = date('Y-m-d', strtotime($this->input->post('from')));
		$to   = date('Y-m-d', strtotime($this->input->post('to')));

		// Header options
		$font       = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
		$size       = 8;
		$color      = array(0, 0, 0);
		$word_space = 0.0;  //  default
		$char_space = 0.0;  //  default
		$angle      = 0.0;   //  default

		$config = array(
				'from' => $from,
				'to'   => $to
			);

		$entities = $this->transaction->billing_report($config);

		// Calculate the total bill from date filtered data
		$total_bill = is_array($entities) ? array_sum(array_column($entities, 'credit_used')) : 0; 

		$data = array(
				'title'      => 'Billing Reports from ' . date('M d, Y', strtotime($config['from'])) . ' to ' . date('M d, Y', strtotime($config['to'])),
				'entities'   => $entities,
				'total_bill' => $total_bill
			);

		// Enable html5 parsing
		$dompdf->set_option('isHtml5ParserEnabled', true);

		// Load the html to pdf
		$dompdf->loadHtml($this->load->view('transaction/billing_reports_view', $data, true));

        $text = 'From ' . date('M d, Y', strtotime($config['from'])) . ' to ' . date('M d, Y', strtotime($config['to']));
        $dompdf->getCanvas()->page_text(40, 55, $text, $font, $size, $color, $word_space, $char_space, $angle);

        $text = date('d/m/Y h:i A');
        $dompdf->getCanvas()->page_text(400, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

        $text = "Printed by: " . ucwords(strtolower($this->session->userdata('fullname')));
        $dompdf->getCanvas()->page_text(400, 45, $text, $font, $size, $color, $word_space, $char_space, $angle);

        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $dompdf->getCanvas()->page_text(520, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

        $text = "Billing Report";
        $size = 14;
        $headerFont = $dompdf->getFontMetrics()->get_font("helvetica", "bold");

        $dompdf->getCanvas()->page_text(40, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream();
	}
}