<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oficina_Model extends CI_Model {
    private static $table_menu  = 'oficina';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getOficinaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('ofic_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('ofic.ofic_nombre',$q);
            $this->db->or_like('ofic.ofic_abreviatura',$q);
            $this->db->or_like('ofic.ofic_direccion',$q);
            $this->db->or_like('ofic.ofic_email',$q);
            $this->db->group_end();
        }
        $query = $this->db->order_by('ofic.ofic_nombre', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->join('oficina_tipo otip', 'otip.otip_id = ofic.otip_id', 'inner');
        $query = $this->db->get(self::$table_menu.' ofic');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getOficinaAllDisponible($usua_id){
        $where = array(
                    'ofic.ofic_estado'=>DB_ACTIVO
                    );
        $this->db->order_by('ofic.ofic_nombre', 'asc');
        $this->db->where($where);
        $this->db->join('oficina_x_usuario uxof', 'uxof.ofic_id = ofic.ofic_id AND uxof.usua_id = '.$usua_id.' AND uxof.uxof_estado = '."'".DB_ACTIVO."'", 'left');
        $this->db->select('ofic.ofic_id, ofic.ofic_codigo, ofic.ofic_nombre, ofic.ofic_abreviatura, ofic.ofic_direccion, 
                            ofic.ofic_email, ofic.ofic_estado, ofic.otip_id, uxof.uxof_id, uxof.uxof_estadodefecto, 
                            uxof.uxof_estado');
        $query = $this->db->get(self::$table_menu.' ofic');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('ofic_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getOficinaByID($ofic_id){
        $where = array('ofic.ofic_id' => $ofic_id);
        $query = $this->db->where($where);
        $query = $this->db->join('oficina_tipo otip', 'otip.otip_id = ofic.otip_id', 'inner');
        $query = $this->db->get(self::$table_menu.' ofic');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteOficinaByID($ofic_id){
        $where      = array('ofic_id' => $ofic_id);
        $data_ofic  = array('ofic_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ofic);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertOficina($data_ofic){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_ofic);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $ofic_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $ofic_id;
    }

    public function updateOficina($data_ofic, $ofic_id){
        $where      = array('ofic_id' => $ofic_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ofic);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $ofic_id;
    }

}

