<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rolusuario_Model extends CI_Model {
    private static $table_menu  = 'rol_x_usuario';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function getRolusuarioAll($usua_id){
        $where = array('rxus.rxus_estado'=>DB_ACTIVO, 'rxus.usua_id' => $usua_id);
        $query = $this->db->order_by('ofic.ofic_nombre', 'asc');
        $query = $this->db->order_by('rol.rol_nombre', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->join('rol', 'rol.rol_id = rxus.rol_id', 'inner');
        $query = $this->db->join('oficina_x_usuario uxof', 'uxof.uxof_id = rxus.uxof_id', 'inner');
        $query = $this->db->join('oficina ofic', 'ofic.ofic_id = uxof.ofic_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rxus');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getRolusuarioByOficina($usua_id, $ofic_id){
        $where = array('rxus.rxus_estado'=>DB_ACTIVO, 'rxus.usua_id' => $usua_id, 'uxof.ofic_id' => $ofic_id);
        $query = $this->db->order_by('ofic.ofic_nombre', 'asc');
        $query = $this->db->order_by('rol.rol_nombre', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->join('rol', 'rol.rol_id = rxus.rol_id', 'inner');
        $query = $this->db->join('oficina_x_usuario uxof', 'uxof.uxof_id = rxus.uxof_id', 'inner');
        $query = $this->db->join('oficina ofic', 'ofic.ofic_id = uxof.ofic_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rxus');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteRolusuarioByOficina($usua_id, $uxof_id){
        $where      = array('usua_id' => $usua_id, 'uxof_id' => $uxof_id);
        $data_Usuario   = array('rxus_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_Usuario);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function deleteRolusuarioByID($rxus_id){
        $where      = array('rxus_id' => $rxus_id);
        $data_rxus   = array('rxus_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rxus);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertRolusuario($data_rxus){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_rxus);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateRolusuario($data_rxus, $rxus_id){
        $where      = array('rxus_id' => $rxus_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rxus);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}















