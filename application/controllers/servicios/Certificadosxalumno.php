<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadosxalumno extends CI_Controller {
    private static $header_title  = 'Certificados/Constancias';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
  
        $this->load->model('servicios/certificadosxalumno_model');
        $this->load->model('registros/certificadoslegales_model');
        $this->load->model('servicios/matricula_model');
        date_default_timezone_set('America/Lima');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'servicios/certificadosxalumno'); 
    }

    /**
    * Funcion Inicio 
    * Maneja la estructura principal
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
        $propio['ruta_base']    = 'servicios/certificadosxalumno/lista/'.$matr_id_enc;
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $propio['filas_totales']= $this->certificadosxalumno_model->contar_estructuras_todos($data['data_matr']->alum_id);
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_cxal']      = $this->certificadosxalumno_model->getCertificadosxalumnoAll($data['data_matr']->alum_id, '', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        
        $data['btn_regresar']   = 'servicios/matricula/ver/'.$matr_id_enc;
        $data['btn_nuevo']      = 'servicios/certificadosxalumno/nuevo/'.$matr_id_enc;
        $this->layout->view('servicios/certificadosxalumno_index', $data);
        $this->load->view('notificacion');
    }


    /**
    * Funcion ver
    * Maneja opcion de mostrar el dato ingresado
    * otip_id_enc id certificados legales encriptado
    * @return void
    */

    public function ver($matr_id_enc = '', $cxal_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        ($cxal_id_enc === '') ? redirect('servicios/certificadosxalumno/lista/'.$matr_id_enc) : '';
        $cxal_id = str_decrypt($cxal_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';

        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesAll();
        $data['data_cxal']      = $this->certificadosxalumno_model->getCertificadosxalumnoByID($cxal_id);
        $data['btn_editar']     = 'servicios/certificadosxalumno/editar/'.$matr_id_enc.'/'.$cxal_id_enc;
        $data['btn_regresar']   = 'servicios/certificadosxalumno/lista/'.$matr_id_enc;
        $this->layout->view('servicios/certificadosxalumno_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion editar
    * Maneja opcion de editar los datos ingresados
    * otip_id_enc id certificados legales encriptado
    * @return void
    */
    public function editar($matr_id_enc = '', $cxal_id_enc = ''){

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        ($cxal_id_enc === '') ? redirect('servicios/certificadosxalumno/lista/'.$matr_id_enc) : '';
        $cxal_id = str_decrypt($cxal_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';

        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesAll();
        $data['data_cxal']      = $this->certificadosxalumno_model->getCertificadosxalumnoByID($cxal_id);
        $data['btn_guardar']    = true;
        $data['btn_regresar']   = 'servicios/certificadosxalumno/lista/'.$matr_id_enc;
        $this->layout->view('servicios/certificadosxalumno_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion Nuevo
    * Maneja opcion nuevo para ingresar nuevos datos
    * @return void
    */

    public function nuevo($matr_id_enc = ''){

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/certificadosxalumno/lista/'.$matr_id_enc;
        $data['tipo_vista']     = 'nuevo';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_cele']      = $this->certificadoslegales_model->getCertificadoslegalesAll();

        $this->layout->view('servicios/certificadosxalumno_form', $data);
        $this->load->view('notificacion');
    }

    /**
    * Funcion eliminar
    * Maneja opcion de elimnar los datos ingresados
    * otip_id_enc id certificados legales encriptado
    * @return void
    */

    public function eliminar($matr_id_enc = '', $cxal_id_enc = ''){       

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);
        ($cxal_id_enc === '') ? redirect('servicios/certificadosxalumno/lista/'.$matr_id_enc) : '';
        $cxal_id = str_decrypt($cxal_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->certificadosxalumno_model->deleteCertificadosxalumno($cxal_id);
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
        redirect('servicios/certificadosxalumno/lista/'.$matr_id_enc);
    }

    /**
    * Funcion guardar
    * Maneja opcion de guardar los datos ingresados
    * moae_id_enc id certificados legales encriptado
    * @return void
    */
    
    public function guardar($matr_id_enc = '', $cxal_id_enc = ''){

        ($matr_id_enc === '') ? redirect('servicios/matricula') : '';
        $matr_id = str_decrypt($matr_id_enc, KEY_ENCRYPT);

        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $datapost = $this->security->xss_clean($this->input->post());
        $cxal_id = ($cxal_id_enc === '') ? '' : str_decrypt($cxal_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('cxal_fecha_vencimiento', 'Fecha de vencimiento','trim');
        $this->form_validation->set_rules('cele_id', 'Certificado/Constancia','required|trim');
        
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($cxal_id === '')?'nuevo':'editar';
        $data['data_matr']      = $this->matricula_model->getMatriculaByID($matr_id);
        $data['data_cxal']      = $this->certificadosxalumno_model->getCertificadosxalumnoByID($cxal_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'servicios/certificadosxalumno/lista/'.$matr_id_enc;
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('servicios/certificadosxalumno_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_cxal = array(
                'cxal_fecha_vencimiento'    => (isset($datapost['cxal_fecha_vencimiento']) && $datapost['cxal_fecha_vencimiento']!= '') ? date('Y-m-d', strtotime(str_replace('/', '-', $datapost['cxal_fecha_vencimiento']))) : null,
                'cele_id'                   => $datapost['cele_id'],
                'alum_id'                   => $data['data_matr']->alum_id
                );
            $data_response = ($cxal_id === '') ? $this->certificadosxalumno_model->insertCertificadosxalumno($data_cxal) : $this->certificadosxalumno_model->updateCertificadosxalumno($data_cxal, $cxal_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($cxal_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('servicios/certificadosxalumno/lista/'.$matr_id_enc);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('servicios/certificadosxalumno_form', $data);
            }
        }
        $this->load->view('notificacion');
    }











































































































































}































































