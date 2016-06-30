<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulosxcarrera extends CI_Controller {
    private static $header_title  = 'Modulos por Carrera';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/modulosxcarrera_model');
        $this->load->model('registros/carrera_model');
        $this->load->model('registros/nivelaprendizaje_model');
        $this->load->model('registros/curso_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/modulosxcarrera');
    }

    public function index($offset = 0) {
        redirect('registros/carrera');
    }

    public function ver($carr_id_enc = '', $modu_id_enc = ''){

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('layout');
        $data['tipo_vista']     = 'ver';
        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeAll();
        $data['data_curs']      = $this->curso_model->getCursoByMODUID($modu_id);

        $data['btn_editar']     = 'registros/modulosxcarrera/editar/'.$carr_id_enc.'/'.$modu_id_enc;
        $data['btn_regresar']   = 'registros/carrera/ver/'.$carr_id_enc;
        $this->layout->view('registros/modulosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($carr_id_enc = '', $modu_id_enc = ''){

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('layout');
        $data['tipo_vista']     = 'editar';
        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeAll();
        $data['data_curs']      = $this->curso_model->getCursoByMODUID($modu_id);
        
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc;
        $this->layout->view('registros/modulosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo($carr_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/modulosxcarrera') : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeAll();

        $this->load->library('layout');
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/carrera/ver/'.$carr_id_enc;
        $this->layout->view('registros/modulosxcarrera_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($carr_id_enc = '', $modu_id_enc = ''){
        
        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->modulosxcarrera_model->deleteModulosxcarreraByID($modu_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/carrera/ver/'.$carr_id_enc);
    }

    public function guardar($carr_id_enc = '', $modu_id_enc = ''){

        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = ($modu_id_enc === '') ? '' : str_decrypt($modu_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->form_validation->set_rules('modu_codigo', 'Código módulo', 'required|trim');
        $this->form_validation->set_rules('modu_descripcion', 'Nombre Modulosxcarrera', 'required|trim');
        $this->form_validation->set_rules('niap_id', 'Nivel de aprendizaje', 'required');

        $data['tipo_vista']     = ($modu_id === '')?'nuevo':'editar';
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID(($modu_id === '')?0:$modu_id);
        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_niap']      = $this->nivelaprendizaje_model->getNivelaprendizajeAll();
        $data['data_curs']      = $this->curso_model->getCursoByMODUID(($modu_id === '')?0:$modu_id);

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/carrera/ver/'.$carr_id_enc;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/modulosxcarrera_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_modu = array(
                'modu_codigo'               => $datapost['modu_codigo'],
                'modu_descripcion'          => $datapost['modu_descripcion'],
                'carr_id'                   => $carr_id,
                'niap_id'                   => $datapost['niap_id']
            );
            $data_response = ($modu_id === '') ? $this->modulosxcarrera_model->insertModulosxcarrera($data_modu) : $this->modulosxcarrera_model->updateModulosxcarrera($data_modu, $carr_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($modu_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/carrera/ver/'.$carr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/modulosxcarrera_form', $data);
            }
        }
        $this->load->view('notificacion');
    }

    public function getModulosxcarrera_ajax($carr_id = ''){
        ($carr_id === '') ? exit() : '';
        $data_modu      = $this->modulosxcarrera_model->getModulosxcarreraByCARRID($carr_id);
        echo json_encode($data_modu);
    }


}































































































































