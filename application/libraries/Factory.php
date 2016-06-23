<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'third_party/PHPExcel_1.8.0/PHPExcel/IOFactory.php');

class Factory extends PHPExcel_IOFactory
{
  // Extend FPDF using this class
  // More at fpdf.org -> Tutorials
  function __construct()
  {       
    parent::__construct();
  }
}
?>