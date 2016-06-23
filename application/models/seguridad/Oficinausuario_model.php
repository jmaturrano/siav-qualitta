<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Oficinausuario_Model extends CI_Model {
    private static $table_menu  = 'oficina_x_usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    

    public function getOficinausuarioAll($usua_id){
        $where = array(
                    'uxof.uxof_estado'=>DB_ACTIVO, 
                    'uxof.usua_id' => $usua_id
                    );
        $query = $this->db->order_by('ofic.ofic_nombre', 'asc');
        $query = $this->db->where($where);
        $query = $this->db->join('oficina ofic', 'ofic.ofic_id = uxof.ofic_id', 'inner');
        $query = $this->db->get(self::$table_menu.' uxof');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getOficinausuarioAllDisponible($usua_id){
        $where = array(
                    'uxof.uxof_estado'=>DB_ACTIVO, 
                    'uxof.usua_id' => $usua_id
                    );
        $this->db->order_by('ofic.ofic_nombre', 'asc');
        $this->db->where($where);
        $this->db->join('oficina ofic', 'ofic.ofic_id = uxof.ofic_id', 'inner');
        $this->db->join('rol_x_usuario rxus', 'rxus.uxof_id = uxof.uxof_id AND rxus.rxus_estado = '."'".DB_ACTIVO."'", 'left');
        $this->db->select('uxof.uxof_id, uxof.uxof_estadodefecto, uxof.uxof_estado, uxof.usua_id, uxof.ofic_id,
                            ofic.ofic_codigo, ofic.ofic_nombre, ofic.ofic_abreviatura, ofic.ofic_direccion, ofic.ofic_email, 
                            ofic.ofic_estado, ofic.otip_id, rxus.rxus_id, rxus.rxus_estado, rxus.rol_id');
        $query = $this->db->get(self::$table_menu.' uxof');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function deleteOficinausuarioByID($uxof_id){
        $where      = array('uxof_id' => $uxof_id);
        $data_uxof   = array('uxof_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_uxof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


    public function insertOficinausuario($data_uxof){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_uxof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateOficinausuario($data_uxof, $uxof_id){
        $where      = array('uxof_id' => $uxof_id);
        $this->db->trans_begin();
        $this->db->where($where)->update(self::$table_menu, $data_uxof);
        /*
        if($data_uxof["uxof_estadodefecto"] ==='S'){
            $where       = array('usua_id' => $data_uxof["usua_id"] , 'uxof_id !=' => $uxof_id);
            $data_uxofs  = array(
                'uxof_estadodefecto'      => 'N'
            );
            $query       = $this->db->where($where)->update(self::$table_menu, $data_uxofs);
        }
        */
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateOficinausuarioAll($data_uxof, $usua_id){
        $where      = array('usua_id' => $usua_id);
        $this->db->trans_begin();
        $this->db->where($where)->update(self::$table_menu, $data_uxof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }





}















