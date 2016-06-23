<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout_Popup {

    private $ci;
    private $layout = 'layout/layout_popup';
    
    public function __construct() {
        $this->ci = &get_instance();
    }

    public function view($view, $parametros = null) {
        $data = array();
        $data['contenido'] = $this->ci->load->view($view, $parametros, true);
        return $this->ci->load->view($this->layout, $data, false);
    }
    
}
