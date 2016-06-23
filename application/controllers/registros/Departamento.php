<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento extends CI_Controller {
    private static $header_title  = 'Departamentos';
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
        
    }

     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0); 
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/departamento');   
    }

    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['offset']=$offset;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'registros/departamento/index';
        $propio['filas_totales'] = $this->departamento_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['btn_nuevo']      = 'registros/departamento/nuevo';
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $this->layout->view('registros/departamento_index', $data);
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
        $data['btn_nuevo']      = 'registros/departamento/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/departamento');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/departamento') : '';
            $data['data_depa']  = $this->departamento_model->getDepartamentoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/departamento_index', $data);
            $this->load->view('notificacion');
        }
    }


    public function buscaritem($depa_id_enc = ''){//pendiente
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        $data['btn_editar']     = 'registros/departamento/editar/'.$depa_id_enc;
        $data['btn_regresar']   = 'registros/departamento';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/departamento/ver/'.$depa_id_enc);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/departamento/ver/'.$depa_id_enc) : '';
            //$data['data_depa']  = $this->departamento_model->getOficinaAll($depa_id, $datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/departamento_form', $data);
            $this->load->view('notificacion');
        }
    }



    public function ver($depa_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($depa_id);
        $data['btn_editar']     = 'registros/departamento/editar/'.$depa_id_enc;
        $data['btn_regresar']   = 'registros/departamento';
        $this->layout->view('registros/departamento_form', $data);
        $this->load->view('notificacion');
    }


    public function editar($depa_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        $depa_id                = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_depa']      = $this->departamento_model->getDepartamentoByID($depa_id);
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($depa_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/departamento';
        $this->layout->view('registros/departamento_form', $data);
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
        $data['btn_cancelar']   = 'registros/departamento';
        $this->layout->view('registros/departamento_form', $data);
        $this->load->view('notificacion');

    }





    public function eliminar($depa_id_enc = ''){
        ($depa_id_enc === '') ? redirect('registros/departamento') : '';
        $depa_id = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->departamento_model->deleteDepartamentoByID($depa_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/departamento');

    }



    public function guardar($depa_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $depa_id = ($depa_id_enc === '') ? '' : str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('depa_codref', 'Código', 'required|trim');
        $this->form_validation->set_rules('depa_descripcion', 'Departamento', 'required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($depa_id === '')?'nuevo':'editar';
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($depa_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/departamento';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/departamento_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_depa = array(
                'depa_codref'               => $datapost['depa_codref'],
                'depa_descripcion'          => $datapost['depa_descripcion']
            );
            $data_response = ($depa_id === '') ? $this->departamento_model->insertDepartamento($data_depa) : $this->departamento_model->updateDepartamento($data_depa, $depa_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($depa_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $depa_id = $data_response;
                redirect('registros/departamento/ver/'.str_encrypt($depa_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/departamento_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


    public function getOficina_ajax($depa_id_enc = ''){//pendiente
        ($depa_id_enc === '') ? exit() : '';
        $depa_id            = str_decrypt($depa_id_enc, KEY_ENCRYPT);
        $data_ofic          = $this->oficina_model->getOficinaAll($depa_id);
        echo json_encode($data_ofic);
    }






}































