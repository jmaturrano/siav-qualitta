<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Modulosxcarrera_Model extends CI_Model {
    private static $table_menu  = 'modulos_carrera';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getModulosxcarreraAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('modu_estado'=>DB_ACTIVO);
        $this->db->order_by('modu_descripcion', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('modu_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('modu_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getModulosxcarreraByCARRID($carr_id){
        $where = array(
                    'modu.carr_id' => $carr_id,
                    'modu.modu_estado' => DB_ACTIVO
                    );
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = modu.carr_id');
        $this->db->join('nivel_aprendizaje niap', 'niap.niap_id = modu.niap_id');
        $query = $this->db->get(self::$table_menu.' modu');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getModulosxcarreraByID($modu_id){
        $where = array(
                    'modu.modu_id' => $modu_id
                    );
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = modu.carr_id');
        $this->db->join('nivel_aprendizaje niap', 'niap.niap_id = modu.niap_id');
        $query = $this->db->get(self::$table_menu.' modu');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteModulosxcarreraByID($modu_id){
        $where      = array('modu_id' => $modu_id);
        $data_modu   = array('modu_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_modu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertModulosxcarrera($data_modu){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_modu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $modu_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $modu_id;
    }

    public function updateModulosxcarrera($data_modu, $modu_id){
        $where      = array('modu_id' => $modu_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_modu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $modu_id;
    }



}















