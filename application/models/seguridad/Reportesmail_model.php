<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Reportesmail_Model extends CI_Model {
    private static $table_menu  = 'reportes_mail';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }


    public function getReportesmailAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('rema_estado'=>DB_ACTIVO);
        $this->db->order_by('rema_codigo', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('rema_codigo',$q);
            $this->db->or_like('rema_titulo',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        //echo "-->".$this->db->last_query();
        //exit();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('rema_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getReportesmailByID($rema_id){
        $where = array('rema_id' => $rema_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteReportesmailByID($rema_id){
        $where      = array('rema_id' => $rema_id);
        $data_rema  = array('rema_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rema);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertReportesmail($data_rema){
        $this->db->trans_begin();
        $this->db->insert(self::$table_menu, $data_rema);
        $rema_id    = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $rema_id;
    }

    public function updateReportesmail($data_rema, $rema_id){
        $where      = array('rema_id' => $rema_id);
        $this->db->trans_begin();        
        $this->db->where($where)->update(self::$table_menu, $data_rema);
            
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $rema_id;
    }





}







