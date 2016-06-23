<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Usuario_Model extends CI_Model {
    private static $table_menu  = 'usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
        $this->load->model('seguridad/clave_model');
        $this->load->model('seguridad/configuracion_model');
    }


    public function getUsuarioAll($q = '', $limit = 1000, $offset = 0){
        $this->db->limit($limit, $offset);
        $where = array('usua_estado'=>DB_ACTIVO);
        $this->db->order_by('usua_nombre', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->group_start();
            $this->db->like('usua_nombre',$q);
            $this->db->or_like('usua_numero_documento',$q);
            $this->db->group_end();
        }
        $query = $this->db->get(self::$table_menu);
        //echo "-->".$this->db->last_query();
        //exit();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function findUsuarioAll($q = ''){
        //$this->db->limit($limit, $offset);
        $where = array('usua_estado'=>DB_ACTIVO);
        $this->db->join('documento_identidad', 'usuario.dide_id=documento_identidad.dide_id');
        $this->db->order_by('usua_nombre', 'asc');
        $this->db->where($where);
        if($q !== ''){
            $this->db->like('usua_nombre',$q );            
        }
        $query = $this->db->get(self::$table_menu);
        //echo "-->".$this->db->last_query();
        //exit();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
    }

    public function contar_estructuras_todos() {
        $this->db->where('usua_estado', DB_ACTIVO);
        return $this->db->count_all_results(self::$table_menu);
    }

    public function getUsuarioByID($usua_id){
        $where = array('usua_id' => $usua_id);
        $query = $this->db->where($where)->get(self::$table_menu);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function deleteUsuarioByID($usua_id){
        $where      = array('usua_id' => $usua_id);
        $data_Usuario   = array('usua_estado' => DB_INACTIVO);
        $query      = $this->db->trans_begin();
        $query      = $this->db->where($where)->update(self::$table_menu, $data_Usuario);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function insertUsuario($data_Usuario){
        /*
        $data['data_conf']      = $this->configuracion_model->getConfigurationData();
        $conf_diasexpclave = $data['data_conf']->conf_diasexpclave;
        $fecha = new DateTime('now');
        date_add($fecha, date_interval_create_from_date_string($conf_diasexpclave.' days'));
        $data_Usuario["usua_fecha_expiracion"]= $fecha->format('Y-m-d H:i:s');
        */
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_Usuario);
        $usua_id    = $this->db->insert_id();
        if(isset($data_Usuario["usua_clave"])){
            $data_clave = array( 'usua_id' => $usua_id , 'ucla_clave' => $data_Usuario["usua_clave"]);
            $this->clave_model->insertClave($data_clave);
            //print_r($data_Usuario);
            //die();
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $usua_id;
    }

    public function updateUsuario($data_Usuario, $usua_id){        
         if(isset($data_Usuario["usua_clave"])){
            
            $data_clave = array( 'usua_id' => $usua_id , 'ucla_clave' => $data_Usuario["usua_clave"]);
            $this->clave_model->insertClave($data_clave);

            /*
            $data['data_conf']      = $this->configuracion_model->getConfigurationData();
            $conf_diasexpclave = $data['data_conf']->conf_diasexpclave;
            $fecha = new DateTime('now');
            date_add($fecha, date_interval_create_from_date_string($conf_diasexpclave.' days'));
            $data_Usuario["usua_fecha_expiracion"]= $fecha->format('Y-m-d H:i:s');
            */
        }

        $where      = array('usua_id' => $usua_id);
        $query      = $this->db->trans_begin();        
        $query      = $this->db->where($where)->update(self::$table_menu, $data_Usuario);
            
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $usua_id;
    }





}







