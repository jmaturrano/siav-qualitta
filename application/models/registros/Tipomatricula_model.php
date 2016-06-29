<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Tipomatricula_Model extends CI_Model {
    private static $table_menu  = 'matricula_tipo';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   
    public function getTipomatriculaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('mati_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('mati_nombre',$q);
        }
        $query = $this->db->order_by('mati_nombre', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('mati_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getTipomatriculaByID($mati_id){
        $where = array('mati_id' => $mati_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteTipomatriculaByID($mati_id){
        $where      = array('mati_id' => $mati_id);
        $data_timat = array('mati_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_timat);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertTipomatricula($data_timat){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_timat);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateTipomatricula($data_timat, $mati_id){
        $where      = array('mati_id' => $mati_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_timat);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}



