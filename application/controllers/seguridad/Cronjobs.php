<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_GET['function'])):
    $cronjob = new Cronjobs();
    switch ($_GET['function']){
        case 'cumpleanos_usuarios':
            $cronjob->cumpleanos_usuarios();
            break;
        case 'cumpleanos_alumnos':
            $cronjob->cumpleanos_alumnos();
            break;
        default:
            "Don't exist your function";
            break;
    }
endif;

class Cronjobs extends CI_Controller {
    public function __construct() {
        parent :: __construct();

        $this->load->model('registros/alumno_model');
        $this->load->model('seguridad/usuario_model');
        $this->load->model('seguridad/reportexusuario_model');
    }

    public function cumpleanos_usuarios(){
        /*
        $fechaactual = getdate();
        print_r($fechaactual);
        echo "Hoy es: $fechaactual[weekday], $fechaactual[mday] de $fechaactual[month] de $fechaactual[year]";
        exit();
        */
        $date_now = date('m-d');
        $data_usua              = $this->usuario_model->getUsuarioAll();
        $data_birthday          = array();
        if(isset($data_usua)):
            foreach ($data_usua as $key => $usua):
                $usua_date = date('m-d', strtotime($usua->usua_fecha_nacimiento));
                if($date_now === $usua_date):
                    $data_birthday[] = $usua;
                endif;
            endforeach;
        endif;

        /* REPORTE NRO. 03 QUE CORRESPONDE A LOS CUMPLEAÑOS DEL PERSONAL */
        $data_rexu          = $this->reportexusuario_model->getReportexusuarioAllByREMACOD('03');
        $email_cc           = '';
        $email_subject      = '';
        $email_descripcion  = '';
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
                    $email_cc           .= ($email_cc === '') ? $usua_email : ','.$usua_email;
                    $email_subject      = $rema_titulo;
                    $email_descripcion  = $rema_descripcion;
                }//end if
            }//end foreach
        }//end if

        if($email_cc != '' && count($data_birthday) > 0){
            foreach ($data_birthday as $key => $usua):
                $data_palabras_reservadas = array(
                        '[CUMPLEANOS]' => $usua->usua_nombre.' '.$usua->usua_apellido
                    );
                $email_to       = ($usua->usua_email != '') ? $usua->usua_email : $usua->usua_email_personal;
                $email_message  = reemplazar_palabras_reservadas($this, $email_descripcion, $data_palabras_reservadas);
                $mail_request   = enviar_email($this, $email_to, $email_subject, $email_message, $email_cc);
            endforeach;
        }//end if
    }


    public function cumpleanos_alumnos(){
        $date_now = date('m-d');
        $data_usua              = $this->alumno_model->getAlumnoAll();
        $data_birthday          = array();
        if(isset($data_usua)):
            foreach ($data_usua as $key => $alum):
                if($alum->alum_fecha_nacimiento != ''):
                    $alum_date = date('m-d', strtotime($alum->alum_fecha_nacimiento));
                    if($date_now === $alum_date):
                        $data_birthday[] = $alum;
                    endif;
                endif;
            endforeach;
        endif;

        /* REPORTE NRO. 04 QUE CORRESPONDE A LOS CUMPLEAÑOS DE ALUMNOS */
        $data_rexu          = $this->reportexusuario_model->getReportexusuarioAllByREMACOD('04');
        $email_to           = '';
        $email_subject      = '';
        $email_descripcion  = '';
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
                    $email_to           .= ($email_to === '') ? $usua_email : ','.$usua_email;
                    $email_subject      = $rema_titulo;
                    $email_descripcion  = $rema_descripcion;
                }//end if
            }//end foreach
        }//end if

        if($email_to != '' && count($data_birthday) > 0){
            //table birthday
            $table = "";
            $table .= "<table border='0' align='center' style='border: 1px solid #cdcdcd;'>";
            $table .= "<thead>";
            $table .= "<tr style='background-color: #006830; color: #fff;'><th></th><th>Alumno</th><th>Edad</th><th>Email</th></tr>";
            $table .= "</thead>";
            $table .= "<tbody>";
            foreach ($data_birthday as $key => $alum):
                $table .= "<tr>";
                $table .= "<td>".($key+1)."</td>";
                $table .= "<td>".$alum->alum_nombre." ".$alum->alum_apellido."</td>";
                $table .= "<td>".str_pad((date('Y')-date('Y', strtotime($alum->alum_fecha_nacimiento))),2,"0",STR_PAD_LEFT)."</td>";
                $table .= "<td><a href='mailto:".$alum->alum_email."'>".$alum->alum_email."</a></td>";
                $table .= "</tr>";
            endforeach;
            $table .= "</tbody>";
            $table .= "</table>";

            $data_palabras_reservadas = array(
                    '[TABLACUMPLEANOS]' => $table
                );

            $email_message  = reemplazar_palabras_reservadas($this, $email_descripcion, $data_palabras_reservadas);
            $mail_request   = enviar_email($this, $email_to, $email_subject, $email_message);
            echo "->".$mail_request."<-";
        }//end if
    }

}































