<?php
    /**
    * MK System Soft  
    *
    * Tipo aeronave
    *
    */
defined('BASEPATH') OR exit('No direct script access allowed');
class Tipoaeronave_Model extends CI_Model {
    private static $table_menu  = 'tipo_aeronave';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }
    
     /**
      * Fx de Tipo aeronave
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */

    public function getTipoaeronaveAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('tiae_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->like('tiae_descripcion',$q);
        }
        $query = $this->db->order_by('tiae_descripcion', 'asc')->where($where)->get(self::$table_menu);
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
        $this->db->where('tiae_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Tipo aeronave por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $tiae_id id principal del registro
      *
      * @return void
      */

    public function getTipoaeronaveByID($otip_id){
        $where = array('tiae_id' => $otip_id);
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
      * @param int $tiae_id principal del registro
      *
      * @return void
      */

    public function deleteTipoaeronaveByID($otip_id){
        $where      = array('tiae_id' => $otip_id);
        $data_tipof = array('tiae_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tipof);
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
      * @param array $data_tipof contiene los datos a ingresar
      *
      * @return void
      */

    public function insertTipoaeronave($data_tipof){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_tipof);
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
      * @param array $data_tipof contiene los datos a ingresar
      *
      * @return void
      */

    public function updateTipoaeronave($data_tipof, $otip_id){
        $where      = array('tiae_id' => $otip_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_tipof);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }
}
