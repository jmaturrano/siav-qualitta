<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operadortelefono_Model extends CI_Model {
    private static $table_menu  = 'operador_telefono';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getOperadortelefonoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('opte_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('opte_descripcion',$q);
        }
        $query = $this->db->order_by('opte_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('opte_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getOperadortelefonoByID($opte_id){
        $where = array('opte_id' => $opte_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteOperadortelefonoByID($opte_id){
        $where      = array('opte_id' => $opte_id);
        $data_optel = array('opte_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_optel);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertOperadortelefono($data_optel){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_optel);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateOperadortelefono($data_optel, $opte_id){
        $where      = array('opte_id' => $opte_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_optel);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}







