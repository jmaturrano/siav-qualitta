<?php
/**
* MK System Soft  
*
* Controlador de lista de precios
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Listaprecio extends CI_Controller {
    private static $header_title  = 'Listas de precios';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/listaprecio_model');
    }

     /**
      * Función inicial  
      *
      * Carga los datos del sistema y sesión
      *
      *
      * @return void
      */
     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/listaprecio'); 
    }

     /**
      * Vista principal  
      *
      * Carga la lista de precios creadas
      *
      * @param int $ofsset parametro de seleccion de paginacion
      *
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
        $propio['ruta_base'] = 'registros/listaprecio/index';
        $propio['filas_totales'] = $this->listaprecio_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']      = 'registros/listaprecio/nuevo';
        $this->layout->view('registros/listaprecio_index', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista resultado de búsqueda  
      *
      * Carga la lista de Precios
      *
      *
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
        $data['btn_nuevo']      = 'registros/listaprecio/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/listaprecio');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/listaprecio') : '';
            $data['data_lipe']  = $this->listaprecio_model->getListaprecioAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/listaprecio_index', $data);
            $this->load->view('notificacion');
        }
    }


    /**
      * Vista de resultado  
      *
      * Muestra los datos de la lista
      *      
      * @param string $lipe_id_enc id encriptado de lista de precio
      *
      * @return void
      */
    public function ver($lipe_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($lipe_id_enc === '') ? redirect('registros/listaprecio') : '';
        $lipe_id = str_decrypt($lipe_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioByID($lipe_id);
        $data['btn_editar']     = 'registros/listaprecio/editar/'.$lipe_id_enc;
        $data['btn_regresar']   = 'registros/listaprecio';
        $this->layout->view('registros/listaprecio_form', $data);
        $this->load->view('notificacion');
    }


    /**
      * Vista de edición  
      *
      * Muestra los datos de la lista
      *      
      * @param string $lipe_id_enc id encriptado de lista de precio
      *
      * @return void
      */
    public function editar($lipe_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($lipe_id_enc === '') ? redirect('registros/listaprecio') : '';
        $lipe_id = str_decrypt($lipe_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioByID($lipe_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/listaprecio';
        $this->layout->view('registros/listaprecio_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de registro  
      *
      * Muestra el formulario de registro    
      *
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
        $data['btn_cancelar']   = 'registros/listaprecio';
        $this->layout->view('registros/listaprecio_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $lipe_id_enc id encriptado de la lista
      *
      *   
      * @return void
      */
    public function eliminar($lipe_id_enc = ''){       
        ($lipe_id_enc === '') ? redirect('registros/listaprecio') : '';
        $lipe_id = str_decrypt($lipe_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->listaprecio_model->deleteListaprecioByID($lipe_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/listaprecio');
    }


    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $lipe_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($lipe_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $datapost = $this->security->xss_clean($this->input->post());
        $lipe_id = ($lipe_id_enc === '') ? '' : str_decrypt($lipe_id_enc, KEY_ENCRYPT);
        $validacion_unique = '';
        if($lipe_id === ''){
            $validacion_unique = '|is_unique[lista_precios.lipe_descripcion]';
        }//end if
        $this->form_validation->set_rules('lipe_descripcion', 'Descripción','required|trim'.$validacion_unique);
        $this->form_validation->set_rules('lipe_indvigente', 'Lista principal','required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($lipe_id === '')?'nuevo':'editar';
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioByID($lipe_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/listaprecio';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/listaprecio_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            if($datapost['lipe_indvigente'] === '1'){
                //SETEA TODOS A NO VIGENTES
                $this->listaprecio_model->updateListaprecioAll(array('lipe_indvigente' => 'N'));
            }
            $data_lipe = array(
                'lipe_descripcion'        => $datapost['lipe_descripcion'],
                'lipe_indvigente'         => ($datapost['lipe_indvigente'] == '0') ? 'N' : 'S'
                            );
            $data_response = ($lipe_id === '') ? $this->listaprecio_model->insertListaprecio($data_lipe) : $this->listaprecio_model->updateListaprecio($data_lipe, $lipe_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($lipe_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/listaprecio');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/listaprecio_form', $data);
            }
        }
        $this->load->view('notificacion');
    }











































































































































}































































