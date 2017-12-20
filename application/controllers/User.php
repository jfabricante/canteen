<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/third_party/PHPExcel/Classes/PHPExcel.php';

class User extends CI_Controller {

	public function __construct()
	{
		date_default_timezone_set('Asia/Manila');

		parent::__construct();

		$this->load->model('user_model', 'user');
		$this->load->model('ipc_model', 'ipc');
	}

	public function store_batch()
	{
		$data = $this->ipc->fetch_all(array('type' => 'array'));

		$ids = array_column($data, 'id');

		$this->user->store_batch($data)->assign_batch_role($ids);
	}

	public function list_()
	{
		$data = array(
				'title'   => 'List of Users',
				'content' => 'user/list_view',
				'users'   => $this->user->fetch()
			);

		$this->load->view('include/template', $data);
	}

	public function form()
	{
		$this->load->helper('form');

		$id = $this->uri->segment(3);

		$data = array(
				'title'  => $id ? 'Update User Details' : 'Add New User',
				'roles'  => $this->user->fetch_roles('array'),
				'entity' => $id ? $this->user->readDetails($id) : ''
			);

		$this->load->view('user/form_view', $data);
	}

	public function store()
	{
		$config = $this->input->post();

		$user_data = array(
				'id'       => $config['id'],
				'username' => $config['username'],
				'password' => $config['password'],
				'emp_no'   => $config['employee_no'],
				'fullname' => $config['fullname'],
				'datetime' => date('Y-m-d H:i:s')
			);

		$user_id = $this->user->store($user_data);

		$role = $this->user->findRole($user_id);

		$user_role = array(
				'id'      => $role['id'] ? $role['id'] : 0,
				'user_id' => $user_id,
				'role_id' => $config['role_id']
			);

		$this->user->store_role($user_role);

		redirect($this->agent->referrer());
	}

	public function count()
	{
		echo '<pre>';
		print_r($this->user->users_count());
		echo '<br />';
		print_r($this->user->users_role_count());
		echo '</pre>';
	}

	public function truncate()
	{
		$this->user->truncate_tbl();
	}

	public function entity()
	{
		$config =  array(
				'emp_no' => $this->input->get('employee_no')
			);

		echo $this->user->read($config) ? json_encode($this->user->read($config)) : '';
	}

	public function balances()
	{
		$data = array(
				'title'    => 'Balances',
				'content'  => 'user/balances_view',
				'entities' => $this->user->fetch_balances()
			);

		$this->load->view('include/template', $data);
	}

	public function purchased_items()
	{
		// Change to standard format
		$config = array(
				'from' => date('Y-m-d' ,strtotime($this->input->post('from'))),
				'to'   => date('Y-m-d' ,strtotime($this->input->post('to'))),
			);

		$entities = $this->user->fetchPurchasedItems($config);

		$data = array(
				'title'    => 'Purchased Items',
				'content'  => 'user/purchased_view',
				'entities' => $entities,
				'dates'    => $this->input->post(),
				'balance'  => $this->user->read_balance()
			);

		$this->load->view('include/template', $data);
	}

	public function ledger()
	{
		$config = $this->input->post();

		$entities = $this->_handleLedger();

		$data = array(
				'title'    => 'Ledger',
				'content'  => 'user/ledger_view',
				'rows'     => $this->user->fetch('array'),
				'entities' => $entities,
				'params'   => $config
			);

		$this->load->view('include/template', $data);
	}

	public function cashier_sales()
	{
		$entities = $this->_handleCashierSales();

		$data = array(
				'title'    => 'Cashier Sales Filter By Dates',
				'content'  => 'user/cashier_view',
				'rows'     => $this->user->cashiers(),
				'entities' => $entities,
				'params'   => $this->input->post() ? $this->input->post() : ''
			);

		$this->load->view('include/template', $data);
	}

	protected function _handleCashierSales()
	{
		$config = array_map('trim', $this->input->post());

		if (isset($config['emp_id']))
		{
			$config['user_id'] = $config['emp_id'];
			$config['from']    = date('Y-m-d', strtotime($config['from']));
			$config['to']      = date('Y-m-d', strtotime($config['to']));

			return $this->user->cashierSales($config);
		}
	}

	protected function _handleLedger()
	{
		$config = array_map('trim', $this->input->post());

		$data = array();

		if (isset($config['emp_no']))
		{
			$user = (array)$this->user->read($config);

			$config['user_id'] = $user['id'];
			$config['from']    = date('Y-m-d', strtotime($config['from']));
			$config['to']      = date('Y-m-d', strtotime($config['to']));

			$mealHistory = $this->user->mealHistory($config);

			$purchasedItems = $this->user->fetchPurchasedItems($config);

			$adjAmountTotal = array_sum(array_column($mealHistory, 'adj_amount'));

			$purchasedItemsTotal = array_sum(array_column($purchasedItems, 'total'));

			$running_balance = $user['meal_allowance'] - $adjAmountTotal + $purchasedItemsTotal;

			$data[] = array(
					'trans_date' => date('m/d/Y', strtotime($config['from'] . ' -1 days')),
					'trans_id'   => '',
					'debit'      => $running_balance >= 0 ? number_format($running_balance, 2) : '',
					'credit'     => $running_balance < 0 ? number_format(abs($running_balance), 2) : '',
					'remarks'    => 'Disclaimer: Running balance before ' . date('m/d/Y', strtotime($config['from']))
				);


			foreach ($mealHistory as $entity)
			{
				$data[] = array(
						'trans_date' => date('m/d/Y', strtotime($entity['payroll_date'])),
						'trans_id'   => '',
						'debit'      => $entity['adj_amount'] >= 0 ? number_format($entity['adj_amount'], 2) : '',
						'credit'     => $entity['adj_amount'] < 0 ? number_format(abs($entity['adj_amount']), 2) : '',
						'remarks'    => $entity['adj_code']
					);
			}

			foreach ($purchasedItems as $entity)
			{
				$data[] = array(
						'trans_date' => date('m/d/Y', strtotime($entity['datetime'])),
						'trans_id'   => $entity['id'],
						'debit'      => $entity['total'] <= 0 ? number_format($entity['total'], 2) : '',
						'credit'     => $entity['total'] > 0 ? number_format(abs($entity['total']), 2) : '',
						'remarks'    => $entity['name']
					);
			}

			if (isset($config['excel_report']))
			{
				$this->_excelReport($data, $user, $config);
			}

		}

		return $data;

	}

	protected function _excelReport($params, $params2, $params3)
	{
		$excelObj       = new PHPExcel();
		$excelActiveSheet  = $excelObj->getActiveSheet();
		$excelDefaultStyle = $excelObj->getDefaultStyle();

		// Params for summation
		$config = array(
				'total' => 'Total',
				'blank' => '',
				'totalDebit' => array_sum(array_column($params, 'debit')),
				'totalCredit' => array_sum(array_column($params, 'credit')),
			);

		$balance = $config['totalDebit'] - $config['totalCredit'];

		$excelDefaultStyle->getAlignment()->setWrapText(true);
		$excelDefaultStyle->getFont()->setSize(11)->setName('Calibri');

		$excelActiveSheet->mergeCells('A1:D1');
		$excelActiveSheet->setCellValue('A1','Meal Allowance Consumption');

		$excelActiveSheet->mergeCells('A3:D3');
		$excelActiveSheet->setCellValue('A3', 'Employee Name: ' . $params2['fullname']);

		$excelActiveSheet->mergeCells('A4:D4');
		$excelActiveSheet->setCellValue('A4', 'Trans. from ' . date('m/d/Y', strtotime($params3['from'])) . ' to ' . date('m/d/Y', strtotime($params3['to'])));

		$excelActiveSheet->mergeCells('A5:D5');
		$excelActiveSheet->setCellValue('A5', 'Balance as of ' . date('m/d/Y', strtotime($params3['to'])) . ' is ' . $balance);


		if ($balance < 0)
		{
			$excelActiveSheet->mergeCells('A6:I6');
			$excelActiveSheet->setCellValue('A6', 'You have credit balance of ' . abs($balance) . ' pesos to be deducted on next meal allowance credit');	
		}
		

		// Paper Size
		$excelActiveSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


		$excelActiveSheet->setCellValue('A8', 'Trans. Date')
							->setCellValue('B8', 'Trans. ID')
							->setCellValue('C8', 'Debit')
							->setCellValue('D8', 'Credit')
							->setCellValue('E8', 'Remarks');


		$excelActiveSheet->fromArray($params, NULL, 'A9');

		$hRow = $excelActiveSheet->getHighestRow() + 1;

		$excelActiveSheet->getStyle('C9:C' . $hRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$excelActiveSheet->getStyle('D9:D' . $hRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

		$excelActiveSheet->getColumnDimension('A')->setAutoSize(true);

		for ($i = 8; $i < $hRow; $i++)
		{
			$excelActiveSheet->mergeCells('E'. $i . ':I' . $i);
		}

		$excelActiveSheet->fromArray($config, NULL, 'A' . $hRow);

		// Excel filename
		$filename = 'ledger.xls';

		// Content header information
		header('Content-Type: application/vnd.ms-excel'); //mine type
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cached-Control: max-age=0');

		// Generate excel version using Excel 2017
		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel5');

		$objWriter->save('php://output');
	}

	protected function _showVars($params)
	{
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}

	public function transfer_balances()
	{
		$userBalances = $this->user->fetchBalances();
		$activeUsers  = $this->ipc->fetchActiveUsers();

		$config = array();

		foreach ($userBalances as $userEntity)
		{
			foreach ($activeUsers as $activeEntity)
			{
				if ($userEntity['emp_id'] == $activeEntity['employee_no'])
				{
					$config[] = array(
							'user_id'               => $activeEntity['id'],
							'meal_allowance'        => $userEntity['meal_allowance'] - $userEntity['excess_credit'],
							'datetime'              => $userEntity['lastUpdate'],
							'last_meal_credit'      => $userEntity['last_meal_credit'],
							'last_meal_credit_date' => $userEntity['last_credit_date']
						);
				}
			}
		}

		$this->user->transferBalances($config);
	}
}
