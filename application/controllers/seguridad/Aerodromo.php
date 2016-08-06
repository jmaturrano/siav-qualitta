<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aerodromo extends CI_Controller {
    private static $header_title  = 'Aeródromos';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/aerodromo_model');
        $this->load->model('registros/departamento_model');
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);    
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/aerodromo');
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
        $propio['ruta_base'] = 'seguridad/aerodromo/index';
        $propio['filas_totales'] = $this->aerodromo_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['offset'] = $offset;
        $data['btn_nuevo']      = 'seguridad/aerodromo/nuevo';
        $data['data_aero']      = $this->aerodromo_model->getAerodromoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $this->layout->view('seguridad/aerodromo_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/aerodromo/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/aerodromo');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/aerodromo') : '';
            $data['data_aero']  = $this->aerodromo_model->getAerodromoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/aerodromo_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($aero_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($aero_id_enc === '') ? redirect('seguridad/aerodromo') : '';
        $aero_id                = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_aero']      = $this->aerodromo_model->getAerodromoByID($aero_id);
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['btn_editar']     = 'seguridad/aerodromo/editar/'.$aero_id_enc;
        $data['btn_regresar']   = 'seguridad/aerodromo';
        $this->layout->view('seguridad/aerodromo_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($aero_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($aero_id_enc === '') ? redirect('seguridad/aerodromo') : '';
        $aero_id                = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_aero']      = $this->aerodromo_model->getAerodromoByID($aero_id);
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/aerodromo';
        $this->layout->view('seguridad/aerodromo_form', $data);
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
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/aerodromo';
        $this->layout->view('seguridad/aerodromo_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($aero_id_enc = ''){
        ($aero_id_enc === '') ? redirect('seguridad/aerodromo') : '';
        $aero_id = str_decrypt($aero_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->aerodromo_model->deleteAerodromoByID($aero_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/aerodromo');
    }

    public function guardar($aero_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $aero_id = ($aero_id_enc === '') ? '' : str_decrypt($aero_id_enc, KEY_ENCRYPT);

        $unique_codigo = '';
        if($aero_id === ''){
            $unique_codigo = '|is_unique[aerodromo.aero_codigo]';
        }
        $this->form_validation->set_rules('aero_codigo', 'Código OACI', 'trim'.$unique_codigo);
        $this->form_validation->set_rules('aero_nombre', 'Nombre Aeródromo', 'required|trim');
        $this->form_validation->set_rules('aero_abreviatura', 'Abreviatura', 'trim');
        $this->form_validation->set_rules('aero_direccion', 'Dirección', 'required|trim');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($aero_id === '')?'nuevo':'editar';
        $data['data_aero']      = ($aero_id === '')? null : $this->aerodromo_model->getAerodromoByID($aero_id);
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/aerodromo';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/aerodromo_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_aero = array(
                'depa_id'               => $datapost['depa_id'],
                'aero_codigo'           => $datapost['aero_codigo'],
                'aero_nombre'           => $datapost['aero_nombre'],
                'aero_abreviatura'      => $datapost['aero_abreviatura'],
                'aero_direccion'        => $datapost['aero_direccion']
            );
            $data_response = ($aero_id === '') ? $this->aerodromo_model->insertAerodromo($data_aero) : $this->aerodromo_model->updateAerodromo($data_aero, $aero_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($aero_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $aero_id = $data_response;
                redirect('seguridad/aerodromo/ver/'.str_encrypt($aero_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/aerodromo_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}
