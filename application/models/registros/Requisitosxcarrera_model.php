<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Requisitosxcarrera_Model extends CI_Model {
    private static $table_menu  = 'requisitos_x_carrera';
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
    public function getRequisitosxcarreraAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('rxca.rxca_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('carr.carr_codigo',$q);
            $this->db->like('rcar.rcar_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('carr.carr_codigo', 'asc');
        $this->db->order_by('rcar.rcar_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = rxca.carr_id', 'inner');
        $this->db->join('requisitos_carrera rcar', 'rcar.rcar_id = rxca.rcar_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rxca');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }


    public function getRequisitosxcarreraByCARR($carr_id){
        $where = array('rxca_estado'=>DB_ACTIVO, 'carr_id' => $carr_id);
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
        $this->db->where('rxca_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Matricula por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $rxca_id id principal del registro
      *
      * @return void
      */
    public function getRequisitosxcarreraByID($rxca_id){
        $where = array('rxca_id' => $rxca_id);
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
      * @param int $rxca_id id principal del registro
      *
      * @return void
      */
    public function deleteRequisitosxcarreraByID($rxca_id){
        $where      = array('rxca_id' => $rxca_id);
        $data_rxca = array('rxca_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rxca);
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
      * @param array $data_rxca contiene los datos a ingresar
      *
      * @return void
      */
    public function insertRequisitosxcarrera($data_rxca){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_rxca);
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
      * @param array $data_rxca contiene los datos a ingresar
      * @param int $rxca_id id principal del registro
      *
      * @return void
      */
    public function updateRequisitosxcarrera($data_rxca, $rxca_id){
        $where      = array('rxca_id' => $rxca_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rxca);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}