<?php
    
    /**
    * MK System Soft  
    *
    * Modelo de Aeronave
    *
    */

defined('BASEPATH') OR exit('No direct script access allowed');

class Aeronave_Model extends CI_Model {
    private static $table_menu  = 'aeronave';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }
    
    /**
      * Fx de modelo aeronave
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */

    public function getAeronaveAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('aero.aero_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('aero.aero_matricula',$q);
        }
        $this->db->order_by('aero.aero_matricula', 'asc');
        $this->db->where($where);
        $this->db->join('modelo_aeronave moae', 'moae.moae_id = aero.moae_id', 'inner');
        $query = $this->db->get(self::$table_menu.' aero');
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
        $this->db->where('aero_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

        /**
      * Fx de modelo aeronave por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $opte_id id principal del registro
      *
      * @return void
      */

    public function getAeronaveByID($aero_id){
        $where = array('aero_id' => $aero_id);
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
      * @param int $aero_id principal del registro
      *
      * @return void
      */

    public function deleteAeronaveByID($aero_id){
        $where      = array('aero_id' => $aero_id);
        $data_aero = array('aero_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_aero);
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
      * @param array $data_aero contiene los datos a ingresar
      *
      * @return void
      */

    public function insertAeronave($data_aero){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_aero);
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
      * @param array $data_aero contiene los datos a ingresar
      *
      * @return void
      */

    public function updateAeronave($data_aero, $aero_id){

        $where      = array('aero_id' => $aero_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_aero);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }
}







