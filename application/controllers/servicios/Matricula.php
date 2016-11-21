<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Matricula extends CI_Controller {
    private static $header_title  = 'Matrícula';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/matricula_model');
        $this->load->model('registros/alumno_model');
        $this->load->model('registros/compromisoscarrera_model');
        $this->load->model('registros/carrera_model');
        $this->load->model('registros/modalidad_model');
        $this->load->model('registros/grupomatricula_model');
        $this->load->model('registros/listaprecio_model');
        $this->load->model('registros/modalidadxcurso_model');
        $this->load->model('registros/conceptosmatricula_model');
        $this->load->model('registros/conceptosxmatricula_model');
        $this->load->model('registros/estadomatricula_model');
        $this->load->model('registros/requisitosxcarrera_model');
        $this->load->model('servicios/requisitosxalumno_model');
        $this->load->model('servicios/financiamiento_model');
        $this->load->model('registros/modulosxcarrera_model');
        $this->load->model('seguridad/oficina_model');
        $this->load->model('registros/telefonoxalumno_model');
        $this->load->model('seguridad/operadortelefono_model');
        $this->load->model('servicios/certificadosxalumno_model');
        $this->load->model('registros/certificadoslegales_model');
        $this->load->model('registros/apoderadoxalumno_model');
        $this->load->model('seguridad/reportexusuario_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/matricula'); 
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
        $propio['ruta_base'] = 'servicios/matricula/index';
        $propio['filas_totales'] = $this->matricula_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_matr']      = $this->matricula_model->getMatriculaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);

        $data['btn_nuevo']      = 'servicios/matricula/nuevo';
        $this->layout->view('servicios/matricula_index', $data);
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
        $data['btn_nuevo']      = 'servicios/matricula/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('servicios/matricula');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('servicios/matricula') : '';
            $data['data_matr']  = $this->matricula_model->getMatriculaAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('servicios/matricula_index', $data);
            $this->load->view('notificacion');
        }
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
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
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
        
        $data['btn_editar']     = 'servicios/matricula/editar/'.$matr_id_enc;
        $data['btn_regresar']   = 'servicios/matricula';
        $this->layout->view('servicios/matricula_form', $data);
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
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
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

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/matricula';
        $this->layout->view('servicios/matricula_form', $data);
        $this->load->view('notificacion');
    }

    public function confirmarreporte($matr_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['reporte_mail']   = true;
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByCARRMODA(isset($data['data_matr'])?$data['data_matr']->carr_id:0, isset($data['data_matr'])?$data['data_matr']->moda_id:0);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();
        
        $data['btn_editar']     = 'servicios/matricula/editar/'.$matr_id_enc;
        $data['btn_regresar']   = 'servicios/matricula';
        $this->layout->view('servicios/matricula_form', $data);
        $this->load->view('notificacion');
    }

    public function enviarreporte($matr_id_enc = ''){
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $data_matr              = $this->matricula_model->getMatriculaByID($matr_id);
        /* REPORTE NRO. 02 QUE CORRESPONDE AL REGISTRO DE MATRICULAS */
        $data_rexu = $this->reportexusuario_model->getReportexusuarioAllByREMACOD('02');
        if(isset($data_rexu)){
            foreach ($data_rexu as $item => $rexu) {
                $rema_id        = $rexu->rema_id;
                $usua_id        = $rexu->usua_id;
                $usua_nombre    = $rexu->usua_nombre;
                $usua_apellido  = $rexu->usua_apellido;
                $usua_email     = $rexu->usua_email;
                $rema_titulo    = $rexu->rema_titulo;
                $rema_descripcion= $rexu->rema_descripcion;
                if($usua_email != ''){
                    $data_palabras_reservadas = array(
                            '[MATRICULA]' => $data_matr->matr_codigo,
                            '[CURSO]' => '('.$data_matr->carr_codigo.') '.$data_matr->carr_descripcion,
                            '[COSTO]' => $data_matr->mone_prefijo.' '.number_format($data_matr->matr_costofinal, 2, '.', ',').' '.$data_matr->mone_descripcion,
                            '[ALUMNO]' => $data_matr->alum_apellido.' '.$data_matr->alum_nombre,
                            '[DIRECCION]' => $data_matr->alum_direccion
                        );
                    $email_to       = $usua_email;
                    $email_subject  = $rema_titulo;
                    $email_message  = reemplazar_palabras_reservadas($this, $rema_descripcion, $data_palabras_reservadas);
                    $mail_request   = enviar_email($this, $email_to, $email_subject, $email_message);
                }//end if
            }//end foreach
        }//end if
        /* REPORTE NRO. 02 QUE CORRESPONDE AL REGISTRO DE MATRICULAS - FIN */
        $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
        $this->session->set_flashdata('mensaje', RMESSAGE_PROCESSED);
        redirect('servicios/matricula/ver/'.$matr_id_enc);
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
        $data['btn_cancelar']   = 'servicios/matricula';
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();

        $this->layout->view('servicios/matricula_form', $data);
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
        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->matricula_model->deleteMatriculaByID($matr_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('servicios/matricula');
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

        $matr_id = ($matr_id_enc === '') ? '' : str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('moda_id', 'Modalidad', 'required|trim');
        $this->form_validation->set_rules('carr_id', 'Carrera', 'required|trim');
        $this->form_validation->set_rules('alum_id', 'Alumno', 'required|trim');
        $this->form_validation->set_rules('lipe_id', 'Lista de precios', 'required|trim');
        $this->form_validation->set_rules('matr_horareal', 'Horas programa', 'required|trim');
        $this->form_validation->set_rules('gmat_id', 'Grupo inicio', 'required|trim');
        $this->form_validation->set_rules('matr_codigo', 'Código matrícula', 'required|trim');
        $this->form_validation->set_rules('matr_fecha_proceso', 'Fecha proceso', 'required|trim');
        $this->form_validation->set_rules('matr_observacion', 'Observaciones', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($matr_id === '')?'nuevo':'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_carr']      = $this->carrera_model->getCarreraAll();
        $data['data_alum']      = $this->alumno_model->getAlumnoAll();
        $data['data_moda']      = $this->modalidad_model->getModalidadAll();
        $data['data_gmat']      = $this->grupomatricula_model->getGrupomatriculaByCARRMODA(isset($data['data_matr'])?$data['data_matr']->carr_id:0, isset($data['data_matr'])?$data['data_matr']->moda_id:0);
        $data['data_lipe']      = $this->listaprecio_model->getListaprecioAll();
        $data['data_cxma']      = $this->conceptosxmatricula_model->getConceptosxMatriculaByMATR($matr_id);
        $data['data_emat']      = $this->estadomatricula_model->getEstadoMatriculaAll();

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/matricula';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/matricula_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $arr_cxma_id          = $datapost['cxma_id'];
            $arr_cmat_id          = $datapost['cmat_id'];
            $arr_cmat_costo       = $datapost['cmat_costo'];
            $arr_cxma_costofinal  = $datapost['cxma_costofinal'];
            $arr_cmat_obligatorio = $datapost['cmat_obligatorio'];
            $matr_costoreal       = 0;
            $matr_costofinal      = 0;
            $data_cxma_insert     = array();
            $data_cxma_update     = array();
            foreach ($arr_cmat_id as $item => $cmat_id) {
              if($arr_cmat_obligatorio[$item] === '1'){

                $matr_costoreal     += (float)$arr_cmat_costo[$item];
                $matr_costofinal    += (float)$arr_cxma_costofinal[$item];

                if($arr_cxma_id[$item] === '0'){
                  $data_cxma_insert[] = array(
                      'cxma_costoreal'      => (float)$arr_cmat_costo[$item],
                      'cxma_costofinal'     => (float)$arr_cxma_costofinal[$item],
                      'cmat_id'             => $cmat_id
                  );
                }else{
                  $data_cxma_update[] = array(
                      'cxma_id'             => $arr_cxma_id[$item],
                      'cxma_costoreal'      => (float)$arr_cmat_costo[$item],
                      'cxma_costofinal'     => (float)$arr_cxma_costofinal[$item],
                      'cmat_id'             => $cmat_id,
                      'matr_id'             => $matr_id
                  );
                }

              }//end if
            }//end foreach

            $data_matr = array(
                'matr_codigo'         => $datapost['matr_codigo'],
                'matr_fecha_proceso'  => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['matr_fecha_proceso']))),
                'matr_horareal'       => $datapost['matr_horareal'],
                'matr_costoreal'      => $matr_costoreal,
                'matr_costofinal'     => $matr_costofinal,
                'matr_observacion'    => $datapost['matr_observacion'],
                'carr_id'             => $datapost['carr_id'],
                'alum_id'             => $datapost['alum_id'],
                'gmat_id'             => $datapost['gmat_id'],
                'lipe_id'             => $datapost['lipe_id'],
                'ofic_id'             => $this->session->userdata('ofic_id')
            );
            if($matr_id === ''){
                /* Estado 1: por aprobar (POR DEFECTO) */
                $data_matr['emat_id'] = 1;
            }

            $data_response = ($matr_id === '') ? $this->matricula_model->insertMatricula($data_matr) : $this->matricula_model->updateMatricula($data_matr, $matr_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($matr_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));

                $funcion_request = 'ver';
                if($matr_id === ''){
                  /*1.- INSERTAR */
                    /* 1.1- REGISTRAR ESTADO MATRICULA */
                    $matr_id = $data_response;
                    $data_exma = array(
                            'exma_fecha_movimiento' => date('Y-m-d'),
                            'matr_id'               => $matr_id
                        );
                    registrar_estado_matricula($this, $data_exma, '01');
                    /* 1.1- REGISTRAR ESTADO MATRICULA - END */

                    /* 1.2.- REGISTRAR ESTADO ALUMNO */
                    $data_exal = array(
                            'exal_fecha_movimiento' => date('Y-m-d'),
                            'alum_id'               => $datapost['alum_id']
                        );
                    registrar_estado_alumno($this, $data_exal, '02');
                    /* 1.2.- REGISTRAR ESTADO ALUMNO - END */

                    /* 1.3.- CONCEPTOS X MATRICULA */
                    foreach ($data_cxma_insert as $key => $value) {
                      $data_cxma_insert[$key]['matr_id'] = $matr_id;
                    }//end foreach
                    $this->conceptosxmatricula_model->insertConceptosxMatriculaByGROUP($data_cxma_insert);
                    /* 1.3.- CONCEPTOS X MATRICULA - END */

                    /* 1.4.- REQUISITOS X ALUMNO */
                    $data_rxca = $this->requisitosxcarrera_model->getRequisitosxcarreraByCARR($data_matr['carr_id']);
                    if(isset($data_rxca)){
                      $data_rxal = array();
                      foreach ($data_rxca as $rxca) {
                        $data_rxal[] = array(
                            'matr_id' => $matr_id,
                            'rxca_id' => $rxca->rxca_id
                          );
                      }//endforeach
                      $this->requisitosxalumno_model->insertRequisitosxalumnoByGROUP($data_rxal);
                    }
                    /* 1.4.- REQUISITOS X ALUMNO - END */

                    /* 1.5.- FINANCIAMIENTO MATRICULA - 1CUOTA */
                    $data_fima = array(
                        'fima_monto'            => $matr_costofinal,
                        'fima_fecha_programada' => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['matr_fecha_proceso']))),
                        'matr_id'               => $matr_id
                    );
                    $this->financiamiento_model->insertFinanciamiento($data_fima);
                    /* 1.5.- FINANCIAMIENTO MATRICULA - 1CUOTA - END */

                    $funcion_request = 'confirmarreporte';
                }else{
                  /* 2.- ACTUALIZAR */
                    /* 2.1.- CONCEPTOS X MATRICULA */
                    $this->conceptosxmatricula_model->updateConceptosxMatriculaByGROUP($data_cxma_update);
                    /* 2.1.- CONCEPTOS X MATRICULA -END */
                }

                redirect('servicios/matricula/'.$funcion_request.'/'.str_encrypt($matr_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/matricula_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


    public function verificadisponibilidad_ajax($moda_id = '', $carr_id = '', $alum_id = '', $lipe_id = ''){

      $matr_existe = 0;
      $data_matr = $this->matricula_model->getMatriculaByCARRALUM($carr_id, $alum_id);
      if(isset($data_matr)){
        if(count($data_matr) > 0){
          $matr_existe = 1;
        }//end if
      }//end if
      $data_gmat = $this->grupomatricula_model->getGrupomatriculaByCARRMODA($carr_id, $moda_id);
      $data_mxca = $this->modalidadxcurso_model->getModalidadxcursoByMODAIDLIPEIDCARRID(array('moda_id' => $moda_id, 'lipe_id' => $lipe_id, 'carr_id' => $carr_id));
      $data_cmat = $this->conceptosmatricula_model->getConceptosMatriculaByLIPE($lipe_id);

      $carr_precio  = 0;
      $carr_horas   = date('H:i:s', strtotime('00:00:00'));
      if(isset($data_mxca)){
        foreach ($data_mxca as $item => $mxca) {
          $carr_horas     = procesar_horas('suma', $carr_horas, $mxca->mxca_horas);
          //$carr_precio    += $mxca->mxca_precio;
        }//end foreach
      }//end if

      $data_modu = $this->modulosxcarrera_model->getModulosxcarreraByCARRID($carr_id);
      if(isset($data_modu)){
        foreach ($data_modu as $item => $modu) {
          $carr_precio += $modu->modu_costo;
        }//end foreach
      }//end if

      $data_result = array(
          'matr_existe'   => $matr_existe,
          'carr_precio'   => $carr_precio,
          'carr_horas'    => $carr_horas,
          'data_gmat'     => $data_gmat,
          'data_cmat'     => $data_cmat
        );
      echo json_encode($data_result);
    }


    public function imprimir($matr_id_enc = ''){
      $data['OFICINAS']       = self::$OFICINAS;
      $data['ROLES']          = self::$ROLES;
      $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
      $data['PERMISOS']       = self::$PERMISOS;

      $matr_id = ($matr_id_enc === '') ? '' : str_decrypt($matr_id_enc, KEY_ENCRYPT);
      $fontsize = 9;
      $this->load->library('excel');
      $this->load->library('factory');

      $data_matr      = $this->matricula_model->getMatriculaByID($matr_id);

      $data_alum      = $this->alumno_model->getAlumnoByID($data_matr->alum_id);
      $data_carr      = $this->carrera_model->getCarreraByID($data_matr->carr_id);
      $data_moda      = $this->modalidad_model->getModalidadByID($data_matr->moda_id);
      $data_ofic      = $this->oficina_model->getOficinaByID($data_matr->ofic_id);
      $data_txal      = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM($data_matr->alum_id);
      $data_opte      = $this->operadortelefono_model->getOperadortelefonoAll();
      $data_cxal      = $this->certificadosxalumno_model->getCertificadosxalumnoAll($data_matr->alum_id);
      $data_cele      = $this->certificadoslegales_model->getCertificadoslegalesAll();
      $data_apoa      = $this->apoderadoxalumno_model->getApoderadoxalumnoByALUM($data_matr->alum_id);

     /** Error reporting */
      error_reporting(E_ALL);
      ini_set('display_errors', TRUE);
      ini_set('display_startup_errors', TRUE);

      $PHPEXCEL_BORDER_1111 = array(
          'borders' => array(
            'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
      );

      $PHPEXCEL_BORDER_0101 = array(
        'borders' => array(
          'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
      );

      $PHPEXCEL_BORDER_1101 = array(
        'borders' => array(
          'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
      );

      $PHPEXCEL_BORDER_1000 = array(
        'borders' => array(
          'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
      );

      $PHPEXCEL_BORDER_0001 = array(
        'borders' => array(
          'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
      );

      $BGStyle =  array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'CDCDCD')
        )
      );
      $UStyle = array(
        'font' => array(
          'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
        )
      );


      // Set document properties
      $this->excel->getProperties()->setCreator(" ")
                                   ->setLastModifiedBy(" ")
                                   ->setTitle("Ficha de Inscripción")
                                   ->setSubject("")
                                   ->setDescription("")
                                   ->setKeywords("")
                                   ->setCategory("");

      $objDrawing = new PHPExcel_Worksheet_Drawing();
      $objDrawing->setName("name");
      $objDrawing->setDescription("Description");
      $objDrawing->setPath('public/assets/img/logo/logo_145x166.png');
      $objDrawing->setHeight(120);
      $objDrawing->setCoordinates('A1');
      $objDrawing->setWorksheet($this->excel->setActiveSheetIndex(0));

      $ruta_imagen = ($data_alum->alum_ruta_imagen != '' && $data_alum->alum_ruta_imagen != NULL) ? IMG_PATH.'alumnos/'.$data_alum->alum_ruta_imagen : '';
      if($ruta_imagen != ''){
        $objDrawing_user = new PHPExcel_Worksheet_Drawing();
        $objDrawing_user->setName("name");
        $objDrawing_user->setDescription("Description");
        $objDrawing_user->setPath($ruta_imagen);
        $objDrawing_user->setHeight(110);
        $objDrawing_user->setCoordinates('I1');
        $objDrawing_user->setWorksheet($this->excel->setActiveSheetIndex(0));

      $this->excel->setActiveSheetIndex(0)
                  ->mergeCells('I1:J1');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('I1:J1')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      }//end if

/*      $this->excel->setActiveSheetIndex(0)
                      ->mergeCells('N1:O1');*/
      // Add some data
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('B3', "FICHA DE INSCRIPCIÓN");
      $this->excel->setActiveSheetIndex(0)
                  ->mergeCells('B3:H3');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('B3:H3')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('B3:H3')
                  ->getFont()->setSize(13)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('B4', 'ALUMNOS')
                  ->setCellValue('A9', ' DATOS DEL CURSO');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('B4')
                  ->getFont()->setSize(13)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A9')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->mergeCells('B4:H4')
                  ->mergeCells('A9:C9')
                  ->getStyle('A9:C9')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('B4:H4')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A9')
                  ->applyFromArray($UStyle);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H9', 'N°');
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('I9', $data_matr->matr_codigo)
                  ->mergeCells('I9:J9')
                  ->getStyle('I9')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      
      
      //DATOS DEL CURSO
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A10', ' PROGRAMA')
                  ->mergeCells('A10:B10');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A10:B10')
                  ->getFont()->setSize($fontsize)
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A10:B10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C10', 'PP')
                  ->getStyle('C10')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($data_carr->carr_codigo === 'SC-PPL'):
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('C10')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('D10', 'PC')
                  ->getStyle('D10')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($data_carr->carr_codigo === 'SC-CPL'):
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('D10')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('E10', 'CFI')
                  ->getStyle('E10')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($data_carr->carr_codigo === 'SC-TDI'):
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('E10')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('E10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('F10', 'EOV')
                  ->getStyle('F10')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($data_carr->carr_codigo === 'SC-DV'):
        $this->excel->setActiveSheetIndex(0)
                    ->getStyle('F10')->applyFromArray($BGStyle);
      endif;


      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('F10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('G10', 'RAP')
                  ->getStyle('G10')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('G10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H10', '61')
                  ->getStyle('H10')
                  ->getFont()->setSize($fontsize)->setBold(true);
      //----------
      if($data_moda->moda_descripcion === 'RAP 61'):
        $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H10')->applyFromArray($BGStyle);
      endif;
      //----------
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('I10', '141')
                  ->getStyle('I10')
                  ->getFont()->setSize($fontsize)->setBold(true);
      //----------
      if($data_moda->moda_descripcion === 'RAP 141'):
        $this->excel->setActiveSheetIndex(0)
                  ->getStyle('I10')->applyFromArray($BGStyle);
      endif;
      //----------
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('I10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('J10', '')
                  ->getStyle('J10')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('J10')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A11', ' CONVALIDACION')
                  ->mergeCells('A11:B11')
                  ->getStyle('A11:B11')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A11:B11')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C11', 'SI')
                  ->getStyle('C11')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C11')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('D11', 'NO')
                  ->getStyle('D11')
                  ->getFont()->setSize($fontsize)->setBold(true);
      //----------
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D11')
                  ->getFont()->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D11')->applyFromArray($BGStyle);
      //----------
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D11')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('E11', ' PAIS DE EXPEDICIÓN')
                  ->mergeCells('E11:G11')
                  ->getStyle('E11:G11')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('E11:G11')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H11', ' ')
                  ->mergeCells('H11:J11')
                  ->getStyle('H11:J11')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H11')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A12', '')
                  ->mergeCells('A12:D12')
                  ->getStyle('A12:D12')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A12:D12')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('E12', ' ESCUELA')
                  ->mergeCells('E12:G12')
                  ->getStyle('E12:G12')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('E12:G12')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H12', '')
                  ->mergeCells('H12:J12')
                  ->getStyle('H12:J12')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H12')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);


      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A13', ' DURACION: '.$data_matr->matr_horareal.' (Horas)')
                  ->mergeCells('A13:B13')
                  ->getStyle('A13:B13')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A13:B13')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C13', ' INICIO: '.date('d/m/Y', strtotime($data_matr->gmat_fecha_inicio)))
                  ->mergeCells('C13:G13')
                  ->getStyle('C13:G13')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C13:G13')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H13', ' FIN')
                  ->mergeCells('H13:J13')
                  ->getStyle('H13:J13')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H13')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);


      //DATOS DEL ALUMNO
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A15', ' DATOS DEL ALUMNO')
                  ->mergeCells('A15:B15')
                  ->getStyle('A15:B15')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A15')
                  ->applyFromArray($UStyle);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A15:C15')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A16', ' APELLIDOS')
                  ->mergeCells('A16:B16')
                  ->getStyle('A16:B16')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A16:B16')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C16', ' '.$data_alum->alum_apellido)
                  ->mergeCells('C16:J16')
                  ->getStyle('C16')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C16')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A17', ' NOMBRES')
                  ->mergeCells('A17:B17')
                  ->getStyle('A17:B17')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A17:B17')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C17', ' '.$data_alum->alum_nombre)
                  ->mergeCells('C17:J17')
                  ->getStyle('C17')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C17')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A18', ' FECHA DE NACIMIENTO')
                  ->mergeCells('A18:B18')
                  ->getStyle('A18:B18')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A18:B18')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C18', ' '.date('d/m/Y', strtotime($data_alum->alum_fecha_nacimiento)))
                  ->mergeCells('C18:J18')
                  ->getStyle('C18')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C18')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A19', ' LUGAR DE NACIMIENTO')
                  ->mergeCells('A19:B19')
                  ->getStyle('A19:B19')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A19:B19')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C19', ' '.$data_alum->alum_lugar_nacimiento)
                  ->mergeCells('C19:J19')
                  ->getStyle('C19')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C19')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A20', ' DOCUMENTO DE IDENTIDAD')
                  ->mergeCells('A20:B20')
                  ->getStyle('A20:B20')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A20:B20')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C20', ' '.$data_alum->alum_numero_documento)
                  ->mergeCells('C20:J20')
                  ->getStyle('C20')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C20')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A21', ' SEGURO MÉDICO')
                  ->mergeCells('A21:B21')
                  ->getStyle('A21:B21')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A21:B21')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C21', ' '.$data_alum->alum_seguro)
                  ->mergeCells('C21:J21')
                  ->getStyle('C21')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C21')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A22', ' DIRECCIÓN')
                  ->mergeCells('A22:B22')
                  ->getStyle('A22:B22')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A22:B22')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C22', ' '.$data_alum->alum_direccion.' '.$data_alum->dist_descripcion.' '.$data_alum->prov_descripcion.' '.$data_alum->depa_descripcion)
                  ->mergeCells('C22:J22')
                  ->getStyle('C22')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C22')
                  ->getFont()->setSize($fontsize);

//
//1->Telefónica
//3->Claro
//4->Entel
//5->Fijo
$telf_1 = '';
$telf_3 = '';
$telf_4 = '';
$telf_5 = '';
if(isset($data_txal)){
  if(count($data_txal) > 0){
    foreach ($data_txal as $txal){
      switch ($txal->opte_id) {
        case '1': $telf_1 = $txal->txal_numero; break;
        case '3': $telf_3 = $txal->txal_numero; break;
        case '4': $telf_4 = $txal->txal_numero; break;
        case '5': $telf_5 = $txal->txal_numero; break;
      }
    }//end foreach
  }//end if
}//end if
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A23', ' TELÉFONO FIJO')
                  ->mergeCells('A23:B23')
                  ->getStyle('A23:B23')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A23:B23')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C23', ' '.$telf_5)
                  ->mergeCells('C23:E23')
                  ->getStyle('C23')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C23')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('F23', ' ENTEL')
                  ->mergeCells('F23:G23')
                  ->getStyle('F23')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('F23:G23')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H23', ' '.$telf_4)
                  ->mergeCells('H23:J23')
                  ->getStyle('H23')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H23')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A24', ' TELÉFONO MÓVIL (RPC)')
                  ->mergeCells('A24:B24')
                  ->getStyle('A24:B24')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A24:B24')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C24', ' '.$telf_3)
                  ->mergeCells('C24:E24')
                  ->getStyle('C24')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C24')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('F24', ' RPM')
                  ->mergeCells('F24:G24')
                  ->getStyle('F24')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('F24:G24')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H24', ' '.$telf_1)
                  ->mergeCells('H24:J24')
                  ->getStyle('H24')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H24')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A25', ' EMAIL')
                  ->mergeCells('A25:B25')
                  ->getStyle('A25:B25')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A25:B25')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C25', ' '.$data_alum->alum_email)
                  ->mergeCells('C25:J25')
                  ->getStyle('C25')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C25')
                  ->getFont()->setSize($fontsize);


/*
#  Autorización especial DGAC             cele_id:20
#  Certificado de 5to de Secundaria         cele_id:1
#  Certificado de estudios              cele_id:9
#  Certificado de Inscripción             cele_id:4
#  Certificado Médico Clase I (12 meses)        cele_id:10
#  Certificado Médico Clase I (6 meses)       cele_id:11
#  Certificado Médico Clase II (12 meses)       cele_id:12
#  Certificado Médico Clase II (6 meses)        cele_id:13
#  Certificado Médico Clase III (12 meses)      cele_id:14
#  Certificado Médico Clase III (6 meses)       cele_id:15
#  Constancia de adoctrinamiento            cele_id:8
#  Constancia de entrega de material de estudio   cele_id:5
#  Declaración Jurada de no tener antecedentes    cele_id:7
#  Licencia Alumno Piloto               cele_id:16
#  Licencia Instructor de Vuelo           cele_id:19
#  Licencia Piloto Comercial              cele_id:18
#  Licencia Piloto Privado              cele_id:17
#  Requisito de compentencia lingüística        cele_id:6
*/

$ap = false;
$pp = false;
$pc = false;

$cele_descripcion = '';
$cxal_fecha_vencimiento = '';

if(isset($data_cxal)){
  foreach ($data_cxal as $cxal) {
    switch ($cxal->cele_id){
      case '16': $ap = true; break;
      case '17': $pp = true; break;
      case '18': $pc = true; break;

      case '10': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
      case '11': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
      case '12': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
      case '13': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
      case '14': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
      case '15': 
      $cele_descripcion = $cxal->cele_descripcion;
      $cxal_fecha_vencimiento = $cxal->cxal_fecha_vencimiento;
      break;
    }
  }//end foreach
}//end if
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A26', ' LICENCIAS')
                  ->mergeCells('A26:B26');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A26:B26')
                  ->getFont()->setSize($fontsize)
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A26:B26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C26', 'AP')
                  ->getStyle('C26')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($ap):
        $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C26')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('D26', 'PP')
                  ->getStyle('D26')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($pp):
        $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D26')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('D26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('E26', 'PC')
                  ->getStyle('E26')
                  ->getFont()->setSize($fontsize)->setBold(true);

      if($pc):
        $this->excel->setActiveSheetIndex(0)
                  ->getStyle('E26')->applyFromArray($BGStyle);
      endif;

      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('E26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('F26', 'EOV')
                  ->getStyle('F26')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('F26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111)
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('G26', ' NUMERO')
                  ->getStyle('G26')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('G26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H26', '')
                  ->mergeCells('H26:J26');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H26')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H26')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A27', ' APTO MÉDICO')
                  ->mergeCells('A27:B27');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A27:B27')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A27:B27')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C27', ' '.$cele_descripcion)
                  ->mergeCells('C27:F27');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C27:F27')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C27')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('G27', ' VENCIM.')
                  ->getStyle('G27')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('G27')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('H27', ' '.(($cxal_fecha_vencimiento != '' && $cxal_fecha_vencimiento != null) ? date('d/m/Y', strtotime($cxal_fecha_vencimiento)) : ''))
                  ->mergeCells('H27:J27');
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H27')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('H27')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A28', ' RESTRICCIÓN')
                  ->mergeCells('A28:B28')
                  ->getStyle('A28:B28')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A28:B28')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C28', '')
                  ->mergeCells('C28:J28')
                  ->getStyle('C28')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);


$apoa_apellido = '';
$apoa_nombre = '';
$apoa_direccion = '';
$apoa_telefijo = '';
$apoa_telemovil = '';
$principal = 0;

$apoa_apellido_ = '';
$apoa_nombre_ = '';
$apoa_direccion_ = '';
$apoa_telefijo_ = '';
$apoa_telemovil_ = '';
if(isset($data_apoa)){
  foreach ($data_apoa as $apoa) {

    if($principal === 0){
      $apoa_apellido = $apoa->apoa_apellido;
      $apoa_nombre = $apoa->apoa_nombre;
      $apoa_direccion = $apoa->apoa_direccion;
      $apoa_telefijo = $apoa->apoa_telefijo;
      $apoa_telemovil = $apoa->apoa_telemovil;
    }//end if

    if($apoa->apoa_principal === 'S'){
      $apoa_apellido_ = $apoa->apoa_apellido;
      $apoa_nombre_ = $apoa->apoa_nombre;
      $apoa_direccion_ = $apoa->apoa_direccion;
      $apoa_telefijo_ = $apoa->apoa_telefijo;
      $apoa_telemovil_ = $apoa->apoa_telemovil;

      //$principal = 1;
    }//end if
  }//end foreach
}//end if
      //DATOS DEL APODERADO
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A30', ' DATOS DEL APODERADO')
                  ->mergeCells('A30:B30')
                  ->getStyle('A30:B30')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A30')
                  ->applyFromArray($UStyle);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A30:B30')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A31', ' APELLIDOS')
                  ->mergeCells('A31:B31')
                  ->getStyle('A31:B31')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A31:B31')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C31', ' '.$apoa_apellido)
                  ->mergeCells('C31:J31')
                  ->getStyle('C31')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C31:J31')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A32', ' NOMBRES')
                  ->mergeCells('A32:B32')
                  ->getStyle('A32:B32')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A32:B32')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C32', ' '.$apoa_nombre)
                  ->mergeCells('C32:J32')
                  ->getStyle('C32')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C32:J32')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A33', ' DIRECCIÓN')
                  ->mergeCells('A33:B33')
                  ->getStyle('A33:B33')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A33:B33')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C33', ' '.$apoa_direccion)
                  ->mergeCells('C33:J33')
                  ->getStyle('C33')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C33:J33')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A34', ' TELÉFONO FIJO')
                  ->mergeCells('A34:B34')
                  ->getStyle('A34:B34')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A34:B34')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C34', ' '.$apoa_telefijo)
                  ->mergeCells('C34:J34')
                  ->getStyle('C34')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C34:J34')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A35', ' TELÉFONO MÓVIL')
                  ->mergeCells('A35:B35')
                  ->getStyle('A35:B35')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A35:B35')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C35', ' '.$apoa_telemovil)
                  ->mergeCells('C35:J35')
                  ->getStyle('C35')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C35:J35')
                  ->getFont()->setSize($fontsize);


      //CONTACTO EN CASO DE EMERGENCIA
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A37', ' CONTACTO EN CASO DE EMERGENCIA')
                  ->mergeCells('A37:C37')
                  ->getStyle('A37:C37')
                  ->getFont()->setSize($fontsize)->setBold(true);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A37')
                  ->applyFromArray($UStyle);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A37:C37')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)       
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A38', ' APELLIDOS')
                  ->mergeCells('A38:B38')
                  ->getStyle('A38:B38')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A38:B38')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C38', ' '.$apoa_apellido_)
                  ->mergeCells('C38:J38')
                  ->getStyle('C38')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C38:J38')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A39', ' NOMBRES')
                  ->mergeCells('A39:B39')
                  ->getStyle('A39:B39')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A39:B39')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C39', ' '.$apoa_nombre_)
                  ->mergeCells('C39:J39')
                  ->getStyle('C39')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C39:J39')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A40', ' TELÉFONO FIJO')
                  ->mergeCells('A40:B40')
                  ->getStyle('A40:B40')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A40:B40')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C40', ' '.$apoa_telefijo_)
                  ->mergeCells('C40:J40')
                  ->getStyle('C40')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C40:J40')
                  ->getFont()->setSize($fontsize);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A41', ' TELÉFONO MÓVIL')
                  ->mergeCells('A41:B41')
                  ->getStyle('A41:B41')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A41:B41')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('C41', ' '.$apoa_telemovil_)
                  ->mergeCells('C41:J41')
                  ->getStyle('C41')
                  ->applyFromArray($PHPEXCEL_BORDER_1111);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('C41:J41')
                  ->getFont()->setSize($fontsize);


      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A43', ' '.strtoupper($data_ofic->ofic_direccion).' '.date('d/m/Y', strtotime($data_matr->matr_fecha_proceso)).' ')
                  ->mergeCells('A43:J43')
                  ->getStyle('A43:J43')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A43:J43')
                  ->getAlignment()
                  ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
                  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A49', ' ALUMNO O APODERADO ')
                  ->mergeCells('A49:B49')
                  ->getStyle('A49:B49')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('A49:B49')
                  ->applyFromArray($PHPEXCEL_BORDER_1000);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('G49', ' DEPARTAMENTO DE ADMISIÓN ')
                  ->mergeCells('G49:J49')
                  ->getStyle('G49:J49')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->getStyle('G49:J49')
                  ->applyFromArray($PHPEXCEL_BORDER_1000);

      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('A50', ' DNI N° '.$data_alum->alum_numero_documento)
                  ->mergeCells('A50:D50')
                  ->getStyle('A50:D50')
                  ->getFont()->setSize($fontsize);
      $this->excel->setActiveSheetIndex(0)
                  ->setCellValue('G50', ' Fecha y hora impresión: '.date('d/m/Y H:i'))
                  ->mergeCells('G50:J50')
                  ->getStyle('G50:J50')
                  ->getFont()->setSize($fontsize);

        // $this->excel->getActiveSheet()
        //             ->getPageSetup()
        //             ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()
                    ->getPageSetup()
                    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
       
 
        $header = array();
 
        $colIndex = PHPExcel_Cell::columnIndexFromString('A');
        $columnLetter = PHPExcel_Cell::stringFromColumnIndex($colIndex-1); 



        $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
        $rendererLibrary = 'dompdf';
        $rendererLibraryPath = APPPATH.'third_party/'. $rendererLibrary.'/src';
        $filename='Reporte_1'.'.pdf'; 
        PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

        $objWriter = $this->factory->createWriter($this->excel, 'PDF'); 
        $objWriter->setSheetIndex(0);
        //print_r($objWriter);die();
        $objWriter->save('php://output');
        exit;

/*        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_1.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = $this->factory->createWriter($this->excel, 'Excel2007');
        //print_r($objWriter);
        $objWriter->save('php://output');*/

    }


}