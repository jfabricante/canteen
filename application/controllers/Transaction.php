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

		$this->_redirectUnauthorized();

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
		
		// Print transaction when the cashier click print button
		if ($data['to_print'])
		{
			$this->_generate_receipt($data);

			echo json_encode($data['trans_id']);
		}
		else
		{
			$trans_id = $this->transaction->store($data);

			$data['trans_id'] = $trans_id;

			$this->transaction->store_items($data, $trans_id);

			$this->user->update_allowance($data);

			echo json_encode($trans_id);
		}
	}

	protected function _generate_receipt($params)
	{
		try {
			// $connector = new WindowsPrintConnector('smb://BARCODE08/EPSON TM-T82II Receipt');
			$connector = new WindowsPrintConnector('smb://IPCPC367/EPSON TM-T82II Receipt6');
			$printer = new Printer($connector);

			$printer->initialize();

			// $copies = array("***Customer Copy***", "***Cashier Copy***");
			$copies = array("***Customer's Copy***");

			foreach ($copies as $row) 
			{
				$printer->text("ISUZU PHILIPPINES CORPORATION\n");
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				if ($params['employee']['fullname'])
				{
					$printer->text("Name: " . ucwords(strtolower($params['employee']['fullname'])) . "\n");
					$params['remaining_credit'] < 0 ? $printer->text("You have " . number_format($params['remaining_credit'], 2) . " meal credit balance, this will be deducted on the next Payroll Cut-off \n") : '';
					$printer->text(str_pad('', 48, '-'));
					$printer->feed(1);
				}

				$printer->text(str_pad('Qty', 6));
				$printer->text(str_pad('Description', 32));
				$printer->text(str_pad('Total', 10) . "\n");
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				foreach ($params['cart'] as $item) 
				{
					$printer->text(str_pad($item['quantity'], 6));

					if ($item['quantity'] > 1)
					{
						$printer->text(str_pad($item['name'], 24));
						$printer->text('@' . str_pad($item['price'], 7));
					}
					else
					{
						$printer->text(str_pad($item['name'], 32));
					}
					$printer->text(str_pad($item['total'], 10) . "\n");
				}
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(1);

				$printer->text(str_pad("Total Transaction:", 38) . str_pad($params['totalPurchase'], 10) . "\n");
				$printer->text(str_pad("Meal Allowance: ", 38) . str_pad($params['employee']['allowance'], 10) . "\n");

				if ($params['remaining_credit'] > 0)
				{
					$printer->text(str_pad("Remaining Allowance:", 38) . str_pad($params['remaining_credit'], 10) . "\n");
				}
				else
				{
					$printer->text(str_pad("Credit:", 38) . str_pad($params['remaining_credit'], 10) . "\n");
				}
				
				$printer->text(str_pad("Cash:", 38) . str_pad($params['cash'] ? $params['cash'] : '', 10) . "\n");
				$printer->text(str_pad("Change:", 38) . str_pad($params['change'] ? $params['change'] : '', 10) . "\n");
				$printer->text(str_pad('', 48, '-'));
				$printer->feed(2);

				$printer->text("Transaction Number: " . $params['trans_id'] . "\n");
				$printer->text('TXN Date/Time: ' . date('D, M d, Y h:i A') . "\n");
				$printer->text("Cashier: " . ucwords(strtolower($this->session->userdata('fullname'))) . "\n");


				$printer->feed(2);
				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer->text("Have a Great Day! :)\n");
				// $printer->text($row . "\n");
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
		$entities = array();

		if (count($this->input->post()))
		{
			$entities = $this->_handleBillingReport();
		}

		$data = array(
				'title'    => 'Filter Dates to Generate Billing Report',
				'content'  => 'transaction/generate_reports_view',
				'entities' => $entities,
				'params'   => $this->input->post()
			);

		$this->load->view('include/template', $data);
	}

	protected function _handleBillingReport()
	{
		$entities = array();

		$from = date('Y-m-d', strtotime($this->input->post('from')));
		$to   = date('Y-m-d', strtotime($this->input->post('to')));

		$config = array(
				'from' => $from,
				'to'   => $to
			);

		if ($this->input->post('pdf_report') !== null)
		{
			$entities = $this->transaction->billing_report($config);

			$this->_billing_to_pdf($entities);
		}
		else if ($this->input->post('excel_report') !== null)
		{
			$this->_billing_to_excel();	
		}
		else if ($this->input->post('filter_date') !== null)
		{
			$entities = $this->transaction->billing_report($config);
		}
		else
		{
			// Fetch data which has null invoice_id
			$entities = $this->transaction->subjectForInvoice($config);

			if (count($entities) > 0)
			{
				// Generate invoice_id
				$invoice_id = $this->transaction->createInvoice();

				foreach($entities as $entity)
				{
					$formatData = array(
							'id'         => $entity['id'],
							'invoice_id' => $invoice_id
						);

					$this->transaction->updateTransInvoice($formatData);
				}

				$this->_billing_to_pdf($entities, $invoice_id);
			}
		}

		return $entities;
	}

	protected function _billing_to_pdf($entities, $invoice_id = 0)
	{
		// Change date format
		$from = date('Y-m-d', strtotime($this->input->post('from')));
		$to   = date('Y-m-d', strtotime($this->input->post('to')));

		$config = array(
				'from' => $from,
				'to'   => $to
			);

		// Verify if there is something to generate
		if (count($entities))
		{
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->set_paper('A4');

			// Header options
			$font       = $dompdf->getFontMetrics()->get_font("helvetica", "normal");
			$size       = 8;
			$color      = array(0, 0, 0);
			$word_space = 0.0;  //  default
			$char_space = 0.0;  //  default
			$angle      = 0.0;  //  default

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


			// Render the HTML as PDF
			$dompdf->render();

			// Add headers and pagination
			$text = 'From ' . date('M d, Y', strtotime($config['from'])) . ' to ' . date('M d, Y', strtotime($config['to']));
	        $dompdf->getCanvas()->page_text(40, 55, $text, $font, $size, $color, $word_space, $char_space, $angle);

	        $text = date('m/d/Y h:i A');
	        $dompdf->getCanvas()->page_text(400, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

	        if ($invoice_id > 0)
	        {
	        	$text = "Invoice No. " . sprintf('%06d',ucwords(strtolower($invoice_id)));
	        	$dompdf->getCanvas()->page_text(400, 40, $text, $font, 10, $color, $word_space, $char_space, $angle);

	        	$text = "Printed by: " . ucwords(strtolower($this->session->userdata('fullname')));
	        	$dompdf->getCanvas()->page_text(400, 55, $text, $font, $size, $color, $word_space, $char_space, $angle);
	        }
	        else
	        {
	        	$text = "Printed by: " . ucwords(strtolower($this->session->userdata('fullname')));
	        	$dompdf->getCanvas()->page_text(400, 45, $text, $font, $size, $color, $word_space, $char_space, $angle);
	        }
	        

	        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
	        $dompdf->getCanvas()->page_text(520, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

	        $text = "Billing Report";
	        $size = 14;
	        $headerFont = $dompdf->getFontMetrics()->get_font("helvetica", "bold");

	        $dompdf->getCanvas()->page_text(40, 30, $text, $font, $size, $color, $word_space, $char_space, $angle);

			// Output the generated PDF to Browser
			$dompdf->stream();
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="alert alert-warning">There is no result on that date range!</div>');

			redirect($this->agent->referrer());
		}
	}

	// Create billing report on excel file
	protected function _billing_to_excel()
	{
		// Change date format
		$from = date('Y-m-d', strtotime($this->input->post('from')));
		$to   = date('Y-m-d', strtotime($this->input->post('to')));

		$config = array(
				'from' => $from,
				'to'   => $to
			);

		// Fetch data
		$entities = $this->transaction->billing_report($config);

		// Total Credit
		$total_bill = number_format(array_sum(array_column($entities, 'credit_used')), 2);

		// Verify if there is something to generate
		if (count($entities) > 0)
		{
			// Create php excel instance
			$excelObj          = new PHPExcel();
			$excelActiveSheet  = $excelObj->getActiveSheet();
			$excelDefaultStyle = $excelObj->getDefaultStyle();

			// Set text alignment to left
			$excelDefaultStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			// Set default fontsize to 8
			$excelDefaultStyle->getFont()->setSize(8);


			// Change the date format
			$dataArray = array_map(function($item) {
							$item['datetime']    = date('m/d/Y h:i:s A', strtotime($item['datetime']));
							$item['credit_used'] = $item['credit_used'] ? number_format($item['credit_used'], 2) : ''; 
							$item['cash']        = $item['cash'] ? number_format($item['cash'], 2) : '';

							return array_values($item);
						}, $entities);

			// Set the Active sheet
			$excelObj->setActiveSheetIndex(0);

			// Merge the cell for the billing title
			$excelActiveSheet->mergeCells('A1:D1');

			// Set the size to show it as a lead
			$excelActiveSheet->getStyle('A1:D1')->getFont()->setSize(11);

			$excelActiveSheet->getHeaderFooter()->setOddHeader('&R Page &P of &N');
			$excelActiveSheet->getHeaderFooter()->setEvenHeader('&R Page &P of &N');

			// Add header to the excel
			$excelActiveSheet->setCellValue('A1', 'Billing Report from ' . date('m/d/Y', strtotime($config['from'])) . ' to ' . date('m/d/Y', strtotime($config['to'])))
					->setCellValue('A2', 'Trans. ID')
					->setCellValue('B2', 'Employee')
					->setCellValue('C2', 'Credit Used')
					->setCellValue('D2', 'Cash Used')
					->setCellValue('E2', 'Date')
					->setCellValue('F2', 'Cashier');

			// Set the header to bold
			$excelActiveSheet->getStyle('A2:F2')->getFont()->setBold(true);

			// Set the with of the cell to autosize
			$excelActiveSheet->getColumnDimension('B')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('C')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('D')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('E')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('F')->setAutoSize(true);

			// Write the formatted data
			$excelActiveSheet->fromArray($dataArray, NULL, 'A3');

			// Calculate cell range to merge
			$cell_from = 'A' . (string)($excelObj->setActiveSheetIndex(0)->getHighestRow() + 2);
			$cell_to   = 'C' . (string)($excelObj->setActiveSheetIndex(0)->getHighestRow() + 2);

			// Set the value on calculated location
			$excelActiveSheet->mergeCells($cell_from . ':' . $cell_to);
			$excelActiveSheet->getStyle($cell_from. ':' . $cell_to)->getFont()->setSize(11);
			$excelActiveSheet->setCellValue($cell_from, 'Total Bill Amount: ' . $total_bill);

			// Apply background color on cell
			$excelActiveSheet->getStyle('A2:F2')
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('FF808080');

			// Paper Size
			$excelActiveSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

			// Set margins
			$excelActiveSheet->getPageMargins()->setTop(0.25);
			$excelActiveSheet->getPageMargins()->setRight(0.25);
			$excelActiveSheet->getPageMargins()->setLeft(0.25);
			$excelActiveSheet->getPageMargins()->setBottom(0.25);

			// Change the text color to white
			$excelActiveSheet->getStyle('A2:F2')->getFont()->getColor()->setRGB('FFFFFF');

			// Excel filename
			$filename = 'billing_report.xls';

			// Content header information
			header('Content-Type: application/vnd.ms-excel'); //mine type
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Cached-Control: max-age=0');

			// Generate excel version using Excel 2017
			$objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel5');

			$objWriter->save('php://output');
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="alert alert-warning">There is no result on that date range!</div>');

			redirect($this->agent->referrer());
		}
		
	}

	public function invoice_list()
	{
		$data = array(
				'title'    => 'List of Invoice',
				'content'  => 'invoice/list_view',
				'entities' => $this->transaction->invoiceList()
			);

		$this->load->view('include/template', $data);
	}

	public function invoice_form()
	{
		$this->load->helper('form');

		$id = $this->uri->segment(3);

		$entity = $this->transaction->invoiceEntity($id);

		$data = array(
				'title'  => 'Update Invoice Status',
				'entity' => $entity
			);

		$this->load->view('invoice/form_view', $data);
	}

	public function invoice_update()
	{
		$config = array_map('trim', $this->input->post());

		unset($config['invoice_no']);

		$config['status']      = ucfirst(strtolower($config['status']));
		$config['last_user']   = $this->session->userdata('id');
		$config['last_update'] = date('Y-m-d H:i:s');

		$this->transaction->updateInvoiceStatus($config);

		$this->session->set_flashdata('message', '<div class="alert alert-success">Invoice has been updated!</div>');

		redirect($this->agent->referrer());
	}

	public function invoice_item()
	{
		$entities = $this->_handleInvoiceItems($this->input->post('invoice_no'));

		$data = array(
				'title'    => 'List of Invoice Items',
				'content'  => 'invoice/items_view',
				'rows'     => $this->transaction->invoiceList(),
				'params'   => $this->input->post(),
				'entities' => $entities
			);

		$this->load->view('include/template', $data);
	}	

	protected function _handleInvoiceItems($params)
	{
		if (count($params))
		{
			return $this->transaction->fetchInvoiceItems($params);
		}	
	}

	protected function _redirectUnauthorized()
	{
		if (count($this->session->userdata()) < 3)
		{
			redirect(base_url());
		}
	}
}