<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Tipodireccion extends CI_Controller {
    private static $header_title  = 'Tipo de Dirección';
    private static $header_icon  = ICON_SETTINGS;

    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/tipodireccion_model');
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/tipodireccion'); 
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
        $propio['ruta_base'] = 'seguridad/tipodireccion/index';
        $propio['filas_totales'] = $this->tipodireccion_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'seguridad/tipodireccion/nuevo';
        $this->layout->view('seguridad/tipodireccion_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/tipodireccion/nuevo';

        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/tipodireccion');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/tipodireccion') : '';
            $data['data_tdir']  = $this->tipodireccion_model->getTipodireccionAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/tipodireccion_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($tdir_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        ($tdir_id_enc === '') ? redirect('seguridad/tipodireccion') : '';
        $tdir_id = str_decrypt($tdir_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionByID($tdir_id);
        $data['btn_editar']     = 'seguridad/tipodireccion/editar/'.$tdir_id_enc;
        $data['btn_regresar']   = 'seguridad/tipodireccion';
        $this->layout->view('seguridad/tipodireccion_form', $data);
        $this->load->view('notificacion');
    }



    public function editar($tdir_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        ($tdir_id_enc === '') ? redirect('seguridad/tipodireccion') : '';
        $tdir_id = str_decrypt($tdir_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionByID($tdir_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/tipodireccion';
        $this->layout->view('seguridad/tipodireccion_form', $data);
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
        $data['btn_cancelar']   = 'seguridad/tipodireccion';
        $this->layout->view('seguridad/tipodireccion_form', $data);
        $this->load->view('notificacion');
    }



    public function eliminar($tdir_id_enc = ''){       

        ($tdir_id_enc === '') ? redirect('seguridad/tipodireccion') : '';
        $tdir_id = str_decrypt($tdir_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->tipodireccion_model->deleteTipodireccionByID($tdir_id);
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
        redirect('seguridad/tipodireccion');
    }



    public function guardar($tdir_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;


        $tdir_id = ($tdir_id_enc === '') ? '' : str_decrypt($tdir_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('tdir_descripcion', 'Descripción', 'required|trim');
        $this->form_validation->set_rules('tdir_abreviatura', 'Abreviatura', 'required|trim');
        
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($tdir_id === '')?'nuevo':'editar';
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionByID($tdir_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/tipodireccion';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/tipodireccion_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_tdir = array(
                'tdir_descripcion'           => $datapost['tdir_descripcion'],
                'tdir_abreviatura'           => $datapost['tdir_abreviatura']
            );
            $data_response = ($tdir_id === '') ? $this->tipodireccion_model->insertTipodireccion($data_tdir) : $this->tipodireccion_model->updateTipodireccion($data_tdir, $tdir_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($tdir_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('seguridad/tipodireccion');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/tipodireccion_form', $data);
            }
        }
        $this->load->view('notificacion');

    }





}















