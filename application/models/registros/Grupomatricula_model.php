<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Grupomatricula_Model extends CI_Model {
    private static $table_menu  = 'grupo_matricula';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getGrupomatriculaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('gmat_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('gmat_fecha_inicio',date('Y-m-d', strtotime(str_replace('/', '-', $q))));
            $this->db->group_end();
        }
        $this->db->order_by('gmat_fecha_inicio', 'asc');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getGrupomatriculaByCARRMODA($carr_id, $moda_id){
        $where = array(
            'carr_id'     => $carr_id,
            'moda_id'     => $moda_id,
            'gmat_estado' => DB_ACTIVO
          );
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }


}







