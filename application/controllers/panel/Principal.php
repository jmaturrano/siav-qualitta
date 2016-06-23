<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {

    private static $PARAMETROS = array();
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('panel/principal_model');
        
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        $this->initOffice();
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'panel/principal');
    }

    public function initOffice(){
        if(!$this->session->userdata('ofic_id')){
            $data_session   = array('ofic_id'=> 0);
            $this->session->set_userdata($data_session);
            if(isset(self::$OFICINAS)){
                foreach (self::$OFICINAS as $item => $oficina) {
                    if($oficina->uxof_estadodefecto === 'S'){
                        $data_session   = array('ofic_id'=>$oficina->ofic_id);
                        $this->session->set_userdata($data_session);
                    }//end if
                }//end for
            }
        }//end if
    }

    public function index() {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $this->layout->view('panel/principal_index', $data);
    }

    public function cambiarofic($uxof_id = '', $ofic_id = ''){
        ($ofic_id === '') ? exit() : '';
        $data_session   = array('ofic_id'=>$ofic_id);
        $this->session->set_userdata($data_session);
        echo "1_|_".$ofic_id;
    }

}

