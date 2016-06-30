<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipomatricula extends CI_Controller {
    private static $header_title  = 'Tipo Matricula';
    private static $header_icon  = ICON_SETTINGS;
    private static $OFICINAS = array();
    private static $ROLES = array();
    private static $PRIVILEGIOS = array();
    private static $PERMISOS = array();

    public function __construct() {
        parent :: __construct();
        $this->initData();
        $this->load->model('registros/tipomatricula_model');
    }


     /**
      * Función inicial 
      *
      * Carga los permisos y configuraciones del sistema
      *
      * @param ''
      *
      * @return void
      */
     public function initData(){
        self::$OFICINAS     = revisar_oficinas($this);
        self::$ROLES        = revisar_roles($this, 0);
        self::$PRIVILEGIOS  = revisar_privilegios($this, 0);   
        self::$PERMISOS     = revisar_permisos(self::$PRIVILEGIOS, 'registros/tipomatricula'); 
    }


     /**
      * Función de listado general de los datos
      *
      * Carga la vista principal del mantenimiento con todos los registros activos
      *
      * @param int $ofsset parametro de seleccion de paginacion
      *
      * @return void
      */
    public function index($offset = 0) {



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;



        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;



        $this->load->library('pagination');

        $propio['ruta_base'] = 'registros/tipomatricula/index';

        $propio['filas_totales'] = $this->tipomatricula_model->contar_estructuras_todos();

        $filasPorPagina = paginacion_configurar($propio, $this);



        $data['data_tima']      = $this->tipomatricula_model->getTipomatriculaAll('', $filasPorPagina, floor($offset / $filasPorPagina)*$filasPorPagina);

        $data['btn_nuevo']      = 'registros/tipomatricula/nuevo';

        $this->layout->view('registros/tipomatricula_index', $data);

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

        $data['btn_nuevo']      = 'registros/tipomatricula/nuevo';



        $this->form_validation->set_rules('q', 'Buscar', 'trim');

        if ($this->form_validation->run() === FALSE) {

            redirect('registros/tipomatricula');

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());

            ($datapost['q'] === '') ? redirect('registros/tipomatricula') : '';

            $data['data_tima']  = $this->tipomatricula_model->getTipomatriculaAll($datapost['q']);

            $data['q']          = $datapost['q'];

            $this->layout->view('registros/tipomatricula_index', $data);

            $this->load->view('notificacion');

        }

    }



    public function ver($mati_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        ($mati_id_enc === '') ? redirect('registros/tipomatricula') : '';

        $mati_id = str_decrypt($mati_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'ver';

        $data['data_tima']      = $this->tipomatricula_model->getTipomatriculaByID($mati_id);

        $data['btn_editar']     = 'registros/tipomatricula/editar/'.$mati_id_enc;

        $data['btn_regresar']   = 'registros/tipomatricula';

        $this->layout->view('registros/tipomatricula_form', $data);

        $this->load->view('notificacion');

    }







    public function editar($mati_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        ($mati_id_enc === '') ? redirect('registros/tipomatricula') : '';

        $mati_id = str_decrypt($mati_id_enc, KEY_ENCRYPT);

        $this->load->library('layout');

        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = 'editar';

        $data['data_tima']      = $this->tipomatricula_model->getTipomatriculaByID($mati_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/tipomatricula';

        $this->layout->view('registros/tipomatricula_form', $data);

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

        $data['btn_cancelar']   = 'registros/tipomatricula';

        $this->layout->view('registros/tipomatricula_form', $data);

        $this->load->view('notificacion');

    }







    public function eliminar($mati_id_enc = ''){       



        ($mati_id_enc === '') ? redirect('registros/tipomatricula') : '';

        $mati_id = str_decrypt($mati_id_enc, KEY_ENCRYPT);

        $data_delete            = $this->tipomatricula_model->deleteTipomatriculaByID($mati_id);

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

        redirect('registros/tipomatricula');

    }







    public function guardar($mati_id_enc = ''){



        $data['OFICINAS']       = self::$OFICINAS;

        $data['ROLES']          = self::$ROLES;

        $data['PRIVILEGIOS']    = self::$PRIVILEGIOS;

        $data['PERMISOS']       = self::$PERMISOS;





        $mati_id = ($mati_id_enc === '') ? '' : str_decrypt($mati_id_enc, KEY_ENCRYPT);

        if ($mati_id === '') {

        $this->form_validation->set_rules('mati_nombre','Nombre','required|trim|is_unique[matricula_tipo.mati_nombre]');

        }

         else 

        {

            $this->form_validation->set_rules('mati_nombre','Nombre','required|trim');

        }



        $data['header_title']   = self::$header_title;

        $data['header_icon']    = self::$header_icon;

        $data['tipo_vista']     = ($mati_id === '')?'nuevo':'editar';

        $data['data_tima']      = $this->tipomatricula_model->getTipomatriculaByID($mati_id);

        $data['btn_guardar']    = true;

        $data['btn_cancelar']   = 'registros/tipomatricula';

        $this->load->library('layout');

        if ($this->form_validation->run() === FALSE) {

            $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

            $this->session->set_flashdata('mensaje', primer_error_validation());

            $this->layout->view('registros/tipomatricula_form', $data);

        }

        else

        {

            $datapost = $this->security->xss_clean($this->input->post());

            $data_timat = array(

                'mati_nombre'           => $datapost['mati_nombre']

                

            );

            $data_response = ($mati_id === '') ? $this->tipomatricula_model->insertTipomatricula($data_timat) : $this->tipomatricula_model->updateTipomatricula($data_timat, $mati_id);

            if($data_response){

                $this->session->set_flashdata('mensaje_tipo', EXIT_SUCCESS);

                $this->session->set_flashdata('mensaje', (($mati_id === '') ? RMESSAGE_INSERT : RMESSAGE_UPDATE));

                redirect('registros/tipomatricula');

            }else{

                $this->session->set_flashdata('mensaje_tipo', EXIT_ERROR);

                $this->session->set_flashdata('mensaje', RMESSAGE_ERROR);

                $this->layout->view('registros/tipomatricula_form', $data);

            }

        }

        $this->load->view('notificacion');



    }











}































