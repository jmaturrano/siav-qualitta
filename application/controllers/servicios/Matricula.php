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
                'lipe_id'             => $datapost['lipe_id']
            );
            if($matr_id === ''){
                /* Estado 1: por aprobar (POR DEFECTO) */
                $data_matr['emat_id'] = 1;
            }

            $data_response = ($matr_id === '') ? $this->matricula_model->insertMatricula($data_matr) : $this->matricula_model->updateMatricula($data_matr, $matr_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($matr_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));

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

                }else{
                  /* 2.- ACTUALIZAR */
                    /* 2.1.- CONCEPTOS X MATRICULA */
                    $this->conceptosxmatricula_model->updateConceptosxMatriculaByGROUP($data_cxma_update);
                    /* 2.1.- CONCEPTOS X MATRICULA -END */
                }

                redirect('servicios/matricula/ver/'.str_encrypt($matr_id, KEY_ENCRYPT));
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


}