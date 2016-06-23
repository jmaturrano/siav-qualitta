<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public $layout = null;   

    public function __construct() {
        parent :: __construct();
        $this->load->model('seguridad/configuracion_model');
    }

    

    public function index() {

        
        $data['data_conf']  = $this->configuracion_model->getConfigurationData();
        //die('data_conf');
        

        $msg = $this->load->view('style/admin', $data, true);
        $this->output->set_content_type('text/css');
        $this->output->set_output($msg);
        
    }

    

}

