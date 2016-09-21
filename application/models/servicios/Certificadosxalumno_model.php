<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificadosxalumno_Model extends CI_Model {
    private static $table_menu  = 'certificado_x_alumno';
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
    public function getCertificadosxalumnoAll($alum_id, $q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('cxal.cxal_estado'=>DB_ACTIVO, 'cxal.alum_id' => $alum_id);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('cele.cele_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('cele.cele_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('certificados_legales cele', 'cele.cele_id = cxal.cele_id', 'inner');
        $query = $this->db->get(self::$table_menu.' cxal');
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
    public function contar_estructuras_todos($alum_id) {
        $where = array('cxal_estado'=>DB_ACTIVO, 'alum_id' => $alum_id);
        $this->db->where($where);
        return $this->db->count_all_results(self::$table_menu);
    }


    public function getCertificadosxalumnoByID($cxal_id){
        $where = array(
            'cxal.cxal_estado'=>DB_ACTIVO, 
            'cele.cele_estado'=>DB_ACTIVO, 
            'cxal.cxal_id' => $cxal_id
            );
        $this->db->order_by('cele.cele_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('certificados_legales cele', 'cele.cele_id = cxal.cele_id', 'inner');
        $query = $this->db->get(self::$table_menu.' cxal');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function insertCertificadosxalumno($data_cxal){
        $query      = $this->db->trans_begin();
        $this->db->insert(self::$table_menu, $data_cxal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $cxal_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $cxal_id;
    }

    public function updateCertificadosxalumno($data_cxal, $cxal_id){
        $where      = array('cxal_id' => $cxal_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_cxal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function deleteCertificadosxalumno($cxal_id){
        $where      = array('cxal_id' => $cxal_id);
        $data_cxal  = array(
              'cxal_estado' => DB_INACTIVO
          );
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_cxal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}