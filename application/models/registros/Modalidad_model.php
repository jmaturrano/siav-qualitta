<?php
/**
* MK System Soft  
*
* Modelo de Modalidad carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Modalidad_Model extends CI_Model {
    private static $table_menu  = 'modalidad';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de Lista de Modalidad
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */
    public function getModalidadAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('moda_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('moda_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->order_by('moda_descripcion', 'asc')->where($where)->get(self::$table_menu);
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
        $this->db->where('moda_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de modalidad por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $moda_id id principal del registro
      *
      * @return void
      */
    public function getModalidadByID($moda_id){
        $where = array('moda_id' => $moda_id);
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
      * @param int $moda_id id principal del registro
      *
      * @return void
      */
    public function deleteModalidadByID($moda_id){
        $where      = array('moda_id' => $moda_id);
        $data_modal = array('moda_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_modal);
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
      * @param array $data_modal contiene los datos a ingresar
      *
      * @return void
      */
    public function insertModalidad($data_modal){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_modal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    /**
      * Fx de Actualización de registro
      *
      * Actualiza un registro activo
      *
      * @param array $data_modal contiene los datos a ingresar
      * @param int $moda_id id principal del registro
      *
      * @return void
      */
    public function updateModalidad($data_modal, $moda_id){
        $where      = array('moda_id' => $moda_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_modal);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}