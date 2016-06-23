<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rol extends CI_Controller {
    private static $header_title  = 'Roles';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/rol_model');
        $this->load->model('seguridad/menu_model');
        $this->load->model('seguridad/menuxrol_model');

    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/rol');
    }

    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'seguridad/rol/index';
        $propio['filas_totales'] = $this->rol_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_rol']       = $this->rol_model->getRolAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'seguridad/rol/nuevo';
        $this->layout->view('seguridad/rol_index', $data);
        $this->load->view('notificacion');
    }

    public function buscar(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['btn_nuevo']      = 'seguridad/rol/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/rol');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/rol') : '';
            $data['data_rol']   = $this->rol_model->getRolAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/rol_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function buscaritem($rol_id_enc = '', $q = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rol_id_enc === '') ? redirect('seguridad/rol') : '';
        $rol_id = str_decrypt($rol_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_rol']       = $this->rol_model->getRolByID($rol_id);
        $data['data_mxro']      = $this->menuxrol_model->getMenuAllByRol($rol_id);
        $data['btn_editar']     = 'seguridad/rol/editar/'.$rol_id_enc;
        $data['btn_regresar']   = 'seguridad/rol';

        ($q === '') ? redirect('seguridad/rol/ver/'.$rol_id_enc) : '';
        $data['data_mxro']  = $this->menuxrol_model->getMenuAllByRol($rol_id, $q);
        $data['q']          = $q;
        $this->layout->view('seguridad/rol_form', $data);
        $this->load->view('notificacion');
    }

    public function ver($rol_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($rol_id_enc === '') ? redirect('seguridad/rol') : '';
        $rol_id = str_decrypt($rol_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_rol']       = $this->rol_model->getRolByID($rol_id);
        $data['data_mxro']      = $this->menuxrol_model->getMenuAllByRol($rol_id);
        $data['btn_editar']     = 'seguridad/rol/editar/'.$rol_id_enc;
        $data['btn_regresar']   = 'seguridad/rol';
        $this->layout->view('seguridad/rol_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($rol_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rol_id_enc === '') ? redirect('seguridad/Rol') : '';
        $rol_id = str_decrypt($rol_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_rol']       = $this->rol_model->getRolByID($rol_id);
        $data['data_mxro']      = $this->menuxrol_model->getMenuAllByRol($rol_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/rol';

        $this->layout->view('seguridad/rol_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['data_mxro']      = $this->menuxrol_model->getMenuAllByRol(0);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/rol';
        $this->layout->view('seguridad/rol_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($rol_id_enc = ''){
        ($rol_id_enc === '') ? redirect('seguridad/Rol') : '';
        $rol_id                 = str_decrypt($rol_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->rol_model->deleteRolByID($rol_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/rol');
    }

    public function guardar($rol_id_enc = ''){
        $rol_id = ($rol_id_enc === '') ? '' : str_decrypt($rol_id_enc, KEY_ENCRYPT);

        $datapost = $this->security->xss_clean($this->input->post());
        $this->form_validation->set_rules('rol_nombre', 'Nombre rol', 'required|trim');
        $this->form_validation->set_rules('menu_id[]', 'Nombre rol', 'required');
        $this->form_validation->set_rules('mxro_accesa[]', 'Permiso de menú', 'required');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($rol_id === '')?'nuevo':'editar';
        $data['data_rol']       = ($rol_id === '') ? null : $this->rol_model->getRolByID($rol_id);
        $data['data_mxro']      = ($rol_id === '') ? $this->menuxrol_model->getMenuAllByRol(0) : $this->menuxrol_model->getMenuAllByRol($rol_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/rol';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/rol_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_rol = array(
                'rol_nombre'          => $datapost['rol_nombre']
            );
            $data_response  = ($rol_id === '') ? $this->rol_model->insertRol($data_rol) : $this->rol_model->updateRol($data_rol, $rol_id);
            if($data_response){
                $message_response = ($rol_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE;
                $rol_id     = $data_response;
                $this->menuxrol_model->updateMenuxRolByROL($rol_id);
                foreach ($datapost['menu_id'] as $item => $menu_id) {
                    $data_verify = $this->menuxrol_model->verificaMenuxRol($rol_id, $datapost['menu_id'][$item]);
                    $data_mxr = array(
                        'mxro_accesa'       => ($datapost['mxro_accesa'][$item] === '' || $datapost['mxro_accesa'][$item] === NULL) ? '0':$datapost['mxro_accesa'][$item],
                        'mxro_ingresa'      => ($datapost['mxro_ingresa'][$item] === '' || $datapost['mxro_ingresa'][$item] === NULL) ? '0':$datapost['mxro_ingresa'][$item],
                        'mxro_elimina'      => ($datapost['mxro_elimina'][$item] === '' || $datapost['mxro_elimina'][$item] === NULL) ? '0':$datapost['mxro_elimina'][$item],
                        'mxro_modifica'     => ($datapost['mxro_modifica'][$item] === '' || $datapost['mxro_modifica'][$item] === NULL) ? '0':$datapost['mxro_modifica'][$item],
                        'mxro_consulta'     => ($datapost['mxro_consulta'][$item] === '' || $datapost['mxro_consulta'][$item] === NULL) ? '0':$datapost['mxro_consulta'][$item],
                        'mxro_imprime'      => ($datapost['mxro_imprime'][$item] === '' || $datapost['mxro_imprime'][$item] === NULL) ? '0':$datapost['mxro_imprime'][$item],
                        'mxro_exporta'      => ($datapost['mxro_exporta'][$item] === '' || $datapost['mxro_exporta'][$item] === NULL) ? '0':$datapost['mxro_exporta'][$item],
                        'rol_id'            => $rol_id,
                        'menu_id'           => $datapost['menu_id'][$item]
                    );
                    if(count($data_verify) === 0){
                        $this->menuxrol_model->insertMenuxRol($data_mxr);
                    }else{
                        $this->menuxrol_model->updateMenuxRol($data_mxr, $datapost['mxro_id'][$item]);
                    }
                }//end foreach
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', $message_response);
                redirect('seguridad/rol/ver/'.str_encrypt($rol_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/rol_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}































































































































