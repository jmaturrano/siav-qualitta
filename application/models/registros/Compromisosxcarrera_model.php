<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Compromisosxcarrera_Model extends CI_Model {
    private static $table_menu  = 'compromiso_x_carrera';
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
    public function getCompromisosxcarreraAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('cxca.cxca_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('ccar.ccar_descripcion',$q);
            $this->db->group_end();
        }
        $this->db->order_by('carr.carr_codigo', 'asc');
        $this->db->order_by('ccar.ccar_descripcion', 'asc');
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = cxca.carr_id', 'inner');
        $this->db->join('compromiso_carrera ccar', 'ccar.ccar_id = cxca.ccar_id', 'inner');
        $query = $this->db->get(self::$table_menu.' cxca');
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
        $this->db->where('cxca_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Matricula por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $cxca_id id principal del registro
      *
      * @return void
      */
    public function getCompromisosxcarreraByID($cxca_id){
        $where = array('cxca_id' => $cxca_id);
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
      * @param int $cxca_id id principal del registro
      *
      * @return void
      */
    public function deleteCompromisosxcarreraByID($cxca_id){
        $where      = array('cxca_id' => $cxca_id);
        $data_cxca = array('cxca_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_cxca);
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
      * @param array $data_cxca contiene los datos a ingresar
      *
      * @return void
      */
    public function insertCompromisosxcarrera($data_cxca){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_cxca);
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
      * @param array $data_cxca contiene los datos a ingresar
      * @param int $cxca_id id principal del registro
      *
      * @return void
      */
    public function updateCompromisosxcarrera($data_cxca, $cxca_id){
        $where      = array('cxca_id' => $cxca_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_cxca);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}