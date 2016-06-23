<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Rolusuario extends CI_Controller {
    private static $header_title  = 'Roles por usuario';
    private static $header_icon  = ICON_SETTINGS;

    public function __construct() {
        parent :: __construct();
        (!$this->session->userdata('usua_id')) ? redirect('seguridad/login') : '';
        $this->load->model('seguridad/rolusuario_model');
    }

    public function asignarrol($usua_id_enc = '', $rxus_id_enc = '') {
        $usua_id = ($usua_id_enc === '') ? redirect('seguridad/usuario') : str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $rxus_id = ($rxus_id_enc === '') ? '' : str_decrypt($rxus_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('uxof_id', 'Oficina asignada', 'required');
        $this->form_validation->set_rules('rol_id', 'Rol', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }else{
            $datapost   = $this->security->xss_clean($this->input->post());
            $data_rxus  = array(
                'usua_id'                 => $usua_id,
                'uxof_id'                 => $datapost['uxof_id'],
                'rol_id'                  => $datapost['rol_id']
            );
            $data_response = ($rxus_id === '') ? $this->rolusuario_model->insertRolusuario($data_rxus) : $this->rolusuario_model->updateRolusuario($data_rxus, $rxus_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', ($rxus_id === '')?RMESSAGE_ASSIGNED:RMESSAGE_ASSIGNEDUP);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('seguridad/usuario/ver/'.$usua_id_enc);

    }


    public function eliminar($usua_id_enc = '', $rxus_id_enc = ''){
        $usua_id = ($usua_id_enc === '') ? redirect('seguridad/usuario/') : str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $rxus_id = ($rxus_id_enc === '') ? redirect('seguridad/usuario/ver/'.$usua_id_enc) : str_decrypt($rxus_id_enc, KEY_ENCRYPT);

        $data_delete_rxus            = $this->rolusuario_model->deleteRolusuarioByID($rxus_id);
        if($data_delete_rxus){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/usuario/ver/'.$usua_id_enc);
    }






}































































