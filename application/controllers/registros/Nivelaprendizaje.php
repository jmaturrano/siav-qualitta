<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nivelaprendizaje extends CI_Controller {
    private static $header_title  = 'Niveles de Aprendizaje';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/nivelaprendizaje_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/nivelaprendizaje');
    }

    public function index($offset = 0) {
        $data['OFICINAS']           = self::$OFICINAS;
        $data['ROLES']              = self::$ROLES;
        $data['PRIVILEGIOS']        = self::$PRIVILEGIOS;
        $data['PERMISOS']           = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']       = self::$header_title;
        $data['header_icon']        = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base']        = 'registros/nivelaprendizaje/index';
        $propio['filas_totales']    = $this->nivelaprendizaje_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_niap']          = $this->nivelaprendizaje_model->getNivelaprendizajeAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']          = 'registros/nivelaprendizaje/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('registros/nivelaprendizaje_index', $data);
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
        $data['btn_nuevo']      = 'registros/nivelaprendizaje/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/nivelaprendizaje');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/nivelaprendizaje') : '';
            $data['data_niap']  = $this->nivelaprendizaje_model->getNivelaprendizajeAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/nivelaprendizaje_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($niap_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($niap_id_enc === '') ? redirect('registros/nivelaprendizaje') : '';
        $niap_id = str_decrypt($niap_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeByID($niap_id);
        $data['btn_editar']     = 'registros/nivelaprendizaje/editar/'.$niap_id_enc;
        $data['btn_regresar']   = 'registros/nivelaprendizaje';
        $this->layout->view('registros/nivelaprendizaje_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($niap_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($niap_id_enc === '') ? redirect('registros/nivelaprendizaje') : '';
        $niap_id = str_decrypt($niap_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeByID($niap_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/nivelaprendizaje';
        $this->layout->view('registros/nivelaprendizaje_form', $data);
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
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/nivelaprendizaje';
        $this->layout->view('registros/nivelaprendizaje_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($niap_id_enc = ''){
        ($niap_id_enc === '') ? redirect('registros/nivelaprendizaje') : '';
        $niap_id = str_decrypt($niap_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->nivelaprendizaje_model->deleteNivelaprendizajeByID($niap_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/nivelaprendizaje');
    }

    public function guardar($niap_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $niap_id = ($niap_id_enc === '') ? '' : str_decrypt($niap_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('niap_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('niap_descripcion', 'Nombre nivelaprendizaje', 'required|trim');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($niap_id === '')?'nuevo':'editar';
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeByID(($niap_id === '')?0:$niap_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/nivelaprendizaje';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/nivelaprendizaje_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_niap = array(
                'niap_codigo'               => $datapost['niap_codigo'],
                'niap_descripcion'          => $datapost['niap_descripcion']
            );
            $data_response = ($niap_id === '') ? $this->nivelaprendizaje_model->insertNivelaprendizaje($data_niap) : $this->nivelaprendizaje_model->updateNivelaprendizaje($data_niap, $niap_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($niap_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/nivelaprendizaje');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/nivelaprendizaje_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}































































































































