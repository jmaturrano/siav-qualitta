<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Conceptosxmatricula_Model extends CI_Model {
    private static $table_menu  = 'conceptos_x_matricula';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getConceptosxMatriculaByMATR($matr_id){
        $where = array('cxma.cxma_estado'=>DB_ACTIVO, 'cxma.matr_id'=>$matr_id);
        $this->db->order_by('cmat.cmat_orden', 'asc');
        $this->db->where($where);
        $this->db->join('conceptos_matricula cmat', 'cmat.cmat_id = cxma.cmat_id', 'inner');
        $query = $this->db->get(self::$table_menu.' cxma');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function insertConceptosxMatriculaByGROUP($data_cxma){
        $query      = $this->db->trans_begin();
        foreach ($data_cxma as $data) {
          $this->db->insert(self::$table_menu, $data);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateConceptosxMatriculaByGROUP($data_cxmax){
        $query      = $this->db->trans_begin();
        $data_cxma = array();
        foreach ($data_cxmax as $item => $data) {
          if($item === 0){
            $this->db->where(array('matr_id' => $data['matr_id']))->update(self::$table_menu, array('cxma_estado' => DB_INACTIVO));
          }
          $where = array('cxma_id' => $data['cxma_id']);
          $data_cxma = array(
              'cxma_costoreal' => $data['cxma_costoreal'],
              'cxma_costofinal' => $data['cxma_costofinal'],
              'matr_id' => $data['matr_id'],
              'cmat_id' => $data['cmat_id'],
              'cxma_estado' => DB_ACTIVO
            );
          $this->db->where($where)->update(self::$table_menu, $data_cxma);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }



}