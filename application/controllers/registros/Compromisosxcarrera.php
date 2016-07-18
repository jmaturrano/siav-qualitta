<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Compromisosxcarrera extends CI_Controller {
    private static $header_title  = 'Compromisos por Carrera';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/compromisoscarrera_model');
        $this->load->model('registros/compromisosxcarrera_model');
        $this->load->model('registros/carrera_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/compromisosxcarrera'); 
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
        $propio['ruta_base'] = 'registros/compromisosxcarrera/index';
        $propio['filas_totales'] = $this->compromisosxcarrera_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_cxca']      = $this->compromisosxcarrera_model->getCompromisosxcarreraAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'registros/compromisosxcarrera/nuevo';
        $this->layout->view('registros/compromisosxcarrera_index', $data);
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
        $data['btn_nuevo']      = 'registros/compromisosxcarrera/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/compromisosxcarrera');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/compromisosxcarrera') : '';
            $data['data_cxca']  = $this->compromisosxcarrera_model->getCompromisosxcarreraAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/compromisosxcarrera_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $cxca_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($cxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($cxca_id_enc === '') ? redirect('registros/compromisosxcarrera') : '';
        $cxca_id = str_decrypt($cxca_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_cxca']      = $this->compromisosxcarrera_model->getCompromisosxcarreraByID($cxca_id);
        $data['data_ccar']      = $this->compromisoscarrera_model->getCompromisoscarreraAll();
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['btn_editar']     = 'registros/compromisosxcarrera/editar/'.$cxca_id_enc;
        $data['btn_regresar']   = 'registros/compromisosxcarrera';
        $this->layout->view('registros/compromisosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $cxca_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($cxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($cxca_id_enc === '') ? redirect('registros/compromisosxcarrera') : '';
        $cxca_id = str_decrypt($cxca_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_cxca']      = $this->compromisosxcarrera_model->getCompromisosxcarreraByID($cxca_id);
        $data['data_ccar']      = $this->compromisoscarrera_model->getCompromisoscarreraAll();
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/compromisosxcarrera';
        $this->layout->view('registros/compromisosxcarrera_form', $data);
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
        $data['btn_cancelar']   = 'registros/compromisosxcarrera';
        $data['data_ccar']      = $this->compromisoscarrera_model->getCompromisoscarreraAll();
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $this->layout->view('registros/compromisosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $cxca_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($cxca_id_enc = ''){
        ($cxca_id_enc === '') ? redirect('registros/compromisosxcarrera') : '';
        $cxca_id = str_decrypt($cxca_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->compromisosxcarrera_model->deleteCompromisosxcarreraByID($cxca_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/compromisosxcarrera');
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $cxca_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($cxca_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $cxca_id = ($cxca_id_enc === '') ? '' : str_decrypt($cxca_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('carr_id', 'Carrera', 'required|trim');
        $this->form_validation->set_rules('ccar_id', 'Compromiso', 'required|trim');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($cxca_id === '')?'nuevo':'editar';
        $data['data_cxca']      = $this->compromisosxcarrera_model->getCompromisosxcarreraByID($cxca_id);
        $data['data_ccar']      = $this->compromisoscarrera_model->getCompromisoscarreraAll();
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/compromisosxcarrera';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/compromisosxcarrera_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_cxca = array(
                'carr_id'  => $datapost['carr_id'],
                'ccar_id'  => $datapost['ccar_id']
            );
            $data_response = ($cxca_id === '') ? $this->compromisosxcarrera_model->insertCompromisosxcarrera($data_cxca) : $this->compromisosxcarrera_model->updateCompromisosxcarrera($data_cxca, $cxca_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($cxca_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/compromisosxcarrera');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/compromisosxcarrera_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}