<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportexusuario extends CI_Controller {
    private static $header_title  = 'Reportes por usuario';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/reportexusuario_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/reportesmail');  
    }


    public function eliminar($rema_id_enc = '', $rexu_id_enc = ''){
        ($rema_id_enc === '') ? redirect('seguridad/reportesmail') : '';
        ($rexu_id_enc === '') ? redirect('seguridad/reportesmail') : '';
        $rema_id = str_decrypt($rema_id_enc, KEY_ENCRYPT);
        $rexu_id = str_decrypt($rexu_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->reportexusuario_model->deleteReportexusuarioByID($rexu_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/reportesmail/ver/'.str_encrypt($rema_id, KEY_ENCRYPT));
    }

    public function guardar($rema_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;

        $rema_id = ($rema_id_enc === '') ? redirect('seguridad/reportesmail') : str_decrypt($rema_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('usua_id', 'Usuario', 'required|trim');

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_rexu = array(
                'rema_id'               => $rema_id,
                'usua_id'               => $datapost['usua_id']
            );

            $data_response = $this->reportexusuario_model->insertReportexusuario($data_rexu);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', 'Item agregado correctamente');
                $rexu_id = $data_response;
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('seguridad/reportesmail/ver/'.str_encrypt($rema_id, KEY_ENCRYPT));
        $this->load->view('notificacion');
    }
   


}































