<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {
    private static $header_title  = 'Perfil Usuario';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/usuario_model');
        $this->load->model('seguridad/docidentidad_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);    
    }

    public function index() {
        redirect('/');
    }

    public function editar($usua_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        ($usua_id_enc === '') ? redirect('seguridad/usuario') : '';
        $usua_id = str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_usua']      = $this->usuario_model->getUsuarioByID($usua_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/usuario';
        $this->layout->view('perfil/perfil_form', $data);
        $this->load->view('notificacion');

    }

    public function guardar($usua_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $usua_id = ($usua_id_enc === '') ? '' : str_decrypt($usua_id_enc, KEY_ENCRYPT);        
        if($usua_id !== ''){
            $this->form_validation->set_rules('usua_clave', 'Clave', 'required');
        }
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($usua_id === '')?'nuevo':'editar';
        $data['data_usua']      = $this->usuario_model->getUsuarioByID($usua_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/usuario';
        $this->load->library('layout');
        $datapost = $this->security->xss_clean($this->input->post());
            //print_r($datapost);
            if($datapost['usua_clave'] != ''){
                $data_usua = array(
                    'usua_clave'              => md5($datapost['usua_clave'])
                );
            }
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', primer_error_validation());
                redirect('perfil/usuario/editar/'.str_encrypt($usua_id, KEY_ENCRYPT), $data);
            }
            else
            {
                $data_response = $this->usuario_model->updateUsuario($data_usua, $usua_id);
                if($data_response){
                    $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                    $this->session->set_flashdata('mensaje', RMESSAGE_UPDATE);
                    redirect('perfil/usuario/editar/'.str_encrypt($usua_id, KEY_ENCRYPT), $data);
                }else{
                    $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                    $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                    $this->layout->view('perfil/usuario_form', $data);
                }
            }
        $this->load->view('notificacion');
    }


}































