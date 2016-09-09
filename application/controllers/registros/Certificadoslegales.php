<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadoslegales extends CI_Controller {
    private static $header_title  = 'Certificados/Constancias';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
       $this->initData();  
  
        $this->load->model('registros/certificadoslegales_model');
        //$this->form_validation->set_error_delimiters('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>', '</div>');
    }

    /**
    * Funcion privilegios
    * Maneja los privilegios del sistema
    * @return void
    */

     public function initData(){

        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/certificadoslegales'); 
    }

    /**
    * Funcion Inicio 
    * Maneja la estructura principal
    * @return void
    */

    public function index($offset = 0) {

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'registros/certificadoslegales/index';
        $propio['filas_totales'] = $this->certificadoslegales_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'registros/certificadoslegales/nuevo';
        $this->layout->view('registros/certificadoslegales_index', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion buscar
    * Maneja la busqueda de datos
    * @return void
    */
    public function buscar(){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['btn_nuevo']      = 'registros/certificadoslegales/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/certificadoslegales');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/certificadoslegales') : '';
            $data['data_cele']  = $this->certificadoslegales_model->getCertificadoslegalesAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/certificadoslegales_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * otip_id_enc id certificados legales encriptado
    * @return void
    */

    public function ver($ctle_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($ctle_id_enc === '') ? redirect('registros/certificadoslegales') : '';
        $ctle_id = str_decrypt($ctle_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesByID($ctle_id);
        $data['btn_editar']     = 'registros/certificadoslegales/editar/'.$ctle_id_enc;
        $data['btn_regresar']   = 'registros/certificadoslegales';
        $this->layout->view('registros/certificadoslegales_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * otip_id_enc id certificados legales encriptado
    * @return void
    */

    public function editar($ctle_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($ctle_id_enc === '') ? redirect('registros/certificadoslegales') : '';
        $ctle_id = str_decrypt($ctle_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesByID($ctle_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/certificadoslegales';
        $this->layout->view('registros/certificadoslegales_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion Nuevo
    * Maneja opcion nuevo para ingresar nuevos datos
    * @return void
    */

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
        $data['btn_cancelar']   = 'registros/certificadoslegales';
        $this->layout->view('registros/certificadoslegales_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * otip_id_enc id certificados legales encriptado
    * @return void
    */

    public function eliminar($ctle_id_enc = ''){       

        ($ctle_id_enc === '') ? redirect('registros/certificadoslegales') : '';
        $ctle_id = str_decrypt($ctle_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->certificadoslegales_model->deleteCertificadoslegalesByID($ctle_id);
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
        redirect('registros/certificadoslegales');
    }

    /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * moae_id_enc id certificados legales encriptado
    * @return void
    */
    
    public function guardar($cele_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $datapost = $this->security->xss_clean($this->input->post());
        $cele_id = ($cele_id_enc === '') ? '' : str_decrypt($cele_id_enc, KEY_ENCRYPT);
        $validacion_unique = '';
        if($cele_id === ''){
            $validacion_unique = '|is_unique[certificados_legales.cele_descripcion]';
        }//end if
        $this->form_validation->set_rules('cele_descripcion', 'Descripción','required|trim'.$validacion_unique);
        $this->form_validation->set_rules('cele_anios_vigencia', 'Vigencia','required|trim');
        $this->form_validation->set_rules('cele_unidad_vigencia', 'Unidad de tiempo','required|trim');
        
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($cele_id === '')?'nuevo':'editar';
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesByID($cele_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/certificadoslegales';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/certificadoslegales_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_cele = array(
                'cele_descripcion'           => $datapost['cele_descripcion'],
                'cele_anios_vigencia'         => $datapost['cele_anios_vigencia'],
                'cele_unidad_vigencia'         => $datapost['cele_unidad_vigencia']
                            );
            $data_response = ($cele_id === '') ? $this->certificadoslegales_model->insertCertificadoslegales($data_cele) : $this->certificadoslegales_model->updateCertificadoslegales($data_cele, $cele_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($cele_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/certificadoslegales');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/certificadoslegales_form', $data);
            }
        }
        $this->load->view('notificacion');
    }











































































































































}































































