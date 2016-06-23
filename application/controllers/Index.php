<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    public function __construct() {
        parent :: __construct();
        $this->load->model('index_model');
    }

    public function index() {
        (!$this->session->userdata('usua_id')) ? redirect('seguridad/login') : redirect('panel/principal');
    }

}
