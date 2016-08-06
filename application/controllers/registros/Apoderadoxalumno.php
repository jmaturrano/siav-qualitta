<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apoderadoxalumno extends CI_Controller {
    private static $header_title  = 'Apoderado por Alumno';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/apoderadoxalumno_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/alumno');  
    }

    public function eliminar($alum_id_enc = '', $apoa_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        ($apoa_id_enc === '') ? redirect('registros/alumno/ver/'.$alum_id_enc) : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $apoa_id = str_decrypt($apoa_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->apoderadoxalumno_model->deleteApoderadoxalumnoByID($apoa_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/alumno/ver/'.$alum_id_enc);
    }

    public function agregarapoderado($alum_id_enc = '', $apoa_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $apoa_id = ($apoa_id_enc === '') ? '' : str_decrypt($apoa_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('apoa_nombre', 'Nombres', 'required|trim');
        $this->form_validation->set_rules('apoa_apellido', 'Apellidos', 'required|trim');
        $this->form_validation->set_rules('apoa_direccion', 'Dirección', 'trim');
        $this->form_validation->set_rules('apoa_telefijo', 'Teléfono Fijo', 'trim');
        $this->form_validation->set_rules('apoa_telemovil', 'Teléfono Móvil', 'trim');
        $this->form_validation->set_rules('apoa_email', 'Correo', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_apoa = array(
                'apoa_nombre'               => $datapost['apoa_nombre'],
                'apoa_apellido'             => $datapost['apoa_apellido'],
                'apoa_direccion'            => $datapost['apoa_direccion'],
                'apoa_telefijo'             => $datapost['apoa_telefijo'],
                'apoa_telemovil'            => $datapost['apoa_telemovil'],
                'apoa_email'                => $datapost['apoa_email'],
                'alum_id'                   => $alum_id
            );

            $data_response = ($apoa_id === '') ? $this->apoderadoxalumno_model->insertApoderadoxalumno($data_apoa) : $this->apoderadoxalumno_model->updateApoderadoxalumno($data_apoa, $apoa_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($apoa_id === '') ? RMESSAGE_ASSIGNED : RMESSAGE_ASSIGNEDUP));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('registros/alumno/ver/'.$alum_id_enc);
    }



}































