<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Curso extends CI_Controller {
    private static $header_title  = 'Cursos';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/curso_model');
        $this->load->model('registros/modulosxcarrera_model');
        $this->load->model('registros/carrera_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/curso');
    }

    public function index($offset = 0) {
        redirect('registros/carrera');
    }

    public function ver($carr_id_enc = '', $modu_id_enc = '', $curs_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        ($curs_id_enc === '') ? redirect('registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);
        $data['data_curs']      = $this->curso_model->getCursoByID($curs_id);

        $this->load->library('layout');
        $data['tipo_vista']     = 'ver';
        $data['btn_editar']     = 'registros/curso/editar/'.$carr_id_enc.'/'.$modu_id_enc.'/'.$curs_id_enc;
        $data['btn_regresar']   = 'registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc;
        $this->layout->view('registros/curso_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($carr_id_enc = '', $modu_id_enc = '', $curs_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        ($curs_id_enc === '') ? redirect('registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);
        $data['data_curs']      = $this->curso_model->getCursoByID($curs_id);

        $this->load->library('layout');
        $data['tipo_vista']     = 'editar';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/curso/ver/'.$carr_id_enc.'/'.$modu_id_enc.'/'.$curs_id_enc;
        $this->layout->view('registros/curso_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo($carr_id_enc = '', $modu_id_enc = ''){
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

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);

        $this->load->library('layout');
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc;
        $this->layout->view('registros/curso_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($carr_id_enc = '', $modu_id_enc = '', $curs_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        ($curs_id_enc === '') ? redirect('registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc) : '';
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->curso_model->deleteCursoByID($curs_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc);
    }

    public function guardar($carr_id_enc = '', $modu_id_enc = '', $curs_id_enc = ''){
        ($carr_id_enc === '') ? redirect('registros/carrera') : '';
        ($modu_id_enc === '') ? redirect('registros/carrera/ver/'.$carr_id_enc) : '';
        
        $carr_id = str_decrypt($carr_id_enc, KEY_ENCRYPT);
        $modu_id = str_decrypt($modu_id_enc, KEY_ENCRYPT);
        $curs_id = ($curs_id_enc === '') ? '' : str_decrypt($curs_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $data['data_carr']      = $this->carrera_model->getCarreraByID($carr_id);
        $data['data_modu']      = $this->modulosxcarrera_model->getModulosxcarreraByID($modu_id);
        $data['data_curs']      = $this->curso_model->getCursoByID(($curs_id === '')?0:$curs_id);

        $this->form_validation->set_rules('curs_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('curs_descripcion', 'Nombre curso', 'required|trim');

        $data['tipo_vista']     = ($curs_id === '')?'nuevo':'editar';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/modulosxcarrera/ver/'.$carr_id_enc.'/'.$modu_id_enc;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/curso_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_curs = array(
                'curs_codigo'               => $datapost['curs_codigo'],
                'curs_descripcion'          => $datapost['curs_descripcion'],
                'modu_id'                   => $modu_id
            );
            $data_response = ($curs_id === '') ? $this->curso_model->insertCurso($data_curs) : $this->curso_model->updateCurso($data_curs, $curs_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($curs_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $curs_id = $data_response;
                redirect('registros/curso/ver/'.$carr_id_enc.'/'.$modu_id_enc.'/'.str_encrypt($curs_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/curso_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}































































































































