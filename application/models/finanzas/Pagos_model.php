<?php
/**
* MK System Soft  
*
* Modelo de Financiamiento carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_Model extends CI_Model {
    private static $table_menu  = '';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

 

}