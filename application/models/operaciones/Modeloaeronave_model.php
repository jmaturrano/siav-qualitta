<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Modeloaeronave_Model extends CI_Model {
    private static $table_menu  = 'modelo_aeronave';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   
    public function getModeloaeronaveAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('moae_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('moae_descripcion',$q);
        }
        $query = $this->db->order_by('moae_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('moae_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getModeloaeronaveByID($moae_id){
        $where = array('moae_id' => $moae_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteModeloaeronaveByID($otip_id){
        $where      = array('moae_id' => $otip_id);
        $data_moae = array('moae_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_moae);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertModeloaeronave($data_moae){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_moae);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateModeloaeronave($data_moae, $otip_id){
        $where      = array('moae_id' => $otip_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_moae);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}



