<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provincia extends CI_Controller {
    private static $header_title  = 'Provincias';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
         $this->initData();
        $this->load->model('registros/departamento_model');
        $this->load->model('registros/provincia_model');
        $this->load->model('registros/distrito_model');
        
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/departamento');  
    }

    public function index($depa_id_enc = '') {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        $this->load->library('pagination');
        $propio['ruta_base'] = 'registros/provincia/index';
        $propio['filas_totales'] = $this->provincia_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['btn_nuevo']      = 'registros/provincia/nuevo';
        $data['data_prov']      = $this->provincia_model->getDepartamentoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $this->layout->view('registros/provincia_index', $data);
        $this->load->view('notificacion');
    }



    public function buscar($depa_id_enc = ''){
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['btn_nuevo']      = 'registros/provincia/nuevo/'.$depa_id_enc;
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/provincia');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/provincia') : '';
            $data['depa_id']    = $depa_id;
            $data['data_prov']  = $this->provincia_model->getProvinciaAll($depa_id, $datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/provincia_index', $data);
            $this->load->view('notificacion');
        }
    }


    public function buscaritem($prov_id_enc = ''){//pendiente
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($prov_id_enc === '') ? redirect('seguridad/oficina') : '';
        $prov_id                = str_decrypt($prov_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_prov']      = $this->provincia_model->getProvinciaByID($prov_id);
        $data['btn_editar']     = 'registros/provincia/editar/'.$prov_id_enc;
        $data['btn_regresar']   = 'registros/provincia';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/provincia/ver/'.$prov_id_enc);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/provincia/ver/'.$prov_id_enc) : '';
            //$data['data_prov']  = $this->provincia_model->getOficinaAll($prov_id, $datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/provincia_form', $data);
            $this->load->view('notificacion');
        }
    }



    public function ver($depa_id_enc = '', $prov_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        ($prov_id_enc === '') ? redirect('registros/departamento/ver/'.$depa_id_enc) : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $prov_id                = str_decrypt($prov_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['tipo_vista']     = 'ver';
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        $data['data_prov']      = $this->provincia_model->getProvinciaByID($prov_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll($prov_id);
        $data['btn_editar']     = 'registros/provincia/editar/'.$depa_id_enc.'/'.$prov_id_enc;
        $data['btn_regresar']   = 'registros/departamento/ver/'.str_encrypt($data['data_prov']->depa_id, KEY_ENCRYPT);
        $this->layout->view('registros/provincia_form', $data);
        $this->load->view('notificacion');
    }


    public function editar($depa_id_enc = '', $prov_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        ($prov_id_enc === '') ? redirect('registros/departamento/ver/'.$depa_id_enc) : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $prov_id                = str_decrypt($prov_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['tipo_vista']     = 'editar';
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        $data['data_prov']      = $this->provincia_model->getProvinciaByID($prov_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll($prov_id);
        $data['btn_guardar']    = true;
        //$data['btn_cancelar']   = 'registros/provincia/ver/'.$prov_id_enc;
        $data['btn_cancelar']   = 'registros/departamento/ver/'.str_encrypt($data['data_prov']->depa_id, KEY_ENCRYPT);
        $this->layout->view('registros/provincia_form', $data);
        $this->load->view('notificacion');
    }



    public function nuevo($depa_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $depa_id                = ($depa_id_enc === '') ? redirect('registros/departamento') : str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        //print_r($data['data_depa']);
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/departamento/ver/'.$depa_id_enc;
        $this->layout->view('registros/provincia_form', $data);
        $this->load->view('notificacion');

    }

    public function eliminar($depa_id_enc = '', $prov_id_enc = ''){
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        ($prov_id_enc === '') ? redirect('registros/departamento/ver/'.$depa_id_enc) : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $prov_id                = str_decrypt($prov_id_enc, KEY_ENCRYPT);

        $data['data_prov']      = $this->provincia_model->getProvinciaByID($prov_id);
        $data_delete            = $this->provincia_model->deleteProvinciaByID($prov_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/departamento/ver/'.str_encrypt($data['data_prov']->depa_id, KEY_ENCRYPT));

    }

    public function guardar($depa_id_enc = '', $prov_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        //($prov_id_enc === '') ? redirect('registros/departamento/ver/'.$depa_id_enc) : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $prov_id                = ($prov_id_enc === '') ? '' : str_decrypt($prov_id_enc, KEY_ENCRYPT);

        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        //$this->form_validation->set_rules('depa_id', 'Departamento', 'required');
        $this->form_validation->set_rules('prov_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('prov_descripcion', 'Provincia', 'required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($prov_id === '')?'nuevo':'editar';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/departamento/ver/'.$depa_id_enc;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/provincia_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_prov = array(
                'depa_id'                        => $depa_id,
                'prov_codigo'                    => $datapost['prov_codigo'],
                'prov_descripcion'               => $datapost['prov_descripcion']
            );
            $data_response = ($prov_id === '') ? $this->provincia_model->insertProvincia($data_prov) : $this->provincia_model->updateProvincia($data_prov, $prov_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($prov_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $prov_id = $data_response;
                redirect('registros/provincia/ver/'.$depa_id_enc.'/'.str_encrypt($prov_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/provincia_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


    public function getProvincia_ajax($depa_id = ''){
        //($depa_id_enc === '') ? exit() : '';
        //$depa_id            = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $data_prov          = $this->provincia_model->getProvinciaAll($depa_id);
        echo json_encode($data_prov);
    }






}































