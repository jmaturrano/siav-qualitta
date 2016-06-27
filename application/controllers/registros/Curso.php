<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Curso extends CI_Controller {
    private static $header_title  = 'Cursos';
    private static $header_icon  = ICON_GROUP;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();
    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/curso_model');
    }

    public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/curso');
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
        $propio['ruta_base']        = 'registros/curso/index';
        $propio['filas_totales']    = $this->curso_model->contar_estructuras_todos();
        $filasPorPagina = paginacion_configurar($propio, $this);
        $data['data_curs']          = $this->curso_model->getcursoAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);
        $data['btn_nuevo']          = 'registros/curso/nuevo';
        $data['offset'] = $offset;
        $this->layout->view('registros/curso_index', $data);
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
        $data['btn_nuevo']      = 'registros/curso/nuevo';
        $this->form_validation->set_rules('q', 'Buscar', 'trim');
        if ($this->form_validation->run() === FALSE) {
            redirect('registros/curso');
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            ($datapost['q'] === '') ? redirect('registros/curso') : '';
            $data['data_curs']  = $this->curso_model->getCursoAll($datapost['q']);
            $data['q']          = $datapost['q'];
            $this->layout->view('registros/curso_index', $data);
            $this->load->view('notificacion');
        }
    }

    public function ver($curs_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($curs_id_enc === '') ? redirect('registros/curso') : '';
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'ver';
        $data['data_curs']      = $this->curso_model->getCursoByID($curs_id);
        $data['btn_editar']     = 'registros/curso/editar/'.$curs_id_enc;
        $data['btn_regresar']   = 'registros/curso';
        $this->layout->view('registros/curso_form', $data);
        $this->load->view('notificacion');
    }

    public function editar($curs_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        ($curs_id_enc === '') ? redirect('registros/curso') : '';
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);
        $this->load->library('layout');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = 'editar';
        $data['data_curs']      = $this->curso_model->getCursoByID($curs_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/curso';
        $this->layout->view('registros/curso_form', $data);
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
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/curso';
        $this->layout->view('registros/curso_form', $data);
        $this->load->view('notificacion');
    }

    public function eliminar($curs_id_enc = ''){
        ($curs_id_enc === '') ? redirect('registros/curso') : '';
        $curs_id = str_decrypt($curs_id_enc, KEY_ENCRYPT);
        $data_delete            = $this->curso_model->deleteCursoByID($curs_id);
        if($data_delete){
            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);
        }else{
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
        }
        redirect('registros/curso');
    }

    public function guardar($curs_id_enc = ''){
        $data['OFICINAS']       = self::$OFICINAS;
        $data['ROLES']          = self::$ROLES;
        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;
        $data['PERMISOS']       = self::$PERMISOS;
        $curs_id = ($curs_id_enc === '') ? '' : str_decrypt($curs_id_enc, KEY_ENCRYPT);
        $this->form_validation->set_rules('curs_codigo', 'Código', 'required|trim');
        $this->form_validation->set_rules('curs_descripcion', 'Nombre curso', 'required|trim');
        $this->form_validation->set_rules('modu_id', 'Módulo', 'required');
        $data['header_title']   = self::$header_title;
        $data['header_icon']    = self::$header_icon;
        $data['tipo_vista']     = ($curs_id === '')?'nuevo':'editar';
        $data['data_curs']      = $this->curso_model->getCursoByID(($curs_id === '')?0:$curs_id);
        $data['btn_guardar']    = true;
        $data['btn_cancelar']   = 'registros/curso';
        $this->load->library('layout');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
            $this->session->set_flashdata('mensaje', primer_error_validation());
            $this->layout->view('registros/curso_form', $data);
        }
        else
        {
            $datapost = $this->security->xss_clean($this->input->post());
            $data_curs = array(
                'curs_codigo'               => $datapost['curs_codigo'],
                'curs_descripcion'          => $datapost['curs_descripcion'],
                'modu_id'                   => $datapost['modu_id']
            );
            $data_response = ($curs_id === '') ? $this->curso_model->insertcurso($data_curs) : $this->curso_model->updatecurso($data_curs, $curs_id);
            if($data_response){
                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);
                $this->session->set_flashdata('mensaje', (($curs_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));
                redirect('registros/curso');
            }else{
                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);
                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);
                $this->layout->view('registros/curso_form', $data);
            }
        }
        $this->load->view('notificacion');
    }


}































































































































