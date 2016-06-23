<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Oficinausuario extends CI_Controller {
    private static $header_title  = 'Oficina por Usuario';
    private static $header_icon  = ICON_SETTINGS;

    public function __construct() {
        parent :: __construct();
        (!$this->session->userdata('usua_id')) ? redirect('seguridad/login') : '';
        $this->load->model('seguridad/oficinausuario_model');
        $this->load->model('seguridad/rolusuario_model');
    }



    public function asignaroficina($usua_id_enc = '', $uxof_id_enc = '') {
        $usua_id = ($usua_id_enc === '') ? redirect('seguridad/usuario') : str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $uxof_id = ($uxof_id_enc === '') ? '' : str_decrypt($uxof_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('ofic_id', 'Oficina', 'required');
        $this->form_validation->set_rules('uxof_estadodefecto', 'Oficina por defecto', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }else{
            $datapost   = $this->security->xss_clean($this->input->post());
            $data_uxof  = array(
                'ofic_id'                 => $datapost['ofic_id'],
                'uxof_estadodefecto'      => $datapost['uxof_estadodefecto'],
                'usua_id'                 => $usua_id
            );
            if($datapost['uxof_estadodefecto'] === 'S'){
                $this->oficinausuario_model->updateOficinausuarioAll(array('uxof_estadodefecto'=>'N'), $usua_id);
            }
            $data_response = ($uxof_id === '') ? $this->oficinausuario_model->insertOficinausuario($data_uxof) : $this->oficinausuario_model->updateOficinausuario($data_uxof, $uxof_id);
            if($data_response){
                //verificar oficina x usuario por defecto
                $this->verificaruxofdefecto($usua_id);

                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', ($uxof_id === '') ? RMESSAGE_ASSIGNED : RMESSAGE_ASSIGNEDUP);
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('seguridad/usuario/ver/'.$usua_id_enc);

    }

    public function verificaruxofdefecto($usua_id){
        $data_uxofx = $this->oficinausuario_model->getOficinausuarioAll($usua_id);
        $cuenta_uxofdef = 0;
        if(isset($data_uxofx)){
            if(count($data_uxofx) > 0){
                foreach ($data_uxofx as $item => $oficinausuario) {
                    if($oficinausuario->uxof_estadodefecto === 'S'){
                        $cuenta_uxofdef++;
                    }//end if
                }//end foreach
                if($cuenta_uxofdef === 0){
                    $this->oficinausuario_model->updateOficinausuario(array('uxof_estadodefecto'=>'S'), $data_uxofx[0]->uxof_id);
                }//end if
            }//end if
        }//end if
        return true;
    }

    public function eliminar($usua_id_enc = '', $uxof_id_enc = ''){
        $usua_id = ($usua_id_enc === '') ? redirect('seguridad/usuario/') : str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $uxof_id = ($uxof_id_enc === '') ? redirect('seguridad/usuario/ver/'.$usua_id_enc) : str_decrypt($uxof_id_enc, KEY_ENCRYPT);

        $data_delete_uxof            = $this->oficinausuario_model->deleteOficinausuarioByID($uxof_id);
        $data_delete_rxus            = $this->rolusuario_model->deleteRolusuarioByOficina($usua_id, $uxof_id);
        if($data_delete_uxof && $data_delete_rxus){
            //verificar oficina x usuario por defecto
            $this->verificaruxofdefecto($usua_id);

            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/usuario/ver/'.$usua_id_enc);
    }


    public function listarAjax(){
        $datapost = $this->security->xss_clean($this->input->post());
        $data_uxof = $this->oficinausuario_model->getOficinausuarioAll($datapost['usua_id']);
        echo json_encode($data_uxof);

    }













}















