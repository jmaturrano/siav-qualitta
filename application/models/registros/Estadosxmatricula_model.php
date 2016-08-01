<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Estadosxmatricula_Model extends CI_Model {
    private static $table_menu  = 'estados_x_matricula';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function insertEstadosxmatricula($data_exma){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_exma);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $exma_id = $this->db->insert_id();
        $this->db->trans_commit();
        return $exma_id;
    }

    public function getEstadosxmatriculaAllByMATR($matr_id){
        $where = array(
                    'exma.exma_estado'   =>DB_ACTIVO,
                    'exma.matr_id'       =>$matr_id
                    );
        $this->db->order_by('exma.exma_fecha_movimiento', 'desc');
        $this->db->where($where);
        $this->db->join('estados_matricula emat', 'emat.emat_id = exma.emat_id', 'inner');
        $query = $this->db->get(self::$table_menu.' exma');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

}







