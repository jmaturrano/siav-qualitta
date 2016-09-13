<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller {
    private static $header_title  = 'Pagos';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/matricula_model');
        $this->load->model('servicios/financiamiento_model');
        $this->load->model('registros/listaprecio_model');
        $this->load->model('registros/conceptosxmatricula_model');


        $this->load->model('registros/alumno_model');
        $this->load->model('registros/compromisoscarrera_model');
        $this->load->model('registros/carrera_model');
        $this->load->model('registros/modalidad_model');
        $this->load->model('registros/grupomatricula_model');
        $this->load->model('registros/modalidadxcurso_model');
        $this->load->model('registros/conceptosmatricula_model');
        $this->load->model('registros/estadomatricula_model');
        $this->load->model('registros/requisitosxcarrera_model');
        $this->load->model('servicios/requisitosxalumno_model');
        $this->load->model('registros/estadosxmatricula_model');

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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/pagos'); 
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

    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'finanzas/pagos/index';
        $propio['filas_totales'] = $this->matricula_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);

        $data_matr      = $this->matricula_model->getMatriculaAllPAGOS('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data_pagos     = array();
        $i              = 0;
        $monto_pagado   = 0;
        $monto_pendiente= 0;
        $matr_id_ant    = '';
        if(isset($data_matr)){

          foreach ($data_matr as $item => $matr) {

            if($matr->matr_id !== $matr_id_ant && $matr_id_ant !== ''){
              $data_pagos[$i]['monto_pagado'] = $monto_pagado;
              $data_pagos[$i]['monto_pendiente'] = $monto_pendiente;

              $i++;
            }//end if

            if($matr->matr_id !== $matr_id_ant){
              $monto_pagado   = 0;
              $monto_pendiente= 0;

              $data_pagos[$i]['matr_id'] = $matr->matr_id;
              $data_pagos[$i]['carr_codigo'] = $matr->carr_codigo;
              $data_pagos[$i]['matr_codigo'] = $matr->matr_codigo;
              $data_pagos[$i]['alum_apellido'] = $matr->alum_apellido;
              $data_pagos[$i]['alum_nombre'] = $matr->alum_nombre;
              $data_pagos[$i]['matr_fecha_proceso'] = $matr->matr_fecha_proceso;
              $data_pagos[$i]['emat_descripcion'] = $matr->emat_descripcion;
            }//end if

            if($matr->fima_pagado === 'S'){
              $monto_pagado += (float)$matr->fima_monto;
            }else{
              $monto_pendiente += (float)$matr->fima_monto;
            }
            $matr_id_ant = $matr->matr_id;
          }//end foreach

          $data_pagos[$i]['monto_pagado'] = $monto_pagado;
          $data_pagos[$i]['monto_pendiente'] = $monto_pendiente;

        }//end if

        $data['data_pagos'] = $data_pagos;
        $data['btn_nuevo']      = 'finanzas/pagos/nuevo';
        $this->layout->view('finanzas/pagos_index', $data);
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
    public function ver($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByCARRMODA(isset($data['data_matr'])?$data['data_matr']->carr_id:0, isset($data['data_matr'])?$data['data_matr']->moda_id:0);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoAllByMATRID($matr_id);
        
        $data['btn_editar']     = 'finanzas/pagos/editar/'.$matr_id_enc;
        $data['btn_regresar']   = 'finanzas/pagos';
        $this->layout->view('finanzas/pagos_form', $data);
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
    public function editar($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByCARRMODA(isset($data['data_matr'])?$data['data_matr']->carr_id:0, isset($data['data_matr'])?$data['data_matr']->moda_id:0);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoAllByMATRID($matr_id);
        
        $data['btn_cancelar']   = 'finanzas/pagos/ver/'.$matr_id_enc;
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'finanzas/pagos';
        $this->layout->view('finanzas/pagos_form', $data);
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
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $matr_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($matr_id_enc = ''){
        ($matr_id_enc === '') ? redirect('servicios/financiamiento') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->financiamiento_model->deleteMatriculaByID($matr_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('servicios/financiamiento');
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
    public function guardar($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('finanzas/pagos') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        // $fima_id = ($fima_id_enc === '') ? '' : str_decrypt($fima_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('emat_id', 'Estado Matrícula', 'required|trim');
        // $this->form_validation->set_rules('fima_fecha_programada', 'Fecha', 'required|trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByCARRMODA(isset($data['data_matr'])?$data['data_matr']->carr_id:0, isset($data['data_matr'])?$data['data_matr']->moda_id:0);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();
        $data['data_fima']      = $this->financiamiento_model->getFinanciamientoAllByMATRID($matr_id);
        
        $data['btn_cancelar']   = 'finanzas/pagos/ver/'.$matr_id_enc;
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'finanzas/pagos';

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('finanzas/pagos_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            /* 1.1- REGISTRAR ESTADO MATRICULA */
            $data_exma = array(
                    'exma_fecha_movimiento' => date('Y-m-d'),
                    'matr_id'               => $matr_id,
                    'emat_id'               => $datapost['emat_id']
                );
            $data_response = $this->estadosxmatricula_model->insertEstadosxmatricula($data_exma);

            $data_matr = array(
                    'emat_id' => $datapost['emat_id']
              );
            $this->matricula_model->updateMatricula($data_matr, $matr_id);
            /* 1.1- REGISTRAR ESTADO MATRICULA - END */

            /* 1.2.- REGISTRAR ESTADO ALUMNO */
            $alum_id = $data['data_matr']->alum_id;
            $data_exal = array(
                    'exal_fecha_movimiento' => date('Y-m-d'),
                    'alum_id'               => $alum_id
                );
            registrar_estado_alumno($this, $data_exal, '03');
            /* 1.2.- REGISTRAR ESTADO ALUMNO - END */

            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', RMESSAGE_UPDATE);
                redirect('finanzas/pagos/ver/'.$matr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('finanzas/pagos_form', $data);
            }
        }
        $this->load->view('notificacion');
    }



}