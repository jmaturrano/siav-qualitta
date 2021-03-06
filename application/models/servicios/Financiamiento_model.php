<?php
/**
* MK System Soft  
*
* Modelo de Financiamiento carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Financiamiento_Model extends CI_Model {
    private static $table_menu  = 'financia_matricula';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de Lista de Financiamiento
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */
    public function getFinanciamientoAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('fima_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('fima_comprobante',$q);
            $this->db->group_end();
        }
        $this->db->order_by('fima_fecha_programada', 'asc');
        $this->db->order_by('fima_fecha_proceso', 'asc');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getFinanciamientoAllByMATRID($matr_id){
        $where = array(
            'fima_estado'=>DB_ACTIVO,
            'matr_id' => $matr_id
            );
        $this->db->order_by('fima_fecha_programada', 'asc');
        $this->db->order_by('fima_fecha_proceso', 'asc');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
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
        $this->db->where('fima_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Financiamiento por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $fima_id id principal del registro
      *
      * @return void
      */
    public function getFinanciamientoByID($fima_id){
        $where = array('fima_id' => $fima_id);
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
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
      * @param int $fima_id id principal del registro
      *
      * @return void
      */
    public function deleteFinanciamientoByID($fima_id){
        $where      = array('fima_id' => $fima_id);
        $data_fima = array('fima_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_fima);
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
      * @param array $data_fima contiene los datos a ingresar
      *
      * @return void
      */
    public function insertFinanciamiento($data_fima){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_fima);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $fima_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $fima_id;
    }

    /**
      * Fx de Actualización de registro
      *
      * Actualiza un registro activo
      *
      * @param array $data_fima contiene los datos a ingresar
      * @param int $fima_id id principal del registro
      *
      * @return void
      */
    public function updateFinanciamiento($data_fima, $fima_id){
        $where      = array('fima_id' => $fima_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_fima);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $fima_id;
    }

}