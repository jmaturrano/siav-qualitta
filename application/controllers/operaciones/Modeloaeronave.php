<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modeloaeronave extends CI_Controller {

    private static $header_title  = 'Modelo Aeronave';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
       $this->initData();  
       $this->load->model('operaciones/modeloaeronave_model');
        //$this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'operaciones/modeloaeronave'); 
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
        $propio['ruta_base'] = 'operaciones/modeloaeronave/index';
        $propio['filas_totales'] = $this->modeloaeronave_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'operaciones/modeloaeronave/nuevo';
        $this->layout->view('operaciones/modeloaeronave_index', $data);
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
        $data['btn_nuevo']      = 'operaciones/modeloaeronave/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('operaciones/modeloaeronave');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('operaciones/modeloaeronave') : '';
            $data['data_moae']  = $this->modeloaeronave_model->getModeloaeronaveAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('operaciones/modeloaeronave_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * otip_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function ver($otip_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($otip_id_enc === '') ? redirect('operaciones/modeloaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveByID($otip_id);
        $data['btn_editar']     = 'operaciones/modeloaeronave/editar/'.$otip_id_enc;
        $data['btn_regresar']   = 'operaciones/modeloaeronave';
        $this->layout->view('operaciones/modeloaeronave_form', $data);
        $this->load->view('notificacion');
    }

     /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * otip_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function editar($otip_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($otip_id_enc === '') ? redirect('operaciones/modeloaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveByID($otip_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/modeloaeronave';
        $this->layout->view('operaciones/modeloaeronave_form', $data);
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
        $data['btn_cancelar']   = 'operaciones/modeloaeronave';
        $this->layout->view('operaciones/modeloaeronave_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * otip_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function eliminar($otip_id_enc = ''){       
        ($otip_id_enc === '') ? redirect('operaciones/modeloaeronave') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->modeloaeronave_model->deleteModeloaeronaveByID($otip_id);
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
        redirect('operaciones/modeloaeronave');
    }

    /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * moae_id_enc id modelo aeronave encriptado
    * @return void
    */

    public function guardar($moae_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $moae_id = ($moae_id_enc === '') ? '' : str_decrypt($moae_id_enc, KEY_ENCRYPT);
         if ($moae_id === '') {
        $this->form_validation->set_rules('moae_descripcion','Descripción','required|trim|is_unique[modelo_aeronave.moae_descripcion]');
        }
         else 
        {
            $this->form_validation->set_rules('moae_descripcion','Descripción','required|trim');
        }
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($moae_id === '')?'nuevo':'editar';
        $data['data_moae']      = $this->modeloaeronave_model->getModeloaeronaveByID($moae_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'operaciones/modeloaeronave';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('operaciones/modeloaeronave_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_moae = array(
                'moae_descripcion'           => $datapost['moae_descripcion'],
                            );
            $data_response = ($moae_id === '') ? $this->modeloaeronave_model->insertModeloaeronave($data_moae) : $this->modeloaeronave_model->updateModeloaeronave($data_moae, $moae_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($moae_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('operaciones/modeloaeronave');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('operaciones/modeloaeronave_form', $data);
            }
        }
        $this->load->view('notificacion');
    }
}































