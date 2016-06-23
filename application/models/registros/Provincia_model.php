<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provincia_Model extends CI_Model {
    private static $table_menu  = 'provincia';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function index() {
        redirect('');
    }

    public function getProvinciaAll($depa_id, $q = ''){
        $where = array('prov_estado'=>DB_ACTIVO, 'depa_id' => $depa_id);
        if($q !== ''){
            $this->db->like('prov_descripcion',$q);
        }
        $query = $this->db->order_by('prov_descripcion', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('prov_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getProvinciaByID($prov_id){
        $where = array('prov_id' => $prov_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteProvinciaByID($prov_id){
        $where      = array('prov_id' => $prov_id);
        $data_prov 	= array('prov_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_prov);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertProvincia($data_prov){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_prov);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $prov_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $prov_id;
    }

    public function updateProvincia($data_prov, $prov_id){
        $where      = array('prov_id' => $prov_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_prov);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $prov_id;
    }







































}































































