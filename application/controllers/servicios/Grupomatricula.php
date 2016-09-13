<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupomatricula extends CI_Controller {
    private static $header_title  = 'Grupos de inicio';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/grupomatricula_model');
        $this->load->model('registros/carrera_model');
        $this->load->model('registros/modalidad_model');
        date_default_timezone_set('America/Lima');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/grupomatricula'); 
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
        $propio['ruta_base'] = 'servicios/grupomatricula/index';
        $propio['filas_totales'] = $this->grupomatricula_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);

        $data['btn_nuevo']      = 'servicios/grupomatricula/nuevo';
        $this->layout->view('servicios/grupomatricula_index', $data);
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
        $data['btn_nuevo']      = 'servicios/grupomatricula/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('servicios/grupomatricula');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('servicios/grupomatricula') : '';
            $data['data_gmat']  = $this->grupomatricula_model->getGrupomatriculaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('servicios/grupomatricula_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $gmat_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($gmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($gmat_id_enc === '') ? redirect('servicios/grupomatricula') : '';
        $gmat_id = str_decrypt($gmat_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByID($gmat_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();

        $data['btn_editar']     = 'servicios/grupomatricula/editar/'.$gmat_id_enc;
        $data['btn_regresar']   = 'servicios/grupomatricula';
        $this->layout->view('servicios/grupomatricula_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $gmat_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($gmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($gmat_id_enc === '') ? redirect('servicios/grupomatricula') : '';
        $gmat_id = str_decrypt($gmat_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByID($gmat_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/grupomatricula';
        $this->layout->view('servicios/grupomatricula_form', $data);
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
        $data['btn_cancelar']   = 'servicios/grupomatricula';
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();

        $this->layout->view('servicios/grupomatricula_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $gmat_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($gmat_id_enc = ''){
        ($gmat_id_enc === '') ? redirect('servicios/grupomatricula') : '';
        $gmat_id = str_decrypt($gmat_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->grupomatricula_model->deleteGrupomatriculaByID($gmat_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('servicios/grupomatricula');
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $gmat_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($gmat_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $gmat_id = ($gmat_id_enc === '') ? '' : str_decrypt($gmat_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('moda_id', 'Modalidad', 'required|trim');
        $this->form_validation->set_rules('carr_id', 'Carrera', 'required|trim');
        $this->form_validation->set_rules('gmat_fecha_inicio', 'Fecha de inicio', 'required|trim');
        $this->form_validation->set_rules('gmat_observacion', 'Observaciones', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($gmat_id === '')?'nuevo':'editar';
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByID($gmat_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/grupomatricula';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/grupomatricula_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_gmat = array(
                'moda_id'             => $datapost['moda_id'],
                'carr_id'             => $datapost['carr_id'],
                'gmat_fecha_inicio'   => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['gmat_fecha_inicio']))),
                'gmat_observacion'    => $datapost['gmat_observacion']
            );

            $data_response = ($gmat_id === '') ? $this->grupomatricula_model->insertGrupomatricula($data_gmat) : $this->grupomatricula_model->updateGrupomatricula($data_gmat, $gmat_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($gmat_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                if($gmat_id === ''){
                  $gmat_id = $data_response;
                }
                redirect('servicios/grupomatricula/ver/'.str_encrypt($gmat_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/grupomatricula_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}