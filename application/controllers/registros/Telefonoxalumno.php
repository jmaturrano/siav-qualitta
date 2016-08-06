<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telefonoxalumno extends CI_Controller {
    private static $header_title  = 'Teléfonos por Alumno';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/telefonoxalumno_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/alumno');  
    }

    public function eliminar($alum_id_enc = '', $txal_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        ($txal_id_enc === '') ? redirect('registros/alumno/ver/'.$alum_id_enc) : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $txal_id = str_decrypt($txal_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->telefonoxalumno_model->deleteTelefonoxalumnoByID($txal_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/alumno/ver/'.$alum_id_enc);
    }

    public function agregartelefono($alum_id_enc = '', $txal_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $txal_id = ($txal_id_enc === '') ? '' : str_decrypt($txal_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('txal_numero', 'Número Teléfono', 'required|trim');
        $this->form_validation->set_rules('opte_id', 'Operador Telefónico', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_txal = array(
                'txal_principal'            => ($datapost['txal_principal'] === '0') ? 'N' : 'S',
                'txal_numero'               => $datapost['txal_numero'],
                'opte_id'                   => $datapost['opte_id'],
                'alum_id'                   => $alum_id
            );

            if($datapost['txal_principal'] === '1'){
                $data_txalx = array(
                        'txal_principal' => 'N'
                    );
                $this->telefonoxalumno_model->updateTelefonoxalumnoByALUMID($data_txalx, $alum_id);
            }

            $data_response = ($txal_id === '') ? $this->telefonoxalumno_model->insertTelefonoxalumno($data_txal) : $this->telefonoxalumno_model->updateTelefonoxalumno($data_txal, $txal_id);
            if($data_response){

                $data_txaly = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM($alum_id);
                if(count($data_txaly) === 1){
                    $data_txalw = array(
                            'txal_principal' => 'S'
                        );
                    $this->telefonoxalumno_model->updateTelefonoxalumnoByALUMID($data_txalw, $alum_id);
                }
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($txal_id === '') ? RMESSAGE_ASSIGNED : RMESSAGE_ASSIGNEDUP));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
            }
        }
        redirect('registros/alumno/ver/'.$alum_id_enc);
    }



}































