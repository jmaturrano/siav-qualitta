<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Matricula extends CI_Controller {
    private static $header_title  = 'Matrícula';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/matricula_model');
        $this->load->model('registros/alumno_model');
        $this->load->model('registros/compromisoscarrera_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/matricula'); 
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
        $propio['ruta_base'] = 'servicios/matricula/index';
        $propio['filas_totales'] = $this->matricula_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_matr']      = $this->matricula_model->getMatriculaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'servicios/matricula/nuevo';
        $this->layout->view('servicios/matricula_index', $data);
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
        $data['btn_nuevo']      = 'servicios/matricula/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('servicios/matricula');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('servicios/matricula') : '';
            $data['data_matr']  = $this->matricula_model->getMatriculaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('servicios/matricula_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $matr_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['btn_editar']     = 'servicios/matricula/editar/'.$matr_id_enc;
        $data['btn_regresar']   = 'servicios/matricula';
        $this->layout->view('servicios/matricula_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $matr_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/matricula';
        $this->layout->view('servicios/matricula_form', $data);
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
        $data['btn_cancelar']   = 'servicios/matricula';
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $this->layout->view('servicios/matricula_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $matr_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($matr_id_enc = ''){
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->matricula_model->deleteMatriculaByID($matr_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('servicios/matricula');
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $matr_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $matr_id = ($matr_id_enc === '') ? '' : str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('carr_id', 'Carrera', 'required|trim');
        $this->form_validation->set_rules('alum_id', 'Alumno', 'required|trim');
        $this->form_validation->set_rules('matr_codigo', 'Código matrícula', 'required|trim');
        $this->form_validation->set_rules('matr_fecha_proceso', 'Fecha proceso', 'required|trim');
        $this->form_validation->set_rules('matr_costoreal', 'Costo Real', 'required|numeric');
        $this->form_validation->set_rules('matr_costofinal', 'Costo Final', 'required|numeric');
        $this->form_validation->set_rules('matr_observacion', 'Observaciones', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($matr_id === '')?'nuevo':'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/matricula';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/matricula_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_matr = array(
                'carr_id'             => $datapost['carr_id'],
                'alum_id'             => $datapost['alum_id'],
                'matr_codigo'         => $datapost['matr_codigo'],
                'matr_fecha_proceso'  => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['matr_fecha_proceso']))),
                'matr_costoreal'      => $datapost['matr_costoreal'],
                'matr_costofinal'     => $datapost['matr_costofinal'],
                'matr_observacion'    => $datapost['matr_observacion']
            );
            $data_response = ($matr_id === '') ? $this->matricula_model->insertMatricula($data_matr) : $this->matricula_model->updateMatricula($data_matr, $matr_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($matr_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('servicios/matricula');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/matricula_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}