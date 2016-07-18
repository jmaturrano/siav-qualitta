<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Requisitosxcarrera extends CI_Controller {
    private static $header_title  = 'Requisitos por Carrera';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/requisitosxcarrera_model');
        $this->load->model('registros/carrera_model');
        $this->load->model('registros/requisitoscarrera_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/requisitosxcarrera'); 
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
        $propio['ruta_base'] = 'registros/requisitosxcarrera/index';
        $propio['filas_totales'] = $this->requisitosxcarrera_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_rxca']      = $this->requisitosxcarrera_model->getRequisitosxcarreraAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'registros/requisitosxcarrera/nuevo';
        $this->layout->view('registros/requisitosxcarrera_index', $data);
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
        $data['btn_nuevo']      = 'registros/requisitosxcarrera/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/requisitosxcarrera');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/requisitosxcarrera') : '';
            $data['data_rxca']  = $this->requisitosxcarrera_model->getRequisitosxcarreraAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/requisitosxcarrera_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $rxca_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($rxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rxca_id_enc === '') ? redirect('registros/requisitosxcarrera') : '';
        $rxca_id = str_decrypt($rxca_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_rxca']      = $this->requisitosxcarrera_model->getRequisitosxcarreraByID($rxca_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_rcar']      = $this->requisitoscarrera_model->getRequisitoscarreraAll();
        $data['btn_editar']     = 'registros/requisitosxcarrera/editar/'.$rxca_id_enc;
        $data['btn_regresar']   = 'registros/requisitosxcarrera';
        $this->layout->view('registros/requisitosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $rxca_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($rxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($rxca_id_enc === '') ? redirect('registros/requisitosxcarrera') : '';
        $rxca_id = str_decrypt($rxca_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_rxca']      = $this->requisitosxcarrera_model->getRequisitosxcarreraByID($rxca_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_rcar']      = $this->requisitoscarrera_model->getRequisitoscarreraAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/requisitosxcarrera';
        $this->layout->view('registros/requisitosxcarrera_form', $data);
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
        $data['btn_cancelar']   = 'registros/requisitosxcarrera';
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_rcar']      = $this->requisitoscarrera_model->getRequisitoscarreraAll();
        $this->layout->view('registros/requisitosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $rxca_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($rxca_id_enc = ''){
        ($rxca_id_enc === '') ? redirect('registros/requisitosxcarrera') : '';
        $rxca_id = str_decrypt($rxca_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->requisitosxcarrera_model->deleteRequisitosxcarreraByID($rxca_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/requisitosxcarrera');
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $rxca_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($rxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $rxca_id = ($rxca_id_enc === '') ? '' : str_decrypt($rxca_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('carr_id', 'Carrera', 'required|trim');
        $this->form_validation->set_rules('rcar_id', 'Requisito', 'required|trim');
        $this->form_validation->set_rules('rxca_obligatorio', 'Obligatorio', 'required|trim');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($rxca_id === '')?'nuevo':'editar';
        $data['data_rxca']      = $this->requisitosxcarrera_model->getRequisitosxcarreraByID($rxca_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_rcar']      = $this->requisitoscarrera_model->getRequisitoscarreraAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/requisitosxcarrera';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/requisitosxcarrera_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_rxca = array(
                'carr_id'           => $datapost['carr_id'],
                'rcar_id'           => $datapost['rcar_id'],
                'rxca_obligatorio'  => ($datapost['rxca_obligatorio'] == '1' ? 'S' : 'N')
            );
            $data_response = ($rxca_id === '') ? $this->requisitosxcarrera_model->insertRequisitosxcarrera($data_rxca) : $this->requisitosxcarrera_model->updateRequisitosxcarrera($data_rxca, $rxca_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($rxca_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/requisitosxcarrera');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/requisitosxcarrera_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}