<?php

/**
* MK System Soft  
*
* Modelo de moneda
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Moneda_Model extends CI_Model {
    private static $table_menu  = 'moneda';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de Lista de precios
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */
    public function getMonedaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('mone_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('mone_descripcion',$q);
        }
        $query = $this->db->order_by('mone_descripcion', 'asc')->where($where)->get(self::$table_menu);
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
        $this->db->where('mone_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de precio por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $mone_id id principal del registro
      *
      * @return void
      */
    public function getMonedaByID($mone_id){
        $where = array('mone_id' => $mone_id);
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
      * @param int $mone_id id principal del registro
      *
      * @return void
      */
    public function deleteMonedaByID($mone_id){
        $where      = array('mone_id' => $mone_id);
        $data_mone = array('mone_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_mone);
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
      * @param array $data_mone contiene los datos a ingresar
      *
      * @return void
      */
    public function insertMoneda($data_mone){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_mone);
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
      * @param array $data_mone contiene los datos a ingresar
      * @param int $mone_id id principal del registro
      *
      * @return void
      */
    public function updateMoneda($data_mone, $mone_id){
        $where      = array('mone_id' => $mone_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_mone);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }


}















