<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Tipodireccion_Model extends CI_Model {
    private static $table_menu  = 'tipo_direccion';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   
    public function getTipodireccionAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('tdir_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('tdir_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->order_by('tdir_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('tdir_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getTipodireccionByID($tdir_id){
        $where = array('tdir_id' => $tdir_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteTipodireccionByID($tdir_id){
        $where      = array('tdir_id' => $tdir_id);
        $data_tdir = array('tdir_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tdir);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertTipodireccion($data_tdir){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_tdir);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateTipodireccion($data_tdir, $tdir_id){
        $where      = array('tdir_id' => $tdir_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tdir);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}



