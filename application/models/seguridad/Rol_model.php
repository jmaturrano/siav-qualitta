<?php



defined('BASEPATH') OR exit('No direct script access allowed');

class Rol_Model extends CI_Model {
    private static $table_menu  = 'rol';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function getRolAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('rol_estado'=>DB_ACTIVO);

        $this->db->order_by('rol_nombre', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->like('rol_nombre',$q);
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('rol_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getRolByID($rol_id){
        $where = array('rol_id' => $rol_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteRolByID($rol_id){
        $where      = array('rol_id' => $rol_id);
        $data_rol   = array('rol_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rol);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertRol($data_rol){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_rol);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $rol_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $rol_id;
    }

    public function updateRol($data_rol, $rol_id){
        $where      = array('rol_id' => $rol_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rol);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $rol_id;
    }

}















