<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno extends CI_Controller {
    private static $header_title  = 'Alumnos';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/alumno_model');
        $this->load->model('registros/departamento_model');
        $this->load->model('registros/provincia_model');
        $this->load->model('registros/distrito_model');
        $this->load->model('seguridad/docidentidad_model');
        $this->load->model('registros/estadosxalumno_model');
        $this->load->model('seguridad/operadortelefono_model');
        $this->load->model('registros/telefonoxalumno_model');
        $this->load->model('registros/apoderadoxalumno_model');
        $this->load->model('seguridad/reportexusuario_model');
        $this->load->model('seguridad/tipodireccion_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);  
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/alumno');  
    }

    public function index($offset = 0) {
        $data['OFICINAS']           = self::$OFICINAS;
        $data['ROLES']              = self::$ROLES;
        $data['PRIVILEGIOS']        = self::$PRIVILEGIOS;
        $data['PERMISOS']           = self::$PERMISOS;
        $this->load->library('layout');
        $data['header_title']       = self::$header_title;
        $data['header_icon']        = self::$header_icon;
        $this->load->library('pagination');
        $propio['ruta_base']        = 'registros/alumno/index';
        $propio['filas_totales']    = $this->alumno_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_alum']          = $this->alumno_model->getAlumnoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']          = 'registros/alumno/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('registros/alumno_index', $data);
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
        $data['btn_nuevo']      = 'registros/alumno/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/alumno');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/alumno') : '';
            $data['data_alum']  = $this->alumno_model->getAlumnoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/alumno_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($alum_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_alum']      = $this->alumno_model->getAlumnoByID($alum_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($data['data_alum']->depa_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll($data['data_alum']->prov_id);
        $data['data_exal']      = $this->estadosxalumno_model->getEstadosxalumnoAllByALUM($alum_id);
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoAll();
        $data['data_txal']      = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM($alum_id);
        $data['data_apoa']      = $this->apoderadoxalumno_model->getApoderadoxalumnoByALUM($alum_id);
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll();

        $data['btn_editar']     = 'registros/alumno/editar/'.$alum_id_enc;
        $data['btn_regresar']   = 'registros/alumno';
        $this->layout->view('registros/alumno_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($alum_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_alum']      = $this->alumno_model->getAlumnoByID($alum_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($data['data_alum']->depa_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll($data['data_alum']->prov_id);
        $data['data_exal']      = $this->estadosxalumno_model->getEstadosxalumnoAllByALUM($alum_id);
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoAll();
        $data['data_txal']      = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM($alum_id);
        $data['data_apoa']      = $this->apoderadoxalumno_model->getApoderadoxalumnoByALUM($alum_id);
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll();

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/alumno';
        $this->layout->view('registros/alumno_form', $data);
        $this->load->view('notificacion');
    }

    public function confirmarreporte($alum_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['reporte_mail']   = true;
        $data['data_alum']      = $this->alumno_model->getAlumnoByID($alum_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['data_prov']      = $this->provincia_model->getProvinciaAll($data['data_alum']->depa_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll($data['data_alum']->prov_id);
        $data['data_exal']      = $this->estadosxalumno_model->getEstadosxalumnoAllByALUM($alum_id);
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoAll();
        $data['data_txal']      = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM($alum_id);
        $data['data_apoa']      = $this->apoderadoxalumno_model->getApoderadoxalumnoByALUM($alum_id);
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll();

        $data['btn_editar']     = 'registros/alumno/editar/'.$alum_id_enc;
        $data['btn_regresar']   = 'registros/alumno';
        $this->layout->view('registros/alumno_form', $data);
        $this->load->view('notificacion');
    }

    public function enviarreporte($alum_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $data_alum              = $this->alumno_model->getAlumnoByID($alum_id);

        /* REPORTE NRO. 01 QUE CORRESPONDE AL REGISTRO DE ALUMNOS */
        $data_rexu = $this->reportexusuario_model->getReportexusuarioAllByREMACOD('01');
        if(isset($data_rexu)){
            foreach ($data_rexu as $item => $rexu) {
                $rema_id        = $rexu->rema_id;
                $usua_id        = $rexu->usua_id;
                $usua_nombre    = $rexu->usua_nombre;
                $usua_apellido  = $rexu->usua_apellido;
                $usua_email     = $rexu->usua_email;
                $rema_titulo    = $rexu->rema_titulo;
                $rema_descripcion= $rexu->rema_descripcion;
                if($usua_email != ''){
                    $data_palabras_reservadas = array(
                            '[ALUMNO]' => $data_alum->alum_apellido.' '.$data_alum->alum_nombre,
                            '[DIRECCION]' => $data_alum->alum_direccion.', '.$data_alum->dist_descripcion.' '.$data_alum->prov_descripcion.' '.$data_alum->depa_descripcion,
                            '[URLALUMNO]' => base_url('registros/alumno/ver/'.str_encrypt($alum_id, KEY_ENCRYPT))
                        );
                    $email_to       = $usua_email;
                    $email_subject  = $rema_titulo;
                    $email_message  = reemplazar_palabras_reservadas($rema_descripcion, $data_palabras_reservadas);
                    $mail_request   = enviar_email($this, $email_to, $email_subject, $email_message);
                }//end if
            }//end foreach
        }//end if
        /* REPORTE NRO. 01 QUE CORRESPONDE AL REGISTRO DE ALUMNOS - FIN */

        $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
        $this->session->set_flashdata('mensaje', RMESSAGE_PROCESSED);
        redirect('registros/alumno/ver/'.$alum_id_enc);
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
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll();
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/alumno';
        $this->layout->view('registros/alumno_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($alum_id_enc = ''){
        ($alum_id_enc === '') ? redirect('registros/alumno') : '';
        $alum_id = str_decrypt($alum_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->alumno_model->deleteAlumnoByID($alum_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/alumno');
    }

    public function guardar($alum_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $alum_id = ($alum_id_enc === '') ? '' : str_decrypt($alum_id_enc, KEY_ENCRYPT);
        //$this->form_validation->set_rules('alum_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('alum_nombre', 'Nombre', 'required|trim');
        $this->form_validation->set_rules('alum_apellido', 'Apellidos', 'required|trim');
        $this->form_validation->set_rules('dide_id', 'Tipo Documento', 'required');
        $this->form_validation->set_rules('alum_numero_documento', 'Doc. Identidad', 'trim');
        $this->form_validation->set_rules('alum_fecha_nacimiento', 'Fecha nacimiento', 'trim');
        $this->form_validation->set_rules('alum_lugar_nacimiento', 'Lugar nacimiento', 'trim');
        $this->form_validation->set_rules('alum_direccion', 'Dirección', 'trim');
        $this->form_validation->set_rules('alum_email', 'Email', 'required|trim');
        $this->form_validation->set_rules('alum_observaciones', 'Comentario', 'required|trim');
        $this->form_validation->set_rules('dist_id', 'Distrito', 'trim');

        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($alum_id === '')?'nuevo':'editar';
        $data['data_alum']      = $this->alumno_model->getAlumnoByID(($alum_id === '')?0:$alum_id);
        $data['data_doid']      = $this->docidentidad_model->getDocidentidadAll();
        $data['data_depa']      = $this->departamento_model->getDepartamentoAll();
        $data['data_prov']      = $this->provincia_model->getProvinciaAll(($alum_id === '')?0:$data['data_alum']->depa_id);
        $data['data_dist']      = $this->distrito_model->getDistritoAll(($alum_id === '')?0:$data['data_alum']->prov_id);
        $data['data_exal']      = $this->estadosxalumno_model->getEstadosxalumnoAllByALUM(($alum_id === '')?0:$alum_id);
        $data['data_opte']      = $this->operadortelefono_model->getOperadortelefonoAll();
        $data['data_txal']      = $this->telefonoxalumno_model->getTelefonoxalumnoByALUM(($alum_id === '')?0:$alum_id);
        $data['data_apoa']      = $this->apoderadoxalumno_model->getApoderadoxalumnoByALUM(($alum_id === '')?0:$alum_id);
        $data['data_tdir']      = $this->tipodireccion_model->getTipodireccionAll();

        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/alumno';

        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/alumno_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_alum = array(
                'alum_codigo'               => $datapost['alum_numero_documento'],
                'alum_nombre'               => $datapost['alum_nombre'],
                'alum_apellido'             => $datapost['alum_apellido'],
                'dide_id'                   => $datapost['dide_id'],
                'alum_numero_documento'     => $datapost['alum_numero_documento'],
                'alum_fecha_nacimiento'     => date('Y-m-d', strtotime(str_replace('/', '-', $datapost['alum_fecha_nacimiento']))),
                'alum_lugar_nacimiento'     => $datapost['alum_lugar_nacimiento'],
                'alum_direccion'            => $datapost['alum_direccion'],
                'alum_email'                => $datapost['alum_email'],
                'alum_observaciones'        => isset($datapost['alum_observaciones'])?$datapost['alum_observaciones']:'',
                'dist_id'                   => ((!isset($datapost['dist_id']) || $datapost['dist_id'] === '')?993:$datapost['dist_id']),
                'tdir_id'                   => isset($datapost['tdir_id']) ? $datapost['tdir_id'] : 0
            );

            /*imagen de usuario*/
            if($_FILES['alum_ruta_imagen']['name']){
                $nombre_imagen              = 'alum_ruta_imagen';
                $directorio                 = 'alumnos';
                $request_upload = upload_file_foto($this, $nombre_imagen, $directorio);
                if(isset($request_upload['file_name'])){
                    $data_alum['alum_ruta_imagen'] = $request_upload['file_name'];
                }//end if
            }//end if

            if($alum_id === ''){
                /* Estado 1: registrado (POR DEFECTO) */
                $data_alum['esal_id'] = 1;

                /* Registrado por: */
                $data_alum['usua_id'] = $this->session->userdata('usua_id');
            }

            $data_response = ($alum_id === '') ? $this->alumno_model->insertAlumno($data_alum) : $this->alumno_model->updateAlumno($data_alum, $alum_id);
            if($data_response){

                $funcion_request = 'ver';
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($alum_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));

                /* 1.- REGISTRAR ESTADO POR ALUMNO */
                if($alum_id === ''){
                    $funcion_request = 'confirmarreporte';
                    $alum_id = $data_response;
                    $data_exal = array(
                            'exal_fecha_movimiento' => date('Y-m-d'),
                            'alum_id'               => $alum_id
                        );
                    $req_exal = registrar_estado_alumno($this, $data_exal, '01');
                }//end if
                /* 1.- REGISTRAR ESTADO POR ALUMNO - END */

                redirect('registros/alumno/'.$funcion_request.'/'.str_encrypt($alum_id, KEY_ENCRYPT));
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/alumno_form', $data);
            }
        }
        $this->load->view('notificacion');
    }



}































