<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipoaeronave extends CI_Controller {
    private static $header_title  = 'Marca Aeronave';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('operaciones/tipoaeronave_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'operaciones/tipoaeronave');
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
        $propio['ruta_base'] = 'operaciones/tipoaeronave/index';
        $propio['filas_totales'] = $this->tipoaeronave_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'operaciones/tipoaeronave/nuevo';
        $this->layout->view('operaciones/tipoaeronave_index', $data);
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
        $data['btn_nuevo']      = 'operaciones/tipoaeronave/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('operaciones/tipoaeronave');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('operaciones/tipoaeronave') : '';
            $data['data_tiae']  = $this->tipoaeronave_model->getTipoaeronaveAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('operaciones/tipoaeronave_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * otip_id_enc id tipo aeronave encriptado
    * @return void
    */

    public function ver($otip_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($otip_id_enc === '') ? redirect('operaciones/tipoaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveByID($otip_id);
        $data['btn_editar']     = 'operaciones/tipoaeronave/editar/'.$otip_id_enc;
        $data['btn_regresar']   = 'operaciones/tipoaeronave';
        $this->layout->view('operaciones/tipoaeronave_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * otip_id_enc id tipo aeronave encriptado
    * @return void
    */

    public function editar($otip_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($otip_id_enc === '') ? redirect('operaciones/tipoaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveByID($otip_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/tipoaeronave';
        $this->layout->view('operaciones/tipoaeronave_form', $data);
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
        $data['btn_cancelar']   = 'operaciones/tipoaeronave';
        $this->layout->view('operaciones/tipoaeronave_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * otip_id_enc id tipo aeronave encriptado
    * @return void
    */

    public function eliminar($otip_id_enc = ''){
        ($otip_id_enc === '') ? redirect('operaciones/tipoaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->tipoaeronave_model->deleteTipoaeronaveByID($otip_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('operaciones/tipoaeronave');
    }

    /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * moae_id_enc id tipo aeronave encriptado
    * @return void
    */

    public function guardar($tiae_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $tiae_id = ($tiae_id_enc === '') ? '' : str_decrypt($tiae_id_enc, KEY_ENCRYPT);
        if ($tiae_id === '') {
            $this->form_validation->set_rules('tiae_descripcion','Descripción','required|trim|is_unique[tipo_aeronave.tiae_descripcion]');
        }else{
            $this->form_validation->set_rules('tiae_descripcion','Descripción','required|trim');
        }
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($tiae_id === '')?'nuevo':'editar';
        $data['data_tiae']      = $this->tipoaeronave_model->getTipoaeronaveByID($tiae_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/tipoaeronave';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('operaciones/tipoaeronave_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_tiae = array(
                'tiae_descripcion'           => $datapost['tiae_descripcion'],
                            );
            $data_response = ($tiae_id === '') ? $this->tipoaeronave_model->insertTipoaeronave($data_tiae) : $this->tipoaeronave_model->updateTipoaeronave($data_tiae, $tiae_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($tiae_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('operaciones/tipoaeronave');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('operaciones/tipoaeronave_form', $data);
            }
        }
        $this->load->view('notificacion');
    }

}































