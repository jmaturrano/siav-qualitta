<?php
defined('BASEPATH') OR exit('No estan permitidos los scripts directos');
 
class Reporte_Model extends CI_Model {
 
    public function __construct() {
        parent :: __construct();
        $this->load->database();
    }
 
 
 
    public function getReporteProyectos( $ogru_id_array = array() , $ofic_id_array = array() , 
                                $peri_id = '', $fecha_reporte='',
                                 $poli_id_array = array() , $ppre_id_array = array() ,
                                 $prod_id_array = array() ,
                                 $acti_id_array = array() , $sact_id_array = array() ,
                                 $estr_id_array = array()
                                ){
        $this->db->select(" poa.ofic_id, 
                            poa.poa_id,                                                         
                            poa.pite_id,
                            poa.poa_siaf,
                            poa.poa_snip,
                            poa.sact_id as poa_sact_id,
                            poa.poa_nomproyecto,
                            poa.poa_metafispia,
                            poa.poa_metaprepia,
                            poa.poa_metafispim,
                            poa.poa_metaprepim,
                            poa.poa_metaprepid,
                            poa.poa_metaprepig,
                            estado_proyecto.epro_nombre,
                            oficina_grupo.ogru_id,
                            oficina_grupo.ogru_nombre,
                            oficina.ofic_nombre,
                            usuario.usua_nombre,                            
                            rol.rol_nombre,                               
                            categoria_actividad.poli_id,
                            categoria_actividad.ppre_id,
                            categoria_actividad.prod_id,
                            categoria_actividad.acti_id,
                            programa_presupuestal.ppre_codref,
                            programa_presupuestal.ppre_nombre,
                            producto.prod_codref,
                            producto.prod_nombre,
                            actividad.acti_codref,
                            actividad.acti_nombre,
                            sub_actividad.sact_id,  
                            sub_actividad.sact_nombre,
                            periodo.peri_id,
                            unidad_medida.umed_id,
                            unidad_medida.umed_nombre,                            
                            poameta_fisica.pmfi_orden,
                            poameta_fisica.pmfi_meta,
                            poameta_fisica.pmfi_tipo,                                                        
                            distrito.dist_id,
                            distrito.dist_descripcion,
                            provincia.prov_id,
                            provincia.prov_descripcion,
                            departamento.depa_id,
                            departamento.depa_descripcion")
                 ->from("poa"); 
        $this->db->join('estado_proyecto', 'estado_proyecto.epro_id = poa.epro_id');
        $this->db->join('estructura_linea', 'estructura_linea.elin_id = poa.elin_id');
        $this->db->join('estructura', 'estructura_linea.estr_id = estructura.estr_id');
        $this->db->join('oficina', 'oficina.ofic_id = poa.ofic_id');
        $this->db->join('oficina_grupo', 'oficina_grupo.ogru_id = oficina.ogru_id'); 
        $this->db->join('oficina_x_usuario', 'oficina_x_usuario.ofic_id = oficina.ofic_id');        
        $this->db->join('usuario', 'usuario.usua_id = oficina_x_usuario.usua_id');
        $this->db->join('rol_x_usuario', 'usuario.usua_id = rol_x_usuario.usua_id AND rol_x_usuario.uxof_id=oficina_x_usuario.uxof_id');
        $this->db->join('rol', 'rol.rol_id = rol_x_usuario.rol_id');      
        $this->db->join('periodo_item', 'periodo_item.pite_id = poa.pite_id');
        $this->db->join('periodo', 'periodo.peri_id = periodo_item.peri_id' );
        $this->db->join('categoria_actividad', 'categoria_actividad.cact_id = poa.cact_id' );        
        $this->db->join('programa_presupuestal', 'categoria_actividad.ppre_id = programa_presupuestal.ppre_id' );
        $this->db->join('producto', 'categoria_actividad.prod_id = producto.prod_id' );
        $this->db->join('actividad', 'categoria_actividad.acti_id = actividad.acti_id' );
        $this->db->join('sub_actividad', 'categoria_actividad.cact_id = sub_actividad.cact_id' );
        $this->db->join('unidad_medida', 'unidad_medida.umed_id = poa.umed_id' );
        $this->db->join('poameta_fisica', 'poameta_fisica.poa_id = poa.poa_id' );
        $this->db->join('centro_poblado', 'centro_poblado.cepo_id = poa.cepo_id' );
        $this->db->join('distrito', 'distrito.dist_id = centro_poblado.dist_id' );
        $this->db->join('provincia', 'provincia.prov_id = distrito.prov_id' );
        $this->db->join('departamento', 'departamento.depa_id = provincia.depa_id' );
        $this->db->where(array('periodo.peri_id' => $peri_id)); 
        $this->db->where('poa_fecinicio <=', $fecha_reporte); 
        $this->db->where('poa.poa_estado !=', DB_INACTIVO );  
        $this->db->where('estructura_linea.elin_estado !=', DB_INACTIVO );
        $this->db->where('estructura.estr_estado !=', DB_INACTIVO );        
        $this->db->where('oficina.ofic_estado !=', DB_INACTIVO );                 
        $this->db->where('oficina_grupo.ogru_estado !=', DB_INACTIVO ); 
        $this->db->where('oficina_x_usuario.uxof_estado !=', DB_INACTIVO );        
        $this->db->where('usuario.usua_estado !=', DB_INACTIVO );         
        $this->db->where('rol_x_usuario.rxus_estado !=', DB_INACTIVO );         
        $this->db->where('rol.rol_estado !=', DB_INACTIVO );         
        $this->db->where('usuario.usua_estado !=', DB_INACTIVO );      
        $this->db->where('periodo_item.pite_estado !=', DB_INACTIVO ); 
        $this->db->where('periodo.peri_estado !=', DB_INACTIVO );         
        $this->db->where('categoria_actividad.cact_estado !=', DB_INACTIVO ); 
        $this->db->where('programa_presupuestal.ppre_estado !=', DB_INACTIVO ); 
        $this->db->where('producto.prod_estado !=', DB_INACTIVO ); 
        $this->db->where('actividad.acti_estado !=', DB_INACTIVO ); 
        $this->db->where('sub_actividad.sact_estado !=', DB_INACTIVO ); 
        $this->db->where('unidad_medida.umed_estado !=', DB_INACTIVO ); 
        $this->db->where('poameta_fisica.pmfi_estado !=', DB_INACTIVO );         
        $this->db->where('centro_poblado.cepo_estado !=', DB_INACTIVO ); 
        $this->db->where('distrito.dist_estado !=', DB_INACTIVO ); 
        $this->db->where('provincia.prov_estado !=', DB_INACTIVO ); 
        $this->db->where('departamento.depa_estado !=', DB_INACTIVO ); 
 
        if(count($estr_id_array)>0){
            $this->db->where_in( 'estructura.estr_id', $estr_id_array);
        } 
        if(count($ogru_id_array)>0){
            $this->db->where_in( 'oficina_grupo.ogru_id', $ogru_id_array);
        }       
        if(count($ofic_id_array)>0){
            $this->db->where_in( 'poa.ofic_id', $ofic_id_array);
        }
        if(count($poli_id_array)>0){
            $this->db->where_in( 'categoria_actividad.poli_id', $poli_id_array);
        }
        if(count($ppre_id_array)>0){
            $this->db->where_in( 'categoria_actividad.ppre_id', $ppre_id_array);
        }
        if(count($prod_id_array)>0){
            $this->db->where_in( 'categoria_actividad.prod_id', $prod_id_array);
        }
        if(count($acti_id_array)>0){
            $this->db->where_in( 'categoria_actividad.acti_id', $acti_id_array);
        }
        if(count($sact_id_array)>0){
            $this->db->where_in( 'poa.sact_id', $sact_id_array);
        }

        $this->db->order_by("programa_presupuestal.ppre_codref", "asc"); 
        $this->db->limit(100);
        
        $query =  $this->db->get();
        //print_r($this->db->last_query()); die();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return null;
 
    }
 
 
 
 
     
  
 
}


