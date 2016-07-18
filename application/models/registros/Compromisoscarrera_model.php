<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Compromisoscarrera_Model extends CI_Model {
    private static $table_menu  = 'compromiso_carrera';
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
    public function getCompromisoscarreraAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('ccar_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('ccar_descripcion',$q);
            $this->db->group_end();
        }
        $query = $this->db->order_by('ccar_descripcion', 'asc')->where($where)->get(self::$table_menu);
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
        $this->db->where('ccar_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Matricula por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $ccar_id id principal del registro
      *
      * @return void
      */
    public function getCompromisoscarreraByID($ccar_id){
        $where = array('ccar_id' => $ccar_id);
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
      * @param int $ccar_id id principal del registro
      *
      * @return void
      */
    public function deleteCompromisoscarreraByID($ccar_id){
        $where      = array('ccar_id' => $ccar_id);
        $data_ccar = array('ccar_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ccar);
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
      * @param array $data_ccar contiene los datos a ingresar
      *
      * @return void
      */
    public function insertCompromisoscarrera($data_ccar){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_ccar);
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
      * @param array $data_ccar contiene los datos a ingresar
      * @param int $ccar_id id principal del registro
      *
      * @return void
      */
    public function updateCompromisoscarrera($data_ccar, $ccar_id){
        $where      = array('ccar_id' => $ccar_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_ccar);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}