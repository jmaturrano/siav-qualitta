<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Estadomatricula_Model extends CI_Model {
    private static $table_menu  = 'estados_matricula';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getEstadoMatriculaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('emat_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('emat_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('emat_descripcion', 'asc');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getEstadomatriculaByCOD($emat_codigo){
        $where = array(
                    'emat_codigo' => $emat_codigo,
                    'emat_estado' => DB_ACTIVO
                );
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }


}







