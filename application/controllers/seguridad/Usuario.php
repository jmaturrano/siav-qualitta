<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {
    private static $header_title  = 'Usuarios';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('seguridad/usuario_model');
        $this->load->model('seguridad/docidentidad_model');
        $this->load->model('seguridad/oficinausuario_model');
        $this->load->model('seguridad/oficina_model');
        $this->load->model('seguridad/rol_model');
        $this->load->model('seguridad/rolusuario_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'seguridad/usuario');  
    }

    public function index($offset = 0) {
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']    = self::$header_title;
        $data['header_icon']     = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base'] = 'seguridad/usuario/index';
        $propio['filas_totales'] = $this->usuario_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_usua']       = $this->usuario_model->getUsuarioAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']       = 'seguridad/usuario/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('seguridad/usuario_index', $data);
        $this->load->view('notificacion');
    }

    public function buscar(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $this->load->library('pagination');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['btn_nuevo']      = 'seguridad/usuario/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('seguridad/usuario');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('seguridad/usuario') : '';
            $data['data_usua']  = $this->usuario_model->getUsuarioAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('seguridad/usuario_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($usua_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($usua_id_enc === '') ? redirect('seguridad/usuario') : '';
        $usua_id = str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_usua']      = $this->usuario_model->getUsuarioByID($usua_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_ofic']      = $this->oficina_model->getOficinaAllDisponible($usua_id);
        $data['data_uxof']      = $this->oficinausuario_model->getOficinausuarioAll($usua_id);
        $data['data_uxofx']     = $this->oficinausuario_model->getOficinausuarioAllDisponible($usua_id);
        $data['data_rol']       = $this->rol_model->getRolAll();
        $data['data_rxus']      = $this->rolusuario_model->getRolusuarioAll($usua_id);
        $data['btn_editar']     = 'seguridad/usuario/editar/'.$usua_id_enc;
        $data['btn_regresar']   = 'seguridad/usuario';
        $this->layout->view('seguridad/usuario_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($usua_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($usua_id_enc === '') ? redirect('seguridad/usuario') : '';
        $usua_id = str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_usua']      = $this->usuario_model->getUsuarioByID($usua_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_ofic']      = $this->oficina_model->getOficinaAllDisponible($usua_id);
        $data['data_uxof']      = $this->oficinausuario_model->getOficinausuarioAll($usua_id);
        $data['data_uxofx']     = $this->oficinausuario_model->getOficinausuarioAllDisponible($usua_id);
        $data['data_rol']       = $this->rol_model->getRolAll();
        $data['data_rxus']      = $this->rolusuario_model->getRolusuarioAll($usua_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/usuario';
        $this->layout->view('seguridad/usuario_form', $data);
        $this->load->view('notificacion');
    }

    public function nuevo(){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'nuevo';
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_rol']       = $this->rol_model->getRolAll();
        $data['data_ofic']      = $this->oficina_model->getOficinaAllDisponible(0);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/usuario';
        $this->layout->view('seguridad/usuario_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($usua_id_enc = ''){
        ($usua_id_enc === '') ? redirect('seguridad/usuario') : '';
        $usua_id = str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->usuario_model->deleteUsuarioByID($usua_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('seguridad/usuario');
    }

    public function guardar($usua_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $usua_id = ($usua_id_enc === '') ? '' : str_decrypt($usua_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('usua_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('usua_nombre', 'Nombre', 'required|trim');
        $this->form_validation->set_rules('usua_apellido', 'Apellidos', 'required|trim');
        $this->form_validation->set_rules('dide_id', 'Tipo Documento', 'required');
        $this->form_validation->set_rules('usua_numero_documento', 'Doc. Identidad', 'required');
        if($usua_id === ''){
            $this->form_validation->set_rules('usua_clave', 'Clave', 'required');
        }
        $this->form_validation->set_rules('usua_email', 'Email', 'required|trim');
        $this->form_validation->set_rules('usua_email_personal', 'Email Personal', 'trim');
        $this->form_validation->set_rules('usua_fecha_nacimiento', 'Fecha nacimiento', 'required|trim');
        $this->form_validation->set_rules('usua_direccion', 'Dirección', 'trim');
        $this->form_validation->set_rules('usua_cargolaboral', 'Cargo Laboral', 'required|trim');
        $this->form_validation->set_rules('usua_activolaboral', 'Estado', 'required');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($usua_id === '')?'nuevo':'editar';
        $data['data_usua']      = $this->usuario_model->getUsuarioByID(($usua_id === '')?0:$usua_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_ofic']      = $this->oficina_model->getOficinaAllDisponible(($usua_id === '')?0:$usua_id);
        $data['data_uxof']      = $this->oficinausuario_model->getOficinausuarioAll(($usua_id === '')?0:$usua_id);
        $data['data_uxofx']     = $this->oficinausuario_model->getOficinausuarioAllDisponible(($usua_id === '')?0:$usua_id);
        $data['data_rol']       = $this->rol_model->getRolAll();
        $data['data_rxus']      = $this->rolusuario_model->getRolusuarioAll(($usua_id === '')?0:$usua_id);

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'seguridad/usuario';

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('seguridad/usuario_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());

            $data_usua = array(
                'usua_codigo'               => $datapost['usua_codigo'],
                'usua_nombre'               => $datapost['usua_nombre'],
                'usua_apellido'             => $datapost['usua_apellido'],
                'dide_id'                   => $datapost['dide_id'],
                'usua_numero_documento'     => $datapost['usua_numero_documento'],
                'usua_email'                => isset($datapost['usua_email'])?$datapost['usua_email']:'',
                'usua_email_personal'       => isset($datapost['usua_email_personal'])?$datapost['usua_email_personal']:'',
                'usua_fecha_nacimiento'     => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['usua_fecha_nacimiento']))),
                'usua_direccion'            => $datapost['usua_direccion'],
                'usua_cargolaboral'         => $datapost['usua_cargolaboral'],
                'usua_activolaboral'        => $datapost['usua_activolaboral']
            );
            /*clave de usuario*/
            if($datapost['usua_clave'] != ''){
                $data_usua['usua_clave']    = md5($datapost['usua_clave']);
            }//end if
            /*imagen de usuario*/
            if($_FILES['usua_ruta_imagen']['name']){
                $nombre_imagen              = 'usua_ruta_imagen';
                $directorio                 = 'usuarios';
                $request_upload = upload_file_foto($this, $nombre_imagen, $directorio);
                if(isset($request_upload['file_name'])){
                    $data_usua['usua_ruta_imagen'] = $request_upload['file_name'];
                }//end if
            }//end if

            $data_response = ($usua_id === '') ? $this->usuario_model->insertUsuario($data_usua) : $this->usuario_model->updateUsuario($data_usua, $usua_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($usua_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                $usua_id = $data_response;
                redirect('seguridad/usuario/ver/'.str_encrypt($usua_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('seguridad/usuario_form', $data);
            }
        }
        $this->load->view('notificacion');
    }

    public function listarAjax(){
        $datapost = $this->security->xss_clean($this->input->post());
        $data_usua = $this->usuario_model->findUsuarioAll($datapost['search']);
        echo json_encode($data_usua);

    }

   


}































