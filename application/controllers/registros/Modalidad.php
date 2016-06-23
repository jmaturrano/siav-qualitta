<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Modalidad extends CI_Controller {

    private static $header_title  = 'Modalidad Carrera';

    private static $header_icon  = ICON_SETTINGS;



    private static $OFICINAS = array();

    private static $ROLES = array();

    private static $PRIVILEGIOS = array();

    private static $PERMISOS = array();



    public function __construct() {

        parent :: __construct();

        $this->initData();

        $this->load->model('registros/modalidad_model');

    }



     public function initData(){

        self::$OFICINAS     = revisar_oficinas($this);

        self::$ROLES        = revisar_roles($this, 0);

        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   

        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/modalidad'); 

    }



    public function index($offset = 0) {



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;



        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;



        $this->load->library('pagination');

        $propio['ruta_base'] = 'registros/modalidad/index';

        $propio['filas_totales'] = $this->modalidad_model->contar_estructuras_todos();

        $filasPorPagina = paginacion_configurar($propio, $this);



        $data['data_modal']      = $this->modalidad_model->getModalidadAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);

        $data['btn_nuevo']      = 'registros/modalidad/nuevo';

        $this->layout->view('registros/modalidad_index', $data);

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

        $data['btn_nuevo']      = 'registros/modalidad/nuevo';



        $this->form_validation->set_rules('q', 'Buscar', 'trim');

        if ($this->form_validation->run() === FALSE) {

            redirect('registros/modalidad');

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());

            ($datapost['q'] === '') ? redirect('registros/modalidad') : '';

            $data['data_modal']  = $this->modalidad_model->getModalidadAll($datapost['q']);

            $data['q']          = $datapost['q'];

            $this->layout->view('registros/modalidad_index', $data);

            $this->load->view('notificacion');

        }

    }



    public function ver($moda_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        ($moda_id_enc === '') ? redirect('registros/modalidad') : '';

        $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'ver';

        $data['data_moda']      = $this->modalidad_model->getModalidadByID($moda_id);

        $data['btn_editar']     = 'registros/modalidad/editar/'.$moda_id_enc;

        $data['btn_regresar']   = 'registros/modalidad';

        $this->layout->view('registros/modalidad_form', $data);

        $this->load->view('notificacion');

    }







    public function editar($moda_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        ($moda_id_enc === '') ? redirect('registros/modalidad') : '';

        $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'editar';

        $data['data_moda']      = $this->modalidad_model->getModalidadByID($moda_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/modalidad';

        $this->layout->view('registros/modalidad_form', $data);

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

        $data['btn_cancelar']   = 'registros/modalidad';

        $this->layout->view('registros/modalidad_form', $data);

        $this->load->view('notificacion');

    }







    public function eliminar($moda_id_enc = ''){       



        ($moda_id_enc === '') ? redirect('registros/modalidad') : '';

        $moda_id = str_decrypt($moda_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->modalidad_model->deleteModalidadByID($moda_id);

        if($data_delete){

            $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);

            $this->session->set_flashdata('mensaje', RMESSAGE_DELETE);

            /*

            $this->session->set_flashdata('message_type', RTYPE_SUCCESS);

            $this->session->set_flashdata('message_title', RTITLE_SUCCESS);

            $this->session->set_flashdata('message_response', RMESSAGE_DELETE);

            */

        }else{

            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

            $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);

            /*

            $this->session->set_flashdata('message_type', RTYPE_ERROR);

            $this->session->set_flashdata('message_title', RTITLE_ERROR);

            $this->session->set_flashdata('message_response', RMESSAGE_ERROR);

            */

        }

        redirect('registros/modalidad');

    }







    public function guardar($moda_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        $moda_id = ($moda_id_enc === '') ? '' : str_decrypt($moda_id_enc, KEY_ENCRYPT);

        $this->form_validation->set_rules('moda_descripcion', 'Descripción', 'required');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = ($moda_id === '')?'nuevo':'editar';

        $data['data_moda']      = $this->modalidad_model->getModalidadByID($moda_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/modalidad';

        $this->load->library('layout');

        if ($this->form_validation->run() === FALSE) {

            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

            $this->session->set_flashdata('mensaje', primer_error_validation());

            $this->layout->view('registros/modalidad_form', $data);

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());

            $data_modal = array(

                'moda_descripcion'           => $datapost['moda_descripcion']

            );

            $data_response = ($moda_id === '') ? $this->modalidad_model->insertModalidad($data_modal) : $this->modalidad_model->updateModalidad($data_modal, $moda_id);

            if($data_response){

                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);

                $this->session->set_flashdata('mensaje', (($moda_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));

                redirect('registros/modalidad');

            }else{

                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);

                $this->layout->view('sregistros/modalidad_form', $data);

            }

        }

        $this->load->view('notificacion');



    }


}