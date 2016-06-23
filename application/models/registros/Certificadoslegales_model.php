<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadoslegales_Model extends CI_Model {
    private static $table_menu  = 'certificados_legales';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function getCertificadoslegalesAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('cele_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('cele_descripcion',$q);
        }
        $query = $this->db->order_by('cele_descripcion', 'asc')->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('cele_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getCertificadoslegalesByID($cele_id){
        $where = array('cele_id' => $cele_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteCertificadoslegalesByID($cele_id){
        $where      = array('cele_id' => $cele_id);
        $data_ctle = array('cele_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ctle);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertCertificadoslegales($data_ctle){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_ctle);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateCertificadoslegales($data_ctle, $cele_id){
        $where      = array('cele_id' => $cele_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ctle);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}







