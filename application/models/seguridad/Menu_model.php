<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_Model extends CI_Model {
    private static $table_menu  = 'menu';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    

    public function getMenuAccess($usua_id, $ofic_id){
        $query = $this->db->order_by('menu_codigo', 'asc');
        $query = $this->db->get(self::$table_menu);
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return false;
    }

    public function getMenuAll(){
        $where = array('menu_estado' => 'AC');
        $query = $this->db->order_by('menu_descripcion', 'asc');
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return false;
    }

    public function getMenuAll2($q = ''){
        $where = array('m.menu_estado' => 'AC');
        $query = $this->db->order_by('m.menu_codigo', 'asc');
        $query = $this->db->where($where);
        if($q !== ''){
            $this->db->like('m.menu_descripcion',$q);
            $this->db->or_like('m.menu_codigo',$q);
        }
        $query = $this->db->get(self::$table_menu.' m');
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }

    public function getMenuByID($menu_id){
        $where = array('m.menu_estado' => 'AC', 'm.menu_id' => $menu_id);
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' m');
        if($query->result_id->num_rows > 0){
            return $query->row();
        }
        return null;
    }

    public function getMenuByCODIGO($menu_codigo){
        $where = array('m.menu_estado' => 'AC', 'm.menu_codigo' => $menu_codigo);
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' m');
        if($query->result_id->num_rows > 0){
            return $query->row();
        }
        return null;
    }

    public function getMenuPadreAll($menu_id = ''){
        if($menu_id != ''){
            $where = array('menu_estado' => 'AC', 'menu_nivel' => '1', 'menu_id !=' => $menu_id);
        }else{
            $where = array('menu_estado' => 'AC', 'menu_nivel' => '1');
        }
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }

    public function deleteMenuByID($menu_id){
        $where      = array('menu_id' => $menu_id);
        $data_menu   = array('menu_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_menu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertMenu($data_menu){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_menu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $menu_id    = $this->db->insert_id();
        $this->db->trans_commit();
        return $menu_id;
    }

    public function updateMenu($data_menu, $menu_id){
        $where      = array('menu_id' => $menu_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_menu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $menu_id;
    }


}



