<?php

defined('BASEPATH') OR exit('No estan permitidos los scripts directos');

class Index_Model extends CI_Model {

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function index() {
        redirigir('');
    }

}
