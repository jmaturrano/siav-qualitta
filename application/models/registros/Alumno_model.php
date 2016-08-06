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
        $where = array('alum.alum_estado'=>DB_ACTIVO);
        $this->db->order_by('alum.alum_nombre', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('alum.alum_nombre',$q);
            $this->db->or_like('alum.alum_apellido',$q);
            $this->db->or_like('alum.alum_email',$q);
            $this->db->or_like('txal.txal_numero',$q);
            $this->db->or_like('usua.usua_nombre',$q);
            $this->db->or_like('usua.usua_apellido',$q);
            $this->db->group_end();
        }
        $this->db->join('usuario usua', "usua.usua_id = alum.usua_id", 'inner');
        $this->db->join('telefono_x_alumno txal', "txal.alum_id = alum.alum_id AND txal.txal_principal = 'S'", 'left');
        $this->db->select('alum.alum_id, alum.alum_codigo, alum.alum_nombre, alum.alum_apellido, alum.alum_numero_documento, 
                            alum.alum_email, alum.alum_fecha_nacimiento, alum.alum_lugar_nacimiento, alum.alum_direccion, alum.alum_ruta_imagen, 
                            alum.alum_observaciones, alum.alum_estado, alum.alum_fecha_registro, alum.dide_id, alum.dist_id, 
                            alum.esal_id, alum.usua_id, usua.usua_codigo, usua.usua_nombre, usua.usua_apellido, 
                            usua.usua_numero_documento, usua.usua_email, usua.usua_estado, txal.txal_id, txal.txal_numero,
                            txal.txal_principal, txal.txal_estado');
        $query = $this->db->get(self::$table_menu.' alum');
        //echo "->".$this->db->last_query();
        //exit();
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







