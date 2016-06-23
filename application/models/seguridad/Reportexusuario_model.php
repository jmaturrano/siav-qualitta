<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Reportexusuario_Model extends CI_Model {
    private static $table_menu  = 'reporte_x_usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }


    public function getReportexusuarioAllByREMACOD($rema_codigo){

        /*
        *
        * Por defecto debería ser:
        * 01 REGISTRO DE ALUMNO NUEVO
        * 02 REGISTRO DE MATRICULA NUEVA
        * 03 SALUDOS POR CUMPLEAÑOS
        * 04 UN ALUMNO CUMPLE AÑOS HOY, SALUDALO!
        *
        */

        $where = array(
                    'rexu.rexu_estado'=>DB_ACTIVO,
                    'usua.usua_estado'=>DB_ACTIVO,
                    'rema.rema_estado'=>DB_ACTIVO,
                    'rema.rema_codigo'=>$rema_codigo
                    );
        $this->db->where($where);
        $this->db->join('reportes_mail rema', 'rema.rema_id = rexu.rema_id', 'inner');
        $this->db->join('usuario usua', 'usua.usua_id = rexu.usua_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rexu');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }



}







