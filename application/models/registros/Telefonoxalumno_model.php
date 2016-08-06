<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Telefonoxalumno_Model extends CI_Model {
    private static $table_menu  = 'telefono_x_alumno';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getTelefonoxalumnoByALUM($alum_id){
        $where = array(
                    'txal.alum_id'       => $alum_id,
                    'txal.txal_estado'   => DB_ACTIVO
                    );
        $this->db->order_by('opte.opte_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('operador_telefono opte', 'opte.opte_id = txal.opte_id', 'inner');
        $query = $this->db->get(self::$table_menu.' txal');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function deleteTelefonoxalumnoByID($txal_id){
        $where      = array('txal_id' => $txal_id);
        $data_txal  = array('txal_estado' => DB_INACTIVO);
        $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_txal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertTelefonoxalumno($data_txal){
        $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_txal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $txal_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $txal_id;
    }

    public function updateTelefonoxalumno($data_txal, $txal_id){
        $where      = array('txal_id' => $txal_id);
        $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_txal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $txal_id;
    }

    public function updateTelefonoxalumnoByALUMID($data_txal, $alum_id){
        $where      = array(
                        'alum_id'       => $alum_id,
                        'txal_estado'   => DB_ACTIVO
                        );
        $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_txal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }





}







