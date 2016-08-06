<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aerodromo_Model extends CI_Model {
    private static $table_menu  = 'aerodromo';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getAerodromoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('aero_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('aero.aero_nombre',$q);
            $this->db->or_like('aero.aero_abreviatura',$q);
            $this->db->or_like('aero.aero_direccion',$q);
            $this->db->or_like('aero.aero_codigo',$q);
            $this->db->or_like('depa.depa_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('depa.depa_descripcion', 'asc');
        $this->db->order_by('aero.aero_nombre', 'asc');
        $this->db->order_by('aero.aero_codigo', 'asc');
        $this->db->where($where);
        $this->db->join('departamento depa', 'depa.depa_id = aero.depa_id', 'inner');
        $query = $this->db->get(self::$table_menu.' aero');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('aero_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getAerodromoByID($aero_id){
        $where = array('aero.aero_id' => $aero_id);
        $this->db->where($where);
        $this->db->join('departamento depa', 'depa.depa_id = aero.depa_id', 'inner');
        $query = $this->db->get(self::$table_menu.' aero');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteAerodromoByID($aero_id){
        $where      = array('aero_id' => $aero_id);
        $data_aero  = array('aero_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_aero);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertAerodromo($data_aero){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_aero);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $aero_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $aero_id;
    }

    public function updateAerodromo($data_aero, $aero_id){
        $where      = array('aero_id' => $aero_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_aero);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $aero_id;
    }

}

