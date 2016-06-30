<?php
/**
* MK System Soft  
*
* Modelo de Certificados Legales
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Certificadoslegales_Model extends CI_Model {
    private static $table_menu  = 'certificados_legales';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de operador Telefono
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */

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

    /**
      * Fx de Contar registros
      *
      * Cuenta todos los registros activos de la tabla
      *
      *
      * @return void
      */

    public function contar_estructuras_todos() {
        $this->db->where('cele_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Certificados legales por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $cele_id principal del registro
      *
      * @return void
      */

    public function getCertificadoslegalesByID($cele_id){
        $where = array('cele_id' => $cele_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    /**
      * Fx de Eliminación de registro
      *
      * Actualiza el registro a estado inactivo
      *
      * @param int $cele_id principal del registro
      *
      * @return void
      */

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

        /**
      * Fx de Inserción de registro
      *
      * Inserta un registro nuevo
      *
      * @param array $data_ctle contiene los datos a ingresar
      *
      * @return void
      */

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

    /**
      * Fx de Actualización de todos los registro
      *
      * Actualiza todos los registro activos
      *
      * @param array $data_ctle contiene los datos a ingresar
      *
      * @return void
      */

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















