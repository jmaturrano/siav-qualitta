<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Tipooficina_Model extends CI_Model {
    private static $table_menu  = 'oficina_tipo';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   
    public function getTipooficinaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('otip_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('otip_nombre',$q);
            $this->db->group_end();
        }
        $query = $this->db->order_by('otip_nombre', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('otip_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getTipooficinaByID($otip_id){
        $where = array('otip_id' => $otip_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteTipooficinaByID($otip_id){
        $where      = array('otip_id' => $otip_id);
        $data_tipof = array('otip_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertTipooficina($data_tipof){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateTipooficina($data_tipof, $otip_id){
        $where      = array('otip_id' => $otip_id);
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



