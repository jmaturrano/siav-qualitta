<?php



defined('BASEPATH') OR exit('No direct script access allowed');



class Modalidad_Model extends CI_Model {

    private static $table_menu  = 'modalidad';



    public function __construct() {

        parent :: __construct();

        $this->load->database();

    }



   

    public function getModalidadAll($q = '', $limit = 1000, $offset = 0){

        $this->db->limit($limit, $offset);

        $where = array('moda_estado'=>DB_ACTIVO);

        if($q !== ''){

            $this->db->group_start();

            $this->db->like('moda_descripcion',$q);

            $this->db->group_end();

        }

        $query = $this->db->order_by('moda_descripcion', 'asc')->where($where)->get(self::$table_menu);

        if($query->num_rows() > 0){

            return $query->result();

        }

        return null;

    }



    public function contar_estructuras_todos() {

        $this->db->where('moda_estado', DB_ACTIVO);

        return $this->db->count_all_results(self::$table_menu);

    }



    public function getModalidadByID($moda_id){

        $where = array('moda_id' => $moda_id);

        $query = $this->db->where($where)->get(self::$table_menu);

        if($query->num_rows() > 0){

            return $query->row();

        }

        return null;

    }



    public function deleteModalidadByID($moda_id){

        $where      = array('moda_id' => $moda_id);

        $data_modal = array('moda_estado' => DB_INACTIVO);

        $query      = $this->db->trans_begin();

        $query      = $this->db->where($where)->update(self::$table_menu, $data_modal);

        if ($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();

            return false;

        }

        $this->db->trans_commit();

        return true;

    }



    public function insertModalidad($data_modal){

        $query      = $this->db->trans_begin();

        $query      = $this->db->insert(self::$table_menu, $data_modal);

        if ($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();

            return false;

        }

        $this->db->trans_commit();

        return true;

    }



    public function updateModalidad($data_modal, $moda_id){

        $where      = array('moda_id' => $moda_id);

        $query      = $this->db->trans_begin();

        $query      = $this->db->where($where)->update(self::$table_menu, $data_modal);

        if ($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();

            return false;

        }

        $this->db->trans_commit();

        return true;

    }

}