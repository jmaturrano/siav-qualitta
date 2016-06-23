<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Estadoalumno_Model extends CI_Model {
    private static $table_menu  = 'estados_alumno';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getEstadoalumnoByCOD($esal_codigo){
        $where = array(
                    'esal_codigo' => $esal_codigo,
                    'esal_estado' => DB_ACTIVO
                );
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }


}







