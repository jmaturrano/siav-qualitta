<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Departamento_Model extends CI_Model {
    private static $table_menu  = 'departamento';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function index() {
        redirect('');
    }

    public function getDepartamentoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('depa_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('depa_descripcion',$q);
        }
        $query = $this->db->order_by('depa_descripcion', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('depa_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getDepartamentoByID($depa_id){
        $where = array('depa_id' => $depa_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteDepartamentoByID($depa_id){
        $where      = array('depa_id' => $depa_id);
        $data_depa 	= array('depa_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_depa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertDepartamento($data_depa){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_depa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $depa_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $depa_id;
    }

    public function updateDepartamento($data_depa, $depa_id){
        $where      = array('depa_id' => $depa_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_depa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $depa_id;
    }




}































































