<?php
/**
* MK System Soft  
*
* Controlador de matricula carrera
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Requisitosxalumno extends CI_Controller {
    private static $header_title  = 'Documentos requisito';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('servicios/requisitosxalumno_model');
        $this->load->model('servicios/matricula_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/requisitosxalumno'); 
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
      redirect('servicios/matricula');
    }

    public function lista($matr_id_enc = '', $offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'servicios/requisitosxalumno/lista/'.$matr_id_enc;
        $propio['filas_totales'] = $this->requisitosxalumno_model->contar_estructuras_todos($matr_id);
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_rxal']      = $this->requisitosxalumno_model->getRequisitosxalumnoAll($matr_id, '', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['btn_regresar']   = 'servicios/matricula/ver/'.$matr_id_enc;
        $this->layout->view('servicios/requisitosxalumno_index', $data);
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
        $data['btn_nuevo']      = 'servicios/requisitosxalumno/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('servicios/matricula');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('servicios/matricula') : '';
            $data['data_rxal']  = $this->matricula_model->getRequisitosxalumnoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('servicios/requisitosxalumno_index', $data);
            $this->load->view('notificacion');
        }
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
    public function guardar($matr_id_enc = '', $rxal_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        ($rxal_id_enc === '') ? redirect('servicios/matricula/ver/'.$matr_id_enc) : '';

        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        $rxal_id = str_decrypt($rxal_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('rxal_cumplido', 'Cumplido', 'required|trim');
        $this->form_validation->set_rules('rxal_observacion', 'Observacion', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            redirect('servicios/requisitosxalumno/lista/'.$matr_id_enc);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_rxal = array(
                'rxal_cumplido'         => ($datapost['rxal_cumplido'] === '1') ? 'S' : 'N',
                'rxal_observacion'      => $datapost['rxal_observacion']
            );

            /*imagen de referencia*/
            if($_FILES['rxal_ruta_imagen']['name']){
                $nombre_imagen              = 'rxal_ruta_imagen';
                $directorio                 = 'requisitosxalumno';
                $request_upload = upload_file_foto($this, $nombre_imagen, $directorio);
                if(isset($request_upload['file_name'])){
                    $data_rxal['rxal_ruta_imagen'] = $request_upload['file_name'];
                }//end if
            }//end if

            $data_response = $this->requisitosxalumno_model->updateRequisitosxalumno($data_rxal, $rxal_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', RMESSAGE_UPDATE);

                redirect('servicios/requisitosxalumno/lista/'.$matr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                redirect('servicios/requisitosxalumno/lista/'.$matr_id_enc);
            }
        }
        $this->load->view('notificacion');
    }



}