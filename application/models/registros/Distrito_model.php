<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Distrito_Model extends CI_Model {
    private static $table_menu  = 'distrito';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function index() {
        redirect('');
    }

    public function getDistritoAll($prov_id, $q = ''){
        $where = array('dist_estado'=>DB_ACTIVO, 'prov_id' => $prov_id);
        if($q !== ''){
            $this->db->like('dist_descripcion',$q);
        }
        $query = $this->db->order_by('dist_descripcion', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getDistritoByID($dist_id){
        $where = array('dist_estado'=>DB_ACTIVO, 'dist_id' => $dist_id);                
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('dist_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

   
    public function deleteDistritoByID($dist_id){
        $where      = array('dist_id' => $dist_id);
        $data_dist 	= array('dist_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_dist);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insert($data_dist){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_dist);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $dist_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $dist_id;
    }

    public function update($data_dist, $dist_id){
        $where      = array('dist_id' => $dist_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_dist);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $dist_id;
    }


}































































