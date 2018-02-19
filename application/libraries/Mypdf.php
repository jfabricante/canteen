<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

require_once APPPATH."/third_party/TCPDF/tcpdf.php";
 
class Mypdf extends TCPDF {

	protected $_covered_date;
	protected $_printed_by;

	public function __construct() {
		parent::__construct();

	}

	// Page header
    public function Header() {

		$this->SetFont('helvetica', 'B', 12);
		$html = "Billing Report";
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 5, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

		$this->SetFont('helvetica', 'N', 9);
		$html = date('m/d/Y h:i A');
		$this->writeHTMLCell($w = 0, $h = 0, $x = 130, $y = 5, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

		$html = 'Page '. $this->getAliasNumPage().' of '.$this->getAliasNbPages();;
		$this->writeHTMLCell($w = 0, $h = 0, $x = 165, $y = 5, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		
		// $this->SetFont('helvetica', 'N', 9);
		$html = $this->_covered_date;
		$this->writeHTMLCell($w = 0, $h = 0, $x = 10, $y = 11, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
		
		$html = "Printed by: " . $this->_printed_by;
		$this->writeHTMLCell($w = 0, $h = 0, $x = 140, $y = 11, $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);

    }

    // Set buyoff date
    public function setCoveredDAte($date)
    {
    	$this->_covered_date = $date;

    	return $this;
    }

    public function setPrintedBy($name)
    {
    	$this->_printed_by = $name;

    	return $this;
    }

    public function Footer()
    {

    }
}
