<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Tipoaeronave_Model extends CI_Model {
    private static $table_menu  = 'tipo_aeronave';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   
    public function getTipoaeronaveAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('tiae_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('tiae_descripcion',$q);
        }
        $query = $this->db->order_by('tiae_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('tiae_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getTipoaeronaveByID($otip_id){
        $where = array('tiae_id' => $otip_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteTipoaeronaveByID($otip_id){
        $where      = array('tiae_id' => $otip_id);
        $data_tipof = array('tiae_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertTipoaeronave($data_tipof){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateTipoaeronave($data_tipof, $otip_id){
        $where      = array('tiae_id' => $otip_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}



