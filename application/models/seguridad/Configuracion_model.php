<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_Model extends CI_Model {
    private static $table_menu  = 'conf_general';
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }

   

    public function field_data(){
        return $this->db->field_data(self::$table_menu);
    }
    
    public function getConfigurationData(){
        $query = $this->db->get(self::$table_menu);
        
        if($query->num_rows() > 0){
            return $query->row();
        }
        return null;
    }

    public function updateConfiguration($data_conf, $conf_id){
    	$where = array('conf_id' => $conf_id);
    	$query = $this->db->where($where)->update(self::$table_menu, $data_conf);
        if($query){
            return true;
        }
        return false;
    }





}







