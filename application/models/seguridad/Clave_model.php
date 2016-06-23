<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Clave_Model extends CI_Model {
    private static $table_menu  = 'usuario_clave';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

  

    public function insertClave($data_clave){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$table_menu, $data_clave);
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return true;
    }

    



}







