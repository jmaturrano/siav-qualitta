<?php
/**
* MK System Soft  
*
* Modelo de Matricula carreras
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Matricula_Model extends CI_Model {
    private static $table_menu  = 'matricula';
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
    public function getMatriculaAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('matr.matr_estado'=>DB_ACTIVO);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('matr.matr_codigo',$q);
            $this->db->group_end();
        }
        $this->db->order_by('matr.matr_codigo', 'asc');
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = matr.carr_id', 'inner');
        $this->db->join('alumno alum', 'alum.alum_id = matr.alum_id', 'inner');
        $this->db->join('estados_matricula emat', 'emat.emat_id = matr.emat_id', 'inner');
        $this->db->join('grupo_matricula gmat', 'gmat.gmat_id = matr.gmat_id', 'inner');
        $query = $this->db->get(self::$table_menu.' matr');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function getMatriculaAllPAGOS($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array(
          'matr.matr_estado'=>DB_ACTIVO,
          'fima.fima_estado'=>DB_ACTIVO,
          'gmat.gmat_estado'=>DB_ACTIVO,
          'emat.emat_estado'=>DB_ACTIVO,
          'alum.alum_estado'=>DB_ACTIVO,
          'carr.carr_estado'=>DB_ACTIVO
          );
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('matr.matr_codigo',$q);
            $this->db->group_end();
        }
        $this->db->order_by('matr.matr_codigo', 'asc');
        $this->db->where($where);
        $this->db->join('carrera carr', 'carr.carr_id = matr.carr_id', 'inner');
        $this->db->join('alumno alum', 'alum.alum_id = matr.alum_id', 'inner');
        $this->db->join('estados_matricula emat', 'emat.emat_id = matr.emat_id', 'inner');
        $this->db->join('grupo_matricula gmat', 'gmat.gmat_id = matr.gmat_id', 'inner');
        $this->db->join('financia_matricula fima', 'fima.matr_id = matr.matr_id', 'inner');
        $query = $this->db->get(self::$table_menu.' matr');
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
        $this->db->where('matr_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    /**
      * Fx de Lista de Matricula por ID
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $matr_id id principal del registro
      *
      * @return void
      */
    public function getMatriculaByID($matr_id){
        $where = array('matr.matr_id' => $matr_id);

        $this->db->join('grupo_matricula gmat', 'gmat.gmat_id = matr.gmat_id', 'inner');
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu.' matr');
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }


    public function getMatriculaByCARRALUM($carr_id, $alum_id){
        $where = array(
            'carr_id'     => $carr_id,
            'alum_id'     => $alum_id,
            'matr_estado' => DB_ACTIVO
          );
        $this->db->where($where);
        $query = $this->db->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    /**
      * Fx de Eliminación de registro
      *
      * Actualiza el registro a estado inactivo
      *
      * @param int $matr_id id principal del registro
      *
      * @return void
      */
    public function deleteMatriculaByID($matr_id){
        $where      = array('matr_id' => $matr_id);
        $data_matr = array('matr_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_matr);
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
      * @param array $data_matr contiene los datos a ingresar
      *
      * @return void
      */
    public function insertMatricula($data_matr){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_matr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $matr_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $matr_id;
    }

    /**
      * Fx de Actualización de registro
      *
      * Actualiza un registro activo
      *
      * @param array $data_matr contiene los datos a ingresar
      * @param int $matr_id id principal del registro
      *
      * @return void
      */
    public function updateMatricula($data_matr, $matr_id){
        $where      = array('matr_id' => $matr_id);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_matr);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $matr_id;
    }

}