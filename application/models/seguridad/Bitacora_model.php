<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Bitacora_Model extends CI_Model {
    private static $bitacora          = 'bitacora';
    private static $bitacora_usuario  = 'bitacora_usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   public function insertBitacora($data_bita){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$bitacora, $data_bita);
        $id         = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return $id;
    }

    public function insertBitacoraUsuario($data_bius){
        $query      = $this->db->trans_begin();
        $query      = $this->db->insert(self::$bitacora_usuario, $data_bius);
        $id         = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        
        $this->db->trans_commit();
        return true;
    }



}







