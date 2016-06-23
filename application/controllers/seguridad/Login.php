<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
    public function __construct() {
        parent :: __construct();
        $this->load->model('seguridad/login_model');
        $this->load->model('seguridad/configuracion_model');
    }
    public function index() {
        $this->load->library('layout');
        $data[] = '';
        $this->layout->view('seguridad/login_index', $data);
    }
	
	public function verificaDocIdent($docidentidad='') {
        ($docidentidad === '')?redirect(''):'';

		$userlogin = $this->login_model->verificaDocIdent($docidentidad);
        if($userlogin){
            $dataverify['usua_estado'] = $userlogin->usua_estado;
            $dataverify['usua_nombre'] = $userlogin->usua_nombre;
            $dataverify['usua_apellido'] = $userlogin->usua_apellido;

        }else{
            $dataverify['usua_estado'] = "";
            $dataverify['usua_nombre'] = "";
            $dataverify['usua_apellido'] = "";
        }
        echo json_encode($dataverify);
	}

    public function resetPassword($docidentidad='') {
        ($docidentidad === '')?redirect(''):'';

        $userlogin = $this->login_model->verificaDocIdent($docidentidad);
        //print_r($userlogin);
        if($userlogin){ 
            $dataverify['usua_estado'] = $userlogin->usua_estado;
            $dataverify['usua_nombre'] = $userlogin->usua_nombre;
            $data['data_conf']      = $this->configuracion_model->getConfigurationData();
            $conf_email = $data['data_conf']->conf_email;
            //$dataverify['conf_email'] = $conf_email;
            $this->load->library('email');
            $this->email->set_mailtype("html");
            $this->email->from('no-reply@mksystemsoft.com', 'MK System Soft');
            //$this->email->to( $conf_email );
            $this->email->to( "jmaturrano@mksystemsoft.com" ); 
            $this->email->subject('Restablecer Clave');
            $this->email->message("El usuario <a href='".base_url('perfil/usuario/editar/'.str_encrypt($userlogin->usua_id , KEY_ENCRYPT)) ."'>".$userlogin->usua_nombre."</a> ha solicitado un cambio de clave.");
            //$this->email->send();
            //send_mail
        }else{
            $dataverify['usua_estado'] = "";
            $dataverify['usua_nombre'] = "";
        }
        echo json_encode($dataverify);
    }

    public function accesslogin() {
        $this->form_validation->set_rules('usua_numero_documento', 'Documento de Identidad', 'required|trim');
        $this->form_validation->set_rules('usua_clave', 'Clave', 'required|trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_user = array(
                'usua_numero_documento'     => $datapost['usua_numero_documento'],
                'usua_clave'                => md5($datapost['usua_clave'])
            );
            $this->load->library('layout');
            $access_login = $this->login_model->verificaAcceso($data_user);
            if($access_login){
                $session_key    = date("YmdHis").rand(100, 999);
                $data_session   = array(
                    'usua_id'       => $access_login->usua_id,
                    'session_key'   => $session_key,
                    'usua_nombre'   => $access_login->usua_nombre,
                    'usua_apellido' => $access_login->usua_apellido
                );
                $this->session->set_userdata($data_session);
                redirect('/');
            }else{
                $data['error']="Documento o clave incorrectos, por favor intente nuevamente";
                $this->layout->view('seguridad/login_index', $data);
            }
        }//end else
    }

    public function accesslogout(){
        $arreglo = array('usua_id' => null, 'session_key' => null);
        $this->session->unset_userdata($arreglo);
        $this->session->sess_destroy();
        redirect('');
    }
}
