<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Reportexusuario_Model extends CI_Model {
    private static $table_menu  = 'reporte_x_usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function getUsuariosxReportexusuarioAll($rema_id = ''){

        $where = array(
                    'rexu.rexu_estado'=>DB_ACTIVO,
                    'usua.usua_estado'=>DB_ACTIVO,
                    'rema.rema_estado'=>DB_ACTIVO,
                    'rema.rema_id'=>$rema_id
                    );
        $this->db->order_by('usua.usua_apellido', 'asc');
        $this->db->order_by('usua.usua_nombre', 'asc');
        $this->db->where($where);

        $this->db->join('reportes_mail rema', 'rema.rema_id = rexu.rema_id', 'inner');
        $this->db->join('usuario usua', 'usua.usua_id = rexu.usua_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rexu');
        //echo "-->".$this->db->last_query();
        //exit();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }


    public function deleteReportexusuarioByID($rexu_id){
        $where      = array('rexu_id' => $rexu_id);
        $data_rexu   = array('rexu_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_rexu);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertReportexusuario($data_rexu){
        $this->db->trans_begin();
        $this->db->insert(self::$table_menu, $data_rexu);
        $rexu_id    = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $rexu_id;
    }

    public function getReportexusuarioAllByREMACOD($rema_codigo){

        /*
        *
        * Por defecto debería ser:
        * 01 REGISTRO DE ALUMNO NUEVO
        * 02 REGISTRO DE MATRICULA NUEVA
        * 03 SALUDOS POR CUMPLEAÑOS
        * 04 UN ALUMNO CUMPLE AÑOS HOY, SALUDALO!
        *
        */

        $where = array(
                    'rexu.rexu_estado'=>DB_ACTIVO,
                    'usua.usua_estado'=>DB_ACTIVO,
                    'rema.rema_estado'=>DB_ACTIVO,
                    'rema.rema_codigo'=>$rema_codigo
                    );
        $this->db->where($where);
        $this->db->join('reportes_mail rema', 'rema.rema_id = rexu.rema_id', 'inner');
        $this->db->join('usuario usua', 'usua.usua_id = rexu.usua_id', 'inner');
        $query = $this->db->get(self::$table_menu.' rexu');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }



}







