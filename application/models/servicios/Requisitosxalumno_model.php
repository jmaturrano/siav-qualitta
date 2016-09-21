<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Requisitosxalumno_Model extends CI_Model {
    private static $table_menu  = 'requisitos_x_alumno';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de Lista de Matricula
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */
    public function getRequisitosxalumnoAll($matr_id, $q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('rxal.rxal_estado'=>DB_ACTIVO, 'rxal.matr_id' => $matr_id);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('rcar.rcar_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('rcar.rcar_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('requisitos_x_carrera rxca', 'rxca.rxca_id = rxal.rxca_id', 'inner');
        $this->db->join('requisitos_carrera rcar', 'rcar.rcar_id = rxca.rcar_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rxal');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    /**
      * Fx de Contar registros
      *
      * Cuenta todos los registros activos de la tabla
      *
      *
      * @return void
      */
    public function contar_estructuras_todos($matr_id) {
        $where = array('rxal_estado'=>DB_ACTIVO, 'matr_id' => $matr_id);
        $this->db->where($where);
        return $this->db->count_all_results(self::$table_menu);
    }


    public function insertRequisitosxalumnoByGROUP($data_rxal){
        $query      = $this->db->trans_begin();
        foreach ($data_rxal as $data) {
          $this->db->insert(self::$table_menu, $data);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function updateRequisitosxalumno($data_rxal, $rxal_id){
        $where      = array('rxal_id' => $rxal_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rxal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}