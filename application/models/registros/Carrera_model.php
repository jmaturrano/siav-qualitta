<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carrera_Model extends CI_Model {
    private static $table_menu  = 'carrera';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getCarreraAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('carr_estado'=>DB_ACTIVO);
        $this->db->order_by('carr_descripcion', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('carr_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('carr_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getCarreraByID($carr_id){
        $where = array('carr_id' => $carr_id);
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteCarreraByID($carr_id){
        $where      = array('carr_id' => $carr_id);
        $data_carr   = array('carr_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_carr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertCarrera($data_carr){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_carr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $carr_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $carr_id;
    }

    public function updateCarrera($data_carr, $carr_id){
        $where      = array('carr_id' => $carr_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_carr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $carr_id;
    }

}















