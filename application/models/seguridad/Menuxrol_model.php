<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Menuxrol_Model extends CI_Model {
    private static $table_menu  = 'menu_x_rol';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function getMenuAllByRol($rol_id, $q = ''){
        $where = array('m.menu_estado' => 'AC');
        if($q !== ''){
            $this->db->like('m.menu_descripcion',$q);
            //$this->db->or_like('',$q);
        }
        $query = $this->db->order_by('m.menu_codigo', 'asc');
        //$query = $this->db->order_by('m.menu_nivel', 'asc');
        //$query = $this->db->order_by('m.menu_idpadre', 'asc');
        //$query = $this->db->order_by('m.menu_orden', 'asc');

        $query = $this->db->where($where);
        $query = $this->db->join('menu m', 'm.menu_id = mxr.menu_id AND mxr.rol_id = '.$rol_id, 'right');
        $query = $this->db->get(self::$table_menu.' mxr');
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }



    public function getMenuByRol($rol_id){
        $where = array('m.menu_estado' => 'AC', 'mxr.rol_id' => $rol_id);
        $query = $this->db->order_by('m.menu_codigo', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->join('menu m', 'm.menu_id = mxr.menu_id', 'inner');
        $query = $this->db->get(self::$table_menu.' mxr');
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }

    public function getMenuByRol_2($rol_id){
        $query = $this->db->order_by('m.menu_nivel', 'asc');
        $query = $this->db->order_by('m.menu_idpadre', 'asc');
        $query = $this->db->order_by('m.menu_orden', 'asc');
        if($this->session->userdata('usua_id') === '1'
            || $this->session->userdata('usua_id') === '3'
            || $this->session->userdata('usua_id') === '10'){
            $where = array('m.menu_estado' => 'AC', 'mxr.rol_id' => $rol_id);
        }else{
            $where = array('m.menu_estado' => 'AC', 'mxr.rol_id' => $rol_id, 'm.menu_id !=' => 15);
        }
        $query = $this->db->where($where);
        $query = $this->db->join('menu m', 'm.menu_id = mxr.menu_id', 'inner');
        $query = $this->db->get(self::$table_menu.' mxr');
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }



    public function insertMenuxRol($data_mxr){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_mxr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



    public function updateMenuxRol($data_mxr, $mxro_id){
        $where      = array('mxro_id' => $mxro_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_mxr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


    public function updateMenuxRolByROL($rol_id){
        $data_mxr   = array(
                        'mxro_accesa'   => '0',
                        'mxro_ingresa'  => '0',
                        'mxro_elimina'  => '0',
                        'mxro_modifica' => '0',
                        'mxro_consulta' => '0',
                        'mxro_imprime'  => '0',
                        'mxro_exporta'  => '0'
                    );
        $where      = array('rol_id' => $rol_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_mxr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


    public function verificaMenuxRol($rol_id, $menu_id){
        $where      = array('rol_id' => $rol_id, 'menu_id' => $menu_id);
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' mxr');
        if($query->result_id->num_rows > 0){
            return $query->result();
        }
        return null;
    }




}































