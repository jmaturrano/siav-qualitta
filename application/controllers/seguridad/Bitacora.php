<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitacora extends CI_Controller {
    private static $header_title  = 'Bitacora';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $FIELD_DATA = array();
    private static $PERMISOS = array();


    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/bitacora_model');
        $this->load->model('seguridad/bitacorausuario_model');
        /*
        Politica
        Programa Presupuestal
        Producto
        Actividad
        Subactividad
        Poa
        Fuentes de Financiamiento
        Metas y Ejecucione Fisicas
        Metas y Ejecuciones Presupuestales
        */
        
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        //self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/bitacora');
    }

    public function index() {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        
    }

   

}
