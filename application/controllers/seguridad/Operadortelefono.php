<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Operadortelefono extends CI_Controller {
    private static $header_title  = 'Operadores Teléfono';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
       $this->initData();  
       $this->load->model('seguridad/operadortelefono_model');

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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/operadortelefono'); 
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
        $propio['ruta_base'] = 'seguridad/operadortelefono/index';
        $propio['filas_totales'] = $this->operadortelefono_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'seguridad/operadortelefono/nuevo';
        $this->layout->view('seguridad/operadortelefono_index', $data);
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
        $data['btn_nuevo']      = 'seguridad/operadortelefono/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/operadortelefono');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/operadortelefono') : '';
            $data['data_opte']  = $this->operadortelefono_model->getOperadortelefonoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/operadortelefono_index', $data);
            $this->load->view('notificacion');
        }
    }

    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * otip_id_enc id operador telefono encriptado
    * @return void
    */

    public function ver($otel_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($otel_id_enc === '') ? redirect('seguridad/operadortelefono') : '';
        $otel_id = str_decrypt($otel_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoByID($otel_id);
        $data['btn_editar']     = 'seguridad/operadortelefono/editar/'.$otel_id_enc;
        $data['btn_regresar']   = 'seguridad/operadortelefono';
        $this->layout->view('seguridad/operadortelefono_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * otip_id_enc id operador telefono encriptado
    * @return void
    */

    public function editar($otel_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($otel_id_enc === '') ? redirect('seguridad/operadortelefono') : '';
        $otel_id = str_decrypt($otel_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoByID($otel_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/operadortelefono';
        $this->layout->view('seguridad/operadortelefono_form', $data);
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
        $data['btn_cancelar']   = 'seguridad/operadortelefono';
        $this->layout->view('seguridad/operadortelefono_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * otip_id_enc id operador telefono encriptado
    * @return void
    */

    public function eliminar($otel_id_enc = ''){       

        ($otel_id_enc === '') ? redirect('seguridad/operadortelefono') : '';
        $otel_id = str_decrypt($otel_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->operadortelefono_model->deleteOperadortelefonoByID($otel_id);
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
        redirect('seguridad/operadortelefono');
    }

     /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * moae_id_enc id operador telefono encriptado
    * @return void
    */

    public function guardar($opte_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $opte_id = ($opte_id_enc === '') ? '' : str_decrypt($opte_id_enc, KEY_ENCRYPT);
         if ($opte_id === '') {
        $this->form_validation->set_rules('opte_descripcion','Descripción','required|trim|is_unique[operador_telefono.opte_descripcion]');
        }
         else 
        {
            $this->form_validation->set_rules('opte_descripcion','Descripción','required|trim');
        }
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($opte_id === '')?'nuevo':'editar';
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoByID($opte_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/operadortelefono';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/operadortelefono_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_opte = array(
                'opte_descripcion'           => $datapost['opte_descripcion'],
                            );
            $data_response = ($opte_id === '') ? $this->operadortelefono_model->insertOperadortelefono($data_opte) : $this->operadortelefono_model->updateOperadortelefono($data_opte, $opte_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($opte_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('seguridad/operadortelefono');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/operadortelefono_form', $data);
            }
        }
        $this->load->view('notificacion');
    }
}































