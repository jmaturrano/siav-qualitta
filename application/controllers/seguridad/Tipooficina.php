<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Tipooficina extends CI_Controller {
    private static $header_title  = 'Tipo Oficina';
    private static $header_icon  = ICON_SETTINGS;

    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/tipooficina_model');
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/tipooficina'); 
    }

    public function index($offset = 0) {

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('pagination');
        $propio['ruta_base'] = 'seguridad/tipooficina/index';
        $propio['filas_totales'] = $this->tipooficina_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['data_tiof']      = $this->tipooficina_model->getTipooficinaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'seguridad/tipooficina/nuevo';
        $this->layout->view('seguridad/tipooficina_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/tipooficina/nuevo';

        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/tipooficina');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/tipooficina') : '';
            $data['data_tiof']  = $this->tipooficina_model->getTipooficinaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/tipooficina_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($otip_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        ($otip_id_enc === '') ? redirect('seguridad/tipooficina') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_tiof']      = $this->tipooficina_model->getTipooficinaByID($otip_id);
        $data['btn_editar']     = 'seguridad/tipooficina/editar/'.$otip_id_enc;
        $data['btn_regresar']   = 'seguridad/tipooficina';
        $this->layout->view('seguridad/tipooficina_form', $data);
        $this->load->view('notificacion');
    }



    public function editar($otip_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        ($otip_id_enc === '') ? redirect('seguridad/tipooficina') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_tiof']      = $this->tipooficina_model->getTipooficinaByID($otip_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/tipooficina';
        $this->layout->view('seguridad/tipooficina_form', $data);
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
        $data['btn_cancelar']   = 'seguridad/tipooficina';
        $this->layout->view('seguridad/tipooficina_form', $data);
        $this->load->view('notificacion');
    }



    public function eliminar($otip_id_enc = ''){       

        ($otip_id_enc === '') ? redirect('seguridad/tipooficina') : '';
        $otip_id = str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->tipooficina_model->deleteTipooficinaByID($otip_id);
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
        redirect('seguridad/tipooficina');
    }



    public function guardar($otip_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        $otip_id = ($otip_id_enc === '') ? '' : str_decrypt($otip_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('otip_nombre', 'Descripción', 'required');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($otip_id === '')?'nuevo':'editar';
        $data['data_tiof']      = $this->tipooficina_model->getTipooficinaByID($otip_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/tipooficina';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/tipooficina_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_tipof = array(
                'otip_nombre'           => $datapost['otip_nombre']
            );
            $data_response = ($otip_id === '') ? $this->tipooficina_model->insertTipooficina($data_tipof) : $this->tipooficina_model->updateTipooficina($data_tipof, $otip_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($otip_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('seguridad/tipooficina');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/tipooficina_form', $data);
            }
        }
        $this->load->view('notificacion');

    }





}















