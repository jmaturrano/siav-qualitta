<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Estadosxalumno_Model extends CI_Model {
    private static $table_menu  = 'estados_x_alumno';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function insertEstadosxalumno($data_exal){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_exal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $exal_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $exal_id;
    }

    public function getEstadosxalumnoAllByALUM($alum_id){
        $where = array(
                    'exal.exal_estado'   =>DB_ACTIVO,
                    'exal.alum_id'       =>$alum_id
                    );
        $this->db->order_by('exal_fecha_movimiento', 'desc');
        $this->db->where($where);
        $this->db->join('estados_alumno esal', 'esal.esal_id = exal.esal_id', 'inner');
        $query = $this->db->get(self::$table_menu.' exal');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

}







