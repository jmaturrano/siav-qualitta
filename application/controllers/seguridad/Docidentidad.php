<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Docidentidad extends CI_Controller {
    private static $header_title  = 'Documentos de Identidad';
    private static $header_icon  = ICON_SETTINGS;

    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/docidentidad_model');
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/docidentidad');  
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
        $propio['ruta_base'] = 'seguridad/docidentidad/index';
        $propio['filas_totales'] = $this->docidentidad_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'seguridad/docidentidad/nuevo';
        $this->layout->view('seguridad/docidentidad_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/docidentidad/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/docidentidad');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/docidentidad') : '';
            $data['data_doid']  = $this->docidentidad_model->getDocidentidadAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/docidentidad_index', $data);
            $this->load->view('notificacion');
        }
    }


    public function ver($dide_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($dide_id_enc === '') ? redirect('seguridad/docidentidad') : '';
        $dide_id = str_decrypt($dide_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadByID($dide_id);
        $data['btn_editar']     = 'seguridad/docidentidad/editar/'.$dide_id_enc;
        $data['btn_regresar']   = 'seguridad/docidentidad';
        $this->layout->view('seguridad/docidentidad_form', $data);
        $this->load->view('notificacion');
    }



    public function editar($dide_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($dide_id_enc === '') ? redirect('seguridad/docidentidad') : '';
        $dide_id = str_decrypt($dide_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadByID($dide_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/docidentidad';
        $this->layout->view('seguridad/docidentidad_form', $data);
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
        $data['btn_cancelar']   = 'seguridad/docidentidad';
        $this->layout->view('seguridad/docidentidad_form', $data);
        $this->load->view('notificacion');
    }



    public function eliminar($dide_id_enc = ''){
        ($dide_id_enc === '') ? redirect('seguridad/docidentidad') : '';
        $dide_id = str_decrypt($dide_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->docidentidad_model->deleteDocidentidadByID($dide_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/docidentidad');

    }



    public function guardar($dide_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $dide_id = ($dide_id_enc === '') ? '' : str_decrypt($dide_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('dide_descripcion', 'Descripción', 'required');
        $this->form_validation->set_rules('dide_caracteres', 'Cant. Caracteres', 'required|integer');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($dide_id === '')?'nuevo':'editar';
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadByID($dide_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/docidentidad';
        $this->load->library('layout');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/docidentidad_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_doid = array(
                'dide_descripcion'        => $datapost['dide_descripcion'],
                'dide_caracteres'         => $datapost['dide_caracteres']
            );
            $data_response = ($dide_id === '') ? $this->docidentidad_model->insertDocidentidad($data_doid) : $this->docidentidad_model->updateDocidentidad($data_doid, $dide_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($dide_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('seguridad/docidentidad');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/docidentidad_form', $data);

            }
        }
        $this->load->view('notificacion');
    }































}















