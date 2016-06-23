<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Alumno_Model extends CI_Model {
    private static $table_menu  = 'alumno';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }


    public function getAlumnoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('alum_estado'=>DB_ACTIVO);
        $this->db->order_by('alum_nombre', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('alum_nombre',$q);
            $this->db->or_like('usua_numero_documento',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('alum_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getAlumnoByID($alum_id){
        $where = array('alum_id' => $alum_id);
        $this->db->where($where);
        $this->db->join('distrito dist', 'dist.dist_id = alum.dist_id', 'inner');
        $this->db->join('provincia prov', 'prov.prov_id = dist.prov_id', 'inner');
        $this->db->join('departamento depa', 'depa.depa_id = prov.depa_id', 'inner');
        $this->db->join('documento_identidad dide', 'dide.dide_id = alum.dide_id', 'inner');
        $query = $this->db->get(self::$table_menu.' alum');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteAlumnoByID($alum_id){
        $where      = array('alum_id' => $alum_id);
        $data_alum   = array('alum_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_alum);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertAlumno($data_alum){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_alum);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $alum_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $alum_id;
    }

    public function updateAlumno($data_alum, $alum_id){
        $where      = array('alum_id' => $alum_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_alum);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $alum_id;
    }





}







