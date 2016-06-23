<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Carrera extends CI_Controller {

    private static $header_title  = 'Carreras';

    private static $header_icon  = ICON_GROUP;

    private static $OFICINAS = array();

    private static $ROLES = array();

    private static $PRIVILEGIOS = array();

    private static $PERMISOS = array();



    public function __construct() {

        parent :: __construct();

        $this->initData();

        $this->load->model('registros/carrera_model');

    }



    public function initData(){

        self::$OFICINAS     = revisar_oficinas($this);

        self::$ROLES        = revisar_roles($this, 0);

        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  

        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/carrera');  

    }



    public function index($offset = 0) {

        $data['OFICINAS']           = self::$OFICINAS;

        $data['ROLES']              = self::$ROLES;

        $data['PRIVILEGIOS']        = self::$PRIVILEGIOS;

        $data['PERMISOS']           = self::$PERMISOS;

        $this->load->library('layout');

        $data['header_title']       = self::$header_title;

        $data['header_icon']        = self::$header_icon;

        $this->load->library('pagination');

        $propio['ruta_base']        = 'registros/carrera/index';

        $propio['filas_totales']    = $this->carrera_model->contar_estructuras_todos();

        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['data_carr']          = $this->carrera_model->getCarreraAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);

        $data['btn_nuevo']          = 'registros/carrera/nuevo';

        $data['offset'] = $offset;

        $this->layout->view('registros/carrera_index', $data);

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

        $data['btn_nuevo']      = 'registros/carrera/nuevo';

        $this->form_validation->set_rules('q', 'Buscar', 'trim');

        if ($this->form_validation->run() === FALSE) {

            redirect('registros/carrera');

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());

            ($datapost['q'] === '') ? redirect('registros/carrera') : '';

            $data['data_carr']  = $this->carrera_model->getCarreraAll($datapost['q']);

            $data['q']          = $datapost['q'];

            $this->layout->view('registros/carrera_index', $data);

            $this->load->view('notificacion');

        }

    }



    public function ver($carr_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';

        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'ver';

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);

        $data['btn_editar']     = 'registros/carrera/editar/'.$carr_id_enc;

        $data['btn_regresar']   = 'registros/carrera';

        $this->layout->view('registros/carrera_form', $data);

        $this->load->view('notificacion');

    }



    public function editar($carr_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';

        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'editar';

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/carrera';

        $this->layout->view('registros/carrera_form', $data);

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

        $data['btn_cancelar']   = 'registros/carrera';

        $this->layout->view('registros/carrera_form', $data);

        $this->load->view('notificacion');

    }



    public function eliminar($carr_id_enc = ''){

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';

        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->carrera_model->deleteCarreraByID($carr_id);

        if($data_delete){

            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);

            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);

        }else{

            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);

        }

        redirect('registros/carrera');

    }



    public function guardar($carr_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;

        $carr_id = ($carr_id_enc === '') ? '' : str_decrypt($carr_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('carr_codigo', 'Código', 'required|trim');

        $this->form_validation->set_rules('carr_descripcion', 'Nombre', 'required|trim');



        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = ($carr_id === '')?'nuevo':'editar';

        $data['data_carr']      = $this->carrera_model->getCarreraByID(($carr_id === '')?0:$carr_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/carrera';



        $this->load->library('layout');

        if ($this->form_validation->run() === FALSE) {

            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

            $this->session->set_flashdata('mensaje', primer_error_validation());

            $this->layout->view('registros/carrera_form', $data);

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());



            $data_carr = array(

                'carr_codigo'               => $datapost['carr_codigo'],

                'carr_descripcion'          => $datapost['carr_descripcion']

            );



            $data_response = ($carr_id === '') ? $this->carrera_model->insertCarrera($data_carr) : $this->carrera_model->updateCarrera($data_carr, $carr_id);

            if($data_response){



                $funcion_request = 'ver';

                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);

                $this->session->set_flashdata('mensaje', (($carr_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));



                redirect('registros/carrera/');

            }else{

                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);

                $this->layout->view('registros/carrera_form', $data);

            }

        }

        $this->load->view('notificacion');

    }







}































































