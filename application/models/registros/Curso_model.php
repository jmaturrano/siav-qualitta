<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Curso_Model extends CI_Model {
    private static $table_menu  = 'curso';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getCursoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('curs_estado'=>DB_ACTIVO);
        $this->db->order_by('curs_descripcion', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('curs_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('curs_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getCursoByID($curs_id){
        $where = array('curs_id' => $curs_id);
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteCursoByID($curs_id){
        $where      = array('curs_id' => $curs_id);
        $data_curs   = array('curs_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_curs);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertCurso($data_curs){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_curs);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $curs_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $curs_id;
    }

    public function updateCurso($data_curs, $curs_id){
        $where      = array('curs_id' => $curs_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_curs);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $curs_id;
    }



}















