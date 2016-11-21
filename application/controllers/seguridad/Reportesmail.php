<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportesmail extends CI_Controller {
    private static $header_title  = 'Reportes email';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/usuario_model');
        $this->load->model('seguridad/reportesmail_model');
        $this->load->model('seguridad/reportexusuario_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/reportesmail');  
    }

    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']    = self::$header_title;
        $data['header_icon']     = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'seguridad/reportesmail/index';
        $propio['filas_totales'] = $this->reportesmail_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_rema']       = $this->reportesmail_model->getReportesmailAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']       = 'seguridad/reportesmail/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('seguridad/reportesmail_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/reportesmail/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/reportesmail');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/reportesmail') : '';
            $data['data_rema']  = $this->reportesmail_model->getReportesmailAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/reportesmail_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($rema_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rema_id_enc === '') ? redirect('seguridad/reportesmail') : '';
        $rema_id = str_decrypt($rema_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_rema']      = $this->reportesmail_model->getReportesmailByID($rema_id);
        $data['data_usua']      = $this->usuario_model->getUsuarioAll();
        $data['data_rexu']      = $this->reportexusuario_model->getUsuariosxReportexusuarioAll($rema_id);
        

        $data['btn_editar']     = 'seguridad/reportesmail/editar/'.$rema_id_enc;
        $data['btn_regresar']   = 'seguridad/reportesmail';
        $this->layout->view('seguridad/reportesmail_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($rema_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rema_id_enc === '') ? redirect('seguridad/reportesmail') : '';
        $rema_id = str_decrypt($rema_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_rema']      = $this->reportesmail_model->getReportesmailByID($rema_id);
        $data['data_usua']      = $this->usuario_model->getUsuarioAll();
        $data['data_rexu']      = $this->reportexusuario_model->getUsuariosxReportexusuarioAll($rema_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/reportesmail';
        $this->layout->view('seguridad/reportesmail_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo(){
        redirect('seguridad/reportesmail');
        
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['data_usua']      = $this->usuario_model->getUsuarioAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/reportesmail';
        $this->layout->view('seguridad/reportesmail_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($rema_id_enc = ''){
        ($rema_id_enc === '') ? redirect('seguridad/reportesmail') : '';
        $rema_id = str_decrypt($rema_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->reportesmail_model->deleteReportesmailByID($rema_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/reportesmail');
    }

    public function guardar($rema_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $rema_id = ($rema_id_enc === '') ? '' : str_decrypt($rema_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('rema_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('rema_titulo', 'Título', 'required|trim');
        $this->form_validation->set_rules('rema_descripcion', 'Descripción', 'required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($rema_id === '')?'nuevo':'editar';
        $data['data_rema']      = $this->reportesmail_model->getReportesmailByID(($rema_id === '')?0:$rema_id);
        $data['data_usua']      = $this->usuario_model->getUsuarioAll();
        $data['data_rexu']      = $this->reportexusuario_model->getUsuariosxReportexusuarioAll(($rema_id === '')?0:$rema_id);

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/reportesmail';

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/reportesmail_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_rema = array(
                'rema_codigo'               => $datapost['rema_codigo'],
                'rema_titulo'               => $datapost['rema_titulo'],
                'rema_descripcion'          => $datapost['rema_descripcion']
            );

            $data_response = ($rema_id === '') ? $this->reportesmail_model->insertReportesmail($data_rema) : $this->reportesmail_model->updateReportesmail($data_rema, $rema_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($rema_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $rema_id = $data_response;
                redirect('seguridad/reportesmail/ver/'.str_encrypt($rema_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/reportesmail_form', $data);
            }
        }
        $this->load->view('notificacion');
    }
   


}































