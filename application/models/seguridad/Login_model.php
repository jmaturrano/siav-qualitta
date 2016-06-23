<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Model extends CI_Model {
    private static $table_user  = 'usuario';

    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

    public function verificaDocIdent($docidentidad){
        $where = array('usua_numero_documento'=>$docidentidad, 'usua_estado'=>DB_ACTIVO);
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_user);
        if($query->result_id->num_rows === 1){
            return $query->row();
        }
        return false;
    }

    public function verificaAcceso($data_user){
        $where = array(
                    'usua_numero_documento' => $data_user['usua_numero_documento'],
                    'usua_clave' => $data_user['usua_clave'],
                    'usua_estado'=>DB_ACTIVO
                    );
        $query = $this->db->where($where);
        $query = $this->db->get(self::$table_user);
        if($query->result_id->num_rows === 1){
            return $query->row();
        }
        return false;
    }

    public function createSession($usua_id, $data_login) {
        $query = $this->db->where('usua_id',$usua_id);
        $query = $this->db->update(self::$table_user,$data_login);
        if($query){
            return true;
        }
        return false;
    }

    public function destroySession($usua_id, $data_logout) {
        $this->createSession($usua_id, $data_logout);
    }


}

