<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulosxcarrera extends CI_Controller {
    private static $header_title  = 'Modulos por Carrera';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/modulosxcarrera_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/modulosxcarrera');
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
        $propio['ruta_base']        = 'registros/modulosxcarrera/index';
        $propio['filas_totales']    = $this->modulosxcarrera_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_modu']          = $this->modulosxcarrera_model->getModulosxcarreraAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']          = 'registros/modulosxcarrera/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('registros/modulosxcarrera_index', $data);
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
        $data['btn_nuevo']      = 'registros/modulosxcarrera/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/modulosxcarrera');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/modulosxcarrera') : '';
            $data['data_modu']  = $this->modulosxcarrera_model->getModulosxcarreraAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/modulosxcarrera_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($carr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($carr_id_enc === '') ? redirect('registros/modulosxcarrera') : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($carr_id);
        $data['btn_editar']     = 'registros/modulosxcarrera/editar/'.$carr_id_enc;
        $data['btn_regresar']   = 'registros/modulosxcarrera';
        $this->layout->view('registros/modulosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($carr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($carr_id_enc === '') ? redirect('registros/modulosxcarrera') : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($carr_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/modulosxcarrera';
        $this->layout->view('registros/modulosxcarrera_form', $data);
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
        $data['btn_cancelar']   = 'registros/modulosxcarrera';
        $this->layout->view('registros/modulosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($carr_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/modulosxcarrera') : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->modulosxcarrera_model->deleteModulosxcarreraByID($carr_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/modulosxcarrera');
    }

    public function guardar($modu_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $modu_id = ($carr_id_enc === '') ? '' : str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('modu_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('modu_descripcion', 'Nombre Modulosxcarrera', 'required|trim');
        $this->form_validation->set_rules('carr_id', 'Carrera', 'required');
        $this->form_validation->set_rules('niap_id', 'Nivel de aprendizaje', 'required');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($modu_id === '')?'nuevo':'editar';
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID(($modu_id === '')?0:$modu_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/modulosxcarrera';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/modulosxcarrera_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_modu = array(
                'modu_codigo'               => $datapost['modu_codigo'],
                'modu_descripcion'          => $datapost['modu_descripcion'],
                'carr_id'                   => $datapost['carr_id'],
                'niap_id'                   => $datapost['niap_id']
            );
            $data_response = ($modu_id === '') ? $this->modulosxcarrera_model->insertModulosxcarrera($data_modu) : $this->modulosxcarrera_model->updateModulosxcarrera($data_modu, $carr_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($modu_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/modulosxcarrera');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/modulosxcarrera_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}































































































































