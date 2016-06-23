<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oficina extends CI_Controller {
    private static $header_title  = 'Oficinas';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/oficina_model');
        $this->load->model('seguridad/tipooficina_model');
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);    
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/oficina');
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
        $propio['ruta_base'] = 'seguridad/oficina/index';
        $propio['filas_totales'] = $this->oficina_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['offset'] = $offset;
        $data['btn_nuevo']      = 'seguridad/oficina/nuevo';
        $data['data_ofic']      = $this->oficina_model->getOficinaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $this->layout->view('seguridad/oficina_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/oficina/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/oficina');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/oficina') : '';
            $data['data_ofic']  = $this->oficina_model->getOficinaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/oficina_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($ofic_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($ofic_id_enc === '') ? redirect('seguridad/oficina') : '';
        $ofic_id                = str_decrypt($ofic_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_ofic']      = $this->oficina_model->getOficinaByID($ofic_id);
        $data['data_otip']      = $this->tipooficina_model->getTipooficinaAll();
        $data['btn_editar']     = 'seguridad/oficina/editar/'.$ofic_id_enc;
        $data['btn_regresar']   = 'seguridad/oficina';
        $this->layout->view('seguridad/oficina_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($ofic_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($ofic_id_enc === '') ? redirect('seguridad/oficina') : '';
        $ofic_id                = str_decrypt($ofic_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_ofic']      = $this->oficina_model->getOficinaByID($ofic_id);
        $data['data_otip']      = $this->tipooficina_model->getTipooficinaAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/oficina';
        $this->layout->view('seguridad/oficina_form', $data);
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
        $data['data_otip']      = $this->tipooficina_model->getTipooficinaAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/oficina';
        $this->layout->view('seguridad/oficina_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($ofic_id_enc = ''){
        ($ofic_id_enc === '') ? redirect('seguridad/oficina') : '';
        $ofic_id = str_decrypt($ofic_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->oficina_model->deleteOficinaByID($ofic_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/oficina');
    }

    public function guardar($ofic_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $ofic_id = ($ofic_id_enc === '') ? '' : str_decrypt($ofic_id_enc, KEY_ENCRYPT);

        $unique_codigo = '';
        if($ofic_id === ''){
            $unique_codigo = '|is_unique[oficina.ofic_codigo]';
        }
        $this->form_validation->set_rules('otip_id', 'Tipo Oficina', 'required');
        $this->form_validation->set_rules('ofic_codigo', 'Código', 'trim'.$unique_codigo);
        $this->form_validation->set_rules('ofic_nombre', 'Nombre Oficina', 'required|trim');
        $this->form_validation->set_rules('ofic_abreviatura', 'Abreviatura', 'trim');
        $this->form_validation->set_rules('ofic_direccion', 'Dirección', 'required|trim');
        $this->form_validation->set_rules('ofic_email', 'Email', 'required|valid_email');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($ofic_id === '')?'nuevo':'editar';
        $data['data_otip']      = $this->tipooficina_model->getTipooficinaAll();
        $data['data_ofic']      = ($ofic_id === '')? null : $this->oficina_model->getOficinaAll($ofic_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/oficina';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/oficina_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_ofic = array(
                'otip_id'               => $datapost['otip_id'],
                'ofic_codigo'           => $datapost['ofic_codigo'],
                'ofic_nombre'           => $datapost['ofic_nombre'],
                'ofic_abreviatura'      => $datapost['ofic_abreviatura'],
                'ofic_direccion'        => $datapost['ofic_direccion'],
                'ofic_email'            => $datapost['ofic_email']
            );
            $data_response = ($ofic_id === '') ? $this->oficina_model->insertOficina($data_ofic) : $this->oficina_model->updateOficina($data_ofic, $ofic_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($ofic_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $ofic_id = $data_response;
                redirect('seguridad/oficina/ver/'.str_encrypt($ofic_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/oficina_form', $data);
            }
        }
        $this->load->view('notificacion');
    }

    public function getOficina_ajax($ofic_id_enc = ''){
        $datapost = $this->security->xss_clean($this->input->post());
        if(isset($datapost['ogru_id_array'])){
            $data_ofic          = $this->oficina_model->getOficinaAllByArray($datapost['ogru_id_array']);
        }else {
            $ofic_id            = str_decrypt($ofic_id_enc, KEY_ENCRYPT);
            $data_ofic          = $this->oficina_model->getOficinaAll($ofic_id);
        }
        echo json_encode($data_ofic);
    }


}
