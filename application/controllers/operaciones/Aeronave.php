<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aeronave extends CI_Controller {

    private static $header_title  = 'Aeronave';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
       parent :: __construct();
       $this->initData();  
       $this->load->model('operaciones/aeronave_model');
       $this->load->model('operaciones/tipoaeronave_model');
       $this->load->model('operaciones/modeloaeronave_model');
    }

    /**
    * Funcion privilegios
    * Maneja los privilegios del sistema
    * @return void
    */

     public function initData(){

        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'operaciones/aeronave'); 
    }

     /**
    * Funcion Inicio 
    * Maneja la estructura principal
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
        $propio['ruta_base'] = 'operaciones/aeronave/index';
        $propio['filas_totales'] = $this->aeronave_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_aero']      = $this->aeronave_model->getAeronaveAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'operaciones/aeronave/nuevo';
        $this->layout->view('operaciones/aeronave_index', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion buscar
    * Maneja la busqueda de datos
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
        $data['btn_nuevo']      = 'operaciones/aeronave/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('operaciones/aeronave');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('operaciones/aeronave') : '';
            $data['data_aero']  = $this->aeronave_model->getAeronaveAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('operaciones/aeronave_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * aero_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function ver($aero_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($aero_id_enc === '') ? redirect('operaciones/aeronave') : '';
        $aero_id = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_aero']      = $this->aeronave_model->getAeronaveByID($aero_id);
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveAll();
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveAll();
        $data['btn_editar']     = 'operaciones/aeronave/editar/'.$aero_id_enc;
        $data['btn_regresar']   = 'operaciones/aeronave';
        $this->layout->view('operaciones/aeronave_form', $data);
        $this->load->view('notificacion');
    }

     /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * aero_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function editar($aero_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($aero_id_enc === '') ? redirect('operaciones/aeronave') : '';
        $aero_id = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_aero']      = $this->aeronave_model->getAeronaveByID($aero_id);
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveAll();
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/aeronave';
        $this->layout->view('operaciones/aeronave_form', $data);
        $this->load->view('notificacion');
    }

     /**
    * Funcion Nuevo
    * Maneja opcion nuevo para ingresar nuevos datos
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
        $data['btn_cancelar']   = 'operaciones/aeronave';
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveAll();
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveAll();
        $this->layout->view('operaciones/aeronave_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * aero_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function eliminar($aero_id_enc = ''){       
        ($aero_id_enc === '') ? redirect('operaciones/aeronave') : '';
        $aero_id = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->aeronave_model->deleteAeronaveByID($aero_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
            /*
            $this->session->set_flashdata('message_type', RTYPE_SUCCESS);
            $this->session->set_flashdata('message_title', RTITLE_SUCCESS);
            $this->session->set_flashdata('message_response', RMESSAGE_DELETE);
            */
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            /*
            $this->session->set_flashdata('message_type', RTYPE_ERROR);
            $this->session->set_flashdata('message_title', RTITLE_ERROR);
            $this->session->set_flashdata('message_response', RMESSAGE_ERROR);
            */
        }
        redirect('operaciones/aeronave');
    }

    /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * aero_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function guardar($aero_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $aero_id = ($aero_id_enc === '') ? '' : str_decrypt($aero_id_enc, KEY_ENCRYPT);
        
        $this->form_validation->set_rules('tiae_id','Marca','required|trim');
        $this->form_validation->set_rules('moae_id','Modelo','required|trim');
        $this->form_validation->set_rules('aero_matricula','Matrícula','required|trim');
        $this->form_validation->set_rules('aero_fecha_fabricacion','Fecha de fabricación','trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($aero_id === '')?'nuevo':'editar';
        $data['data_aero']      = $this->aeronave_model->getAeronaveByID($aero_id);
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveAll();
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/aeronave';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('operaciones/aeronave_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_aero = array(
                'tiae_id'           => $datapost['tiae_id'],
                'moae_id'           => $datapost['moae_id'],
                'aero_matricula'    => $datapost['aero_matricula'],
                'aero_fecha_fabricacion'      => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['aero_fecha_fabricacion'])))
                            );
            $data_response = ($aero_id === '') ? $this->aeronave_model->insertAeronave($data_aero) : $this->aeronave_model->updateAeronave($data_aero, $aero_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($aero_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('operaciones/aeronave');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('operaciones/aeronave_form', $data);
            }
        }
        $this->load->view('notificacion');
    }
}































