<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipc extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');

		$this->_redirectUnauthorized();

		// Load model
		$this->load->model('ipc_model', 'ipc');
	}

	public function ajax_personal_info()
	{
		$emp_no = $this->uri->segment(3);

		$data = $this->ipc->fetch_personal_info($emp_no);

		echo json_encode($data);
	}

	public function ajax_dept_head_info()
	{
		$emp_no = $this->uri->segment(3);

		$data = $this->ipc->fetch_department_head($emp_no);

		echo json_encode($data);
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