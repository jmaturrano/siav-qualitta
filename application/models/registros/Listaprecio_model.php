<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Listaprecio_Model extends CI_Model {
    private static $table_menu  = 'lista_precios';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getListaprecioAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('lipe_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('lipe_descripcion',$q);
        }
        $query = $this->db->order_by('lipe_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('lipe_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getListaprecioByID($lipe_id){
        $where = array('lipe_id' => $lipe_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteListaprecioByID($lipe_id){
        $where      = array('lipe_id' => $lipe_id);
        $data_lipe = array('lipe_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_lipe);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertListaprecio($data_lipe){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_lipe);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateListaprecio($data_lipe, $lipe_id){
        $where      = array('lipe_id' => $lipe_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_lipe);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}















