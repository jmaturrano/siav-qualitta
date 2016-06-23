<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docidentidad_Model extends CI_Model {
    private static $table_menu  = 'documento_identidad';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function getDocidentidadAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('dide_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('dide_descripcion',$q);
        }
        $query = $this->db->order_by('dide_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('dide_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getDocidentidadByID($dide_id){
        $where = array('dide_id' => $dide_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteDocidentidadByID($dide_id){
        $where      = array('dide_id' => $dide_id);
        $data_doid = array('dide_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_doid);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertDocidentidad($data_doid){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_doid);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateDocidentidad($data_doid, $dide_id){
        $where      = array('dide_id' => $dide_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_doid);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}







