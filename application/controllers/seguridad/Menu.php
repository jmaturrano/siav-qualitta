<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {
    private static $header_title  = 'Menú del sistema';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $FIELD_DATA = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/menu_model');
        //self::$FIELD_DATA   = $this->configuracion_model->field_data();
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/menu');
    }

    public function index() {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('layout');
        $data['data_menu']      = $this->menu_model->getMenuAll2();
        $data['btn_nuevo']      = 'seguridad/menu/nuevo';
        $this->layout->view('seguridad/menu_index', $data);
        $this->load->view('notificacion');
    }

    public function listitem(){
    	$data['arrMenu'] = $this->menu_model->getMenuAccess($this->session->userdata('usua_id'), $this->session->userdata('ofic_id'));
        echo json_encode($data['arrMenu']);
    }

    public function buscar(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['btn_nuevo']      = 'seguridad/menu/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/menu');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/menu') : '';
            $data['data_menu']  = $this->menu_model->getMenuAll2($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/menu_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function accesodirecto(){
        $datapost = $this->security->xss_clean($this->input->post());
        ($datapost['menu_codigo'] === '') ? redirect('panel/principal') : '';
        $data_menu              = $this->menu_model->getMenuByCODIGO($datapost['menu_codigo']);
        if(isset($data_menu)){
            redirect($data_menu->menu_formulario);
        }else{
            redirect('panel/principal');
        }
    }

    public function ver($menu_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        ($menu_id_enc === '') ? redirect('seguridad/menu') : '';
        $menu_id = str_decrypt($menu_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['tipo_vista']     = 'ver';
        $data['data_menu']      = $this->menu_model->getMenuByID($menu_id);
        $data['data_menupadre'] = $this->menu_model->getMenuPadreAll($menu_id);
        $data['btn_editar']     = 'seguridad/menu/editar/'.$menu_id_enc;
        $data['btn_regresar']   = 'seguridad/menu';
        $this->layout->view('seguridad/menu_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($menu_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        ($menu_id_enc === '') ? redirect('seguridad/menu') : '';
        $menu_id = str_decrypt($menu_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['tipo_vista']     = 'editar';
        $data['data_menu']      = $this->menu_model->getMenuByID($menu_id);
        $data['data_menupadre'] = $this->menu_model->getMenuPadreAll($menu_id);
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'seguridad/menu';
        $this->layout->view('seguridad/menu_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('layout');
        $data['tipo_vista']     = 'editar';
        $data['data_menupadre'] = $this->menu_model->getMenuPadreAll();
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'seguridad/menu';
        $this->layout->view('seguridad/menu_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($menu_id_enc = ''){
        ($menu_id_enc === '') ? redirect('seguridad/menu') : '';
        $menu_id                 = str_decrypt($menu_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->menu_model->deleteMenuByID($menu_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/menu');
    }

    public function guardar($menu_id_enc = ''){
        $menu_id = ($menu_id_enc === '') ? '' : str_decrypt($menu_id_enc, KEY_ENCRYPT);
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('layout');
        $data['tipo_vista']     = ($menu_id === '')?'nuevo':'editar';
        $data['data_menupadre'] = $this->menu_model->getMenuPadreAll($menu_id);
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'seguridad/menu';

        $this->form_validation->set_rules('menu_nivel', 'Nivel', 'required');
        $this->form_validation->set_rules('menu_idpadre', 'Superior', '');
        $this->form_validation->set_rules('menu_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('menu_orden', 'Nro. Orden Grupo', 'required|numeric');
        $this->form_validation->set_rules('menu_formulario', 'Formulario', 'trim');
        $this->form_validation->set_rules('menu_descripcion', 'Descripción', 'required|trim');
        $this->form_validation->set_rules('menu_control_agencia', 'Control Agencia', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/menu_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_menu = array(
                'menu_nivel'          => $datapost['menu_nivel'],
                'menu_idpadre'        => (isset($datapost['menu_idpadre'])?$datapost['menu_idpadre']:0),
                'menu_codigo'         => $datapost['menu_codigo'],
                'menu_orden'          => (int)$datapost['menu_orden'],
                'menu_formulario'     => (isset($datapost['menu_formulario'])?$datapost['menu_formulario']:''),
                'menu_descripcion'    => $datapost['menu_descripcion'],
                'menu_control_agencia'=> $datapost['menu_control_agencia']
            );
            $data_response  = ($menu_id === '') ? $this->menu_model->insertMenu($data_menu) : $this->menu_model->updateMenu($data_menu, $menu_id);
            if($data_response){
                $message_response = ($menu_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE;
                $menu_id     = $data_response;
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', $message_response);
                redirect('seguridad/menu/ver/'.str_encrypt($menu_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/menu_form', $data);
            }
        }
        $this->load->view('notificacion');
    }



}

