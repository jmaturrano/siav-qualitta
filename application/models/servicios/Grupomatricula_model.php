<?php
/**
* MK System Soft  
*
* Modelo de Grupomatricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupomatricula_Model extends CI_Model {
    private static $table_menu  = 'grupo_matricula';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    /**
      * Fx de Lista de Grupomatricula
      *
      * Trae la lista de todos los registros activos en general o por búsqueda específica
      *
      * @param string $q parámetro de búsqueda
      * @param string $limit cantidad límite por búsqueda realizada o listado general
      * @param string $offset número de página o inicio de búsqueda
      *
      * @return void
      */
    public function getGrupomatriculaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('gmat.gmat_estado'=>DB_ACTIVO);
        if($q !== ''){
            $q_fecha  = '';
            $q_fecha_ = explode('/', $q);
            if(count($q_fecha_) === 3){
              $q_fecha = date('Y-m-d', strtotime(str_replace('/', '-', $q)));
            }//end if
            $this->db->group_start();
            $this->db->like('carr.carr_codigo',$q);
            $this->db->like('moda.moda_descripcion',$q);
            if($q_fecha != ''){
              $this->db->like('gmat.gmat_fecha_inicio',$q_fecha);
            }
            $this->db->group_end();
        }
        $this->db->order_by('carr.carr_codigo', 'asc');
        $this->db->order_by('moda.moda_descripcion', 'asc');
        $this->db->order_by('gmat.gmat_fecha_inicio', 'asc');
        
        $this->db->where($where);
        $this->db->join('modalidad moda', 'moda.moda_id = gmat.moda_id', 'inner');
        $this->db->join('carrera carr', 'carr.carr_id = gmat.carr_id', 'inner');
        $query = $this->db->get(self::$table_menu.' gmat');
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
        $this->db->where('gmat_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Grupomatricula por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $gmat_id id principal del registro
      *
      * @return void
      */
    public function getGrupomatriculaByID($gmat_id){
        $where = array('gmat.gmat_id' => $gmat_id);
        $this->db->join('modalidad moda', 'moda.moda_id = gmat.moda_id', 'inner');
        $this->db->join('carrera carr', 'carr.carr_id = gmat.carr_id', 'inner');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' gmat');
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
      * @param int $gmat_id id principal del registro
      *
      * @return void
      */
    public function deleteGrupomatriculaByID($gmat_id){
        $where      = array('gmat_id' => $gmat_id);
        $data_gmat  = array('gmat_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_gmat);
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
      * @param array $data_gmat contiene los datos a ingresar
      *
      * @return void
      */
    public function insertGrupomatricula($data_gmat){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_gmat);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $gmat_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $gmat_id;
    }

    /**
      * Fx de Actualización de registro
      *
      * Actualiza un registro activo
      *
      * @param array $data_gmat contiene los datos a ingresar
      * @param int $gmat_id id principal del registro
      *
      * @return void
      */
    public function updateGrupomatricula($data_gmat, $gmat_id){
        $where      = array('gmat_id' => $gmat_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_gmat);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $gmat_id;
    }

}