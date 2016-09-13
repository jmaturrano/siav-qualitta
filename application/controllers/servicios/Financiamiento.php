<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Financiamiento extends CI_Controller {
    private static $header_title  = 'Financiamiento';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/financiamiento_model');
        $this->load->model('servicios/matricula_model');
        $this->load->model('registros/listaprecio_model');
        $this->load->model('registros/conceptosxmatricula_model');
        date_default_timezone_set('America/Lima');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/financiamiento'); 
    }

     /**
      * Vista principal  
      *
      * Carga la lista de matriculaes
      *
      * @param int $ofsset parametro de seleccion de paginacion
      *
      * @return void
      */

    public function index() {
      redirect('servicios/financiamiento');
    }

    public function lista($matr_id_enc = '', $offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'servicios/financiamiento/lista/'.$matr_id_enc;
        $propio['filas_totales'] = $this->financiamiento_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoAllByMATRID($matr_id);
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['btn_nuevo']      = 'servicios/financiamiento/nuevo/'.$matr_id_enc;
        $data['btn_regresar']   = 'servicios/matricula/ver/'.$matr_id_enc;
        $this->layout->view('servicios/financiamiento_index', $data);
        $this->load->view('notificacion');
    }


    /**
      * Vista de resultado  
      *
      * Muestra los datos del registro
      *      
      * @param string $matr_id_enc id encriptado del registro
      *
      * @return void
      */
    public function ver($matr_id_enc = '', $fima_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID($fima_id);
        
        $data['btn_editar']     = 'servicios/financiamiento/editar/'.$matr_id_enc.'/'.$fima_id_enc;
        $data['btn_regresar']   = 'servicios/financiamiento/lista/'.$matr_id_enc;
        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }
    

    public function verpago($matr_id_enc = '', $fima_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID($fima_id);
        
        $data['btn_editar']     = 'servicios/financiamiento/editarpago/'.$matr_id_enc.'/'.$fima_id_enc;
        $data['btn_regresar']   = 'servicios/financiamiento/lista/'.$matr_id_enc;
        $data['finanzas']       = true;
        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de edición  
      *
      * Muestra los datos del registro
      *      
      * @param string $matr_id_enc id encriptado del registro
      *
      * @return void
      */
    public function editar($matr_id_enc = '', $fima_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID($fima_id);
        
        $data['btn_guardar']     = true;
        $data['btn_cancelar']   = 'servicios/financiamiento/ver/'.$matr_id_enc.'/'.$fima_id_enc;
        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }


    public function editarpago($matr_id_enc = '', $fima_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID($fima_id);
        
        $data['btn_guardar']     = true;
        $data['btn_cancelar']   = 'servicios/financiamiento/ver/'.$matr_id_enc.'/'.$fima_id_enc;
        $data['finanzas']       = true;
        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }

    /**
      * Vista de registro  
      *
      * Muestra el formulario de registro    
      *
      * @return void
      */
    public function nuevo($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/financiamiento/lista/'.$matr_id_enc;
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();

        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }


    /**
      * Vista de registro  
      *
      * Muestra el formulario de registro    
      *
      * @return void
      */
    public function nuevopago($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'finanzas/pagos/ver/'.$matr_id_enc;
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['finanzas']       = true;

        $this->layout->view('servicios/financiamiento_form', $data);
        $this->load->view('notificacion');
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $matr_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($matr_id_enc = '', $fima_id_enc = ''){
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->financiamiento_model->deleteFinanciamientoByID($fima_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('servicios/financiamiento');
    }


    public function eliminarpago($matr_id_enc = '', $fima_id_enc = ''){
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        ($fima_id_enc === '') ? redirect('servicios/financiamiento/lista/'.$matr_id_enc) : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->financiamiento_model->deleteFinanciamientoByID($fima_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('finanzas/pagos/ver/'.$matr_id_enc);
    }

    /**
      * Registro de Lista de precio
      *
      * Procesa el registro o actualización de una lista
      *
      * @param string $matr_id_enc id encriptado de la lista
      *
      * @return void
      */
    public function guardar($matr_id_enc = '', $fima_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = ($fima_id_enc === '') ? '' : str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('fima_monto', 'Monto', 'required|trim|numeric');
        $this->form_validation->set_rules('fima_fecha_programada', 'Fecha', 'required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($fima_id === '')?'nuevo':'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID(($fima_id === '') ? 0: $fima_id);

        $data['btn_cancelar']   = 'servicios/financiamiento/lista/'.$matr_id_enc;
        $data['btn_guardar']    = true;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/financiamiento_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_fima = array(
                'fima_monto'            => $datapost['fima_monto'],
                'fima_fecha_programada' => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['fima_fecha_programada']))),
                'matr_id'               => $matr_id
            );

            $data_response = ($fima_id === '') ? $this->financiamiento_model->insertFinanciamiento($data_fima) : $this->financiamiento_model->updateFinanciamiento($data_fima, $fima_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($fima_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('servicios/financiamiento/lista/'.$matr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/financiamiento_form', $data);
            }
        }
        $this->load->view('notificacion');
    }




    public function guardarpago($matr_id_enc = '', $fima_id_enc = ''){

      // redirect('finanzas/pagos/ver/'.$matr_id_enc);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $fima_id = ($fima_id_enc === '') ? '' : str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('fima_monto', 'Monto', 'required|trim|numeric');
        $this->form_validation->set_rules('fima_fecha_programada', 'Fecha', 'required|trim');
        $this->form_validation->set_rules('fima_pagado', 'Pagado', 'trim');
        $this->form_validation->set_rules('fima_comprobante', 'Comprobante', 'trim');
        $this->form_validation->set_rules('fima_fecha_proceso', 'Fecha proceso', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($fima_id === '')?'nuevo':'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoByID(($fima_id === '') ? 0: $fima_id);
        $data['finanzas']       = true;

        $data['btn_cancelar']   = 'finanzas/pagos/ver/'.$matr_id_enc;
        $data['btn_guardar']    = true;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/financiamiento_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_fima = array(
                'fima_monto'            => $datapost['fima_monto'],
                'fima_fecha_programada' => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['fima_fecha_programada']))),
                'matr_id'               => $matr_id,
                'fima_pagado'           => (isset($datapost['fima_pagado'])) ? ($datapost['fima_pagado'] == '1' ? 'S' : 'N') : 'N',
                'fima_comprobante'      => $datapost['fima_comprobante']
            );
            if($datapost['fima_fecha_proceso'] != ''){
              $data_fima['fima_fecha_proceso'] = date('Y-m-d', strtotime(str_replace('/', '-', $datapost['fima_fecha_proceso'])));
            }

            $data_response = ($fima_id === '') ? $this->financiamiento_model->insertFinanciamiento($data_fima) : $this->financiamiento_model->updateFinanciamiento($data_fima, $fima_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($fima_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('finanzas/pagos/ver/'.$matr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/financiamiento_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}