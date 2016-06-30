<?php
/**
* MK System Soft  
*
* Modelo de Modalidad por curso
*
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Modalidadxcurso_Model extends CI_Model {
    private static $table_menu  = 'modalidad_x_curso';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }


    /**
      * Fx de Lista de modalidad por curso por ID de modalidad, ID curso, ID lista precio
      *
      * Devuelve los datos de un registro específico
      *
      * @param int $moda_id id de modalidad carrera
      * @param int $curs_id id de curso
      * @param int $lipe_id id de lista precio
      *
      * @return void
      */
    public function getModalidadxcursoByCURSIDMODAIDLIPEID($moda_id, $curs_id, $lipe_id){
        $where = array(
            'moda_id' => $moda_id,
            'curs_id' => $curs_id,
            'lipe_id' => $lipe_id,
            'mxca_estado' => DB_ACTIVO
            );
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }


    /**
      * Fx de Lista de modalidad por curso por ID de modalidad, ID módulo carrera, ID lista precio
      *
      * Devuelve los datos de un registro específico
      *
      * @param array $data_mxca con los ID's 
      *
      * @return void
      */
    public function getModalidadxcursoByMODAIDLIPEIDMODUID($data_mxca){
        $where = array(
            'mxca.moda_id'      => $data_mxca['moda_id'],
            'curs.modu_id'      => $data_mxca['modu_id'],
            'mxca.lipe_id'      => $data_mxca['lipe_id'],
            'mxca.mxca_estado'  => DB_ACTIVO
            );
        $this->db->where($where);
        $this->db->join('curso curs', 'curs.curs_id = mxca.curs_id', 'inner');
        $query = $this->db->get(self::$table_menu.' mxca');
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
      * @param array $data_mxca con los ID de los registros
      *
      * @return void
      */
    public function deleteModalidadxcursoGROUP($data_mxca){
        $data_mxcax = array('mxca_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where_in('mxca_id', $data_mxca)->update(self::$table_menu, $data_mxcax);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    /**
      * Fx de Inserción de registro por GRUPO
      *
      * Inserta un registro nuevo
      *
      * @param array $data_mxca contiene los datos a ingresar
      *
      * @return void
      */
    public function insertModalidadxcursoGROUP($data_mxca){
      $query      = $this->db->trans_begin();
      if(count($data_mxca) > 0){
        foreach ($data_mxca as $data) {
          $this->db->insert(self::$table_menu, $data);
        }//end foreach
      }//end if
      if ($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          return false;
      }
      $this->db->trans_commit();
      return true;
    }

    /**
      * Fx de Actualización de registro por grupo
      *
      * Actualiza un registro activo
      *
      * @param array $data_mxca contiene los datos a ingresar y el id del registro
      *
      * @return void
      */
    public function updateModalidadxcursoGROUP($data_mxca){
      $query      = $this->db->trans_begin();
      if(count($data_mxca) > 0){
        foreach ($data_mxca as $data) {
          $where      = array('mxca_id' => $data['mxca_id']);
          $query      = $this->db->where($where)->update(self::$table_menu, $data);
        }//end foreach
      }//end if
      if ($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          return false;
      }
      $this->db->trans_commit();
      return true;
    }

}