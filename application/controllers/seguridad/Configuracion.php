<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {
    private static $header_title  = 'Configuración General';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $FIELD_DATA = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/configuracion_model');
        self::$FIELD_DATA   = $this->configuracion_model->field_data();
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/configuracion');
    }

    public function index($conf_id_enc = '') {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['data_conf']      = $this->configuracion_model->getConfigurationData();
        $conf_id = $data['data_conf']->conf_id;
        $data['tipo_vista']     = 'ver';
        $data['btn_editar']     = 'seguridad/configuracion/editar/'.str_encrypt($conf_id, KEY_ENCRYPT);
        //echo "flag";
        $this->layout->view('seguridad/configuracion_index', $data);
        $this->load->view('notificacion');
    }

    public function actualizar($conf_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $conf_id = ($conf_id_enc === '') ? '' : str_decrypt($conf_id_enc, KEY_ENCRYPT);
        ($conf_id === '') ? redirect('seguridad/configuracion') : '';
        $this->form_validation->set_rules('conf_nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('conf_ruc', 'RUC', 'trim');
        $this->form_validation->set_rules('conf_direccion', 'Dirección', 'required');
        $this->form_validation->set_rules('conf_email', 'Email', 'required|trim|valid_email');
        //$this->form_validation->set_rules('conf_diasexpclave', 'Días de expiración clave', 'required|trim');
        $this->form_validation->set_rules('conf_fecha_registro', 'Fecha de registro', 'required');
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['data_conf']      = $this->configuracion_model->getConfigurationData();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/configuracion';
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/configuracion_index', $data);
            $this->load->view('notificacion');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_conf = array(
                'conf_nombre'           => $datapost['conf_nombre'],
                'conf_ruc'              => $datapost['conf_ruc'],
                'conf_direccion'        => $datapost['conf_direccion'],
                'conf_email'            => $datapost['conf_email'],
                'conf_diasexpclave'     => 30,
                'conf_fecha_registro'   => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->input->post('conf_fecha_registro')))) ,
                'conf_temacolor'        => $datapost['conf_temacolor']
            );
            $result = $this->configuracion_model->updateConfiguration($data_conf, $conf_id);
            if($result){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', RMESSAGE_UPDATE);
                redirect('seguridad/configuracion');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/configuracion_index', $data);
            }
        }
    }

    public function editar($conf_id_enc = '') {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['data_conf']      = $this->configuracion_model->getConfigurationData();
        $conf_id = $data['data_conf']->conf_id;
        $data['tipo_vista']     = 'editar';
        //$data['btn_editar']     = 'configuracion/editar/'.str_encrypt($conf_id, KEY_ENCRYPT);
        $data['btn_guardar']     = true;
        $data['btn_cancelar']   = 'seguridad/configuracion';
        $this->layout->view('seguridad/configuracion_index', $data);
        $this->load->view('notificacion');
    }

}































































