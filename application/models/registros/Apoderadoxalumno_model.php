<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Apoderadoxalumno_Model extends CI_Model {
    private static $table_menu  = 'apoderado_x_alumno';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getApoderadoxalumnoByALUM($alum_id){
        $where = array(
                    'apoa.alum_id'       => $alum_id,
                    'apoa.apoa_estado'   => DB_ACTIVO
                    );
        $this->db->order_by('apoa.apoa_apellido', 'asc');
        $this->db->order_by('apoa.apoa_nombre', 'asc');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' apoa');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function deleteApoderadoxalumnoByID($apoa_id){
        $where      = array('apoa_id' => $apoa_id);
        $data_apoa  = array('apoa_estado' => DB_INACTIVO);
        $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_apoa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertApoderadoxalumno($data_apoa){
        $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_apoa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $apoa_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $apoa_id;
    }

    public function updateApoderadoxalumno($data_apoa, $apoa_id){
        $where      = array('apoa_id' => $apoa_id);
        $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_apoa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $apoa_id;
    }





}







