<?php
defined('BASEPATH') OR exit('No estan permitidos los scripts directos');

if (!function_exists('str_encrypt')) {
    function str_encrypt($string, $key)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM );
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data_bin = mcrypt_generic($td, $string);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $encrypted_data_hex = bin2hex($iv).bin2hex($encrypted_data_bin);
        return trim($encrypted_data_hex);
    }
}

if (!function_exists('str_decrypt')) {
    function str_decrypt($encrypted_data_hex, $key)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $iv_size_hex = mcrypt_enc_get_iv_size($td)*2;
        $iv = pack("H*", substr($encrypted_data_hex, 0, $iv_size_hex));
        $encrypted_data_bin = pack("H*", substr($encrypted_data_hex, $iv_size_hex));
        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted_data_bin);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return trim($decrypted);
    }
}

if (!function_exists('arr_daysofweek')) {
    function arr_daysofweek(){
        $arr_daysofweek = array(
                            0 => 'Lunes',
                            1 => 'Martes',
                            2 => 'Miércoles',
                            3 => 'Jueves',
                            4 => 'Viernes',
                            5 => 'Sábado',
                            6 => 'Domingo'
                            );
        return $arr_daysofweek;
    }
}

if (!function_exists('arr_monthsofyear')) {
    function arr_monthsofyear(){
        $arr_monthsofyear = array(
                            1   => 'Enero',
                            2   => 'Febrero',
                            3   => 'Marzo',
                            4   => 'Abril',
                            5   => 'Mayo',
                            6   => 'Junio',
                            7   => 'Julio',
                            8   => 'Agosto',
                            9   => 'Setiembre',
                            10  => 'Octubre',
                            11  => 'Noviembre',
                            12  => 'Diciembre'
                            );
        return $arr_monthsofyear;
    }
}

// CARLOS: devuelve el primer error que halla en las validaciones
if (!function_exists('primer_error_validation')) {
    function primer_error_validation() {
        $ERRORES = explode('</p>', validation_errors());
        $error = $ERRORES[0];
        $error = str_replace('<p>', '', $error);
        return str_replace('</p>', '', $error);
    }
}

// CARLOS: valida la estructura correcta de una fecha
if (!function_exists('fecha_validar')) {
    function fecha_validar($fecha) {
        imprimir($fecha);
        if ($fecha) {
            $FECHA = explode(FECHA_SEPARADOR, $fecha);
            if (count($FECHA) == 3) {
                $dia = $FECHA[0];
                $mes = $FECHA[1];
                $anho = $FECHA[2];
                if (is_numeric($anho) && is_numeric($mes) && is_numeric($dia)) {
                    return checkdate($mes, $dia, $anho);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

// CARLOS: devuelve el formato de fecha a insertar en BD
if (!function_exists('fecha_formato_insertar')) {
    function fecha_formato_insertar($fecha) {
        if ($fecha) {
            $FECHA = explode(FECHA_SEPARADOR, $fecha);
            return $FECHA[2] . '-' . $FECHA[1] . '-' . $FECHA[0];
        }
        return '';
    }
}

if (!function_exists('revisar_oficinas')) {
    function revisar_oficinas($th) {
        if (!$th->session->userdata('usua_id'))
            redirect('seguridad/login');
        $th->load->model('seguridad/oficinausuario_model');
        $OFICINAS = $th->oficinausuario_model->getOficinausuarioAll($th->session->userdata('usua_id'));
        return $OFICINAS;
    }
}

if (!function_exists('revisar_roles')) {
    function revisar_roles($th, $ofic_id) {
        if (!$th->session->userdata('usua_id'))
            //die(redirigir('seguridad/login/accesslogout'));
            redirect('seguridad/login');
        if ($th->session->userdata('ofic_id'))
            $ofic_id = $th->session->userdata('ofic_id');
        $th->load->model('seguridad/rolusuario_model');
        if($ofic_id === 0){//oficina x usuario default
            $data_uxof = revisar_oficinas($th);
            if(isset($data_uxof)){
                foreach ($data_uxof as $key => $oficinausuario) {
                    if($oficinausuario->uxof_estadodefecto === 'S'){
                        $ofic_id = $oficinausuario->ofic_id;
                        $th->session->set_userdata(array('ofic_id' => $ofic_id));
                    }//end if
                }//end foreach
            }//end if
        }//end if
        $ROLES = $th->rolusuario_model->getRolusuarioByOficina($th->session->userdata('usua_id'), $ofic_id);
        return $ROLES;
    }
}

// CARLOS: revisa si el usuario se encuentra autenticado y devuelve los privilegios que tiene
if (!function_exists('revisar_privilegios')) {
    function revisar_privilegios($th = null, $rol_id = 0) {
        if (!$th->session->userdata('usua_id'))
            redirect('seguridad/login');
        $th->load->model('seguridad/menuxrol_model');
        if($rol_id === 0){//rol de oficina default
            $data_rxus  = revisar_roles($th, 0);
            if(isset($data_rxus)){
                $rol_id     = $data_rxus->rol_id;
                $th->session->set_userdata(array('rol_id' => $rol_id));
            }
        }
        $data_mxro = $th->menuxrol_model->getMenuByRol($rol_id);
        $PRIVILEGIOS = array();
        $i = 0;
        if($data_mxro){
            foreach ($data_mxro as $item => $rolmenu) {
                if($rolmenu->mxro_accesa === '1'){
                    if($rolmenu->menu_idpadre == 0){
                        $PRIVILEGIOS[$rolmenu->menu_id]['menu_orden'] = $rolmenu->menu_orden;
                        $PRIVILEGIOS[$rolmenu->menu_id]['menu_descripcion'] = $rolmenu->menu_descripcion;
                        $PRIVILEGIOS[$rolmenu->menu_id]['menu_control_agencia'] = $rolmenu->menu_control_agencia;
                        $PRIVILEGIOS[$rolmenu->menu_id]['menu_id'] = $rolmenu->menu_id;
                        $PRIVILEGIOS[$rolmenu->menu_id]['menu_nivel'] = $rolmenu->menu_nivel;
                        $PRIVILEGIOS[$rolmenu->menu_id]['mxro_accesa'] = $rolmenu->mxro_accesa;
                    }else{
                        if($rolmenu->menu_orden == 1){
                            $i = 0;
                        }
                        if(isset($PRIVILEGIOS[$rolmenu->menu_idpadre])){
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_orden'] = $rolmenu->menu_orden;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_descripcion'] = $rolmenu->menu_descripcion;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_control_agencia'] = $rolmenu->menu_control_agencia;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_id'] = $rolmenu->menu_id;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_nivel'] = $rolmenu->menu_nivel;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['menu_formulario'] = $rolmenu->menu_formulario;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_accesa'] = $rolmenu->mxro_accesa;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_ingresa'] = $rolmenu->mxro_ingresa;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_elimina'] = $rolmenu->mxro_elimina;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_modifica'] = $rolmenu->mxro_modifica;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_consulta'] = $rolmenu->mxro_consulta;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_imprime'] = $rolmenu->mxro_imprime;
                            $PRIVILEGIOS[$rolmenu->menu_idpadre]['opcion'][$i]['permisos']['mxro_exporta'] = $rolmenu->mxro_exporta;
                        }
                        $i++;
                    }
                }//end if
            }//end foreach
        }//end if
        return $PRIVILEGIOS;
    }
}

// CARLOS: devuelve una fecha en formato latino
if (!function_exists('fecha_latino')) {
    function fecha_latino($fecha) {
        if ($fecha)
            return date('d/m/Y', strtotime($fecha));
        return '';
    }
}

// CARLOS: devuelve una fecha y hora en formato latino
if (!function_exists('fecha_y_hora_latino')) {
    function fecha_y_hora_latino($fecha) {
        if ($fecha)
            return date('d/m/Y H:i', strtotime($fecha));
        return '';
    }
}

// CARLOS: devuelve la descripcion de un indicador de vigencia
if (!function_exists('describir_vigencia')) {
    function describir_vigencia($vigencia) {
        switch ($vigencia) {
            case 'S':
                return 'Vigente';
            case 'N':
                return 'No vigente';
            default: return $vigencia;
        }
    }
}

// CARLOS: devuelve la descripcion de un estado
if (!function_exists('describir_estado')) {
    function describir_estado($estado) {
        switch ($estado) {
            case 'RE':
                return 'Registrado';
            case 'MO':
                return 'Modificado';
            default: return $estado;
        }
    }
}

// CARLOS: valida que el valor pasado como parametro sea un numero entero
if (!function_exists('validar_entero')) {
    function validar_entero($cad) {
        $cad = trim($cad);
        return ctype_digit($cad);
    }
}

// CARLOS: devuelve el ultimo id del objeto insertado en BD
if (!function_exists('ultimo_id_insertado')) {
    function ultimo_id_insertado($th) {
        return $th->db->insert_id();
    }
}

// CARLOS: 
if (!function_exists('paginacion_configurar')) {
    function paginacion_configurar($propio, $th) {
        $config['per_page'] = CANTIDAD_FILAS_PAGINACION;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<<';
        $config['last_link'] = '>>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '<';
        $config['next_link'] = '>';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        // parametros de configuracion propio
        $config['base_url'] = base_url($propio['ruta_base']);
        $propio['filas_totales'] > CANTIDAD_FILAS_PAGINACION ? $config['total_rows'] = $propio['filas_totales'] : $config['total_rows'] = CANTIDAD_FILAS_PAGINACION;
        // inicializamos la paginacion
        $th->pagination->initialize($config);
        return CANTIDAD_FILAS_PAGINACION;
    }
}

if (!function_exists('revisar_permisos')) {
    function revisar_permisos($PRIVILEGIOS = array(), $opcion_menu) {
        $PERMISOS = array();
        //$PERMISOS = new stdClass();
        foreach ($PRIVILEGIOS as $key => $privilegio) {
            if(isset($privilegio["opcion"])){
                foreach($privilegio["opcion"] as $subkey => $item) {
                    if(strtoupper(trim($item["menu_formulario"])) === strtoupper(trim($opcion_menu))){
                        $PERMISOS = $item["permisos"];
                    }
                }//end foreach
            }//end if
        }//end foreach
        return $PERMISOS;
    }
}

if(!function_exists('describe_menu_nivel')){
    function describe_menu_nivel($menu_nivel){
        switch ($menu_nivel) {
            case '1':
                return 'Menú';
            case '2':
                return 'Opción';
            default: return $menu_nivel;
        }
    }
}


if(!function_exists('describe_estado_aprobacion')){
    function describe_estado_aprobacion($estado_aprobacion){
        switch ($estado_aprobacion) {
            case '1':
                return 'Abierto';
            case '0':
                return 'Cerrado';
            default: return $estado_aprobacion;
        }
    }
}

if(!function_exists('upload_file_foto')){
    function upload_file_foto($th = null, $nombre_imagen, $directorio){
        $config['upload_path']      = IMG_PATH.$directorio.'/';
        $config_key                 = date("YmdHis").rand(100, 999);
        $config['file_name']        = $config_key;
        $config['allowed_types']    = "gif|jpg|jpeg|png";
        $config['max_size']         = "50000";
        $config['max_width']        = "2000";
        $config['max_height']       = "2000";
        $th->load->library('upload', $config);
        if (!$th->upload->do_upload($nombre_imagen)) {
            //*** ocurrio un error
            $uploadError = $th->upload->display_errors();
            return $uploadError;
        }
        $uploadSuccess = $th->upload->data();
        return $uploadSuccess;
    }
}

if(!function_exists('enviar_email')){
    function enviar_email($th = null, $email_to, $email_subject, $email_message){
        $th->load->library('email');
        $th->email->set_mailtype("html");
        $th->email->from(MAILPRODUCTO, PRODUCTO);
        $th->email->to(trim($email_to));
        $th->email->subject($email_subject);
        $th->email->message($email_message);
        if($th->email->send()){
            return true;
        }//end if
        return false;
    }
}

if(!function_exists('reemplazar_palabras_reservadas')){
    function reemplazar_palabras_reservadas($th, $rema_descripcion, $data_palabras_reservadas){
        $th->load->model('seguridad/configuracion_model');
        $data_conf = $th->configuracion_model->getConfigurationData();
        /*
        *
        * Palabras reservadas:
        * [ALUMNO]:Nombre de alumno
        * [COSTO]: Costo total del curso
        * [CUMPLEANOS]: Personal de cumpleaños
        * [CURSO]: Nombre de programa de instrucción
        * [DIRECCION]: Dirección de alumnp
        * [EMPRESA]: Nombre de la empresa
        * [MATRICULA]: Código de matrícula
        * [PRODUCTO]: Nombre del sistema
        * [TABLACUMPLEANOS]: Lista de alumnos que cumplen años del día
        * [URLALUMNO]: --
        * [URLMATRICULA]: --
        *
        */

        if(count($data_palabras_reservadas) > 0){
            $data_palabras_reservadas['[PRODUCTO]'] = PRODUCTO;
            $data_palabras_reservadas['[EMPRESA]'] = $data_conf->conf_nombre;
            foreach ($data_palabras_reservadas as $reservada => $equivalente) {
                if(strrpos($rema_descripcion, $reservada) !== FALSE){
                    $rema_descripcion = str_replace($reservada, $equivalente, $rema_descripcion);
                }//end if
            }//end foreach
        }//end if
        return $rema_descripcion;
    }
}

if(!function_exists('interpretar_booleanchar')){
    function interpretar_booleanchar($booleanchar){
        $truefalse_var = '';
        switch ($booleanchar) {
            case 'S':
                $truefalse_var = 'Sí';
                break;
            case 'N':
                $truefalse_var = 'No';
                break;
            default:
                $truefalse_var = '';
                break;
        }//end switch
        return $truefalse_var;
    }
}

if(!function_exists('registrar_estado_alumno')){
    function registrar_estado_alumno($th = null, $data_exal, $esal_codigo){

        /*
        *
        * Por defecto debería ser:
        * 01 REGISTRADO
        * 02 PENDIENTE MATRICULA
        * 03 MATRICULADO
        * 04 ESTUDIANTE
        * 05 GRADUADO
        *
        */

        $th->load->model('registros/estadoalumno_model');
        $th->load->model('registros/estadosxalumno_model');

        $req_exal = false;
        $data_esal = $th->estadoalumno_model->getEstadoalumnoByCOD($esal_codigo);
        if(isset($data_esal)){
            $esal_id = $data_esal->esal_id;
            $data_exal['esal_id'] = $esal_id;
            $req_exal = $th->estadosxalumno_model->insertEstadosxalumno($data_exal);

            /* CAMBIAR ESTADO ALUMNO */
            $data_alum = array(
                    'esal_id' => $esal_id
                );
            cambiar_estado_alumno($th, $data_alum, $data_exal['alum_id']);
            /* CAMBIAR ESTADO ALUMNO - END */

        }//end if
        return $req_exal;
    }
}

if(!function_exists('cambiar_estado_alumno')){
    function cambiar_estado_alumno($th, $data_alum, $alum_id){
        $th->load->model('registros/alumno_model');
        return $th->alumno_model->updateAlumno($data_alum, $alum_id);
    }
}

if(!function_exists('registrar_estado_matricula')){
    function registrar_estado_matricula($th = null, $data_exma, $emat_codigo){

        /*
        *
        * Estados de matricula:
        * 01 Por aprobar
        * 02 En proceso de pago
        * 03 Matrícula cancelada
        * 04 Matrícula pagada
        *
        */

        $th->load->model('registros/estadomatricula_model');
        $th->load->model('registros/estadosxmatricula_model');

        $req_exma = false;
        $data_emat = $th->estadomatricula_model->getEstadomatriculaByCOD($emat_codigo);
        if(isset($data_emat)){
            $emat_id = $data_emat->emat_id;
            $data_exma['emat_id'] = $emat_id;
            $req_exma = $th->estadosxmatricula_model->insertEstadosxmatricula($data_exma);

            /* CAMBIAR ESTADO MATRICULA */
            $data_matr = array(
                    'emat_id' => $emat_id
                );
            cambiar_estado_matricula($th, $data_matr, $data_exma['matr_id']);
            /* CAMBIAR ESTADO MATRICULA - END */

        }//end if
        return $req_exma;
    }
}

if(!function_exists('cambiar_estado_matricula')){
    function cambiar_estado_matricula($th, $data_matr, $matr_id){
        $th->load->model('servicios/matricula_model');
        return $th->matricula_model->updateMatricula($data_matr, $matr_id);
    }
}

if(!function_exists('procesar_horas')){
    function procesar_horas($operacion, $hora_inicial, $hora_operacion){
        $hh = 0;
        $mm = 0;
        $ss = 0;
        if($operacion === 'suma'){
            if($hora_inicial != 0 && $hora_inicial != ''){
                $horas_i = explode(':', $hora_inicial);
                $hh = $horas_i[0];
                $mm = $horas_i[1];
            }
            if($hora_operacion != ''){
                $horas_o = explode(':', $hora_operacion);
                $hh += $horas_o[0];
                $mm += $horas_o[1];
            }
        }
        //echo $hh.':'.$mm.':'.$ss."<br>";
        //echo date('H:i:s', strtotime($hh.':'.$mm.':'.$ss));
        //exit();
        return date('H:i:s', strtotime($hh.':'.$mm.':'.$ss));
    }
}


if(!function_exists('array_unidades_tiempo')){
    function array_unidades_tiempo(){
        $tiempo = array('DIA', 'MES', 'ANO');
        return $tiempo;
    }
}

