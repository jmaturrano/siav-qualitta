<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Conceptosmatricula extends CI_Controller {
    private static $header_title  = 'Conceptos Matrícula';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/conceptosmatricula_model');
        $this->load->model('registros/listaprecio_model');
    }

     /**
      * Función inicial  
      *
      * Carga los datos del sistema y sesión
      *
      *
      * @return void
      */
     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/conceptosmatricula'); 
    }

     /**
      * Vista principal  
      *
      * Carga la lista de matriculaes
      *
      * @param int $ofsset parametro de seleccion de paginacion
      *
      * @return void
      */
    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'registros/conceptosmatricula/index';
        $propio['filas_totales'] = $this->conceptosmatricula_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_cmat']      = $this->conceptosmatricula_model->getConceptosMatriculaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'registros/conceptosmatricula/nuevo';
        $this->layout->view('registros/conceptosmatricula_index', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista resultado de búsqueda  
      *
      * Carga la lista de Precios
      *
      *
      * @return void
      */
    public function buscar(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['btn_nuevo']      = 'registros/conceptosmatricula/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/conceptosmatricula');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/conceptosmatricula') : '';
            $data['data_cmat']  = $this->conceptosmatricula_model->getConceptosMatriculaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/conceptosmatricula_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $cmat_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($cmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($cmat_id_enc === '') ? redirect('registros/conceptosmatricula') : '';
        $cmat_id = str_decrypt($cmat_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_cmat']      = $this->conceptosmatricula_model->getConceptosMatriculaByID($cmat_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['btn_editar']     = 'registros/conceptosmatricula/editar/'.$cmat_id_enc;
        $data['btn_regresar']   = 'registros/conceptosmatricula';
        $this->layout->view('registros/conceptosmatricula_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $cmat_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($cmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($cmat_id_enc === '') ? redirect('registros/conceptosmatricula') : '';
        $cmat_id = str_decrypt($cmat_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_cmat']      = $this->conceptosmatricula_model->getConceptosMatriculaByID($cmat_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/conceptosmatricula';
        $this->layout->view('registros/conceptosmatricula_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de registro  
      *
      * Muestra el formulario de registro    
      *
      * @return void
      */
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
        $data['btn_cancelar']   = 'registros/conceptosmatricula';
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $this->layout->view('registros/conceptosmatricula_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $cmat_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($cmat_id_enc = ''){
        ($cmat_id_enc === '') ? redirect('registros/conceptosmatricula') : '';
        $cmat_id = str_decrypt($cmat_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->conceptosmatricula_model->deleteConceptosMatriculaByID($cmat_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/conceptosmatricula');
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $cmat_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($cmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $cmat_id = ($cmat_id_enc === '') ? '' : str_decrypt($cmat_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('lipe_id', 'Lista de precios', 'required');
        $this->form_validation->set_rules('cmat_descripcion', 'Descripción', 'required|trim');
        $this->form_validation->set_rules('cmat_costo', 'Costo', 'required|trim|numeric');
        $this->form_validation->set_rules('cmat_obligatorio', 'Obligatorio', 'required|trim');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($cmat_id === '')?'nuevo':'editar';
        $data['data_cmat']      = $this->conceptosmatricula_model->getConceptosMatriculaByID($cmat_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/conceptosmatricula';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/conceptosmatricula_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_cmat = array(
                'lipe_id'           => $datapost['lipe_id'],
                'cmat_descripcion'  => $datapost['cmat_descripcion'],
                'cmat_costo'        => $datapost['cmat_costo'],
                'cmat_obligatorio'  => ($datapost['cmat_obligatorio'] === '0' ? 'N' : 'S')
            );
            $data_response = ($cmat_id === '') ? $this->conceptosmatricula_model->insertConceptosMatricula($data_cmat) : $this->conceptosmatricula_model->updateConceptosMatricula($data_cmat, $cmat_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($cmat_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/conceptosmatricula');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/conceptosmatricula_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}