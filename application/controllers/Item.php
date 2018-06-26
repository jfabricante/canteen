<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/TCPDF/tcpdf.php';

require_once APPPATH . '/third_party/PHPExcel/Classes/PHPExcel.php';

class Item extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->_redirectUnauthorized();

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

	public function ajax_has_duplicate()
	{
		$hasDuplicate = $this->item->hasDuplicateBarcode($this->input->post());

		echo json_encode(array_values($hasDuplicate));
	}

	public function oldMenu()
	{
		$category = array(
				'Meal'   => 1,
				'Pastry' => 2,
				'Snack'  => 3,
				'Drinks' => 4,
				'Others' => 5
			);

		$oldMenu = $this->item->fetchOldMenu();

		$config = array();

		foreach ($oldMenu as $menu)
		{
			if (strpos($menu['item_name'], '/'))
			{
				$menus = array_map('trim', explode('/', $menu['item_name']));

				foreach ($menus as $row)
				{
					if (strpos($row, '-'))
					{
						$config[] = array(
								'name'        => substr($row, 2),
								'category_id' => $category[$menu['item_type']],
								'price'       => $menu['price']
							);
					}
					else
					{
						$config[] = array(
								'name'        => $row,
								'category_id' => $category[$menu['item_type']],
								'price'       => $menu['price']
							);	
					}
				}
			}
			else
			{
				if (strpos($menu['item_name'], '-'))
				{
					$config[] = array(
							'name'        => substr($menu['item_name'], 2),
							'category_id' => $category[$menu['item_type']],
							'price'       => $menu['price']
						);
				}
				else
				{
					$config[] = array(
							'name'        => $menu['item_name'],
							'category_id' => $category[$menu['item_type']],
							'price'       => $menu['price']
						);
				}
				
			}
		}

		$datetime = date('Y-m-d H:i:s');

		foreach ($config as $entity)
		{
			$item = array(
					'name'     => $entity['name'],
					'price'    => $entity['price'],
					'datetime' => $datetime
				);

			$item_id = $this->item->insertItem($item);

			$item_category = array(
					'item_id'     => $item_id,
					'category_id' => $entity['category_id'],
				);

			$this->item->insertCategory($item_category);
		}

		echo '<pre>';
		print_r($config);
		echo '<pre>';
	}

	public function items_barcode()
	{
		$config = array_column($this->item->fetchItemsBarcode(), 'barcode');
		$barcode = array_filter($config, 'is_numeric');

		$items = $this->item->fetchItemsNoBarcode();

		$unique = array();

		$config = array();

		while (count($unique) < count($items))
		{
			$generated = 'IPC' . sprintf("%d", abs(rand(100000000, 999999999)));

			while (in_array($generated, $barcode) || in_array($generated, $unique))
			{
				$generated = 'IPC' . sprintf("%d", abs(rand(100000000, 999999999)));
			}

			array_push($unique, $generated);
		}

		for($i = 0; $i < count($items); $i++)
		{
			$temp = array(
				'id'      => $items[$i]['id'],
				'name'    => $items[$i]['name'],
				'barcode' => $unique[$i]
			);

			$this->item->updateItemBarcode($temp);
		}
	}

	public function display_barcode_layout()
	{
		$entities = $this->item->fetchCreatedBarcode();

		$this->_createPdf($entities);		
	}

	protected function _createPdf($params)
	{
		ob_start();
		// Create TCPDF instance
		$pdf = new TCPDF;

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('helvetica', '', 25);

		// add a page
		$pdf->AddPage();

		// -----------------------------------------------------------------------------
		$style = array(
		    'position'     => '',
		    'align'        => 'L',
		    'stretch'      => false,
		    'fitwidth'     => true,
		    'cellfitalign' => '',
		    'border'       => false,
		    'hpadding'     => 'auto',
		    'vpadding'     => 'auto',
		    'fgcolor'      => array(0,0,0),
		    'bgcolor'      => false,
		    'text'         => true,
		    'font'         => 'helveticaB',  //array(255,255,255),
		    'stretchtext'  => 4,
		);

		$style['fontsize'] = 12;

		// set color for background
		$pdf->SetFillColor(255, 255, 255);

		// set color for text
		$pdf->SetTextColor(0, 0, 0);

		$content = '';

		$px = 15;
		$py = 20;

		$bx = 15;
		$by = 30;

		$count = 1;

		$x = $pdf->getX();
		$y = $pdf->getY();

		$style7 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 128, 0));

		$pdf->Text(15, 4, $params[0]['category']);

		$currentCat = $params[0]['category'];

		foreach ($params as $entity)
		{
			if (($count % 13 == 0) || ($currentCat != $entity['category']))
			{
				$pdf->AddPage();
				$pdf->SetFont('helvetica', '', 25);
				$pdf->Text(15, 4, $entity['category']);

				$currentCat = $entity['category'];

				$count = 1;

				$px = 15;
				$py = 20;

				$bx = 15;
				$by = 30;
			}

			if ($count % 2 == 0)
			{
				$pdf->Rect($px + 100, $py - 5, 80, 40, $style7, array(255,255,255), array(255,255,255));
				// Label font
				$pdf->SetFont('helvetica', '', 18);
				$pdf->writeHTMLCell(0, 0, $px + 100, $py + 3, '<span>' . ucwords(strtolower($entity['name'])) . '</span>', 0, 0, false, true, 'B',false);
				$pdf->writeHTMLCell(0, 0, $px + 158, $py + 3, '<span>' . number_format($entity['price'], 2) . '</span>', 0, 0, false, true, 'B',false);

				// Label font
				$pdf->SetFont('helvetica', '', 25);
				$pdf->write1DBarcode($entity['barcode'], 'C39', $bx + 100, $by, '', 20, 0.4, $style, 'M');

				$py = $py + 40;
				$by = $by + 40;
			}
			else
			{
				$pdf->Rect($px, $py - 5, 80, 40, $style7, array(255,255,255), array(255,255,255));
				// Label font
				$pdf->SetFont('helvetica', '', 18);
				$pdf->writeHTMLCell(0, 0, $px, $py + 3, '<span>' . ucwords(strtolower($entity['name'])) . '</span>', 0, 0, false, true, 'B',false);
				$pdf->writeHTMLCell(0, 0, $px + 58, $py + 3, '<span>'. number_format($entity['price'], 2) . '</span>', 0, 0, false, true, 'B',false);

				// Label font
				$pdf->SetFont('helvetica', '', 25);
				$pdf->write1DBarcode($entity['barcode'], 'C39', $bx, $by, 80, 20, 0.4, $style, 'M');
			}

			$count++;

		}

		ob_end_clean();
		
		echo $pdf->Output('barcode.pdf', 'I');
	}

	public function diff_form()
	{
		$data = array(
				'title'   => 'Upload file',
				'content' => 'item/diff_view',
			);

		$this->load->view('include/template', $data);
	}

	public function ajax_excel_file()
	{
		$excelContent = $this->input->post()['data'];

		$excelItems = array_column($excelContent, 'ITEM');

		$excelItems = array_map('strtoupper', $excelItems);

		$sourceItem = $this->item->browse(array('type' => 'array'));

		$sourceItem = array_column($sourceItem, 'name');

		$sourceItem = array_map('strtoupper', $sourceItem);


		echo '<pre>';
		print_r(count(array_diff($excelItems, $sourceItem)));
		echo '</pre>';
	}

	// Create billing report on excel file
	public function pdf_item_list()
	{
		// Fetch data
		$entities = $this->item->browse(array('type' => 'array'));

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


			// Set the Active sheet
			$excelObj->setActiveSheetIndex(0);

			// Merge the cell for the billing title
			$excelActiveSheet->mergeCells('A1:D1');

			// Set the size to show it as a lead
			$excelActiveSheet->getStyle('A1:D1')->getFont()->setSize(11);

			$excelActiveSheet->getHeaderFooter()->setOddHeader('&R Page &P of &N');
			$excelActiveSheet->getHeaderFooter()->setEvenHeader('&R Page &P of &N');

			// Add header to the excel
			$excelActiveSheet->setCellValue('A1', 'Item Masterlist')
					->setCellValue('A2', 'Item ID')
					->setCellValue('B2', 'Item Name')
					->setCellValue('C2', 'Price')
					->setCellValue('D2', 'Thumbnail')
					->setCellValue('E2', 'Date')
					->setCellValue('F2', 'Barcode')
					->setCellValue('G2', 'Cat. ID')
					->setCellValue('H2', 'Category');


			// Set the header to bold
			$excelActiveSheet->getStyle('A2:F2')->getFont()->setBold(true);

			// Set the with of the cell to autosize
			$excelActiveSheet->getColumnDimension('B')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('C')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('D')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('E')->setAutoSize(true);
			$excelActiveSheet->getColumnDimension('F')->setAutoSize(true);

			// Write the formatted data
			// $excelActiveSheet->fromArray($dataArray, NULL, 'A3');
			$excelActiveSheet->fromArray($entities, NULL, 'A3');


			// Apply background color on cell
			$excelActiveSheet->getStyle('A2:H2')
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
			$excelActiveSheet->getStyle('A2:H2')->getFont()->getColor()->setRGB('FFFFFF');

			// Excel filename
			$filename = 'item_masterlist.xls';

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

	protected function _redirectUnauthorized()
	{
		if (count($this->session->userdata()) < 3)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-warning">Login first!</div>');
			redirect(base_url());
		}
	}
}