<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nivelaprendizaje_Model extends CI_Model {
    private static $table_menu  = 'nivel_aprendizaje';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getNivelaprendizajeAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('niap_estado'=>DB_ACTIVO);
        $this->db->order_by('niap_codigo', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('niap_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('niap_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getNivelaprendizajeByID($niap_id){
        $where = array('niap_id' => $niap_id);
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteNivelaprendizajeByID($niap_id){
        $where      = array('niap_id' => $niap_id);
        $data_niap   = array('niap_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_niap);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertNivelaprendizaje($data_niap){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_niap);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $niap_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $niap_id;
    }

    public function updateNivelaprendizaje($data_niap, $niap_id){
        $where      = array('niap_id' => $niap_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_niap);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $niap_id;
    }



}















