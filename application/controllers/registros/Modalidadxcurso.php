<?php
/**
* MK System Soft  
*
* Controlador de Modalidad por curso
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Modalidadxcurso extends CI_Controller {
    private static $header_title  = 'Modalidad por Carrera';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/modalidadxcurso_model');
        $this->load->model('registros/curso_model');
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
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/modalidadxcurso'); 
    }

     /**
      * Método de eliminación
      *
      * Actualiza el estado del registro a inactivo
      *     
      * @param string $moda_id_enc id encriptado del registro
      *
      *   
      * @return void
      */
    public function eliminar($moda_id_enc = ''){
      ($moda_id_enc === '') ? redirect('registros/modalidad') : '';
      $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

      $this->form_validation->set_rules('chk_registro[]', 'Curso', 'required');
      $this->form_validation->set_rules('mxca_id[]', 'Curso', 'required');
      $this->form_validation->set_rules('mxca_horas[]', 'Horas', 'trim');
      $this->form_validation->set_rules('mxca_precio[]', 'Precio', 'trim');
      $this->form_validation->set_rules('mxca_observacion[]', 'Observaciones', 'trim');

      $this->load->library('layout');
      if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
          $this->session->set_flashdata('mensaje', primer_error_validation());
      }
      else
      {
          $datapost = $this->security->xss_clean($this->input->post());
          $data_mxca = array();

          foreach ($datapost['chk_registro'] as $item => $chk_registro) {
            if($chk_registro === '1'){
              $data_mxca[] = $datapost['mxca_id'][$item];
            }
          }//end foreach
          $data_response = $this->modalidadxcurso_model->deleteModalidadxcursoGROUP($data_mxca);
          if($data_response){
              $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
              $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
          }else{
              $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
              $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
          }
      }
      redirect('registros/modalidad/ver/'.$moda_id_enc);
    }

    /**
      * Registro de modalidad por curso
      *
      * Procesa el registro o actualización de uno o varios cursos por modalidad
      *
      * @param string $moda_id_enc id encriptado de la modalidad 
      *
      * @return void
      */
    public function guardar($moda_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        ($moda_id_enc === '') ? redirect('registros/modalidad') : '';
        $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('carr_id', 'Carrera', 'required');
        $this->form_validation->set_rules('modu_id', 'Módulo', 'required');
        if($this->input->post('agregar_curso_todo') === '0'){
          $this->form_validation->set_rules('curs_id', 'Curso', 'required');
        }
        $this->form_validation->set_rules('lipe_id', 'Lista de Precio', 'required');
        $this->form_validation->set_rules('agregar_curso_todo', 'Módulo', 'required');

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_mxca = array();

            if($datapost['agregar_curso_todo'] === '1'){
              //agregar todos los cursos dentro del módulo seleccionado
              $data_curs      = $this->curso_model->getCursoByMODUID($datapost['modu_id']);
              if(isset($data_curs)){
                foreach ($data_curs as $item => $curso) {
                  $data_verifica = $this->modalidadxcurso_model->getModalidadxcursoByCURSIDMODAIDLIPEID($moda_id, $curso->curs_id, $datapost['lipe_id']);
                  if(!isset($data_verifica)){
                    $data_mxca[] = array(
                        'mxca_horas'    => '00:00:00',
                        'mxca_precio'   => 0,
                        'moda_id'       => $moda_id,
                        'curs_id'       => $curso->curs_id,
                        'lipe_id'       => $datapost['lipe_id']
                      );
                  }//end if
                }//end foreach
              }//end if
            }else{
              //agregar un curso seleccionado
              $data_verifica = $this->modalidadxcurso_model->getModalidadxcursoByCURSIDMODAIDLIPEID($moda_id, $datapost['curs_id'], $datapost['lipe_id']);
              if(!isset($data_verifica)){
                $data_mxca[] = array(
                    'mxca_horas'    => strtotime('00:00:00'),
                    'mxca_precio'   => 0,
                    'moda_id'       => $moda_id,
                    'curs_id'       => $datapost['curs_id'],
                    'lipe_id'       => $datapost['lipe_id']
                  );
              }//end if
            }//end else

            $data_response = $this->modalidadxcurso_model->insertModalidadxcursoGROUP($data_mxca);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', RMESSAGE_INSERT);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('registros/modalidad/ver/'.$moda_id_enc);
        $this->load->view('notificacion');
    }



  public function actualizar($moda_id_enc = ''){
      $data['OFICINAS']       = self::$OFICINAS;
      $data['ROLES']          = self::$ROLES;
      $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
      $data['PERMISOS']       = self::$PERMISOS;
      $data['header_title']   = self::$header_title;
      $data['header_icon']    = self::$header_icon;
      ($moda_id_enc === '') ? redirect('registros/modalidad') : '';
      $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

      $this->form_validation->set_rules('chk_registro[]', 'Curso', 'required');
      $this->form_validation->set_rules('mxca_id[]', 'Curso', 'required');
      $this->form_validation->set_rules('mxca_horas[]', 'Horas', 'trim');
      $this->form_validation->set_rules('mxca_precio[]', 'Precio', 'trim');
      $this->form_validation->set_rules('mxca_observacion[]', 'Observaciones', 'trim');

      $this->load->library('layout');
      if ($this->form_validation->run() === FALSE) {
          $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
          $this->session->set_flashdata('mensaje', primer_error_validation());
      }
      else
      {
          $datapost = $this->security->xss_clean($this->input->post());
          $data_mxca = array();

          foreach ($datapost['chk_registro'] as $item => $chk_registro) {
            if($chk_registro === '1'){
              $str_time = str_replace('AM', '', $datapost['mxca_horas'][$item]);
              $str_time = str_replace('PM', '', $str_time);
              $str_time = str_replace(' ', '', $str_time);

              $data_mxca[] = array(
                  'mxca_id'           => $datapost['mxca_id'][$item],
                  'mxca_horas'        => trim($str_time),
                  'mxca_precio'       => $datapost['mxca_precio'][$item],
                  'moda_id'           => $moda_id,
                  'curs_id'           => $datapost['curs_id'][$item],
                  'lipe_id'           => $datapost['lipe_id'][$item],
                  'mxca_observacion'  => $datapost['mxca_observacion'][$item]
                );
            }
          }//end foreach
          $data_response = $this->modalidadxcurso_model->updateModalidadxcursoGROUP($data_mxca);
          if($data_response){
              $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
              $this->session->set_flashdata('mensaje', RMESSAGE_UPDATE);
          }else{
              $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
              $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
          }
      }
      redirect('registros/modalidad/ver/'.$moda_id_enc);
      $this->load->view('notificacion');
  }

  function getModalidadxcurso_ajax($moda_id = '', $modu_id = '', $lipe_id = ''){
        ($moda_id === '') ? exit() : '';
        ($modu_id === '') ? exit() : '';
        ($lipe_id === '') ? exit() : '';
        $data_search = array(
            'moda_id' => $moda_id,
            'modu_id' => $modu_id,
            'lipe_id' => $lipe_id
          );
        $data_mxca      = $this->modalidadxcurso_model->getModalidadxcursoByMODAIDLIPEIDMODUID($data_search);
        echo json_encode($data_mxca);
  }


}